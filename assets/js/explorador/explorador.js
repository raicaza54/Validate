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
    cargaCarpetas: function () {
        return $.ajax({
            url: '/explorador/v1/carpetas',
            type: "GET",
            dataType: 'json',
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            }
        });
    },
    listarCarpetas: function () {
        var datos = EXPLORADOR.methods.cargaCarpetas();
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
                'default': {
                    'icon': 'far fa-folder'
                },
                'file': {
                    'icon': 'far fa-file-excel'
                }
            },
            'plugins': ['types']
        });
    },
    arbol: function () {
        $('#explorador-content').html(`<div id="carpetasTree" obj="tree"></div>`);
    }
}
