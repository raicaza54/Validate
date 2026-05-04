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
$("body").mLoading();
$(document).ready(function () {
    includeFiles(base_url + '/assets/js/main.js?v=' + version);
    includeFiles(base_url + '/assets/js/plantilla/sidebar.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/actualizaciones.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/clientes.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/contratos.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/mantenimiento.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/servicios.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/tablero.js?v=' + version);
    includeFiles(base_url + '/assets/js/admin/usuarios.js?v=' + version);
    $('#content').on('click', '#menu-tab a', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#content').on('change', 'input[type="file"]', function(){
        $('#content').find('label[for="archivo"]').text(this.value.split("\\").pop());
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
}).ajaxError(function( event, jqxhr, settings, thrownError ) {
    $("body").mLoading('hide');
    GLOBAL.computed.toast('Algo no anda bien');
    GLOBAL.computed.secure();
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