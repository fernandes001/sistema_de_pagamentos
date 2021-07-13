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
                                        <li class="breadcrumb-item"><a href="{{ url('/logs') }}">Logs</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Logs
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @php
                                            $arrTipos = array(
                                                0 => 'Login',
                                                1 => 'Deleção',
                                                2 => 'Criação',
                                                3 => 'Alteração'
                                            );
                                        @endphp

                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="usuario">Usuário</label>
                                                    <input type="text" class="form-control" name="usuario" id="usuario" value="{{ isset($dadosLog->usuario_id) ? $dadosLog->usuario->nome : '' }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="tabela">Modulo</label>
                                                    <input type="text" class="form-control" name="tabela" id="tabela" value="{{ $dadosLog->tabela }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="tabela_id">ID da tabela</label>
                                                    <input type="text" class="form-control" name="tabela_id" id="tabela_id" value="{{ $dadosLog->tabela_id }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="ip">IP</label>
                                                    <input type="text" class="form-control" name="ip" id="ip" value="{{ $dadosLog->ip }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="created_at">Data de criação</label>
                                                    <input type="text" class="form-control" name="created_at" id="created_at" value="{{ !is_null($dadosLog->created_at) ? date('d/m/Y H:i:s', strtotime($dadosLog->created_at)) : '' }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="tipo">Tipo</label>
                                                    <input type="text" class="form-control" name="tipo" id="tipo" value="{{ $arrTipos[$dadosLog->tipo] }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="logsHistoryBody"></div>
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

        @if($dadosLog->tabela == 'registros')
        <script>
            logsHistory({{ $dadosLog->id }});
        </script>
        @endif
    </body>
</html>
