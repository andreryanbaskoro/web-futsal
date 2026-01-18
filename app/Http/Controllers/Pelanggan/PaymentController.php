<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * CREATE SNAP TOKEN
     */
    public function createSnap(Request $request)
    {
        $request->validate([
            'kode' => 'required|string'
        ]);

        $pemesanan = Pemesanan::where('kode_pemesanan', $request->kode)
            ->where('status_pemesanan', 'pending')
            ->firstOrFail();

        // 🔥 ORDER ID HARUS STABIL
        $orderId = 'ORDER-' . $pemesanan->kode_pemesanan;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $pemesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => optional($pemesanan->pengguna)->nama ?? 'Guest',
                'email' => optional($pemesanan->pengguna)->email ?? 'guest@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // 🔥 CEGAH DUPLIKASI PEMBAYARAN
        Pembayaran::updateOrCreate(
            ['id_pemesanan' => $pemesanan->id_pemesanan],
            [
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'tipe_pembayaran' => 'snap',
                'status_transaksi' => 'pending',
            ]
        );

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
