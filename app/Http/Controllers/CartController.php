<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        // Обновляем количество товаров в сессии
        session(['cart_count' => $cart->items_count]);

        // Получаем информацию о скидках для каждого товара
        $discountInfo = $this->getCartDiscountInfo($cart);

        return view('cart.index', compact('cart', 'discountInfo'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart();
        
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        // Обновляем количество товаров в сессии
        session(['cart_count' => $cart->fresh()->items_count]);

        return redirect()->back()->with('success', __('app.add_to_cart'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        // Обновляем количество товаров в сессии
        $cart = $this->getOrCreateCart();
        session(['cart_count' => $cart->fresh()->items_count]);

        return redirect()->back()->with('success', __('app.cart_updated'));
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        // Обновляем количество товаров в сессии
        $cart = $this->getOrCreateCart();
        session(['cart_count' => $cart->fresh()->items_count]);

        return redirect()->back()->with('success', __('app.remove_from_cart'));
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        // Обновляем количество товаров в сессии
        session(['cart_count' => 0]);

        return redirect()->back()->with('success', __('app.cart_cleared'));
    }

    private function getOrCreateCart()
    {
        $sessionId = session()->getId();
        
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
            $cart = Cart::create(['session_id' => $sessionId]);
        }
        
        return $cart;
    }

    private function getCartDiscountInfo($cart)
    {
        $user = Auth::user();
        $discountInfo = [];

        foreach ($cart->items as $item) {
            $product = $item->product;
            
            if ($user) {
                $discount = $product->getPersonalDiscountForUser($user->id);
                
                if ($discount) {
                    $discountedPrice = $product->getDiscountedPrice($user->id);
                    $discountInfo[$item->id] = [
                        'has_discount' => true,
                        'original_price' => $product->price,
                        'discounted_price' => $discountedPrice,
                        'discount_percent' => $discount->discount_percent,
                        'description' => $discount->description,
                        'total_original' => $product->price * $item->quantity,
                        'total_discounted' => $discountedPrice * $item->quantity,
                        'saved_amount' => ($product->price - $discountedPrice) * $item->quantity
                    ];
                } else {
                    $discountInfo[$item->id] = [
                        'has_discount' => false,
                        'original_price' => $product->price,
                        'discounted_price' => $product->price,
                        'discount_percent' => 0,
                        'total_original' => $product->price * $item->quantity,
                        'total_discounted' => $product->price * $item->quantity,
                        'saved_amount' => 0
                    ];
                }
            } else {
                $discountInfo[$item->id] = [
                    'has_discount' => false,
                    'original_price' => $product->price,
                    'discounted_price' => $product->price,
                    'discount_percent' => 0,
                    'total_original' => $product->price * $item->quantity,
                    'total_discounted' => $product->price * $item->quantity,
                    'saved_amount' => 0
                ];
            }
        }

        return $discountInfo;
    }
}
