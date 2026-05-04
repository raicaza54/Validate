/**
 * Perfil - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2020 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2020-06-29
 */
var COMPRAR = COMPRAR || {};
COMPRAR.methods = {
    comprarModal: function () {
        var ventana = $('#ventanaModal');
        GLOBAL.computed.initializeModal(ventana);
        ventana.find('.modal-title').text('Comprar Validate System');
        ventana.find('.btn-primary').text('Enviar Solicitud');
        ventana.find('.btn-primary').attr('onclick','COMPRAR.computed.procesar()');
        ventana.find('.btn-link').text('Cancelar');
        var datos = COMPRAR.computed.cargaDatos();
        datos.then(function (data) {
            if(parseInt(data.status) == 200){
                COMPRAR.componets.parametrosModal(data);
            }
            return data;
        }).then(function (data) {
            if(parseInt(data.status) == 200){
                ventana.modal('show');
            }
        });        
    }
}
COMPRAR.computed = {
    cargaDatos: function() {
        return $.ajax({
            url: '/perfil/v1/dataComprar',
            type: "GET",
            dataType: 'json',
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }            
                GLOBAL.computed.secure();
            }
        });
    },
    procesar: function () {
        var form_data = $('form#form-comprar').serializeArray();
        return $.ajax({
            url: '/perfil/v1/saveComprar',
            type: "POST",
            dataType: 'json', 
            data: {form: form_data},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    var ventana = $('#ventanaModal');
                    GLOBAL.computed.initializeModal(ventana);
                    ventana.find('.modal-title').text('Comprar Validate System');
                    ventana.find('.btn-primary').hide();
                    ventana.find('.btn-link').text('Aceptar');
                    COMPRAR.componets.enviarModal();                    
                }
                GLOBAL.computed.secure();
            }
        });        
    }
}
COMPRAR.componets = {
    parametrosModal: function (data) {
        var cliente = data.data.cliente;
        $('#ventanaModal .modal-body').html(
            `<form id="form-comprar">
                <p>
                    ¡Gracias por utilizar nuestro demo y contratar con nosotros! Haz tomado la mejor decisión, nuestro equipo comercial en breve se pondrá en contacto, dejanos por favor tus datos o confirma
                </p>
                <div class="form-group">
                    <label for="com-nombre">Nombre y Apellido</label>
                    <input type="text" maxlength="250" class="form-control" id="com-nombre" name="com-nombre" value="` + cliente.nombre + `">
                </div>
                <div class="form-group">
                    <label for="com-nombre">Correo Electrónico</label>
                    <input type="text" maxlength="250" class="form-control" id="com-correo" name="com-correo" value="` + cliente.email + `">
                </div>
                <div class="form-group">
                    <label for="com-telefono">Teléfono de Contacto</label>
                    <input type="text" maxlength="250" class="form-control" id="com-telefono" name="com-telefono" value="` + cliente.telefono + `">
                </div>
                <div class="form-group">
                    <label for="com-observacion">Comentario</label>
                    <textarea class="form-control" id="com-observacion" name="com-observacion" rows="3"></textarea>
                </div>        
            </form>`);
    },
    enviarModal: function () {
        $('#ventanaModal .modal-body').html(
            `<h4 class="text-center" style="margin: 30px">La solicitud fue recibida, nuestro equipo comercial <br/>en breve se pondrá en contacto</h4>`);        
    }
}