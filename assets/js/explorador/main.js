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
EXPLORADOR.main = {
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
        var datos = EXPLORADOR.main.cargaCarpetas();
        datos.then(function (r) {
            EXPLORADOR.componets.arbol();
            return r;
        }).then(function (r) {
            EXPLORADOR.componets.arbolPoblar(r);
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
            stack: false
        });
    },
    arbolPoblar: function (datos) {
        $.each(datos['data'], function (key, value) {
            $('#carpetasTree').append(
                `<ul obj="tree">`);
        });
        $('#carpetasTree').jstree({
            'core': {
                'themes': {
                    'responsive': false,
                },
                'multiple': false
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
        $('#simpleTree').show();
    },
    arbol: function () {
        $('#explorador-content').html(`<div id="carpetasTree" obj="tree" style="display: none;"></div>`);
        /*
        $('#explorador-content').html(
            `<div id="simpleTree" obj="tree" style="display: none;">
                <ul obj="tree">
                    <li obj="tree" data-jstree='{"opened":false}'>2019
                        <ul obj="tree">
                            <li obj="tree" data-jstree='{"opened":false}'>Febrero
                                <ul obj="tree">
                                    <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                </ul>
                            </li>
                        </ul>
                    </li>        
                </ul>
            </div>`);
            */
    }
}
