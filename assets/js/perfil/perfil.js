/**
 * Perfil - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-05-10
 */
var PERFIL = PERFIL || {};
var $movnat;
var $movdhb;
var $blp;
PERFIL.methods = {
    disco: function(obj) {
        var datos = PERFIL.computed.disco();
        datos.then(function (data) {
            var progress = Math.round((parseInt(data.data.disco.size)*100)/524288000);
            $('div.progress-bar').removeClass('[w-*]').addClass('w-' + progress);
            $('div.progress-bar').attr('aria-valuenow', progress);
            $('span#espacio').html(data.data.disco.sizeFormat + ' / 5 Gb');
        });
    },
    contrato: async function (obj, tab = true) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = PERFIL.computed.datos();
            datos.then(function (data) {
                if(tab == true){ PERFIL.componets.tabs() }
                return data;
            }).then(function (data) {
                $.each(data['data']['contrato'], function(key, value){
                    $('#perfil-' + key).html(value);
                });
                $('#perfil-disco').html(data.data.disco.sizeFormat);
                return data;
            }).then(function (data) {
                var users = data.data.usuario;
                $('[name="idUsuario"]').val(users.id);
                $('[name="idCliente"]').val(users.id_empr);
                $('#email').val(users.user_correo);
                $('#user_correo').html(users.user_correo);
                $('#user_nombre').val(users.user_nombre);
                $('#user_apellido').val(users.user_apellido);
                $('#user_telefono').val(users.user_telefono);
                $('#user_professional_card').val(users.user_professional_card);
                $('#user_last_login').html(users.user_last_login);
                $('#user_ip_address').html(users.user_ip_address);
                $('#empr_nombre').html(users.empr_nombre);
                $('#empr_identificacion').html(users.empr_identificacion);
                $('#empr_direccion').val(users.empr_direccion);
                $('#empr_telefonos').val(users.empr_telefonos);
                $('#empr_correo').val(users.empr_correo);
                $('#empr_color').val(users.empr_color);
                $('#empr_correo').val(users.empr_correo);
                $('input[id^=empr_usarfirma][value=' + users.empr_usarfirma + ']').attr('checked', true);
                $('input[id^=empr_usarlogotipo][value=' + users.empr_usarlogotipo + ']').attr('checked', true);
                $('input[id^=empr_usardemo][value=' + users.empr_usardemo + ']').attr('checked', true);
                $('#img').attr('src', data.data.logo);
                return data;
            }).then(function (data) {
                let materialidad = data.data.columnas.materialidad; 
                if(Object.keys(materialidad).length > 0){
                    $('table-materialidad-msg').addClass('d-none').removeClass('d-block');
                    $('table-materialidad').addClass('d-block').removeClass('d-none');
                    Object.keys(materialidad).forEach(function(key, index) {
                      $('span#' + key).html(GLOBAL.computed.number_format(this[key],2,',','.'));
                    }, materialidad);
                    if(data.data.columnas.materialidad.archivo){
                        $('span#materialidad-archivoNombre').html(data.data.columnas.materialidad.archivo.nombre);
                        $('a#materialidad-archivoNombre-link').attr('onclick','GLOBAL.computed.selectNodeId(\'' + data.data.columnas.materialidad.archivo.id + '\')');                    
                    }                    
                }else{
                    $('div#table-materialidad-msg').addClass('d-block').removeClass('d-none');
                    $('div#table-materialidad').addClass('d-none').removeClass('d-block');
                }
                return data;
            }).then(function (data) {
                if(GLOBAL.computed.isNull(GLOBAL.empresaId) || GLOBAL.empresaId.length == 0){
                    $('#body-columnas').html(`<div class="alert alert-info" style="width: 100%;">Se Debe seleccionar una empresa para comprobar si existen configuraciones por defecto</div>`);
                }else if(GLOBAL.computed.isset(data.data.columnas.empresa) == false){
                    $('#body-columnas').html(`<p class="text-center text-muted" style="margin: 0 auto;">No existen configuraciones almacenadas</p>`);
                }else{
                    $('#body-columnas').html('');
                    if(!$.isEmptyObject(data.data.columnas.columnas_movnat) && $(data.data.columnas.columnas_movnat).length > 0){
                        $('#body-columnas').append(
                            `<div class="col-sm-12 py-2 lists-option">
                                <div class="form-check float-left">
                                    <input class="form-check-input" type="radio" onClick="PERFIL.componets.columnasDefault('movnat')" name="columnas" id="movnat" data-id="` + data.data.columnas.id + `" value="1">
                                    <label class="form-check-label" for="movnat">
                                        ` + data.data.columnas.empresa + `- Tipo de archivo: Movimiento - Formato: Naturaleza y Valor
                                    </label>
                                </div>
                            </div>`
                        );
                        $movnat = data.data.columnas.columnas_movnat;
                    }
                    if(!$.isEmptyObject(data.data.columnas.columnas_movdhb) && $(data.data.columnas.columnas_movdhb).length > 0){
                        $('#body-columnas').append(
                            `<div class="col-sm-12 py-2 lists-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" onClick="PERFIL.componets.columnasDefault('movdhb')" name="columnas" id="movdhb" data-id="` + data.data.columnas.id + `" value="1">
                                    <label class="form-check-label" for="movdhb">
                                        ` + data.data.columnas.empresa + `- Tipo de archivo: Movimiento - Formato: Débitos y Créditos
                                    </label>
                                </div>
                            </div>`
                        );
                        $movdhb = data.data.columnas.columnas_movdhb;
                    }
                    if(!$.isEmptyObject(data.data.columnas.columnas_blp) && $(data.data.columnas.columnas_blp).length > 0){
                        $('#body-columnas').append(
                            `<div class="col-sm-12 py-2 lists-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" onClick="PERFIL.componets.columnasDefault('blp')" name="columnas" id="blp" data-id="` + data.data.columnas.id + `" value="1">
                                    <label class="form-check-label" for="blp">
                                        ` + data.data.columnas.empresa + `- Tipo de archivo: Balance de Prueba
                                    </label>
                                </div>
                            </div>`
                        );
                        $blp = data.data.columnas.columnas_blp;
                    }
                }
                $("body").mLoading('hide');
            });
        }
    },
    perfilMenu: function (tab) {
        $.when().then(function () {
            $('.perfil-tab').removeClass('d-block');
            $('.perfil-tab').addClass('d-none');
        }).then(function () {
            $('#' + tab).addClass('d-block');
        });
    },
    save: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = PERFIL.computed.saveDatos();
            datos.then(function (data) {
               if(data.status == 200){
                   PERFIL.methods.contrato(true);
               }
               return data;
            }).then(function (data) {
                if(data.status == 200){
                    GLOBAL.computed.toast(`Los datos de Perfil fueron actualizados correctamente!`);
                    $("body").mLoading('hide');
                }
            });
        }
    },
    saveEmpresa: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = PERFIL.computed.saveEmpresa();
            datos.then(function (data) {
               if(data.status == 200){
                   PERFIL.methods.contrato(true);
               }
               return data;
            }).then(function (data) {
                if(data.status == 200){
                    GLOBAL.computed.toast(`Los datos de Perfil fueron actualizados correctamente!`);
                    $("body").mLoading('hide');
                }
            });
        }
    },
    saveLimite: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var datos = PERFIL.computed.saveLimite();
            datos.then(function (data) {
               if(data.status == 200){
                   PERFIL.methods.contrato(true);
               }
               return data;
            }).then(function (data) {
                if(data.status == 200){
                    GLOBAL.computed.toast(`Los datos de Perfil fueron actualizados correctamente!`);
                    $("body").mLoading('hide');
                }
            });
        }
    },
    colorDefault: function() {
        $('#empr_color').val('#5799C7');
    }
}
PERFIL.computed = {
    getBase64Image: function (img) {
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL("image/jpg");
        return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    },
    saveDatos: function() {
        var form_data = $('form#form-perfil').serializeArray();
        return $.ajax({
            url: '/perfil/v1/save',
            type: "POST",
            dataType: 'json', 
            data: {form: form_data},
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
    saveEmpresa: function() {
        var formData = new FormData($('form#form-empresa')[0]);
        return $.ajax({
            url: '/perfil/v1/saveEmpresa',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
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
    saveLimite: function() {
        var form_data = $('form#form-limite').serializeArray();
        return $.ajax({
            url: '/perfil/v1/saveLimite',
            type: "POST",
            dataType: 'json', 
            data: {form: form_data},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(GLOBAL.empresaId == 1){
                    EMPRESAS.methods.desactivarEmpresa();
                }
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                GLOBAL.computed.secure();
            }
        });
    },
    datos: function () {
        return $.ajax({
            url: '/perfil/v1/datos',
            type: "GET",
            dataType: 'json',
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
    disco: function () {
        return $.ajax({
            url: '/perfil/v1/disco',
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
    columnasGuardar: function (columna) {
        var form_data = $('form#form-archivo-config').serializeArray();
        form_data.push({ name: "archivoId", value: GLOBAL.archivoId });
        form_data.push({ name: "tipo", value: columna });
        return $.ajax({
            url: '/perfil/v1/saveColumnas',
            type: "POST",
            dataType: 'json',
            data: {
                form: form_data
            },
            beforeSend: function (xhr) {
                $('#btn-guardar-columnas').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                $blp = false; 
                $movdhb = false; 
                $movnat = false;
                PERFIL.methods.contrato(true, false);
                GLOBAL.computed.secure();
                GLOBAL.computed.toast('La configuración ha sido almacenda');
                PERFIL.componets.columnasCancel();
            }
        });        
    }
}
PERFIL.componets = {
    disco: function() {
        return `<div>
            Espacio en Disco <span id='espacio'>0/0</span>
            <div class='progress'>
                <div class='progress-bar w-0' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'></div>
            </div>
            <p style='margin: 10px 0px 0px;'>
                Usted puede solicitar ampliar el espacio seg&uacute;n sus necesidades
            </p>
        </div>`;
    },
    columnasDefault: function(columna) {
        var datos;
        if(columna == 'movdhb'){
            datos = $movdhb;
        }else if(columna == 'movnat'){
            datos = $movnat;
        }else if(columna == 'blp'){
            datos = $blp;
        }
        var x = 0, columnDef;
        $('form#form-archivo-config #table-config-archivo').html('');
        $('form#form-archivo-config #btn-config-archivo').html('');
        $.each(datos.encabezado, function (key, value) {
            x++;
            if(GLOBAL.computed.array_key_exists(key, datos.columnDef)){
                columnDef = datos.columnDef[key];
            }else{
                columnDef = null;
            }
            $('form#form-archivo-config #table-config-archivo').append(
                `<tr>
                    <td style="padding-top: 15px; height: 54px;" class="text-muted">` + x + ". " + value + `</td>
                    <td style="width: 250px;">
                        <div class="form-group">
                            ` + ARCHIVOS.componets.configTipo(datos.tipo, datos.formato, key, columnDef) + `
                        </div>                        
                    </td>
                </tr>`
            );
        });
        $('form#form-archivo-config #btn-config-archivo').append(`
            <hr>
            <button type="button" id="btn-guardar-columnas" class="btn btn-primary float-right" onClick="PERFIL.computed.columnasGuardar('` + columna + `')">Guardar</button>
            <button type="button" class="btn btn-link float-right mr-2" onClick="PERFIL.componets.columnasCancel()">Cancelar</button>`
        );        
    },
    columnasCancel: function() {
        $('[name=columnas]').prop('checked', false);
        $('form#form-archivo-config #table-config-archivo').html('');
        $('form#form-archivo-config #btn-config-archivo').html('');        
    },
    tabs: async function () {
        var deferred = $.Deferred();
        let d = new Map();
        d.set('perfil-contrato', 'd-none');
        d.set('perfil-limites', 'd-none');
        d.set('perfil-empresa', 'd-none');
        d.set('perfil-usuario', 'd-none');
        d.set('perfil-columnas', 'd-none');
        d.set('perfil-terminos', 'd-none');
        d.set('perfil-materialidad', 'd-none');
        if($('.perfil-data .perfil-tab.d-block').attr('id')){
            d.set($('.perfil-data .perfil-tab.d-block').attr('id'), 'd-block');
        }else{
            d.set('perfil-contrato', 'd-block');
        }
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-perfil-tab" data-toggle="tab" href="#nav-perfil" role="tab" aria-controls="nav-perfil" aria-selected="true">Configuraciones</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-perfil" role="tabpanel" aria-labelledby="nav-perfil-tab">
                    <div id="body-perfil">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="far fa-file-pdf"></i> Descargar
                                </a>
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
                                    <div class="perfil-menu col-3">
                                        <b style="padding-left: 10px;">CONSULTA GENERAL</b>
                                        <hr/>
                                        <ul class="mt-2 mb-3">
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-contrato')">Contrato</li>
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-limites')">Limites y Demostración</li>
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-terminos')">Términos y Condiciones</li>
                                        </ul>
                                        <b style="padding-left: 10px;">OPCIONES DE ARCHIVOS</b>
                                        <hr/>
                                        <ul class="mt-2 mb-3">
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-columnas')">Columnas por Defecto</li>
                                        </ul>        
                                        <b style="padding-left: 10px;">OTRAS OPCIONES</b>
                                        <hr/>
                                        <ul class="mt-2">
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-usuario')">Perfil de Usuario</li>
                                        </ul>
                                        <ul class="mt-2">
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-empresa')">Datos de la Empresa</li>
                                        </ul>
                                        <ul class="mt-2">
                                            <li onclick="PERFIL.methods.perfilMenu('perfil-materialidad')">Materialidad</li>
                                        </ul>
                                    </div>
                                    <div class="perfil-data col">
                                        <div id="perfil-contrato" class="perfil-tab ` + d.get('perfil-contrato') + `">
                                            <b>CONTRATO</b>
                                            <hr/>
                                            <form class="mt-3 form-data">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-justify">
                                                            <b>Validate</b>  es una poderosa herramienta para análisis, y auditoría contable, fácil de utilizar, nos ayuda a verificar la calidad 
                                                            e integridad de la información de bases de datos y la información contable de la empresa, nos ayuda en la identificación, análisis y 
                                                            prevención de fraudes. Ayuda en la elaboración de los documentos que debe elaborar el revisor fiscal o auditor, permitiendo que de 
                                                            forma automatizada cumpla con sus funciones de manera más eficaz y precisa.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Número</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-consecutivo">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Fecha Inicio</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-fecha_ini">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Vencimiento</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-fecha_fin">N/A</span> (Vigencia <span id="perfil-vigencia">N/A</span> días)
                                                    </div>
                                                </div>        
                                                <div class="form-group row mb-2">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Servicios Contratados</label>
                                                    </div>    
                                                    <div class="col-sm-9 text-justify">
                                                        <span id="perfil-servicios">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Estado</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span class="badge badge-primary">
                                                            ACTIVO
                                                        </span>
                                                        <!--<span class="badge badge-secondary">
                                                            INACTIVO
                                                        </span>
                                                        <span class="badge badge-danger">
                                                            CANCELADO
                                                        </span>-->
                                                    </div>
                                                </div>
                                                <b>PERSONA CONTACTO</b>
                                                <hr class="mt-1 mb-2"/>        
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Nombre</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-pc_nombre">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Teléfono</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-pc_telefono">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Correo Electrónico</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-pc_email">N/A<span>
                                                    </div>
                                                </div>        
                                                <b>EJECUTIVO COMERCIAL ENCARGADO</b>
                                                <hr class="mt-1 mb-2"/>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Nombre</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-ec_nombre">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Teléfono</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-ec_telefono">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Correo Electrónico</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-ec_email">N/A</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="perfil-limites" class="perfil-tab ` + d.get('perfil-limites') + `">
                                            <b>LIMITES</b>
                                            <hr/>
                                            <form class="mt-3 form-data" id="form-limite">
                                            <input type="hidden" value="" name="idCliente" id="idCliente" />
                                                <!--<div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-justify">

                                                        </p>
                                                    </div>
                                                </div>-->
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Cuentas</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_usuario">N/A</span> <span class="text-muted">usuarios</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Empresas</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_empresas">N/A</span> <span class="text-muted">registradas</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Movimientos</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_movimiento">N/A</span> <span class="text-muted">archivos</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Registros por Movimientos</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-filas_movimiento">N/A</span> <span class="text-muted">filas</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Balances</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_balances">N/A</span> <span class="text-muted">archivos</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Registros por Balances</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-filas_balances">N/A</span> <span class="text-muted">filas</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Cuentas por Pagar</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_cxp">N/A</span> <span class="text-muted">archivos</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Registros en Cuentas por Pagar</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-filas_cxp">N/A</span> <span class="text-muted">filas</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Cuentas por Cobrar</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-cant_cxc">N/A</span> <span class="text-muted">archivos</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Registros en Cuentas por Cobrar</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-filas_cxc">N/A</span> <span class="text-muted">filas</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Disco Duro</label>
                                                    </div>    
                                                    <div class="col-sm-9">
                                                        <span id="perfil-disco">N/A</span>/<span id="perfil-espacio_disco">N/A</span> Gb
                                                    </div>
                                                </div>
                                                <b>EMPRESA DEMOSTRACIÓN</b>
                                                <hr class="mt-1 mb-2"/>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-justify">
                                                            La Empresa Demostración esta pensada como una ayuda inicial donde podrás conocer el funcionamiento de 
                                                            cada uno de los análisis, la misma esta preparada para con algunos ejemplos de fraudes y manipulación de 
                                                            datos para ayudarte a comprender y te vallas familiarizando con la herramienta, debes tener en cuenta que 
                                                            en esta no puedes subir archivos, cambiar nombre de carpetas o archivos o borrar los archivos, unicamente
                                                            es posible activar/inactivar según sea necesario
                                                        </p>
                                                    </div>
                                                </div>                                                
                                                <div class="form-group row">
                                                    <label for="empr_demo" class="col-sm-3 col-form-label text-muted">Empresa Demo</label>
                                                    <div class="col-sm-9">
                                                        <div class="row" style="padding-left: 15px;">
                                                            <div class="form-check mt-2 mb-2 mr-4 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usardemo" id="empr_usardemo1" value="no">
                                                                <label class="form-check-label" for="empr_usardemo1">
                                                                    Inactiva
                                                                </label>
                                                            </div>
                                                            <div class="form-check mt-2 mb-2 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usardemo" id="empr_usardemo2" value="si">
                                                                <label class="form-check-label" for="empr_usardemo2">
                                                                    Activa
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="">
                                                <button type="button" class="btn btn-primary float-right" onClick="PERFIL.methods.saveLimite(true)">Actualizar</button>        
                                                <button type="button" class="btn btn-link float-right mr-2" onClick="PERFIL.methods.contrato(true)">Cancelar</button>        
                                            </form>
                                        </div>
                                        <div id="perfil-terminos" class="perfil-tab ` + d.get('perfil-terminos') + `">
                                            <div style="background-color: #fff;">
                                                <p class="western" align="center" style="margin-left: 1.54cm; margin-right: 1.62cm; margin-bottom: 0.60cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt"><b>CONTRATO
                                                        DE TÉRMINOS Y CONDICIONES PARA EL USO DE LA APLICACIÓN Y LOS
                                                        SERVICIOS DE VALIDATE </b></font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es"><b>SYSTEM</b></span></font></font></p>

                                                <p class="western" align="justify" style="margin-bottom: 0.28cm"><a name="_GoBack"></a><a name="_Hlk18354004"></a>
                                                    <font size="3" style="font-size: 11pt">Los términos expresados en
                                                    este contrato constituyen las condiciones de uso y</font><font size="3" style="font-size: 11pt"><span lang="es">
                                                    </span></font><font size="3" style="font-size: 11pt">privacidad para
                                                    todos los servicios prestados en la actualidad y los añadidos en el
                                                    futuro, por G</font><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font><font size="3" style="font-size: 11pt">
                                                    Informatic Solutions S.A.S en su sitio web www.</font><font size="3" style="font-size: 11pt"><span lang="es">v</span></font><font size="3" style="font-size: 11pt">alidate.com.co
                                                    y su</font><font size="3" style="font-size: 11pt"><span lang="es">
                                                    </span></font><font size="3" style="font-size: 11pt">aplicación</font><font size="3" style="font-size: 11pt"><span lang="es">
                                                        web v</span></font><font size="3" style="font-size: 11pt">alidate.geoiss.com.
                                                    El usuario acepta, al momento de empezar a utilizar el servicio,
                                                    respetar todas las condiciones impuestas por este contrato.</font></p>
                                                <p class="western" style="margin-right: 12.51cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt"><b>Glosario
                                                        Utilizado </b></font></font>
                                                </p>
                                                <p class="western" style="margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Las
                                                    definiciones a continuación tendrán el siguiente significado en
                                                    este contrato: </font></font>
                                                </p>
                                                <ul>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.79cm; margin-bottom: 0cm; orphans: 0; widows: 0"><a name="__DdeLink__583_307950072"></a>
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “contrato” o “acuerdo” hace referencia a este
                                                            contrato y a sus Términos y Condiciones. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “leyes aplicables” hace referencia a las leyes que se
                                                            aplican en Colombia</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">para
                                                            este tipo de contratos. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0"><a name="_Hlk18354057"></a>
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “sitio” ó “sitio web” hace referencia al sitio
                                                            donde se prestan todos los servicios que G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S ofrece, sin tener en cuenta posibles
                                                            terceros relacionados con G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S.</font></font></p>
                                                    </li>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.18cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “servicio” o “servicios” hace referencia a la
                                                            aplicación que ofrece G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S en su sitio web y que opera desde el
                                                            dominio </font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">v</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">alidate.geoiss.com.
                                                            </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            Término “Validate”, “Validate</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                                System</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">”,
                                                            “nosotros”, “nuestro(a)” hace referencia a G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S y todos sus asociados. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “geoiss” hace referencia a la plataforma web usada por
                                                            G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S para prestar su servicio, incluido el
                                                            software que usa la plataforma y todos sus contenidos. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" style="margin-top: 0.18cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “Aplicación” hace referencia al software que ofrece
                                                            G</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">EO</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">
                                                            Informatic Solutions S.A.S como servicio en su sitio
                                                            </font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">v</span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">alidate.geoiss.com</font></font></p>
                                                    </li>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “titular de la cuenta” hace referencia a la persona a
                                                            nombre de quien está la tarjeta de crédito que se usó para
                                                            registrar la cuenta en Validate. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" align="justify" style="margin-top: 0.18cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “Usuario” hace referencia a la persona</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">/</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">empleado</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">/</font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">colaborador
                                                            de nuestro cliente que utiliza y realiza su trabajo de </font></font><font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es">auditoría
                                                            </span></font></font><font color="#222222"><font size="3" style="font-size: 11pt">en
                                                            la aplicación web Validate, sea o no “titular de la cuenta”. </font></font>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="western" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                            <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                            término “Plan” hace referencia a las condiciones de uso que
                                                            tienen un usuario según el pago que haya efectuado. </font></font>
                                                        </p>
                                                    </li>
                                                </ul>
                                                <p class="western" style="margin-top: 0.19cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <br/>

                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.14cm; margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt"><b>Términos
                                                        y Condiciones </b></font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 13.96cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Aceptación
                                                    </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario de este sitio, acepta por simple causal de uso del sistema
                                                    Validate lo dispuesto en este contrato y en sus Términos y
                                                    Condiciones, susceptible de cambio sin previo aviso por parte de GEO
                                                    Informatic Solutions S.A.S. Si el usuario representa una
                                                    organización, está dando por entendido que la organización acepta
                                                    ceñirse a este contrato y que tiene las facultades para actuar en
                                                    nombre de aquella y por lo tanto obligarla frente a Validate y
                                                    aceptar las obligaciones establecidas en el presente contrato. El
                                                    usuario que no esté de acuerdo con esto, no podrá hacer uso de los
                                                    servicios prestados por Validate.</font></font></p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 8.53cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Capacidad
                                                    de Celebración de Contratos </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Según
                                                    la ley de Colombia, el usuario que acepte este acuerdo de términos y
                                                    condiciones, debe ser legalmente apto para celebrar un contrato según
                                                    lo permita su autonomía de la voluntad y las leyes que le sean
                                                    aplicables. Refiérase entonces, el que quiera celebrar este contrato
                                                    a la teoría general de celebración de contratos de la ley que
                                                    aplica en su país. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    da por entendido que aquel usuario que acepte este acuerdo de
                                                    términos y condiciones conoce previamente si está o no en capacidad
                                                    de celebrar contratos a nombre de la persona que se determine como
                                                    Usuario. Quienes sean considerados incapaces absolutos o relativos o
                                                    parciales deberán tener autorización de sus representantes legales
                                                    para celebrar este contrato, y serán estos últimos considerados
                                                    responsables de cualquier conducta de sus apoderados. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 10.09cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Registro
                                                    de Cuentas y Usuarios  </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    presta un servicio de software que se accede a través de su sitio
                                                    web <?= anchor('https://validate.geoiss.com','','target="_blank"')?> Los usuarios que accedan a este servicio
                                                    deberán registrar una cuenta y brindar la información solicitada en
                                                    los formularios que se habilitan a la hora de registrar una cuenta.
                                                    Validate da por entendido que cualquier información ingresada en
                                                    estos formularios es hecha bajo juramento y por lo tanto exonera a
                                                    Validate de poseer información falsa sobre cualquier usuario. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    permite el ingreso de la cantidad de usuarios que el usuario-cliente
                                                    haya comprado según el plan al momento de registrar su cuenta.
                                                    Validate no permite, bajo ninguna circunstancia que un solo usuario
                                                    sea utilizado por varias personas o que estos usuarios sean distintos
                                                    de aquellos que el titular de la cuenta haya decidido habilitar como
                                                    tales para el uso de la plataforma y estos no podrán ser
                                                    reemplazados por otras personas que conozcan las credenciales para
                                                    ingresar a la plataforma. De suceder cualquiera de las situaciones
                                                    descritas, Validate no se hace responsable por la pérdida de
                                                    información o el uso indebido, pernicioso, o malintencionado de la
                                                    información de la cuenta en contra del titular de la misma, ni
                                                    frente al usuario, ni frente a terceros, tampoco por sanciones
                                                    impuestas y responsabilidad ante entidades estatales. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.54cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Descripción
                                                    del Servicio </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    presta el servicio de su aplicación web de acceso por usuarios, es
                                                    decir para que una persona pueda ingresar y usar la aplicación de
                                                    Validate tiene que comprar previamente un usuario. Validate podrá
                                                    modificar cualquiera de sus planes para usuarios nuevos, pero el
                                                    usuario que lo adquirió antes podrá seguir usándolo
                                                    indefinidamente siempre y cuando no realice cambios posteriores en su
                                                    plan. El usuario adquiere un derecho de uso no-exclusivo, mundial y
                                                    temporal e intransferible para usar el sistema Validate según las
                                                    condiciones del plan que haya elegido al momento de pagar por el
                                                    servicio y acepta las condiciones al ser un usuario pero en ningún
                                                    momento adquiere propiedad sobre la plataforma. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.49cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Los
                                                    derechos de autor sobre las obras de software que componen la
                                                    plataforma y los Servicios serán de titularidad de Validate y bajo
                                                    ninguna interpretación de éstos términos de servicio se entenderán
                                                    transferidos al usuario. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    servicio de Validate se inicia al momento de registrar una cuenta en
                                                    el sitio web de Validate, tras haber aceptado las condiciones
                                                    expresadas en este contrato. El servicio consiste en el uso del
                                                    software Validate disponible en <?= anchor('https://validate.geoiss.com','','target="_blank"')?> y todos los
                                                    servicios disponibles en el sitio web <?= anchor('https://validate.com.co','','target="_blank"')?>. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario se hace conocedor de los servicios por los que está pagando
                                                    a la hora de usar alguno de los planes que ofrece Validate. Validate
                                                    no se hará responsable en ningún caso por los errores cometidos por
                                                    el usuario a la hora de elegir su plan, así como tampoco al momento
                                                    de digitar o ingresar su información tanto personal como de la
                                                    operación y marcha de su actividad empresarial ni tampoco de la
                                                    clasificación que realice de la misma lo que afectará
                                                    irremediablemente los resultados arrojados por el Software. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    garantiza al Usuario el acceso al sitio web <?= anchor('https://validate.geoiss.com','','target="_blank"')?>,
                                                    <?= anchor('https://validate.com.co','','target="_blank"')?>  para ver la información allí disponible mientras
                                                    tenga conexión a Internet bajo condiciones normales, sin embargo el
                                                    usuario acepta que existan circunstancias técnicas por las que ésta
                                                    información puede llegar a estar inaccesible de manera temporal y
                                                    por lo tanto exonera a Validate de cualquier tipo de responsabilidad
                                                    por este hecho, bajo el entendido de que esto puede obedecer a
                                                    limitaciones inherentes al estado de la tecnología en la actualidad.</font></font></p>
                                                <p class="western" align="justify" style="margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <br/>

                                                </p>
                                                <p class="western" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Alcance
                                                    y Niveles de Servicio </font></font>
                                                </p>
                                                <p class="western" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <br/>
                                                    <br/>

                                                </p>
                                                <p class="western" align="justify" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En
                                                    condiciones normales, la plataforma de Validate tiene un “down
                                                    time” de aproximadamente 3%, de modo que la plataforma se encuentra
                                                    disponible el 97% del tiempo. Validate adelantará las gestiones que
                                                    a su juicio estime conducentes, para que la aplicación esté
                                                    disponible para el usuario, pero no garantiza lo anterior, por cuanto
                                                    pueden existir eventos como daños en las comunicaciones, actos de
                                                    terceros, mantenimiento o reestructuración de la aplicación, entre
                                                    otros, que escapen al control y responsabilidad de Validate, pero
                                                    garantiza el soporte técnico bajo los siguiente niveles de servicio
                                                    (SLA): </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <br/>
                                                    <br/>

                                                </p>
                                                <p class="western" align="center" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt"><span lang="es"><b>Niveles
                                                            de Servicio</b></span></font></font></p>
                                                <table width="100%" cellpadding="8" cellspacing="0">
                                                    <col width="35">
                                                    <col width="71">
                                                    <col width="228">
                                                    <col width="112">
                                                    <col width="111">
                                                    <tr>
                                                        <td width="35" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es"><b>NIVEL</b></span></font></font></p>
                                                        </td>
                                                        <td width="71" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es"><b>CRITICIDAD</b></span></font></font></p>
                                                        </td>
                                                        <td width="228" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es"><b>DESCRIPCIÓN</b></span></font></font></p>
                                                        </td>
                                                        <td width="112" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es"><b>TIEMPO
                                                                        DE ATENCIÓN</b></span></font></font></p>
                                                        </td>
                                                        <td width="111" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es"><b>TIEMPO
                                                                        MÁXIMO DE RESOLUCIÓN</b></span></font></font></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="35" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">CN1</span></font></font></p>
                                                        </td>
                                                        <td width="71" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Baja</span></font></font></p>
                                                        </td>
                                                        <td width="228" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Preguntas
                                                                    o consultas puntuales sobre el uso de la plataforma</span></font></font></p>
                                                        </td>
                                                        <td width="112" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">De
                                                                    01 a 12 horas hábiles</span></font></font></p>
                                                        </td>
                                                        <td width="111" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">48
                                                                    horas hábiles </span></font></font>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="35" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">CN2</span></font></font></p>
                                                        </td>
                                                        <td width="71" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Media</span></font></font></p>
                                                        </td>
                                                        <td width="228" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Consultas
                                                                    o requerimientos por fallas o supuestas fallas de la plataforma</span></font></font></p>
                                                        </td>
                                                        <td width="112" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">De
                                                                    01 a 08 horas hábiles</span></font></font></p>
                                                        </td>
                                                        <td width="111" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">48
                                                                    horas hábiles </span></font></font>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="35" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">CN3</span></font></font></p>
                                                        </td>
                                                        <td width="71" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Alta</span></font></font></p>
                                                        </td>
                                                        <td width="228" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Reporte
                                                                    de fallas de software o funcionalidades especificas</span></font></font></p>
                                                        </td>
                                                        <td width="112" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">De
                                                                    01 a 04 horas hábiles</span></font></font></p>
                                                        </td>
                                                        <td width="111" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">24horas
                                                                    hábiles </span></font></font>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="35" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">CN4</span></font></font></p>
                                                        </td>
                                                        <td width="71" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Critica</span></font></font></p>
                                                        </td>
                                                        <td width="228" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Situaciones
                                                                    criticas o no disponibilidad del servicio. Desbloqueo temporal,
                                                                    para dar solución definitiva</span></font></font></p>
                                                        </td>
                                                        <td width="112" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">De
                                                                    01 a 04 horas con prioridad</span></font></font></p>
                                                        </td>
                                                        <td width="111" style="border: 1px solid #000000; padding-top: 0.2cm; padding-bottom: 0.2cm; padding-left: 0.21cm; padding-right: 0.2cm">
                                                            <p class="western" align="justify" style="orphans: 0; widows: 0"><font color="#222222"><font size="1" style="font-size: 8pt"><span lang="es">Depende
                                                                    del requerimiento</span></font></font></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p class="western" align="justify" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <br/>
                                                    <br/>

                                                </p>
                                                <p class="western" align="justify" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">No
                                                    obstante lo anterior, Validate garantiza a los usuarios que la
                                                    aplicación estará disponible en circunstancias normales, según lo
                                                    anunciado, desde cualquier lugar del mundo que permita una conexión
                                                    a internet, siempre y cuando el usuario recuerde las credenciales de
                                                    su cuenta y las condiciones de prestación del servicio de conexión
                                                    a Internet por parte de cada proveedor, lo permitan. De manera tal
                                                    que si no se pudiera acceder por el tipo de conexión o por el
                                                    deficiente servicio del mencionado proveedor, ello no implica en
                                                    ningún momento incumplimiento de la prestación del servicio
                                                    prestado por parte de Validate. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En
                                                    cuanto al mantenimiento y reestructuración de la aplicación,
                                                    Validate se reserva el derecho de llevar a cabo las gestiones
                                                    necesarias, sin previo aviso a los usuarios, pero procurará que se
                                                    les brinde un aviso con la antelación que a su juicio considere
                                                    conveniente o prudente para evitar incomodidades o eventuales
                                                    perjuicios en el procesamiento de datos o de información. </font></font>
                                                </p>
                                                <p class="western" style="margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Manejo
                                                    de Backups  </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 13cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Medios
                                                    de Pago </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 0.48cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario podrá elegir el plan de servicio anual que desee y realizar
                                                    el primer pago, el próximo cobro se realizará el mismo día del mes
                                                    en que se inscribió. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El pago
                                                    de los derechos de uso del software Validate es 100% anticipado por
                                                    período anual y en pesos colombianos. El usuario acepta que se
                                                    realicen cobros recurrentes de acuerdo al periodo establecido. Además
                                                    se compromete a notificar a Validate en caso tal de que no quiera que
                                                    se genere el siguiente cobro, mínimo con (quince) 15 días hábiles
                                                    antes de su próxima fecha de cobro. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.48cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En caso
                                                    de que el usuario no notifique a Validate, con anticipación sobre su
                                                    deseo de no generar el cobro recurrente, este tendrá 5 días hábiles
                                                    contados a partir de la fecha en que se realiza el cobro para pedir
                                                    la devolución del dinero conforme el derecho de retracto previsto en
                                                    el Estatuto del Consumidor. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario afirma bajo la gravedad de juramento, que no está incluido
                                                    en la Lista Clinton y que sus ingresos no tienen relación con ningún
                                                    tipo de actos de aquellos que la legislación colombiana e
                                                    internacional determinan para el lavado de activos, narcotráfico,
                                                    terrorismo ni otro delito y que son adquiridos de manera lícita. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.33cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Información
                                                    de la Cuenta </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    es un aplicativo Web, el cual proporciona un sitio donde se alojan
                                                    datos que el Cliente administra bajo su responsabilidad. Validate
                                                    vela por mantener la información de los usuarios, segura y toma las
                                                    precauciones a su juicio necesarias para ello, más no se
                                                    responsabiliza por actos mal intencionados de usuarios y las
                                                    consecuencias de ello frente al cliente, usuarios o frente a
                                                    terceros. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    pone en conocimiento de sus usuarios, que la información que
                                                    suministre se albergará en servidores de terceros, específicamente
                                                    Amazon Web Services, que cumplen con los más altos estándares de
                                                    seguridad e idoneidad. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    tampoco se hace responsable por el tipo de información ingresada por
                                                    cada usuario en su cuenta ni por los resultados inadecuados si la
                                                    misma se ingresó de manera inadecuada de acuerdo con los parámetros
                                                    contables, fiscales, de auditoría, o las normas internacionales de
                                                    auditoria aplicables a la materia en cada caso. Se da a entender que
                                                    el usuario, al usar los servicios de Validate, hará un uso sano y
                                                    legal de todas las herramientas que se ponen a su disposición y esto
                                                    exonera a Validate de cualquier uso indebido de su información por
                                                    parte de cualquier usuario, entendiéndose por ello el usar
                                                    información para evadir obligaciones tributarias, emitir conceptos
                                                    que no obedezcan a la realidad de un tercero, cometer actos ilícitos,
                                                    entre otras. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.49cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    no estará obligada a velar por la legalidad del contenido e
                                                    información que los usuarios alberguen en su cuenta a través de los
                                                    Servicios prestados, sin embargo podrá tomar los correctivos en
                                                    contra de información ilegal, cuando lo considere pertinente. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario se obliga a la hora de usar cualquiera de los servicios de
                                                    Validate a (i) no causar daño físico, moral o mental a otros
                                                    usuarios del servicio (ii) no utilizar el servicio con fines
                                                    maliciosos o de mala voluntad, ni para beneficiarse en perjuicio de
                                                    terceros y mucho menos del Estado entendiendo por éste, aquel que de
                                                    acuerdo con la normatividad que le sea aplicable, sea quien deba
                                                    recibir dinero por concepto de impuestos bajo cualquier denominación
                                                    por el resultado de la operación mercantil del usuario o un tercero.
                                                    (iii) no usar el servicio con fines criminales o ilegales ni para
                                                    sacar provecho o beneficio ilícito para sí o para terceros, bien
                                                    sea remunerado o no, (iv) no publicar información que vulnere
                                                    derechos de terceros, tales como derechos de propiedad intelectual,
                                                    secretos industriales o cualquier otro que sea de propiedad de
                                                    terceros y respecto de los cuales no se encuentre autorizado (v)
                                                    publicar información información sensible que ya no es vigente o
                                                    que pueda inducir a error a terceros o al Estado. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    usuario de Validate manifiesta ser dueño de la información que
                                                    ingresa en el sistema y bajo ninguna circunstancia esta información
                                                    pasará a ser propiedad de Geo Informatic Solutions S.A.S, y de
                                                    manera inversa, esta última sociedad es la única dueña de la
                                                    plataforma sobre la cual el usuario ingresa la información , sin que
                                                    respecto de ésta se considera surtida transferencia alguna en razón
                                                    de éste contrato. Si el usuario da por terminado el contrato tendrá
                                                    la información a su alcance, pero en ningún momento Validate se
                                                    obliga a entregar la misma en formato alguno ni a llevar a cabo
                                                    ningún tipo de proceso de migración, ni mucho menos a efectuar un
                                                    desarrollo para que la información pueda ser analizada, ingresada o
                                                    digitalizada en cualquier otro software, pues sólo se ingresan
                                                    datos, para su consulta y procesamiento en aras de su funcionamiento
                                                    y uso. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Adicional
                                                    a lo anterior, Validate no se responsabiliza por el mal
                                                    diligenciamiento de un formulario o por la información que
                                                    erróneamente suministre el USUARIO al momento de realizar un trámite
                                                    o de ingresar la información al software. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.99cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Política
                                                    de Privacidad </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Funciones
                                                    publicitarias de Google Analytics, AdRoll, Facebook o aplicaciones
                                                    similares implementadas en Validate. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.48cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    utiliza las audiencias de remarketing de Google Analytics o similares
                                                    para su uso en Google AdWords, DoubleClick Bid Manager, Facebook Ads,
                                                    LinkeidIn Ads, AdRoll o plataformas de publicidad similares, esto con
                                                    el fin de publicar campañas de publicidad y remarketing dirigidas a
                                                    sus usuarios. El usuario acepta que su información sea tratada para
                                                    efectos de Big Data y por lo tanto permite que Validate utilice estas
                                                    herramientas con fines estadísticos. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 10.82cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Modo de
                                                    uso de las cookies </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.48cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate,
                                                    Mediante la instalación de cookies e identificadores propios y de
                                                    terceros y a través de herramientas para el análisis de uso de
                                                    cuenta, podrá realizar actividades de seguimiento a los usuarios que
                                                    utilizan su aplicación. Con estas herramientas, Validate podrá
                                                    recopilar información de cada usuario para el análisis de
                                                    estadísticas. Estas herramientas podrán realizar seguimiento de la
                                                    configuración del usuario y hacen que su experiencia en la
                                                    aplicación sea más práctica, reconociendo y recordando sus
                                                    preferencias y ajustes. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En esta
                                                    medida, Validate podrá recopilar datos de uso, como la duración de
                                                    uso, o datos demográficos como el origen, el sexo y la edad.
                                                    Validate usa esta información para fines analíticos. El usuario de
                                                    Validate podrá deshabilitar estas herramientas. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Las
                                                    actividades de seguimiento adelantadas por Validate también podrán
                                                    realizarse dentro de la aplicación, para análisis interno, ejemplo,
                                                    pero no exclusivamente para determinar tendencias y participación en
                                                    la aplicación, tiempo de uso, preferencia, entre otros. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.55cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Política
                                                    Comunicacional </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Al
                                                    registrar una cuenta con Validate el titular de la cuenta debe
                                                    introducir un email y un teléfono de contacto, así como registrar
                                                    todos aquellos datos que el software le solicite a fin de determinar
                                                    no solo su identificación, sino aquello que le permita al software
                                                    procesar la información que el mismo usuario diligencie o ingrese.
                                                    Al hacer esto, acepta recibir todos los correos y llamadas con
                                                    información sobre el uso de Validate, información promocional u
                                                    otro tipo de información procesada o enviada por Validate. No
                                                    obstante lo anterior, Validate no podrá utilizar en beneficio suyo o
                                                    de terceros, la información que haya ingresado el usuario. El
                                                    usuario podrá solicitar que sus datos dejen de ser compartidos en
                                                    cualquier momento y por medio de los canales establecidos y/o a la
                                                    siguiente dirección de correo electrónico:
                                                    <?= mailto('comercial@validate.com.co')?></font></font></p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Esta
                                                    cláusula se interpretará sin detrimento de la autorización de uso
                                                    de información a que se refiere el título “Política de
                                                    Privacidad”. La autorización a que se refiere ésta cláusula
                                                    podrá ser revocada por el usuario en cualquier tiempo escribiendo a
                                                    la siguiente dirección de correo electrónico:
                                                    <?= mailto('comercial@validate.com.co')?></font></font></p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Adicionalmente
                                                    los usuarios tendrán habilitada la posibilidad de comunicarse con el
                                                    equipo de Validate en caso de que tengan preguntas o dudas por
                                                    resolver, preguntas generales sobre su cuenta o sobre el software
                                                    como tal. Para los casos anteriores, deberán escribir a
                                                    <?= mailto('comercial@validate.com.co')?>, o utilizar la pestaña de ayuda disponible
                                                    en la aplicación. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <br/>

                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.62cm; margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Seguridad
                                                    de la Cuenta </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    vela por la seguridad de la contraseña que provea el titular de
                                                    cuenta y los usuarios a la hora de registrar una cuenta con Validate
                                                    y garantiza que tomará las medidas que se encuentren a su alcance
                                                    para que esta contraseña no sea vista por terceros, mas no puede
                                                    arrogarse la responsabilidad de garantizar su confidencialidad. Por
                                                    otro lado Validate no se responsabiliza por el mal uso de la
                                                    contraseña por parte del usuario ni por el uso de contraseñas que
                                                    sean fáciles de descifrar, asumiendo que siempre que se acceda al
                                                    sistema, lo hace el usuario directamente. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 12.57cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Operación
                                                    de Sitio </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    llevará a cabo las gestiones que a su juicio estime conducentes,
                                                    tendientes a que los sitios webs asociados siempre estén disponibles
                                                    para el usuario, pero no garantiza lo anterior, tanto por los daños
                                                    en las comunicaciones, por actos de terceros, mantenimiento o
                                                    reestructuración de los sitios u otro tipo de actos que se escapen
                                                    del alcance y responsabilidad directa de Validate. No obstante lo
                                                    anterior, Validate garantiza a los usuarios que pagan por el
                                                    servicio, que el software estará disponible en <?= anchor('https://validate.geoiss.com','','target="_blank"')?>
                                                    desde cualquier lugar del mundo que permita una conexión a internet,
                                                    siempre y cuando el usuario recuerde las credenciales de su cuenta y
                                                    las condiciones de prestación del servicio de conexión a Internet
                                                    por parte de cada proveedor, lo permitan, lo cual escapa de las
                                                    obligaciones de Validate, de manera tal que si no se pudiera acceder
                                                    por el tipo de conexión y las características que le sean propias,
                                                    por fallas en la comunicación o por el deficiente servicio del
                                                    mencionado proveedor, ello no implica en ningún momento
                                                    incumplimiento de la prestación del servicio contratado por parte de
                                                    Validate. En cuanto al mantenimiento y reestructuración del sitio,
                                                    Validate se reserva el derecho de hacerlo sin previo aviso a los
                                                    usuarios, pero procurará que se les brinde un aviso con la
                                                    antelación que a su juicio considere conveniente o prudente para
                                                    evitar incomodidades o eventuales perjuicios en el procesamiento de
                                                    datos o de información. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 13.71cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Limitaciones
                                                    </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">EL
                                                    CLIENTE no podrá aplicar técnicas de ingeniería inversa,
                                                    descompilar o desensamblar el software, ni realizar cualquier otra
                                                    operación que tienda a descubrir el código fuente. Además queda
                                                    prohibida la separación de los componentes. Validate autoriza el uso
                                                    del software como un producto único. Las partes que lo componen no
                                                    se podrán separar para utilizarlas, ni hacer uso de ellas por
                                                    separado. El usuario comprende que faltar a lo dispuesto en ésta
                                                    cláusula constituye un delito de acuerdo al artículo 272 del Código
                                                    Penal Colombiano. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <br/>

                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 8.8cm; margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Interrupción
                                                    y Terminación del Servicio </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.48cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    se reserva el derecho de terminar el servicio en cualquier momento,
                                                    tanto de manera permanente como temporal, para aquellos casos en los
                                                    que se deban realizar pagos sucesivos. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    podrá terminar de manera unilateral la cuenta de un usuario en los
                                                    siguientes escenarios: (i) En caso de que el USUARIO utilice los
                                                    servicios prestados por Validate para fines contrarios a la ley,
                                                    especialmente aquellos que contraríen derechos de propiedad
                                                    intelectual de terceros y sobre de todos de Validate y de otros
                                                    usuarios; (ii) En caso de que Validate encuentre que el USUARIO está
                                                    haciendo uso de su cuenta para la transmisión de programas malignos
                                                    como virus, malwares, spywares, troyanos o similares, que puedan
                                                    comprometer el debido funcionamiento de la plataforma de Validate o
                                                    que perjudiquen a terceros; (iii) Cuando existan elementos que
                                                    permitan inferir a Validate que el USUARIO no cuenta con la edad
                                                    mínima para contratar los Servicios, en los términos del artículo
                                                    segundo de estas Condiciones. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    se reserva el derecho a decidir si el contenido publicado por los
                                                    usuarios, al igual que el material de texto, audio, video o
                                                    fotográfico que sea cargado a la página web de Validate resulta
                                                    apropiado y se ajusta a las Condiciones. En éste sentido, Validate
                                                    podrá impedir la publicación y comercialización de contenido que
                                                    infrinja derechos de imagen, de habeas data y de privacidad de
                                                    terceros, así como aquellos que resulten ofensivos, difamatorios o
                                                    que constituyan infracciones a la ley. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 9.45cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Parágrafo:
                                                    Suspensión del Servicio </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Validate
                                                    se reserva el derecho de suspender la prestación de los servicios al
                                                    USUARIO y de inhabilitar su acceso al Software, así como a
                                                    cualquiera de los módulos creados para EL USUARIO si luego de dos
                                                    intentos de cobro del servicio el recaudo resulta fallido, o en caso
                                                    de no recibir el pago del servicio en la forma acordada. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 3.09cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Se dará
                                                    la suspensión del servicio al USUARIO con aviso anticipado. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En caso
                                                    de suspensión, la cuenta del USUARIO no será reactivada hasta que
                                                    el pago de todas aquellas deudas que en ese momento se encuentren a
                                                    cargo del usuario se hayan realizado por completo. Si luego de tres
                                                    intentos de cobro del servicio el recaudo resulta fallido, el USUARIO
                                                    debe realizar el pago del dinero adeudado mediante un enlace de
                                                    pagos. Para obtener el enlace donde se debe realizar el pago, el
                                                    USUARIO debe comunicarse con el equipo de soporte de Validate
                                                    escribiendo a traves de <?= mailto('comercial@validate.com.co')?></font></font></p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">La
                                                    suspensión de la cuenta del USUARIO no elimina su obligación de
                                                    pagar las deudas pendientes, así como tampoco el porcentaje cobrado
                                                    impide la generación de los gastos de cobranza e indemnización de
                                                    perjuicios que se pudiesen generar a favor de Validate por tal
                                                    incumplimiento. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En caso
                                                    de falta de pago, o uso indebido de la cuenta, el usuario recibirá a
                                                    su correo una notificación a partir de la cual tendrá un periodo de
                                                    gracia de 15 días durante el cual podrá consultar su cuenta y la
                                                    información que hasta el momento se encuentre almacenada en ella,
                                                    pero sin la posibilidad de agregar nueva información. El usuario
                                                    será responsable de copiar o sustraer dicha información, por cuanto
                                                    una vez venza el periodo de 15 días, Validate dejará de ser
                                                    responsable de la información albergada en la cuenta y esta podrá
                                                    ser eliminada libremente. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 9.45cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Legislación
                                                    Aplicable y Jurisdicción </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Este
                                                    contrato se rige por las leyes de la República de Colombia. Si
                                                    cualquier parte de este contrato se declara nula o contraria a la
                                                    ley, entonces la provisión inválida o no exigible se considerará
                                                    sustituida por una disposición válida y aplicable que más se
                                                    acerque a la intención del contrato original y el resto del acuerdo
                                                    entre Validate y el usuario continuará en efecto. A menos que se
                                                    especifique lo contrario en este documento, estas Condiciones
                                                    constituyen el acuerdo completo entre usted y Validate con respecto a
                                                    los Servicios de Validate y reemplaza a todas las comunicaciones
                                                    previas y propuestas, tanto de manera electrónica, oral o escrita,
                                                    entre el usuario y Validate con respecto a los Servicios de Validate.
                                                    </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 12.32cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Derecho
                                                    de retracto </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">El
                                                    USUARIO podrá ejercer su derecho al retracto en los términos del
                                                    artículo 47 de la Ley 1480 de 2011, es decir, podrá solicitar que
                                                    se reverse la transacción perdiendo el dominio sobre su cuenta y
                                                    recibiendo la devolución de lo pagado. Para efectos de poder ejercer
                                                    el derecho de retracto será necesario que el USUARIO lo ejerza
                                                    dentro de la oportunidad legal, es decir durante los cinco (5) días
                                                    posteriores a la celebración del contrato. </font></font>
                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 10.04cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Declinación
                                                    de Responsabilidad </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Este
                                                    software esta diseñado para ayudarles a los auditores en la
                                                    implementación de los Estándares Internacionales de Auditoria en la
                                                    auditoria de las entidades de tamaño pequeño y mediano, pero no
                                                    tiene la intención de ser sustituto de los Estándares
                                                    Internacionales de Auditoria. Ademas, el auditor debe utilizar este
                                                    software a la luz de su propio juicio profesional y de los hechos y
                                                    circunstancias que rodean cada auditoria particular. Validate declina
                                                    cualquier responsabilidad u obligación que pueda ocurrir, directa o
                                                    indirectamente, como consecuencia del uso y aplicación de este
                                                    software por parte de los auditores. </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 0cm; orphans: 0; widows: 0">
                                                    <br/>

                                                </p>
                                                <p class="western" style="margin-left: 0.46cm; margin-right: 11.29cm; margin-bottom: 0.28cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">Comentarios
                                                    y Preguntas </font></font>
                                                </p>
                                                <p class="western" align="justify" style="margin-left: 0.46cm; margin-right: 0.47cm; margin-top: 0.58cm; margin-bottom: 1cm; orphans: 0; widows: 0">
                                                    <font color="#222222"><font size="3" style="font-size: 11pt">En caso
                                                    de existir consultas, quejas o reclamos sobre la información
                                                    contenida en el presente documento de Términos y Condiciones, por
                                                    favor escribir al correo <?= mailto('comercial@validate.com.co')?>, utilizando como
                                                    asunto: Comentario Términos y Condiciones. </font></font>
                                                </p>    
                                            </div>
                                        </div>
                                        <div id="perfil-empresa" class="perfil-tab ` + d.get('perfil-empresa') + `">
                                            <b>DATOS DE LA EMPRESA</b>
                                            <hr/>
                                            <form class="mt-3 form-data" enctype="multipart/form-data" id="form-empresa">
                                                <input type="hidden" value="" name="idCliente" id="idCliente" />
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-justify">
                                                            <b>Validate</b>  es una poderosa herramienta para análisis, y auditoría contable, fácil de utilizar, nos ayuda a verificar la calidad 
                                                            e integridad de la información de bases de datos y la información contable de la empresa, nos ayuda en la identificación, análisis y 
                                                            prevención de fraudes. Ayuda en la elaboración de los documentos que debe elaborar el revisor fiscal o auditor, permitiendo que de 
                                                            forma automatizada cumpla con sus funciones de manera más eficaz y precisa.
                                                        </p>
                                                    </div>
                                                </div>        
                                                <div class="form-group row">
                                                    <label for="empr_nombre" class="col-sm-3 col-form-label text-muted">Empresa</label>
                                                    <div class="col-sm-9">
                                                        <span id="empr_nombre">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_identificacion" class="col-sm-3 col-form-label text-muted">NIT</label>
                                                    <div class="col-sm-9">
                                                        <span id="empr_identificacion">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_correo" class="col-sm-3 col-form-label text-muted">Correo Electrónico</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="empr_correo" autocomplete="off" class="form-control" id="empr_correo" value="" maxlength="50">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_telefonos" class="col-sm-3 col-form-label text-muted">Teléfono</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="empr_telefonos" autocomplete="off" class="form-control" id="empr_telefonos" value="" maxlength="50">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_direccion" class="col-sm-3 col-form-label text-muted">Dirección</label>
                                                    <div class="col-sm-9">
                                                        <textarea type="textarea" class="form-control" name="empr_direccion" id="empr_direccion" rows="3"></textarea>
                                                    </div>
                                                </div>        
                                                <div class="form-group row position-relative">

                                                    <label for="empr_logo" class="col-sm-3 col-form-label text-muted">
                                                        Logotipo en PDF's
                                                        <img id="img" src="" width="64" height="64" class="bd-placeholder-img position-absolute" />
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="archivo" name="archivo">
                                                            <label class="custom-file-label" for="archivo">Cargar archivo</label>
                                                        </div>
                                                        <p id="empr_logotipo" class="form-text text-muted">El logotipo debe ser una imagen JPG,JPEG ó PNG las dimensiones 300x300 píxeles</p>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_usarlogotipo" class="col-sm-3 col-form-label text-muted">Utilizar logotipo</label>        
                                                    <div class="col-sm-9">
                                                        <div class="row" style="padding-left: 15px;">
                                                            <div class="form-check mt-2 mb-2 mr-4 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usarlogotipo" id="empr_usarlogotipo1" value="no">
                                                                <label class="form-check-label" for="empr_usarlogotipo1">
                                                                    No utilizar logotipo
                                                                </label>
                                                            </div>
                                                            <div class="form-check mt-2 mb-2 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usarlogotipo" id="empr_usarlogotipo2" value="si">
                                                                <label class="form-check-label" for="empr_usarlogotipo2">
                                                                    Colocar logotipo en los PDF's
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row d-none">
                                                    <label for="empr_firma" class="col-sm-3 col-form-label text-muted">Firma en PDF's</label>
                                                    <div class="col-sm-9">
                                                        <textarea type="textarea" class="form-control" name="empr_firma" id="empr_firma" rows="3"></textarea>
                                                        <p id="empr_firma" class="form-text text-muted">La firma se añade en todos los archivos PDF's de análisis con una línea superior para colocar la firma</p>
                                                    </div>
                                                </div>
                                                <div class="form-group row d-none">
                                                    <label for="empr_usarfirma" class="col-sm-3 col-form-label text-muted">Utilizar Firma</label>        
                                                    <div class="col-sm-9">
                                                        <div class="row" style="padding-left: 15px;">
                                                            <div class="form-check mt-2 mb-2 mr-4 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usarfirma" id="empr_usarfirma1" value="no">
                                                                <label class="form-check-label" for="empr_usarfirma1">
                                                                    No utilizar firma
                                                                </label>
                                                            </div>
                                                            <div class="form-check mt-2 mb-2 float-left">
                                                                <input class="form-check-input" type="radio" name="empr_usarfirma" id="empr_usarfirma2" value="si">
                                                                <label class="form-check-label" for="empr_usarfirma2">
                                                                    Colocar firma en los PDF's
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="empr_color" class="col-sm-3 col-form-label text-muted">Color en Gráficas</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <input type="color" class="form-control" id="empr_color" name="empr_color" value="">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-formulario" type="button" onClick="PERFIL.methods.colorDefault()" id="button-addon2">Color por defecto</button>
                                                            </div>
                                                        </div>
                                                        <p id="empr_color" class="form-text text-muted">Color para los gráficos presentes en los análisis</p>
                                                    </div>
                                                </div>        
                                                <hr class="">
                                                <button type="button" class="btn btn-primary float-right" onClick="PERFIL.methods.saveEmpresa(true)">Actualizar</button>        
                                                <button type="button" class="btn btn-link float-right mr-2" onClick="PERFIL.methods.contrato(true)">Cancelar</button>
                                            </form>                                            
                                        </div>
                                        <div id="perfil-usuario" class="perfil-tab ` + d.get('perfil-usuario') + `">
                                            <b>PERFIL DE USUARIO</b>
                                            <hr/>
                                            <form class="mt-3 form-data" id="form-perfil">
                                                <input type="hidden" value="" name="idUsuario" id="idUsuario" />
                                                <input type="hidden" value="" name="email" id="email" />
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Correo Electrónico</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <span id="user_correo">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="user_nombre" class="col-sm-3 col-form-label text-muted">Nombres</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="user_nombre" autocomplete="off" class="form-control" id="user_nombre" value="" maxlength="50">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="user_apellido" class="col-sm-3 col-form-label text-muted">Apellidos</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="user_apellido" autocomplete="off" class="form-control" id="user_apellido" value="" maxlength="50">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="user_telefono" class="col-sm-3 col-form-label text-muted">Teléfono</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="user_telefono" autocomplete="off" class="form-control" id="user_telefono" value="" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="user_professional_card" class="col-sm-3 col-form-label text-muted">Tarjeta Profesional</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="user_professional_card" autocomplete="off" class="form-control" id="user_professional_card" value="" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="user_clave" class="col-sm-3 col-form-label text-muted">Clave<br/>(si quiere cambiarla)</label>
                                                    <div class="col-sm-9">
                                                        <input type="password" name="user_clave" autocomplete="off" class="form-control" id="user_clave" value="" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <label for="user_repetir" class="col-sm-3 col-form-label text-muted">Repetir Clave<br/>(si quiere cambiarla)</label>
                                                    <div class="col-sm-9">
                                                        <input type="password" name="user_repetir" autocomplete="off" class="form-control" id="user_repetir" value="" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Ultimo acceso</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <span id="user_last_login">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label class="text-muted">Ultima IP accedió</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <span id="user_ip_address">N/A</span>
                                                    </div>
                                                </div>
                                                <hr class="">
                                                <button type="button" class="btn btn-primary float-right" onClick="PERFIL.methods.save(true)">Actualizar</button>        
                                                <button type="button" class="btn btn-link float-right mr-2" onClick="PERFIL.methods.contrato(true)">Cancelar</button>
                                            </form>
                                        </div>
                                        <div id="perfil-materialidad" class="perfil-tab ` + d.get('perfil-materialidad') + `">
                                            <b>MATERIALIDAD</b>
                                            <hr/>
                                            <div id="table-materialidad" class="d-block">
                                                <a class="nav-item nav-link px-0 py-0 float-left" href="#" id="materialidad-archivoNombre-link" onClick="" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="materialidad-archivoNombre" style="max-width: 500px;"><span></a>
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 200px">Cuenta</th>
                                                            <th style="width: 120px">Rango</th>
                                                            <th style="width: 80px">Porcentaje</th>
                                                            <th style="width: 177px">Valor</th>
                                                            <th style="width: 176px">Materialidad</th>
                                                            <th style="width: 176px">Error Tolerable</th>
                                                            <th style="width: 176px">Importe Nominal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left">Utilidad Antes de Impuesto</td>
                                                            <td class="text-right">5,0% a 10,0%</td>
                                                            <td class="text-right"><span id="utladi"></span></td>
                                                            <td class="text-right"><span id="utladi_val"></span></td>
                                                            <td class="text-right"><span id="utladi_mate"></span></td>
                                                            <td class="text-right"><span id="utladi_erto"></span></td>
                                                            <td class="text-right"><span id="utladi_imno"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">Utilidad Operacional</td>
                                                            <td class="text-right">7,0% a 10,0%</td>        
                                                            <td class="text-right"><span id="utlope"></span></td>
                                                            <td class="text-right"><span id="utlope_val"></span></td>
                                                            <td class="text-right"><span id="utlope_mate"></span></td>
                                                            <td class="text-right"><span id="utlope_erto"></span></td>
                                                            <td class="text-right"><span id="utlope_imno"></span></td>        
                                                        </tr>        
                                                        <tr>
                                                            <td class="text-left">Utilidad Bruta</td>
                                                            <td class="text-right">3,0% a 5,0%</td>        
                                                            <td class="text-right"><span id="utlbru"></span></td>        
                                                            <td class="text-right"><span id="utlbru_val"></span></td>
                                                            <td class="text-right"><span id="utlbru_mate"></span></td>
                                                            <td class="text-right"><span id="utlbru_erto"></span></td>
                                                            <td class="text-right"><span id="utlbru_imno"></span></td>        
                                                        </tr>        
                                                        <tr>        
                                                            <td class="text-left">Ingresos Operacionales</td>
                                                            <td class="text-right">0,5% a 1,0%</td>        
                                                            <td class="text-right"><span id="ingope"></span></td>        
                                                            <td class="text-right"><span id="ingope_val"></span></td>
                                                            <td class="text-right"><span id="ingope_mate"></span></td>
                                                            <td class="text-right"><span id="ingope_erto"></span></td>
                                                            <td class="text-right"><span id="ingope_imno"></span></td>        
                                                        </tr>        
                                                            <td class="text-left">Activos</td>
                                                            <td class="text-right">0,5% a 1,0%</td>        
                                                            <td class="text-right"><span id="activo"></span></td>
                                                            <td class="text-right"><span id="activo_val"></span></td>
                                                            <td class="text-right"><span id="activo_mate"></span></td>
                                                            <td class="text-right"><span id="activo_erto"></span></td>
                                                            <td class="text-right"><span id="activo_imno"></span></td>        
                                                        </tr>        
                                                        <tr>        
                                                            <td class="text-left">Patrimonio</td>
                                                            <td class="text-right">5,0% a 7,0%</td>        
                                                            <td class="text-right"><span id="patrim"></span></td>
                                                            <td class="text-right"><span id="patrim_val"></span></td>
                                                            <td class="text-right"><span id="patrim_mate"></span></td>
                                                            <td class="text-right"><span id="patrim_erto"></span></td>
                                                            <td class="text-right"><span id="patrim_imno"></span></td>        
                                                        </tr>                                                    
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="table-materialidad-msg" class="d-none">
                                                <div class="alert alert-info" style="width: 100%;">Se Debe seleccionar una empresa para comprobar si existe configuración</div>
                                            </div>
                                        </div>        
                                        <div id="perfil-columnas" class="perfil-tab ` + d.get('perfil-columnas') + `">
                                            <b>COLUMNAS POR DEFECTO</b>
                                            <hr/>
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-justify">
                                                            <b>Validate</b>  es una poderosa herramienta para análisis, y auditoría contable, fácil de utilizar, nos ayuda a verificar la calidad 
                                                            e integridad de la información de bases de datos y la información contable de la empresa, nos ayuda en la identificación, análisis y 
                                                            prevención de fraudes. Ayuda en la elaboración de los documentos que debe elaborar el revisor fiscal o auditor, permitiendo que de 
                                                            forma automatizada cumpla con sus funciones de manera más eficaz y precisa.
                                                        </p>
                                                    </div>
                                                </div>
                                                <b>CONFIGURACIONES POR DEFECTO ALMACENADAS</b>
                                                <hr class="mt-1 mb-3"/>
                                                <div class="container-fluid" id="form-resultados">
                                                    <div class="row" style="max-height: 200px; overflow: auto;" id="body-columnas"></div>
                                                </div>
                                                <form class="mt-3 form-data" id="form-archivo-config">
                                                    <table id="table-config-archivo" class="table table-hover table-striped mt-3"></table>
                                                    <div id="btn-config-archivo"></div>
                                                </form>
                                            </div>
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
    }
}
