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
GLOBAL.empresaId = null;
GLOBAL.folderId = null;
GLOBAL.folderPath = 'Debe seleccionar un destino';
GLOBAL.methods = {
    secure: function() {
        $.ajaxSetup({data: {'A4d6ebb02e86d4': Cookies.get('A4d6ebb02e86d4')}});
    },
    ventana: function () {

    },
    billboard: function () {
        BENFORD.chart.resize();
    },
    spider: function() {
        var outerContent = $('#content').find('.scrollTable');
        var innerContent = $('#content').find('.scrollTable #body-spider');
        outerContent.scrollLeft( (innerContent.width() - outerContent.width()) / 2);
        outerContent.scrollTop( (innerContent.height() - outerContent.height()) / 2);
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
    },
    toast: function(mensaje, heading = 'default') {
        var bgColor = '';
        switch (heading) {
            case 'info':
                bgColor = '#1e88e5';
                break;
            case 'success':
                bgColor = '#43a047';
                break;
            case 'error':
                bgColor = '#e53935';
                break;
            default:
                bgColor = '#444';
                break;
        }
        $.toast({
            text: mensaje,
            position: 'bottom-left',
            stack: false,
            allowToastClose: false,
            loader: false,
            bgColor: bgColor,
        });        
    }        
}
GLOBAL.componets = {
    ventanaModal: function () {
        $('body').append(``);
    }
}