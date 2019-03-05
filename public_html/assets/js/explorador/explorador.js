/**
 * Explorador - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var EXPLORADOR = EXPLORADOR || {};
EXPLORADOR.methods = {
    crearCarpeta: function () {
        var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        sel = ref.create_node(sel, {"type":"folder"});
        if(sel) {
            ref.edit(sel);
        }        
    },
    crearProcesar: function(carpeta, parent) {
        return $.ajax({
            url: '/explorador/v1/crear',
            type: "POST",
            dataType: 'json',
            data:{
                carpeta: carpeta, 
                parent_id: parent
            },
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });        
    },
    editarCarpeta: function () {
        var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        ref.edit(sel);        
    },
    editarProcesar: function(carpeta, id, parent) {
        return $.ajax({
            url: '/explorador/v1/editar',
            type: "POST",
            dataType: 'json',
            data:{
                carpeta: carpeta, 
                id: id,
                parent_id: parent
            },
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });
    },
    borrarCarpeta: function () {
        var ventana = $('#ventanaModal');
        ventana.find('div.modal-dialog').removeClass('modal-lg').addClass('modal-sm');
        ventana.find('.modal-title').text('Explorador de Carpetas');
        ventana.find('.btn-primary').show();
        ventana.find('.btn-primary').text('Eliminar');
        ventana.find('.btn-primary').attr('onclick','');
        ventana.find('.btn-secondary').text('Cancelar');
        EXPLORADOR.componets.borrarModal();
        ventana.modal('show');
    },
    cargaCarpetas: function (id) {
        return $.ajax({
            url: '/explorador/v1/carpetas',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });
    },
    listarCarpetas: function (id) {
        var datos = EXPLORADOR.methods.cargaCarpetas(id);
        datos.then(function (r) {
            EXPLORADOR.componets.arbol();
            return r;
        }).then(function (r) {
            EXPLORADOR.componets.arbolPoblar(r['data']);
        });
    }
}
//https://desarrolloweb.com/articulos/upload-archivos-ajax-jquery.html
EXPLORADOR.componets = {
    limpiarContent: function () {
        $('#content').html(
                `<div class="selec-empresa text-center text-muted small no-seleccionable">
                Debe Seleccionar<br/>una empresa
            </div>`);
        $.toast({
            text: 'Mensaje',
            position: 'bottom-left',
            stack: false,
            allowToastClose: false,
            loader: false,
        });
    },
    arbolPoblar: function (datos) {
        $('#carpetasTree').jstree({
            'core': {
                'themes': {
                    'responsive': false,
                },
                multiple: false,
                data: datos,
                check_callback: true,
            },
            'types': {
                'default': {'icon': 'far fa-file'},
                'folder':  {'icon': 'far fa-folder'},
                'excel':   {'icon': 'far fa-file-excel'},
                'word':    {'icon': 'far fa-file-word'},
                'pdf':     {'icon': 'far fa-file-pdf'},
                'img':     {'icon': 'far fa-file-image'}
            },
            'plugins': ['types','dnd']
        }).on('create_node.jstree', function (e, data) {
            var datos = EXPLORADOR.methods.crearProcesar(data.node.text, data.node.parent);
            datos.then(function (r) {
                data.instance.set_id(data.node, r['data']['id']);
            });
        }).on('move_node.jstree', function (e, data) {
            EXPLORADOR.methods.editarProcesar(data.node.text, data.node.id, data.parent);
        }).on('rename_node.jstree', function (e, data) {
            EXPLORADOR.methods.editarProcesar(data.node.text, data.node.id, data.node.parent);
        }).on('changed.jstree', function (e, data) {
            var path = data.instance.get_path(data.node,'/');
            var type = data.node.type;
            GLOBAL.folderId = data.node.id;
            GLOBAL.folderPath = path;
            if($('span#path-archivo').length){
               $('span#path-archivo').text(path); 
            }
            if((type == 'excel') || (type == 'word') || (type == 'pdf') || (type == 'img')){
                ARCHIVOS.methods.listarDatos(data.node.id);
            }
        });
    },
    arbol: function () {
        $('#explorador-content').html(`<div id="carpetasTree" obj="tree"></div>`);
    },
    borrarModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-explorador">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed bibendum tellus. 
                    Vestibulum eu lorem at nisl venenatis dictum nec vel nisi. Aenean fermentum sit amet erat feugiat volutpat. 
                    Phasellus nec dui et ex porta gravida.
                </p>
            </form>`);
    }    
}
