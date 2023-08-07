<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAjaxRequest {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $redirect = null) {
        if (!$request->expectsJson()) {
			return $request->is('admin/*') ? route('admin') : route('site');
			logger('DDR -> IsAjaxRequest middleware нет redirect');
			return false;
		} 
        return $next($request);
    }
}