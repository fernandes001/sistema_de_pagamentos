<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use App\Usuario;

class ContaController extends Controller
{
    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');

        $data = Usuario::find($user_data['user_id']);

        $data = array(
            'data' => $data,
            'user_data' => $user_data
        );
        
        return view('modulos/conta/conta_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'senha' => 'required|max:64'
        ]);

        $usuario = Usuario::find($id);
        $logData = $usuario->toJson();

        if(!$usuario) {
            abort(404);
        }

        if($user_data['user_id'] !== $usuario->id) {
            abort(404);
        }

        $usuario->senha = hash('sha256', 'fe81eccf3b0142884394d795022112d1de41a6c36a501069e317f668dbc1353d'.$request->senha);
        
        try {
            $result = $usuario->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'usuarios', $usuario->id, $logData, 3);

                return redirect('/conta/')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/conta/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }
}
