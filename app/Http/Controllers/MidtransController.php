<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Pembayaran;
use App\Models\Pemesanan;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
    }

    public function notification(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;
        $statusCode = $notif->status_code; // string

        // temukan Pembayaran by order_id
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            // optional: log & 404
            return response('Not Found', 404);
        }

        // update status_transaksi
        $pembayaran->status_transaksi = $transaction;
        $pembayaran->tipe_pembayaran = $paymentType;
        $pembayaran->response_midtrans = $notif; // laravel akan cast to array
        $pembayaran->save();

        $pemesanan = $pembayaran->pemesanan;

        // Map status -> tindakan
        if ($transaction == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($fraud == 'challenge') {
                    // payment challenged (manual)
                    $pembayaran->status_transaksi = 'challenge';
                    $pembayaran->save();
                } else {
                    // success
                    $pembayaran->status_transaksi = 'capture';
                    $pembayaran->save();

                    $pemesanan->status_pemesanan = 'dibayar';
                    $pemesanan->waktu_bayar = now();
                    $pemesanan->save();
                }
            }
        } else if ($transaction == 'settlement') {
            // VA / transfer settled
            $pembayaran->status_transaksi = 'settlement';
            $pembayaran->save();

            $pemesanan->status_pemesanan = 'dibayar';
            $pemesanan->waktu_bayar = now();
            $pemesanan->save();
        } else if ($transaction == 'pending') {
            $pembayaran->status_transaksi = 'pending';
            $pembayaran->save();
        } else if (in_array($transaction, ['deny','expire','cancel'])) {
            $pembayaran->status_transaksi = $transaction;
            $pembayaran->save();

            $pemesanan->status_pemesanan = 'kadaluarsa';
            $pemesanan->save();
        }

        return response('OK', 200);
    }
}
