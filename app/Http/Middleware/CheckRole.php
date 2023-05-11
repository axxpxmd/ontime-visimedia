<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        $roles = $this->cekRoute($request->route());
        if(!$roles){
            return $next($request);
        }
        // dd($roles);
        if(in_array(auth()->user()->role->role, $roles)){

            return $next($request);
        }



        return abort(403, 'Access Forbidden');
    }

    public function cekRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['roles']) ? $actions['roles'] : null;
    }
}
