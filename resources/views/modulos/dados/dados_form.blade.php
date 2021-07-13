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
                                    <li class="breadcrumb-item"><a href="{{ url('/dados') }}">Dados</a></li>
                                    @if(isset($dado->id))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Dado
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/dados/{{ isset($dado->id) ? $dado->id : '' }}">
                                            <div class="row">
                                                @if(isset($dado->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="dadoA">Dado A</label>
                                                        <input type="text" class="form-control" name="dado_a" id="dadoA" value="{{ isset($dado->dado_a) ? $dado->dado_a : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="dadoB">Dado B</label>
                                                        <input type="text" class="form-control" name="dado_b" id="dadoA" value="{{ isset($dado->dado_b) ? $dado->dado_b : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="dadoC">Dado C</label>
                                                        <input type="text" class="form-control" name="dado_c" id="dadoA" value="{{ isset($dado->dado_c) ? $dado->dado_c : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dado->id))
                                                            Salvar
                                                        @else
                                                            Criar
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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
