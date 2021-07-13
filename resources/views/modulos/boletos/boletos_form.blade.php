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
                                    <li class="breadcrumb-item"><a href="{{ url('/boletos') }}">Boleto</a></li>
                                    @if(isset($dadosBoleto->id))
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

                                        <form method="post" id="formUserData" action="/boletos{{ isset($dadosBoleto->id) ? '/'.$dadosBoleto->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosBoleto->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="saldoAReceber"><b>Saldo a receber*</b></label>
                                                        <input type="text" class="form-control" name="saldo_a_receber" id="saldoAReceber" value="{{ isset($saldo_a_receber) ? $saldo_a_receber : '' }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="valorPago"><b>Valor pago*</b></label>
                                                        <input type="text" class="form-control" name="valor_pago" id="valorPago" value="{{ isset($dadosBoleto->valor_pago) ? $dadosBoleto->valor_pago : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="bancoId"><b>Banco*</b></label>
                                                    <select id="bancoId" name="banco_id" class="form-control">
                                                        <option value="">--</option>
                                                        @foreach($bancos as $banco)
                                                        <option value="{{ $banco->id }}" {{ isset($dadosBoleto->banco_id) && $dadosBoleto->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="dataPagamento">Data pagamento</label>
                                                        <input type="text" class="form-control" name="data_pagamento" id="dataPagamento" value="{{ isset($dadosBoleto->data_pagamento) ? date('d/m/Y', strtotime($dadosBoleto->data_pagamento)) : '' }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                @if(isset($dadosBoleto))
                                                <div class="col-lg-3">
                                                    @if(is_null($dadosBoleto->responsavel_confirmacao_id)  && ($dadosBoleto->responsavel_id !== $user_data['user_id']))
                                                    <label for="responsavelConfirmacaoId"><b>Confirmacao*</b></label>
                                                    <select id="responsavelConfirmacaoId" name="responsavel_confirmacao_id" class="form-control">
                                                        <option value="0">Não confirmado</option>
                                                        <option value="{{ $user_data['user_id'] }}">{{ $user_data['nome'] }}</option>
                                                    </select>
                                                    @else
                                                    <div class="form-group">
                                                        <label for="confirmacao"><b>Confirmacao*</b></label>
                                                        <input type="text" class="form-control" name="confirmacao" id="confirmacao" value="{{ isset($dadosBoleto->responsavel_confirmacao_id) ? $dadosBoleto->responsavelConfirmacao->nome : 'Sem confirmação' }}" disabled>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea class="form-control" name="observacoes" id="observacoes" rows="3">{{ isset($dadosBoleto->observacoes) ? $dadosBoleto->observacoes : '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 text-left">
                                                    Criado por: <b>{{ isset($dadosBoleto->responsavel->nome) ? $dadosBoleto->responsavel->nome : $user_data['nome'] }}</b>
                                                </div>
                                                
                                                <div class="col-lg-6 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosBoleto->id))
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
