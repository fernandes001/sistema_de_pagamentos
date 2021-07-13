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
                                    <li class="breadcrumb-item"><a href="{{ url('/citardys') }}">Citar DYS</a></li>
                                    @if(isset($dadosCitarDys->id))
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

                                        <form method="post" id="formUserData" action="/citardys{{ isset($dadosCitarDys->id) ? '/'.$dadosCitarDys->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosCitarDys->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="saldoAReceber"><b>Saldo a receber*</b></label>
                                                        <input type="text" class="form-control" name="saldo_a_receber" id="saldoAReceber" value="{{ isset($saldo_a_receber) ? $saldo_a_receber : '' }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="valorPago"><b>Valor pagamento*</b></label>
                                                        <input type="text" class="form-control" name="valor_pago" id="valorPago" value="{{ isset($dadosCitarDys->valor_pago) ? $dadosCitarDys->valor_pago : '' }}" placeholder="0,00">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="dataPagamento"><b>Data pagamento*</b></label>
                                                        <input type="text" class="form-control" name="data_pagamento" id="dataPagamento" value="{{ isset($dadosCitarDys->data_pagamento) ? date('d/m/Y', strtotime($dadosCitarDys->data_pagamento)) : '' }}" autocomplete="off" placeholder="DD/MM/YYYY">
                                                    </div>
                                                </div>

                                                @if(isset($dadosCitarDys))
                                                <div class="col-lg-2">
                                                    @if(is_null($dadosCitarDys->confirmacao_id) && ($dadosCitarDys->usuario_id !== $user_data['user_id']))
                                                    <label for="confirmacao_id"><b>Confirmacao*</b></label>
                                                    <select id="confirmacaoId" name="confirmacao_id" class="form-control">
                                                        <option value="0">Não confirmado</option>
                                                        <option value="{{ $user_data['user_id'] }}">{{ $user_data['nome'] }}</option>
                                                    </select>
                                                    @else
                                                    <div class="form-group">
                                                        <label for="confirmacao"><b>Confirmação*</b></label>
                                                        <input type="text" class="form-control" name="confirmacao" id="confirmacao" value="{{ isset($dadosCitarDys->confirmacao_id) ? $dadosCitarDys->responsavelConfirmacao->nome : 'Sem confirmação' }}" disabled>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                                <div class="{{ isset($dadosCitarDys->id) ? 'col-lg-4' : 'col-lg-6' }}">
                                                    <div class="form-group">
                                                        <label for="bancoQueRecebemos"><b>Banco que recebemos*</b></label>
                                                        <input type="text" class="form-control" name="banco_que_recebemos" id="bancoQueRecebemos" value="{{ isset($dadosCitarDys->banco_que_recebemos) ? $dadosCitarDys->banco_que_recebemos : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea class="form-control" name="observacoes" id="observacoes" rows="3">{{ isset($dadosCitarDys->observacoes) ? $dadosCitarDys->observacoes : '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="comprovante">Comprovante</label>
                                                        <input type="text" class="form-control" name="comprovante" id="comprovante" value="{{ isset($dadosCitarDys->comprovante) ? $dadosCitarDys->comprovante : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <iframe src="https://dyspaga.com.br/geradordecomprovante/" style="width: 100%; height: 700px; border: 0;"></iframe>
                                                </div>

                                                <div class="col-lg-6 text-left">
                                                    Criado por: <b>{{ isset($dadosCitarDys->usuario->nome) ? $dadosCitarDys->usuario->nome : $user_data['nome'] }}</b>
                                                </div>
                                                
                                                <div class="col-lg-6 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosCitarDys->id))
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
