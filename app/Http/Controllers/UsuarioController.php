<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use Illuminate\Validation\Rule;
use App\Usuario;

class UsuarioController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');
        $user_data = $request->session()->get('user_data');
        $pagina = 'lista_usuarios';

        $usuario = new Usuario();
        $usuarios = $usuario->list($search);

        $data = array(
            'usuarios' => $usuarios,
            'user_data' => $user_data,
            'pagina' => $pagina
        );
        
        return view('modulos/usuarios/usuarios_lista', $data);
    }

    public function create(Request $request) {
        $user_data = $request->session()->get('user_data');

        $data = array(
            'user_data' => $user_data
        );

        return view('modulos/usuarios/usuarios_form', $data);
    }

    public function store(Request $request) {
        $usuario = new Usuario;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:100',
            'email' => 'required|max:100',
            'senha' => 'nullable|max:64',
            'grupo_id' => ['required', 'numeric', Rule::in([3, 4])],
			'ativo' => ['required', 'numeric', Rule::in([0, 1])],
			'empresa' => ['required', 'numeric', Rule::in([1, 2])]
        ]);

        $usuario->grupo_id	= $request->grupo_id;
        $usuario->nome		= $request->nome;
        $usuario->email		= $request->email;
        $usuario->senha		= hash('sha256', 'fe81eccf3b0142884394d795022112d1de41a6c36a501069e317f668dbc1353d'.$request->senha);
		$usuario->empresa	= $request->empresa;
		$usuario->ativo		= $request->ativo;
        $usuario->url_photo = $request->url_photo;
        $usuario->updated_at = null;

        try {
            $result = $usuario->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'usuarios', $usuario->id, null, 2);

                return redirect('/usuarios/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            echo "<pre>";
            var_dump($e);
            die;
            return redirect('/usuarios/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $usuario = Usuario::find($id);

        if(!$usuario){
            abort(404);
        }

        $data = array(
            'usuario' => $usuario,
            'user_data' => $user_data
        );

        return view('modulos/usuarios/usuarios_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:100',
            'email' => 'required|max:100',
            'senha' => 'nullable|max:64',
            'grupo_id' => ['required', 'numeric', Rule::in([3, 4])],
			'ativo' => ['required', 'numeric', Rule::in([0, 1])],
			'empresa' => ['required', 'numeric', Rule::in([1, 2])]
        ]);

        $usuario = Usuario::find($id);

        $created_at = $usuario->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/usuarios/'.$usuario->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/usuarios/'.$usuario->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }

        }

        $logData = $usuario->toJson();

        if(!$usuario){
            abort(404);
        }

        $usuario->grupo_id	= $request->grupo_id;
        $usuario->nome		= $request->nome;
        $usuario->email		= $request->email;
		$usuario->empresa	= $request->empresa;
		$usuario->ativo		= $request->ativo;
        $usuario->url_photo = $request->url_photo;

        if (!empty($request->senha)) {
            $usuario->senha = hash('sha256', 'fe81eccf3b0142884394d795022112d1de41a6c36a501069e317f668dbc1353d'.$request->senha);
        }

        try {
            $result = $usuario->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'usuarios', $usuario->id, $logData, 3);

                return redirect('/usuarios/'.$usuario->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/usuarios/'.$usuario->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $usuario = Usuario::find($id);

        $created_at = $usuario->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/usuarios')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/usuarios')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }

        $logData = $usuario->toJson();

        try {
            $result = $usuario->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'usuarios', $usuario->id, $logData, 1);

                return redirect('/usuarios/')->with('responseSuccess', 'Os dados do usuário '.$usuario->email.' foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/usuarios/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }
}
