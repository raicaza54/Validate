/**
 * Controlador Main
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
$(document).ready(function () {
    includeFiles('http://auditoria.local/assets/js/plantilla/sidebar.js');
    includeFiles('http://auditoria.local/assets/js/empresas/main.js');
    includeFiles('http://auditoria.local/assets/js/explorador/main.js');
    includeFiles('http://auditoria.local/assets/js/archivos/main.js');
});
function includeFiles(path) {
    var script = document.createElement('script');
    script.src = path;
    document.getElementsByTagName('head')[0].appendChild(script);    
}