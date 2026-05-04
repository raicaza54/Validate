/**
 * Ayuda - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-08-11
 */
var AYUDA = AYUDA || {};
AYUDA.chart = [];
AYUDA.methods = {
    asistente: function (obj, bodyClear = true) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            $.when().then(function () {
                GLOBAL.computed.initializeModal(ventana, bodyClear);
                AYUDA.componets.asistente();
            }).then(function () {
                ventana.find('.modal-title').text('Asistente de inicio');
                ventana.find('.btn-primary').text('Siguiente');
                ventana.find('.btn-primary').attr('onclick','AYUDA.methods.empresa(true)');
                ventana.modal('show');
            });            
        }
    },
    empresa: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            $.when().then(function () {
                GLOBAL.computed.initializeModal(ventana, false);
                AYUDA.componets.empresa();
            }).then(function () {
                ventana.find('.modal-title').text('Empresa');
                ventana.find('.btn-primary').text('Siguiente');
                ventana.find('.btn-primary').attr('onclick','AYUDA.methods.carpetaarchivos(true)');
                var button = '<button type="button" onClick="AYUDA.methods.asistente(true, false)" class="btn btn-secondary">Atras</button>';
                ventana.find('#btn-extra').html(button);
                ventana.find('#btn-extra').show();
            });
        }
    },
    carpetaarchivos: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            $.when().then(function () {
                GLOBAL.computed.initializeModal(ventana, false);
                AYUDA.componets.carpetaarchivos();
            }).then(function () {
                ventana.find('.modal-title').text('Carpeta de Archivos');
                ventana.find('.btn-primary').text('Siguiente');
                ventana.find('.btn-primary').attr('onClick','AYUDA.methods.carpetaresultados(true)');
                var button = '<button type="button" onClick="AYUDA.methods.empresa(true)" class="btn btn-secondary">Atras</button>';
                ventana.find('#btn-extra').html(button);
                ventana.find('#btn-extra').show();
            });            
        }
    },
    carpetaresultados: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            $.when().then(function () {
                GLOBAL.computed.initializeModal(ventana, false);
                AYUDA.componets.carpetaresultados();
            }).then(function () {
                ventana.find('.modal-title').text('Carpeta de Resultados');
                ventana.find('.btn-primary').text('Finalizar');
                ventana.find('.btn-primary').attr('onclick','AYUDA.methods.finalizar(true)');
                var button = '<button type="button" onClick="AYUDA.methods.carpetaarchivos(true)" class="btn btn-secondary">Atras</button>';
                ventana.find('#btn-extra').html(button);
                ventana.find('#btn-extra').show();
            });
        }
    },
    finalizar: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            AYUDA.computed.finalizar().then(function (e) {
                if(parseInt(e.status) == 200){
                    setTimeout(function () { EMPRESAS.methods.activarEmpresa(true) } , 2000);
                    setTimeout(function () { ARCHIVOS.methods.cargarArchivo(true) } , 3000);
                    setTimeout(function () { GLOBAL.computed.restaurar() }, 3500);
                    ventana.modal('hide');
                }
            });
        }
    }
    
}
AYUDA.computed = {
    siguiente: function (pag) {
        $.when().then(function () {
            $('#form-asistente').hide();
            $('#form-empresa').hide();
            $('#form-carpetaarchivos').hide();
            $('#form-carpetaresultados').hide();            
        }).then(function () {
            $('#' + pag).show();
        });
    },
    finalizar: function (){
        var form_data = {
            nombre: $('#emp-nombre').val(),
            identificacion: $('#emp-identificacion').val(),
            direccion: $('#emp-direccion').val(),
            persona: $('#emp-persona').val(),
            persona_tlfs: $('#emp-persona_tlfs').val(),
            'crp-label': $('#crp-label').val(),
            'res-label': $('#res-label').val(),
        };
        var ventana = $('#ventanaModal');
        return $.ajax({
            url: '/asistente/v1/salvar',
            type: "POST",
            dataType: 'json',
            data: {
                form: form_data
            },
            beforeSend: function (xhr) {
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                    ventana.find('.btn-primary').prop('disabled', false);
                    ventana.find('.btn-link').prop('disabled', false);
                    ventana.find('.btn-secondary').prop('disabled', false);
                    ventana.find('.btn-primary').html('Finalizar');                    
                }else{
                    $('input[name="id-empresa"]').val(data.data.empresaId);
                    GLOBAL.empresaId = data.data.empresaId;
                }
                GLOBAL.computed.secure();
            },
            error: function (jqXHR) {
                var ventana = $('#ventanaModal');
                ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);
                ventana.find('.btn-primary').html('Aplicar y actualizar');
            }
        });        
    }
}
AYUDA.componets = {
    asistente: function() {
        $.when().then(function () {
            AYUDA.computed.siguiente('form-asistente');
        }).then(function () {
            if (!$("#form-asistente").length) {
                $('#ventanaModal .modal-body').append(
                    `<form id="form-asistente">
                        <p>
                            Bienvenido a Validate System, antes de realizar sus análisis es necesario que cree una empresa o compañía. Lo invitamos a crear la misma, cualquier duda referente a este proceso podrá encontrar en el portal de ayuda material en video con su explicación paso a paso. Bienvenido a la era 4.0
                        </p>
                    </form>`);            
            }            
        });
    },
    empresa: function() {
        $.when().then(function () {
            AYUDA.computed.siguiente('form-empresa');
        }).then(function () {
            if (!$("#form-empresa").length) {
                $('#ventanaModal .modal-body').append(
                    `<form id="form-empresa">
                        <p>
                            Validate System trabaja bajo un modelo de empresas, en la cual usted tendrá la posibilidad de tener diferentes compañías dentro del sistema que le permitirán un manejo de los datos independiente y una mayor especificación  en los informes que realice ya que cada uno será a nombre de la empresa en la cual se ejecute el proceso. Realice Con detenimiento este proceso para que obtenga una mayor calidad en su trabajo.
                        </p>
                        <div class="form-group">
                            <label for="emp-nombre">Datos de la Empresa</label>
                            <input type="text" maxlength="250" class="form-control" id="emp-nombre" name="emp-nombre" placeholder="Ingrese el nombre de la Empresa">
                        </div>
                        <div class="form-group">
                            <input type="text" maxlength="250" class="form-control" id="emp-identificacion" name="emp-identificacion" placeholder="NIT de la Empresa">
                        </div>
                        <div class="form-group">
                            <input type="text" maxlength="500" class="form-control" id="emp-direccion" name="emp-direccion" placeholder="Dirección de la Empresa">
                        </div>
                        <div class="form-group">
                            <label for="emp-persona">Persona Contacto en la Empresa</label>
                            <input type="text" maxlength="250" class="form-control" id="emp-persona" name="emp-persona" placeholder="Ingrese el nombre de la Persona contacto">
                        </div>
                        <div class="form-group">
                            <input type="text" maxlength="250" class="form-control" id="emp-persona_tlfs" name="emp-persona_tlfs" placeholder="Teléfono de la Persona contacto">
                        </div>
                    </form>`);        
            }
        });
    },
    carpetaarchivos: function() {
        $.when().then(function () {
            AYUDA.computed.siguiente('form-carpetaarchivos');
        }).then(function () {
            if (!$("#form-carpetaarchivos").length) {
                $('#ventanaModal .modal-body').append(
                    `<form id="form-carpetaarchivos">
                        <p>
                            Las carpetas de archivos son los elementos en los cuales serán almacenados los archivos subidos a Validate , estas pueden ser creadas, eliminadas o editadas. 
                        </p>
                        <div class="form-group">
                            <label for="crp-label">Carpeta de Archivos</label>
                            <input type="text" maxlength="250" class="form-control" id="crp-label" name="crp-label" aria-describedby="crp-label" placeholder="Ingrese el nombre de la Carpeta de Archivos">
                            <small id="crp-label" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                    </form>`);        
            }
        });
    },
    carpetaresultados: function() {
        $.when().then(function () {
            AYUDA.computed.siguiente('form-carpetaresultados');
        }).then(function () {
            if (!$("#form-carpetaresultados").length) {
                $('#ventanaModal .modal-body').append(
                    `<form id="form-carpetaresultados">
                        <p>
                            Las carpetas de resultados son los elementos en los cuales serán almacenados los archivos subidos a Validate, estas pueden ser creadas, eliminadas o editadas. 
                        </p>    
                        <div class="form-group">
                            <label for="res-label">Carpeta de Archivos</label>
                            <input type="text" maxlength="250" class="form-control" id="res-label" name="res-label" aria-describedby="res-label" placeholder="Ingrese el nombre de la Carpeta de Archivos">
                            <small id="res-label" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                    </form>`);        
            }
        });
    },
    tab: function () {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-ayuda-tab" data-toggle="tab" href="#nav-ayuda" role="tab" aria-controls="nav-ayuda" aria-selected="true">Ayuda</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <input name="id-empresa" type="hidden" value="">
                <div class="tab-pane fade show active clearfix" id="nav-ayuda" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-ayuda">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link" href="#" onClick="AYUDA.methods.asistente(true)">
                                    <i class="far fa-life-ring"></i> Asistente de inicio
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
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h3 class="text-center">Análisis de datos contables</h3>
                                    <p style="font-size: 16px; width: 70%; margin: 0 auto;" class="text-center">
                                        Este es el portal de ayuda, en el cual podrás encontrar los videos referentes a cualquier proceso en Validate System, en caso de no encontrar el requerimiento solicitado por favor comunicarse en el chat de ayuda. 
                                    </p>
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/lxyjliqtaCc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/Vl1kelZ5OdA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/U17OKzBzFd4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/h9VPmsX1QqY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>        
                                </div>        
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/KByABt7cY9U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/KfSq6vS4ry4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>        
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/DWvYGJD6Apc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/-kt01uW-Lj8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-4">
                                    <div class="embed-responsive embed-responsive-21by9 video-marco">
                                        <iframe src="https://www.youtube.com/embed/QBEaRTBVpHo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);        
    }
}