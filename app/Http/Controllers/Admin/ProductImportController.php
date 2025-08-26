<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file)[0]; // Получаем все данные как массив

            if (empty($data) || count($data) < 2) {
                return redirect()->back()->with('error', 'Файл пуст или содержит только заголовки');
            }

            $headers = array_map('strtolower', $data[0]); // Заголовки в нижнем регистре
            $rows = array_slice($data, 1); // Данные без заголовков

            $imported = 0;
            $errors = [];
            
            
            DB::beginTransaction();
            
            foreach ($rows as $index => $row) {
                try {
                    $productData = $this->mapRowToProduct($headers, $row);
                   
                    if ($productData) {
                        if (isset($productData['code'])) {
                            $product = Product::where('code', $productData['code'])->first();
                            if ($product) {
                                $product->update($productData);
                            } else {
                                $product = Product::create($productData);
                            }
                            $imported++;
                            $product['stock'] = $productData['stock'];
                            $this->addWarehouseStock($product);
                        } 
                        
                        // Добавляем остатки на складах
                        //
                        
                        
                    }
                } catch (\Exception $e) {
                    $errors[] = "Строка " . ($index + 2) . ": " . $e->getMessage();
                    Log::error("Import error on row " . ($index + 2) . ": " . $e->getMessage());
                }
            }

            DB::commit();

            $message = "Импортировано товаров: $imported";
            if (!empty($errors)) {
                $message .= ". Ошибки: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " и еще " . (count($errors) - 5) . " ошибок";
                }
            }

            return redirect('/admin/import/products')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import failed: " . $e->getMessage());
            
            return redirect()->back()->with('error', 'Ошибка импорта: ' . $e->getMessage());
        }
    }

    private function mapRowToProduct($headers, $row)
    {
        $mapping = [
            'название' => 'name',
            'name' => 'name',
            'описание' => 'description',
            'description' => 'description',
            'цена' => 'price',
            'price' => 'price',
            'категория' => 'category_name',
            'category' => 'category_name',
            'бренд' => 'brand_name',
            'brand' => 'brand_name',
            'в наличии' => 'in_stock',
            'in_stock' => 'in_stock',
            'Код' => 'code',
            'code' => 'code',
        ];

        $productData = [];
        
        foreach ($headers as $index => $header) {
            $header = trim(strtolower($header));
            
            if (isset($mapping[$header]) && isset($row[$index])) {
                $field = $mapping[$header];
                $value = trim($row[$index]);
                
                if ($field === 'category_name') {
                    $category = Category::where('name', 'like', "%$value%")->first();
                    if ($category) {
                        $productData['category_id'] = $category->id;
                    }
                } elseif ($field === 'brand_name') {
                    $brand = Brand::where('name', 'like', "%$value%")->first();
                    if ($brand) {
                        $productData['brand_id'] = $brand->id;
                    }
                } elseif ($field === 'price') {
                    $productData['price'] = (float) preg_replace('/[^0-9.]/', '', $value);
                } elseif ($field === 'in_stock') {
                    $productData['in_stock'] = $value > 0 ? true : false;
                    $productData['stock'] = $value;
                } elseif ($field === 'code') {
                    $productData['code'] = $value;
                } else {
                    $productData[$field] = $value;
                }

            }
        }

        // Проверяем обязательные поля
        if (empty($productData['name'])) {
            throw new \Exception('Название товара обязательно');
        }

        if (empty($productData['price'])) {
            $productData['price'] = 0;
        }

        if (!isset($productData['in_stock'])) {
            $productData['in_stock'] = true;
        }

        return $productData;
    }

    private function addWarehouseStock($product)
    {
        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {
            $stock = $warehouse->name === 'Актау' ? $product->stock : 0; // По умолчанию
            $product->warehouses()->attach($warehouse->id, ['stock' => $stock]);
        }
    }
}
