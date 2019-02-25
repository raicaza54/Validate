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
    includeFiles('http://auditoria.local/assets/js/main.js');
    includeFiles('http://auditoria.local/assets/js/plantilla/sidebar.js');
    includeFiles('http://auditoria.local/assets/js/empresas/empresas.js');
    includeFiles('http://auditoria.local/assets/js/explorador/explorador.js');
    includeFiles('http://auditoria.local/assets/js/archivos/archivos.js');
    includeFiles('http://auditoria.local/assets/js/benford/benford.js');
    includeFiles('http://auditoria.local/assets/js/spider/spider.js');
    $('#content').on('click', '#menu-tab a', function (e) {
        e.preventDefault()
        $(this).tab('show')
    });
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
});
function includeFiles(path) {
    var script = document.createElement('script');
    script.src = path;
    document.getElementsByTagName('head')[0].appendChild(script);    
}