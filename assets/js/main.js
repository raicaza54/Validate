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
    billboard: function () {
        BENFORD.chart.resize();
    },
    maximizar: function (callback) {
        $('#content').addClass('position-absolute maximizar');
        $('#content').find('#maximizar').hide();
        $('#content').find('#restaurar').show();
        if(typeof callback === 'function'){
            callback();
        }
    },
    restaurar: function (callback) {
        $('#content').removeClass('position-relative maximizar');
        $('#content').find('#restaurar').hide();
        $('#content').find('#maximizar').show();
        if(typeof callback === 'function'){
            callback();
        }
    }
}
GLOBAL.componets = {
    ventanaModal: function () {
        $('body').append(``);
    }
}