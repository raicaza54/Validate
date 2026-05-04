/**
 * Resultados - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-05-01
 */
var RESULTADOS = RESULTADOS || {};
RESULTADOS.methods = {
    crearCarpeta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(!GLOBAL.computed.isNull(GLOBAL.empresaId)){
                var ref = $('#resultadosTree, #modalTree').jstree(true);
                if(!GLOBAL.computed.isNull(GLOBAL.resultId)){
                    var sel = ref.create_node(GLOBAL.resultId, {
                        'id': GLOBAL.computed.uniqint(),
                        'type':'folder',
                        'text': 'Carpeta nueva'
                    });
                }else{
                    var sel = ref.create_node("#", {
                        'id': GLOBAL.computed.uniqint(),
                        'type':'folder',
                        'text': 'Carpeta nueva'
                    });                    
                }
                if(sel) {
                    ref.edit(sel);
                }
            }else{
                GLOBAL.computed.toast('Debe seleccionar una empresa donde se puedan crear las carpetas');
            }
        }
    },
    editarCarpeta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ref = $('#resultadosTree, #modalTree').jstree(true), sel = ref.get_selected();
            if(!sel.length) { return false; }
            sel = sel[0];
            ref.edit(sel);
        }
    },
    filtroResultado: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            
        }
    },
    modalResultados: function (obj, app = false) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('.modal-title').text('Guardar Resultados');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);        
            ventana.find('.btn-primary').text('Guardar Resultado');
            ventana.find('.btn-primary').attr('onclick','RESULTADOS.computed.guardarResultados(\'' + app + '\')');
            ventana.find('.btn-link').text('Cancelar');
            $.when().then(function () {
                RESULTADOS.componets.resultadosModal();
            }).then(function () {
                RESULTADOS.methods.listarCarpetas(true, GLOBAL.empresaId);
            }).then(function () {
                ventana.modal('show');
            });
        }
    },
    borrarCarpeta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(`Debe seleccionar un elemento para eliminarlo`, 400);
                return;
            }
            var sel = $('#resultadosTree').jstree('get_selected', true);
            if(sel.length){
                var ventana = $('#ventanaModal');
                GLOBAL.computed.initializeModal(ventana, true, 'modal');
                ventana.find('.modal-title').text('Explorador de Resultado');
                ventana.find('.btn-primary').show();
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);        
                ventana.find('.btn-primary').text('Eliminar');
                ventana.find('.btn-primary').attr('onclick','RESULTADOS.methods.borrarRun()');
                ventana.find('.btn-link').text('Cancelar');
                RESULTADOS.componets.borrarModal(sel[0].text);
                ventana.modal('show');                
            }else{
                GLOBAL.computed.toast(`Debe seleccionar un archivo o directorio`, 400);
                return false;                
            }
        }
    },
    borrarRun: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(!$('.fa-trash-alt').hasClass('item-disabled')){
                var ventana = $('#ventanaModal');
                $.when().then(function () {
                    ventana.modal('hide');
                }).then(function () {
                    var ref = $('#resultadosTree, #modalTree').jstree(true), sel = ref.get_selected();
                    if(!sel.length) { return false; }
                    ref.delete_node(sel);                    
                });
            }
        }
    },
    listarCarpetas: function (obj, id, archivo = false) {
        var deferred = $.Deferred();
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(`Debe seleccionar una empresa para poder explorar sus carpetas`, 400);
                return;
            }
            var datos = RESULTADOS.computed.cargaCarpetas(id, archivo);
            datos.then(function (data) {
                RESULTADOS.componets.arbol();
                return data;
            }).then(function (data) {
                GLOBAL.resultId = null;
                GLOBAL.resultPath = 'Debe seleccionar un destino';
                return data;
            }).then(function (data) {
                RESULTADOS.componets.arbolPoblar(data['data']);
                deferred.resolve();
                if(archivo == true){
                    $('#explorador a[href="#tabresultados"]').tab('show');
                }
            });
        }
        return deferred.promise(); 
    }
}
RESULTADOS.computed = {
    aplicar: function(e) {
        var deferred = $.Deferred();
        if(e == 'dictamen'){
            form_data = DICTAMEN.methods.datos();
            $.when().then(function () {
                GLOBAL.computed.initializePdf('dictamen');
            }).then(function () {
                GLOBAL.pdfDictamen = GLOBAL.computed.uniqint();
            }).then(function () {
                form_data.push({ name: "ejecucion", value: GLOBAL.pdfDictamen });
                $.ajax({
                    url: '/dictamen/v1/procesar',
                    type: "POST",
                    dataType: 'json', 
                    data: {form: form_data},
                    complete: function (jqXHR, textStatus) {
                        var data = jqXHR.responseJSON;
                        var status = parseInt(data.status);
                        if(status != 200){
                            GLOBAL.computed.toast(data.detail, status);
                        }
                        GLOBAL.computed.secure();
                        if(data.data == 1) DICTAMEN.componets.limpiarContent();
                        deferred.resolve();
                    }
                });
            });
        }else if(e == 'materialidad'){
            $.when().then(function () {
                GLOBAL.computed.initializePdf('materialidad');
            }).then(function () {
                GLOBAL.pdfMaterialidad = GLOBAL.computed.uniqint();
            }).then(function () {
                var form_data = $('form#form-materialidad').serializeArray();
                form_data.push({ name: "archivoId", value: GLOBAL.archivoId });
                form_data.push({ name: "ejecucion", value: GLOBAL.pdfMaterialidad });
                return $.ajax({
                    url: '/materialidad/v1/procesar',
                    type: "POST",
                    dataType: 'json',
                    data: {form: form_data},
                    complete: function (jqXHR, textStatus) {
                        var data = jqXHR.responseJSON;
                        var status = parseInt(data.status);
                        if(status != 200){
                            GLOBAL.computed.toast(data.detail, status);
                        }
                        GLOBAL.computed.secure();
                        deferred.resolve();
                    }
                });
            });
        }else{
            deferred.resolve();
        }
        return deferred.promise();
    },
    guardarResultados: async function(e) {
        var deferred = $.Deferred();
        $.ajaxSetup({async:false});
        var ventana = $('#ventanaModal');
        var nombre = $('input#filename').val().replace(".pdf", "") + '.pdf';
        var carpeta = $('form#form-resultados input[name="carpeta"]').val();
        var validName = GLOBAL.computed.validaFileName(nombre);
        if(validName == false){
            GLOBAL.computed.toast('El nombre del archivo no valido, el mismo no puede contener simbolos, unicamente letras y números');
            return;
        }
        if((carpeta == 'Debe seleccionar un destino') || (carpeta.trim() == '') || GLOBAL.computed.isNull(carpeta)){
            GLOBAL.computed.toast('Debe seleccionar un destino donde se almacenara el archivo');
            return;            
        }
        RESULTADOS.computed.aplicar(e).then(function () {
            var datos = RESULTADOS.computed.guardarProcesar(nombre, carpeta);
            datos.then(function (data) {
                if(parseInt(data.status) == 200){
                    if(data.status == 200){
                        GLOBAL.computed.AjaxDownloader({
                            url: data.data.url + '/d'
                        });
                    }
                    RESULTADOS.methods.listarCarpetas(true, GLOBAL.empresaId);
                    GLOBAL.componets.procesando('hide');
                }else{
                    GLOBAL.computed.toast('Tenemos un problema, el archivo no pudo ser procesado, intentelo nuevamente o pongase en contacto con soporte');
                }
                deferred.resolve();
            });
            ventana.modal('hide');
        });
        $.ajaxSetup({async:true});
        return deferred.promise(); 
    },
    guardarProcesar: function(nombre, carpeta) {
        let form_data = [];
        $('div.summernote.wysiwyg').each(function (k,v) {
            form_data.push({ name: $(v).attr('name'), value: v.innerHTML });
        });
        return $.ajax({
            url: '/resultados/v1/pdf',
            type: "POST",
            dataType: 'json',
            data:{
                nombre: nombre,
                archivoId: GLOBAL.archivoId,
                carpeta: carpeta,
                summernote: form_data,
                pdfBenford: GLOBAL.pdfBenford,
                pdfManipulacion: GLOBAL.pdfManipulacion,
                pdfConfianza: GLOBAL.pdfConfianza,
                pdfSpider: GLOBAL.pdfSpider,
                pdflistasControl: GLOBAL.pdflistasControl,
                pdfcondicionCuenta: GLOBAL.pdfcondicionCuenta,
                pdfDictamen: GLOBAL.pdfDictamen,
                pdfMaterialidad: GLOBAL.pdfMaterialidad,
                idPdf: GLOBAL.computed.uniqint(),
            },
            beforeSend: function (xhr) {
                GLOBAL.componets.procesando();
                var ventana = $('#ventanaModal');
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },            
            complete: function (jqXHR, textStatus) {
                if(textStatus != 'error'){
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }
                }
                GLOBAL.computed.secure();
            }      
        });        
    },
    crearProcesar: function(label, parent, id, type = 'folder') {
        return $.ajax({
            url: '/resultados/v1/crear',
            type: "POST",
            dataType: 'json',
            data:{
                label: label,
                parent_id: parent,
                type: type,
                id: id
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
    cargaCarpetas: function (id, archivo = false) {
        return $.ajax({
            url: '/resultados/v1/carpetas',
            type: "POST",
            dataType: 'json',
            data:{
                id: id,
                id_archivo: ((archivo != false) ? GLOBAL.archivoId : 0),
            },
            complete: function (jqXHR, textStatus) {
                if(textStatus != 'error'){
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }
                }
                GLOBAL.computed.secure();
            }      
        });
    },
    editarProcesar: function(label, id, parent, deleted_at = false) {
        return $.ajax({
            url: '/resultados/v1/editar',
            type: "POST",
            dataType: 'json',
            data:{
                label: label, 
                id: id,
                parent_id: parent,
                deleted_at: deleted_at
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
    descargar: function (idAnalisis) {
        return $.ajax({
            url: '/resultados/v1/descargar',
            type: "POST",
            dataType: 'json',
            data:{
                id: idAnalisis,
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
    dblclicktr: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ref = $('#resultadosTree').jstree(true), sel = ref.get_selected();
            if(!sel.length) { 
                GLOBAL.computed.toast(`Debe seleccionar un archivo para abrir`, 400);
                return false; 
            }
            sel = sel[0];
            var data = ref.get_node(sel);
            var type = data.type;
            if((type == 'csv') || (type == 'excel') || (type == 'word') || (type == 'pdf') || (type == 'img')){
                ARCHIVOS.methods.listarDatos(true, data.li_attr.file);
            }else if((type == 'benford') || (type == 'manipulacion') || (type == 'confianza') || (type == 'spider') || (type == 'listascontrol') || (type == 'condicioncuenta')){
                var datos = RESULTADOS.computed.descargar(data.li_attr.file);
                datos.then(function (data) {
                    if(data.status == 200){
                        RESULTADOS.componets.vistaPdf(data.data.url);
                    }                    
                });                    
            }            
        }
    }    
}
RESULTADOS.componets = {
    limpiarContent: function () {
        $('#content').html(
            `<div class="selec-empresa text-center text-muted small no-seleccionable">
                Debe Seleccionar<br/>una empresa
            </div>`);
    },
    limpiarResultados: function () {
        $('#resultados-content').html(`<div class="selec-empresa text-center text-muted small no-seleccionable">
                Debe Seleccionar<br/>una empresa
            </div>`);
    },    
    vistaPdf: function(url) {
        $('#content').html(
                `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-archivo-tab" data-toggle="tab" href="#nav-archivo" role="tab" aria-controls="nav-archivo" aria-selected="true">Resultado</a>
                </div>
            </nav>
            <input type="hidden" name="archivoId" value="">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-archivo" role="tabpanel" aria-labelledby="nav-archivo-tab">
                    <div id="body-archivo">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link btn-span" href="` + url + `/d"><i class="far fa-file-pdf"></i> Descargar</a>
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
                                    <object data="` + url + `/r" type="application/pdf" style="width:100%; height:1220px;">alt : <a href="` + url + `/r">archivo.pdf</a></object>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);        
    },
    resultadosModal: function() {
        $('#ventanaModal .modal-body').html(
            `<form id="form-resultados">
                <div class="row mb-2">
                    <div class="col-sm-6 text-left">
                        Espacio en Disco:
                    </div>        
                    <div class="col-sm-6 text-right">
                        200/500Gb
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 text-left">
                        Destino: <span id="path-archivo">Debe seleccionar un destino</span>
                        <input name="carpeta" type="hidden" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="input-group col-sm-12 mb-3">
                        <input type="text" class="form-control" name="filename" id="filename" placeholder="Nombre de Archivo" aria-label="Nombre de Archivo" aria-describedby="filename" maxlength="50">
                        <div class="input-group-append">
                            <span class="input-group-text" id="filename">.pdf</span>
                        </div>
                    </div>
                </div>
                <div id="modalTree" obj="tree" style="height: calc(100vh - 350px); overflow-y: auto; border: 1px solid #ccc; border-radius: 3px; padding: 10px 0px;">
                </div>
            </form>`);        
    },
    arbolPoblar: function (datos) {
        $('#resultadosTree, #modalTree').jstree({
            'core': {
                'animation' : 0,
                'themes': {
                    'responsive': false,
                },
                multiple: false,
                data: datos,
                check_callback: function(operation, node, node_parent, node_position, more) {
                    if (operation === "move_node") {
                        return node_parent.original.type === "folder";
                    }
                    return true;
                }
            },
            'types': {
                'default':         {'icon': 'far fa-file'},
                'csv':             {'icon': 'fas fa-file-csv'},
                'folder':          {'icon': 'far fa-folder'},
                'excel':           {'icon': 'far fa-file-excel'},
                'word':            {'icon': 'far fa-file-word'},
                'pdf':             {'icon': 'far fa-file-pdf'},
                'img':             {'icon': 'far fa-file-image'},
                'benford':         {'icon': 'fas fa-chart-bar'},
                'manipulacion':    {'icon': 'fas fa-user-secret'},
                'confianza':       {'icon': 'fas fa-book'},
                'spider':          {'icon': 'fas fa-spider'},
                'listascontrol':   {'icon': 'fas fa-tasks'},
                'dictamen':        {'icon': 'far fa-comment-dots'},
                'condicioncuenta': {'icon': 'fas fa-calculator'}
            },
            'dnd': {
                check_while_dragging: true
            },
            'search': {
                show_only_matches: true,
            },            
            'plugins': ['search', 'types','dnd']
        });
        var to = false;
        $('#resultadosTree_q').keyup(function () {
            if (to) {
                clearTimeout(to);
            }
            to = setTimeout(function () {
                var v = $('#resultadosTree_q').val();
                $('#resultadosTree').jstree(true).search(v);
            }, 250);
        });                
        $('#resultadosTree, #modalTree').on('create_node.jstree', function (e, data) {
            RESULTADOS.computed.crearProcesar(data.node.text, data.node.parent, data.node.id, data.node.type);
        }).on('delete_node.jstree', function (e, data) {
            if(data.node.li_attr.file == GLOBAL.archivoId){
                ARCHIVOS.componets.limpiarContent();
            }
            RESULTADOS.computed.editarProcesar(data.node.text, data.node.id, data.parent, true);
        }).on('move_node.jstree', function (e, data) {
            RESULTADOS.computed.editarProcesar(data.node.text, data.node.id, data.parent);
        }).on('rename_node.jstree', function (e, data) {
            RESULTADOS.computed.editarProcesar(data.node.text, data.node.id, data.node.parent);
        }).on('dblclick.jstree', function (e) {
            var tree = $(this).jstree();
            var data = tree.get_node(e.target);
            var type = data.type;
            if((type == 'csv') || (type == 'excel') || (type == 'word') || (type == 'pdf') || (type == 'img')){
                ARCHIVOS.methods.listarDatos(true, data.li_attr.file);
            }else if((type == 'benford') || (type == 'manipulacion') || (type == 'confianza') || (type == 'spider') || (type == 'listascontrol') || (type == 'condicioncuenta') || (type == 'dictamen')){
                var datos = RESULTADOS.computed.descargar(data.li_attr.file);
                datos.then(function (data) {
                    if(data.status == 200){
                        RESULTADOS.componets.vistaPdf(data.data.url);
                    }                    
                });                    
            }
        }).on('changed.jstree', function (e, data) {
            var path = data.instance.get_path(data.node,'/');
            if(data.node.type == 'folder'){
                GLOBAL.resultId = data.node.id;
                GLOBAL.resultPath = path;
                if($('form#form-resultados span#path-archivo').length){
                    $('form#form-resultados span#path-archivo').text(path);
                    $('form#form-resultados input[name="carpeta"]').val(data.node.id);
                }
            }
        });
    },
    arbol: function () {
        $('#resultados-content').html(`
            <div class="input-group mb-2 ml-2" style="width: 95%">
                <input type="text" class="form-control" id="resultadosTree_q" placeholder="Buscar Resultados">
                <div class="input-group-append">
                    <span class="input-group-text eraser" style="font-size: 12px !important"><i class="fas fa-eraser"></i></span>
                    <span class="input-group-text" style="font-size: 12px !important"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <div id="resultadosTree" obj="tree"></div>
        `);
    },
    borrarModal: function (e) {
        $('#ventanaModal .modal-body').html(
            `<form id="form-borrar">
                <p class="my-0">
                    ¿Esta usted seguro de borrar este contenido?<br/>- ` + e + `
                </p>
            </form>`);
    }    
}
