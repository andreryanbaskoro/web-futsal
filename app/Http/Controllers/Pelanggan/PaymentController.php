<?php
namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\PemesananJadwal;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

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
     * API: dibuat oleh page pembayaran via ajax.
     * Mengembalikan snap_token (untuk snap popup) atau response VA.
     */
    public function createSnap(Request $request)
    {
        $request->validate([
            'kode' => 'required|string'
        ]);

        $kode = $request->kode;
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)->where('status_pemesanan','pending')->firstOrFail();

        $grossAmount = (float) $pemesanan->total_bayar;

        // order_id unik (boleh pakai kode pemesanan)
        $orderId = 'ORDER-' . $pemesanan->kode_pemesanan . '-' . time();

        // item details (opsional)
        $items = [
            [
                'id' => $pemesanan->id_pemesanan,
                'price' => (int) $grossAmount,
                'quantity' => 1,
                'name' => 'Booking ' . $pemesanan->kode_pemesanan
            ]
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => optional($pemesanan->pengguna)->nama ?? 'Guest',
                'email' => optional($pemesanan->pengguna)->email ?? 'guest@example.com',
                'phone' => optional($pemesanan->pengguna)->no_hp ?? '',
            ],
            'item_details' => $items,
        ];

        // jika ingin langsung pakai VA tertentu, bisa set 'payment_type' => 'bank_transfer' + 'bank' => 'bca'
        // tapi Snap akan menghasilkan berbagai pilihan
        try {
            $snapResponse = Snap::getSnapToken($params);

            // simpan record pembayaran (pending)
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'order_id' => $orderId,
                'snap_token' => $snapResponse,
                'tipe_pembayaran' => 'snap',
                'status_transaksi' => 'pending',
            ]);

            return response()->json([
                'snap_token' => $snapResponse,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
