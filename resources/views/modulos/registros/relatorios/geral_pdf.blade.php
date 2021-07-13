<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatorio em PDF</title>
    </head>

    <body>
        @foreach($dadosRegistros as $registro)
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
            <div>
                <div><b>ID:</b> #{{ $registro->id }}</div>
                <div><b>Cliente:</b> {{ $registro->favorecido }}</div>
                <div><b>Banco:</b> {{ $registro->banco->nome }}</div>
                <div><b>Valor:</b> R$ {{ number_format((float)$registro->valor, 2, ",",".") }}</div>
                <div><b>Status:</b> {{ $arrStatus[$registro->status_ultimo] }}</div>
                <div>
                    <b>Tipo:</b> 
                    @php
                        if($registro->tipo==1){echo 'TED';}
                        if($registro->tipo==2){echo 'Boleto';}
                        if($registro->tipo==3){echo 'TEV';}
                        if($registro->tipo==4){echo 'Desconhecido';}
                    @endphp
                </div>
                <div><b>Responsável:</b> {{ isset($registro->responsavel_id) ? $registro->usuario->nome : '' }}</div>
                <div><b>Confirmado:</b> {{ isset($registro->confirmacao_id) ? $registro->confirmacao->nome : '' }}</div>
                <div><b>Data de Criação:</b> {{ isset($registro->created_at) ? date('d/m/Y H:i:i', strtotime($registro->created_at)) : '' }}</div>
                <div><b>Observações:</b> {{ isset($registro->observacoes) ? $registro->observacoes : '' }}</div>
            </div>
            <br>
            <hr>
            <br>
        @endforeach
    </body>
</html>