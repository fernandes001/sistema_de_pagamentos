<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Pais;
use App\Estado;
use App\Cidade;

class ClienteController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');
        $user_data = $request->session()->get('user_data');
        $pagina = 'lista_clientes';

        $cliente = new Cliente();
        $dadosClientes = $cliente->list($search);

        $data = array(
            'dadosClientes' => $dadosClientes,
            'user_data' => $user_data,
            'pagina' => $pagina
        );
        
        return view('modulos/clientes/clientes_lista', $data);
    }

    public function create(Request $request) {
        $user_data = $request->session()->get('user_data');

        $dadosPaises = Pais::all();

        $data = array(
            'user_data' => $user_data,
            'paises' => $dadosPaises,
            'dadosPaises' => $dadosPaises,
        );

        return view('modulos/clientes/clientes_form', $data);
    }

    public function store(Request $request) {
        $cliente = new Cliente();
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:150',
            'email' => 'max:150',
            'cpf_cnpj' => 'max:18',
            'telefone' => 'max:18',
        ]);

        $cliente->nome = $request->nome;
        $cliente->email = $request->email;
        $cliente->cpf_cnpj = $request->cpf_cnpj;
        $cliente->telefone = $request->telefone;
        $cliente->pais_id = $request->pais_id;
        $cliente->estado_id = $request->estado_id;
        $cliente->cidade_id = $request->cidade_id;
        $cliente->cep = $request->cep;
        $cliente->endereco = $request->endereco;
        $cliente->complemento = $request->complemento;
        $cliente->updated_at = null;

        try {
            $result = $cliente->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'clientes', $cliente->id, null, 2);

                return redirect('/clientes/')->with('responseSuccess', 'Os dados foram salvos.');
            }
        } catch(\Exception $e) {
            echo "<pre>";
            var_dump($e);
            die;

            return redirect('/clientes/create')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function edit(Request $request) {
        $id = (int)$request->id;
        $currentSite = $request->site;
        $user_data = $request->session()->get('user_data');

        $dadosCliente = Cliente::find($id);

        $dadosPaises = Pais::all();
        $dadosEstados = isset($dadosCliente->pais_id) ? Estado::where('pais_id', '=', $dadosCliente->pais_id)->get() : array();
        $dadosCidades = isset($dadosCliente->estado_id) ? Cidade::where('estado_id', '=', $dadosCliente->estado_id)->get() : array();

        if(!$dadosCliente){
            abort(404);
        }

        $data = array(
            'dadosCliente' => $dadosCliente,
            'user_data' => $user_data,
            'dadosPaises' => $dadosPaises,
            'dadosEstados' => $dadosEstados,
            'dadosCidades' => $dadosCidades
        );

        return view('modulos/clientes/clientes_form', $data);
    }

    public function update(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $this->validate($request, [
            'nome' => 'required|max:150',
            'email' => 'max:150',
            'cpf_cnpj' => 'max:18',
            'telefone' => 'max:18',
        ]);

        $dadosCliente = Cliente::find($id);

        $created_at = $dadosCliente->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        
        if($dia == 1) {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/clientes/'.$dadosCliente->id.'/edit')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser editado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/clientes/'.$dadosCliente->id.'/edit')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser editado.');
            }

        }

        $logData = $dadosCliente->toJson();

        if(!$dadosCliente){
            abort(404);
        }

        $dadosCliente->nome = $request->nome;
        $dadosCliente->email = $request->email;
        $dadosCliente->cpf_cnpj = $request->cpf_cnpj;
        $dadosCliente->telefone = $request->telefone;
        $dadosCliente->pais_id = $request->pais_id;
        $dadosCliente->estado_id = $request->estado_id;
        $dadosCliente->cidade_id = $request->cidade_id;
        $dadosCliente->cep = $request->cep;
        $dadosCliente->endereco = $request->endereco;
        $dadosCliente->complemento = $request->complemento;

        try {
            $result = $dadosCliente->save();

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'clientes', $dadosCliente->id, $logData, 3);

                return redirect('/clientes/'.$dadosCliente->id.'/edit')->with('responseSuccess', 'Os dados foram salvos.');
            }

        } catch(\Exception $e) {
            return redirect('/clientes/'.$dadosCliente->id.'/edit')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function destroy(Request $request) {
        $id = (int)$request->id;
        $user_data = $request->session()->get('user_data');

        $dadosCliente = Cliente::find($id);

        $created_at = $dadosCliente->created_at;
 
        $dia = date('w'); // 1 = monday
        $date1 = date_create($created_at->format('Y-m-d H:i:s'));
        $date2 = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($date1, $date2);
        

        if($dia == 1) {
            
            if($user_data['grupo_id'] != 3 && $diff->d >= 3) {
                return redirect('/clientes')->with('responseError', 'O prazo de 72h já passou, e esse item não pode ser deletado.');
            }

        } else {

            if($user_data['grupo_id'] != 3 && $diff->d >= 2) {
                return redirect('/clientes')->with('responseError', 'O prazo de 48h já passou, e esse item não pode ser deletado.');
            }

        }

        $logData = $dadosCliente->toJson();

        try {
            $result = $dadosCliente->destroy($id);

            if($result) {
                $log = new LogController();                
                $log->store($user_data['user_id'], 'clientes', $dadosCliente->id, $logData, 1);

                return redirect('/clientes/')->with('responseSuccess', 'Os dados do usuário '.$dadosCliente->email.' foram removidos.');
            }

        } catch(\Exception $e) {
            return redirect('/clientes/')->with('responseError', 'Ocorreu um erro, tente novamente.');
        }
    }

    public function buscaEstadosPorPais(Request $request) {
        $pais_id = (int)$request->pais_id;

        $estados = Estado::where('pais_id', '=', $pais_id)->get();
        
        try {
            $html = '<option value="">--</option>';

            foreach($estados as $estado) {
                $html .= '<option value="'.$estado->id.'">'.$estado->descricao.'</option>';
            }
    
            echo $html;
        } catch(\Exception $e) {}
    }

    public function buscaCidadesPorEstado(Request $request) {
        $estado_id = (int)$request->estado_id;

        $estados = Cidade::where('estado_id', '=', $estado_id)->get();
        
        try {
            $html = '<option value="">--</option>';

            foreach($estados as $estado) {
                $html .= '<option value="'.$estado->id.'">'.$estado->descricao.'</option>';
            }
    
            echo $html;
        } catch(\Exception $e) {}
    }

    // todo, turn into a helper
    public function moneyFormat($formatedValue) {
        if(!empty($formatedValue)) {
            $valores = array_reverse(explode(',', $formatedValue));

            if(count($valores) > 1) {
                $valor = str_replace('.', '', $valores[1]).".".$valores[0];
                return $valor;
            } else {
                return $formatedValue;
            }
        } else {
            return null;
        }
    }

    public function reverseData($data, $hora = false) {
        if($hora == true) {
            $arr = explode(' ', $data);

            $data = $arr[0];
            $horaNew = $arr[1];
        }

        $data = explode('/', $data);

        if(count($data) < 3) {
            return '';
        }

        if($hora == true) {
            $data = $data[2].'-'.$data[1].'-'.$data[0].' '.$horaNew;
        } else {
            $data = $data[2].'-'.$data[1].'-'.$data[0];
        }
        
        return $data;
    }
}
