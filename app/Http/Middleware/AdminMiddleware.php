<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki hak akses administrator.');
        }

        return $next($request);
    }
}
