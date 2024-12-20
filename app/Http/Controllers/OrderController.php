<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with('user')->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order){
        $order->load('user', 'orderItems.product');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order, User $user){
        return view('orders.edit', compact('order', 'user'));
    }

    public function update(Request $request, Order $order){
        $validatedData = $request->validate([
            'status' => 'required|string',
        ]);

        $order->update($validatedData);

        return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order){
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}