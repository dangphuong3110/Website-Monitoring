<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoginAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            session(['intended_url' => url()->current()]);
            return redirect('/website-monitoring/login');
        }

        return $next($request);
    }
}
