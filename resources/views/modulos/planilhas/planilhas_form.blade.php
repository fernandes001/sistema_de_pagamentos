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
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Início</a></li>
                                    <li class="breadcrumb-item"><a href="{{ url('/planilhas') }}">Planilhas</a></li>
                                    @if(isset($dadosPlanilha->id))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Planilhas
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/planilhas/{{ isset($dadosPlanilha->id) ? $dadosPlanilha->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosPlanilha->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <label for="clienteId">Cliente</label>
                                                    <select id="clienteId" name="cliente_id" class="form-control">
                                                        <option value="">--</option>
                                                        @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" {{ isset($dadosPlanilha->cliente_id) && $dadosPlanilha->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="bancoId">Banco</label>
                                                    <select id="bancoId" name="banco_id" class="form-control">
                                                        <option value="">--</option>
                                                        @foreach($bancos as $banco)
                                                        <option value="{{ $banco->id }}" {{ isset($dadosPlanilha->banco_id) && $dadosPlanilha->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="favorecido">Favorecido</label>
                                                        <input type="text" class="form-control" name="favorecido" id="favorecido" value="{{ isset($dadosPlanilha->favorecido) ? $dadosPlanilha->favorecido : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="valor">Valor</label>
                                                        <input type="text" class="form-control" name="valor" id="valor" value="{{ isset($dadosPlanilha->valor) ? $dadosPlanilha->valor : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="tipo">Tipo</label>
                                                        <input type="text" class="form-control" name="tipo" id="tipo" value="{{ isset($dadosPlanilha->tipo) ? $dadosPlanilha->tipo : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="saqueId">ID Saque</label>
                                                        <input type="text" class="form-control" name="saque_id" id="saqueId" value="{{ isset($dadosPlanilha->saque_id) ? $dadosPlanilha->saque_id : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="responsavelId">Responsável</label>
                                                        <input type="text" class="form-control" name="responsavel_id" id="responsavelId" value="{{ isset($dadosPlanilha->responsavel_id) ? $dadosPlanilha->responsavel_id : '' }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <input type="text" class="form-control" name="status" id="status" value="{{ isset($dadosPlanilha->status) ? $dadosPlanilha->status : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="confirmacao">Confirmação</label>
                                                        <input type="text" class="form-control" name="confirmacao" id="confirmacao" value="{{ isset($dadosPlanilha->confirmacao) ? $dadosPlanilha->confirmacao : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="estorno">Estorno</label>
                                                        <input type="text" class="form-control" name="estorno" id="estorno" value="{{ isset($dadosPlanilha->estorno) ? $dadosPlanilha->estorno : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="urlComprovante">Comprovante</label>
                                                        <input type="text" class="form-control" name="url_comprovante" id="urlComprovante" value="{{ isset($dadosPlanilha->url_comprovante) ? $dadosPlanilha->url_comprovante : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosPlanilha->id))
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
