$(document).ready(function(){
    $('#telefone').mask('+00 00 0 0000-0000');
    $('#cpf').mask('000.000.000-00');
    $('#cnpj').mask('00.000.000/0000-00');
    $('#agencia').mask('00000000000000000000');
    $('#conta').mask('00000000000000000000');
    $('#qtdeItens').mask('00000000000000000000');
    $('#saqueId').mask('00000000000');
    $('#valor').mask('000.000.000.000.000,00', {reverse: true});
    $('#fValor').mask('000.000.000.000.000,00', {reverse: true});
    $('#estorno').mask('000.000.000.000.000,00', {reverse: true});
    $('#valorPago').mask('000.000.000.000.000,00', {reverse: true});
    $('#saldoAReceber2').mask('###.###.###.###.###,##', {reverse: true});
    $('.list-format-currency').mask('000.000.000.000.000,00', {reverse: true});
    $('#createdAt').mask('00/00/0000 00:00:00');
    $('#fCreatedAt').datepicker({ dateFormat: 'dd/mm/yy' }).val();
	
	//$('#citarDysDataInicio').datepicker({dateFormat: 'dd/mm/yy', minDate: -20, maxDate: "+1M" }).val();
	//$('#citarDysDataFim').datepicker({dateFormat: 'dd/mm/yy', minDate: -20, maxDate: "+1M" }).val();


	$('#citarDysDataInicio').keyup(function(){
		$('#citarDysDataInicio').val('');
	});

	$('#citarDysDataInicio').datepicker({
		dateFormat: 'dd/mm/yy',
		onClose: function(selectedDate){
			let arrData = selectedDate.split('/');
			var date = new Date(arrData[1]+"-"+arrData[0]+"-"+arrData[2]);
			date.setMonth( date.getMonth() + 1 );
	

			let data = ("00" + date.getDate()).slice(-2) +"/"+ ("00" + (date.getMonth() + 1)).slice(-2) +"/"+ date.getFullYear();

			console.log(data);	

			$('#citarDysDataFim').datepicker('option', 'minDate', selectedDate);
			$('#citarDysDataFim').datepicker('option', 'maxDate', data);
		}
	});
	
	$('#citarDysDataFim').keyup(function(){
		$('#citarDysDataFim').val('');
	});

	$('#citarDysDataFim').datepicker({
		dateFormat: 'dd/mm/yy'
	});

    $('#tarifas').mask('000.000.000.000.000,00', {reverse: true});
    $('#duplicidade').mask('000.000.000.000.000,00', {reverse: true});
    $('#valorDuplicado').mask('000.000.000.000.000,00', {reverse: true});
    $('#reembolso').mask('000.000.000.000.000,00', {reverse: true});
    $('#saquePrime').mask('000.000.000.000.000,00', {reverse: true});
    $('.auditoria-bancos').mask('000.000.000.000.000,00', {reverse: true});

    $('#dataRemessa').mask('00/00/0000');
    $('#dataPagamento').mask('00/00/0000');

    $('#dataInicio').datepicker({ dateFormat: 'dd/mm/yy' }).val();
    $('#dataFim').datepicker({ dateFormat: 'dd/mm/yy' }).val();
    $('#dataPagamento').datepicker({ dateFormat: 'dd/mm/yy' }).val();

    $('#cpfCnpj').keypress(function(){
        let cpfCnpj = $(this).val();
        if(cpfCnpj.length < 14) {
            $('#cpfCnpj').mask('000.000.000-00');
        } else {
            $('#cpfCnpj').mask('00.000.000/0000-00');
        }
    });

    $('#cep').mask('000000-000');
});


$('#saldoAReceber').mask('#.###.###.###.###,##', {
  reverse: true,
  translation: {
    '#': {
      pattern: /-|\d/,
      recursive: true
    }
  },
  onChange: function(value, e) {      
    e.target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');
  }
});