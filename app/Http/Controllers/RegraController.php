<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegraController extends Controller
{
    /**
     * Check if user has permission for a request route
     */
    public function hasRole($request){
    	$roles = $request->session()->get("regras"); // roles for access

        // check if key site exists
        if(!isset($roles['dys'])){
            return false;
        }

        $roles = $roles['dys']["roles"];

        $action = $request->route()->getAction()['as'];
        
        foreach($roles as $role){
            if($role["modulo_uri"].".".$role["acao"] == $action){
                return true;
            }
        }

    	return false;
    }

    public function checkRoleByRoleName($roles, $action) {
        $roles = $roles['dys']["roles"];

        foreach($roles as $role){
            if($role["modulo_uri"].".".$role["acao"] == $action){
                return true;
            }
        }

    	return false;
    }
}
