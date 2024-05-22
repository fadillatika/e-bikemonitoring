<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in and has the appropriate motor ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $guard = empty($guards) ? null : $guards[0];

        if ($this->auth->guard($guard)->check()) {
            return;
        }

        if ($guard == 'admin' && $this->attemptLogin($request)) {
            return;
        }

        $this->unauthenticated($request, $guards);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $motorId = $request->input('motor_id');

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if ($user && Auth::getProvider()->validateCredentials($user, $credentials)) {
            $user->motor_id = $motorId;

            Auth::login($user);

            return true;
        }

        return false;
    }
}