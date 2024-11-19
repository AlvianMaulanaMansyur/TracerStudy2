<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        Log::info('Admin Middleware: User is authenticated: ', ['authenticated' => Auth::guard('admin')->check()]);

        if (!Auth::guard('admin')->check()) {
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
