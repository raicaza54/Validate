/**
 * Explorador - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-02-13
 */
var EXPLORADOR = EXPLORADOR || {};
EXPLORADOR.methods = {
    crearCarpeta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(!GLOBAL.computed.isNull(GLOBAL.empresaId)){
                var ref = $('#carpetasTree').jstree(true);
                if(!GLOBAL.computed.isNull(GLOBAL.folderId)){
                    var sel = ref.create_node(GLOBAL.folderId, {
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
            var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
            if(!sel.length) { return false; }
            sel = sel[0];
            ref.edit(sel);
        }
    },
    borrarCarpeta: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(`Debe seleccionar un elemento para eliminarlo`, 400);
                return;
            }
            var sel = $('#carpetasTree').jstree('get_selected', true);
            if(sel.length){
                var ventana = $('#ventanaModal');
                GLOBAL.computed.initializeModal(ventana, true, 'modal');
                ventana.find('.modal-title').text('Explorador de Carpetas');
                ventana.find('.btn-primary').show();
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);        
                ventana.find('.btn-primary').text('Eliminar');
                ventana.find('.btn-primary').attr('onclick','EXPLORADOR.methods.borrarRun()');
                ventana.find('.btn-link').text('Cancelar');
                EXPLORADOR.componets.borrarModal(sel[0].text);
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
                    var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
                    if(!sel.length) { return false; }
                    ref.delete_node(sel);
                });
            }
        }
    },
    listarCarpetas: function (obj, id) {
        var deferred = $.Deferred();
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(`Debe seleccionar una empresa para poder explorar sus carpetas`, 400);
                return;
            }
            var datos = EXPLORADOR.computed.cargaCarpetas(id);
            datos.then(function (data) {
                EXPLORADOR.componets.arbol();
                return data;
            }).then(function (data) {
                GLOBAL.folderId = null;
                GLOBAL.folderPath = 'Debe seleccionar un destino';                
                EXPLORADOR.componets.arbolPoblar(data.data);
                deferred.resolve();
            });
        }
        return deferred.promise();
    }
}
EXPLORADOR.computed = {
    crearProcesar: function(label, parent, id, type = 'folder', archivos_id = 0, disabled = 0) {
        return $.ajax({
            url: '/explorador/v1/crear',
            type: "POST",
            dataType: 'json',
            data:{
                label: label,
                parent_id: parent,
                type: type,
                id: id,
                archivos_id: archivos_id,
                disabled: disabled,
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
    cargaCarpetas: function (id) {
        return $.ajax({
            url: '/explorador/v1/carpetas',
            type: "POST",
            dataType: 'json',
            data:{id: id},
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
    editarProcesar: function(label, id, parent, deleted_at = false) {
        return $.ajax({
            url: '/explorador/v1/editar',
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
    dblclicktr: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
            if(!sel.length) { 
                GLOBAL.computed.toast(`Debe seleccionar un archivo para abrir`, 400);
                return false; 
            }
            sel = sel[0];
            var data = ref.get_node(sel);
            var type = data.type;
            if((type == 'csv') || (type == 'excel') || (type == 'word') || (type == 'pdf') || (type == 'img')){
                ARCHIVOS.methods.listarDatos(true, data.li_attr.file, data.id);
            }            
        }
    }
}
EXPLORADOR.componets = {
    limpiarContent: function () {
        $('#content').html(
            `<div class="selec-empresa text-center text-muted small no-seleccionable">
                Debe Seleccionar<br/>una empresa
            </div>`);
    },
    limpiarExplorador: function () {
        $('#explorador-content').html(`<div class="selec-empresa text-center text-muted small no-seleccionable">
                Debe Seleccionar<br/>una empresa
            </div>`);
    },
    arbolPoblar: function (datos) {
        $('#carpetasTree').jstree({
            'core': {
                'animation' : 0,
                'themes': {
                    'responsive': false,
                },
                multiple: false,
                deselect_all: false,
                data: datos,
                check_callback: function(operation, node, node_parent, node_position, more) {
                    if (operation === "move_node") {
                        return node_parent.original.type === "folder";
                    }
                    return true;
                }
            },
            'types': {
                'default': {'icon': 'far fa-file'},
                'csv':     {'icon': 'fas fa-file-csv'},
                'folder':  {'icon': 'far fa-folder'},
                'excel':   {'icon': 'far fa-file-excel'},
                'word':    {'icon': 'far fa-file-word'},
                'pdf':     {'icon': 'far fa-file-pdf'},
                'img':     {'icon': 'far fa-file-image'}
            },
            'dnd': {
                check_while_dragging: true
            },
            'search': {
                show_only_matches: true,
            },
            plugins: ['types','dnd','multiselect','search']
        });
        var to = false;
        $('#carpetasTree_q').keyup(function () {
            if (to) {
                clearTimeout(to);
            }
            to = setTimeout(function () {
                var v = $('#carpetasTree_q').val();
                $('#carpetasTree').jstree(true).search(v);
            }, 250);
        });        
        $('#carpetasTree').on('create_node.jstree', function (e, data) {
            var archivos_id = 0;
            var disabled = 0;
            if($.inArray(data.node.type, ['csv','excel']) >= 0){
                archivos_id = data.node.id;
            }
            if(GLOBAL.computed.array_key_exists('disabled', data.node.original)){
                disabled = data.node.original.disabled;
            }
            $.when().then(function () {
                EXPLORADOR.computed.crearProcesar(data.node.text, data.node.parent, data.node.id, data.node.type, archivos_id, disabled);
            }).then(function () {
                data.node.li_attr.file = archivos_id;
                if(disabled == 1){
                    $("#carpetasTree").jstree().disable_node(data.node);
                }
            });
        }).on('delete_node.jstree', function (e, data) {
            if(data.node.li_attr.file == GLOBAL.archivoId){
                ARCHIVOS.componets.limpiarContent();
            }
            EXPLORADOR.computed.editarProcesar(data.node.text, data.node.id, data.parent, true);
        }).on('move_node.jstree', function (e, data) {
            EXPLORADOR.computed.editarProcesar(data.node.text, data.node.id, data.parent);
        }).on('rename_node.jstree', function (e, data) {
            EXPLORADOR.computed.editarProcesar(data.node.text, data.node.id, data.node.parent);
        }).on('dblclick.jstree', function (e) {
            var tree = $(this).jstree();
            var data = tree.get_node(e.target);
            var type = data.type;
            if((type == 'csv') || (type == 'excel') || (type == 'word') || (type == 'pdf') || (type == 'img')){
                if(data.state.disabled == false){
                    ARCHIVOS.methods.listarDatos(true, data.li_attr.file, data.id);
                }else if(data.state.disabled == true){
                    ARCHIVOS.computed.comprobarDatos(true, data.li_attr.file, data.id).then(function (data) {
                        if(data.data.refresh == 1){
                            GLOBAL.computed.toast('El archivo fue eliminado el mismo no pudo ser cargado, esta corrupto o no tiene un formato adecuado, por favor verificar el archivo e intentarlo nuevamente');
                            EXPLORADOR.methods.listarCarpetas(this, GLOBAL.empresaId);
                        }else if(data.data.refresh == 0){
                            GLOBAL.computed.toast('El archivo se encuentra en procesamiento de carga, espere un momento por favor');
                        }
                    });
                }
            }
        }).on('changed.jstree', function (e, data) {
            var path = data.instance.get_path(data.node,'/');
            if(data.node.type == 'folder'){
                GLOBAL.folderId = data.node.id;
                GLOBAL.folderPath = path;
                if($('span#path-archivo').length){
                    $('span#path-archivo').text(path);
                    $('input[name="carpeta"]').val(data.node.id);
                }
            }
        });
    },
    arbol: function () {
        $('#explorador-content').html(`
            <div class="input-group mb-2 ml-2" style="width: 95%">
                <input type="text" class="form-control" id="carpetasTree_q" placeholder="Buscar Archivos">
                <div class="input-group-append">
                    <span class="input-group-text eraser" style="font-size: 12px !important"><i class="fas fa-eraser"></i></span>
                    <span class="input-group-text" style="font-size: 12px !important"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <div id="carpetasTree" obj="tree"></div>
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
