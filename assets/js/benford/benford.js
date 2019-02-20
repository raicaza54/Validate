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
BENFORD.methods = {
    parametros: function () {
        var ventana = $('#ventanaModal');
        var idArchivo = $('[name="archivoId"]').val();
        ventana.find('.modal-title').text('Ley de Benford');
        ventana.find('.btn-primary').text('Procesar');
        ventana.find('.btn-primary').attr('onclick','BENFORD.methods.procesar()');
        BENFORD.componets.bodyModal();
        var datos = BENFORD.methods.cargaDatos(idArchivo);
        datos.then(function (data) {
            BENFORD.componets.selectPoblar(data);
            ventana.find('.modal-body #archivoIdProcesar').val(idArchivo);
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
    tablaBenford: function() {
        var form_data = $('#form-benford').serializeArray();
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
    procesar: function () {
        var datos = BENFORD.methods.tablaBenford();
        datos.then(function (data) {
            $('#ventanaModal').modal('hide');
            BENFORD.componets.graficaBenford();
            var data1 = data['data']['grafica']['data1'];
            var data2 = data['data']['grafica']['data2'];
            var chart = bb.generate({
                data: {
                    xs: {
                      data1: "x1",
                      data2: "x2"
                    },                    
                    columns: [
                        ["x1", 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        ["x2", 1, 2, 3, 4, 5, 6, 7, 8, 9],
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
                    }                    
                },
                bindto: "#BenfordChart"
            });            
        });
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
        $('#content').html(`
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-benford-tab" data-toggle="tab" href="#nav-benford" role="tab" aria-controls="nav-benford" aria-selected="true">Ley de Benford</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-benford" role="tabpanel" aria-labelledby="nav-benford-tab">
                    <div id="body-archivo">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                        </ul>
                        <div class="scrollTable"><div id="BenfordChart"></div></div>
                    </div>
                </div>
            </div>`);
    },
    selectPoblar: function (datos) {
        $.each(datos['data'], function (key, value) {
            $('#campoAnalizar').append($('<option>', { 
                value: value,
                text : value
            }));
        });
    },
    bodyModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford">
                <input type="hidden" value="" id="archivoIdProcesar" name="archivoIdProcesar">
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
