/**
 * Condicion de Cuenta - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-10-16
 */
var CONDICION = CONDICION || {};
CONDICION.table = null;
CONDICION.methods = {
    consulta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Movimiento 
                    para realizar los cálculos de Condición de Cuenta`, 400);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Movimiento 
                    para realizar los cálculos de Condición de Cuenta`, 400);
                return;
            }
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('.modal-title').text('Condiciones de Cuenta');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);
            ventana.find('.btn-primary').text('Guardar y Consultar');
            ventana.find('.btn-primary').attr('onclick','CONDICION.methods.buscar(true)');
            ventana.find('.btn-link').text('Cancelar');
            CONDICION.componets.parametrosModal().then(function () {
                var table = document.getElementById('spreadsheet');
                var data = [];
                CONDICION.table = jexcel(table, {
                    data: data,
                    minDimensions: [3,5],
                    allowInsertRow: true,
                    onbeforeinsertrow: function(){
                        if((table.jexcel.rows.length) == 100)
                            return false;
                        else
                            return true;
                    },
                    columnSorting: false,
                    autoCasting: false,
                    columns: [
                        { type: 'numeric', title: 'Cuenta',     width: 300, align: 'center' },
                        { type: 'numeric', title: 'Porcentaje', width: 100, mask: '#,000', decimal: ',', align: 'right' },
                        { type: 'numeric', title: 'Tolerancia', width: 100, mask: '#,000',   decimal: ',', align: 'right' }
                    ]
                });                
            });
            var datos = CONDICION.computed.cargaDatos();
            datos.then(function (data) {
                CONDICION.computed.cargarCondicion(data);
                if(parseInt(data.status) == 200){
                    ventana.modal('show');
                }
            });            
        }
    },
    buscar: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            $.when({
                ejecucion: GLOBAL.computed.uniqint() 
            }).done(function (x) {
                GLOBAL.pdfcondicionCuenta = x.ejecucion;
                GLOBAL.computed.initializePdf('condicioncuenta');
                var datos = CONDICION.computed.consultar(x.ejecucion);
                datos.then(function (data) {
                    CONDICION.componets.tabs(data.data).then(function () {
                        GLOBAL.computed.maximizar();
                    });
                    ventana.modal('hide');
                });
            });
        }
    }
}
CONDICION.computed = {
    cargarCondicion: function(data) {
        var max = CONDICION.table.getData().length;
        var i = 0;
        $.each(data.data, function (key, value) {
            i = key + 1;
            if(i <= max){
                CONDICION.table.setValue('A' + i, value.cuenta, true);
                CONDICION.table.setValue('B' + i, value.porcentaje, true);
                CONDICION.table.setValue('C' + i, value.tolerancia, true);
            }else{
                CONDICION.table.insertRow([value.cuenta,value.porcentaje,value.tolerancia]);
            }
        });        
    },
    copiarTolerancia: function() {
        var max = CONDICION.table.getData().length;
        var d = 0;
        for (var i = 1; i <= max; i++) {
            d = parseFloat(CONDICION.table.getValue('C' + i).replace(',','.'));
            if(d > 0){
                var tolerancia = CONDICION.table.getValue('C' + i);
                break;
            }
        }
        for (var i = 1; i <= max; i++) {
            CONDICION.table.setValue('C' + i, tolerancia, true);
        }
    },
    extraerCuenta: function () {
        return $.ajax({
            url: '/condicion/v1/extraer',
            type: "POST",
            dataType: 'json',
            data: {
                id: GLOBAL.archivoId
            },            
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    var max = CONDICION.table.getData().length;
                    var i = 0;
                    $.each(data.data, function (key, value) {
                        i = key + 1;
                        if(i <= max){
                            CONDICION.table.setValue('A' + i, value.cta, true);
                        }else{
                            CONDICION.table.insertRow([value.cta,'','']);
                        }
                    });
                }
                GLOBAL.computed.secure();
            }
        });
    },
    cargaDatos: function () {
        return $.ajax({
            url: '/condicion/v1/datos',
            type: "POST",
            dataType: 'json',
            data: {
                id: GLOBAL.archivoId
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
    consultar: function (ejecucion) {
        var data = JSON.stringify(CONDICION.table.getData());
        return $.ajax({
            url: '/condicion/v1/consultar',
            type: "POST",
            dataType: 'json',
            data: {
                id: GLOBAL.archivoId,
                ejecucion: ejecucion,
                data: data
            },
            beforeSend: function (xhr) {
                var ventana = $('#ventanaModal');
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },            
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                GLOBAL.computed.secure();
                return data;
            }
        });
    }
}
CONDICION.componets = {
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
    parametrosModal: function(f) {
        var deferred = $.Deferred();
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford">
                <p class="text-justify">
                    Este informe analiza la correcta determinación de las retenciones en la fuente y los IVA’s de todos los meses,  para ello tomamos el valor del movimiento y lo dividimos entre la base,  dicho resultado lo comparamos con el porcentaje ingresado una sola vez por el usuario y verificamos que el valor este correctamente calculado.<br/>
                    Archivo: <b>` + GLOBAL.archivoNombre + `</b> 
                </p>
                <div class="row">
                    <div class="col-sm-9">
                        <div id="spreadsheet" style="height: calc(100vh - 420px); overflow: auto; padding-right: 10px;"></div>
                    </div>
                    <div class="col-sm-3 text-justify" style="padding-left: 0px">
                        <p>
                            Si usted lo desea puede extraer las cuentas del archivo con Base mayor a cero <a onClick="CONDICION.computed.extraerCuenta();" style="float: right;" href="#!">Extraer Cuentas</a>
                        </p>
                        <p>
                            Copiar tolerancia a todas la cuentas <a onClick="CONDICION.computed.copiarTolerancia();" style="float: right;" href="#!">Copiar Tolerancia</a>
                        </p>        
                    </div>
                </div>
            </form>`);
        deferred.resolve();
        return deferred.promise();        
    },
    tabs: function (data) {
        var deferred = $.Deferred();
        var html = `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-condicioncuenta-tab" data-toggle="tab" href="#nav-condicioncuenta" role="tab" aria-controls="nav-condicioncuenta" aria-selected="true">Condición de Cuenta</a>
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-condicioncuenta" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-condicioncuenta">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                    <i class="far fa-save"></i>
                                    Guardar PDF
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="CONDICION.methods.consulta(this)">
                                    <i class="fas fa-calculator"></i>
                                    Condiciones de Cuenta
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
                            <div class="summernote wysiwyg summernote-addinit" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer1"></div>`;
                var tarjeta = '';
                if(data.filas.length){
                    tarjeta = ((data.exedido == 1) ? 'Los datos han excedido mas de filas permitidas, debe ajustar los valores' : '') + `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Cuenta</th>
                                <th scope="col">Documento</th>
                                <th scope="col">Identificación</th>
                                <th scope="col">Base</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Porcentaje</th>
                                <th scope="col">Esperado</th>
                                <th scope="col">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        $.each(data.filas, function (key, value) {
                            tarjeta = tarjeta + `<tr>
                                <td class="text-right" scope="row">` + value.cta + `</td>
                                <td class="text-right">` + value.doc + `</td>
                                <td class="text-right">` + value.identificacion + `</td>
                                <td class="text-right">` + GLOBAL.computed.number_format(value.base, 3, ',') + `</td>
                                <td class="text-right">` + GLOBAL.computed.number_format(value.valor, 3, ',') + `</td>
                                <td class="text-right">` + GLOBAL.computed.number_format(value.calculo, 3, ',') + `%</td>
                                <td class="text-right">` + GLOBAL.computed.number_format(value.porcentaje, 3, ',') + `%</td>
                                <td class="text-right">` + ((value.diferencia > 0) ? value.condicion : '') + value.diferencia + `%</td>
                            </tr>`;                
                        });
                    tarjeta = tarjeta + `</tbody>
                    </table>`;
                }else{
                    html = html + 
                    `<div class="alert alert-info" role="alert">
                        Enhorabuena, no existe niguna coincidencia!
                    </div>`;
                }
        html = html + tarjeta +  `<div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2"></div></div>
                    </div>
                </div>
            </div>`;
        $('#content').html(html);
        deferred.resolve();
        return deferred.promise();
    },
    tarjeta: function (data, tp) {
        var html = '', otros = {};
        $.each(data['resultados'], function (index, value) {
            html = html + `<div class="card mb-3" style="border: 1px solid rgba(0,0,0,.225)">
                <div class="card-header">
                    <div class="float-left"><b>Buscado</b>: `+ data['buscado'] +`</div>
                    <div class="float-right"><i class="fas fa-exclamation-triangle text-secondary"></i></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            Lista:
                        </div>
                        <div class="col-10">
                            `+ value['lista'] +`
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Nombre:
                        </div>
                        <div class="col-10">
                            `+ value['nombre'] + ((tp == 'n') ? '' : '') +`
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Identificación:
                        </div>
                        <div class="col-10">
                            `+ value['identificacion'] + ((tp == 'i') ? '' : '') +`
                        </div>
                    </div>`;
            otros = GLOBAL.computed.unserialize(value['otros']);
            if($(otros).length){
                $.each(otros, function (index, value) {
                    html = html +
                    `<div class="row">
                        <div class="col-2">
                            ` + index + `:
                        </div>
                        <div class="col-10">
                            `+ value +`
                        </div>
                    </div>`;
                });
            }
            html = html +
                `</div>
            </div>`;
        });
        return html;
    }
}