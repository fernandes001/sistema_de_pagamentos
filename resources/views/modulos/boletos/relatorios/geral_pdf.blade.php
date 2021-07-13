<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatorio em PDF</title>
    </head>

    <body>
        @foreach($dadosBoletos as $boleto)    
            <div>
				
				
				<div><b>Data de pagamento: </b>{{ isset($boleto->data) ? date('d/m/Y', strtotime($boleto->data)) : '' }}</div>

				<div>
					<b>Valor a receber: </b>
					<?php 
						$data_boleto = date("Y-m-d", strtotime($boleto->data));

						$sql_boleto = 'SELECT (count(r.id)*10) AS valor_boleto FROM registros AS r WHERE DATE_FORMAT(r.created_at,"%Y-%m-%d") = "'.$data_boleto.'"';

						$query_boleto = DB::select($sql_boleto);
						//echo $sql_boleto;
						echo 'R$ ' . number_format((float)$query_boleto[0]->valor_boleto, 2, ",",".");
					?>
				</div>


				<div><b>Valor pago: </b>R$ {{ number_format((float)$boleto->valor_pago, 2, ",",".") }}</div>

				<div>
					<b>Saldo a receber: </b>
					<?php 
						$data = date("Y-m-d", strtotime($boleto->data)).' 23:59:59';

						$sql2 = "SELECT (SELECT count(r.id)*10 AS total_a_receber FROM registros AS r WHERE r.created_at <= '".$data."') 
								-
								(SELECT SUM(b.valor_pago) FROM boletos AS b WHERE b.created_at <= '".$data."')-(1152660) AS saldo_a_receber";

						$query2 = DB::select($sql2);
						//echo $sql2;
						echo 'R$ ' . number_format((float)$query2[0]->saldo_a_receber, 2, ",",".");
					?>
				</div>

				<div><b>Banco: </b>{{ isset($boleto->banco->nome) ? $boleto->banco->nome : 'Registro sem relação' }}</div>	
				
				
				
				
				<?php /*?>
                <div><b>ID:</b> #{{ isset($boleto->id) ? $boleto->id : '' }}</div>
                <div><b>Data pagamento:</b> {{ isset($boleto->data_pagamento) ? date('d/m/Y', strtotime($boleto->data_pagamento)) : '' }}</div>
                <div><b>Confirmação:</b> {{ isset($boleto->banco->nome) ? $boleto->banco->nome : 'Registro sem relação' }}</div>
                <div><b>Valor pago:</b> R$ {{ isset($boleto->valor_pago) ? number_format((float)$boleto->valor_pago, 2, ",",".") : '' }}</div>
                <div><b>Observacoes:</b> #{{ isset($boleto->observacoes) ? $boleto->observacoes : '' }}</div>
				*/?> 
				 
				 
            </div>
            <br>
            <hr>
            <br>
        @endforeach
    </body>
</html>