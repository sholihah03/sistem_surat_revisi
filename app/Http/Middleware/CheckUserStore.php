<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userHasStore = Auth::user()->UserStore()->exists();

            if ($userHasStore) {
                return $next($request);
            } else {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman toko.');
            }
        }

        // Jika user tidak login, redirect ke halaman login
        return redirect()->route('loginIndex');
    }
}
