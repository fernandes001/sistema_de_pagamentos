<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banco extends Model
{
    protected $table = 'bancos';
    public $timestamps = true;

    public function list($banco_nome, $qtde_itens){
        $query = Banco::select();
        if(!empty($banco_nome)){
            $query->where('nome', 'like', '%'.$banco_nome.'%');
        }

        if(empty($qtde_itens)) {
            $qtde_itens = 20;
        }

        $data = $query->orderByDesc('id')->paginate($qtde_itens);

        return $data;
    }

    public function bancosAuditoriaBancos($auditoria_id) {
        $query = DB::table('bancos as b')
        ->select()
        ->leftJoin('auditoria_bancos as ab', function($ljoin) {
            $ljoin->on('b.id', '=', 'ab.banco_id');
        })
        ->where('ab.auditoria_id', '=', $auditoria_id)
        ->orderBy('b.id');

        $data = $query->get();
    
        return $data;
    }
}
