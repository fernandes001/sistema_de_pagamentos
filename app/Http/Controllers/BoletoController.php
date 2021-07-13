<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LogController;
use App\Boleto;
use App\Banco;
use App\Usuario;
use App\Log;
use PDF;

class BoletoController extends Controller
{
    public function index(Request $request) {
        $this->validate($request, [
            'qtde_itens' => 'nullable|numeric'
        ]);

        $data_inicio = $this->reverseData($request->get('data_inicio'));
        $data_fim = $this->reverseData($request->get('data_fim'));
        $qtde_itens = $request->get('qtde_itens');
    
        $user_data = $request->session()->get('user_data');

        $boleto = new Boleto();

        $dadosBoletos = $boleto->list($data_inicio, $data_fim, $qtde_itens);
		
        $data = array(
            'dadosBoletos' => $dadosBoletos,
            'user_data' => $user_data,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'qtde_itens' => $qtde_itens
        );
        
        return view('modulos/boletos/boletos_lista', $data);
    }

    public function create(Request $request) {
        $user_data = $request->session()->get('user_data');
        $dadosBoleto = new Boleto();

        $bancos = Banco::all();

        $buscaSaldoAReceber = $dadosBoleto->buscaSaldoAReceber(date('Y-m-d'))[0];

        $data = array(
            'user_data' => $user_data,
            'bancos' => $bancos,
            'saldo_a_receber' => number_format((double)$buscaSaldoAReceber->saldo_a_receber, 2, '.', '')
        );

        return view('modulos/boletos/boletos_form', $data);
    }

    public function store(Request $request) {
        $dadosBoleto = new Boleto;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'valor_pago' => 'required',
            'banco_id' => 'required',
            'responsavel_confirmacao_id' => 'numeric'
        ]);

        $dadosBoleto->responsavel_id = $user_data['user_id'];
        $dadosBoleto->banco_id = $request->banco_id;
        $dadosBoleto->data_pagamento = $request->data_pagamento != '' ? $this->reverseData($request->data_pagamento) : null;
        $dadosBoleto->valor_pago = $this->moneyFormat($request->valor_pago);
        $dadosBoleto->observacoes = $request->observacoes;
        $dadosBoleto->updated_at = null;

        try {
            $result = $dadosBoleto->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'boletos', $dadosBoleto->id, null, 2);

                return redirect('/boletos/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            return redirect('/boletos/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosBoleto = Boleto::find($id);

        if(!$dadosBoleto){
            abort(404);
        }

        $bancos = Banco::all();

        $boleto = new Boleto();
        $buscaSaldoAReceber = $boleto->buscaSaldoAReceber($dadosBoleto->created_at->format('Y-m-d'))[0];

        $data = array(
            'dadosBoleto' => $dadosBoleto,
            'user_data' => $user_data,
            'bancos' => $bancos,
            'saldo_a_receber' => number_format((double)$buscaSaldoAReceber->saldo_a_receber, 2, '.', '')
        );

        return view('modulos/boletos/boletos_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'valor_pago' => 'required',
            'banco_id' => 'required',
            'responsavel_confirmacao_id' => 'numeric'
        ]);

        $dadosBoleto = Boleto::find($id);

        $created_at = $dadosBoleto->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {
        
            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/boletos/'.$dadosBoleto->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }
            
        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/boletos/'.$dadosBoleto->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }

        }
        
        $logData = $dadosBoleto->toJson();

        if(!$dadosBoleto){
            abort(404);
        }

        $dadosBoleto->responsavel_id = $user_data['user_id'];
        $dadosBoleto->banco_id = $request->banco_id;
        $dadosBoleto->data_pagamento = $request->data_pagamento != '' ? $this->reverseData($request->data_pagamento) : null;
        $dadosBoleto->valor_pago = $this->moneyFormat($request->valor_pago);
        $dadosBoleto->responsavel_confirmacao_id = $request->responsavel_confirmacao_id == 0 ? null : $request->responsavel_confirmacao_id;
        $dadosBoleto->observacoes = $request->observacoes;

        try {
            $result = $dadosBoleto->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'boletos', $dadosBoleto->id, $logData, 3);

                return redirect('/boletos/'.$dadosBoleto->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/boletos/'.$dadosBoleto->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function confirmacao(Request $request) {
        $id = (int)$request->id;

        if($id == 0) {
            echo "Sem informação...";
        } else {
            $user_data = $request->session()->get('user_data');

            $dadosBoleto = Boleto::find($id);
            $logData = $dadosBoleto->toJson();

            if(!$dadosBoleto){
                abort(404);
            }

            if(is_null($dadosBoleto->responsavel_confirmacao_id) || $dadosBoleto->responsavel_confirmacao_id == 0) {
                $dadosBoleto->responsavel_confirmacao_id = $user_data['user_id'];
            } else {
                $dadosBoleto->responsavel_confirmacao_id = null;
            }
            
            try {
                $result = $dadosBoleto->save();

                if($result) {
                    // general log
                    $log = new LogController();                
                    $log->store($user_data['user_id'], 'boletos', $dadosBoleto->id, $logData, 3);

                    echo "Os dados foram salvos.";
                }

            } catch(\Exception $e) {
                echo "Ocorreu um erro, teten novamente.";
            }
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $dadosBoleto = Boleto::find($id);

        $created_at = $dadosBoleto->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/boletos')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/boletos')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }

        $logData = $dadosBoleto->toJson();

        try {
            $result = $dadosBoleto->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'boletos', $dadosBoleto->id, $logData, 1);

                return redirect('/boletos/')->with('responseSuccess', 'Os dados foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/boletos/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function geraPdf(Request $request) {
        $data_inicio = $request->get('data_inicio');
        $data_fim = $request->get('data_fim');

        $boletos = new Boleto();
        $dadosBoletos = $boletos->list($data_inicio, $data_fim, 250);

        $pdf = PDF::loadView('modulos/boletos/relatorios/geral_pdf', compact('dadosBoletos'));
        return $pdf->setPaper('a4')->stream('boletos');
    }

    public function geraCsv(Request $request) {
        $data_inicio = $request->get('data_inicio');
        $data_fim = $request->get('data_fim');

        $boleto = new Boleto();
        $dadosBoletos = $boleto->list($data_inicio, $data_fim, 250);

        $cabecalho = "ID,Data pagamento,Banco,Valor Pago,Observações \n";
        $corpo = '';
        foreach($dadosBoletos as $boleto) {
            $banco = isset($boleto->banco->nome) ? $boleto->banco->nome : 'Sem banco';
            $valor_pago = "R$ ". isset($boleto->valor_pago) ? number_format((float)$boleto->valor_pago, 2, ",",".") : '';

            
            $corpo .= $boleto->id.','.date('d/m/Y', strtotime($boleto->data_pagamento)).','.$banco.','.$valor_pago.','.$boleto->observacoes."\n";
        }

        $file = md5(rand(1,1000000))."_relatorio.csv";
   
        $report_file = Storage::disk('local')->put('files/reports/'.$file, $cabecalho.$corpo);

        $exists = Storage::disk('local')->exists('files/reports/'.$file);
        if($exists){
            return Storage::download('files/reports/'.$file);
        } else {
            return redirect('/boletos/')->with('messageError', 'Arquivo não encontrdo.');
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

        $dadosBoleto = Log::where('tabela', '=', 'boletos')->where('tabela_id', '=', $id)->get();
        
        $html = '';
        foreach($dadosBoleto as $boleto) {
            $history = json_decode($boleto->previous_data);
            $criadoPor = !empty($boleto->usuario_id) ? Usuario::find($boleto->usuario_id)->nome : '';
            $responsavel = !empty($history->responsavel_id) ? Usuario::find($history->responsavel_id)->nome : '';
            $responsavel_confirmacao = !empty($history->rsponsavel_confirmacao_id) ? Usuario::find($history->rsponsavel_confirmacao_id)->nome : '';
            $banco = !empty($history->banco_id) ? Banco::find($history->banco_id)->nome : '';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Data de Criação Log</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($boleto->created_at)).'</div>'.
            '</div>';
            
            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Log criado por</div>'.
                '<div class="col-md-9">'.$criadoPor.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Reponsável</div>'.
                '<div class="col-md-9">'.$responsavel.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Reponsável Confirmacao</div>'.
                '<div class="col-md-9">'.$responsavel_confirmacao.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Banco</div>'.
                '<div class="col-md-9">'.$banco.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Valor pago</div>'.
                '<div class="col-md-9"> R$'.number_format((float)$history->valor_pago, 2, ",",".").'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Data pagamento</div>'.
                '<div class="col-md-9">'.date('d/m/Y', strtotime($history->data_pagamento)).'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Observacoes</div>'.
                '<div class="col-md-9">'.$history->observacoes.'</div>'.
            '</div>';

            $html .= '<div class="row modal-table">'.
                '<div class="col-md-3">Criado em</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($history->created_at)).'</div>'.
            '</div>';

            $html .= '<div class="mb-4 row modal-table">'.
                '<div class="col-md-3">Atualizado em</div>'.
                '<div class="col-md-9">'.date('d/m/Y H:i:s', strtotime($history->updated_at)).'</div>'.
            '</div>';
        }

        echo($html);
    }
}
