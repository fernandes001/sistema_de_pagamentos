<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroHistoricoStatus extends Model
{
    protected $table = 'registros_historico_status';
    public $timestamps = false;

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'usuario_id');
    }
}
