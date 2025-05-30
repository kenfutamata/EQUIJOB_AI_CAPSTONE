<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedMiddleware
{
    protected $role; 
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard=null): Response
    {
        if(!Auth::guard($guard)->check()){
            return redirect()->route('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        return $next($request);
    }
}
