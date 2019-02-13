/**
 * Empresas - jQuery plugin 0.0.1
 *
 * Copyright  (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author    GEO INFORMATIC SOLUTIONS SAS
 * @author    Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version   0.0.1
 */
$(document).ready(function () {
    $.ajax({
        url: '/empresas/v1/datos',  
        type: "POST",
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            console.log("Hola mundo");
            //console.log(data);
            //viewModel.vendors(data.result);
        }
    });
});