<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::guard('admin')->check()) {
            return redirect('/pracownik/logowanie')->withErrors('Aby uzyskać dostęp do tej strony, musisz się najpierw zalogować');
        }

        return $next($request);
    }
}
