<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::guard('web')->check()) {
            return redirect('/logowanie')->withErrors('Aby uzyskać dostęp do tej strony, musisz się najpierw zalogować');
        }

        return $next($request);
    }
}
