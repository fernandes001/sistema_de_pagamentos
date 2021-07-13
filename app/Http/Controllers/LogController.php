<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Cliente;
use App\Usuario;
use App\Banco;

class LogController extends Controller
{
    public $ip = '';

    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'qtde_itens' => 'nullable|numeric'
        ]);

        $usuario = $request->get('usuario');
        $tipo = $request->get('tipo');
        $tabela = $request->get('tabela');
        $created_at = $this->reverseData($request->get('created_at'));
        $qtde_itens = $request->get('qtde_itens');

        $log = new Log();
        $dadosLogs = $log->list($usuario, $tipo, $tabela, $created_at, $qtde_itens);

        $data = array(
            'dadosLogs' => $dadosLogs,
            'user_data' => $user_data,
            'usuario' => $usuario,
            'tipo' => $tipo,
            'tabela' => $tabela,
            'created_at' => $created_at,
            'qtde_itens' => $qtde_itens
        );
        
        return view('modulos/logs/logs_lista', $data);
    }

    public function show(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosLog = Log::find($id);

        if(!$dadosLog){
            abort(404);
        }

        $data = array(
            'dadosLog' => $dadosLog,
            'user_data' => $user_data
        );

        return view('modulos/logs/logs_show', $data);
    }

    // Global function, not necessary ACL
    public function store($usuario_id, $tabela, $tabela_id, $data, $tipo) {
        $log = new Log;

        $log->usuario_id = $usuario_id;
        $log->tabela = $tabela;
        $log->tabela_id = $tabela_id;
        $log->previous_data = $data;
        $log->tipo = $tipo; // 0 -> Login, 1 -> Deleção, 2 -> Criação, 3 -> Alteração
        $log->ip = $this->ip;
        $log->created_at = date('Y-m-d H:i:s');

        try {
            $log->save();
        } catch(\Exception $e) {
            echo "<pre>";
            var_dump($e);
            die;

            return false;
        }
    }

    public function getRealPreviousDataDetails($id) {
        $id = (int)$id;

        $dadosLog = Log::find($id);

        $history = json_decode($dadosLog->previous_data);

        $arrTiposOperacoes = array(
            1 => 'TED',
            2 => 'Boleto',
            3 => 'TEV',
            4 => 'Desconhecido'
        );

        $arrStatusUltimo = array(
            0 => 'Pendente',
            1 => 'Realizado',
            2 => 'Cancelado',
            3 => 'Estornado',
            4 => 'Aguardando comprovante',
            6 => 'Progresso',
            7 => 'Erro',
            8 => 'Refeito'
        );

        $arrHistory = array(
            'cliente_id' => isset(Cliente::find($history->cliente_id)->nome) ? Cliente::find($history->cliente_id)->nome : '',
            'banco_id' => isset(Banco::find($history->banco_id)->nome) ? Banco::find($history->banco_id)->nome : '',
            'responsavel_id' => isset(Usuario::find($history->responsavel_id)->nome) ? Usuario::find($history->responsavel_id)->nome : '',
            'confirmacao_id' => isset(Usuario::find($history->confirmacao_id)->nome) ? Usuario::find($history->confirmacao_id)->nome : '',
            'tipo' => $arrTiposOperacoes[$history->tipo],
            'status_ultimo' => $arrStatusUltimo[$history->status_ultimo],
            'favorecido' => $history->favorecido,
            'valor' => $history->valor,
            'estorno' => $history->estorno,
            'observacoes' => $history->observacoes,
            'created_at' => $history->created_at,
            'url_comprovante' => $history->url_comprovante
        );

        $html = '
            <div class="row">
                <div class="col-lg-12"><h5>Dados extras</h5></div>

                <div class="col-md-12"><b>Cliente: </b>'.$arrHistory['cliente_id'].'</div>
                <div class="col-md-12"><b>Banco: </b>'.$arrHistory['banco_id'].'</div>
                <div class="col-md-12"><b>Criação: </b>'.$arrHistory['responsavel_id'].'</div>
                <div class="col-md-12"><b>Responsável: </b>'.$arrHistory['confirmacao_id'].'</div>
                <div class="col-md-12"><b>Tipo: </b>'.$arrHistory['tipo'].'</div>
                <div class="col-md-12"><b>Status: </b>'.$arrHistory['status_ultimo'].'</div>
                <div class="col-md-12"><b>Favorecido: </b>'.$arrHistory['favorecido'].'</div>
                <div class="col-md-12"><b>Valor: </b> R$ '.(!is_null($arrHistory['valor']) ? number_format((float)$arrHistory['valor'], 2, ",",".") : "0,00").'</div>
                <div class="col-md-12"><b>Estorno: </b> R$ '.(!is_null($arrHistory['estorno']) ? number_format((float)$arrHistory['estorno'], 2, ",",".") : "0,00").'</div>
                <div class="col-md-12"><b>Data de criação: </b>'.date('d/m/Y H:i:s', strtotime($arrHistory['created_at'])).'</div>
                <div class="col-md-12"><b>Comprovante: </b>'.$arrHistory['url_comprovante'].'</div>
                <div class="col-md-12"><b>Observações: </b>'.$arrHistory['observacoes'].'</div>
            </div>
        ';

        echo $html;
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
