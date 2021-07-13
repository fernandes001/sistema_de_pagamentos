<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use App\Usuario;

class LoginController extends Controller
{

    /**
     * Render login page
     */
    public function index(Request $request){
        $user_data = $request->session()->get('user_data');

        // prevent login redirect
        if(!isset($user_data['user_id'])){
            return view('/modulos/login/login_form');
        }

        if(isset($request->no_permission) && $request->no_permission == '1') {
            return redirect('/')->with('responseError', 'Você tá logado, porém não tem permissão para essa página');
        } else {
            return redirect('/')->with('responseError', 'Você já está logado.');
        }
    }

    public function logar(Request $request){
        $usuario = new Usuario();

        // Validate fields
        $this->validate($request, [
            'email' => 'required',
            'senha' => 'required'
        ]);

        try {
            // Cat user data
            $dadosLogin = $usuario->logar($request->email, hash('sha256', 'fe81eccf3b0142884394d795022112d1de41a6c36a501069e317f668dbc1353d'.$request->senha));

            // Check if found this user
            if($dadosLogin !== null){
                // Put user data in session
                $dadosSessao = [];
                $dadosSessao['user_data']["user_id"] = $dadosLogin->id;
                $dadosSessao['user_data']["nome"] = $dadosLogin->nome;
                $dadosSessao['user_data']["email"] = $dadosLogin->email;
                $dadosSessao['user_data']["grupo_id"] = $dadosLogin->grupo_id;
				$dadosSessao['user_data']["ativo"] = $dadosLogin->ativo;

                $regras = $usuario->getRoles($dadosLogin->grupo_id);

                $dadosSessao['regras'] = $regras;

                $request->session()->put($dadosSessao);

                $log = new LogController();                
                $log->store($dadosLogin->id, 'usuarios', null, null, 0);

                return redirect('/registros');
            } else {
                return redirect('/login')->with('responseError', 'Usuário/Senha invalidos.');
            }

        } catch(Exception $e) {
            return redirect('/login')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function logoff(Request $request){
        // Remove all session data
        $request->session()->forget("user_data");
        $request->session()->forget("regras");
    
        return redirect('/login');
    }
}
