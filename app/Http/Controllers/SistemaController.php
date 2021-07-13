<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SistemaController extends Controller
{
    public function index(Request $request) {
        $user_data = $request->session()->get('user_data');

        $data = array(
            'user_data' => $user_data
        );

        return view('index', $data);
    }
}
