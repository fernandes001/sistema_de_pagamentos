<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatorio em PDF</title>
    </head>

    <body>
        @foreach($dadosCitarDys as $citarDys)    
            <div>
                <div><b>ID:</b> #{{ $citarDys->id }}</div>
                <div><b>Valor pago:</b> R$ {{ number_format((float)$citarDys->valor_pago, 2, ",",".") }}</div>
                <div><b>Data pagamento:</b> {{ isset($citarDys->data_pagamento) ? date('d/m/Y', strtotime($citarDys->data_pagamento)) : '' }}</div>
                <div><b>Banco:</b> {{ isset($citarDys->banco_que_recebemos) ? $citarDys->banco_que_recebemos : '' }}</div>
                <div><b>Comprovante:</b> {{ isset($citarDys->comprovante) ? $citarDys->comprovante : '' }}</div>
                <div><b>Observações:</b> {{ isset($citarDys->observacoes) ? $citarDys->observacoes : '' }}</div>
            </div>
            <br>
            <hr>
            <br>
        @endforeach
    </body>
</html>