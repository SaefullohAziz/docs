<?php

namespace App\Http\Middleware;

use Closure;

class LevelCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $level)
    {
        if ( ! $request->user()->hasLevel($level)) {
            return redirect()->route('home')->with('alert-danger', __('You have no related permission.'));
        }
        return $next($request);
    }
}
