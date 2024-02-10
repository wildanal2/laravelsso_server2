<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->hasRole(['sso.admin', 'sso.super-admin'])) {
            return $next($request);
        }

        return redirect()->route('index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
