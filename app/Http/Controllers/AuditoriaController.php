<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use App\Registro;
use App\Auditoria;
use App\Banco;
use App\AuditoriaBanco;

class AuditoriaController extends Controller
{
    public function index(Request $request) {
        //$search = $request->get('search');
        $user_data = $request->session()->get('user_data');

        $registro = new Registro();
        $dadosAuditoria = $registro->auditoriaItens();

        $data = array(
            'dadosAuditoria' => $dadosAuditoria,
            'user_data' => $user_data
        );
        
        return view('modulos/auditoria/auditoria_lista', $data);
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $dadosAuditoria = Auditoria::where('data_registro', '=', date('Y-m-d', $id))->first();

        $auditoria_id = isset($dadosAuditoria->id) ? $dadosAuditoria->id : '';
  
        $bancos = Banco::all();
        $banco = new Banco();

        if(isset($dadosAuditoria->id)) {
            $valoresAuditoriaBanco = $banco->bancosAuditoriaBancos($dadosAuditoria->id);
        } else {
            $valoresAuditoriaBanco = null;
        }
        
        $data = array(
            'dadosAuditoria' => $dadosAuditoria,
            'user_data' => $user_data,
            'id' => $id,
            'bancos' => $bancos,
            'valoresAuditoriaBanco' => $valoresAuditoriaBanco
        );

        return view('modulos/auditoria/auditoria_form', $data);
    }

    public function store(Request $request) {
        $auditoria = new Auditoria;
        $user_data = $request->session()->get('user_data');

        $auditoria->data_registro = date('Y-m-d', $request->data_registro);
        $auditoria->responsavel_id = $user_data['user_id'];
        $auditoria->tarifas = $this->moneyFormat($request->tarifas);
        $auditoria->duplicidade = $this->moneyFormat($request->duplicidade);
        $auditoria->valor_duplicado = $this->moneyFormat($request->valor_duplicado);
        $auditoria->reembolso = $this->moneyFormat($request->reembolso);
        $auditoria->saque_prime = $this->moneyFormat($request->saque_prime);
        $auditoria->observacoes = $request->observacoes;
        $auditoria->updated_at = null;

        try {
            $result = $auditoria->save();
            $id = $auditoria->id;

            foreach($request->bancos as $banco) {
                $auditoriaBancoCreate = new AuditoriaBanco();
    
                $auditoriaBancoCreate->auditoria_id = $id;
                $auditoriaBancoCreate->banco_id = $banco['banco_id'];
                $auditoriaBancoCreate->valor = $banco['valor'];
    
                $auditoriaBancoCreate->save();
            }

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'auditoria', $auditoria->id, null, 2);

                return redirect('/auditoria/'.strtotime($auditoria->data_registro).'/edit')->with('responseSuccess', 'Os dados foram criados.');
            }
        } catch(\Exception $e) {
            return redirect('/auditoria/'.strtotime($auditoria->data_registro).'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $auditoria = Auditoria::find($id);

        $logData = $auditoria->toJson();

        $auditoria->confirmacao_id = $request->confirmacao_id == 0 ? null : $request->confirmacao_id;
        $auditoria->tarifas = $this->moneyFormat($request->tarifas);
        $auditoria->duplicidade = $this->moneyFormat($request->duplicidade);
        $auditoria->valor_duplicado = $this->moneyFormat($request->valor_duplicado);
        $auditoria->reembolso = $this->moneyFormat($request->reembolso);
        $auditoria->saque_prime = $this->moneyFormat($request->saque_prime);
        $auditoria->observacoes = $request->observacoes;

        foreach($request->bancos as $banco) {
            $auditoriaBanco = AuditoriaBanco::find($banco['auditoria_id']);
           
            if(!is_null($auditoriaBanco)) { // update if already exists
                
                $auditoriaBanco->valor = $this->moneyFormat($banco['valor']);
                $auditoriaBanco->save();

            } else { // create if it does not exist

                $auditoriaBancoCreate = new AuditoriaBanco();

                $auditoriaBancoCreate->auditoria_id = $id;
                $auditoriaBancoCreate->banco_id = $banco['banco_id'];
                $auditoriaBancoCreate->valor = $this->moneyFormat($banco['valor']);

                $auditoriaBancoCreate->save();

            }
        }

        try {
            $result = $auditoria->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'auditoria', $auditoria->id, $logData, 3);

                return redirect('/auditoria/'.strtotime($auditoria->data_registro).'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/auditoria/'.strtotime($auditoria->data_registro).'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function confirmacao(Request $request) {
        $id = (int)$request->id;

        if($id == 0) {
            echo "Não encontrado...";
        } else {
            $user_data = $request->session()->get('user_data');

            $dadosAuditoria = Auditoria::find($id);
            $logData = $dadosAuditoria->toJson();

            if(!$dadosAuditoria){
                abort(404);
            }

            if(is_null($dadosAuditoria->confirmacao_id) || $dadosAuditoria->confirmacao_id == 0) {
                $dadosAuditoria->confirmacao_id = $user_data['user_id'];
            } else {
                $dadosAuditoria->confirmacao_id = null;
            }
            
            try {
                $result = $dadosAuditoria->save();

                if($result) {
                    // general log
                    $log = new LogController();                
                    $log->store($user_data['user_id'], 'auditoria', $dadosAuditoria->id, $logData, 3);

                    echo "Os dados foram salvos.";
                }

            } catch(\Exception $e) {
                echo "Ocorreu um erro, teten novamente.";
            }
        }
    }

    public function auditoriaBanco($id) {
        $dadosAuditoria = Auditoria::where('data_registro', '=', date('Y-m-d', $id))->first();
        $banco = new Banco();

        if(isset($dadosAuditoria->id)) {
            $valoresAuditoriaBanco = $banco->bancosAuditoriaBancos($dadosAuditoria->id);
        } else {
            $valoresAuditoriaBanco = array();
        }

        return $valoresAuditoriaBanco;
    }

    public function auditoria($id) {
        $dadosAuditoria = Auditoria::where('data_registro', '=', date('Y-m-d', $id))->first();
        
        return $dadosAuditoria;
    }

    public function getSaldoCredito($date) {
        $auditoria = new Auditoria();

        $date = date('Y-m-d', $date);

        $dadosSaldo = $auditoria->saldoCredito($date);

        return $dadosSaldo;
    }

    // todo, turn into a helper
    public function moneyFormat($formatedValue) {
        if(!empty($formatedValue)) {
            $valores = array_reverse(explode(',', $formatedValue));

            if(count($valores) > 1) {
                $valor = str_replace('.', '', $valores[1]).".".$valores[0];
                return $valor;
            } else {
                return $formatedValue;
            }
        } else {
            return null;
        }
    }
}
