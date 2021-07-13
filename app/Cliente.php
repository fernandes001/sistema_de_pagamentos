<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = true;

    public function list($search, $filters = null){
        $query = Cliente::select();
        if(!empty($search)){
            $query->where('nome', 'like', '%'.$search.'%');
        }

        $data = $query->orderByDesc('id')->paginate(20);

        return $data;
    }
}
