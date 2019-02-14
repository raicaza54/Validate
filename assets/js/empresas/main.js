/**
 * Empresas - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var EMPRESAS = EMPRESAS || {};
EMPRESAS.main = {
    cargaDatos: function () {
        return $.ajax({
            url: '/empresas/v1/datos',
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            }
        });
    }
}
EMPRESAS.componets = {
    listar: function (datos) {
        $('#content').html(`<table id="example" class="display table bordered" style="width:100%; display: none;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                    </tr>
                </thead>
            </table>`);
        $('#example').DataTable({
            data: datos['data'],
            language: {
                "url": "http://auditoria.local/assets/library/datatables/Spanish.json"
            },
            initComplete: function (settings, json) {
                $('#content').find('#example').show();
            },
            order: true,
            columns: [
                {data: "nombre"},
                {data: "identificacion"},
                {data: "direccion"},
            ]
        });

    }
}
var datos = EMPRESAS.main.cargaDatos();
datos.then(function (r) {
    EMPRESAS.componets.listar(r);
});
