<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Registro extends Model
{
    protected $table = 'registros';
    public $timestamps = true;

    public function banco(){
        return $this->belongsTo('App\Banco', 'banco_id');
    }

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'responsavel_id');
    }

    public function confirmacao(){
        return $this->belongsTo('App\Usuario', 'confirmacao_id');
    }

    public function list($data_inicio, $data_fim, $favorecido, $banco_id, $status_ultimo, $tipo, $valor, $id, $qtde_itens){
        $query = Registro::select('r.*')->from('registros as r');

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(r.created_at)'), '=', $data_inicio);
        }
        
        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim);
                }
            });
        }

        if(!empty($banco_id)) {
            $query->where('r.banco_id', '=', $banco_id);
        }

        if(!empty($status_ultimo)) {
            $query->where('r.status_ultimo', '=', $status_ultimo);
        }

        if(!empty($tipo)) {
            $query->where('r.tipo', '=', $tipo);
        }

        if(!empty($valor)) {
            $query->where('r.valor', '=', $valor);
        }

        if(!empty($id)) {
            $query->where('r.id', '=', $id);
        }

        $query->where('r.favorecido', 'like', '%'.$favorecido.'%');
        
        if(empty($qtde_itens)) {
            $qtde_itens = 50;
        }

        $data = $query->orderByDesc('created_at')->paginate($qtde_itens);

        return $data;
    }

    public function relProdutividade($data_inicio, $data_fim) {
        $query = DB::table('registros as r')->select('u.nome', DB::raw('count(u.nome) as qtd'))
        ->join('usuarios as u', function ($join) {
            $join->on('r.responsavel_id', '=', 'u.id');
            $join->orOn('r.confirmacao_id', '=' ,'u.id');
        });

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(r.created_at)'), '=', $data_inicio);
        }

        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim);
                }
            });
        }
		
		//apenas lista usuários da DYS
		$query->where(DB::raw('u.empresa'), '=', 1);
		
        $query->groupBy('u.nome');
        $data = $query->get();

        return $data;
    }

    public function relContabilidade($data_inicio, $data_fim) {
        $query = DB::table('registros as r')->select(DB::raw('sum(r.valor) as valor'), DB::raw('count(r.id) as qtde_registros'), 'b.nome')
        ->join('bancos as b', function($join){
            $join->on('r.banco_id', '=', 'b.id');
        });

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(r.created_at)'), '=', $data_inicio);
        }

        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim);
                }
            });
        }

        $query->groupBy('b.nome');
        $data = $query->get();

        return $data;      
    }

    public function relFechamentoDoDia($data_inicio, $data_fim) {
        $query = DB::table('registros as r')
        ->select(
            DB::raw('sum(r.valor) as total_valor_pago'),
            DB::raw('sum(r.estorno) as total_valor_estorno'), 
            DB::raw('(sum(r.valor) - sum(r.estorno)) AS valor_receber'),
            DB::raw('count(r.id) AS total_de_saques')
        );

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(r.created_at)'), '=', $data_inicio);
        }

        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim);
                }
            });
        }

        $data = $query->get();

        return $data;
    }

    public function relContabilidadeEstorno($data_inicio, $data_fim) {
        $query = DB::table('registros as r')->select(DB::raw('sum(r.estorno) AS estorno'), 'b.nome')
        ->join('bancos as b', function($join){
            $join->on('r.banco_id', '=', 'b.id');
        });

        if(!empty($data_inicio) && empty($data_fim)) {
            $query->where(DB::raw('date(r.created_at)'), '=', $data_inicio);
        }

        if(!empty($data_inicio) && !empty($data_fim)) {
            $query->where(function($subquery) use($data_inicio, $data_fim){
                if($data_inicio != '' && $data_fim != '') {
                    $subquery->where(DB::raw('date(r.created_at)'), '>=', $data_inicio);
                    $subquery->where(DB::raw('date(r.created_at)'), '<=', $data_fim);
                }
            });
        }

        $query->groupBy('b.nome');
        $data = $query->get();

        return $data;
    }

    public function auditoriaItens() {
        $query = DB::table('registros as r')->select(
            DB::raw('(sum(r.valor) - sum(r.estorno)) as valor'), 
            DB::raw('DATE_FORMAT(r.created_at, "%Y-%m-%d") as created_at')
        )
        ->leftJoin('auditoria as au', function($join){
            $join->on(DB::raw('DATE_FORMAT(au.created_at, "%Y-%m-%d")'), '=', DB::raw('DATE_FORMAT(r.created_at, "%Y-%m-%d")'));
        })
        ->groupBy(DB::raw('DATE_FORMAT(r.created_at, "%Y-%m-%d")'));

        $data = $query->orderByDesc(DB::raw('DATE_FORMAT(r.created_at, "%Y-%m-%d")'))->paginate(20);
  
        return $data;
    }
}
