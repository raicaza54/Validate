/**
 * Spider - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var SPIDER = SPIDER || {};
SPIDER.methods = {
    parametros: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Movimiento 
                    para realizar el analisis aplicando La Araña`);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Movimiento 
                    para realizar el analisis aplicando La Araña`);
                return;                
            }
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana, true, 'modal-lg');
            var idArchivo = $('[name="archivoId"]').val();
            ventana.find('.modal-title').text('La Araña');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);
            ventana.find('.btn-primary').html('Procesar');
            ventana.find('.btn-primary').attr('onclick','SPIDER.methods.procesar(this)');
            ventana.find('.btn-link').text('Cancelar');
            SPIDER.componets.parametrosModal();
            if(typeof idArchivo !== 'undefined'){
                GLOBAL.archivoId = idArchivo;
            }
            var datos = SPIDER.computed.cargaDatos(GLOBAL.archivoId);
            datos.then(function (data) {
                if(data.status == 200){
                    SPIDER.componets.selectPoblar(data);
                }
                return data;
            }).then(function (data) {
                if(data.status == 200){
                    ventana.modal('show');
                    $('.selectpicker.cuentas').selectpicker({language: 'ES', title: 'Seleccione una cuenta o hasta un máximo  de 5'});
                    $('.selectpicker.comprobantes').selectpicker({language: 'ES', title: 'Todos los comprobantes'});
                }
                $("body").mLoading('hide');
            });
        }
    },
    procesarClick: function(obj, cuenta) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            SPIDER.methods.procesar(true, cuenta);
        }
    },
    procesar: function (obj, cuenta = null) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = SPIDER.computed.procesarSpider(cuenta);
            datos.then(function (data) {
                if(parseInt(data.status) == 200){
                    SPIDER.componets.graficaSpider();
                    $("body").mLoading('hide');
                    $('#body-spider').html(data.data.body);
                    //SPIDER.componets.tablas(data, 'debito');
                    //SPIDER.componets.tablas(data, 'credito');
                    $('div#form-spider form input').remove();
                    $('#ventanaModal .modal-body').html('');
                    return data;                    
                }
            }).then(function (data) {
                if(GLOBAL.computed.isset(data)){
                    GLOBAL.computed.maximizar();
                    if (!$('#content').find('div#form-spider form input').length) {
                        $.each(data.data.form, function (key, value) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: key,
                                value: value
                            }).appendTo('div#form-spider form');
                        });
                    }                    
                }
            }).then(function () {
                GLOBAL.computed.spider();
            }).then(function () {
                $('div').tooltip({trigger: 'hover', delay: 200});
            });
        }
    },
    clearChosen: function(e) {
        $('.selectpicker.' + e + ' option:selected').val('');
        $('.selectpicker.' + e).selectpicker('deselectAll');
    }
}
SPIDER.computed = {
    cargaDatos: function (id) {
        return $.ajax({
            url: '/spider/v1/cuentas',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            beforeSend: function (xhr) {
                $("body").mLoading();
            },
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
    procesarSpider: function(cuenta = null) {
        var form_data = $('form#form-spider').serializeArray();
        if(parseInt(GLOBAL.archivoId) <= 0){
            GLOBAL.computed.toast(
                `Debes seleccionar un archivo de Movimiento 
                para realizar el analisis aplicando La Araña`);
            return;                
        }
        return $.when().then(function () {
            GLOBAL.computed.initializePdf('spider');
        }).then(function () {
            GLOBAL.pdfSpider = GLOBAL.computed.uniqint();
        }).then(function () {
            var form_data = [];
            if(cuenta != null){
                form_data.push({ name: "clickSpider", value: cuenta });
            }else{
                form_data.push({ name: "archivoIdProcesar", value: GLOBAL.archivoId });
                form_data.push({ name: "campoSpider", value: GLOBAL.computed.valuesSelect('campoSpider') });
                form_data.push({ name: "comprobantes", value: GLOBAL.computed.valuesSelect('comprobantes') });                
                form_data = GLOBAL.computed.removeItemFromArr(form_data, 'clickSpider');
            }
            form_data.push({ name: "ejecucion", value: GLOBAL.pdfSpider });
            var ventana = $('#ventanaModal');
            return $.ajax({
                url: '/spider/v1/procesar',
                type: "POST",
                dataType: 'json', 
                data: {form: form_data},
                beforeSend: function (xhr) {
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', true);
                    ventana.find('.btn-link').prop('disabled', true);
                    ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                    $('#ventanaModal').modal('hide');
                    $("body").mLoading();
                },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                        ventana.find('.btn-primary').html('Procesar');
                        ventana.find('.btn-primary').prop('disabled', false);
                        ventana.find('.btn-link').prop('disabled', false);
                    }
                    GLOBAL.computed.secure();
                    ventana.find('.btn-primary').html('Procesar');
                }
            });
        });
    }    
}
SPIDER.componets = {
    limpiarContent: function () {
        $('#content').html(
            `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>            
            </div>`
        );
    },  
    tablas: function(data, tabla) {
        $('#spills-d2').append(`<h4 style="text-transform: uppercase;">` + tabla + `S</h4>`);
        $.each(data.data.tabla.tabla, function (key, value) {
            $('#spills-d2').append(`
                <table id="table-spider` + tabla + key + `" class="display table table-bordered table-hover table-sm table-striped mt-2">
                    <thead>
                        <tr>
                            <th>Registro</th>
                            <th>Cuenta</th>
                            <th>Comprobante</th>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Ref</th>
                            <th>NIT</th>
                            <th>Detalle</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Base</th>
                            <th>CC</th>
                            <th>TB</th>
                            <th>PL</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            `);
            $.each(value, function (dkey, dvalue) {
                $('#table-spider' + tabla + key + ' tbody').append(`
                    <tr>
                        <td class="text-right">` + dvalue.fila.REGISTRO + `</td>
                        <td class="text-right">` + dvalue.fila.CUENTA + `</td>
                        <td class="text-left">` + dvalue.fila.CTE + `</td>
                        <td class="text-left">` + dvalue.fila.FECHA + `</td>
                        <td class="text-left">` + dvalue.fila.DOC + `</td>
                        <td class="text-left">` + dvalue.fila.REF + `</td>
                        <td class="text-left">` + dvalue.fila.NIT + `</td>
                        <td class="text-left">` + dvalue.fila.DETALLE + `</td>
                        <td class="text-right">` + dvalue.fila.TIPO + `</td>
                        <td class="text-right">` + GLOBAL.computed.number_format(dvalue.fila.VALOR,2,',','.') + `</td>
                        <td class="text-right">` + GLOBAL.computed.number_format(dvalue.fila.BASE,2,',','.') + `</td>
                        <td class="text-left">` + dvalue.fila.CC + `</td>
                        <td class="text-left">` + dvalue.fila.TB + `</td>
                        <td class="text-left">` + dvalue.fila.PL + `</td>
                    </tr>
                `);
            });                
        });        
    },
    graficaSpider: function() {
        if (!$('#content').find('nav#tabs-spider').length ) {
            $('#content').html(`
                <nav id="tabs-spider">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-spider-tab" data-toggle="tab" href="#nav-spider" role="tab" aria-controls="nav-spider" aria-selected="true">La Araña</a>
                        <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                    </div>
                </nav>
                <div id="form-spider"><form id="form-spider"></form></div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active clearfix" id="nav-spider" role="tabpanel" aria-labelledby="nav-spider-tab">
                        <div id="body-archivo">
                            <ul class="nav position-relative" id="menu-tab" role="tablist">
                                <li class="nav-item">
                                    <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                        <i class="far fa-save"></i>
                                        Guardar PDF
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link btn-span" onClick="SPIDER.methods.parametros(this)">
                                        <i class="fas fa-spider"></i>
                                        Araña
                                    </span>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                    <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.spider)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                    <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.spider)"><i class="far fa-window-restore"></i> Restaurar</span>
                                </li>            
                            </ul>
                            <div class="scrollTable">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active clearfix" id="spills-d1" role="tabpanel" aria-labelledby="spills-d1-tab">
                                        <div id="body-spider" style="margin: 0 auto;"></div>
                                        <div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2"></div>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>
                </div>`);
        }
    },
    selectPoblar: function (datos) {
        var cuenta;
        $.each(datos.data.items, function (key, value) {
            cuenta = '';
            if(datos.data.cuentas[value[datos.data.column]]){
                cuenta = datos.data.cuentas[value[datos.data.column]];
            }
            $('#campoSpider').append($('<option>', {
                value: value[datos.data.column],
                text : value[datos.data.column] + ' ' + cuenta,
            }));
        });
        $.each(datos.data.comp, function (key, value) {
            $('#comprobantes').append($('<option>', {
                value: value[datos.data.columnComp],
                text : value[datos.data.columnComp],
            }));
        });
    },
    parametrosModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-spider">
                <p>
                    Es un informe extraído del movimiento contable de una empresa, lo puedo aplicar a el movimiento de un periodo, puede ser mes, 
                    trimestre, semestre o año.  Este me da como resultado el movimiento entre cuentas, es decir contra que cuentas se movió la 
                    cuenta que yo consulte, mostrándome de donde le ingreso movimiento y hacia donde se generaron transacciones, con esto puedo 
                    detectar movimientos anómalos por errores contables o fraude.
                </p>    
                <div class="row">
                    <label for="campoSpider" class="float-left col-6">Cuenta en la Ara&ntilde;a:&nbsp;&nbsp;</label>
                    <label class="float-right text-truncate col-6 text-right">Archivo: ` + GLOBAL.archivoNombre + `</label>
                </div>        
                <div class="input-group mb-3">
                    <select multiple data data-live-search="true" data-max-options="5" id="campoSpider" name="campoSpider" class="form-control col selectpicker cuentas"></select>
                    <div class="input-group-append">
                        <button class="btn btn-formulario" onClick="SPIDER.methods.clearChosen('cuentas')" type="button">Desmarcar Todo</button>
                    </div>
                </div>
                <div class="row">
                    <label for="comprobantes" class="float-left col-6">Nro. Comprobante</label>
                </div>
                <div class="input-group">
                    <select multiple data-live-search="true" data-actions-box="true" data-max-options="10" id="comprobantes" name="comprobantes" class="form-control col selectpicker comprobantes"></select>
                    <div class="input-group-append">
                        <button class="btn btn-formulario" onClick="SPIDER.methods.clearChosen('comprobantes')" type="button">Desmarcar Todo</button>
                    </div>        
                </div>
            </form>`);
    }
}
