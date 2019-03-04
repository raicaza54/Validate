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
ARCHIVOS.methods = {
    cargaDatos: function (id) {
        return $.ajax({
            url: '/archivos/v1/datos',
            type: "POST",
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });
    },
    filtroDigito: function (digito, id, grafica) {
        var ventana = $('#ventanaModal');
        ventana.find('.modal-title').text('Digito ('+ digito.x +')');
        ventana.find('.btn-primary').hide();
        ventana.find('.btn-primary').text('Procesar');
        ventana.find('.btn-secondary').text('Cerrar');
        ventana.find('.btn-primary').attr('onclick','');
        var datos = ARCHIVOS.methods.cargaDigito(digito.x, id, grafica);
        ARCHIVOS.componets.digitoModal();
        datos.then(function (datos) {
            ARCHIVOS.componets.digitoPoblar(datos);
        }).then(function () {
            ventana.modal('show');
        });
    },
    cargaDigito: function(digito, id, grafica) {
        return $.ajax({
            url: '/archivos/v1/digito',
            type: "POST",
            dataType: 'json',
            data: {id: id, digito: digito, grafica: grafica},
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });
    },
    cargarArchivo: function() {
        ARCHIVOS.componets.archivo(GLOBAL.folderPath);
    },
    subirArchivo: function() {
        var formData = $('#content').find('input[name="archivo"]').val();
        return $.ajax({
            url: '/archivos/v1/subir',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: {archivo: formData},
            success: function (data) {
                GLOBAL.methods.secure();
            }
        });
    },
    listarDatos: function (id) {
        var idDatos = id;
        var datos = ARCHIVOS.methods.cargaDatos(id);
        datos.then(function (r) {
            ARCHIVOS.componets.tabs();
            return r;
        }).then(function (r) {
            $('[name="archivoId"]').val(idDatos);
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
    digitoPoblar: function(datos) {
        $.each(datos['data'], function (key, value) {
            $('#form-benford-digito #table-digito tbody').append(
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
    archivo: function (path) {
        $('#content').html(`
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-subir-tab" data-toggle="tab" href="#nav-subir" role="tab" aria-controls="nav-subir" aria-selected="true">Cargar Archivo</a>
                </div>
            </nav>
            <div class="tab-content form-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-subir" role="tabpanel" aria-labelledby="nav-subir-tab">
                    <div id="body-subir">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onclick="ARCHIVOS.methods.subirArchivo()"><i class="fas fa-upload"></i> Cargar archivo</i></span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>        
                        </ul>
                        <div class="scrollTable">
                            <form enctype="multipart/form-data">
                                <p>
                                    Los tipos de archivos permitidos de ofimatica unicamente se permiten los siguientes tipos 
                                    hojas ed calculo xls, xlsx y ods documentos tales como doc, docx, pdf, odt. Tambien estan permitidas
                                    las imagenes de tipo jpg, jpeg, bmp y png.
                                </p>
                                <p>
                                    Los archivos no deben superar 20 mb
                                </p>
                                <p>
                                    Directorio destino: <span id="path-archivo">` + path + `</span>
                                </p>        
                                <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="archivo" name="archivo">
                                    <label class="custom-file-label" for="archivo">Cargar archivo</label>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="encabezado" name="encabezado">
                                    <label class="form-check-label" for="encabezado">la primera fila contiene los encabezados</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>        
        `);
    },
    tabs: function () {
        $('#content').html(
                `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-archivo-tab" data-toggle="tab" href="#nav-archivo" role="tab" aria-controls="nav-archivo" aria-selected="true">Archivo</a>
                </div>
            </nav>
            <input type="hidden" name="archivoId" value="">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-archivo" role="tabpanel" aria-labelledby="nav-archivo-tab">
                    <div id="body-archivo">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#"><i class="fas fa-star"></i></a>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.methods.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
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
    },
    digitoModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford-digito">
                <div class="scrollTable">
                    <table id="table-digito" class="display table table-bordered table-hover table-sm table-striped">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>`);
    }
}
