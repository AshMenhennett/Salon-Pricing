<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') === 'production') {
            \Request::setTrustedProxies([$request->getClientIp()]);
            if (! $request->isSecure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }
        return $next($request);
    }
}
