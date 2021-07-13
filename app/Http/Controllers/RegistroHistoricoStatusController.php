<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RegistroHistoricoStatus;

class RegistroHistoricoStatusController extends Controller
{
    public function store($registro_id, $current_status, $usuario_id) {
        $log = new RegistroHistoricoStatus;

        $log->registro_id = $registro_id;
        $log->status = $current_status;
        $log->usuario_id = $usuario_id;
        $log->created_at = date('Y-m-d H:i:s');

        try {
            $log->save();
        } catch(\Exception $e) {
            return false;
        }
    }
}
