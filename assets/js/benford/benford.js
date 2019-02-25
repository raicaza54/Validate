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
var BENFORD = BENFORD || {};
BENFORD.archivoId = 0;
BENFORD.methods = {
    parametros: function () {
        var ventana = $('#ventanaModal');
        var idArchivo = $('[name="archivoId"]').val();
        ventana.find('.modal-title').text('Ley de Benford');
        ventana.find('.btn-primary').show();
        ventana.find('.btn-primary').text('Procesar');
        ventana.find('.btn-primary').attr('onclick','BENFORD.methods.procesar(1)');
        ventana.find('.btn-secondary').text('Cancelar');
        BENFORD.componets.parametrosModal();
        BENFORD.archivoId = idArchivo;
        var datos = BENFORD.methods.cargaDatos(idArchivo);
        datos.then(function (data) {
            BENFORD.componets.selectPoblar(data);
        }).then(function () {
            ventana.modal('show');
        });
    },
    cargaDatos: function (id) {
        return $.ajax({
            url: '/benford/v1/encabezado',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            }
        });
    },
    tablaBenford: function(digito) {
        var form_data = $('form#form-benford').serializeArray();
        form_data.push({ name: "archivoIdProcesar", value: BENFORD.archivoId });
        form_data.push({ name: "digito", value: digito });
        return $.ajax({
            url: '/benford/v1/procesar',
            type: "POST",
            dataType: 'json',
            data: {form: form_data},
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });        
    },
    procesar: function (digito) {
        if (!$('#content').find('div#pills-d' + digito + ' div#BenfordChartd' + digito + '.bb').length ) {
            var datos = BENFORD.methods.tablaBenford(digito);
            datos.then(function (data) {
                $('#ventanaModal').modal('hide');
                BENFORD.componets.graficaBenford();
                $('[name="archivoId"]').val(BENFORD.archivoId);
                var data1 = data['data']['d' + digito]['grafica']['data1'];
                var data2 = data['data']['d' + digito]['grafica']['data2'];
                var x1 = data['data']['d' + digito]['x1'];
                var x2 = data['data']['d' + digito]['x2'];
                var chart = bb.generate({
                    data: {
                        xs: {
                          data1: "x1",
                          data2: "x2"
                        },                    
                        columns: [
                            x1,
                            x2,
                            data1,
                            data2,
                        ],
                        type: "bar",
                        types: {
                            data1: "bar",
                            data2: "line",
                        },
                        names: {
                          data1: "Recuento",
                          data2: "Ley de Benford"
                        },
                        onclick:function(d) {
                            var grafica = $('a.nav-link[id^="bpills-d"].active').data('digito');
                            ARCHIVOS.methods.filtroDigito(d, BENFORD.archivoId, grafica);
                        }
                    },
                    bindto: "#BenfordChartd" + digito
                });
                $.each(data['data']['d' + digito]['tabla'], function (key, value) {
                    $('#table-benfordD' + digito + ' tbody').append(
                        `<tr>
                            <td class="text-right">` + value['numero'] + `</td>
                            <td class="text-right">` + value['frecuencia'] + `</td>
                            <td class="text-right">` + value['observado'] + `</td>
                            <td class="text-right">` + value['benford'] + `</td>
                            <td class="text-right">` + value['variacion'] + `</td>
                        </tr>`);
                });
                $('p#madD' + digito).html(
                        `<label class="mb-0">
                            Desviación Absoluta Media
                        </label><br/>
                        EN ESTE CASO NOS DA ` + data['data']['d' + digito]['mad'] + ' QUE VIENDOLO EN LA TABLA ES ' + data['data']['d' + digito]['madDescribe']
                );
                if (!$('#content').find('div#form-benford form input').length) {
                    $.each(data['data']['form'], function (key, value) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: key,
                            value: value
                        }).appendTo('div#form-benford form');
                    });
                }
            });
        }
    }
}
BENFORD.componets = {
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
    graficaBenford: function() {
        if (!$('#content').find('nav#tabs-benford').length ) {
            $('#content').html(`
                <nav id="tabs-benford">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-benford-tab" data-toggle="tab" href="#nav-benford" role="tab" aria-controls="nav-benford" aria-selected="true">Ley de Benford</a>
                    </div>
                </nav>
                <div id="form-benford"><form id="form-benford"></form></div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active clearfix" id="nav-benford" role="tabpanel" aria-labelledby="nav-benford-tab">
                        <div id="body-archivo">
                            <ul class="nav position-relative" id="menu-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" onclick="BENFORD.methods.procesar(1)" id="bpills-d1-tab" data-digito="1" data-toggle="pill" href="#bpills-d1" role="tab" aria-controls="bpills-d1" aria-selected="true">
                                        <b class="digito-benford">1</b>23
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="BENFORD.methods.procesar(2)" id="bpills-d2-tab" data-digito="2" data-toggle="pill" href="#bpills-d2" role="tab" aria-controls="bpills-d2" aria-selected="true">
                                        1<b class="digito-benford">2</b>3
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="BENFORD.methods.procesar(12)" id="bpills-d12-tab" data-digito="12" data-toggle="pill" href="#bpills-d12" role="tab" aria-controls="bpills-d12" aria-selected="true">
                                        <b class="digito-benford">12</b>3
                                    </a>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                    <a id="requestfullscreen" class="nav-link" href="#!" onclick="GLOBAL.methods.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</a>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                    <a id="exitfullscreen" class="nav-link" href="#" onclick="GLOBAL.methods.restaurar()"><i class="far fa-window-restore"></i> Restaurar</a>
                                </li>            
                            </ul>
                            <div class="scrollTable">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="bpills-d1" role="tabpanel" aria-labelledby="bpills-d1-tab">
                                        <div id="BenfordChartd1"></div>
                                        <p id="madD1" class="text-uppercase mt-3"></p>
                                        <table id="table-benfordD1" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>N&uacute;mero</th>
                                                    <th>Frecuencia</th>
                                                    <th>Observado</th>
                                                    <th>Ley de Benford</th>
                                                    <th>Variaci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade show" id="bpills-d2" role="tabpanel" aria-labelledby="bpills-d2-tab">
                                        <div id="BenfordChartd2"></div>
                                        <p id="madD2" class="text-uppercase mt-3"></p>
                                        <table id="table-benfordD2" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>N&uacute;mero</th>
                                                    <th>Frecuencia</th>
                                                    <th>Observado</th>
                                                    <th>Ley de Benford</th>
                                                    <th>Variaci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade show" id="bpills-d12" role="tabpanel" aria-labelledby="bpills-d12-tab">
                                        <div id="BenfordChartd12" class="text-uppercase mt-3"></div>
                                        <p id="madD12" class="text-uppercase mt-3"></p>
                                        <table id="table-benfordD12" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>N&uacute;mero</th>
                                                    <th>Frecuencia</th>
                                                    <th>Observado</th>
                                                    <th>Ley de Benford</th>
                                                    <th>Variaci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>
                </div>`);
        }
    },
    selectPoblar: function (datos) {
        $.each(datos['data'], function (key, value) {
            $('#campoAnalizar').append($('<option>', { 
                value: value,
                text : value
            }));
        });
    },
    parametrosModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford">
                <div class="form-group col-md-12">
                    <label for="campoAnalizar">Campo a analizar:&nbsp;&nbsp;</label>
                    <select id="campoAnalizar" name="campoAnalizar" class="form-control col"></select>
                </div>
                <div class="form-group col-md-12">
                    <label>Incluir Valores</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="incluirValores" id="incluirValores1" value="positivos" checked>
                        <label class="form-check-label" for="incluirValores1">
                            Positivos
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="incluirValores" id="incluirValores2" value="negativos">
                        <label class="form-check-label" for="incluirValores2">
                            Negativos
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="resultado">Resultado</label>
                    <div id="resultado" class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" name="resultado" value="1" aria-label="Inlcuir un archivo de resultados">
                            </div>
                        </div>
                        <input type="text" class="form-control" name="resultadoNombre" aria-label="Nombre para el archivo de resultados">
                    </div>                                    
                </div>                                    
            </form>`);
    }
}
