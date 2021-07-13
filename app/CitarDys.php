<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CitarDys extends Model
{
    protected $table = 'citar_dys';
    public $timestamps = true;

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'usuario_id');
    }

    public function responsavelConfirmacao(){
        return $this->belongsTo('App\Usuario', 'confirmacao_id');
    }

    public function saldosDinamico($date) {
        $query = DB::select("
            SELECT 
            SUM(c.valor_pago) AS valor_ja_pago,
            '67193806.570008' AS saldo_inicial,
            (SELECT sum(COALESCE(r.valor)) AS valor_a_receber FROM registros AS r WHERE r.status_ultimo = 1 AND r.created_at <= date('".$date."') ) AS valorReceber,
            (SELECT sum(COALESCE(rr.valor)) AS valor_a_receber FROM registros AS rr WHERE rr.status_ultimo = 1  AND rr.created_at <= date('".$date."') ) - SUM(c.valor_pago)-1655690.34 AS valor_a_receber_atualizado
            FROM citar_dys AS c
            WHERE c.created_at <= date('".$date."')
        ");

        return $query;
    }

    public function list($data_inicio, $data_fim, $banco_recebimento, $qtde_itens){
		
		if(isset($data_inicio)&&$data_inicio!=null){
			$data_inicio = $data_inicio;
		} else {
			$data_inicio = date('Y/m/d', strtotime('-45 days', strtotime(date('Y-m-d'))));
		}
		
		if(isset($data_inicio)&&$data_inicio!=null&&isset($data_fim)&&$data_fim!=null){
			$data_fim = $data_fim.' 23:59:59';
		} else {
			$data_fim = date('Y-m-d H:i:s');
		}
		
		$query = CitarDys::select('citar_dys')
                            ->leftjoin('registros AS r',  DB::raw('date(r.created_at)'), '=', DB::raw('date(citar_dys.created_at)'))
                            ->select(
                                        'citar_dys.*',
                                        DB::raw('(sum(COALESCE(r.valor,0))-sum(COALESCE(r.estorno,0))) AS valor_total_saque'),
										DB::raw("(SELECT count(cc.id) AS qtd_pagamentos_do_dia FROM citar_dys AS cc WHERE date(cc.created_at) = date(citar_dys.created_at)) AS qtd_pagamentos_do_dia"),
										DB::raw('(SELECT sum(COALESCE(ccc.valor_pago)) AS total_pago_dia FROM citar_dys AS ccc WHERE DATE_FORMAT(ccc.created_at,"%Y-%m-%d") = DATE_FORMAT(citar_dys.created_at,"%Y-%m-%d")) AS total_pago_dia')
                                    )
                            ->where('citar_dys.created_at', '>=', DB::raw('"'.$data_inicio.'"'))
							->where('citar_dys.created_at', '<=', DB::raw('"'.$data_fim.'"'))
                            /*->groupBy('citar_dys.valor_pago')
							->groupBy('citar_dys.comprovante')*/
							->groupBy('citar_dys.id')
							->orderByDesc('citar_dys.data_pagamento')
							->orderByDesc('citar_dys.id')
							/*->toSql()*/;
	   if(!empty($banco_recebimento)) {
            $query->where('citar_dys.banco_que_recebemos', '=', $banco_recebimento);
        }
    
		//$query->toSql();
		//dd($query);
	   $data = $query->paginate(20);
	   
        return $data;
	
    }

    public function buscaSaldoAReceber($date) { 

        $query = DB::select(
            "
            SELECT 
			(
			(SELECT sum(COALESCE(r.valor,0))-sum(COALESCE(r.estorno,0)) AS valor_a_receber FROM registros AS r WHERE r.created_at <= ('".$date." 23:59:59'))
			-
			(SELECT sum(COALESCE(c.valor_pago)) AS valor_pago FROM citar_dys AS c WHERE c.data_pagamento <= ('".$date." 23:59:59'))

			)+66747395.890009806 AS saldo_a_receber
            "
        );

		//67193806.570008 saldo inicial da citar, onde nao havia controle na planilha $query[0]->saldo_a_receber

        return $query;
    }    
}
