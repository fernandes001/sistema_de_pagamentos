<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    protected $table = 'logs';
    public $timestamps = false;

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'usuario_id');
    }

    public function getLogById($tabela, $id) {
        $query = Log::select();

        $query->where('tabela_id', '=', $id);
        $data = $query->where('tabela', '=', $tabela)->whereNotNull('previous_data')->get();

        return $data;
    }

    public function list($usuario, $tipo, $tabela, $created_at, $qtde_itens){
        $query = Log::from('logs as l')->select('l.*', 'u.nome as nome_usuario')
        ->join('usuarios as u', function ($join) {
            $join->on('l.usuario_id', '=', 'u.id');
        });

        if(!empty($usuario)){
            $query->where('u.nome', 'like', '%'.$usuario.'%');
        }

        if(!empty($tipo)) {
            $query->where('l.tipo', '=', $tipo);
        }

        if(!empty($tabela)) {
            $query->where('l.tabela', '=', $tabela);
        }

        if(!empty($created_at)) {
            $query->where(DB::raw('date(l.created_at)'), '=', $created_at);
        }

        if(empty($qtde_itens)) {
            $qtde_itens = 20;
        }

        $data = $query->orderByDesc('l.id')->paginate($qtde_itens);
    
        return $data;
    }
}
