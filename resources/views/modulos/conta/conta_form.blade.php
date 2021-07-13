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
                                        <li class="breadcrumb-item"><a href="{{ url('/conta') }}">Minha Conta</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
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

                                        <form method="post" id="formUserData" action="/conta/{{ $data->id }}">
                                            <div class="row">
                                                {{ method_field('PUT') }}
                                            
                                                {{ csrf_field() }}

                                                <div class="col-lg-4">
                                                    <label for="grupo_id">Grupo</label>
                                                    <select id="grupo_id" class="form-control" disabled>
                                                        <option value="">--</option>
                                                        <option value="3" {{ $data->grupo_id === 3 ? 'selected' : '' }}>Root</option>
                                                        <option value="4" {{ $data->grupo_id === 4 ? 'selected' : '' }}>User</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="name">Nome</label>
                                                        <input type="text" class="form-control" name="nome" id="name" value="{{ $data->nome }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email">E-mail</label>
                                                        <input type="email" class="form-control" name="email" id="email" value="{{ $data->email }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="password">Senha</label>
                                                        <input type="password" name="senha" id="password" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        Salvar
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
