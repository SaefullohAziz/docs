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
    public function handle($request, Closure $next, $levels)
    {
        $levels = explode('|', $levels);
        if ( ! $request->user()->hasLevel($levels)) {
            return redirect()->route('home')->with('alert-danger', __('You have no related permission.'));
        }
        return $next($request);
    }
}
