<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;


class CartController extends Controller
{
    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return response()->json(['message' => 'Product added to cart', 'cart' => $cart], 201);
    }

    public function deleteProduct($productId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Product removed from cart']);
        }

        return response()->json(['message' => 'Product not found in cart'], 404);
    }

    public function countProducts(Request $request)
    {
        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->paginate(10); 

        return response()->json([
            'count' => $cartItems->total(),
            'products' => $cartItems->items(),
            'pagination' => [
                'current_page' => $cartItems->currentPage(),
                'last_page' => $cartItems->lastPage(),
                'per_page' => $cartItems->perPage(),
                'total' => $cartItems->total(),
                'next_page_url' => $cartItems->nextPageUrl(),
                'prev_page_url' => $cartItems->previousPageUrl(),
            ],
        ]);
    }

    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();
        // $cartItems = $user->carts;

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'subtotal' => $cartItem->quantity * $cartItem->product->price,
                'product_name' => $product->name,
                'product_price' => $product->price,
            ]);

            $product = $cartItem->product;
            $product->stock -= $cartItem->quantity;
            $product->save();
        }

        $admins = User::where('role_id', 1)->get();
        Notification::send($admins, new NewOrderNotification($order->order_id, $order->id, $order->total));

        Cart::where('user_id', $user->id)->delete();

        Mail::to($user->email)->send(new OrderConfirmationMail($order));
        
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => $cartItems->map(function ($cartItem) {
                return [
                    'id' => $cartItem->product_id,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'name' => $cartItem->product->name,
                ];
            })->toArray(),
        ];

        try {
            $snapResponse = Snap::createTransaction($params);
            return response()->json([
                'snap_token' => $snapResponse->token,
                'redirect_url' => $snapResponse->redirect_url
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Checkout successful', 'order' => $order], 201);
    }
}