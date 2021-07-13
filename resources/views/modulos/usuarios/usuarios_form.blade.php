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
                                    <li class="breadcrumb-item"><a href="{{ url('/usuarios') }}">Usuários</a></li>
                                    @if(isset($usuario->id))
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

                                        <form method="post" id="formUserData" action="/usuarios{{ isset($usuario->id) ? '/'.$usuario->id : '' }}">
                                            <div class="row">
                                                @if(isset($usuario->id))
                                                {{ method_field('PUT') }}
                                                @endif
                                                
                                                {{ csrf_field() }}

                                                <div class="col-lg-3">
                                                    <label for="grupo_id"><b>Grupo*</b></label>
                                                    <select id="grupo_id" name="grupo_id" class="form-control">
                                                        <option value="">--</option>
                                                        <option value="3" {{ isset($usuario->grupo_id) && $usuario->grupo_id === 3 ? 'selected' : '' }}>Root</option>
                                                        <option value="4" {{ isset($usuario->grupo_id) && $usuario->grupo_id === 4 ? 'selected' : '' }}>User</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="name"><b>Nome*</b></label>
                                                        <input type="text" class="form-control" name="nome" id="name" value="{{ isset($usuario->nome) ? $usuario->nome : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email"><b>E-mail*</b></label>
                                                        <input type="email" class="form-control" name="email" id="email" value="{{ isset($usuario->email) ? $usuario->email : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="password"><b>Senha*</b></label>
                                                        <input type="password" name="senha" id="password" class="form-control">
                                                    </div>
                                                </div>
												
												<div class="col-lg-3">
                                                    <label for="empresa"><b>Empresa*</b></label>
                                                    <select id="empresa" name="empresa" class="form-control">
                                                        <option value="">--</option>
                                                        <option value="1" {{ isset($usuario->empresa) && $usuario->empresa === 1 ? 'selected' : '' }}>DYS</option>
                                                        <option value="2" {{ isset($usuario->empresa) && $usuario->empresa === 2 ? 'selected' : '' }}>CITAR</option>
                                                    </select>
                                                </div>
												
												<div class="col-lg-3">
                                                    <label for="ativo"><b>Status*</b></label>
                                                    <select id="ativo" name="ativo" class="form-control">
                                                        <option value="0" {{ isset($usuario->ativo) && $usuario->ativo === 0 ? 'selected' : '' }}>Bloqueado</option>
                                                        <option value="1" {{ isset($usuario->ativo) && $usuario->ativo === 1 ? 'selected' : '' }}>Ativo</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="urlPhoto">Foto</label>
                                                        <input type="text" name="url_photo" id="urlPhoto" class="form-control" value="{{ isset($usuario->url_photo) ? $usuario->url_photo : '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        @if(isset($usuario->id))
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
