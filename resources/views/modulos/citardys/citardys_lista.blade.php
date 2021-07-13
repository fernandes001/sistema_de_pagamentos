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
                                        <li class="breadcrumb-item active" aria-current="page">Citar DYS</li>
                                        <li><a href="{{ url('/') }}/citardys/gerapdf?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&banco_recebimento={{ $banco_recebimento }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarPdf">Exportar PDF</a></li>
                                        <li><a href="{{ url('/') }}/citardys/geracsv?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&banco_recebimento={{ $banco_recebimento }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarCsv">Exportar CSV</a></li>
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
                                                        <form method="GET" action="/citardys">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="dataInicio">Data início</label>
                                                                        <input type="text" class="form-control" name="data_inicio" id="citarDysDataInicio" placeholder="DD/MM/YYYY" autocomplete="off" value="{{ $data_inicio != '' ? date('d/m/Y', strtotime($data_inicio)) : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="dataFim">Data fim</label>
                                                                        <input type="text" class="form-control" name="data_fim" id="citarDysDataFim" placeholder="DD/MM/YYYY" autocomplete="off" value="{{ $data_fim != '' ? date('d/m/Y', strtotime($data_fim)) : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="fBancoRecebimento">Banco</label>
                                                                        <input type="text" class="form-control" name="banco_recebimento" id="fBancoRecebimento"  autocomplete="off" value="{{ isset($banco_recebimento) ? $banco_recebimento : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="qtdeItens">Qtde itens</label>
                                                                        <input type="text" class="form-control" name="qtde_itens" id="qtdeItens" placeholder="Ex: 20" autocomplete="off" value="{{ isset($qtde_itens) ? $qtde_itens : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 text-right">
                                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                                        Filtrar Registros
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
                                        <h6 class="m-0 font-weight-bold text-primary">
											Citar DYS
											<?php 
												$data1 = date("Y-m-d H:i:s");

												$sql1 = "SELECT 
														(
														(SELECT sum(COALESCE(r.valor,0))-sum(COALESCE(r.estorno,0)) AS valor_a_receber FROM registros AS r WHERE r.created_at <= ('$data1'))
														-
														(SELECT sum(COALESCE(c.valor_pago)) AS valor_pago FROM citar_dys AS c WHERE c.data_pagamento <= ('$data1'))

														)+66747395.890009806 AS saldo_a_receber";

												$query1 = DB::select($sql1);

												echo '(Saldo a receber: R$ ' . number_format((float)$query1[0]->saldo_a_receber, 2, ",",".") . ')';
											?>
										
										</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
													<th scope="col">Data de Pagamento</th>
													<th scope="col">Valor a receber</th>
													<th scope="col">Valor pago</th>
													<th scope="col">Saldo a receber</th>
                                                    <th scope="col">Usuário</th>
													<th scope="col">Banco</th>
                                                    <th scope="col">Confirmação</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
												<?php
												$ii=1;
												$aaa=1;
												$valor_linha = 0;
												?>
                                                @foreach($dadosCitarDys as $citarDys)
												
												<?php 
														$data = date("Y-m-d", strtotime($citarDys->data_pagamento)).' 23:59:59';
														
														$sql2 = "SELECT 
																(
																(SELECT sum(COALESCE(r.valor,0))-sum(COALESCE(r.estorno,0)) AS valor_a_receber FROM registros AS r WHERE r.created_at <= ('$data'))
																-
																(SELECT sum(COALESCE(c.valor_pago)) AS valor_pago FROM citar_dys AS c WHERE c.data_pagamento <= ('$data'))

																)+66747395.890009806 AS saldo_a_receber";

														$query2 = DB::select($sql2);
														
														$valor_final				= $query2[0]->saldo_a_receber;
														$valor_total_do_dia_pago	= $citarDys->total_pago_dia;
														$valor_inicial				=	$valor_final-$valor_total_do_dia_pago;

													?>
												
												
                                                    <tr class="lista_hover">
														<td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">{{ isset($citarDys->data_pagamento) ? date('d/m/Y', strtotime($citarDys->data_pagamento)) : '' }}</td>
														
														<td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">
															<?php 

																if($ii==1){
																	echo 'R$ ' . number_format((float)$citarDys->valor_total_saque, 2, ",",".");
																} 

																if($citarDys->qtd_pagamentos_do_dia==$ii){
																	$ii=1;
																} else {
																	$ii++;
																}
																
															?>
														</td>
                                                        
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">R$ {{ number_format((float)$citarDys->valor_pago, 2, ",",".") }}</td>
														<td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">
															<?php 

																if($aaa==1){
																	$valor_linha = $valor_final;
																	echo 'R$ ' . number_format((float)$valor_linha, 2, ",",".");

																	$valor_linha = (((-$citarDys->valor_total_saque)+$citarDys->valor_pago)+$valor_linha);
																	
																} else {
																	
																	echo 'R$ ' . number_format((float)$valor_linha, 2, ",",".");
																	$valor_linha = $valor_linha + $citarDys->valor_pago;
																}
																
																if($aaa==$citarDys->qtd_pagamentos_do_dia){
																	$aaa=1;
																	$valor_linha = 0;
																} else {
																	$aaa++;
																}
															?>
														</td>
														<td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">{{$citarDys->usuario->nome}}</td>
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $citarDys->id }}" onclick="getLogCitarDys({{ $citarDys->id }})">{{ $citarDys->banco_que_recebemos}}</td>
                                                        
                                                        <td>
                                                            @if(($citarDys->usuario_id !== $user_data['user_id']))
															<label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $citarDys->id }}" class="checkboxConfirmacao" onclick="confirmacao('{{ url('/') }}', 'citardys', {{ $citarDys->id }});" {{ isset($citarDys->confirmacao_id) && !is_null($citarDys->confirmacao_id) ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @else
                                                            <label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $citarDys->id }}" class="checkboxConfirmacao" {{ isset($citarDys->confirmacao_id) && !is_null($citarDys->confirmacao_id) ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @endif
														</td>

                                                        <td>
                                                            <form method="POST" action="/citardys/{{ $citarDys->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
                                                                <a class="btn btn-sm btn-success" href="/citardys/{{ $citarDys->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Begin Informaçõs Modal -->
                                                    <div class="modal fade" id="informacoesModal{{ $citarDys->id }}" tabindex="-1" role="dialog" aria-labelledby="informacoesModal{{ $citarDys->id }}Label" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                 
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="informacoesModal{{ $citarDys->id }}Label"></h5>
                                                                    
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Responsável</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->usuario_id) ? $citarDys->usuario->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Confirmação</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->confirmacao_id) ? $citarDys->responsavelConfirmacao->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Valor pago</div>
                                                                        <div class="col-md-10">R$ {{ isset($citarDys->valor_pago) ? number_format((float)$citarDys->valor_pago, 2, ",",".") : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Banco que recebmos</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->banco_que_recebemos) ? $citarDys->banco_que_recebemos : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Comprovante</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->comprovante) ? $citarDys->comprovante : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Data de pagamento</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->data_pagamento) ? date('d/m/Y', strtotime($citarDys->data_pagamento)) : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Data de criação</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->created_at) ? date('d/m/Y', strtotime($citarDys->created_at)) : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Observações</div>
                                                                        <div class="col-md-10">{{ isset($citarDys->observacoes) ? $citarDys->observacoes : '' }}</div>
                                                                    </div>
                                                                </div>

                                                                <div class="row logs-lista-registros">
                                                                    <div class="col-lg-12">
                                                                        <h5 class="text-center">Logs</h5>
                                                                    </div>

                                                                    <div class="col-lg-12 corpoLog{{ $citarDys->id }}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End of Informações Modal -->
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosCitarDys->total() > $dadosCitarDys->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosCitarDys->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosCitarDys->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosCitarDys->appends(request()->except('page'))->url($dadosCitarDys->currentPage() - 2) }}">{{ $dadosCitarDys->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosCitarDys->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosCitarDys->appends(request()->except('page'))->url($dadosCitarDys->currentPage() - 1) }}">{{ $dadosCitarDys->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosCitarDys->currentPage() }}</a></li>

                                                @if($dadosCitarDys->currentPage() + 1 <= $dadosCitarDys->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosCitarDys->appends(request()->except('page'))->url($dadosCitarDys->currentPage() + 1) }}">{{ $dadosCitarDys->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosCitarDys->currentPage() + 2 <= $dadosCitarDys->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosCitarDys->appends(request()->except('page'))->url($dadosCitarDys->currentPage() + 2) }}">{{ $dadosCitarDys->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosCitarDys->appends(request()->except('page'))->url($dadosCitarDys->lastPage()) }}">Último</a></li>
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
