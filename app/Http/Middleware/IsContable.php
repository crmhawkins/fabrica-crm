<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class IsContable
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next)
    {

        if (Auth::user()){
            if(Auth::user()->role == 'contable' || Auth::user()->role == 'admin') {
                return $next($request);
            }
        }

        return redirect()->route('home'); // If user is not an admin.
    }
}
