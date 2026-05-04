/**
 * Controlador Loader cargador de archivos
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
$(document).ready(function () {
    includeFiles(`${base_url}/assets/js/main.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/plantilla/sidebar.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/resultados/resultados.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/archivos/archivos.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/empresas/empresas.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/explorador/explorador.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/benford/benford.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/spider/spider.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/manipulacion/manipulacion.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/materialidad/materialidad.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/perfil/perfil.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/perfil/comprar.js?v=${version}`);    
    includeFiles(`${base_url}/assets/js/listascontrol/listascontrol.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/condicion/condicion.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/ayuda/ayuda.js?v=${version}`);
    includeFiles(`${base_url}/assets/js/dictamen/dictamen.js?v=${version}`);
        $('#content').on('click', '#menu-tab a', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#content').on('change', 'input[type="file"]', function(){
        $('#content').find('label[for="archivo"]').text(this.value.split("\\").pop());
    });
    $('#exploradorContent').on('click', 'span.eraser', function () {
        $(this).parents('.input-group').find('input[id$=_q]').val('');
        $('#' + $(this).parents('.input-group').siblings('div.jstree').attr('id')).jstree(true).search('');
    });
    $("<iframe>", {
        name    : "AjaxDownloaderIFrame"
    }).hide().appendTo("body");
    $("body").mLoading('hide');
//    $('#support').text($.fullscreen.isNativelySupported() ? 'supports' : 'doesn\'t support');
//    $('#content').on('click','#requestfullscreen', function() {
//        $('#content').fullscreen();
//        $('#content').find('li#maximizar').hide();
//        $('#content').find('li#restaurar').show();
//        return false;
//    });
//    $('#content').on('click','#exitfullscreen', function() {
//        $.fullscreen.exit();
//        $('#content').find('li#maximizar').show();
//        $('#content').find('li#restaurar').hide();        
//        return false;
//    });
    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
}).ajaxError(function( event, jqxhr, settings, thrownError ) {
    $("body").mLoading('hide');
    GLOBAL.computed.toast('Algo no anda bien');
    GLOBAL.computed.secure();
});
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        decimal: ',',
        thousands: '.',
        info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
        infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
        infoPostFix: '',
        infoFiltered: '(filtrado de un total de _MAX_ registros)',
        loadingRecords: 'Cargando...',
        lengthMenu: 'Mostrar _MENU_ registros',
        paginate: {
            first: 'Primero',
            last: 'Último',
            next: 'Siguiente',
            previous: 'Anterior'
        },
        processing: 'Procesando...',
        search: 'Buscar:',
        searchPlaceholder: 'Término de búsqueda',
        zeroRecords: 'No se encontraron resultados',
        emptyTable: 'Ningún dato disponible en esta tabla',
        aria: {
            sortAscending: ': Activar para ordenar la columna de manera ascendente',
            sortDescending: ': Activar para ordenar la columna de manera descendente'
        },
        //only works for built-in buttons, not for custom buttons
        buttons: {
            create: 'Nuevo',
            edit: 'Cambiar',
            remove: 'Borrar',
            copy: 'Copiar',
            csv: 'fichero CSV',
            excel: 'tabla Excel',
            pdf: 'documento PDF',
            print: 'Imprimir',
            colvis: 'Visibilidad columnas',
            collection: 'Colección',
            upload: 'Seleccione fichero....'
        },
        select: {
            rows: {
                _: '%d filas seleccionadas',
                0: 'clic fila para seleccionar',
                1: 'una fila seleccionada'
            }
        }
    }
});
function includeFiles(path) {
    var deferred = $.Deferred();
    setTimeout(function() {
        var script = document.createElement('script');
        script.src = path;
        document.getElementsByTagName('head')[0].appendChild(script);        
        deferred.resolve(); 
    }, 50);
    return deferred.promise();
}
(function ($) {
    $.utils = {
        // http://stackoverflow.com/a/8809472
        createUUID: function ()
        {
            var d = new Date().getTime();
            if (window.performance && typeof window.performance.now === "function")
            {
                d += performance.now(); //use high-precision timer if available
            }
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c)
            {
                var r = (d + Math.random() * 16) % 16 | 0;
                d = Math.floor(d / 16);
                return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
            });
            return uuid;
        }
    }

    $.fn.dialogue = function (options) {
        var defaults = {
            title: "", 
            content: $("<p />"),
            closeButton: false, 
            closeIcon: false,
            id: $.utils.createUUID(), 
            open: function () { }, 
            sizeDialogue: "",
            buttons: []
        };
        var settings = $.extend(true, {}, defaults, options);

        // create the DOM structure
        var $modal = $("<div />").attr("id", settings.id).attr("role", "dialog").addClass("modal fade")
                .append($("<div />").addClass("modal-dialog modal-dialog-centered").addClass(settings.sizeDialogue)
                        .append(
                                $("<div />").addClass("modal-content")
                                .append(
                                        $("<div />").addClass("modal-header").append($("<h5 />").addClass("modal-title").text(settings.title))
                                        )
                                .append($("<div />").addClass("modal-body").append($("<p />").addClass('my-0').append(settings.content)))
                                .append($("<div />").addClass("modal-footer"))
                                )
                        );
        $modal.shown = false;
        $modal.dismiss = function () {
            // loop until its shown
            // this is only because you can do $.fn.alert("utils.js makes this so easy!").dismiss(); in which case it will try to remove it before its finished rendering
            if (!$modal.shown)
            {
                window.setTimeout(function ()
                {
                    $modal.dismiss();
                }, 50);
                return;
            }

            // hide the dialogue
            $modal.modal("hide");
            // remove the blanking
            $modal.prev().remove();
            // remove the dialogue
            $modal.empty().remove();

            $("body").removeClass("modal-open");
        }

        if (settings.closeIcon)
            $modal.find(".modal-header").append($("<button />").attr("type", "button").addClass("close").html("&times;").click(function () {
                $modal.dismiss()
            }));

        // add the buttons
        var $footer = $modal.find(".modal-footer");
        for (var i = 0; i < settings.buttons.length; i++)
        {
            (function (btn)
            {
                $footer.prepend($("<button />").addClass("btn " + (btn.css ? btn.css : 'btn-default'))
                        .attr("id", btn.id)
                        .attr("type", "button")
                        .text(btn.text)
                        .attr("onclick", btn.click))
            })(settings.buttons[i]);
        }

        if (settings.closeButton)
            $modal.find(".modal-footer").prepend($("<button />").attr("type", "button").addClass("btn btn-link").html("Cancelar").click(function () {
                $modal.dismiss()
            }));

        settings.open($modal);

        $modal.on('shown.bs.modal', function (e) {
            $modal.shown = true;
        });
        // show the dialogue
        $modal.modal("show");

        return $modal;
    };
})(jQuery);