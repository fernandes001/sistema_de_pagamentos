@if(session('responseError'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erro!</strong> {{session('responseError')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('responseSuccess'))			  		
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sucesso!</strong> {{session('responseSuccess')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erro!</strong> Algum campo do formulário não foi preenchido corretamente
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif