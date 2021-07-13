<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/registros') }}">Início</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/registros') }}">Registros</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo</li>
                </ol>
            </nav>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Registros
                    </h6>
                </div>

                <div class="card-body">
                    <div class="modalMsg"></div>

                    <form method="post" id="formRegistrosModal" action="/registros">
                        <div class="row">
                            {{ csrf_field() }}

                            <div class="col-lg-3">
                                <label for="clienteId"><b>Cliente*</b></label>
                                <select id="clienteId" name="cliente_id" class="form-control">
                                    <option value="">--</option>
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="bancoId"><b>Banco*</b></label>
                                <select id="bancoId" name="banco_id" class="form-control">
                                    <option value="">--</option>
                                    @foreach($bancos as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="favorecido"><b>Favorecido*</b></label>
                                    <input type="text" class="form-control" name="favorecido" id="favorecido" value="">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="valor"><b>Valor*</b></label>
                                    <input type="text" class="form-control" name="valor" id="valor" value="">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="saqueId"><b>ID Saque*</b></label>
                                    <input type="text" class="form-control" name="saque_id" id="saqueId" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="tipo"><b>Tipo*</b></label>
                                <select id="tipo" name="tipo" class="form-control">
                                    <option value="">--</option>
                                    <option value="1">TED</option>
                                    <option value="2">Boleto</option>
                                    <option value="3">Mesmo banco</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="estorno">Estorno</label>
                                    <input type="text" class="form-control" name="estorno" id="estorno" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="statusUltimo"><b>Status*</b></label>
                                <select id="statusUltimo" name="status_ultimo" class="form-control">
                                    <option value="">--</option>
                                    <option value="0">Pendente</option>
                                    <option value="1">Realizado</option>
                                    <option value="2">Cancelado</option>
                                    <option value="3">Estornado</option>
                                    <option value="4">Aguardando comprovante</option>
                                    <option value="6">Progresso</option>
                                    <option value="7">Erro</option>
                                    <option value="8">Refeito</option>
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <ul class="registros__status-registro">
                                    @php
                                        $arrStatus = array(
                                            '0' => 'Pendente',
                                            '1' => 'Realizado',
                                            '2' => 'Cancelado',
                                            '3' => 'Estornado',
                                            '4' => 'Aguardando comprovante',
                                            '6' => 'Progresso',
                                            '7' => 'Erro',
                                            '8' => 'Refeito'
                                        );
                                    @endphp

                                    @if(isset($dadosRegistroStatus))
                                        @foreach($dadosRegistroStatus as $key => $dStatus)
                                        <li>{{ $key+1 }} - {{ $arrStatus[$dStatus->status] }} - {{ $dStatus->created_at != null ? date('d/m/Y', strtotime($dStatus->created_at)) : '' }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="urlComprovante">Comprovante</label>
                                    <input type="text" class="form-control" name="url_comprovante" id="urlComprovante" value="">
                                    
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <iframe src="https://dyspaga.com.br/geradordecomprovante/" style="width: 100%; height: 700px; border: 0;"></iframe>
                            </div>

                            <div class="col-lg-6 text-left">
                                Responsável: <b>{{ $user_data['nome'] }}</b>
                            </div>

                            <div class="col-lg-6 text-right">
                                <button type="submit" id="novoRegistroModal" class="btn btn-sm btn-primary">
                                    Criar
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
