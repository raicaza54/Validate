/**
 * Ley de Benford - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-02-13
 */
var BENFORD = BENFORD || {};
BENFORD.chart = [];
BENFORD.methods = {
    parametros: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Movimiento 
                    para realizar el analisis aplicando la Ley de Benford`, 400);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Movimiento 
                    para realizar el analisis aplicando la Ley de Benford`, 400);
                return;
            }
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            ventana.find('.modal-title').text('Ley de Benford');
            ventana.find('.btn-primary').show();
            ventana.find('#campoAnalizar').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);        
            ventana.find('.btn-primary').text('Procesar');
            ventana.find('.btn-primary').attr('onclick','BENFORD.methods.procesar(true, 1, true)');
            ventana.find('.btn-link').text('Cancelar');
            BENFORD.componets.parametrosModal();
            var datos = BENFORD.computed.cargaDatos(GLOBAL.archivoId);
            datos.then(function (data) {
                if(parseInt(data.status) == 200){
                    BENFORD.componets.selectPoblar(data);
                }
                return data;
            }).then(function (data) {
                if(parseInt(data.status) == 200){
                    ventana.modal('show');
                }
            });
            
        }
    },
    procesar: function (obj, digito, procesar = false) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            GLOBAL.computed.initializePdf('benford');
            $("body").mLoading();
            $.when({
                ejecucion: GLOBAL.computed.uniqint() 
            }).done(function (x) {
                GLOBAL.pdfBenford = x.ejecucion;
                BENFORD.computed.graficar(digito, procesar, x.ejecucion);
            });
        }
    }
}
BENFORD.computed = {
    graficar: function(digito, procesar = false, ejecucion = 0) {
        if (!($('#content').find('div#bpills-d' + digito + ' div#BenfordChartd' + digito + '.bb').length > 0) || (procesar == true)) {
            var datos = BENFORD.computed.tablaBenford(digito, ejecucion);
            datos.then(function (data) {
                BENFORD.componets.graficaBenford();
                GLOBAL.computed.maximizar();
                $('[name="archivoId"]').val(GLOBAL.archivoId);
                var data1 = data.data['d' + digito].grafica.data1;
                var data2 = data.data['d' + digito].grafica.data2;
                var x1 = data.data['d' + digito].x1;
                var x2 = data.data['d' + digito].x2;
                BENFORD.chart[digito] = bb.generate({
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
                            ARCHIVOS.methods.filtroDigito(true, d, GLOBAL.archivoId, grafica);
                        }
                    },
                    tooltip: {
                        format: {
                            title: function (d) {
                                return 'Dígito ' + d;
                            },
                            value: function (value) {
                                var format = d3.format('.3%');
                                return format(value/100);
                            }
                        }
                    },                    
                    bindto: "#BenfordChartd" + digito
                });
                $('#table-benfordD' + digito + ' tbody').html('');
                $.each(data.data['d' + digito].tabla, function (key, value) {
                    $('#table-benfordD' + digito + ' tbody').append(
                        `<tr>
                            <td class="text-right">` + value.numero + `</td>
                            <td class="text-right">` + value.frecuencia + `</td>
                            <td class="text-right">` + value.observado + `</td>
                            <td class="text-right">` + value.benford + `</td>
                            <td class="text-right">` + value.variacion + `</td>
                        </tr>`);
                });
                $('p#madD' + digito).html(
                        `<label class="mb-0">
                            Desviación Absoluta Media
                        </label><br/>
                        EN ESTE CASO NOS DA ` + data.data['d' + digito].mad + ' QUE VIENDOLO EN LA TABLA ES ' + data.data['d' + digito].madDescribe
                );
                $('#table-benford-mad' + digito + ' tbody').html('');
                $.each(data.data['d' + digito]['mad_d' + digito], function (key, value) {
                    $('#table-benford-mad' + digito + ' tbody').append(
                        `<tr>
                            <td class="text-right">` + GLOBAL.computed.number_format(value.min,4,',','.') + `</td>
                            <td class="text-right">` + ((value.max < 10000) ? GLOBAL.computed.number_format(value.max,4,',','.'):'&infin;') + `</td>
                            <td class="text-left">` + value.descripcion + `</td>
                        </tr>`);
                });
                $('#tblBad-d' + digito).html('');
                $.each(data.data['d' + digito].tblBad, function (key, value) {
                    var idd = key + digito;
                    $('#tblBad-d' + digito).append('<div class="col-sm-2" id="tblb'+ idd +'"><div>');
                    BENFORD.componets.tablasBad(value, idd, key);
                });
                if (!$('#content').find('div#form-benford form input').length) {
                    $.each(data.data.form, function (key, value) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: key,
                            value: value
                        }).appendTo('div#form-benford form');
                    });
                }
            }).then(function () {
                if(digito == 1){
                    BENFORD.computed.graficar(2, procesar, ejecucion);
                }
            }).then(function () {
                if(digito == 2){
                    BENFORD.computed.graficar(12, procesar, ejecucion);
                }
            });
        }
    },
    cargaDatos: function (id) {
        return $.ajax({
            url: '/benford/v1/encabezado',
            type: "POST",
            dataType: 'json',
            data:{
                id: id
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }            
                GLOBAL.computed.secure();
            }
        });
    },
    tablaBenford: function(digito, ejecucion) {
        var form_data = $('form#form-benford').serializeArray();
        form_data.push({ name: "archivoIdProcesar", value: GLOBAL.archivoId });
        form_data.push({ name: "digito", value: digito });
        form_data.push({ name: "ejecucion", value: ejecucion });
        if (!$('#content').find('div#bpills-d1 div#BenfordChartd1.bb').length ) {
            GLOBAL.campoBenford = $('#campoAnalizar').val();
        }
        return $.ajax({
            url: '/benford/v1/procesar',
            type: "POST",
            dataType: 'json',
            data: {
                form: form_data
            },
            beforeSend: function (xhr) {
                var ventana = $('#ventanaModal');
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                $('#ventanaModal').modal('hide');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                    var ventana = $('#ventanaModal');
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', false);
                    ventana.find('.btn-link').prop('disabled', false);
                    ventana.find('.btn-primary').html('Procesar');                                    
                }
                if(digito == 12){
                    $("body").mLoading('hide');
                }
                GLOBAL.computed.secure();
            }
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
    },
    tablasBad: function(tabla, id, digito) {
        $('#tblb' + id).html(`
            <table class="table table-bordered table-hover table-sm table-striped">
                <thead>
                    <tr><th colspan="2" class="text-center">Dígito ` + digito + `</th></tr>
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Frecuencia</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>`);
        $.each(tabla, function (key, value) {
            $('#tblb' + id + ' table tbody').append(
                `<tr>
                    <td class="text-right">` + GLOBAL.computed.number_format(value.valor,2,',','.') + `</td>
                    <td class="text-right">` + GLOBAL.computed.number_format(value.cantidad,0,',','.') + `</td>
                </tr>`);
        });
    },
    graficaBenford: function() {
        if (!$('#content').find('nav#tabs-benford').length ) {
            $('#content').html(`
                <nav id="tabs-benford">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-benford-tab" data-toggle="tab" href="#nav-benford" role="tab" aria-controls="nav-benford" aria-selected="true">Ley de Benford</a>
                        <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                    </div>
                </nav>
                <div id="form-benford"><form id="form-benford"></form></div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active clearfix" id="nav-benford" role="tabpanel" aria-labelledby="nav-benford-tab">
                        <div id="body-archivo">
                            <ul class="nav position-relative" id="menu-tab" role="tablist">
                                <li class="nav-item">
                                    <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this)">
                                        <i class="far fa-save"></i>
                                        Guardar PDF
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link btn-span" onClick="BENFORD.methods.parametros(this)">
                                        <i class="fas fa-chart-bar"></i>
                                        Ley de Benford
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="bpills-d1-tab" data-digito="1" data-toggle="pill" href="#bpills-d1" role="tab" aria-controls="bpills-d1" aria-selected="true">
                                        Primer D&iacute;gito
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bpills-d2-tab" data-digito="2" data-toggle="pill" href="#bpills-d2" role="tab" aria-controls="bpills-d2" aria-selected="true">
                                        Segundo D&iacute;gito
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bpills-d12-tab" data-digito="12" data-toggle="pill" href="#bpills-d12" role="tab" aria-controls="bpills-d12" aria-selected="true">
                                        Primero y Segundo D&iacute;gito
                                    </a>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                    <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.billboard)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                    <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.billboard)"><i class="far fa-window-restore"></i> Restaurar</span>
                                </li>            
                            </ul>
                            <div class="scrollTable">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="summernote wysiwyg summernote-addinit" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer1"></div>
                                    <div class="tab-pane fade show active" id="bpills-d1" role="tabpanel" aria-labelledby="bpills-d1-tab">
                                        <div id="BenfordChartd1"></div>
                                        <p id="madD1" class="text-uppercase mt-3"></p>
                                        <table id="table-benford-mad1" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>M&iacute;nimo</th>
                                                    <th>M&aacute;ximo</th>
                                                    <th>Descripci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
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
                                        <div id="tblBad-d1" class="row"></div>
                                    </div>
                                    <div class="tab-pane fade show" id="bpills-d2" role="tabpanel" aria-labelledby="bpills-d2-tab">
                                        <div id="BenfordChartd2"></div>
                                        <p id="madD2" class="text-uppercase mt-3"></p>
                                        <table id="table-benford-mad2" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>M&iacute;nimo</th>
                                                    <th>M&aacute;ximo</th>
                                                    <th>Descripci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>            
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
                                        <div id="tblBad-d2" class="row"></div>
                                    </div>
                                    <div class="tab-pane fade show" id="bpills-d12" role="tabpanel" aria-labelledby="bpills-d12-tab">
                                        <div id="BenfordChartd12" class="text-uppercase mt-3"></div>
                                        <p id="madD12" class="text-uppercase mt-3"></p>
                                        <table id="table-benford-mad12" class="display table table-bordered table-hover table-sm table-striped mt-4">
                                            <thead>
                                                <tr>
                                                    <th>M&iacute;nimo</th>
                                                    <th>M&aacute;ximo</th>
                                                    <th>Descripci&oacute;n</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>            
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
                                        <div id="tblBad-d12" class="row"></div>
                                    </div>
                                    <div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2"></div>            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);
        }
    },
    selectPoblar: function (datos) {
        var tipo = '';
        $.each(datos.data.encabezado, function (key, value) {
            if(GLOBAL.computed.array_key_exists(key, datos.data.columnDef)){
                tipo = datos.data.columnDef[key][1]
                if((tipo == 'num') || (tipo == 'float')){
                    $('#campoAnalizar').append($('<option>', { 
                        value: key,
                        text : value
                    }));                    
                }
            }
        });
    },
    parametrosModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford">
                <p>
                    Herramienta para detector fraudes o movimientos anómalos en un rango de valores,  con esta hacemos auditoria del movimiento 
                    contable,  de las cuentas por cobrar o por pagar,  inventarios y todos los archivos de nuestra contabilidad que sean valores 
                    aleatorios, verificamos la consistencia del primer dígito, segundo digito y los dos primeros,  también le aplicamos la prueba 
                    estadística de la desviación absoluta media (DMA)  que nos verifica la variación de un conjunto de datos,  nos revela lo 
                    acertado de los resultados de la ley Benford.
                </p>
                <div class="form-group">
                    <div class="row">
                        <label for="campoAnalizar" class="float-left col-6">Campo a analizar:&nbsp;&nbsp;</label>
                        <label class="float-right text-truncate col-6 text-right">Archivo: ` + GLOBAL.archivoNombre + `</label>
                    </div>
                    <select id="campoAnalizar" name="campoAnalizar" class="form-control col"></select>
                </div>
                <!--
                <div class="form-group">
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
                -->
            </form>`);
    }
}
