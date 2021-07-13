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
                                        <li class="breadcrumb-item active" aria-current="page">Clientes</li>
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Clientes</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">CPF</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($dadosClientes as $cliente)
                                                    <tr class="lista_hover">
                                                        <td>{{ $cliente->nome }}</td>
                                                        <td>{{ $cliente->email }}</td>
                                                        <td>{{ $cliente->cpf_cnpj }}</td>
                                                        <td>
                                                            <form method="POST" action="/clientes/{{ $cliente->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
                                                                <a class="btn btn-sm btn-success" href="/clientes/{{ $cliente->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($dadosClientes->total() > $dadosClientes->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $dadosClientes->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($dadosClientes->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $dadosClientes->appends(request()->except('page'))->url($dadosClientes->currentPage() - 2) }}">{{ $dadosClientes->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($dadosClientes->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $dadosClientes->appends(request()->except('page'))->url($dadosClientes->currentPage() - 1) }}">{{ $dadosClientes->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $dadosClientes->currentPage() }}</a></li>

                                                @if($dadosClientes->currentPage() + 1 <= $dadosClientes->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $dadosClientes->appends(request()->except('page'))->url($dadosClientes->currentPage() + 1) }}">{{ $dadosClientes->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($dadosClientes->currentPage() + 2 <= $dadosClientes->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $dadosClientes->appends(request()->except('page'))->url($dadosClientes->currentPage() + 2) }}">{{ $dadosClientes->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $dadosClientes->appends(request()->except('page'))->url($dadosClientes->lastPage()) }}">Último</a></li>
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
