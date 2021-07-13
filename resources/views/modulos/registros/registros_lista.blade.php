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
								<div class="row">
									<div class="col-lg-12">
										<nav aria-label="breadcrumb">
											<ol class="breadcrumb">
												<li class="breadcrumb-item"><a href="{{ url('/registros') }}">Início</a></li>
												<li class="breadcrumb-item active" aria-current="page">Registros</li>
                                                @if($createPer)
                                                <li><a href="#" class="btn btn-sm btn-success" id="btNovoRegistro" data-toggle="modal" data-target="#novoRegistroModal">Novo Registro</a></li>
                                                @endif

                                                <li><a href="{{ url('/') }}/registros/gerapdf?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&favorecido={{ $favorecido }}&banco_id={{ $banco_id }}&status_ultimo={{ $status_ultimo }}&tipo={{ $tipo }}&valor={{ $valor }}&id={{ $id }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarPdfRegistros">Exportar PDF</a></li>
                                                <li><a href="{{ url('/') }}/registros/geracsv?data_inicio={{ $data_inicio }}&data_fim={{ $data_fim }}&favorecido={{ $favorecido }}&banco_id={{ $banco_id }}&status_ultimo={{ $status_ultimo }}&tipo={{ $tipo }}&valor={{ $valor }}&id={{ $id }}&qtde_itens={{ $qtde_itens }}" target="_blank" class="btn btn-sm btn-info" id="btExportarCsvRegistros">Exportar CSV</a></li>
                                            </ol>
										</nav>
									</div>
								</div>
								
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
                                                        <form method="GET" action="/registros">
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
                                                                        <label for="fFavorecido">Favorecido</label>
                                                                        <input type="text" class="form-control" name="favorecido" id="fFavorecido" value="{{ $favorecido }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <label for="fBancoId">Banco</label>
                                                                    <select id="fBancoId" name="banco_id" class="form-control">
                                                                        <option value="">--</option>
                                                                        @foreach($bancos as $banco)
                                                                            <option value="{{ $banco->id }}" {{ isset($banco_id) && $banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <label for="fStatusUltimo">Status</label>
                                                                    <select id="fStatusUltimo" name="status_ultimo" class="form-control">
                                                                        <option value="">--</option>
                                                                        <option value="0" {{ isset($status_ultimo) && $status_ultimo == 0 ? 'selected' : '' }}>Pendente</option>
                                                                        <option value="1" {{ isset($status_ultimo) && $status_ultimo == 1 ? 'selected' : '' }}>Realizado</option>
                                                                        <option value="2" {{ isset($status_ultimo) && $status_ultimo == 2 ? 'selected' : '' }}>Cancelado</option>
                                                                        <option value="3" {{ isset($status_ultimo) && $status_ultimo == 3 ? 'selected' : '' }}>Estornado</option>
                                                                        <option value="4" {{ isset($status_ultimo) && $status_ultimo == 4 ? 'selected' : '' }}>Aguardando comprovante</option>
                                                                        <option value="6" {{ isset($status_ultimo) && $status_ultimo == 6 ? 'selected' : '' }}>Progresso</option>
                                                                        <option value="7" {{ isset($status_ultimo) && $status_ultimo == 7 ? 'selected' : '' }}>Erro</option>
                                                                        <option value="8" {{ isset($status_ultimo) && $status_ultimo == 8 ? 'selected' : '' }}>Refeito</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <label for="fTipo">Tipo</label>
                                                                    <select id="fTipo" name="tipo" class="form-control">
                                                                        <option value="">--</option>
                                                                        <option value="1" {{ isset($tipo) && $tipo == 1 ? 'selected' : '' }}>TED</option>
                                                                        <option value="2" {{ isset($tipo) && $tipo == 2 ? 'selected' : '' }}>Boleto</option>
                                                                        <option value="3" {{ isset($tipo) && $tipo == 3 ? 'selected' : '' }}>Mesmo banco</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="fValor">Valor</label>
                                                                        <input type="text" class="form-control" name="valor" id="fValor" value="{{ isset($valor) ? $valor : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="id">ID</label>
                                                                        <input type="text" class="form-control" name="id" id="id" value="{{ isset($id) ? $id : '' }}">
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

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card shadow mb-4">
                                            <div class="card-header py-3">
                                                <div class="row" onclick="abre_fecha_relatorios();">
                                                    <div class="col-8 text-left">
                                                        <h6 class="m-0 font-weight-bold text-primary">Relatórios</h6>
                                                    </div>

                                                    <div class="col-4 text-right">
                                                        <h6 class="m-0 font-weight-bold text-primary"><span class="informacoesBodyBt">+</span></h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body informacoesBody">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <h5>Produtividade</h5>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($relProdutividade as $prod)
                                                                    <tr>
                                                                        <td>{{ $prod->nome }}</td>
                                                                        <td>{{ $prod->qtd }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <h5>Contabilidade (sem estorno)</h5>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($relContabilidade as $cont)
                                                                    <tr>
                                                                        <td>{{ $cont->nome }}</td>
                                                                        <td>R$ {{ number_format((float)$cont->valor, 2, ",",".") }} <b>({{ $cont->qtde_registros }})</b></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-lg-6 pt-5">
                                                        <h5>Contabilidade estorno</h5>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($relContabilidadeEstorno as $est)
                                                                    <tr>
                                                                        <td>{{ $est->nome }}</td>
                                                                        <td>R$ {{ number_format((float)$est->estorno, 2, ",",".") }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-lg-6 pt-5">
                                                        <h5>Contabilidade (com estorno)</h5>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($relContabilidade as $key => $cont)
                                                                    <tr>
                                                                        <td>{{ $cont->nome }}</td>
                                                                        <td>R$ {{ number_format(((float)$cont->valor - (float)$relContabilidadeEstorno[$key]->estorno), 2, ",",".") }} <b>({{ $cont->qtde_registros }})</b></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-lg-6 pt-5">
                                                        <h5>Fechamento do dia</h5>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($relFechamentoDoDia as $fdia)
                                                                    <tr>
                                                                        <td>Total saques</td>
                                                                        <td>{{ $fdia->total_de_saques }}</td>
                                                                    </tr>
                                                                
                                                                    <tr>
                                                                        <td>Total valor pago</td>
                                                                        <td>R$ {{ number_format((float)$fdia->total_valor_pago, 2, ",",".") }}</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Total valor estorno</td>
                                                                        <td>R$ {{ number_format((float)$fdia->total_valor_estorno, 2, ",",".") }}</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Valor a receber</td>
                                                                        <td>R$ {{ number_format((float)$fdia->valor_receber, 2, ",",".") }}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td>Boleto / NF</td>
                                                                        <td>R$ {{ number_format((float)$fdia->total_de_saques * 10, 2, ",",".") }}</td>
                                                                    </tr>
                                                                    
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="m-0 font-weight-bold text-primary">Registros</h6>
                                            </div>

                                            <div class="col-6 text-right">
                                                Registros: <b>{{ $dadosRegistros->total() }}</b>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
													<th scope="col">Data</th>
                                                    <th scope="col">Favorecido</th>
                                                    <th scope="col">Valor</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Banco</th>
													<th scope="col">Tipo</th>
													<th scope="col">Confirmar</th>
                                                    <th scope="col" class="text-center" style="width: 9%;">Ações</th>
													<th scope="col" class="text-center">ID</th>
													<th scope="col" class="text-center">Operador</th>
                                                </tr>
                                            </thead>

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

                                            <tbody>
                                                @foreach($dadosRegistros as $item)
													<tr class="lista_hover">
														<td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">
															@php echo date("d/m/Y H:i:s", strtotime($item->created_at)); @endphp
														</td>
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">{{ $item->favorecido }}</td>
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">R$ {{ number_format((float)$item->valor, 2, ",",".") }}</td>
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">{{ isset($arrStatus[$item->status_ultimo]) ? $arrStatus[$item->status_ultimo] : '' }}</td>
                                                        <td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">{{ $item->banco->nome }}</td>
														<td data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});">
															@php
																if($item->tipo==1){echo 'TED';}
																if($item->tipo==2){echo 'Boleto';}
																if($item->tipo==3){echo 'TEV';}
																if($item->tipo==4){echo 'Desconhecido';}
															@endphp
														</td>
														
														<td>
                                                            @if($item->responsavel_id !== $user_data['user_id'])
															<label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $item->id }}" class="checkboxConfirmacao" onclick="confirmacao('{{ url('/') }}', 'registros', {{ $item->id }});" {{isset($item->confirmacao_id)&&$item->confirmacao_id>0 ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @else
                                                            <label class="switch">
																<input type="checkbox" id="checkbox_confirmacao{{ $item->id }}" class="checkboxConfirmacao" {{isset($item->confirmacao_id)&&$item->confirmacao_id>0 ? 'checked' : '' }} >
															    <span class="slider round"></span>
															</label>
                                                            @endif
															
															<input type="hidden" value="{{ $item->id }}" name="checkbox_id{{ $item->id }}" id="checkbox_id{{ $item->id }}">
															<input type="hidden" value="{{ $user_data['user_id'] }}" name="checkbox_user{{ $item->id }}" id="checkbox_user{{ $item->id }}">
															<input type="hidden" value="{{isset($item->confirmacao_id)&&$item->confirmacao_id>0 ? 1 : 0 }}" name="checkbox_atual{{ $item->id }}" id="checkbox_atual{{ $item->id }}">
															
														</td>
                                                        <td class="text-center">
                                                            <form method="POST" action="/registros/{{ $item->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
																<a class="btn btn-sm btn-success" href="/registros/{{ $item->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
														<td  class="text-center" data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});" style="font-size: 14px;">{{ $item->id }}</td>
														<td  class="text-center" data-toggle="modal" data-target="#informacoesModal{{ $item->id }}" onclick="logs({{ $item->id }});" style="font-size: 14px;">
															{{ isset($item->responsavel_id) ? $item->usuario->nome : '' }}
														</td>
                                                    </tr>
					
                                                    <!-- Begin Informaçõs Modal -->
                                                    <div class="modal fade" id="informacoesModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="informacoesModal{{ $item->id }}Label" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="informacoesModal{{ $item->id }}Label"></h5>
                                                                    
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
																	<div class="row modal-table">
																		<div class="col-md-2">Data</div>
																		<div class="col-md-10">{{ isset($item->created_at) ? date("d/m/Y H:i:s", strtotime($item->created_at)) : '' }}</div>
																	</div>
                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Cliente</div>
                                                                        <div class="col-md-10">{{ $item->favorecido }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Banco</div>
                                                                        <div class="col-md-10">{{ $item->banco->nome }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Valor</div>
                                                                        <div class="col-md-10">R$ {{ number_format((float)$item->valor, 2, ",",".") }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Status</div>
                                                                        <div class="col-md-10">{{ isset($arrStatus[$item->status_ultimo]) ? $arrStatus[$item->status_ultimo] : '' }}</div>
                                                                    </div>

                                                                    

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Tipo</div>
                                                                        <div class="col-md-10">
                                                                            @php
                                                                                if($item->tipo==1){echo 'TED';}
                                                                                if($item->tipo==2){echo 'Boleto';}
                                                                                if($item->tipo==3){echo 'TEV';}
                                                                                if($item->tipo==4){echo 'Desconhecido';}
                                                                            @endphp
                                                                        </div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Responsável</div>
                                                                        <div class="col-md-10">{{ isset($item->responsavel_id) ? $item->usuario->nome : '' }}</div>
                                                                    </div>

                                                                    <div class="row modal-table">
                                                                        <div class="col-md-2">Confirmado</div>
                                                                        <div class="col-md-10">{{ isset($item->confirmacao_id) ? $item->confirmacao->nome : '' }}</div>
                                                                    </div>
																	
																	<div class="row modal-table">
                                                                        <div class="col-md-2">ID</div>
                                                                        <div class="col-md-10">{{$item->id}}</div>
                                                                    </div>

                                                                    <div class="row logs-lista-registros">
                                                                        <div class="col-lg-12">
                                                                            <h5 class="text-center">Logs</h5>
                                                                        </div>

                                                                        <div class="col-lg-12 corpoLog{{ $item->id }}"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End of Informações Modal -->
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosRegistros->total() > $dadosRegistros->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosRegistros->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosRegistros->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosRegistros->appends(request()->except('page'))->url($dadosRegistros->currentPage() - 2) }}">{{ $dadosRegistros->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosRegistros->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosRegistros->appends(request()->except('page'))->url($dadosRegistros->currentPage() - 1) }}">{{ $dadosRegistros->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosRegistros->currentPage() }}</a></li>

                                                @if($dadosRegistros->currentPage() + 1 <= $dadosRegistros->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosRegistros->appends(request()->except('page'))->url($dadosRegistros->currentPage() + 1) }}">{{ $dadosRegistros->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosRegistros->currentPage() + 2 <= $dadosRegistros->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosRegistros->appends(request()->except('page'))->url($dadosRegistros->currentPage() + 2) }}">{{ $dadosRegistros->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosRegistros->appends(request()->except('page'))->url($dadosRegistros->lastPage()) }}">Último</a></li>
                                            </ul>
                                        </nav>
                                    @endif
                                </div>

                                @if($createPer)
                                <!-- Begin Novo Registro Modal -->
                                <div class="modal fade" id="novoRegistroModal" tabindex="-1" role="dialog" aria-labelledby="novoRegistroModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="novoRegistroModalLabel"></h5>
                                                
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='/registros';">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                @include('modulos/registros/registros_form_modal')
                                            </div>
                                            
                                            <!--<div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                <button type="button" class="btn btn-primary">Criar</button>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Novo Registro Modal -->
                                @endif
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
