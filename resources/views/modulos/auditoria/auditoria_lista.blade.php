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
                                        <li class="breadcrumb-item active" aria-current="page">Auditoria</li>
                                    </ol>
                                </nav>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card shadow mb-4">
                                            <div class="card-header py-3">
                                                <div class="row" onclick="abre_fecha_filtro();">
                                                    <div class="col-lg-8 text-left">
                                                        <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                                                    </div>

                                                    <div class="col-4 text-right">
                                                        <h6 class="m-0 font-weight-bold text-primary"><span class="filtrosBodyBt">+</span></h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body filtrosBody">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="GET" action="/auditoria">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="qtdeItens">Qtde itens</label>
                                                                        <input type="text" class="form-control" name="qtde_itens" id="qtdeItens" placeholder="Ex: 20" autocomplete="off" value="">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 text-right">
                                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                                        Filtrar dados
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Auditoria</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 10%;">Data</th>
                                                    <th scope="col" style="width: 40%;">Bancos</th>
                                                    <th scope="col" style="width: 35%;">Contabilidade</th>
                                                    <th scope="col" style="width: 5%;">Confirmação</th>
                                                    <th scope="col" style="width: 10%;">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @inject('auditoriaCtrl', 'App\Http\Controllers\AuditoriaController')
                                                @foreach($dadosAuditoria as $auditoria)
													<tr class="lista_hover">
                                                        <td>{{ isset($auditoria->created_at) ? date('d/m/Y', strtotime($auditoria->created_at)) : '' }}</td>
                                                        <td>
                                                            @php
                                                                $bancos = $auditoriaCtrl->auditoriaBanco(isset($auditoria->created_at) ? strtotime($auditoria->created_at) : '');
                                                                $dAuditoria = $auditoriaCtrl->auditoria(isset($auditoria->created_at) ? strtotime($auditoria->created_at) : '');

                                                                $saldoBancos = 0;

                                                                $saldoCredito = $auditoriaCtrl->getSaldoCredito(isset($auditoria->created_at) ? strtotime($auditoria->created_at) : 0);
                                                            
                                                                $saldo_a_receber = isset($saldoCredito->saldo_a_receber) ? (float)$saldoCredito->saldo_a_receber : 0;
                                                                $tarifas = isset($dAuditoria['tarifas']) ? (float)$dAuditoria['tarifas'] : 0;
                                                                $reembolso = isset($dAuditoria['reembolso']) ? (float)$dAuditoria['reembolso'] : 0;
                                                                $valor_duplicado = isset($dAuditoria['valor_duplicado']) ? (float)$dAuditoria['valor_duplicado'] : 0;
                                                                $saque_prime = isset($dAuditoria['saque_prime']) ? (float)$dAuditoria['saque_prime'] : 0;
                                                                $valor = isset($auditoria->valor) ? (float)$auditoria->valor : 0;
                                                                $duplicidade = isset($dAuditoria['duplicidade']) ? (float)$dAuditoria['duplicidade'] : 0;
                                                            @endphp

                                                            <div class="row">
                                                            @foreach($bancos as $banco)
                                                                @php
                                                                    $valor_banco = isset($banco->valor) ? (float)$banco->valor : 0;

                                                                    $saldoBancos += $valor_banco;
                                                                @endphp
                                                                <div class="col-lg-6"><b>{{ $banco->nome }}:</b> R$ {{ number_format($valor_banco, 2, ",",".") }}</div>
                                                            @endforeach
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-lg-6"><b>Saldo bancos: </b> R$ {{ number_format($saldoBancos, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Saldo crédito (com tarifas): </b> R$ {{ number_format(($saldo_a_receber + $tarifas), 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Saldo atual: </b> R$ {{ number_format(($saldo_a_receber + $tarifas + $saldoBancos), 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Saldo a receber:</b> R$ {{ number_format($valor, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Tarifas: </b> R$ {{ number_format($tarifas, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Duplicidade: </b> R$ {{ number_format($duplicidade, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Valor duplicado: </b> R$ {{ number_format($valor_duplicado, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Reembolsos: </b> R$ {{ number_format($reembolso, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Saque prime: </b> R$ {{ number_format($saque_prime, 2, ",",".") }}</div>
                                                                <div class="col-lg-6"><b>Saldo crédito: </b> R$ {{ number_format($saldo_a_receber, 2, ",",".") }}</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if((isset($dAuditoria['responsavel_id']) ? $dAuditoria['responsavel_id'] : null) != $user_data['user_id'])
                                                            <label class="switch">
																<input type="checkbox" class="checkboxConfirmacao" onclick="confirmacao('{{ url('/') }}', 'auditoria', {{ isset($dAuditoria['id']) ? $dAuditoria['id'] : 0 }});" {{isset($dAuditoria['confirmacao_id']) && $dAuditoria['confirmacao_id'] > 0 ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @else
                                                            <label class="switch">
																<input type="checkbox" class="checkboxConfirmacao" {{isset($dAuditoria['confirmacao_id']) && $dAuditoria['confirmacao_id'] > 0 ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-success" href="/auditoria/{{ isset($auditoria->created_at) ? strtotime($auditoria->created_at) : '' }}/edit" role="button">Editar</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosAuditoria->total() > $dadosAuditoria->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosAuditoria->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosAuditoria->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosAuditoria->appends(request()->except('page'))->url($dadosAuditoria->currentPage() - 2) }}">{{ $dadosAuditoria->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosAuditoria->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosAuditoria->appends(request()->except('page'))->url($dadosAuditoria->currentPage() - 1) }}">{{ $dadosAuditoria->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosAuditoria->currentPage() }}</a></li>

                                                @if($dadosAuditoria->currentPage() + 1 <= $dadosAuditoria->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosAuditoria->appends(request()->except('page'))->url($dadosAuditoria->currentPage() + 1) }}">{{ $dadosAuditoria->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosAuditoria->currentPage() + 2 <= $dadosAuditoria->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosAuditoria->appends(request()->except('page'))->url($dadosAuditoria->currentPage() + 2) }}">{{ $dadosAuditoria->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosAuditoria->appends(request()->except('page'))->url($dadosAuditoria->lastPage()) }}">Último</a></li>
                                            </ul>
                                        </nav>
                                    @endif
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
