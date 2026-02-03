<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTableContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('cafe_table_id')) {
            return redirect('/')->with('error', 'Silakan scan QR meja terlebih dahulu.');
        }
        return $next($request);
    }
}
