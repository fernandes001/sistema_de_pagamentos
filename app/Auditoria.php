<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    public $timestamps = true;

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'responsavel_id');
    }

    public function confirmacao(){
        return $this->belongsTo('App\Usuario', 'confirmacao_id');
    }

    public function saldoCredito($date) {
        $sql = "
            SELECT 
            (
            (SELECT sum(COALESCE(r.valor,0))-sum(COALESCE(r.estorno,0)) AS valor_a_receber FROM registros AS r WHERE r.created_at <= '".$date." 23:59:59')
            -
            (SELECT sum(COALESCE(c.valor_pago,0)) AS valor_pago FROM citar_dys AS c WHERE c.data_pagamento <= '".$date." 23:59:59')

            )+66747395.890009806 AS saldo_a_receber
        ";

        $query = DB::select($sql);

        return $query[0];
    }
}
