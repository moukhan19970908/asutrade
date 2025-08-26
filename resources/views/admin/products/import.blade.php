@extends('layouts.app')

@section('title', 'Импорт товаров')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Импорт товаров</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Выберите файл Excel (xls, xlsx)
                    </label>
                    <input type="file" 
                           name="file" 
                           id="file" 
                           accept=".xls,.xlsx" 
                           required 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Поддерживаемые колонки:</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li>• <strong>Название</strong> / <strong>Name</strong> - обязательное поле</li>
                            <li>• <strong>Описание</strong> / <strong>Description</strong> - необязательное поле</li>
                            <li>• <strong>Цена</strong> / <strong>Price</strong> - числовое значение</li>
                            <li>• <strong>Категория</strong> / <strong>Category</strong> - должна существовать в базе</li>
                            <li>• <strong>Бренд</strong> / <strong>Brand</strong> - должен существовать в базе</li>
                            <li>• <strong>В наличии</strong> / <strong>In Stock</strong> - Да/Yes/1/True или Нет/No/0/False</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Пример файла:</h3>
                    <div class="bg-gray-50 p-4 rounded-md overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2">Название</th>
                                    <th class="text-left p-2">Описание</th>
                                    <th class="text-left p-2">Цена</th>
                                    <th class="text-left p-2">Категория</th>
                                    <th class="text-left p-2">Бренд</th>
                                    <th class="text-left p-2">В наличии</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-2">Воздушный фильтр AF-123</td>
                                    <td class="p-2">Высококачественный фильтр</td>
                                    <td class="p-2">650</td>
                                    <td class="p-2">Воздушный фильтр</td>
                                    <td class="p-2">Shell</td>
                                    <td class="p-2">Да</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="/admin/resource/products" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Назад к товарам
                    </a>
                    
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Импортировать
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Импортируем...';
});
</script>
@endsection 