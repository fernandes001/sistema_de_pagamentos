<!DOCTYPE html>
<html lang="en">
    @include('inc/topo')

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">
            @include('inc/menu')

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    @include('inc/topbar')

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/registros') }}">Início</a></li>
                                    <li class="breadcrumb-item"><a href="{{ url('/registros') }}">Registros</a></li>
                                    @if(isset($dadosRegristro->id))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Registros
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/registros{{ isset($dadosRegistro->id) ? '/'.$dadosRegistro->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosRegistro->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <label for="clienteId"><b>Cliente*</b></label>
                                                    <select id="clienteId" name="cliente_id" class="form-control">
                                                        <option value="">--</option>
                                                        @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" {{ isset($dadosRegistro->cliente_id) && $dadosRegistro->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="bancoId"><b>Banco*</b></label>
                                                    <select id="bancoId" name="banco_id" class="form-control">
                                                        <option value="">--</option>
                                                        @foreach($bancos as $banco)
                                                        <option value="{{ $banco->id }}" {{ isset($dadosRegistro->banco_id) && $dadosRegistro->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="favorecido"><b>Favorecido*</b></label>
                                                        <input type="text" class="form-control" name="favorecido" id="favorecido" value="{{ isset($dadosRegistro->favorecido) ? $dadosRegistro->favorecido : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="valor"><b>Valor*</b></label>
                                                        <input type="text" class="form-control" name="valor" id="valor" value="{{ isset($dadosRegistro->valor) ? $dadosRegistro->valor : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <label for="tipo"><b>Tipo*</b></label>
                                                    <select id="tipo" name="tipo" class="form-control">
                                                        <option value="">--</option>
                                                        <option value="1" {{ isset($dadosRegistro->tipo) && $dadosRegistro->tipo == 1 ? 'selected' : '' }}>TED</option>
                                                        <option value="2" {{ isset($dadosRegistro->tipo) && $dadosRegistro->tipo == 2 ? 'selected' : '' }}>Boleto</option>
                                                        <option value="3" {{ isset($dadosRegistro->tipo) && $dadosRegistro->tipo == 3 ? 'selected' : '' }}>Mesmo banco</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="saqueId"><b>ID Saque*</b></label>
                                                        <input type="text" class="form-control" name="saque_id" id="saqueId" value="{{ isset($dadosRegistro->saque_id) ? $dadosRegistro->saque_id : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="responsavel"><b>Responsável*</b></label>
                                                        <input type="text" class="form-control" name="responsavel" id="responsavel" value="{{ isset($dadosRegistro->usuario->nome) ? $dadosRegistro->usuario->nome : $user_data['nome'] }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="estorno">Estorno</label>
                                                        <input type="text" class="form-control" name="estorno" id="estorno" value="{{ isset($dadosRegistro->estorno) ? $dadosRegistro->estorno : '' }}" placeholder="0,00">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="statusUltimo"><b>Status*</b></label>
                                                    <select id="statusUltimo" name="status_ultimo" class="form-control">
                                                        <option value="">--</option>
                                                        <option value="0" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 0 ? 'selected' : '' }}>Pendente</option>
                                                        <option value="1" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 1 ? 'selected' : '' }}>Realizado</option>
                                                        <option value="2" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 2 ? 'selected' : '' }}>Cancelado</option>
                                                        <option value="3" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 3 ? 'selected' : '' }}>Estornado</option>
                                                        <option value="4" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 4 ? 'selected' : '' }}>Aguardando comprovante</option>
                                                        <option value="6" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 6 ? 'selected' : '' }}>Progresso</option>
                                                        <option value="7" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 7 ? 'selected' : '' }}>Erro</option>
                                                        <option value="8" {{ isset($dadosRegistro->status_ultimo) && $dadosRegistro->status_ultimo == 8 ? 'selected' : '' }}>Refeito</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="createdAt">Data de criação</label>
                                                        <input type="text" class="form-control" name="created_at" id="createdAt" value="{{ isset($dadosRegistro->created_at) ? date('d/m/Y H:i:s', strtotime($dadosRegistro->created_at)) : '' }}">
                                                    </div>
                                                </div>

                                                @if(isset($dadosRegistro))
                                                <div class="col-lg-5">
                                                    @if(is_null($dadosRegistro->confirmacao_id) && ($dadosRegistro->responsavel_id !== $user_data['user_id']))
                                                    <label for="confirmacao_id"><b>Confirmacao*</b></label>
                                                    <select id="confirmacaoId" name="confirmacao_id" class="form-control">
                                                        <option value="0">Não confirmado</option>
                                                        <option value="{{ $user_data['user_id'] }}">{{ $user_data['nome'] }}</option>
                                                    </select>
                                                    @else
                                                    <div class="form-group">
                                                        <label for="confirmacao"><b>Confirmação*</b></label>
                                                        <input type="text" class="form-control" name="confirmacao" id="confirmacao" value="{{ isset($dadosRegistro->confirmacao_id) ? $dadosRegistro->confirmacao->nome : 'Sem confirmação' }}" disabled>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                                <div class="col-lg-12">
                                                    <ul class="registros__status-registro">
                                                        @php
                                                            $arrStatus = array(
                                                                '0' => 'Pendente',
                                                                '1' => 'Realizado',
                                                                '2' => 'Cancelado',
                                                                '3' => 'Estornado',
                                                                '4' => 'Aguardando comprovante',
                                                                '6' => 'Progresso',
                                                                '7' => 'Erro',
                                                                '8' => 'Refeito'
                                                            );
                                                        @endphp

                                                        @if(isset($dadosRegistroStatus))
                                                            @foreach($dadosRegistroStatus as $key => $dStatus)
                                                            <li>{{ $key+1 }} - {{ $arrStatus[$dStatus->status] }} - {{ $dStatus->created_at != null ? date('d/m/Y', strtotime($dStatus->created_at)) : '' }} - {{ $dStatus->usuario_id != null ? $dStatus->usuario->nome : '' }}</li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea class="form-control" name="observacoes" id="observacoes" rows="3">{{ isset($dadosRegistro->observacoes) ? $dadosRegistro->observacoes : '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="urlComprovante">Comprovante</label>
                                                        <input type="text" class="form-control" name="url_comprovante" id="urlComprovante" value="{{ isset($dadosRegistro->url_comprovante) ? $dadosRegistro->url_comprovante : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <iframe src="https://dyspaga.com.br/geradordecomprovante/" style="width: 100%; height: 700px; border: 0;"></iframe>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosRegistro->id))
                                                            Salvar
                                                        @else
                                                            Criar
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                @include('inc/rodape')

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        @include('inc/js')
    </body>
</html>
