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
                                        <li class="breadcrumb-item active" aria-current="page">Usuários</li>
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Usuários</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Grupo</th>
													<th scope="col">Status</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $arrGrupos = array(
                                                        3 => 'Root',
                                                        4 => 'User'
                                                    );
                                                @endphp

                                                @foreach($usuarios as $usuario)
                                                    <tr class="lista_hover">
                                                        <td>{{ $usuario->nome }}</td>
                                                        <td>{{ $usuario->email }}</td>
                                                        <td>{{ $arrGrupos[$usuario->grupo_id] }}</td>
														<td>{{ ($usuario->ativo)==1 ? 'Ativo' : 'Bloqueado' }}</td>
                                                        <td>
                                                            <form method="POST" action="/usuarios/{{ $usuario->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
                                                                <a class="btn btn-sm btn-success" href="/usuarios/{{ $usuario->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($usuarios->total() > $usuarios->perPage())
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item"><a class="page-link" href="{{ $usuarios->appends(request()->except('page'))->url(1) }}">Início</a></li>

                                                @if($usuarios->currentPage() - 2 > 0)
                                                <li class="page-item"><a class="page-link" href="{{ $usuarios->appends(request()->except('page'))->url($usuarios->currentPage() - 2) }}">{{ $usuarios->currentPage() - 2 }}</a></li>
                                                @endif

                                                @if($usuarios->currentPage() - 1 > 0)
                                                <li class="page-item"><a class="page-link" title="Página Anterior" href="{{ $usuarios->appends(request()->except('page'))->url($usuarios->currentPage() - 1) }}">{{ $usuarios->currentPage() - 1 }}</a></li>
                                                @endif

                                                <li class="page-item active"><a class="page-link">{{ $usuarios->currentPage() }}</a></li>

                                                @if($usuarios->currentPage() + 1 <= $usuarios->lastPage())
                                                <li class="page-item"><a class="page-link" title="Próxima Página" href="{{ $usuarios->appends(request()->except('page'))->url($usuarios->currentPage() + 1) }}">{{ $usuarios->currentPage() + 1 }}</a></li>
                                                @endif

                                                @if($usuarios->currentPage() + 2 <= $usuarios->lastPage())
                                                <li class="page-item"><a class="page-link" href="{{ $usuarios->appends(request()->except('page'))->url($usuarios->currentPage() + 2) }}">{{ $usuarios->currentPage() + 2 }}</a></li>
                                                @endif

                                                <li class="page-item"><a class="page-link" href="{{ $usuarios->appends(request()->except('page'))->url($usuarios->lastPage()) }}">Último</a></li>
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
