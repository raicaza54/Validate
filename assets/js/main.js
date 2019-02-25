/**
 * Controlador Principal
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var GLOBAL = GLOBAL || {};
GLOBAL.methods = {
    ventana: function () {

    },
    maximizar: function () {
        $('#content').addClass('position-absolute');
        $('#content').addClass('maximizar');
        $('#content').find('#maximizar').hide();
        $('#content').find('#restaurar').show();
    },
    restaurar: function () {
        $('#content').addClass('position-relative');
        $('#content').removeClass('maximizar');
        $('#content').find('#maximizar').show();
        $('#content').find('#restaurar').hide();        
    },
    
}
GLOBAL.componets = {
    ventanaModal: function () {
        $('body').append(``);
    }
}