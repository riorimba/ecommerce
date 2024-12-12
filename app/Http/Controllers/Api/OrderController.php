<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json($orders);
    }

    public function show($orderId)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->with('orderItems.product')->findOrFail($orderId);

        return response()->json($order);
    }
}
