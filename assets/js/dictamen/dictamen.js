/**
 * Archivos - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2020-07-26
 */
var DICTAMEN = DICTAMEN || {};
DICTAMEN.modal = null;
//Procesos
DICTAMEN.methods = {
editar: function(e) {
        $.ajaxSetup({async:false});
        var salvar = function (x) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fas fa-save"/> Salvar',
                tooltip: 'Salvar Párrafo',
                click: function (event) {
                    $.ajaxSetup({async:false});
                    $(e).summernote('destroy');
                    DICTAMEN.computed.salvar().then(function (data) {
                        $('#id_formato').val(data.data.id_formato);
                    });
                    $.ajaxSetup({async:true});
                }
            });
            return button.render();
        }
        $(e).summernote({
            height: (e.clientHeight),
            lang: 'es-ES',
            focus: true,
            shortcuts: false,
            styleTags: ['p', {style : 'line-height: 1'}],
            toolbar: [
                ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table','undo','redo']],
                //['view', ['codeview']],
                ['custom1', ['salvar']]
            ],
            buttons: {
                salvar: salvar,
            },
            cleaner: {
                  action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                  newline: '<br/>', // Summernote's default is to use '<p><br></p>'
                  notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                  keepHtml: false, // Remove all Html formats
                  keepOnlyTags: ['<span>', '<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                  keepClasses: false, // Remove Classes
                  badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                  badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                  limitChars: false, // 0/false|# 0/false disables option
                  limitDisplay: 'both', // text|html|both
                  limitStop: false // true/false
            }
        });
        $('div.summernote').each(function (k,x) {
            if((x.id != e.id) && ($('#' + x.id).css('display') == 'none')){
                $('#' + x.id).summernote('destroy');
                DICTAMEN.computed.salvar().then(function (data) {
                    $('#id_formato').val(data.data.id_formato);
                });                
            }
        });
        $.ajaxSetup({async:true});
    },
    parametros: function (obj) {
        var ventana = $('#ventanaModal');
        GLOBAL.computed.initializeModal(ventana);
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debe seleccionar una <b>"Empresa"</b> a la cual estará asociado el Dictamen, intetelo nuevamente`, 400);
                return;
            }
            var datos = DICTAMEN.computed.formatos();
            datos.then(function (data) {
                DICTAMEN.componets.formatos(data);
            });
            ventana.find('.modal-title').text('Formato de Dictamen');
            ventana.find('.btn-primary').text('Confirmar');
            ventana.find('.btn-primary').attr('onclick','DICTAMEN.methods.diligenciar()');
            ventana.find('.btn-link').text('Cancelar');
            ventana.modal('show');
        }
    },
    diligenciar: function () {
        var e = $('input[name="formato"]:checked').val();
        var ventana = $('#ventanaModal');
        var datos = DICTAMEN.computed.contenido(e);
        datos.then(function (data) {
            DICTAMEN.componets.formato(data, e).then(function () {
                GLOBAL.computed.maximizar();
                ventana.modal('hide');
            });            
        });
    },
    diligenciarBorrador: function (e) {
        var ventana = $('#ventanaModal');
        var datos = DICTAMEN.computed.borrador(e);
        datos.then(function (data) {
            DICTAMEN.componets.formato(data, data.data.format).then(function () {
                GLOBAL.computed.maximizar();
                ventana.modal('hide');
                return data;
            }).then(function (data) {
                $('#id_formato').val(data.data.id);
            });
        });
    },
    descartar: function (e, t) {
        DICTAMEN.modal = $.fn.dialogue({
            title: 'Descartar Borrador',
            content: '¿Esta usted seguro de descartar este borrador?<br/>' + t,
            closeIcon: true,
            closeButton: true,
            buttons: [
                {text: 'Descartar', css: 'btn-primary', click: 'DICTAMEN.methods.descartado(\'' + e + '\')'},
            ]
        });
    },
    descartado: function (e) {
        DICTAMEN.computed.descartar(e).then(function (data) {
            if(data.data == 1){
                DICTAMEN.methods.parametros(true);
                if(e == $('#id_formato').val()){
                    DICTAMEN.componets.limpiarContent();
                }
            }
            DICTAMEN.modal.modal('hide');
        });
    },
    datos: function () {
        var form_data = $('form#form-dictamen').serializeArray();
        var content = [];
        $('div.summernote.wysiwyg').each(function (k,v) {
            content.push({ name: $(v).attr('name'), value: v.innerHTML });
        });
        form_data.push({ name: "contenido", value: content });
        return form_data;
    }
}
//Conexion con backend
DICTAMEN.computed = {
    salvar: function () {
        $.ajaxSetup({async:false});
        form_data = DICTAMEN.methods.datos();
        return $.ajax({
            url: '/dictamen/v1/salvar',
            type: "POST",
            dataType: 'json',
            data: { form: form_data},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }            
                GLOBAL.computed.secure();
            }
        });
        $.ajaxSetup({async:true});
    },
    formatos: function () {
        return $.ajax({
            url: '/dictamen/v1/formatos',
            type: "POST",
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
    descartar: function (e) {
        return $.ajax({
            url: '/dictamen/v1/descartar',
            type: "POST",
            dataType: 'json',
            data:{
                id: e
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
    contenido: function (e) {
        return $.ajax({
            url: '/dictamen/v1/contenido',
            type: "POST",
            dataType: 'json',
            data:{
                formato: e
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
    borrador: function (borrador) {
        return $.ajax({
            url: '/dictamen/v1/borrador',
            type: "POST",
            dataType: 'json',
            data:{
                id: borrador
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
    }
}
//Html
DICTAMEN.componets = {
    limpiarContent: function () {
        GLOBAL.computed.restaurar(GLOBAL.computed.dtable);
        $('#content').html(
            `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>
            </div>`
        );
    },    
    formato: function(e, formato) {
        var deferred = $.Deferred();
        var contenido = e.data.formato.contenido;
        var parrafos = '';
        var direccion = `<p class="note-editable">
            ` + e.data.revisor.nombre + `<br/>
            Revisor Fiscal<br/>
            T.P. N° ` + e.data.revisor.tp + `
        </p>`;
        var c = contenido.length - 1;
        $.each(contenido, function (k, v) {
            if(k == c) parrafos += direccion;
            parrafos += `
                <h6>` + v.titulo + `</h6>
                <input type="hidden" value="` + v.titulo + `" id="tit`+ k +`" name="tit`+ k +`" />
                <div class="summernote wysiwyg" onclick="DICTAMEN.methods.editar(this)" title="Click para editar" name="tit`+ k +`" id="edit`+ k +`">` + (v.cuerpo ? v.cuerpo : '<p><br/></p>') + `</div>
            `;
        });
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-dictamen-tab" data-toggle="tab" href="#nav-dictamen" role="tab" aria-controls="nav-dictamen" aria-selected="true">Dictamen</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-dictamen" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-dictamen">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this,'dictamen')">
                                    <i class="far fa-save"></i>
                                    Finalizar y Guardar PDF
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="DICTAMEN.methods.parametros(this)">
                                    <i class="far fa-comment-dots"></i>
                                    Formatos Dictamen
                                </span>
                            </li>        
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.dtable)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.dtable)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable">
                            <form method="post" id="form-dictamen">
                                <input type="hidden" value="" id="id_formato" name="id_formato" />
                                <input type="hidden" value="` + formato + `" id="format" name="format" />
                                <input type="hidden" value="` + e.data.revisor.nombre + `" id="nombre" name="revisor.nombre" />
                                <input type="hidden" value="` + e.data.revisor.tp + `" id="tp" name="revisor.tp" />
                                <input type="hidden" value="` + e.data.empresa + `" id="empresa" name="empresa" />
                                <input type="hidden" value="` + e.data.formato.titulo + `" id="titulo" name="titulo" />
                                <input type="hidden" value="` + e.data.formato.formato + `" id="archivo" name="archivo" />
                                <h6 class="text-center mt-3">`+ e.data.formato.titulo +`</h6>
                                    ` + parrafos + `
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            `);
            deferred.resolve();
        
        return deferred.promise();
    },
    formatos: function (e) {
        var formato = e.data;
        var borrador = e.borradores;
        var borradores = '';
        var formatos = '';
        $.each(formato, function (k,v) {
            formatos += `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="formato" id="` + k + `" value="` + k + `" `+( k == 'formato1' ? 'checked':'')+`>
                        <label class="form-check-label" for="` + k + `">
                            <h6>` + v.formato + `</h6>
                        </label>
                    </div>
                    `;
            if(v.comment.length){
                formatos +=
                        `<p style="padding-left: 20px;" class="text-muted" for="` + k + `">
                            ` + v.comment + `
                        </p>`;                
            }else{
                formatos += '<p></p>';
            }
        });
        $.each(borrador, function (k,v) {
            borradores += `
                    <tr>
                        <td>
                            <div class="float-left text-truncate" style="max-width: 450px;">` + v.etiqueta + `</div>
                            <div class="float-right" id="table-btn">
                                <i class="far fa-edit btn-icon" title="Editar" onclick="DICTAMEN.methods.diligenciarBorrador('` + v.id + `')"></i> 
                                <i class="far fa-trash-alt btn-icon" title="Descartar" onclick="DICTAMEN.methods.descartar('` + v.id + `','- ` + v.etiqueta + '<br/>&nbsp;&nbsp;Fecha: ' + v.update_at + `')"></i>
                            </div>
                        </td>
                        <td style="width: 200px;"><div>` + v.update_at + `</div></td>
                    </tr>`;
        });
        $('#ventanaModal .modal-body').html(
            `<p>
                    Los siguientes son formatos que no permite extenderse en explicaciones, así que en algunas acciones concretas se puede hacer referencia a otros expedientes que recogen toda la información sobre su desarrollo. 
                    <div style="max-height: 145px; overflow: auto;">
                    <table class="table table-bordered table-hover" id="table-acciones">
                      <tbody>` + borradores + `</tbody>
                    </table>
                    </div>
                </p><div class="scrollTable" style="height: calc(100vh - 470px);">`
                + formatos + '</div>');
    }
}