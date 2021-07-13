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
                                        <li class="breadcrumb-item active" aria-current="page">Bancos</li>
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
                                                        <form method="GET" action="/bancos">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="bancoNome">Banco</label>
                                                                        <input type="text" class="form-control" name="banco_nome" id="bancoNome" autocomplete="off" value="{{ isset($banco_nome) ? $banco_nome : '' }}">
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
                                        <h6 class="m-0 font-weight-bold text-primary">Bancos</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">CNPJ</th>
                                                    <th scope="col">Favorecido</th>
                                                    <th scope="col">Agência</th>
                                                    <th scope="col">Conta</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($dadosBancos as $banco)
													<tr class="lista_hover">
                                                        <td>{{ $banco->nome }}</td>
                                                        <td>{{ $banco->cnpj }}</td>
                                                        <td>{{ $banco->favorecido }}</td>
                                                        <td>{{ $banco->agencia }}</td>
                                                        <td>{{ $banco->conta }}</td>
                                                        <td>
                                                            <form method="POST" action="/bancos/{{ $banco->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
                                                                <a class="btn btn-sm btn-success" href="/bancos/{{ $banco->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosBancos->total() > $dadosBancos->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBancos->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosBancos->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBancos->appends(request()->except('page'))->url($dadosBancos->currentPage() - 2) }}">{{ $dadosBancos->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosBancos->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosBancos->appends(request()->except('page'))->url($dadosBancos->currentPage() - 1) }}">{{ $dadosBancos->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosBancos->currentPage() }}</a></li>

                                                @if($dadosBancos->currentPage() + 1 <= $dadosBancos->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosBancos->appends(request()->except('page'))->url($dadosBancos->currentPage() + 1) }}">{{ $dadosBancos->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosBancos->currentPage() + 2 <= $dadosBancos->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosBancos->appends(request()->except('page'))->url($dadosBancos->currentPage() + 2) }}">{{ $dadosBancos->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosBancos->appends(request()->except('page'))->url($dadosBancos->lastPage()) }}">Último</a></li>
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
