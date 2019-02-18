/**
 * Archivos - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var ARCHIVOS = ARCHIVOS || {};
/*
$(document).on('click', '.jstree-anchor', function(e) {
    var anchorId = $(this).parent().attr('id');
    var clickId = anchorId.substring(anchorId.indexOf('_') + 1, anchorId.length);
    ARCHIVOS.methods.listarDatos();
});
*/

$("#explorador-content").on("click",".jstree-clicked", function (e) {
        var nodeSelect = $(this).jstree('get_selected', true);
	var node = nodeSelect[0];
	if(node.type == 'file'){
		ARCHIVOS.methods.listarDatos(node.id);
	}
});
ARCHIVOS.methods = {
    cargaDatos: function (id) {
        return $.ajax({
            url: '/archivos/v1/datos',
            type: "POST",
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            }
        });
    },
    listarDatos: function (id) {
        var datos = ARCHIVOS.methods.cargaDatos(id);
        datos.then(function (r) {
            ARCHIVOS.componets.tabs();
            return r;
        }).then(function (r) {
            ARCHIVOS.componets.tablaPoblar(r);
        });
    }
}
ARCHIVOS.componets = {
    limpiarContent: function () {
        $('#content').html(
                `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>            
            </div>`
                );
        $.toast({
            text: 'Se ha seleccionado la empresa exitosamente, ahora carga o selecciona un archivo',
            position: 'bottom-left',
            stack: false,
            allowToastClose: false,
            loader: false,
        });
    },
    tablaPoblar: function (datos) {
        $.each(datos['data'], function (key, value) {
            $('#table-archivo tbody').append(
                `<tr>
                    <td>` + value['campo1'] + `</td>
                    <td>` + value['campo2'] + `</td>
                    <td>` + value['campo3'] + `</td>
                    <td>` + value['campo4'] + `</td>
                    <td>` + value['campo5'] + `</td>
                    <td>` + value['campo6'] + `</td>
                    <td>` + value['campo7'] + `</td>
                    <td>` + value['campo8'] + `</td>
                    <td>` + value['campo9'] + `</td>
                    <td>` + value['campo10'] + `</td>
                    <td>` + value['campo11'] + `</td>
                    <td>` + value['campo12'] + `</td>
                    <td>` + value['campo13'] + `</td>
                </tr>`);
        });
    },
    tabs: function () {
        $('#content').html(
                `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-archivo-tab" data-toggle="tab" href="#nav-archivo" role="tab" aria-controls="nav-archivo" aria-selected="true">Archivo</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-archivo" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-archivo">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                        </ul>
                        <div class="scrollTable">
                        <table id="table-archivo" class="display table table-bordered table-hover table-sm table-striped">
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>`);
    }
}
