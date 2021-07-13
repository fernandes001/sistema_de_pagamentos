<!DOCTYPE html>
<html lang="en">
    @include('inc/topo')

    <body class="bg-gradient-primary">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Bem vindo!</h1>
                                        </div>

                                        <form class="user" method="post" action="/login">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <input name="email" type="email" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="E-mail">
                                            </div>

                                            <div class="form-group">
                                                <input name="senha" type="password" class="form-control form-control-user" placeholder="Senha">
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                                    <label class="custom-control-label" for="customCheck">Lembrar-me</label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                        </form>

                                        <hr>
                                        
                                        <div class="text-center">
                                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                                        </div>

                                        <div class="text-center">
                                            <a class="small" href="register.html">Create an Account!</a>
                                        </div>

                                        <div class="text-center pt-2" style="height: 50px;">
                                            @if(session('responseError'))
                                                <p class="text-danger">{{ session('responseError') }}</p>
                                            @endif

                                            @if($errors->any())
                                                <p class="text-danger">Algum campo do formulário não foi preenchido corretamente</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('inc/js')
    </body>
</html>
