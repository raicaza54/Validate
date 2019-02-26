/**
 * Spider - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var SPIDER = SPIDER || {};
SPIDER.archivoId = 0;
SPIDER.methods = {
    parametros: function () {
        var ventana = $('#ventanaModal');
        var idArchivo = $('[name="archivoId"]').val();
        ventana.find('.modal-title').text('La Araña');
        ventana.find('.btn-primary').show();
        ventana.find('.btn-primary').text('Procesar');
        ventana.find('.btn-primary').attr('onclick','SPIDER.methods.procesar()');
        ventana.find('.btn-secondary').text('Cancelar');
        SPIDER.componets.parametrosModal();
        SPIDER.archivoId = idArchivo;
        var datos = SPIDER.methods.cargaDatos(idArchivo);
        datos.then(function (data) {
            SPIDER.componets.selectPoblar(data);
        }).then(function () {
            ventana.modal('show');
        });
    },
    cargaDatos: function (id) {
        return $.ajax({
            url: '/spider/v1/cuentas',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            success: function (data) {
                $.ajaxSetup({data: {'A4d6ebb02e86d4': data.csrf}});
            }
        });
    },
    procesarSpider: function() {
        var form_data = $('form#form-spider').serializeArray();
        form_data.push({ name: "archivoIdProcesar", value: SPIDER.archivoId });
        return $.ajax({
            url: '/spider/v1/procesar',
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
        var datos = SPIDER.methods.procesarSpider();
        SPIDER.componets.graficaSpider();
        datos.then(function (data) {
            console.log(data['data']['body']);
            $('#ventanaModal').modal('hide');
            $('#body-spider').html(data['data']['body']);
        });
    }
}
SPIDER.componets = {
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
    graficaSpider: function() {
        if (!$('#content').find('nav#tabs-benford').length ) {
            $('#content').html(`
                <nav id="tabs-benford">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-benford-tab" data-toggle="tab" href="#nav-benford" role="tab" aria-controls="nav-benford" aria-selected="true">La Araña</a>
                    </div>
                </nav>
                <div id="form-benford"><form id="form-benford"></form></div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active clearfix" id="nav-benford" role="tabpanel" aria-labelledby="nav-benford-tab">
                        <div id="body-archivo">
                            <ul class="nav position-relative" id="menu-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="spills-d1-tab" data-toggle="pill" href="#spills-d1" role="tab" aria-controls="spills-d1" aria-selected="true">
                                        <i class="fas fa-spider" data-original-title="" title=""></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="spills-d2-tab" data-toggle="pill" href="#spills-d2" role="tab" aria-controls="spills-d2" aria-selected="true">
                                        <i class="fas fa-spider" data-original-title="" title=""></i>
                                    </a>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                    <span id="requestfullscreen" class="nav-link" onclick="GLOBAL.methods.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                                </li>
                                <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                    <span id="exitfullscreen" class="nav-link" onclick="GLOBAL.methods.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                                </li>            
                            </ul>
                            <div class="scrollTable">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="spills-d1" role="tabpanel" aria-labelledby="spills-d1-tab">
                                        <div id="body-spider" style="margin: 0 auto;"></div>
                                    </div>
                                    <div class="tab-pane fade show" id="spills-d2" role="tabpanel" aria-labelledby="spills-d2-tab">
                                        Araña
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
            $('#campoSpider').append($('<option>', { 
                value: value['campo1'],
                text : value['campo1'] + " - Nombre de la Cuenta"
            }));
        });
    },
    parametrosModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-spider">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed bibendum tellus. 
                    Vestibulum eu lorem at nisl venenatis dictum nec vel nisi. Aenean fermentum sit amet erat feugiat volutpat. 
                    Phasellus nec dui et ex porta gravida. Suspendisse faucibus lacus id consequat dignissim. 
                    Pellentesque laoreet quam ac felis molestie feugiat.
                </p>
                <div class="form-group col-md-12">
                    <label for="campoSpider">Cuenta en la Ara&ntilde;a:&nbsp;&nbsp;</label>
                    <select id="campoSpider" name="campoSpider" class="form-control col"></select>
                </div>
            </form>`);
    }
}
