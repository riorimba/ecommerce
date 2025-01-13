<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans\Config;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


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

    public function callback(Request $request){
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . config('midtrans.server_key'));
        
        if ($hashed == $request->signature_key) {
            // Perbarui status order berdasarkan status transaksi
            $order = Order::where('order_id', $request->order_id)->firstOrFail();
            $order->payment_type = $request->payment_type;
            $order->transaction_time = $request->transaction_time;
            $order->transaction_id = $request->transaction_id;
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order->status = 'paid';
                $order->pay_at = now();
            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                $order->status = 'cancelled';
            } elseif ($request->transaction_status == 'pending') {
                $order->status = 'pending';
            }
            $order->save();

            $users = User::all();
            Notification::send($users, new OrderStatusChangedNotification($order->order_id, $order->status));

            return response()->json(['message' => 'Order status updated successfully.']);
        } else {
            return response()->json(['message' => 'Invalid signature.'], 400);
        }
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    public function downloadInvoice($id)
    {
        $order = Order::with('user', 'orderItems.product')->findOrFail($id);
        if (Auth::id() !== $order->user_id && Auth::user()->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $pdf = PDF::loadView('invoice', compact('order'));

        return $pdf->download('invoice.pdf');
    }
}