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
                                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Início</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Planilhas</li>
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Planilhas</h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Favorecido</th>
                                                    <th scope="col">Valor</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Banco</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($dadosPlanilhas as $item)
                                                    <tr>
                                                        <th scope="row">{{ $item->id }}</th>
                                                        <td>{{ $item->favorecido }}</td>
                                                        <td>{{ $item->valor }}</td>
                                                        <td>{{ $item->status }}</td>
                                                        <td>{{ $item->banco->nome }}</td>
                                                        <td>
                                                            <form method="POST" action="/planilhas/{{ $item->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                
                                                                <a class="btn btn-sm btn-success" href="/planilhas/{{ $item->id }}/edit" role="button">Editar</a>
                                                                <button type="submit" class="btn btn-sm btn-danger" role="button">Deletar</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
