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
                                    <li class="breadcrumb-item"><a href="{{ url('/clientes') }}">Clientes</a></li>
                                    @if(isset($dadosCliente->id))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Usuário
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/clientes{{ isset($dadosCliente->id) ? '/'.$dadosCliente->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosCliente->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="name"><b>Nome*</b></label>
                                                        <input type="text" class="form-control" name="nome" id="name" value="{{ isset($dadosCliente->nome) ? $dadosCliente->nome : '' }}" maxlength="150">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email">E-mail</label>
                                                        <input type="email" class="form-control" name="email" id="email" value="{{ isset($dadosCliente->email) ? $dadosCliente->email : '' }}" maxlength="150">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="paisId">País</label>
                                                        <select id="paisId" name="pais_id" class="form-control">
                                                            <option value="">--</option>
                                                            @foreach($dadosPaises as $pais)
                                                                <option value="{{ $pais->id }}" {{ isset($dadosCliente->pais_id) && $dadosCliente->pais_id == $pais->id ? 'selected' : '' }}>{{ $pais->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="estadoId">Estado</label>
                                                        <select id="estadoId" name="estado_id" class="form-control">
                                                            <option value="">--</option>
                                                            @if(isset($dadosEstados))
                                                                @foreach($dadosEstados as $estado)
                                                                    <option value="{{ $estado->id }}" {{ isset($dadosCliente->estado_id) && $dadosCliente->estado_id == $estado->id ? 'selected' : '' }}>{{ $estado->descricao }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="cidadeId">Cidade</label>
                                                        <select id="cidadeId" name="cidade_id" class="form-control">
                                                            <option value="">--</option>
                                                            @if(isset($dadosCidades))
                                                                @foreach($dadosCidades as $cidade)
                                                                    <option value="{{ $cidade->id }}" {{ isset($dadosCliente->estado_id) && $dadosCliente->estado_id == $cidade->id ? 'selected' : '' }}>{{ $cidade->descricao }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="cpfCnpj">CPF/CNPJ</label>
                                                        <input type="text" class="form-control" name="cpf_cnpj" id="cpfCnpj" value="{{ isset($dadosCliente->cpf_cnpj) ? $dadosCliente->cpf_cnpj : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="cep">CEP</label>
                                                        <input type="text" class="form-control" name="cep" id="cep" value="{{ isset($dadosCliente->cep) ? $dadosCliente->cep : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="endereco">Endereço</label>
                                                        <input type="text" class="form-control" name="endereco" id="endereco" value="{{ isset($dadosCliente->endereco) ? $dadosCliente->endereco : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="complemento">Complemento</label>
                                                        <input type="text" class="form-control" name="complemento" id="complemento" value="{{ isset($dadosCliente->complemento) ? $dadosCliente->complemento : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="telefone">Telefone</label>
                                                        <input type="text" class="form-control" name="telefone" id="telefone" value="{{ isset($dadosCliente->telefone) ? $dadosCliente->telefone : '' }}" placeholder="Ex.: +55 11 9 0000-0000" maxlength="18">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosCliente->id))
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
