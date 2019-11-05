<?php

namespace App\Http\Middleware;

use Closure;

class Joined
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->can('set', \App\School::class)) {
            return redirect()->route('school.set');
        }
        return $next($request);
    }
}
