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
                                        <li class="breadcrumb-item active" aria-current="page">Logs</li>
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
                                                        <form method="GET" action="/logs">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="usuario">Usuário</label>
                                                                        <input type="text" class="form-control" name="usuario" id="usuario" autocomplete="off" value="{{ isset($usuario) ? $usuario : '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <label for="fTipo">Tipo</label>
                                                                    <select id="fTipo" name="tipo" class="form-control">
                                                                        <option value="">--</option>
                                                                        <option value="0" {{ isset($tipo) && $tipo == 0 ? 'selected' : '' }}>Login</option>
                                                                        <option value="1" {{ isset($tipo) && $tipo == 1 ? 'selected' : '' }}>Deleção</option>
                                                                        <option value="2" {{ isset($tipo) && $tipo == 2 ? 'selected' : '' }}>Criação</option>
                                                                        <option value="3" {{ isset($tipo) && $tipo == 3 ? 'selected' : '' }}>Alteração</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <label for="fTabela">Modulo</label>
                                                                    <select id="fTabela" name="tabela" class="form-control">
                                                                        <option value="">--</option>
                                                                        <option value="usuarios" {{ isset($tabela) && $tabela == 'usuarios' ? 'selected' : '' }}>Usuários</option>
                                                                        <option value="clientes" {{ isset($tabela) && $tabela == 'clientes' ? 'selected' : '' }}>Clientes</option>
                                                                        <option value="registros" {{ isset($tabela) && $tabela == 'registros' ? 'selected' : '' }}>Registros</option>
                                                                        <option value="bancos" {{ isset($tabela) && $tabela == 'bancos' ? 'selected' : '' }}>Bancos</option>
                                                                        <option value="boletos" {{ isset($tabela) && $tabela == 'boletos' ? 'selected' : '' }}>Boletos</option>
                                                                        <option value="citardys" {{ isset($tabela) && $tabela == 'citardys' ? 'selected' : '' }}>Citar DYS</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="fCreatedAt">Data</label>
                                                                        <input type="text" class="form-control" name="created_at" id="fCreatedAt" autocomplete="off" value="{{ isset($created_at) ? $created_at : '' }}">
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
                                        <h6 class="m-0 font-weight-bold text-primary">Logs</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Usuário</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Modulo</th>
                                                    <th scope="col">Criado em</th>
                                                    <th scope="col">IP</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $arrTipos = array(
                                                        0 => 'Login',
                                                        1 => 'Deleção',
                                                        2 => 'Criação',
                                                        3 => 'Alteração'
                                                    );
                                                @endphp

                                                @foreach($dadosLogs as $log)
													<tr class="lista_hover">
                                                        <td>{{ $log->usuario->nome }}</td>
                                                        <td>{{ $arrTipos[$log->tipo] }}</td>
                                                        <td>{{ $log->tipo == 0 && $log->tabela == 'usuarios' ? '' : $log->tabela }}</td>
                                                        <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
                                                        <td>{{ $log->ip }}</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-info" href="/logs/{{ $log->id }}" role="button">Visualizar</a>    
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosLogs->total() > $dadosLogs->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosLogs->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosLogs->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosLogs->appends(request()->except('page'))->url($dadosLogs->currentPage() - 2) }}">{{ $dadosLogs->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosLogs->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosLogs->appends(request()->except('page'))->url($dadosLogs->currentPage() - 1) }}">{{ $dadosLogs->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosLogs->currentPage() }}</a></li>

                                                @if($dadosLogs->currentPage() + 1 <= $dadosLogs->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosLogs->appends(request()->except('page'))->url($dadosLogs->currentPage() + 1) }}">{{ $dadosLogs->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosLogs->currentPage() + 2 <= $dadosLogs->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosLogs->appends(request()->except('page'))->url($dadosLogs->currentPage() + 2) }}">{{ $dadosLogs->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosLogs->appends(request()->except('page'))->url($dadosLogs->lastPage()) }}">Último</a></li>
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

        <script>
            setTimeout(function(){
                window.location.reload(1);
            }, 60000);
        </script>
    </body>
</html>
