/**
 * Archivos - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2020-05-02
 */
var ADMINTABLERO = ADMINTABLERO || {};
//Procesos
ADMINTABLERO.methods = {
    metricas: function () {
        ADMINTABLERO.componets.metricas().then(function () {
            $('#desde1').datepicker({uiLibrary: 'bootstrap4', format: 'dd-mm-yyyy'});
            $('#hasta1').datepicker({uiLibrary: 'bootstrap4', format: 'dd-mm-yyyy'});
            $('#desde2').datepicker({uiLibrary: 'bootstrap4', format: 'dd-mm-yyyy'});
            $('#hasta2').datepicker({uiLibrary: 'bootstrap4', format: 'dd-mm-yyyy'});            
        });
    },
    downloadPDF: function(pdf) {
        const linkSource = `data:application/pdf;base64,${pdf}`;
        const downloadLink = document.createElement("a");
        const fileName = "Reporte.pdf";
        downloadLink.href = linkSource;
        downloadLink.download = fileName;
        downloadLink.click();
    }    
}
//Conexion con backend
ADMINTABLERO.computed = {
    reporte1: function () {
        return $.ajax({
            url: '/admin/v1/metricas/reporte1',
            type: "POST",
            dataType: 'json',
            data:{
                f1: $('#desde1').val(),
                f2: $('#hasta1').val(),
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    ADMINTABLERO.methods.downloadPDF(data.data);
                }
                GLOBAL.computed.secure();
            }
        });        
    },
    reporte2: function () {
        return $.ajax({
            url: '/admin/v1/metricas/reporte2',
            type: "POST",
            dataType: 'json',
            data:{
                f1: $('#desde2').val(),
                f2: $('#hasta2').val(),
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    ADMINTABLERO.methods.downloadPDF(data.data);
                }
                GLOBAL.computed.secure();
            }
        });        
    }
}
//Html
ADMINTABLERO.componets = {
    metricas: function () {
        var deferred = $.Deferred();
        $('#content').html(`
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-subir-tab" data-toggle="tab" href="#nav-subir" role="tab" aria-controls="nav-subir" aria-selected="true">Métricas</a>
                </div>
            </nav>
            <div class="tab-content form-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-subir" role="tabpanel" aria-labelledby="nav-subir-tab">
                    <div id="body-subir">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span">Descargar</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>        
                        </ul>
                        <div class="scrollTable row">
                            <div class="col-3">
                                <div class="card shadow-sm m-0">
                                    <h5 class="card-header">Inicios de Sesión</h5>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Seleccione un periodo de tiempo donde se contaran los accesos a la herramienta agrupados por usuario
                                        </p>
                                        <div class="mb-2"><input type="text" id="desde1" value="" class="form-control"/></div>
                                        <div class="mb-2"><input type="text" id="hasta1" value="" class="form-control"/></div>        
                                        <button class="btn btn-secondary float-right col" onclick="ADMINTABLERO.computed.reporte1()">Descargar PDF</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card shadow-sm m-0">
                                    <h5 class="card-header">Uso de Herramientas</h5>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Se cuenta el uso de cada uno de las herramientas de analisis, Ley de Benford, Araña, Lista de Control, Indicadores de Cambio...
                                        </p>
                                        <div class="mb-2"><input type="text" id="desde2" value="" class="form-control"/></div>
                                        <div class="mb-2"><input type="text" id="hasta2" value="" class="form-control"/></div>
                                        <button class="btn btn-secondary float-right col" onclick="ADMINTABLERO.computed.reporte2()">Descargar PDF</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card shadow-sm m-0">
                                    <h5 class="card-header">Datos del Sistema</h5>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Server: AWS Cloud<br/>
                                            EC2: m5.large 2 Core 8Gb RAM<br/>
                                            Versión PHP: 7.2<br/>
                                            Versión MySQL: 5.7<br/>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        deferred.resolve();
        return deferred.promise();
    }
}