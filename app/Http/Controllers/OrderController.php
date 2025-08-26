<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class OrderController extends Controller
{
    public function checkout()
    {
        // Проверяем авторизацию
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему для оформления заказа');
        }

        $user = Auth::user();
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        // Проверяем корзину
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        return view('orders.checkout', compact('user', 'cart'));
    }

    public function store(Request $request)
    {
        // Проверяем авторизацию
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему для оформления заказа');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . time() . '-' . Auth::id(),
                'status' => 'pending',
                'total_amount' => $cart->total,
                'notes' => $request->notes,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_country' => $request->shipping_country,
                'shipping_city' => $request->shipping_city,
                'shipping_address' => $request->shipping_address,
                'shipping_postal_code' => $request->shipping_postal_code,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Заказ успешно оформлен!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при оформлении заказа. Попробуйте еще раз.');
        }
    }

    public function show(Order $order)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
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
}
