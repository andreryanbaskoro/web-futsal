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
        // Konfigurasi Midtrans global
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function notification(Request $request)
    {
        // 🔍 Log semua notifikasi untuk debug
        \Log::info('MIDTRANS NOTIFICATION', $request->all());

        // Ambil data penting dari request
        $orderId      = $request->order_id ?? '';
        $statusCode   = $request->status_code ?? '';
        $grossAmount  = $request->gross_amount ?? '';
        $signatureKey = $request->signature_key ?? '';

        // 🔐 Verifikasi signature (opsional sementara untuk tes)
        if (!empty($orderId) && !empty($statusCode) && !empty($grossAmount)) {
            $serverKey = config('services.midtrans.server_key');
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $expectedSignature) {
                \Log::warning('MIDTRANS INVALID SIGNATURE', [
                    'expected' => $expectedSignature,
                    'received' => $signatureKey
                ]);
                return response('Invalid signature', 403);
            }
        }

        // ⚠️ Untuk tes webhook saja, jangan panggil SDK Notification
        $isTest = str_contains($orderId, 'payment_notif_test');
        if (!$isTest) {
            try {
                $notif = new Notification();

                $transaction = $notif->transaction_status;
                $paymentType = $notif->payment_type;
                $fraud       = $notif->fraud_status;
            } catch (\Exception $e) {
                \Log::error('MIDTRANS SDK ERROR', ['message' => $e->getMessage()]);
                return response('OK', 200); // Tetap balas 200 agar Midtrans tidak retry
            }
        } else {
            // 🔹 Data dummy untuk tes
            $transaction = $request->transaction_status ?? 'settlement';
            $paymentType = $request->payment_type ?? 'gopay';
            $fraud       = $request->fraud_status ?? 'accept';
        }

        // Cari pembayaran di database
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            \Log::warning('MIDTRANS PAYMENT NOT FOUND', ['order_id' => $orderId]);
            return response('Not Found', 404);
        }

        // Simpan response Midtrans
        $pembayaran->status_transaksi = $transaction;
        $pembayaran->tipe_pembayaran  = $paymentType;
        $pembayaran->response_midtrans = $request->all();
        $pembayaran->save();

        $pemesanan = $pembayaran->pemesanan;

        // 🔄 Mapping status pembayaran ke pemesanan
        if ($transaction === 'capture') {
            if ($paymentType === 'credit_card') {
                if ($fraud === 'challenge') {
                    $pembayaran->status_transaksi = 'challenge';
                } else {
                    $pembayaran->status_transaksi = 'capture';
                    $pemesanan->status_pemesanan = 'dibayar';
                    $pemesanan->waktu_bayar = now();
                }
            }
        } elseif ($transaction === 'settlement') {
            $pembayaran->status_transaksi = 'settlement';
            $pemesanan->status_pemesanan = 'dibayar';
            $pemesanan->waktu_bayar = now();
        } elseif ($transaction === 'pending') {
            $pembayaran->status_transaksi = 'pending';
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $pembayaran->status_transaksi = $transaction;
            $pemesanan->status_pemesanan = 'kadaluarsa';
        }

        $pembayaran->save();
        $pemesanan?->save();

        // ✅ Balas Midtrans 200 OK
        return response('OK', 200);
    }
}
