<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LogController;
use App\CitarDys;
use App\Log;
use App\Usuario;
use PDF;

class CitarDysController extends Controller
{
    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'qtde_itens' => 'nullable|numeric'
        ]);

        $data_inicio = $this->reverseData($request->get('data_inicio'));
        $data_fim = $this->reverseData($request->get('data_fim'));
        $banco_recebimento = $request->get('banco_recebimento');
        $qtde_itens = $request->get('qtde_itens');
    

        $citardys = new CitarDys();
        $dadosCitarDys = $citardys->list($data_inicio, $data_fim, $banco_recebimento, $qtde_itens);
        
        $data = array(
            'dadosCitarDys' => $dadosCitarDys,
            'user_data' => $user_data,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'qtde_itens' => $qtde_itens,
            'banco_recebimento' => $banco_recebimento
        );
        
        return view('modulos/citardys/citardys_lista', $data);
    }

    public function create(Request $request) {
        $user_data = $request->session()->get('user_data');
        $citardys = new CitarDys();

        $buscaSaldoAReceber = $citardys->buscaSaldoAReceber(date('Y-m-d'))[0];

        $data = array(
            'user_data' => $user_data,
            'saldo_a_receber' => number_format((double)$buscaSaldoAReceber->saldo_a_receber, 2, '.', '')
        );

        return view('modulos/citardys/citardys_form', $data);
    }

    public function store(Request $request) {
        $dadosCitarDys = new CitarDys;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'valor_pago' => 'required',
            'banco_que_recebemos' => 'required',
            'data_pagamento' => 'required'
        ]);

        $dadosCitarDys->usuario_id = $user_data['user_id'];
        $dadosCitarDys->valor_pago = $this->moneyFormat($request->valor_pago);
        $dadosCitarDys->banco_que_recebemos = $request->banco_que_recebemos;
        $dadosCitarDys->data_pagamento = $this->reverseData($request->data_pagamento);
        $dadosCitarDys->comprovante = $request->comprovante;
        $dadosCitarDys->observacoes = $request->observacoes;

        $dadosCitarDys->updated_at = null;

        try {
            $result = $dadosCitarDys->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'citardys', $dadosCitarDys->id, null, 2);

                return redirect('/citardys/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            return redirect('/citardys/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosCitarDys = CitarDys::find($id);

        $citardys = new CitarDys();
        $buscaSaldoAReceber = $citardys->buscaSaldoAReceber($dadosCitarDys->created_at->format('Y-m-d'))[0];

        if(!$dadosCitarDys){
            abort(404);
        }

        $data = array(
            'dadosCitarDys' => $dadosCitarDys,
            'user_data' => $user_data,
            'saldo_a_receber' => number_format((double)$buscaSaldoAReceber->saldo_a_receber, 2, '.', '')
        );

        return view('modulos/citardys/citardys_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'valor_pago' => 'required',
            'banco_que_recebemos' => 'required',
            'data_pagamento' => 'required',
            'confirmacao_id' => 'numeric'
        ]);

        $dadosCitarDys = CitarDys::find($id);

        $created_at = $dadosCitarDys->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/citardys/'.$dadosCitarDys->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }

        } else {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/citardys/'.$dadosCitarDys->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }

        }


        $logData = $dadosCitarDys->toJson();

        if(!$dadosCitarDys){
            abort(404);
        }

        $dadosCitarDys->valor_pago = $this->moneyFormat($request->valor_pago);
        $dadosCitarDys->banco_que_recebemos = $request->banco_que_recebemos;
        $dadosCitarDys->data_pagamento = $this->reverseData($request->data_pagamento);
        $dadosCitarDys->comprovante = $request->comprovante;
        $dadosCitarDys->observacoes = $request->observacoes;
        $dadosCitarDys->confirmacao_id = $request->confirmacao_id == 0 ? null : $request->confirmacao_id;

        try {
            $result = $dadosCitarDys->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'citardys', $dadosCitarDys->id, $logData, 3);

                return redirect('/citardys/'.$dadosCitarDys->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/citardys/'.$dadosCitarDys->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function confirmacao(Request $request) {
        $id = (int)$request->id;

        $user_data = $request->session()->get('user_data');

        $dadosCitarDys = CitarDys::find($id);
        $logData = $dadosCitarDys->toJson();

        if(!$dadosCitarDys){
            abort(404);
        }

        if(is_null($dadosCitarDys->confirmacao_id) || $dadosCitarDys->confirmacao_id == 0) {
            $dadosCitarDys->confirmacao_id = $user_data['user_id'];
        } else {
            $dadosCitarDys->confirmacao_id = null;
        }
        
        try {
            $result = $dadosCitarDys->save();

            if($result) {
                // general log
                $log = new LogController();                
                $log->store($user_data['user_id'], 'citardys', $dadosCitarDys->id, $logData, 3);

                echo "Os dados foram salvos.";
            }

        } catch(\Exception $e) {
            echo "Ocorreu um erro, teten novamente.";
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $dadosCitarDys = CitarDys::find($id);

        $created_at = $dadosCitarDys->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/citardys')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/citardys')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }

        $logData = $dadosCitarDys->toJson();

        try {
            $result = $dadosCitarDys->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'citardys', $dadosCitarDys->id, $logData, 1);

                return redirect('/citardys/')->with('responseSuccess', 'Os dados foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/citardys/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function geraPdf(Request $request) {
        $data_inicio = $request->get('data_inicio');
        $data_fim = $request->get('data_fim');
        $banco_recebimento = $request->get('banco_recebimento');

        $citarDys = new CitarDys();
        $dadosCitarDys = $citarDys->list($data_inicio, $data_fim, $banco_recebimento, 250);

        $pdf = PDF::loadView('modulos/citardys/relatorios/geral_pdf', compact('dadosCitarDys'));
        return $pdf->setPaper('a4')->stream('citardys');
    }

    public function geraCsv(Request $request) {
        $data_inicio = $request->get('data_inicio');
        $data_fim = $request->get('data_fim');

        $citarDys = new CitarDys();
        $dadosCitarDys = $citarDys->list($data_inicio, $data_fim, $banco_recebimento, 250);

        $cabecalho = "ID,Valor Pago,Data Pagamento,Banco,Comprovante,Observações \n";
        $corpo = '';
        foreach($dadosCitarDys as $citarDys) {
            $valor_pago = number_format((float)$citarDys->valor_pago, 2, ",",".");
            $data_pagamento = isset($citarDys->data_pagamento) ? date('d/m/Y', strtotime($citarDys->data_pagamento)) : '';
            $banco = isset($citarDys->banco_que_recebemos) ? $citarDys->banco_que_recebemos : '';

            $corpo .= $citarDys->id.',';
            $corpo .= $valor_pago.',';
            $corpo .= $data_pagamento.',';
            $corpo .= $banco.',';
            $corpo .= $citarDys->comprovante.',';
            $corpo .= $citarDys->observacoes."\n";
        }

        $file = md5(rand(1,1000000))."_relatorio.csv";
   
        $report_file = Storage::disk('local')->put('files/reports/'.$file, $cabecalho.$corpo);

        $exists = Storage::disk('local')->exists('files/reports/'.$file);
        if($exists){
            return Storage::download('files/reports/'.$file);
        } else {
            return redirect('/citardys/')->with('messageError', 'Arquivo não encontrdo.');
        }
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

    public function reverseData($data) {
        $data = explode('/', $data);

        if(count($data) < 3) {
            return '';
        }

        $data = $data[2].'-'.$data[1].'-'.$data[0];
        return $data;
    }

    public function logs(Request $request) {
        $id = (int)$request->id;

        $dadosCitarDys = Log::where('tabela', '=', 'citardys')->where('tabela_id', '=', $id)->get();

        $html = '';
        foreach($dadosCitarDys as $citarDys) {
            $history = json_decode($citarDys->previous_data);
            $criadoPor = !empty($citarDys->usuario_id) ? Usuario::find($citarDys->usuario_id)->nome : '';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Data de Criação Log</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($citarDys->created_at)).'</div>'.
            '</div>';
            
            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Log criado por</div>'.
                '<div class="col-md-9">'.$criadoPor.'</div>'.
            '</div>';   
            
            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Valor pago</div>'.
                '<div class="col-md-9"> R$'.number_format((float)$history->valor_pago, 2, ",",".").'</div>'.
            '</div>'; 
            
            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Banco que recebmos</div>'.
                '<div class="col-md-9">'.$history->banco_que_recebemos.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Comprovante</div>'.
                '<div class="col-md-9">'.$history->comprovante.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Observações</div>'.
                '<div class="col-md-9">'.$history->observacoes.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Data pagamento</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($history->data_pagamento)).'</div>'.
            '</div>';

            $html .= '<div class="mb-4 row modal-table">'.
                '<div class="col-md-3">Data de criação</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($history->created_at)).'</div>'.
            '</div>';
        }

        echo($html);
    }
}
