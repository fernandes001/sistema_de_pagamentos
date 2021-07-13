<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use App\Dado;

class DadoController extends Controller
{
    public function index(Request $request) {
        $dados = Dado::all();

        $data = array(
            'dados' => $dados
        );
        
        return view('modulos/dados/dados_lista', $data);
    }

    public function create(Request $request) {
        return view('modulos/dados/dados_form');
    }

    public function store(Request $request) {
        $dado = new Dado;

        $this->validate($request, [
            'dado_a' => 'required',
            'dado_b' => 'required',
            'dado_c' => 'required'
        ]);

        $dado->dado_a = $request->dado_a;
        $dado->dado_b = $request->dado_b;
        $dado->dado_c = $request->dado_c;
        $dado->updated_at = null;

        try {
            $result = $dado->save();

            if($result) {
                // gravar log

                return redirect('/dados/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            return redirect('/dados/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;

        $dado = Dado::find($id);

        if(!$dado){
            abort(404);
        }

        $data = array(
            'dado' => $dado
        );

        return view('modulos/dados/dados_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'dado_a' => 'required',
            'dado_b' => 'required',
            'dado_c' => 'required'
        ]);

        $dado = Dado::find($id);
        $logData = $dado->toJson();

        if(!$dado){
            abort(404);
        }

        $dado->dado_a = $request->dado_a;
        $dado->dado_b = $request->dado_b;
        $dado->dado_c = $request->dado_c;

        try {
            $result = $dado->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'dados', $id, $logData, 4);

                return redirect('/dados/'.$dado->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/dados/'.$dado->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;

        $dado = Dado::find($id);

        try {
            $result = $dado->destroy($id);

            if($result) {
                // gravar log

                return redirect('/dados/')->with('responseSuccess', 'Os dados foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/dados/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }
}
