<?php
//echo '<pre>';
//print_r($dadosBoletos);
//die();
?>

<!DOCTYPE html>
<html lang="en">
    @include('inc/topo')

    <body id="page-top">

        @php
            //echo "<pre>";
            //print_r($dadosBoletos);
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
                                        <li class="breadcrumb-item active" aria-current="page">Boletos</li>
                                        <li><a href="{{ url('/') }}/boletos/gerapdf?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarPdf">Exportar PDF</a></li>
                                        <li><a href="{{ url('/') }}/boletos/geracsv?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarCsv">Exportar CSV</a></li>
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
                                                        <form method="GET" action="/boletos">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="dataInicio">Data início</label>
                                                                        <input type="text" class="form-control" name="data_inicio" id="dataInicio" placeholder="DD/MM/YYYY" autocomplete="off" value="{{ $data_inicio != '' ? date('d/m/Y', strtotime($data_inicio)) : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="dataFim">Data fim</label>
                                                                        <input type="text" class="form-control" name="data_fim" id="dataFim" placeholder="DD/MM/YYYY" autocomplete="off" value="{{ $data_fim != '' ? date('d/m/Y', strtotime($data_fim)) : '' }}">
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
											Boletos

											<?php 
												$data1 = date("Y-m-d H:i:s");

												$sql1 = "SELECT (SELECT count(r.id)*10 AS total_a_receber FROM registros AS r WHERE r.created_at <= '".$data1."') 
														-
														(SELECT SUM(b.valor_pago) FROM boletos AS b WHERE b.created_at <= '".$data1."')-(1152660) AS saldo_a_receber";

												$query1 = DB::select($sql1);
												//echo $sql2;
												echo '(Saldo a receber: R$ ' . number_format((float)$query1[0]->saldo_a_receber, 2, ",",".") . ')';
											?>
										</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
													<th scope="col">Data de pagamento</th>
													<th scope="col">Valor a receber</th>
                                                    <th scope="col">Valor pago</th>
													<th scope="col">Saldo a receber</th>
                                                    <th scope="col">Banco</th>
                                                    <th scope="col">Confirmação</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($dadosBoletos as $boleto)
                                                    <tr class="lista_hover">
														<td data-toggle="modal" data-target="#informacoesModal{{ $boleto->id }}" onclick="getLogBoleto({{ $boleto->id }})">{{ isset($boleto->data) ? date('d/m/Y', strtotime($boleto->data)) : '' }}</td>
														
														<td data-toggle="modal" data-target="#informacoesModal{{ $boleto->id }}" onclick="getLogBoleto({{ $boleto->id }})">
															
															<?php 
																$data_boleto = date("Y-m-d", strtotime($boleto->data));
															
																$sql_boleto = 'SELECT (count(r.id)*10) AS valor_boleto FROM registros AS r WHERE DATE_FORMAT(r.created_at,"%Y-%m-%d") = "'.$data_boleto.'"';

																$query_boleto = DB::select($sql_boleto);
																//echo $sql_boleto;
																echo 'R$ ' . number_format((float)$query_boleto[0]->valor_boleto, 2, ",",".");
															?>
														</td>
														
														
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $boleto->id }}" onclick="getLogBoleto({{ $boleto->id }})">R$ {{ number_format((float)$boleto->valor_pago, 2, ",",".") }}</td>
														
														<td data-toggle="modal" data-target="#informacoesModal{{ $boleto->id }}" onclick="getLogBoleto({{ $boleto->id }})">
															
															<?php 
																$data = date("Y-m-d", strtotime($boleto->data)).' 23:59:59';
															
																$sql2 = "SELECT (SELECT count(r.id)*10 AS total_a_receber FROM registros AS r WHERE r.created_at <= '".$data."') 
																		-
																		(SELECT SUM(b.valor_pago) FROM boletos AS b WHERE b.created_at <= '".$data."')-(1152660) AS saldo_a_receber";

																$query2 = DB::select($sql2);
																//echo $sql2;
																echo 'R$ ' . number_format((float)$query2[0]->saldo_a_receber, 2, ",",".");
															?>
														</td>

                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $boleto->id }}" onclick="getLogBoleto({{ $boleto->id }})">{{ isset($boleto->banco->nome) ? $boleto->banco->nome : 'Registro sem relação' }}</td>
                                                        
                                                        <td>
                                                            @if(($boleto->responsavel_id !== $user_data['user_id']))
															<label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $boleto->id }}" class="checkboxConfirmacao" onclick="confirmacao('{{ url('/') }}', 'boletos', {{ $boleto->id }});" {{ isset($boleto->responsavel_confirmacao_id) && !is_null($boleto->responsavel_confirmacao_id) ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @else
                                                            <label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $boleto->id }}" class="checkboxConfirmacao" {{ isset($boleto->responsavel_confirmacao_id) && !is_null($boleto->responsavel_confirmacao_id) ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @endif
														</td>

                                                        <td>
                                                            <form method="POST" action="/boletos/{{ $boleto->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
																<?php
																	if(isset($boleto->id)&&$boleto->id!=null){
																		$acao = '/boletos/'.$boleto->id.'/edit';
																		$acao_botao		= 'Editar';
																		$acao_estilo	= 'success';
																	} else {
																		$acao			= '/boletos/create';
																		$acao_botao		= 'Cadastrar';
																		$acao_estilo	= 'info';
																	}
																?>
                                                                <a class="btn btn-sm btn-{{$acao_estilo}}" href="{{ $acao }}" role="button" style="width: 78px;">{{$acao_botao}}</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Begin Informaçõs Modal -->
                                                    <div class="modal fade" id="informacoesModal{{ $boleto->id }}" tabindex="-1" role="dialog" aria-labelledby="informacoesModal{{ $boleto->id }}Label" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                 
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="informacoesModal{{ $boleto->id }}Label"></h5>
                                                                    
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Responsável</div>
                                                                        <div class="col-md-10">{{ isset($boleto->responsavel_id) ? $boleto->responsavel->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Confirmação</div>
                                                                        <div class="col-md-10">{{ isset($boleto->responsavel_confirmacao_id) ? $boleto->responsavelConfirmacao->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Banco</div>
                                                                        <div class="col-md-10">{{ isset($boleto->banco_id) ? $boleto->banco->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Valor pago</div>
                                                                        <div class="col-md-10">R$ {{ isset($boleto->valor_pago) ? number_format((float)$boleto->valor_pago, 2, ",",".") : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Data de pagamento</div>
                                                                        <div class="col-md-10">{{ isset($boleto->data_pagamento) ? date('d/m/Y', strtotime($boleto->data_pagamento)) : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Data de criação</div>
                                                                        <div class="col-md-10">{{ isset($boleto->created_at) ? date('d/m/Y H:i:s', strtotime($boleto->created_at)) : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Observações</div>
                                                                        <div class="col-md-10">{{ isset($boleto->observacoes) ? $boleto->observacoes : '' }}</div>
                                                                    </div>
                                                                </div>

                                                                <div class="row logs-lista-registros">
                                                                    <div class="col-lg-12">
                                                                        <h5 class="text-center">Logs</h5>
                                                                    </div>

                                                                    <div class="col-lg-12 corpoLog{{ $boleto->id }}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End of Informações Modal -->
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosBoletos->total() > $dadosBoletos->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBoletos->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosBoletos->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBoletos->appends(request()->except('page'))->url($dadosBoletos->currentPage() - 2) }}">{{ $dadosBoletos->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosBoletos->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosBoletos->appends(request()->except('page'))->url($dadosBoletos->currentPage() - 1) }}">{{ $dadosBoletos->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosBoletos->currentPage() }}</a></li>

                                                @if($dadosBoletos->currentPage() + 1 <= $dadosBoletos->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosBoletos->appends(request()->except('page'))->url($dadosBoletos->currentPage() + 1) }}">{{ $dadosBoletos->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosBoletos->currentPage() + 2 <= $dadosBoletos->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBoletos->appends(request()->except('page'))->url($dadosBoletos->currentPage() + 2) }}">{{ $dadosBoletos->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosBoletos->appends(request()->except('page'))->url($dadosBoletos->lastPage()) }}">Último</a></li>
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
