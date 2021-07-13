<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planilha extends Model
{
    protected $table = 'planilhas';
    public $timestamps = true;

    public function banco(){
        return $this->belongsTo('App\Banco', 'banco_id');
    }
}
