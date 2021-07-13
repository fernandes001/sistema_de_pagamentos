<!DOCTYPE html>
<html lang="en">
    @include('inc/topo')
    
    <body id="page-top">

        @php
            //echo "<pre>";
            //print_r($bancos);
            //die;
        @endphp

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
                                    <li class="breadcrumb-item"><a href="{{ url('/auditoria') }}">Auditoria</a></li>
                                    @if(!is_null($dadosAuditoria))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Auditoria
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/auditoria{{ isset($dadosAuditoria->id) ? '/'.$dadosAuditoria->id : '' }}">
                                            <div class="row">
                                                @if(!is_null($dadosAuditoria))
                                                {{ method_field('PUT') }}
                                                @endif

                                                {{ csrf_field() }}

                                                <input type="hidden" name="data_registro" value="{{ Request::route('id') }}">

                                                @if(isset($dadosAuditoria))
                                                <div class="col-lg-3">
                                                    @if(is_null($dadosAuditoria->confirmacao_id) && ($dadosAuditoria->responsavel_id !== $user_data['user_id']))
                                                    <label for="confirmcaoId"><b>Confirmacao*</b></label>
                                                    <select id="confirmacaoId" name="confirmacao_id" class="form-control">
                                                        <option value="0">Não confirmado</option>
                                                        <option value="{{ $user_data['user_id'] }}">{{ $user_data['nome'] }}</option>
                                                    </select>
                                                    @else
                                                    <div class="form-group">
                                                        <label for="confirmacao"><b>Confirmação*</b></label>
                                                        <input type="text" class="form-control" name="confirmacao" id="confirmacao" value="{{ isset($dadosAuditoria->confirmacao_id) ? $dadosAuditoria->confirmacao->nome : 'Sem confirmação' }}" disabled>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="tarifas">Tarifas</label>
                                                        <input type="text" class="form-control" name="tarifas" id="tarifas" value="{{ isset($dadosAuditoria->tarifas) ? $dadosAuditoria->tarifas : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="duplicidade">Duplicidade</label>
                                                        <input type="text" class="form-control" name="duplicidade" id="duplicidade" value="{{ isset($dadosAuditoria->duplicidade) ? $dadosAuditoria->duplicidade : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="valorDuplicado">Valor duplicado</label>
                                                        <input type="text" class="form-control" name="valor_duplicado" id="valorDuplicado" value="{{ isset($dadosAuditoria->valor_duplicado) ? $dadosAuditoria->valor_duplicado : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="reembolso">Reembolso</label>
                                                        <input type="text" class="form-control" name="reembolso" id="reembolso" value="{{ isset($dadosAuditoria->reembolso) ? $dadosAuditoria->reembolso : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="saquePrime">Saque prime</label>
                                                        <input type="text" class="form-control" name="saque_prime" id="saquePrime" value="{{ isset($dadosAuditoria->saque_prime) ? $dadosAuditoria->saque_prime : '' }}">
                                                    </div>
                                                </div>

                                                <div class="{{ isset($dadosAuditoria->id) ? 'col-lg-6' : 'col-lg-8' }}"></div>

                                                @foreach($bancos as $key => $banco)
                                                    @php
                                                        $valor = '';
                                                        $id = '';
                                                    @endphp
                                                    
                                                    @if(!is_null($valoresAuditoriaBanco))
                                                        @foreach($valoresAuditoriaBanco as $auditoria)
                                                            @if($auditoria->banco_id == $banco->id)
                                                                @php
                                                                    $valor = $auditoria->valor;
                                                                    $id = $auditoria->id;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="banco{{$banco->id}}">{{ $banco->nome }}</label>
                                                            <input type="text" class="form-control auditoria-bancos" name="bancos[{{$key}}][valor]" value="{{ $valor }}">
                                                            <input type="hidden" name="bancos[{{$key}}][banco_id]" value="{{ $banco->id }}"/>
                                                            <input type="hidden" name="bancos[{{$key}}][auditoria_id]" value="{{ $id }}"/>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea class="form-control" name="observacoes" id="observacoes" rows="3">{{ isset($dadosAuditoria->observacoes) ? $dadosAuditoria->observacoes : '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(!is_null($dadosAuditoria))
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
