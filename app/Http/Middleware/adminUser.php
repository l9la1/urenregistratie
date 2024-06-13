<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class adminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is loged in and that the role is the same as the incomming role
        if (Auth::check() && Auth::user()->role == $role)
            return $next($request);
        // When it is a xmlHttpRequest request then abort it when failed
        if($request->isXmlHttpRequest())
            abort(403);
        else
            return redirect()->route("index");
    }
}
