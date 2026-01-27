<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class AutoExpirePemesanan
{
    public function handle(Request $request, Closure $next)
    {
        Pemesanan::where('status_pemesanan', Pemesanan::PENDING)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->update([
                'status_pemesanan' => Pemesanan::KADALUARSA,
            ]);

        return $next($request);
    }
}
