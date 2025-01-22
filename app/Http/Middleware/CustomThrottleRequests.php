<?php

namespace App\Http\Middleware;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Str;
use Closure;

class CustomThrottleRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next, $maxAttempts = 5, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);

            return redirect()->back()->with('tooManyAttempts', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$retryAfter} detik.");
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return Str::lower($request->input('nim')).'|'.$request->ip();
    }
}
