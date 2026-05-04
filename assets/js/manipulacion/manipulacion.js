/**
 * Manipulacion - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-03-19
 */
var MANIPULACION = MANIPULACION || {};
MANIPULACION.suma_acorrientes_anocorrientes = [];
MANIPULACION.suma_otrospasivosc_pnocorrientes = [];
MANIPULACION.suma_obligacionesfc_obligacionesfnoc = [];
MANIPULACION.balances = [];
MANIPULACION.options = {
    mask: Number,
    scale: 2,
    min: -9999999999999,
    max: 9999999999999,
    thousandsSeparator: '.'            
};
MANIPULACION.methods = {
    parametros: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un directorio que 
                    contengan Balances de Prueba para comprobar Manipulación`);
                return;
            }
            var ventana = $('#ventanaModal');
            var folderId = GLOBAL.folderId;
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            ventana.find('.modal-title').text('Indicadores de Cambio');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);        
            ventana.find('.btn-primary').text('Procesar');
            ventana.find('.btn-primary').attr('onclick','MANIPULACION.methods.manipulacion(this)');
            ventana.find('.btn-link').text('Cancelar');
            MANIPULACION.componets.parametrosModal();
            var datos = MANIPULACION.computed.cargaDatos(folderId);
            datos.then(function (data) {
                if(parseInt(data.status) == 200){
                    var poblar = MANIPULACION.componets.balancesPoblar(data);
                }
                return {data:data, poblar:poblar};
            }).then(function (data) {
                if((parseInt(data.data.status) == 200) && ((data.poblar.columnasF <= 0) && (data.poblar.columnasT >= 2))){
                    $("body").mLoading('hide');
                    ventana.modal('show');
                }
            });
        }
    },
    parametrosConfianza: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un directorio que 
                    contengan Balances de Prueba para comprobar Manipulación`);
                return;
            }
            var ventana = $('#ventanaModal');
            var folderId = GLOBAL.folderId;
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            ventana.find('.modal-title').text('Indicadores de Confianza');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);        
            ventana.find('.btn-primary').text('Siguiente');
            ventana.find('.btn-primary').attr('onclick','MANIPULACION.methods.editarConfianza(this)');
            ventana.find('.btn-link').text('Cancelar');
            MANIPULACION.componets.parametrosModal();
            var datos = MANIPULACION.computed.cargaDatos(folderId);
            $("body").mLoading('show');
            datos.then(function (data) {
                if(parseInt(data.status) == 200){
                    var poblar = MANIPULACION.componets.balancesPoblar(data);
                }
                return {'data':data, 'poblar':poblar};
            }).then(function (data) {
                if((parseInt(data.data.status) == 200) && ((data.poblar.columnasF <= 0) && (data.poblar.columnasT >= 2))){
                    $("body").mLoading('hide');
                    ventana.modal('show');
                }
            });
        }
    },
    editarConfianza: function() {
        var balances = [], id;
        MANIPULACION.balances = [];
        $.each($("input[name='balances']:checked"), function(){
            id = $(this).attr('id');
            MANIPULACION.balances.push({
                balance: $('input#'+id).val(),
                posicion: $('select#'+id).val()
            });
        });
        return $.ajax({
            url: '/manipulacion/v1/editarConfianza',
            type: "POST",
            dataType: 'json',
            data: {
                balances: MANIPULACION.balances
            },            
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                GLOBAL.computed.secure();
                if(status == 200){
                    var ventana = $('#ventanaModal');
                    ventana.find('.btn-primary').text('Procesar');
                    ventana.find('.btn-primary').attr('onclick','MANIPULACION.methods.confianza(this)');                    
                    ventana.find('.btn-link').hide();
                    var button = '<button type="button" onClick="MANIPULACION.methods.parametrosConfianza(this)" class="btn btn-secondary">Atras</button>';
                    ventana.find('#btn-extra').html(button);
                    ventana.find('#btn-extra').show();                    
                    MANIPULACION.componets.parametrosIndicadores(data.data);
                }
            }
        });
    },
    manipulacion: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = MANIPULACION.computed.procesar();
            datos.then(function (data) {
                MANIPULACION.componets.tabs(data);
            }).then(function(){
                $("body").mLoading('hide');
                GLOBAL.computed.maximizar();
            });
        }
    },
    confianza: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = MANIPULACION.computed.procesarConfianza();
            datos.then(function (data) {
                MANIPULACION.componets.tabsConfianza(data);
            }).then(function(){
                $("body").mLoading('hide');
                GLOBAL.computed.maximizar();
            });
        }
    },
    posicion: function(obj, e) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var sl, id;
            $.each($("input[name='balances']:checked"), function(){
                id = $(this).attr('id');
                sl = $('select#'+e).val();
                if(e != id){
                    if(sl == 't'){
                        $('select#'+ id + ' option[value="t-1"]').prop('selected', true);
                    }else{
                        $('select#'+ id + ' option[value="t"]').prop('selected', true);
                    }
                }
            });
        }
    },
    vista: function (obj, e) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var chk = $('input#chek'+e), sel;
            sel = $('select[name="posicion"]').not('.d-none').val();
            $('select#chek'+ e + ' option[value="t"]').prop('selected', true);
            if(chk.prop('checked')){
                $('select#chek'+e).removeClass('d-none');
                if(sel == 't'){
                    $('select#chek'+ e + ' option[value="t-1"]').prop('selected', true);
                }
            }else{
                $('select#chek'+e).addClass('d-none');
                if(sel == 't'){
                    $('select#chek'+ e + ' option[value="t-1"]').prop('selected', true);
                }            
            }
            var x = 0, id;
            $.each($("input[name='balances']:checked"), function(){
                x++;
                id = $(this).attr('id');
                if(x > 2){
                    $(this).prop('checked', false);
                    $('select#'+id).addClass('d-none');
                }
            });        
        }
    },
    indicadorAjustado: function (ax, bx, totalx) {
        var a = $('#' + ax).val().replace(/\./g,'').replace(/,/g,'.');
        var total = $('#' + totalx).html().replace(/\./g,'').replace(/,/g,'.');
        var c = total - a;
        $('#' + bx).val(c);
        eval('MANIPULACION.' + bx + '.value = String(' + c + ')');
    }
}
MANIPULACION.computed = {
    cargaDatos: function (folderId) {
        return $.ajax({
            url: '/manipulacion/v1/archivos',
            type: "POST",
            dataType: 'json',
            data: {
                folderId: folderId
            },
            beforeSend: function (xhr) {
                $("body").mLoading('show');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                $("body").mLoading('hide');
                GLOBAL.computed.secure();
            }
        });
    },
    procesarConfianza: function() {
        var form_data = $('form#form-confianza').serializeArray();
        return $.when().then(function () {
            GLOBAL.computed.initializePdf('confianza');
        }).then(function () {
            GLOBAL.pdfConfianza = GLOBAL.computed.uniqint();
        }).then(function () {
            var ventana = $('#ventanaModal');
            return $.ajax({
                url: '/manipulacion/v1/confianza',
                type: "POST",
                dataType: 'json',
                data: {
                    form: form_data,
                    balances: MANIPULACION.balances,
                    ejecucion: GLOBAL.pdfConfianza,
                },
                beforeSend: function (xhr) {
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', true);
                    ventana.find('.btn-link').prop('disabled', true);
                    ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                    $("body").mLoading();
                },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }
                    GLOBAL.computed.secure();
                    ventana.modal('hide');
                }
            });
        });        
    },
    procesar: function () {
        var balances = [], id;
        $.each($("input[name='balances']:checked"), function(){
            id = $(this).attr('id');
            balances.push({
                balance: $('input#'+id).val(),
                posicion: $('select#'+id).val()
            });
        });
        return $.when().then(function () {
            GLOBAL.computed.initializePdf('manipulacion');
        }).then(function () {
            GLOBAL.pdfManipulacion = GLOBAL.computed.uniqint();
        }).then(function () {
            var ventana = $('#ventanaModal');
            return $.ajax({
                url: '/manipulacion/v1/procesar',
                type: "POST",
                dataType: 'json',
                data: {
                    balances: balances,
                    ejecucion: GLOBAL.pdfManipulacion,
                },
                beforeSend: function (xhr) {
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', true);
                    ventana.find('.btn-link').prop('disabled', true);
                    ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                    $("body").mLoading();
                },            
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }
                    GLOBAL.computed.secure();
                    ventana.modal('hide');
                }
            });            
        });
    }
}
MANIPULACION.componets = {
    limpiarContent: function() {
        $('#content').html(
            `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>            
            </div>`
        );
        GLOBAL.computed.toast('Se ha seleccionado la empresa exitosamente, ahora carga o selecciona un archivo para que empecemos los análisis');
    },
    balancesPoblar: function (datos) {
        var estado = '', columnasF = 0, columnasT = 0;
        $.each(datos.data.balances, function (key, value) {
            estado = value['estado'];
            $('form#form-manipulacion div#balances #table-balances').append(
                `<tr>
                    <td style="padding-top: 15px; height: 54px;">
                        <div class="form-group form-check">
                            <input ` + ((estado == 'F') ? 'disabled="disabled"' : '') + ` name="balances" value="` + value.id + `" type="checkbox" onchange="MANIPULACION.methods.vista(this, ` + value.id + `)" class="form-check-input" id="chek` + value.id + `">
                            <label class="form-check-label ` + ((estado == 'F') ? 'text-danger' : '') + `" for="chek` + value.id + `">` + value.label + `</label>
                        </div>
                    </td>
                    <td style="width: 200px;">
                        <div class="form-group">
                            <select name="posicion" class="form-control d-none" onchange="MANIPULACION.methods.posicion(this, 'chek` + value.id + `')" id="chek` + value.id + `">
                                <option value="t">Reciente</option>
                                <option value="t-1">Anterior</option>
                            </select>
                        </div>                        
                    </td>
                </tr>`
            );
            if(estado == 'F'){
                columnasF++;
            }
            if(estado == 'T'){
                columnasT++;
            }
        });
        if(columnasT < 2){
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            ventana.find('.btn-primary').hide();
            ventana.find('.btn-primary').prop('disabled', true);
            ventana.find('.btn-link').text('Cerrar');
        }
        if(columnasF > 0){
            GLOBAL.computed.toast('Algunos de los archivos no contienen la definición de las columnas necesarias para la Manipulación, las cuales son: Número de Cuenta, Nombre de Cuenta y Saldo Final');
        }
        if(columnasT < 2){
            GLOBAL.computed.toast('La Manipulación requiere mínimo dos archivos validos para realizar los cálculos','error', 500);
        }
        return {'columnasF':columnasF, 'columnasT':columnasT};
    },
    tabsConfianza: function (data) {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-manipulacion-tab" data-toggle="tab" href="#nav-manipulacion" role="tab" aria-controls="nav-manipulacion" aria-selected="true">Indicadores de Confianza</a>
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-manipulacion" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-confianza">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                    <i class="far fa-save"></i>
                                    Guardar PDF
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="MANIPULACION.methods.parametrosConfianza(this)">
                                    <i class="fas fa-user-secret"></i>
                                    Indicadores de Confianza
                                </span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable">
                            <div class="summernote wysiwyg summernote-addinit" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer1"></div>
                            <div class="alert alert-info" role="alert">
                                <h6 style="margin-bottom: 0px;">` + data.data.mensaje + `<br/><div class="mt-2">La probabilidad de los Indicadores de Cambio es ` + data.data.probabilidad + `%</div></h6>
                            </div>
                            <table id="table-manipulacion" class="display table table-bordered table-hover table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="3"><h5>SCORE DE INDICADORES DE CONFIANZA</h5></td>
                                        <td colspan="2" class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.m8ind,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="width: 20%;">
                                            INDICADORES
                                        </td>
                                        <td class="text-right">
                                            FACTOR IDEAL
                                        </td>
                                        <td class="text-right">
                                            VARIACI&Oacute;N (+/-)
                                        </td>
                                        <td class="text-center">
                                            AN&Aacute;LISIS
                                        </td>
                                    </tr>        
                                    <tr>
                                        <td style="width: 50px;">
                                            DSRI
                                        </td>
                                        <td style="text-align: right; width: 80px;">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.dsri.msg + `
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            GMI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.gmi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            AQI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.aqi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            SGI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.sgi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            DEPI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.depi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.depi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.depi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.depi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            SGAI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.sgai.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgai.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgai.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.sgai.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            LVGI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.lvgi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.lvgi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.lvgi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.lvgi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            TATA
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.tata.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.tata.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.tata.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.tata.msg + `
                                        </td>        
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table id="table-manipulacion" class="display table table-bordered table-hover table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <td><h5>DSRI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.dsri.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>CUENTAS POR COBRAR T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cxc['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>CUENTAS POR COBRAR T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cxc['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>GMI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.gmi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>COSTO DE VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>COSTO DE VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>AQI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.aqi.resultado,3,',','.') + `</h5></td>
                                    </tr>        
                                    <tr>
                                        <td>ACTIVOS CORRIENTES T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.acorrientes['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>ACTIVOS CORRIENTES T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.acorrientes['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>ACTIVOS TOTALES T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>ACTIVOS TOTALES T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td><h5>SGI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.sgi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>DEPI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.depi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>DEPRECIACION T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.depreciacion['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>DEPRECIACION T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.depreciacion['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>SGAI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.sgai.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>GASTOS DE EXPLOTACIÓN T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.gastosexplotacion['t'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>GASTOS DE EXPLOTACIÓN T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.gastosexplotacion['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>LVGI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.lvgi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>DEUDAS A LARGO PLAZO T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.deudaslplazo['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>DEUDAS A LARGO PLAZO T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.deudaslplazo['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>PASIVO CORRIENTE T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.pcorriente['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>PASIVO CORRIENTE T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.pcorriente['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>ACTIVOS TOTALES T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>ACTIVOS TOTALES T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>TATA</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.tata.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>UTILIDAD DESPUÉS DE IMPUESTOS</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.utilidaddimpuesto['t'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>EFECTIVO GENERADO EN OPERACIÓN</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.efectivogoperacion['t'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>TOTAL ACTIVOS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t'],3,',','.') + `</td>
                                    </tr>        
                                </tbody>
                            </table>
                            <div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2"></div>
                        </div>
                    </div>
                </div>
            </div>
        
        `);
    },
    tabs: function (data) {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-manipulacion-tab" data-toggle="tab" href="#nav-manipulacion" role="tab" aria-controls="nav-manipulacion" aria-selected="true">Indicadores de Cambio</a>
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-manipulacion" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-manipulacion">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                    <i class="far fa-save"></i>
                                    Guardar PDF
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="MANIPULACION.methods.parametros(this)">
                                    <i class="fas fa-user-secret"></i>
                                    Indicadores de Cambio
                                </span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable">
                            <div class="summernote wysiwyg summernote-addinit" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer1"></div>
                            <div class="alert alert-info" role="alert">
                                <h6 style="margin-bottom: 0px;">` + data.data.mensaje + `<br/><div class="mt-2">La probabilidad de los Indicadores de Cambio es ` + data.data.probabilidad + `%</div></h6>
                            </div>
                            <table id="table-manipulacion" class="display table table-bordered table-hover table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="3"><h5>SCORE DE INDICADORES DE CAMBIO</h5></td>
                                        <td colspan="2" class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.m5ind,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="width: 20%;">
                                            INDICADORES
                                        </td>
                                        <td class="text-right">
                                            FACTOR IDEAL
                                        </td>
                                        <td class="text-right">
                                            VARIACI&Oacute;N (+/-)
                                        </td>
                                        <td class="text-center">
                                            AN&Aacute;LISIS
                                        </td>
                                    </tr>        
                                    <tr>
                                        <td style="width: 50px;">
                                            DSRI
                                        </td>
                                        <td style="text-align: right; width: 80px;">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.dsri.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.dsri.msg + `
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            GMI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.gmi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.gmi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            AQI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.aqi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.aqi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            SGI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.sgi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.sgi.msg + `
                                        </td>        
                                    </tr>
                                    <tr>
                                        <td>
                                            DEPI
                                        </td>
                                        <td style="text-align: right;">
                                            ` + GLOBAL.computed.number_format(data.data.depi.resultado,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.depi.ideal,3,',','.') + `
                                        </td>
                                        <td class="text-right">
                                            ` + GLOBAL.computed.number_format(data.data.depi.aporte,3,',','.') + `
                                        </td>
                                        <td class="text-left">
                                            ` + data.data.analisis.depi.msg + `
                                        </td>        
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table id="table-manipulacion" class="display table table-bordered table-hover table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <td><h5>DSRI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.dsri.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>CUENTAS POR COBRAR T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cxc['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>CUENTAS POR COBRAR T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cxc['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>GMI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.gmi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>COSTO DE VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>COSTO DE VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.cventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>AQI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.aqi.resultado,3,',','.') + `</h5></td>
                                    </tr>        
                                    <tr>
                                        <td>ACTIVOS CORRIENTES T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.acorrientes['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>ACTIVOS CORRIENTES T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.acorrientes['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>ACTIVOS TOTALES T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>ACTIVOS TOTALES T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.actvtotales['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t-1'],3,',','.') + `</td>
                                    </tr>        
                                    <tr>
                                        <td><h5>SGI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.sgi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>VENTAS T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.ventas['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td><h5>DEPI</h5></td>
                                        <td class="text-right"><h5>` + GLOBAL.computed.number_format(data.data.depi.resultado,3,',','.') + `</h5></td>
                                    </tr>
                                    <tr>
                                        <td>DEPRECIACION T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.depreciacion['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>DEPRECIACION T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.depreciacion['t-1'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t'],3,',','.') + `</td>
                                    </tr>
                                    <tr>
                                        <td>INMOVILIZADO MATERIAL T-1</td>
                                        <td class="text-right">` + GLOBAL.computed.number_format(data.data.inmmaterial['t-1'],3,',','.') + `</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2"></div>
                        </div>
                    </div>
                </div>
            </div>`);
    },
    parametrosModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-manipulacion">
                <p>
                    Esta metodología está basada en el modelo de Beneish, mediante la cual se trata de establecer mediante unos indicadores cinco y ocho si se manipularon los estados financieros de una empresa
                    <br/>Se deben elegir dos balances de prueba para aplicar el metodo de comparacion
                </p>    
                <div id="balances" class="scrollTable" style="height: calc(100vh - 350px)">
                    <table id="table-balances" class="table table-hover table-striped"></table>
                </div>
            </form>`);
    },
    parametrosIndicadores: function (data) {
        $('#ventanaModal .modal-body').html(
            `<form id="form-confianza">
                <p>
                    Esta metodología está basada en el modelo de Beneish, mediante la cual se trata de establecer mediante unos indicadores cinco y ocho si se manipularon los estados financieros de una empresa
                    <br/>Se deben elegir dos balances de prueba para aplicar el metodo de comparacion
                </p>    
                <div id="indicadores" class="scrollTable" style="height: calc(100vh - 350px)">
                    <table id="table-indicadores" class="table" style="margin-bottom: 0px;">
                        <tbody>
                            <tr>
                                <th>CUENTAS</th>
                                <th>ANTERIOR</th>
                                <th>RECIENTES</th>
                            <tr>
                            <tr>
                                <td class="align-middle indicadores-col">
                                    ACTIVOS CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('acorrientest1','anocorrientest1','suma_acorrientes_anocorrientest1')" autocomplete="off" class="form-control text-right" id="acorrientest1" name="acorrientest1" value="" maxlength="50"/>
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('acorrientest','anocorrientest','suma_acorrientes_anocorrientest')" autocomplete="off" class="form-control text-right" id="acorrientest" name="acorrientest" value="" maxlength="50"/>
                                </td>        
                            <tr>
                            <tr>
                                <td class="align-middle">
                                    ACTIVOS NO CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('anocorrientest1','acorrientest1','suma_acorrientes_anocorrientest1')" autocomplete="off" class="form-control text-right" id="anocorrientest1" name="anocorrientest1" value="" maxlength="50"/>
                                </td>        
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('anocorrientest','acorrientest','suma_acorrientes_anocorrientest')" autocomplete="off" class="form-control text-right" id="anocorrientest" name="anocorrientest" value="" maxlength="50"/>
                                </td>
                            <tr>
                            <tr class="mb-3">
                                <td class="bg-light">
                                    <span class="text-right"><strong>TOTAL ACTIVOS</strong></span>
                                </td>
                                <td class="text-right bg-light">
                                    <strong id="suma_acorrientes_anocorrientest1">0,00</strong>
                                </td>
                                <td class="text-right bg-light">
                                    <strong id="suma_acorrientes_anocorrientest">0,00</strong>
                                </td>        
                            <tr>
                        </tbody>
                    </table>
                    <table id="table-indicadores" class="table">
                        <tbody>        
                            <tr>
                                <td class="align-middle indicadores-col">
                                    OBLIGACIONES FINANCIERAS CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('obligacionesfct1','obligacionesfnoct1','suma_obligacionesfc_obligacionesfnoct1')" autocomplete="off" class="form-control text-right" id="obligacionesfct1" name="obligacionesfct1" value="" maxlength="50">
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('obligacionesfct','obligacionesfnoct','suma_obligacionesfc_obligacionesfnoct')" autocomplete="off" class="form-control text-right" id="obligacionesfct" name="obligacionesfct" value="" maxlength="50">
                                </td>        
                            <tr>        
                            <tr>
                                <td class="align-middle">
                                    OBLIGACIONES FINANCIERAS NO CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('obligacionesfnoct1','obligacionesfct1','suma_obligacionesfc_obligacionesfnoct1')" autocomplete="off" class="form-control text-right" id="obligacionesfnoct1" name="obligacionesfnoct1" value="" maxlength="50">
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('obligacionesfnoct','obligacionesfct','suma_obligacionesfc_obligacionesfnoct')" autocomplete="off" class="form-control text-right" id="obligacionesfnoct" name="obligacionesfnoct" value="" maxlength="50">
                                </td>        
                            <tr>        
                            <tr>
                                <td class="border-bottom bg-light">
                                    <span class="text-right"><strong>TOTAL OBLIGACIONES FINANCIERAS</strong></span>
                                </td>
                                <td class="text-right border-bottom bg-light">
                                    <strong id="suma_obligacionesfc_obligacionesfnoct1">0,00</strong>
                                </td>
                                <td class="text-right border-bottom bg-light">
                                    <strong id="suma_obligacionesfc_obligacionesfnoct">0,00</strong>
                                </td>        
                            <tr>        
                            <tr>
                                <td class="align-middle">
                                    OTROS PASIVOS CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('otrospasivosct1','pnocorrientest1','suma_otrospasivosc_pnocorrientest1')" autocomplete="off" class="form-control text-right" id="otrospasivosct1" name="otrospasivosct1" value="" maxlength="50">
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('otrospasivosct','pnocorrientest','suma_otrospasivosc_pnocorrientest')" autocomplete="off" class="form-control text-right" id="otrospasivosct" name="otrospasivosct" value="" maxlength="50">
                                </td>        
                            <tr>        
                            <tr>
                                <td class="align-middle">
                                    PASIVOS NO CORRIENTES
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('pnocorrientest1','otrospasivosct1','suma_otrospasivosc_pnocorrientest1')" autocomplete="off" class="form-control text-right" id="pnocorrientest1" name="pnocorrientest1" value="" maxlength="50">
                                </td>
                                <td>
                                    <input type="text" onInput="MANIPULACION.methods.indicadorAjustado('pnocorrientest','otrospasivosct','suma_otrospasivosc_pnocorrientest')" autocomplete="off" class="form-control text-right" id="pnocorrientest" name="pnocorrientest" value="" maxlength="50">
                                </td>        
                            <tr>
                            <tr>
                                <td class="border-bottom bg-light">
                                    <span class="text-right"><strong>TOTAL PASIVOS</strong></span>
                                </td>
                                <td class="text-right border-bottom bg-light">
                                    <strong id="suma_otrospasivosc_pnocorrientest1">0,00</strong>
                                </td>
                                <td class="text-right border-bottom bg-light">
                                    <strong id="suma_otrospasivosc_pnocorrientest">0,00</strong>
                                </td>        
                            <tr>        
                        </tbody>
                    </table>
                </div>
            </form>`);
        
        $.each(data, function (key, value) {
            if($('#' + key + 't').is('input')){
                $('#' + key + 't').val(GLOBAL.computed.number_format(value.t, 2, ',', ''));
                $('#' + key + 't1').val(GLOBAL.computed.number_format(value['t-1'], 2, ',', ''));
                eval('MANIPULACION.' + key + 't = new IMask(document.getElementById(\'' + key + 't\'), MANIPULACION.options);');
                eval('MANIPULACION.' + key + 't1 = new IMask(document.getElementById(\'' + key + 't1\'), MANIPULACION.options);');
            }else if($('#' + key + 't').is('strong')){
                MANIPULACION[key]['t'] = GLOBAL.computed.number_format(value.t, 2, '.', '');
                MANIPULACION[key]['t1'] = GLOBAL.computed.number_format(value['t-1'], 2, '.', '');
                $('#' + key + 't').html(GLOBAL.computed.number_format(value.t, 2, ',', '.'));
                $('#' + key + 't1').html(GLOBAL.computed.number_format(value['t-1'], 2, ',', '.'));
            }
        });
    }    
}