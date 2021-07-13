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
                                    <li class="breadcrumb-item"><a href="{{ url('/bancos') }}">Bancos</a></li>
                                    @if(isset($dadosBanco->id))
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Novo</li>
                                    @endif
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Usuário
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        @include('inc/msgs')

                                        <form method="post" id="formUserData" action="/bancos{{ isset($dadosBanco->id) ? '/'.$dadosBanco->id : '' }}">
                                            <div class="row">
                                                @if(isset($dadosBanco->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="name"><b>Nome*</b></label>
                                                        <input type="text" class="form-control" name="nome" id="name" value="{{ isset($dadosBanco->nome) ? $dadosBanco->nome : '' }}" maxlength="150">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="agencia">Agência</label>
                                                        <input type="text" class="form-control" name="agencia" id="agencia" value="{{ isset($dadosBanco->agencia) ? $dadosBanco->agencia : '' }}" maxlength="20">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="conta">Conta</label>
                                                        <input type="text" class="form-control" name="conta" id="conta" value="{{ isset($dadosBanco->conta) ? $dadosBanco->conta : '' }}" maxlength="20">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="cnpj">CNPJ</label>
                                                        <input type="text" class="form-control" name="cnpj" id="cnpj" value="{{ isset($dadosBanco->cnpj) ? $dadosBanco->cnpj : '' }}" maxlength="18">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="favorecido">Favorecido</label>
                                                        <input type="text" class="form-control" name="favorecido" id="favorecido" value="{{ isset($dadosBanco->favorecido) ? $dadosBanco->favorecido : '' }}" maxlength="150">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($dadosBanco->id))
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
