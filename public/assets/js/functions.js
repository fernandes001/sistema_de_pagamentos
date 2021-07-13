$(document).ready(function() {
    var saldo_a_receber = moneyFormat($('#saldoAReceber').val());
    
    saldo_a_receber = teste(saldo_a_receber);
    
    $('#valorPago').keyup(function(){
        var b;
        if($(this).val() != ''){
            b = moneyFormat(teste($(this).val()));
        } else {
            b = $(this).val();
        }

        $('#saldoAReceber').val(saldo_a_receber - b);
    });

    $('#paisId').change(function(){
        let pais_id = $(this).val();

        buscaEstadosPorPaises(pais_id);
    });

    $('#estadoId').change(function(){
        let estado_id = $(this).val();
 
        buscaCidadesPorEstado(estado_id);
    });

    $('#novoRegistroModal').submit(function(event){
        event.preventDefault();

        let _token = $('#formRegistrosModal input[name=_token]').val();
        let cliente_id = $('#formRegistrosModal select[name=cliente_id]').val();
        let banco_id = $('#formRegistrosModal select[name=banco_id]').val();
        let favorecido = $('#formRegistrosModal input[name=favorecido]').val();
        let valor = $('#formRegistrosModal input[name=valor]').val();
        let saque_id = $('#formRegistrosModal input[name=saque_id]').val();
        let tipo = $('#formRegistrosModal select[name=tipo]').val();
        let confirmacao_id = $('#formRegistrosModal select[name=confirmacao_id]').val();
        let url_comprovante = $('#formRegistrosModal input[name=url_comprovante]').val();
        let estorno = $('#formRegistrosModal input[name=estorno]').val();
        let status_ultimo = $('#formRegistrosModal select[name=status_ultimo]').val();
        let observacoes = $('#formRegistrosModal textarea[name=observacoes]').val();

        if(status_ultimo == '') {
            alert('Preencha o campo status!');
            return false;
        }

        let url = APP_URL+'/registros';

        $.ajax({
            url : url,
            type : 'post',
            data : {
                '_token' : _token,
                'cliente_id' : cliente_id,
                'banco_id' : banco_id,
                'favorecido' : favorecido,
                'valor' : valor,
                'saque_id' : saque_id,
                'tipo' : tipo,
                'confirmacao_id' : confirmacao_id,
                'url_comprovante' : url_comprovante,
                'estorno' : estorno,
                'status_ultimo' : status_ultimo,
                'observacoes' : observacoes
            },
            beforeSend : function(){
                //$("#resultado").html("ENVIANDO...");
            }
        })
        .done(function(msg){
            msg = JSON.parse(msg);

            if(msg.error === null) {
                $('.modalMsg').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sucesso!</strong> `+msg.success.msg+`
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            } else {
                $('.modalMsg').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erro!</strong> `+msg.error+`
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            }
            clearFields();
        })
        .fail(function(jqXHR, textStatus, msg){
            alert(msg);
        });
    });
});

function clearFields(){
    $('#formRegistrosModal select[name=cliente_id]').val('');
    $('#formRegistrosModal select[name=banco_id]').val('');
    $('#formRegistrosModal input[name=favorecido]').val('');
    $('#formRegistrosModal input[name=valor]').val('');
    $('#formRegistrosModal input[name=saque_id]').val('');
    $('#formRegistrosModal select[name=tipo]').val('');
    $('#formRegistrosModal input[name=url_comprovante]').val('');
    $('#formRegistrosModal input[name=estorno]').val('');
    $('#formRegistrosModal select[name=status_ultimo]').val('');
    $('#formRegistrosModal textarea[name=observacoes]').val('');
}

function moneyFormat(formatedValue) {
    formatedValue = String(formatedValue);
    let valores = formatedValue.split(',').reverse();
    
    if(valores.length == 1) {
        return formatedValue;
    }
    
    let valor = String(valores[1]).replace('.', '')+'.'+String(valores[0]);
    return valor;
}

var filtrosBodyControl = 0;
function abre_fecha_filtro(){
    if(filtrosBodyControl === 0) {
        $('.filtrosBody').css('display', 'block');
        filtrosBodyControl = 1;
    } else {
        $('.filtrosBody').css('display', 'none');
        filtrosBodyControl = 0;
    }
}

var relatoriosBodyControl = 0;
function abre_fecha_relatorios() {
    if(relatoriosBodyControl === 0) {
        $('.informacoesBody').css('display', 'block');
        relatoriosBodyControl = 1;
    } else {
        $('.informacoesBody').css('display', 'none');
        relatoriosBodyControl = 0;
    }
}

// acesso publico de usuários
function confirmaResolvido( id ){
    dados_registro_id             = $("#checkbox_id"+id).val(); 
    dados_confirmacao_id_atual    = $("#checkbox_atual"+id).val();

    $.ajax({
         url : "../assets/ajax/confirmaResolvido.php",
         type : 'post',
         data : {
              registro_id : dados_registro_id,
              confirmacao_id_atual : dados_confirmacao_id_atual
         },
         beforeSend : function(){
              //$("#resultado").html("ENVIANDO...");
         }
    })
    .done(function(msg){ 
         //$("#resultado").html(msg);//
        $("#checkbox_atual"+id).val(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
         alert(msg);
    });
}

function confirmacao(url, modulo, id) {
    $.ajax({
        url : url+"/"+modulo+"/"+id+"/confirmacao",
        type : 'get',
        beforeSend : function(){
            console.log('Enviando...');
        }
    })
    .done(function(msg){ 
        console.log(msg);
        //console.log('done', msg);
        //alert(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
        console.log('fail', msg);
    });
}

function logs(id) {
    let url = APP_URL+'/registros';

    $.ajax({
        url : url+"/"+id+"/logs",
        type : 'get',
        beforeSend : function(){
            console.log('Enviando...');
        }
    })
    .done(function(msg){ 
        let json = JSON.parse(msg);

        let html = '';
        for(var i = 0; i < json.success.data.length; i++) {
            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Data de Criação</div>';
                html += '<div class="col-md-9">'+json.success.data[i].data_criacao+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Tipo Log</div>';
                html += '<div class="col-md-9">'+json.success.data[i].tipo_log+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Tipo</div>';
                html += '<div class="col-md-9">'+json.success.data[i].tipo+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Cliente</div>';
                html += '<div class="col-md-9">'+JSON.parse(json.success.data[i].data).favorecido+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Banco</div>';
                html += '<div class="col-md-9">'+json.success.data[i].banco+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Valor</div>';
                html += '<div class="col-md-9">'+json.success.data[i].valor+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Status</div>';
                html += '<div class="col-md-9">'+json.success.data[i].status+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Responsavel</div>';
                html += '<div class="col-md-9">'+json.success.data[i].responsavel+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Confirmação</div>';
                html += '<div class="col-md-9">'+json.success.data[i].confirmacao+'</div>';
            html += '</div>';

            html += '<div class="row modal-table">'
                html += '<div class="col-md-3">Data de criação</div>';
                html += '<div class="col-md-9">'+json.success.data[i].data_criacao+'</div>';
            html += '</div>';

            html += '<div class="mb-4 row modal-table">'
                html += '<div class="col-md-3">Observações</div>';
                html += '<div class="col-md-9">'+JSON.parse(json.success.data[i].data).observacoes+'</div>';
            html += '</div>';
        }

        $('.corpoLog'+id).html(html);
    })
    .fail(function(jqXHR, textStatus, msg){
        console.log('fail', msg);
    });
}

function logsHistory(id) {
    let url = APP_URL+'/logs';

    $.ajax({
        url : url+"/"+id+"/history",
        type : 'get',
        beforeSend : function(){
            console.log('Enviando...');
        }
    })
    .done(function(msg){ 
        // json = JSON.parse(msg);
        console.log(msg);

        $('.logsHistoryBody').html(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
        console.log('fail', msg);
    });
}

function buscaEstadosPorPaises(pais_id) {
    let url = APP_URL+'/estados/busca';

    $.ajax({
        url : url+"/"+pais_id,
        type : 'get',
        beforeSend : function(){
            console.log('Enviando...');
        }
    })
    .done(function(msg){
        $('#estadoId').html(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
        console.log('fail', msg);
    });
}

function buscaCidadesPorEstado(estado_id) {
    let url = APP_URL+'/cidades/busca';

    $.ajax({
        url : url+"/"+estado_id,
        type : 'get',
        beforeSend : function(){
            console.log('Enviando...');
        }
    })
    .done(function(msg){
        $('#cidadeId').html(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
        console.log('fail', msg);
    });
}

function teste(valor) {
    var arrVal = valor.split('.');
    var lastPos = arrVal.pop();

    var newVal = '';
    for(var i = 0; i < arrVal.length; i++) {
        newVal += arrVal[i];
    }

    valor = moneyFormat(newVal+"."+lastPos);
    return valor;
}

function getLogBoleto(id) {
    if(id !== undefined) {
        let url = APP_URL+'/boletos';

        $.ajax({
            url : url+"/"+id+"/logs",
            type : 'get',
            beforeSend : function(){
                console.log('Enviando...');
            }
        })
        .done(function(msg){ 
            $('.corpoLog'+id).html(msg);
        })
        .fail(function(jqXHR, textStatus, msg){
            console.log('fail', msg);
        });
    }
}

function getLogCitarDys(id) {
    if(id !== undefined) {
        let url = APP_URL+'/citardys';

        $.ajax({
            url : url+"/"+id+"/logs",
            type : 'get',
            beforeSend : function(){
                console.log('Enviando...');
            }
        })
        .done(function(msg){ 
            console.log(msg);

            $('.corpoLog'+id).html(msg);
        })
        .fail(function(jqXHR, textStatus, msg){
            console.log('fail', msg);
        });
    }
}