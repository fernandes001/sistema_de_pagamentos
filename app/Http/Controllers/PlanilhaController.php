<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use App\Planilha;
use App\Banco;
use App\Cliente;

class PlanilhaController extends Controller
{
    public function index(Request $request) {
        $dadosPlanilhas = Planilha::all();

        $data = array(
            'dadosPlanilhas' => $dadosPlanilhas
        );
        
        return view('modulos/planilhas/planilhas_lista', $data);
    }

    public function create(Request $request) {
        $dadosBancos = Banco::all();
        $dadosClientes = Cliente::all();

        $data = array(
            'bancos' => $dadosBancos,
            'clientes' => $dadosClientes
        );

        return view('modulos/planilhas/planilhas_form', $data);
    }

    public function store(Request $request) {
        $planilha = new Planilha;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'cliente_id' => 'required',
            'banco_id' => 'required',
            'favorecido' => 'required',
            'valor' => 'required',
            'tipo' => 'required',
            'saque_id' => 'required',
            'status' => 'required',
            'confirmacao' => 'required'
        ]);

        $planilha->cliente_id = $request->cliente_id;
        $planilha->banco_id = $request->banco_id;
        $planilha->favorecido = $request->favorecido;
        $planilha->valor = $request->valor;
        $planilha->tipo = $request->tipo;
        $planilha->saque_id = $request->saque_id;
        $planilha->status = $request->status;
        $planilha->responsavel_id = $user_data['user_id'];
        $planilha->confirmacao = $request->confirmacao;
        $planilha->estorno = $request->estorno;
        $planilha->url_comprovante = $request->url_comprovante;
        $planilha->updated_at = null;

        try {
            $result = $planilha->save();

            if($result) {
                // gravar log

                return redirect('/planilhas/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            echo "<pre>";
            var_dump($e);
            die;
            return redirect('/planilhas/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;

        $dadosPlanilha = Planilha::find($id);
        $dadosBancos = Banco::all();
        $dadosClientes = Cliente::all();

        if(!$dadosPlanilha){
            abort(404);
        }

        $data = array(
            'dadosPlanilha' => $dadosPlanilha,
            'bancos' => $dadosBancos,
            'clientes' => $dadosClientes
        );

        return view('modulos/planilhas/planilhas_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'cliente_id' => 'required',
            'banco_id' => 'required',
            'favorecido' => 'required',
            'valor' => 'required',
            'tipo' => 'required',
            'saque_id' => 'required',
            'status' => 'required',
            'confirmacao' => 'required'
        ]);

        $dadosPlanilha = Planilha::find($id);
        $logData = $dadosPlanilha->toJson();

        if(!$dadosPlanilha){
            abort(404);
        }

        $dadosPlanilha->cliente_id = $request->cliente_id;
        $dadosPlanilha->banco_id = $request->banco_id;
        $dadosPlanilha->favorecido = $request->favorecido;
        $dadosPlanilha->valor = $request->valor;
        $dadosPlanilha->tipo = $request->tipo;
        $dadosPlanilha->saque_id = $request->saque_id;
        $dadosPlanilha->status = $request->status;
        $dadosPlanilha->responsavel_id = $user_data['user_id'];
        $dadosPlanilha->confirmacao = $request->confirmacao;
        $dadosPlanilha->estorno = $request->estorno;
        $dadosPlanilha->url_comprovante = $request->url_comprovante;

        try {
            $result = $dadosPlanilha->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'dados', $id, $logData, 4);

                return redirect('/planilhas/'.$dadosPlanilha->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/planilhas/'.$dadosPlanilha->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;

        $dadosPlanilha = Planilha::find($id);

        try {
            $result = $dadosPlanilha->destroy($id);

            if($result) {
                // gravar log

                return redirect('/planilhas/')->with('responseSuccess', 'Os dados foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/planilhas/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }
}
