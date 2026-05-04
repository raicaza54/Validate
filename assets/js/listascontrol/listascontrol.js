/**
 * Listas de Control - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-07-07
 */
var LISTASCONTROL = LISTASCONTROL || {};
LISTASCONTROL.methods = {
    consulta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Movimiento 
                    para realizar la consulta en las Listas de Control`, 400);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Movimiento 
                    para realizar la consulta en las Listas de Control`, 400);
                return;
            }
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            ventana.find('.modal-title').text('Listas de Control');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);
            ventana.find('.btn-primary').text('Consultar');
            ventana.find('.btn-primary').attr('onclick','LISTASCONTROL.methods.buscar(true)');
            ventana.find('.btn-link').text('Cancelar');
            LISTASCONTROL.componets.parametrosModal();
            var datos = LISTASCONTROL.computed.cargaDatos();
            datos.then(function (data) {
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
                GLOBAL.pdflistasControl = x.ejecucion;
                GLOBAL.computed.initializePdf('listascontrol');
                var datos = LISTASCONTROL.computed.consultar(x.ejecucion);
                datos.then(function (data) {
                    LISTASCONTROL.componets.tabs(data['data']).then(function () {
                        GLOBAL.computed.maximizar();
                    });
                    $("body").mLoading('hide');
                });
            });
        }
    }
}
LISTASCONTROL.computed = {
    cargaDatos: function () {
        return $.ajax({
            url: '/listascontrol/v1/datos',
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
        return $.ajax({
            url: '/listascontrol/v1/consultar',
            type: "POST",
            dataType: 'json',
            data: {
                id: GLOBAL.archivoId,
                ejecucion: ejecucion
            },
            beforeSend: function (xhr) {
                var ventana = $('#ventanaModal');
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                ventana.modal('hide');
                $("body").mLoading();
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
LISTASCONTROL.componets = {
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
    parametrosModal: function() {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford">
                <p class="text-justify">
                    Se realizara la conulta en las Listas de Control al archivo <b>` + GLOBAL.archivoNombre + `</b>, estas son bases de datos 
                    nacionales e internacionales que recogen información, reportes y antecedentes de diferentes organismos, 
                    tratándose de personas naturales y jurídicas, que pueden presentar actividades sospechosas, investigaciones, procesos o 
                    condenas por los delitos de Lavado de Activos y Financiación del terrorismo.<br/>
                    Se realizara la consulta en las siguientes listas:
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 80%;">Lista</th>
                            <th scope="col">Actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Declaratoria Proveedores Ficticios</th>
                            <td>25-02-2019</td>
                        </tr>
                    </tbody>
                </table>
            </form>`);        
    },
    tabs: function (data) {
        var deferred = $.Deferred();
        var html = `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-listascontrol-tab" data-toggle="tab" href="#nav-listascontrol" role="tab" aria-controls="nav-listascontrol" aria-selected="true">Listas de Control</a>
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-listascontrol" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-listascontrol">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                    <i class="far fa-save"></i>
                                    Guardar PDF
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="LISTASCONTROL.methods.consulta(this)">
                                    <i class="fas fa-tasks"></i>
                                    Listas de Control
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
                if($(data).length){
                    if(GLOBAL.computed.array_key_exists('nombre', data)){
                        if(data['nombre'].length > 0){
                            $.each(data['nombre'], function (index, value) {
                                tarjeta = tarjeta + LISTASCONTROL.componets.tarjeta(value, 'n');
                            });
                        }
                    }
                    if(GLOBAL.computed.array_key_exists('identificacion', data)){
                        if(data['identificacion'].length > 0){
                            $.each(data['identificacion'], function (index, value) {
                                tarjeta = tarjeta + LISTASCONTROL.componets.tarjeta(value, 'i');
                            });
                        }
                    }
                    html = html + 
                    `<div class="alert alert-danger" role="alert">
                        <b>Advertencia</b>, en este archivo se generaron coincidencias con la lista de control por lo cual debería proceder a una verificación del mismo y tomar las medidas pertinentes para este caso, el siguiente es el dato de la lista de control correspondiente al tercero o terceros que generaron coincidencias
                    </div>`;
                }else{
                    html = html + 
                    `<div class="alert alert-info" role="alert">
                        <b>Enhorabuena</b>, no existe ninguna coincidencia con los terceros reportados en la lista de proveedores ficticios de la DIAN, por lo cual se le recomienda extraer un archivo PDF que certifique que a la fecha del archivo suministrado no se encuentran relaciones con este tipo de terceros.
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