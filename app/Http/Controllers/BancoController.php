<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banco;

class BancoController extends Controller
{
    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'qtde_itens' => 'nullable|numeric'
        ]);

        $banco_nome = $request->get('banco_nome');
        $qtde_itens = $request->get('qtde_itens');

        $banco = new Banco();
        $dadosBancos = $banco->list($banco_nome, $qtde_itens);

        $data = array(
            'dadosBancos' => $dadosBancos,
            'user_data' => $user_data,
            'banco_nome' => $banco_nome,
            'qtde_itens' => $qtde_itens
        );
        
        return view('modulos/bancos/bancos_lista', $data);
    }

    public function create(Request $request) {
        $user_data = $request->session()->get('user_data');

        $data = array(
            'user_data' => $user_data
        );

        return view('modulos/bancos/bancos_form', $data);
    }

    public function store(Request $request) {
        $banco = new Banco;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:150',
            'agencia' => 'max:20',
            'conta' => 'max:20',
            'favorecido' => 'max:150',
            'cnpj' => 'max:18'
        ]);

        $banco->nome = $request->nome;
        $banco->agencia = $request->agencia;
        $banco->conta = $request->conta;
        $banco->favorecido = $request->favorecido;
        $banco->cnpj = $request->cnpj;
        $banco->updated_at = null;

        try {
            $result = $banco->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'bancos', $banco->id, null, 2);

                return redirect('/bancos/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            return redirect('/bancos/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosBanco = Banco::find($id);

        if(!$dadosBanco){
            abort(404);
        }

        $data = array(
            'dadosBanco' => $dadosBanco,
            'user_data' => $user_data
        );

        return view('modulos/bancos/bancos_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:150',
            'agencia' => 'max:20',
            'conta' => 'max:20',
            'favorecido' => 'max:150',
            'cnpj' => 'max:18'
        ]);

        $dadosBanco = Banco::find($id);

        $created_at = $dadosBanco->created_at;
  
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/bancos/'.$dadosBanco->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/bancos/'.$dadosBanco->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }

        }


        $logData = $dadosBanco->toJson();

        if(!$dadosBanco){
            abort(404);
        }

        $dadosBanco->nome = $request->nome;
        $dadosBanco->agencia = $request->agencia;
        $dadosBanco->conta = $request->conta;
        $dadosBanco->favorecido = $request->favorecido;
        $dadosBanco->cnpj = $request->cnpj;

        try {
            $result = $dadosBanco->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'bancos', $dadosBanco->id, $logData, 3);

                return redirect('/bancos/'.$dadosBanco->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/bancos/'.$dadosBanco->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');
      
        $dadosBanco = Banco::find($id);

        $created_at = $dadosBanco->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/bancos')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/bancos')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }

        $logData = $dadosBanco->toJson();

        try {
            $result = $dadosBanco->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'bancos', $dadosBanco->id, $logData, 1);

                return redirect('/bancos/')->with('responseSuccess', 'Os dados do banco '.$dadosBanco->nome.' foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/bancos/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }
}
