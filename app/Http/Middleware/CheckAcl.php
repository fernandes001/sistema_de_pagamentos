<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\RegraController;

class CheckAcl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $regras = new RegraController();

        // Put current site in session
        $request->session()->put('currentSite', $request->site);

        // Check if user is logged in
        $user_data = $request->session()->get('user_data');
        if(!isset($user_data['user_id'])){
            return redirect('/login')->with('responseError', "Faça login novamente.");
        }

        // Check if user has permission for this route
        $hasRole = $regras->hasRole($request);
        if($hasRole){
            return $next($request);
        } else {
            return redirect('/login?no_permission=1')->with('responseError', "Você não tem permissão.");
        }
    }
}
