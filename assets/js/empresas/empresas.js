/**
 * Empresas - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-02-13
 */
var EMPRESAS = EMPRESAS || {};
EMPRESAS.methods = {
    listarEmpresas: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            $.when(
                EMPRESAS.componets.tabs()
            ).then(
                EMPRESAS.computed.cargaDatos()
            );
        }
    },
    crearEmpresas: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = EMPRESAS.computed.limites();
            datos.then(function (data) {
                EMPRESAS.componets.formulario('c').then(function () {
                    $('#lim-cant').html(GLOBAL.computed.number_format(data['data']['empresas']['cant'],0));
                    $('#lim-limite').html(GLOBAL.computed.number_format(data['data']['empresas']['limite'],0));
                    $('input[id=empresa-naturaleza_credito1]').attr('checked', true);
                });
            });
        }
    },
    editarEmpresa: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var id = $('input[name="id-empresa"]').val(), tipo;            
            if($.isNumeric(id)){
                $.ajax({
                    url: '/empresas/v1/formulario',
                    type: "POST",
                    dataType: 'json',
                    data:{id: id},
                    complete: function (jqXHR, textStatus) {
                        var data = jqXHR.responseJSON;
                        var status = parseInt(data.status);
                        GLOBAL.computed.secure();
                        if(status != 200){
                            GLOBAL.computed.toast(data.detail, status);
                        }else if(status == 200){
                            EMPRESAS.componets.formulario().then(function () {
                                $.each(data.data.empresa, function(key, value){
                                    if((key.indexOf('created') != -1) || (key.indexOf('update') != -1)){
                                        $('#empresa-' + key).html(value);
                                    }else{
                                        tipo = $('[id^=empresa-' + key + ']').attr('type');
                                        if(tipo == 'radio'){
                                            $('input[id^=empresa-' + key + '][value=' + value + ']').attr('checked', true);
                                        }else{
                                            $('#empresa-' + key).val(value);
                                        }
                                        tipo = '';
                                    }
                                });                                
                            });
                        }
                    }
                });                
            }
        }
    },
    eliminarEmpresa: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var id = $('input[name="id-empresa"]').val();
            if($.isNumeric(id)){
                var ventana = $('#ventanaModal');
                GLOBAL.computed.initializeModal(ventana);
                ventana.find('.modal-title').text('Empresas');
                ventana.find('.btn-primary').show();
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);
                ventana.find('.btn-primary').text('Eliminar');
                ventana.find('.btn-primary').attr('onclick','EMPRESAS.computed.eliminarEmpresa()');
                ventana.find('.btn-link').text('Cancelar');
                EMPRESAS.componets.borrarModal();
                ventana.modal('show');
            }
        }
    },
    desactivarEmpresa: function() {
        GLOBAL.empresaId = null;
        $('#empresaActiva').html('');
        GLOBAL.computed.initialize();
        EXPLORADOR.componets.limpiarExplorador();
        RESULTADOS.componets.limpiarResultados();
    },
    activarEmpresa: function(obj, init = true) {
        var deferred = $.Deferred();
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var id = $('input[name="id-empresa"]').val();
            GLOBAL.empresaId = id;
            if($.isNumeric(id)){
                $.ajax({
                    url: '/empresas/v1/activar',
                    type: "POST",
                    dataType: 'json',
                    data:{id: id},
                    complete: function (jqXHR, textStatus) {
                        var data = jqXHR.responseJSON;
                        var status = parseInt(data.status);
                        if(status != 200){
                            GLOBAL.computed.toast(data.detail, status);
                        }
                        var r = data['data'];
                        $('#empresaActiva').html(
                        `<label><b>` + r['nombre'] + `</b></label>
                        <label>NIT: ` + r['identificacion'] + `</label>
                        <label>Contacto: ` + r['persona'] + `</label>
                        <label>Tlf.: ` + r['persona_tlfs'] + `</label>`);
                        GLOBAL.computed.secure();
                        EXPLORADOR.methods.listarCarpetas(true, id).then(function () {
                            RESULTADOS.methods.listarCarpetas(true, id).then(function () {
                                if(init == true){
                                    GLOBAL.computed.initialize();
                                }
                                EMPRESAS.componets.limpiarContent().then(
                                    deferred.resolve()
                                );
                            });
                        });
                    }
                });
            }else{
                GLOBAL.computed.toast(`Debes seleccionar una empresa`);
                return;                
            }
        }
        return deferred.promise();
    },
    loadEmpresa: function () {
        var deferred = $.Deferred();
        if(ayudame == 0){
            if(!GLOBAL.computed.is_numeric(GLOBAL.empresaId)){
                EMPRESAS.methods.listarEmpresas(true);
            }else{
                $('input[name="id-empresa"]').val(GLOBAL.empresaId);
                EMPRESAS.methods.activarEmpresa(true, false).then(function () {
                    if(parseInt(GLOBAL.archivoId) > 0){
                        ARCHIVOS.methods.listarDatos(true, GLOBAL.archivoId, GLOBAL.exploradorId);
                    }
                    deferred.resolve();
                });
            }
        }else{
            $.when(
                AYUDA.componets.tab()
            ).then(
                GLOBAL.computed.maximizar()
            );
            deferred.resolve(); 
        }
        return deferred.promise();
    }
}
EMPRESAS.computed = {
    limites: function() {
        return $.ajax({
            url: '/empresas/v1/limites',
            type: "GET",
            dataType: 'json',
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
    },    
    eliminarEmpresa: function() {
        var id = $('input[name="id-empresa"]').val();
        if($.isNumeric(id)){
            $.ajax({
                url: '/empresas/v1/eliminar',
                type: "POST",
                dataType: 'json',
                data:{id: id},
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }else if(status == 200){
                        GLOBAL.computed.toast(data.detail, status);
                        EMPRESAS.methods.listarEmpresas(true);
                        var ventana = $('#ventanaModal');
                        ventana.modal('hide');
                    }
                    GLOBAL.computed.secure();
                }
            });
        }
    },
    actualizar: function() {
        var form_data = $('form#form-perfil').serializeArray();
        $.ajax({
            url: '/empresas/v1/actualizar',
            type: "POST",
            dataType: 'json',
            data: {form: form_data},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else if(status == 200){
                    GLOBAL.computed.toast(data.detail, status);
                    EMPRESAS.methods.listarEmpresas(true);
                }
                GLOBAL.computed.secure();
            }
        });
    },
    crear: function() {
        var form_data = $('form#form-perfil').serializeArray();
        $.ajax({
            url: '/empresas/v1/crear',
            type: "POST",
            dataType: 'json',
            data: {form: form_data},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else if(status == 200){
                    GLOBAL.computed.toast(data.detail, status);
                    EMPRESAS.methods.listarEmpresas(true);
                }
                GLOBAL.computed.secure();
            }
        });
    },
    cargaDatos: function () {
        GLOBAL.table = $('#table-empresas').DataTable({
            scrollResize: true,
            scrollY: 100,
            scrollX: true,
            scrollCollapse: true,
            searching: false,
            serverSide: true,
            order: [],
            select: true,
            ajax: {
                url :'/empresas/v1/datos',
                type: "POST"
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id);
            },
            columns: [
                { data: "nombre" },
                { data: "identificacion" },
            ]
        });
        GLOBAL.table.on( 'select', function ( e, dt, type, indexes ) {
            if ( type === 'row' ) {
                var data = GLOBAL.table.rows( indexes ).data().pluck( 'id' );
                $('input[name="id-empresa"]').val(data[0]);
            }
        }).on('dblclick','tr',function(e){
            EMPRESAS.methods.activarEmpresa(true);
        });
    }
}
EMPRESAS.componets = {
    limpiarContent: function() {
        var deferred = $.Deferred();
        $('#content').html(
            `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>            
            </div>`
        );
        GLOBAL.computed.restaurar();
        deferred.resolve();
        return deferred.promise();        
    },
    formulario: function(estado = 'u') {
        var deferred = $.Deferred();
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-empresas-tab" data-toggle="tab" href="#nav-empresas" role="tab" aria-controls="nav-empresas" aria-selected="true">Empresas</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-empresas" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-empresas">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="far fa-file-pdf"></i> Descargar</a>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.dtable)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.dtable)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="perfil-usuario" class="perfil-tab">` +
                                            (estado == 'c' ? `
                                            <b>LIMITES</b>
                                            <hr class="mt-1 mb-2"/>
                                            <div class="form-group row mb-1">
                                                <div class="col-sm-2 mb-2">
                                                    <label class="text-muted">Empresas:</label> <span id="lim-cant">N/A</span>/<span id="lim-limite">N/A</span>
                                                </div>
                                            </div>
                                            `:``)
                                            +`<b>DATOS DE LA EMPRESA</b>
                                            <hr class="mt-1 mb-2"/>
                                            <form class="mt-3 form-data" id="form-perfil">
                                                <input type="hidden" value="" name="empresa-id" id="empresa-id" />
                                                <div class="form-group row">
                                                    <label for="empresa-nombre" class="col-sm-2 col-form-label text-muted">Empresa*</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-nombre" autocomplete="off" class="form-control" id="empresa-nombre" value="" maxlength="250">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-identificacion" class="col-sm-2 col-form-label text-muted">NIT*</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-identificacion" autocomplete="off" class="form-control" id="empresa-identificacion" value="" maxlength="250">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label text-muted" for="empresa-direccion">Dirección*</label>
                                                    <div class="col-sm-10">
                                                        <textarea type="textarea" class="form-control" name="empresa-direccion" id="empresa-direccion" rows="3"></textarea>
                                                    </div>        
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-telefonos" class="col-sm-2 col-form-label text-muted">Teléfono</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-telefonos" autocomplete="off" class="form-control" id="empresa-telefonos" value="" maxlength="150">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-correo" class="col-sm-2 col-form-label text-muted">Correo Electrónico</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-correo" autocomplete="off" class="form-control" id="empresa-correo" value="" maxlength="250">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-correo" class="col-sm-2 col-form-label text-muted"></label>
                                                    <div class="col-sm-10 text-muted">
                                                        LAS CUENTAS DE PASIVOS, INGRESOS Y PATRIMONIO EN SU BALANCE DE PRUEBA EL SALDO FINAL ES DE NATURALEZA CRÉDITO
                                                        <div class="row" style="padding-left: 15px;">
                                                            <div class="form-check mt-2 mb-2 mr-4 float-left">
                                                                <input class="form-check-input" type="radio" name="empresa-naturaleza_credito" id="empresa-naturaleza_credito1" value="si" ` + ( estado == 'c' ? 'checked':'' ) + `>
                                                                <label class="form-check-label" for="empresa-naturaleza_credito1">
                                                                    Si es crédito
                                                                </label>
                                                            </div>
                                                            <div class="form-check mt-2 mb-2 float-left">
                                                                <input class="form-check-input" type="radio" name="empresa-naturaleza_credito" id="empresa-naturaleza_credito2" value="no">
                                                                <label class="form-check-label" for="empresa-naturaleza_credito2">
                                                                    No es crédito
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>        
                                                <b>DATOS DE CONTACTO</b>
                                                <hr class="mt-1 mb-2"/>
                                                <div class="form-group row">
                                                    <label for="empresa-persona" class="col-sm-2 col-form-label text-muted">Nombre*</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-persona" autocomplete="off" class="form-control" id="empresa-persona" value="" maxlength="250">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-persona_tlfs" class="col-sm-2 col-form-label text-muted">Teléfono*</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="empresa-persona_tlfs" autocomplete="off" class="form-control" id="empresa-persona_tlfs" value="" maxlength="250">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label text-muted" for="empresa-persona_direc">Dirección</label>
                                                    <div class="col-sm-10">
                                                        <textarea type="textarea" class="form-control" name="empresa-persona_direc" id="empresa-persona_direc" rows="3"></textarea>
                                                    </div>        
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empresa-persona_correo" class="col-sm-2 col-form-label text-muted">Correo Electrónico</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="empresa-persona_correo" name="empresa-persona_correo" autocomplete="off" class="form-control" id="user_telefono" value="" maxlength="250">
                                                    </div>
                                                </div>        
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label text-muted" for="empresa-observacion">Observación</label>
                                                    <div class="col-sm-10">
                                                        <textarea type="textarea" class="form-control" name="empresa-observacion" id="empresa-observacion" rows="3"></textarea>
                                                    </div>        
                                                </div>` +
                                                (estado == 'u' ? `<b>INFORMACIÓN</b>
                                                <hr class="mt-1 mb-2"/>        
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-2">
                                                        <label class="text-muted">Creado</label>
                                                    </div>    
                                                    <div class="col-sm-10">
                                                        <span id="empresa-created_at">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-2">
                                                        <label class="text-muted">Creador por:</label>
                                                    </div>    
                                                    <div class="col-sm-10">
                                                        <span id="empresa-created_user">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-2">
                                                        <label class="text-muted">Modificado</label>
                                                    </div>    
                                                    <div class="col-sm-10">
                                                        <span id="empresa-update_at">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-2">
                                                        <label class="text-muted">Modificado por:</label>
                                                    </div>    
                                                    <div class="col-sm-10">
                                                        <span id="empresa-update_user">N/A</span>
                                                    </div>
                                                </div>` : ``) +
                                                `<hr class="">` +
                                                (estado == 'u' ? `<button type="button" class="btn btn-primary float-right" onClick="EMPRESAS.computed.actualizar(true)">Actualizar</button>`:
                                                `<button type="button" class="btn btn-primary float-right" onClick="EMPRESAS.computed.crear(true)">Registrar Empresa</button>`) +
                                                `<button type="button" class="btn btn-link float-right" onClick="EMPRESAS.methods.listarEmpresas(true)">Cancelar</button>`
                                            + `</form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);
        deferred.resolve();
        return deferred.promise();
    },
    tabs: function () {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-empresas-tab" data-toggle="tab" href="#nav-empresas" role="tab" aria-controls="nav-empresas" aria-selected="true">Empresas</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-empresas" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-empresas">
                        <ul class="nav position-relative">
                            <li class="nav-item" onclick="EMPRESAS.methods.activarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-check"></i> Seleccionar</a>
                            </li>
                            <li class="nav-item" onclick="EMPRESAS.methods.editarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-pen"></i> Editar</a>
                            </li>
                            <li class="nav-item" onclick="EMPRESAS.methods.eliminarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-trash"></i> Eliminar</a>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.dtable)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.dtable)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable" style="overflow: hidden;">
                            <table id="table-empresas" class="display table table-bordered table-hover table-sm table-striped" style="width: 100%">
                                <thead style="width: 100%;">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>NIT</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`);
    },
    borrarModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-borrar">
                <p>
                    ¿Esta usted seguro de borrar este contenido?
                </p>
            </form>`);
    }
}