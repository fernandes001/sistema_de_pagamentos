<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Boleto extends Model
{
    protected $table = 'boletos';
    public $timestamps = true;

    public function responsavel(){
        return $this->belongsTo('App\Usuario', 'responsavel_id');
    }

    public function responsavelConfirmacao(){
        return $this->belongsTo('App\Usuario', 'responsavel_confirmacao_id');
    }

    public function banco(){
        return $this->belongsTo('App\Banco', 'banco_id');
    }
	
    public function list($data_inicio, $data_fim, $qtde_itens){

		$query = Boleto::select('b.*', 'r.created_at AS data', 'r.id AS id_registros')->from('registros as r')
				->leftJoin('boletos as b', DB::raw('DATE_FORMAT(r.created_at,"%Y-%m-%d")'), '=', DB::raw('DATE_FORMAT(b.data_pagamento,"%Y-%m-%d")'));

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(b.data_pagamento)'), '=', $data_inicio);
        }
        
        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim.' 23:59:59');
                }
            });
        }
		
		$query->groupBy(DB::raw('DATE_FORMAT(r.created_at,"%Y-%m-%d")'));
		
        if(empty($qtde_itens)) {
            $qtde_itens = 20;
        }

        $data = $query->orderByDesc('r.created_at')->paginate($qtde_itens);

        return $data;
    }

    public function buscaSaldoAReceber($date) {
		//1152660 = saldo inicial de quando nao havia controle diario
        $sql = "
            SELECT (SELECT count(r.id)*10 AS total_a_receber FROM registros AS r WHERE r.created_at <= '".$date." 23:59:59') 
            -
            (SELECT SUM(b.valor_pago) FROM boletos AS b WHERE b.created_at <= '".$date." 23:59:59')-(1152660) AS saldo_a_receber
            ";
		//die($sql);
		$query = DB::select($sql);

        return $query;
    }
}
