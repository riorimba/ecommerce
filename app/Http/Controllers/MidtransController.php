<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat instance notifikasi
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        // Cari order berdasarkan ID
        $order = Order::findOrFail($orderId);

        // Perbarui status order berdasarkan status transaksi
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->status = 'paid';
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->status = 'cancelled';
        } elseif ($transactionStatus == 'pending') {
            $order->status = 'pending';
        }

        // Simpan perubahan status order
        $order->save();

        return response()->json(['message' => 'Notification handled successfully']);
    }
}
