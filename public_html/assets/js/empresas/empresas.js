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
EMPRESAS.methods = {
    cargaDatos: function () {
        return $.ajax({
            url: '/empresas/v1/datos',
            type: "GET",
            dataType: 'json',
            beforeSend: function(jqXHR, settings) {
                
            },
            success: function (data, textStatus) {
                GLOBAL.methods.secure();
            },
            error: function (qXHR, textStatus) {
                GLOBAL.methods.secure();
            }
        });
    },
    listarEmpresas: function() {
        var datos = EMPRESAS.methods.cargaDatos();
        datos.then(function (r) {
            EMPRESAS.componets.tabs();
            return r;
        }).then(function (r) {
            EMPRESAS.componets.tablaPoblar(r);
        });        
    },
    activarEmpresa: function() {
        var id = $('input[name="customRadio"]:checked').val();
        GLOBAL.empresaId = id;
        if($.isNumeric(id)){
            $.ajax({
                url: '/empresas/v1/activar',
                type: "POST",
                dataType: 'json',
                data:{id: id},
                success: function (data) {
                    var r = data['data'];
                    $('#empresaActiva').html(
                    `<label><b>` + r['nombre'] + `</b></label>
                    <label>NIT: ` + r['identificacion'] + `</label>
                    <label>Contacto: ` + r['persona'] + `</label>
                    <label>Tlf.: ` + r['persona_tlfs'] + `</label>`);
                    GLOBAL.methods.secure();
                    EXPLORADOR.methods.listarCarpetas(id);
                    EMPRESAS.componets.limpiarContent();
                }
            });
        }
    }
}
EMPRESAS.componets = {
    limpiarContent: function() {
        $('#content').html(
            `<div style="height: calc(100vh - 135px);">
                <div class="selec-empresa text-center text-muted small no-seleccionable">
                    Debe Seleccionar<br/>un archivo
                </div>            
            </div>`
        );
        /*
        $.toast({
            text: 'Se ha seleccionado la empresa exitosamente, ahora carga o selecciona un archivo',
            position: 'bottom-left',
            stack: false,
            allowToastClose: false,
            loader: false,
        });
        */
    },
    tablaPoblar: function (datos) {
        $.each(datos['data'], function (key, value) {
            $('#table-empresas tbody').append(
                `<tr>
                    <td>
                        <div class="custom-control custom-radio">
                          <input type="radio" name="customRadio" class="custom-control-input" id="customRadio` + value['id'] + `" value="` + value['id'] + `">
                          <label class="custom-control-label" for="customRadio` + value['id'] + `">` + value['nombre'] + `</label>
                        </div>            
                    </td>
                    <td>` + value['identificacion'] + `</td>
                </tr>`);
        });        
    },
    tabs: function () {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-empresas-tab" data-toggle="tab" href="#nav-empresas" role="tab" aria-controls="nav-empresas" aria-selected="true">Empresas</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-empresas" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-empresas">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link" onclick="EMPRESAS.methods.activarEmpresa()" href="#"><i class="far fa-check-square"></i> Seleccionar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-pen"></i> Editar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-trash"></i> Eliminar</a>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable">
                        <table id="table-empresas" class="display table table-bordered table-hover table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>NIT</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>`);
    }
}
EMPRESAS.methods.listarEmpresas();
