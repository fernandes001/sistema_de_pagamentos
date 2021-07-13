<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RegistroHistoricoStatusController;
use App\Http\Controllers\RegraController;
use App\Registro;
use App\Banco;
use App\Cliente;
use App\RegistroHistoricoStatus;
use App\Usuario;
use App\Log;
use PDF;

class RegistroController extends Controller
{
    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');
        $roles = $request->session()->get("regras");
        
        $this->validate($request, [
            'qtde_itens' => 'nullable|numeric'
        ]);

        $data_inicio =  $this->reverseData($request->get('data_inicio'));
        $data_fim = $this->reverseData($request->get('data_fim'));
        $favorecido = $request->get('favorecido');
        $banco_id = $request->get('banco_id');
        $status_ultimo = $request->get('status_ultimo');
        $tipo = $request->get('tipo');
        $valor = $this->moneyFormat($request->get('valor'));
        $id = $request->get('id');
        $qtde_itens = $request->get('qtde_itens');

        $registro = new Registro();
        $dadosRegistros = $registro->list($data_inicio, $data_fim, $favorecido, $banco_id, $status_ultimo, $tipo, $valor, $id, $qtde_itens);

        $relProdutividade = $registro->relProdutividade($data_inicio, $data_fim);
        $relContabilidade = $registro->relContabilidade($data_inicio, $data_fim);
        $relFechamentoDoDia = $registro->relFechamentoDoDia($data_inicio, $data_fim);
        $relContabilidadeEstorno = $registro->relContabilidadeEstorno($data_inicio, $data_fim);

        $checkRegra = new RegraController();
        $editPer = $checkRegra->checkRoleByRoleName($roles, 'registros.edit');
        $createPer = $checkRegra->checkRoleByRoleName($roles, 'registros.create');
        
        // dados necessários no formulário de cadastro
        $dadosBancos = Banco::all();
        $dadosClientes = Cliente::all();
        //$dadosPaises = Pais::all();

        // 2020-05-06
        $data = array(
            'dadosRegistros' => $dadosRegistros,
            'user_data' => $user_data,
            'relProdutividade' => $relProdutividade,
            'relContabilidade' => $relContabilidade,
            'relFechamentoDoDia' => $relFechamentoDoDia,
            'relContabilidadeEstorno' => $relContabilidadeEstorno,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'favorecido' => $favorecido,
            'banco_id' => $banco_id,
            'status_ultimo' => $status_ultimo,
            'tipo' => $tipo,
            'valor' => $valor,
            'id' => $id,
            'qtde_itens' => $qtde_itens,
            'editPer' => $editPer,
            'createPer' => $createPer,
            'bancos' => $dadosBancos,
            'clientes' => $dadosClientes
        );
        
        return view('modulos/registros/registros_lista', $data);
    }

    public function create(Request $request) { // DISABLED
        $dadosBancos = Banco::all();
        $dadosClientes = Cliente::all();
        $user_data = $request->session()->get('user_data');

        $data = array(
            'bancos' => $dadosBancos,
            'clientes' => $dadosClientes,
            'user_data' => $user_data
        );

        return view('modulos/registros/registros_form', $data);
    }

    public function store(Request $request) {
        $registro = new Registro;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'cliente_id' => 'required',
            'banco_id' => 'required',
            'favorecido' => 'required',
            'valor' => 'required',
            'tipo' => 'required|numeric',
            'saque_id' => 'required',
            'status_ultimo' => 'required|numeric'
        ]);

        $registro->cliente_id = $request->cliente_id;
        $registro->banco_id = $request->banco_id;
        $registro->favorecido = $request->favorecido;
        $registro->valor = $this->moneyFormat($request->valor);
        $registro->tipo = $request->tipo;
        $registro->saque_id = $request->saque_id;
        $registro->status_ultimo = $request->status_ultimo;
        $registro->responsavel_id = $user_data['user_id'];
        $registro->estorno = $this->moneyFormat($request->estorno);
        $registro->url_comprovante = $request->url_comprovante;
        $registro->observacoes = $request->observacoes;

        $registro->updated_at = null;

        try {
            $result = $registro->save();

            if($result) {
                // general log
                $log = new LogController();                
                $log->store($user_data['user_id'], 'registros', $registro->id, null, 2);

                //return redirect('/registros/')->with('responseSuccess', 'Os dados foram salvos.');
                $arr = array(
                    'error' => null,
                    'success' => array(
                        'msg' => 'Os dados foram salvos'
                    )
                );
                echo json_encode($arr);
            }
        } catch(\Exception $e) {
            //return redirect('/registros/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
            
            $arr = array(
                'error' => 'Ocorreu um erro, tente novamente.',
                'success' => null
            );
            echo json_encode($arr);
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosRegistro = Registro::find($id);
        $dadosBancos = Banco::all();
        $dadosClientes = Cliente::all();
        
        $dadosRegistroStatus = RegistroHistoricoStatus::where('registro_id', '=', $id)->get();

        if(!$dadosRegistro){
            abort(404);
        }

        $data = array(
            'dadosRegistro' => $dadosRegistro,
            'bancos' => $dadosBancos,
            'clientes' => $dadosClientes,
            'user_data' => $user_data,
            'dadosRegistroStatus' => $dadosRegistroStatus
        );

        return view('modulos/registros/registros_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'cliente_id' => 'required',
            'banco_id' => 'required',
            'favorecido' => 'required',
            'valor' => 'required',
            'tipo' => 'required|numeric',
            'saque_id' => 'required',
            'status_ultimo' => 'required|numeric',
            'confirmacao_id' => 'numeric'
        ]);

        $dadosRegistro = Registro::find($id);

        $created_at = $dadosRegistro->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/registros/'.$dadosRegistro->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }

        } else {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/registros/'.$dadosRegistro->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }
            
        }

        $logData = $dadosRegistro->toJson();

        $status_ultimo = $dadosRegistro->status_ultimo;

        if(!$dadosRegistro){
            abort(404);
        }

        if($user_data['grupo_id'] == 3) {
            $dadosRegistro->created_at = $this->reverseData($request->created_at, true);
        }
        $dadosRegistro->cliente_id = $request->cliente_id;
        $dadosRegistro->banco_id = $request->banco_id;
        $dadosRegistro->favorecido = $request->favorecido;
        $dadosRegistro->valor = $this->moneyFormat($request->valor);
        $dadosRegistro->tipo = $request->tipo;
        $dadosRegistro->saque_id = $request->saque_id;
        $dadosRegistro->status_ultimo = $request->status_ultimo;
        $dadosRegistro->responsavel_id = $user_data['user_id'];
        $dadosRegistro->confirmacao_id = $request->confirmacao_id == 0 ? null : $request->confirmacao_id;
        $dadosRegistro->estorno = $this->moneyFormat($request->estorno);
        $dadosRegistro->url_comprovante = $request->url_comprovante;
        $dadosRegistro->observacoes = $request->observacoes;

        try {
            $result = $dadosRegistro->save();

            if($result) {
                // general log
                $log = new LogController();                
                $log->store($user_data['user_id'], 'registros', $dadosRegistro->id, $logData, 3);

                // status log
                $statusLog = new RegistroHistoricoStatusController();
                $statusLog->store($dadosRegistro->id, $status_ultimo, $user_data['user_id']);

                return redirect('/registros/'.$dadosRegistro->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            echo "<pre>";
            var_dump($e);
            die;

            return redirect('/registros/'.$dadosRegistro->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function confirmacao(Request $request) {
        $id = (int)$request->id;

        $user_data = $request->session()->get('user_data');

        $dadosRegistro = Registro::find($id);
        $logData = $dadosRegistro->toJson();

        //$status_ultimo = $dadosRegistro->status_ultimo;

        if(!$dadosRegistro){
            abort(404);
        }

        if(is_null($dadosRegistro->confirmacao_id) || $dadosRegistro->confirmacao_id == 0) {
            $dadosRegistro->confirmacao_id = $user_data['user_id'];
            //$dadosRegistro->status_ultimo = 1;
        } else {
            $dadosRegistro->confirmacao_id = null;
        }
        
        try {
            $result = $dadosRegistro->save();

            if($result) {
                // general log
                $log = new LogController();                
                $log->store($user_data['user_id'], 'registros', $dadosRegistro->id, $logData, 3);

                // status log
                //$statusLog = new RegistroHistoricoStatusController();
                //$statusLog->store($dadosRegistro->id, $status_ultimo, $user_data['user_id']);

                echo "Os dados foram salvos.";
            }

        } catch(\Exception $e) {
            echo "Ocorreu um erro, teten novamente.";
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $dadosRegistro = Registro::find($id);

        $created_at = $dadosRegistro->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        

        if($dia == 1) {

            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/registros')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/registros')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }


        $logData = $dadosRegistro->toJson();

        try {
            $result = $dadosRegistro->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'registros', $dadosRegistro->id, $logData, 1);

                return redirect('/registros/')->with('responseSuccess', 'Os dados foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/registros/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function getLogs(Request $request) {
        $id = (int)$request->id;

        $log = new Log();
        $data = $log->getLogById('registros', $id);


        $dataNew = array();

        $arrTipos = array(
            1 => 'Deleção',
            2 => 'Criação',
            3 => 'Alteração'
        );

        $arrTiposOperacoes = array(
            1 => 'TED',
            2 => 'Boleto',
            3 => 'TEV',
            4 => 'Desconhecido'
        );

        foreach($data as $key => $d) {
            $banco = Banco::find(json_decode($d->previous_data)->banco_id);
            $valor = number_format((float)json_decode($d->previous_data)->valor, 2, ",",".");
            $responsavel = !empty(json_decode($d->previous_data)->responsavel_id) ? Usuario::find(json_decode($d->previous_data)->responsavel_id)->nome : '';
            $confirmacao = !empty(json_decode($d->previous_data)->confirmacao_id) ? Usuario::find(json_decode($d->previous_data)->confirmacao_id)->nome : '';
            $data_criacao = !empty($d->created_at) ? date('d/m/Y H:i:i', strtotime($d->created_at)) : '';

            $dataNew[$key]['data'] = $d->previous_data;
            $dataNew[$key]['tipo'] = $arrTiposOperacoes[json_decode($d->previous_data)->tipo];
            $dataNew[$key]['banco'] = $banco->nome;
            $dataNew[$key]['valor'] = $valor;
            $dataNew[$key]['status'] = '';
            $dataNew[$key]['responsavel'] = $responsavel;
            $dataNew[$key]['confirmacao'] = $confirmacao;
            $dataNew[$key]['data_criacao'] = $data_criacao;
            $dataNew[$key]['tipo_log'] = $arrTipos[$d->tipo];
        }

   
        try {
            $arr = array(
                'error' => null,
                'success' => array(
                    'msg' => 'Os dados foram salvos',
                    'data' => $dataNew
                )
            );

            echo json_encode($arr);
        } catch(\Exception $e) {
            $arr = array(
                'error' => 'Ocorreu um erro, tente novamente.',
                'success' => null
            );

            echo json_encode($arr);
        }
    }

    public function geraPdf(Request $request) {
        $data_inicio =  $request->get('data_inicio');
        $data_fim = $request->get('data_fim');
        $favorecido = $request->get('favorecido');
        $banco_id = $request->get('banco_id');
        $status_ultimo = $request->get('status_ultimo');
        $tipo = $request->get('tipo');
        $valor = $this->moneyFormat($request->get('valor'));
        $id = $request->get('id');
        $qtde_itens = $request->get('qtde_itens');

        $registro = new Registro();
        $dadosRegistros = $registro->list($data_inicio, $data_fim, $favorecido, $banco_id, $status_ultimo, $tipo, $valor, $id, 250);

        $pdf = PDF::loadView('modulos/registros/relatorios/geral_pdf', compact('dadosRegistros'));
        return $pdf->setPaper('a4')->stream('teste');
    }

    public function geraCsv(Request $request) {
        $data_inicio =  $request->get('data_inicio');
        $data_fim = $request->get('data_fim');
        $favorecido = $request->get('favorecido');
        $banco_id = $request->get('banco_id');
        $status_ultimo = $request->get('status_ultimo');
        $tipo = $request->get('tipo');
        $valor = $this->moneyFormat($request->get('valor'));
        $id = $request->get('id');
        $qtde_itens = $request->get('qtde_itens');

        $registro = new Registro();
        $dadosRegistros = $registro->list($data_inicio, $data_fim, $favorecido, $banco_id, $status_ultimo, $tipo, $valor, $id,  250);

        $cabecalho = "ID,Cliente,Banco,Valor,Status,Tipo,Responsável,Confirmação,Data de criação,Observações \n";
        $corpo = '';
        foreach($dadosRegistros as $registro) {
            $banco = $registro->banco->nome;
            $valor = "R$ ". number_format((float)$registro->valor, 2, ",",".");
            $responsavel = isset($registro->usuario->nome) ? $registro->usuario->nome : '';
            $confirmado = isset($registro->confirmacao->nome) ? $registro->confirmacao->nome : '';
            $data_criacao = isset($registro->created_at) ? date('d/m/Y H:i:i', strtotime($registro->created_at)) : '';
            
            $corpo .= $registro->id.',';
            $corpo .= $registro->favorecido.',';
            $corpo .= $banco.',';
            $corpo .= $valor.',';
            $corpo .= '----'.',';
            $corpo .= $responsavel.',';
            $corpo .= $confirmado.',';
            $corpo .= $data_criacao.',';
            $corpo .= $registro->observacoes."\n";
        }

        $file = md5(rand(1,1000000))."_relatorio.csv";
   
        $report_file = Storage::disk('local')->put('files/reports/'.$file, $cabecalho.$corpo);

        $exists = Storage::disk('local')->exists('files/reports/'.$file);
        if($exists){
            return Storage::download('files/reports/'.$file);
        } else {
            return redirect('/registros/')->with('messageError', 'Arquivo não encontrdo.');
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

    public function reverseData($data, $hora = false) {
        if($hora == true) {
            $arr = explode(' ', $data);

            $data = $arr[0];
            $horaNew = $arr[1];
        }

        $data = explode('/', $data);

        if(count($data) < 3) {
            return '';
        }

        if($hora == true) {
            $data = $data[2].'-'.$data[1].'-'.$data[0].' '.$horaNew;
        } else {
            $data = $data[2].'-'.$data[1].'-'.$data[0];
        }
        
        return $data;
    }
}
