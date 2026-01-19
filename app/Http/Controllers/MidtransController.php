<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Pelanggan\BookingController;


class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function notification(Request $request)
    {
        Log::info('MIDTRANS NOTIFICATION', $request->all());

        /* ===============================
         * VALIDASI SIGNATURE
         * =============================== */
        $signature = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                config('services.midtrans.server_key')
        );

        if ($signature !== $request->signature_key) {
            Log::warning('MIDTRANS SIGNATURE INVALID', [
                'order_id' => $request->order_id
            ]);
            return response('Invalid signature', 403);
        }

        /* ===============================
         * AMBIL DATA NOTIFIKASI RESMI
         * =============================== */
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('MIDTRANS NOTIFICATION ERROR', [
                'message' => $e->getMessage()
            ]);
            return response('OK', 200);
        }

        $orderId            = $notif->order_id;
        $transactionStatus  = $notif->transaction_status;
        $paymentType        = $notif->payment_type;
        $fraudStatus        = $notif->fraud_status ?? null;
        $transactionTime    = $notif->transaction_time ?? null;
        $settlementTime     = $notif->settlement_time ?? null;

        /* ===============================
         * AMBIL DATA PEMBAYARAN
         * =============================== */
        $pembayaran = Pembayaran::with('pemesanan')
            ->where('order_id', $orderId)
            ->first();

        if (!$pembayaran) {
            Log::warning('PEMBAYARAN TIDAK DITEMUKAN', [
                'order_id' => $orderId
            ]);
            return response('Not Found', 404);
        }

        $pemesanan = $pembayaran->pemesanan;

        /* ===============================
         * TRANSACTION DB (AMAN)
         * =============================== */
        DB::transaction(function () use (
            $pembayaran,
            $pemesanan,
            $transactionStatus,
            $paymentType,
            $fraudStatus,
            $settlementTime,
            $request
        ) {
            /* ===== UPDATE PEMBAYARAN ===== */
            $pembayaran->update([
                'status_transaksi'  => $transactionStatus, // ASLI MIDTRANS
                'tipe_pembayaran'   => $paymentType,
                'status_fraud'      => $fraudStatus,
                'waktu_bayar'       => $settlementTime,
                'response_midtrans' => $request->all(),
            ]);

            /* ===== MAPPING KE PEMESANAN ===== */
            switch ($transactionStatus) {

                case 'capture':
                    if ($paymentType === 'credit_card' && $fraudStatus !== 'challenge') {

                        if ($pemesanan->status_pemesanan !== 'dibayar') {

                            $pemesanan->status_pemesanan = 'dibayar';
                            $pemesanan->waktu_bayar = now();
                            $pemesanan->save();

                            app(BookingController::class)
                                ->markAsPaid($pemesanan);
                        }
                    }
                    break;


                case 'settlement':
                    if ($pemesanan->status_pemesanan !== 'dibayar') {

                        $pemesanan->status_pemesanan = 'dibayar';
                        $pemesanan->waktu_bayar = now();
                        $pemesanan->save();

                        // 🔥 INI YANG KURANG
                        app(BookingController::class)
                            ->markAsPaid($pemesanan);
                    }
                    break;


                case 'pending':
                    $pemesanan->status_pemesanan = 'pending';
                    break;

                case 'deny':
                case 'cancel':
                    $pemesanan->status_pemesanan = 'dibatalkan';
                    break;

                case 'expire':
                    $pemesanan->status_pemesanan = 'kadaluarsa';
                    break;

                case 'refund':
                case 'partial_refund':
                    // Optional: buat status sendiri kalau perlu
                    $pemesanan->status_pemesanan = 'dibatalkan';
                    break;
            }

            $pemesanan->save();
        });

        /* ===============================
         * BALAS WAJIB 200
         * =============================== */
        return response('OK', 200);
    }
}
