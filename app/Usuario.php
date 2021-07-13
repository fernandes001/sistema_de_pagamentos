<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    protected $table = 'usuarios';
    public $timestamps = true;
    
    public function logar($email, $password){
		return Usuario::where('email', $email)->where('senha', $password)->where('ativo', 1)->first();
    }
    
    public function grupo(){
        return $this->belongsTo('App\Grupo', 'grupo_id');
    }

    /**
     * Get user roles array by your group id
     * @param  Integer 	$grupo_id 	group id
     * @return array
     */
	public function getRoles($grupo_id){
		$data = DB::table('grupos_x_regras')
				->join('regras', 'regras.id', '=', 'grupos_x_regras.regra_id')
				->join('modulos', 'modulos.id', '=', 'regras.modulo_id')
				->join('grupos', 'grupos.id', '=', 'grupos_x_regras.grupo_id')
				->select(
							'grupos.titulo as grupo', 
							'modulos.uri as modulo_uri', 
							'modulos.id as modulo_id', 
							'modulos.titulo', 
							'regras.acao as acao'
				)
				->where('grupos_x_regras.grupo_id', '=', $grupo_id)
				->get();

		$re = [];
		foreach($data as $regra){
			$site = array_key_exists('dys', $re);
			
			if(!$site){
				$re['dys'] = array("roles" => array());
				$re['dys']["extra"] = '';
				array_push($re['dys']["roles"], array(
					'modulo_id' => $regra->modulo_id,
					'modulo_uri' => $regra->modulo_uri,
					'acao' => $regra->acao
				));
			} else {
				array_push($re['dys']["roles"], array(
					'modulo_id' => $regra->modulo_id,
					'modulo_uri' => $regra->modulo_uri,
					'acao' => $regra->acao
				));
			}
		}

		return $re;
	}

	public function list($search, $filters = null){
        $query = Usuario::select();
        if(!empty($search)){
            $query->where('email', 'like', '%'.$search.'%');
        }

        $data = $query->orderBy('nome')->paginate(20);

        return $data;
    }
}
