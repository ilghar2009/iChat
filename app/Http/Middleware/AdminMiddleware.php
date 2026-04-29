<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \auth()?->user();

        if(isset($user) and $user->is_admin){
            return $next($request);
        }

        return Redirect::route('authP', ['signL' => 'you dont have permission access to this page']);
    }
}
