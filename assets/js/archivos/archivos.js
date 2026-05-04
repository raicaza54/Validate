/**
 * Archivos - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-02-13
 */
var ARCHIVOS = ARCHIVOS || {};
ARCHIVOS.lim_blp = null;
ARCHIVOS.lim_mov = null;
ARCHIVOS.lim_cxc = null;
ARCHIVOS.lim_cxp = null;
ARCHIVOS.modal = null;
ARCHIVOS.inputFiltro = null;
ARCHIVOS.methods = {
    vista: function(obj, e) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var current = $('select[name="' + e + '"]');
            var cname = current.attr('name');
            var cval = current.val();
            if($.inArray(cval, ['cta','ctan','comp','doc','tipo','valor','debe','haber','identificacion','nombre','base']) >= 0){
                $.each($("select.config-archivo"), function(key, value){
                    if(($(this).val() == cval) && ($(this).attr('name') != cname)){
                        $(this).val('string');
                    }
                });            
            }
        }
    },
    cofingCambio: function(){
        var tipo = $("#archivoTipo").val(), opt = '';
        $("#archivoFormato").prop('disabled', true);
        var mov = `
            <option value="string" selected="selected">LETRAS Y NÚMEROS</option>
            <option value="float">NÚMERO DECIMAL</option>
            <option value="num">NÚMERO</option>
            <option value="date">FECHA</option>
            <option value="cta">NÚMEROS DE CUENTAS&sup1;</option>
            <option value="comp">COMPROBANTE&sup1;</option>
            <option value="doc">DOCUMENTO&sup1;</option>
            <option value="identificacion">IDENTIFICACION&sup1;</option>
            <option value="nombre">NOMBRE&sup1;</option>
            <option value="tipo">NATURALEZA&sup1;</option>
            <option value="valor">VALOR&sup1;</option>
            <option value="base">BASE&sup1;</option>`;
        var blp = `
            <option value="string">LETRAS Y NUMEROS</option>
            <option value="float">NÚMERO DECIMAL</option>
            <option value="num">NÚMERO</option>
            <option value="date">FECHA</option>
            <option value="cta">NÚMEROS DE CUENTAS&sup1;</option>
            <option value="ctan">NOMBRE DE CUENTAS&sup1;</option>
            <option value="valor">SALDO FINAL&sup1;</option>`;
        opt = blp;
        if(tipo == 'mov'){
            opt = mov;
            $("#archivoFormato").prop('disabled', false);
        }
        $.when().then(function () {
            $('option', $('.config-archivo')).remove();
        }).then(function () {
            $('.config-archivo').append(opt);
        });
    },
    cofingFormato: function(){
        var tipo = $("#archivoFormato").val(), opt = '';
        var fnat = `
            <option value="string" selected="selected">LETRAS Y NÚMEROS</option>
            <option value="float">NÚMERO DECIMAL</option>
            <option value="num">NÚMERO</option>
            <option value="date">FECHA</option>
            <option value="cta">NÚMEROS DE CUENTAS&sup1;</option>
            <option value="comp">COMPROBANTE&sup1;</option>
            <option value="doc">DOCUMENTO&sup1;</option>
            <option value="identificacion">IDENTIFICACION&sup1;</option>
            <option value="nombre">NOMBRE&sup1;</option>        
            <option value="tipo">NATURALEZA&sup1;</option>
            <option value="valor">VALOR&sup1;</option>
            <option value="base">BASE&sup1;</option>`;
        var fdhb = `
            <option value="string" selected="selected">LETRAS Y NÚMEROS</option>
            <option value="float">NÚMERO DECIMAL</option>
            <option value="num">NÚMERO</option>
            <option value="date">FECHA</option>
            <option value="cta">NÚMEROS DE CUENTAS&sup1;</option>
            <option value="comp">COMPROBANTE&sup1;</option>
            <option value="doc">DOCUMENTO&sup1;</option>
            <option value="identificacion">IDENTIFICACION&sup1;</option>
            <option value="nombre">NOMBRE&sup1;</option>
            <option value="debe">DÉBITO&sup1;</option>
            <option value="haber">CRÉDITO&sup1;</option>
            <option value="base">BASE&sup1;</option>`;
        opt = fnat;
        if(tipo == 'debehaber'){
            opt = fdhb;
        }
        $.when().then(function () {
            $('option', $('.config-archivo')).remove();
        }).then(function () {
            $('.config-archivo').append(opt);
        });
    },
    aplicarConfig: function (obj){
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var form_data = $('form#form-archivo-config').serializeArray();
            form_data.push({ name: "archivoId", value: GLOBAL.archivoId });
            form_data.push({ name: "columnasDefault", value: $('[name=columnasDefault]:checked').val() ? 1 : 0 });
            return $.ajax({
                url: '/archivos/v1/configurar',
                type: "POST",
                dataType: 'json',
                data: {
                    form: form_data
                },
                beforeSend: function (xhr) {
                    var ventana = $('#ventanaModal');
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', true);
                    ventana.find('.btn-link').prop('disabled', true);
                    ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
                },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }
                    GLOBAL.computed.secure();
                    var ventana = $('#ventanaModal');
                    ARCHIVOS.computed.cargaDatos(GLOBAL.archivoId, GLOBAL.exploradorId);
                    ventana.modal('hide');
                },
                error: function (jqXHR) {
                    var ventana = $('#ventanaModal');
                    ventana.find('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
                    ventana.find('.btn-primary').prop('disabled', false);
                    ventana.find('.btn-link').prop('disabled', false);
                    ventana.find('.btn-primary').html('Aplicar y actualizar');
                }
            });
        }
    },
    configArchivo: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana);
            ventana.find('.modal-title').text('Configurar archivo');
            ventana.find('#btn-extra1').html(`
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="columnasDefault" name="columnasDefault" value="1" checked>
                  <label class="form-check-label" for="columnasDefault">Utilizar esta configuración de columnas por defecto</label>
                </div>
            `);
            ventana.find('#btn-extra1').show();
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);
            ventana.find('.btn-primary').text('Aplicar y actualizar');
            ventana.find('.btn-link').text('Cancelar');
            ventana.find('.btn-primary').attr('onclick','ARCHIVOS.methods.aplicarConfig(this, ' + GLOBAL.archivoId + ')');
            $.when(
                ARCHIVOS.computed.configColumn()
            ).then(function (datos) {
                if(datos != false){
                    ARCHIVOS.componets.configModal(datos).then(function () {
                        //ARCHIVOS.methods.cofingFormato()
                    }).then(
                        ventana.modal('show')
                    );
                }else{
                    GLOBAL.computed.toast('Algo no anda bien al cargar el archivo, intentelo nuevamente');
                }
            });
        }
    },
    cargaDigito: function(obj, digito, id, grafica) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            $.ajax({
                url: '/archivos/v1/header',
                type: "POST",
                dataType: 'json',
                data: {
                    id: id,
                    campoAnalizar: GLOBAL.campoBenford
                },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }                    
                    GLOBAL.computed.secure();
                    return data;
                }
            }).then(function (columns) {
                var max = $('#max-benford').val();
                var min = $('#min-benford').val();
                $('#table-digito').DataTable({
                    scrollResize: true,
                    scrollY: 100,
                    scrollX: true,
                    scrollCollapse: true,                
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    order: [],
                    pageLength: 50,
                    ajax: {
                        url : '/archivos/v1/digito',
                        type: "POST",
                        data: {
                            id: id,
                            digito: digito,
                            grafica: grafica,
                            campoAnalizar: GLOBAL.campoBenford,
                            min: min,
                            max: max,
                        },
                        dataSrc: "data"
                    },
                    columns: columns.column,
                    columnDefs: columns.columnDef,
                    formatNumber: function ( toFormat ) {
                      return toFormat.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g, "'"
                      )
                    }
                });
            });
        }
    },
    cargaSpider: function(cuenta, spider, naturaleza, id) {
        $.ajax({
            url: '/archivos/v1/header',
            type: "POST",
            dataType: 'json',
            data: {
                id: id,
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }                    
                GLOBAL.computed.secure();
                return data;
            }
        }).then(function (columns) {
            $('#table-spider').DataTable({
                scrollResize: true,
                scrollY: 100,
                scrollX: true,
                scrollCollapse: true,                
                destroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                pageLength: 50,
                ajax: {
                    url : '/archivos/v1/relacionspider',
                    type: "POST",
                    data: {
                        id: id,
                        cuenta: cuenta,
                        spider: spider,
                        naturaleza: naturaleza,
                    },
                    dataSrc: "data"
                },
                columns: columns.column,
                columnDefs: columns.columnDef,
                formatNumber: function ( toFormat ) {
                  return toFormat.toString().replace(
                    /\B(?=(\d{3})+(?!\d))/g, "'"
                  )
                }
            });
        });
    },
    limitesList: function() {
        var tipo = $('[name="tipo"]').val();
        switch (tipo) {
            case 'mov':
                $("#lim-limite").html(GLOBAL.computed.number_format(ARCHIVOS.lim_mov.limite,0));
                $("#lim-cant").html(GLOBAL.computed.number_format(ARCHIVOS.lim_mov.cant,0));
                $("#lim-filas").html(GLOBAL.computed.number_format(ARCHIVOS.lim_mov.filas,0));
                break;
            case 'blp':
                $("#lim-limite").html(GLOBAL.computed.number_format(ARCHIVOS.lim_blp.limite,0));
                $("#lim-cant").html(GLOBAL.computed.number_format(ARCHIVOS.lim_blp.cant,0));
                $("#lim-filas").html(GLOBAL.computed.number_format(ARCHIVOS.lim_blp.filas,0));
                break;
            case 'cxc':
                $("#lim-limite").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxc.limite,0));
                $("#lim-cant").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxc.cant,0));
                $("#lim-filas").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxc.filas,0));                
                break;
            case 'cxp':
                $("#lim-limite").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxp.limite,0));
                $("#lim-cant").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxp.cant,0));
                $("#lim-filas").html(GLOBAL.computed.number_format(ARCHIVOS.lim_cxp.filas,0));                
                break;
            default:
                $("#lim-limite").html(0);
                $("#lim-cant").html(0);
                $("#lim-filas").html(0);
                break;
        }
    },
    cargarArchivo: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un directorio 
                     destino donde se importara el archivo`);
                return;
            }
            var datos = ARCHIVOS.computed.limites();
            datos.then(function (data) {
                if(GLOBAL.computed.array_key_exists('balances', data.data)){
                    ARCHIVOS.lim_blp = data.data.balances;
                }
                if(GLOBAL.computed.array_key_exists('movimiento', data.data)){
                    ARCHIVOS.lim_mov = data.data.movimiento;
                }
                if(GLOBAL.computed.array_key_exists('cxc', data.data)){
                    ARCHIVOS.lim_cxc = data.data.cxc;
                }
                if(GLOBAL.computed.array_key_exists('cxp', data.data)){
                    ARCHIVOS.lim_cxp = data.data.cxp;
                }
                
            }).then(function () {
                ARCHIVOS.componets.archivo(GLOBAL.folderPath, GLOBAL.folderId);
            }).then(function () {
                ARCHIVOS.methods.limitesList();
            });
        }
    },
    descargarExcel: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo para exportar el archivo`);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo para descargar`);
                return;                
            }
            var datos = ARCHIVOS.computed.descargar(GLOBAL.archivoId);
            datos.then(function (data) {
                if(data.status == 200){
                    GLOBAL.computed.AjaxDownloader({
                        url: data.data.url
                    });
                }                    
            });            
        }
    },
    subirArchivo: function(obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if((GLOBAL.folderPath == 'Debe seleccionar un destino') || GLOBAL.computed.isNull(GLOBAL.folderId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar un directorio 
                     destino donde se importara el archivo`);
                return;
            }
            var id = GLOBAL.computed.uniqint(), sel;
            var datosp = ARCHIVOS.computed.preprocesar(id);
            $('form#form-archivo').find('input[name="id"]').val(id);
            datosp.then(function (data) {
                if(parseInt(data.status) == 200){
                    var ref = $('#carpetasTree').jstree(true);
                    sel = ref.create_node(GLOBAL.folderId, {
                        id: data.data.id,
                        type: data.data.type,
                        text: data.data.filename,
                        disabled: 1
                    });
                    if(sel) {
                        ref.deselect_all(true);
                        ref.select_node(sel);
                    }
                }
                return data;
            }).then(function (data) {
                if(parseInt(data.status) == 200){
                    var datos = ARCHIVOS.computed.procesarArchivo();
                    datos.then(function (data) {
                        if(parseInt(data.status) == 200){
                            GLOBAL.componets.procesando('hide');
                            GLOBAL.computed.toast('El archivo fue cargado con exito');
                            $("#carpetasTree").jstree().enable_node(sel);
                        }
                    });
                }
            });
        }
    },
    filtroDigito: function (obj, digito, id, grafica) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            var ventana = $('#ventanaModal');
            GLOBAL.computed.initializeModal(ventana, true, 'modal-xl');
            ventana.find('.modal-title').text('Filtrar Dígito');
            ventana.find('.btn-primary').show();
            ventana.find('.btn-primary').prop('disabled', false);
            ventana.find('.btn-link').prop('disabled', false);
            ventana.find('.btn-primary').text('Exportar XLSX');
            ventana.find('.btn-primary').attr('onclick', 'ARCHIVOS.computed.exportarDigito(' + digito.x + ', ' + id + ', ' + grafica + ')');
            ventana.find('.btn-link').text('Cerrar');
            $.when(
                ARCHIVOS.componets.digitoModal()
            ).then(function () {
                    ventana.find('#span-columna').html(digito.x);
                    ventana.find('#filtro-digito').attr('onclick', 'ARCHIVOS.methods.cargaDigito(this, ' + digito.x + ', ' + id + ', ' + grafica + ')');
                }
            ).then(
                ARCHIVOS.methods.cargaDigito(true, digito.x, id, grafica)
            ).then(
                ventana.modal('show')
            );
        }
    },
    filtroSpider: function (cuenta, spider, naturaleza) {
        var ventana = $('#ventanaModal');
        GLOBAL.computed.initializeModal(ventana, true, 'modal-xl');
        ventana.find('.modal-title').text('Relación de Cuentas');
        ventana.find('.btn-primary').show();
        ventana.find('.btn-primary').prop('disabled', false);
        ventana.find('.btn-link').prop('disabled', false);
        ventana.find('.btn-primary').text('Exportar XLSX');
        ventana.find('.btn-primary').attr('onclick', 'ARCHIVOS.computed.exportarSpider(' + cuenta + ', ' + spider + ', \'' + naturaleza + '\')');
        ventana.find('.btn-link').text('Cerrar');
        $.when(
            ARCHIVOS.componets.spiderModal(cuenta, spider, naturaleza)
        ).then(function () {
                ARCHIVOS.methods.cargaSpider(cuenta, spider, naturaleza, GLOBAL.archivoId)
        }).then(
            ventana.modal('show')
        );
    },
    listarDatos: function (obj, id, exId) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            GLOBAL.archivoId = id;
            GLOBAL.exploradorId = exId;
            $.when(
                GLOBAL.componets.loader()
            ).then(
                ARCHIVOS.componets.tabs()
            ).then(
                ARCHIVOS.computed.cargaDatos(id, exId)
            ).then(
                $('[name="archivoId"]').val(id)
            );
        }
    },
    explorador: function (explorador) {
        $.when().then(function () {
            $('.explorador-vista').removeClass('d-block');
            $('.explorador-vista').addClass('d-none');
        }).then(function () {
            $('#' + explorador).addClass('d-block');
        });
    },
    filtroMaterialidad: function (e) {
        ARCHIVOS.inputFiltro = e;
        var datos = PERFIL.computed.datos();
        datos.then(function (e) {
            let m = e.data.columnas.materialidad;
            ARCHIVOS.modal = $.fn.dialogue({
                title: 'Materialidad',
                content: `
                    <p>
                        Usted puede elegir un valor para ser aplicado en el filtro
                    </p>
                    <table class="table table-striped table-hover table-bordered materialidad-filtro">
                        <thead>
                            <tr>
                                <th style="width: 200px">Cuenta</th>
                                <th style="width: 120px">Rango</th>
                                <th style="width: 80px">Porcentaje</th>
                                <th style="width: 177px">Valor</th>
                                <th style="width: 176px">Materialidad</th>
                                <th style="width: 176px">Error Tolerable</th>
                                <th style="width: 176px">Importe Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">Utilidad Antes de Impuesto</td>
                                <td class="text-right">5,0% a 10,0%</td>
                                <td class="text-right"><span id="utladi">` + GLOBAL.computed.number_format(m.utladi,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utladi_val" >` + GLOBAL.computed.number_format(m.utladi_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="utladi_mate">` + GLOBAL.computed.number_format(m.utladi_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utladi_erto">` + GLOBAL.computed.number_format(m.utladi_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utladi_imno">` + GLOBAL.computed.number_format(m.utladi_imno,2,',','.') + `</span></td>
                            </tr>
                            <tr>
                                <td class="text-left">Utilidad Operacional</td>
                                <td class="text-right">7,0% a 10,0%</td>        
                                <td class="text-right"><span id="utlope">` + GLOBAL.computed.number_format(m.utlope,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utlope_val" >` + GLOBAL.computed.number_format(m.utlope_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="utlope_mate">` + GLOBAL.computed.number_format(m.utlope_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utlope_erto">` + GLOBAL.computed.number_format(m.utlope_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utlope_imno">` + GLOBAL.computed.number_format(m.utlope_imno,2,',','.') + `</span></td>        
                            </tr>        
                            <tr>
                                <td class="text-left">Utilidad Bruta</td>
                                <td class="text-right">3,0% a 5,0%</td>        
                                <td class="text-right"><span id="utlbru">` + GLOBAL.computed.number_format(m.utlbru,2,',','.') + `</span></td>        
                                <td class="text-right"><span id="utlbru_val" >` + GLOBAL.computed.number_format(m.utlbru_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="utlbru_mate">` + GLOBAL.computed.number_format(m.utlbru_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utlbru_erto">` + GLOBAL.computed.number_format(m.utlbru_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="utlbru_imno">` + GLOBAL.computed.number_format(m.utlbru_imno,2,',','.') + `</span></td>        
                            </tr>        
                            <tr>        
                                <td class="text-left">Ingresos Operacionales</td>
                                <td class="text-right">0,5% a 1,0%</td>        
                                <td class="text-right"><span id="ingope">` + GLOBAL.computed.number_format(m.ingope,2,',','.') + `</span></td>        
                                <td class="text-right"><span id="ingope_val" >` + GLOBAL.computed.number_format(m.ingope_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="ingope_mate">` + GLOBAL.computed.number_format(m.ingope_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="ingope_erto">` + GLOBAL.computed.number_format(m.ingope_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="ingope_imno">` + GLOBAL.computed.number_format(m.ingope_imno,2,',','.') + `</span></td>        
                            </tr>        
                                <td class="text-left">Activos</td>
                                <td class="text-right">0,5% a 1,0%</td>        
                                <td class="text-right"><span id="activo">` + GLOBAL.computed.number_format(m.activo,2,',','.') + `</span></td>
                                <td class="text-right"><span id="activo_val" >` + GLOBAL.computed.number_format(m.activo_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="activo_mate">` + GLOBAL.computed.number_format(m.activo_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="activo_erto">` + GLOBAL.computed.number_format(m.activo_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="activo_imno">` + GLOBAL.computed.number_format(m.activo_imno,2,',','.') + `</span></td>        
                            </tr>        
                            <tr>        
                                <td class="text-left">Patrimonio</td>
                                <td class="text-right">5,0% a 7,0%</td>        
                                <td class="text-right"><span id="patrim">` + GLOBAL.computed.number_format(m.patrim,2,',','.') + `</span></td>
                                <td class="text-right"><span id="patrim_val" >` + GLOBAL.computed.number_format(m.patrim_val,2,',','.')  + `</span></td>
                                <td class="text-right"><span id="patrim_mate">` + GLOBAL.computed.number_format(m.patrim_mate,2,',','.') + `</span></td>
                                <td class="text-right"><span id="patrim_erto">` + GLOBAL.computed.number_format(m.patrim_erto,2,',','.') + `</span></td>
                                <td class="text-right"><span id="patrim_imno">` + GLOBAL.computed.number_format(m.patrim_imno,2,',','.') + `</span></td>        
                            </tr>                                                    
                        </tbody>            
                    </table>
                `,
                closeIcon: true,
                closeButton: true,
                sizeDialogue: 'modal-xl',
            });            
        }).then(function () {
            $("body").mLoading('hide');
        });
    },    
}
ARCHIVOS.computed = {
    descargar: function (idArchivo) {
        return $.ajax({
            url: '/archivos/v1/descargar',
            type: "POST",
            dataType: 'json',
            data:{
                id: idArchivo,
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
    exportarDigito: function(digito, id, grafica) {
        var ventana = $('#ventanaModal');
        return $.ajax({
            url: '/archivos/v1/exportarDigito',
            type: "POST",
            dataType: 'json',
            data:{
                id: id,
                digito: digito,
                grafica: grafica,
                campoAnalizar: GLOBAL.campoBenford
            },
            beforeSend: function (xhr) {
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);
                ventana.find('.btn-primary').html('Exportar XLSX');
                if(data.status == 200){
                    GLOBAL.computed.AjaxDownloader({
                        url: data.data.url + '/r'
                    });
                }                
                GLOBAL.computed.secure();
            }
        });        
    },
    exportarSpider: function(cuenta, spider, naturaleza) {
        var ventana = $('#ventanaModal');
        return $.ajax({
            url: '/archivos/v1/exportarspider',
            type: "POST",
            dataType: 'json',
            data:{
                id: GLOBAL.archivoId,
                cuenta: cuenta,
                spider: spider,
                naturaleza: naturaleza,                
            },
            beforeSend: function (xhr) {
                ventana.find('.btn-primary').prop('disabled', true);
                ventana.find('.btn-link').prop('disabled', true);
                ventana.find('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando');
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }
                ventana.find('.btn-primary').prop('disabled', false);
                ventana.find('.btn-link').prop('disabled', false);
                ventana.find('.btn-primary').html('Exportar XLSX');
                if(data.status == 200){
                    GLOBAL.computed.AjaxDownloader({
                        url: data.data.url + '/r'
                    });
                }                
                GLOBAL.computed.secure();
            }
        });        
    },
    limites: function() {
        return $.ajax({
            url: '/archivos/v1/limites',
            type: "GET",
            dataType: 'json',
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }            
                GLOBAL.computed.secure();
                return data;
            }
        });
    },
    cargaDatos: function (id, exId = null) {
        $.ajax({
            url: '/archivos/v1/header',
            type: "POST",
            dataType: 'json',
            data: {id: id},
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    GLOBAL.archivoNombre = data.archivo['nombre'];
                    $('span#archivoNombre').html(GLOBAL.archivoNombre);
                    GLOBAL.archivoTipo = data.archivo['tipo'];
                    GLOBAL.archivoFormato = data.archivo['formato'];
                }
                GLOBAL.computed.secure();
                return data;
            }
        }).then(function (columns) {
            if(columns['status'] == 200){
                GLOBAL.table = $('#table-archivo').DataTable( {
                    destroy: true,
                    scrollResize: true,
                    scrollY: 100,
                    scrollX: true,
                    searching: false,
                    scrollCollapse: true,
                    processing: true,
                    serverSide: true,
                    order: [],
                    pageLength: 50,
                    ajax: {
                        url : '/archivos/v1/datos',
                        type: "POST",
                        data: {id: id, exId: exId, length: 50},
                        dataSrc: "data"
                    },
                    initComplete: function( settings, json){
                        $("body").mLoading('hide');
                    },
                    columns: columns.column,
                    columnDefs: columns.columnDef,
                });                
            }else{
                
            }
        });
    },
    configColumn: function() {
        if(GLOBAL.archivoId > 0){
            return $.ajax({
                url: '/archivos/v1/encabezado',
                type: "POST",
                dataType: 'json',
                data: { id: GLOBAL.archivoId },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }           
                    GLOBAL.computed.secure();
                    return data;
                }
            });            
        }else{
            return false;
        }
    },
    comprobarDatos: function(obj, id, exId) {
        if(exId > 0){
            return $.ajax({
                url: '/archivos/v1/comprobarDatos',
                type: "POST",
                dataType: 'json',
                data: { id: exId },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }           
                    GLOBAL.computed.secure();
                    return data;
                }
            });            
        }else{
            return false;
        }
    },
    columnasPorDefecto: function() {
        if(GLOBAL.empresaId > 0){
            return $.ajax({
                url: '/perfil/v1/columnasPorDefecto',
                type: "POST",
                dataType: 'json',
                data: { 
                    id: GLOBAL.archivoId,  
                    archivoFormato: $("#archivoFormato").val(),
                    archivoTipo: $("#archivoTipo").val()
                },
                complete: function (jqXHR, textStatus) {
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                    }           
                    GLOBAL.computed.secure();
                    var x = 0, i = 0;
                    $.each(data.data.encabezado, function (campo, value) {
                        $.each($('form#form-archivo-config #table-config-archivo tr td:first-child'), function (key, cell) {
                            if(cell.innerHTML.indexOf(value) > 0){
                                x = data.data.columnDef[campo][0] != 'campo' ? 0 : 1;
                                $("[name="+ campo +"]").val(data.data.columnDef[campo][x]);
                                i++;
                                return true;
                            }
                        });
                    });
                    $("#columnasDefault").attr('checked', false);
                    GLOBAL.computed.toast(i > 0 ? 'Se han seleccionado las columnas por defecto':'Las columnas no corresponden con los valores por defecto');
                    return data;
                }
            });            
        }else{
            return false;
        }        
    },
    preprocesar: function(id) {
        return $.ajax({
            url: '/archivos/v1/preprocesar',
            type: "POST",
            data: {
                id: id,
                nombre: GLOBAL.computed.basename($('input#archivo').val()),
                tipo: $('select[name="tipo"]').val(),
                carpeta: GLOBAL.folderId
            },
            complete: function (jqXHR, textStatus) {
                if(textStatus != 'error'){
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                        $('#form-archivo').find('.btn-primary').removeClass('disabled');
                        $('#form-archivo').find('.btn-link').removeClass('disabled');
                        $('#form-archivo').find('.btn-primary').html('Cargar Archivo');                        
                    }
                }
                GLOBAL.computed.secure();
            },
            error: function (jqXHR, textStatus) {
                $('#form-archivo').find('.btn-primary').removeClass('disabled');
                $('#form-archivo').find('.btn-link').removeClass('disabled');
                $('#form-archivo').find('.btn-primary').html('Cargar Archivo');
            }
        });        
    },
    procesarArchivo: function() {
        var formData = new FormData($('#content').find('form#form-archivo')[0]);
        return $.ajax({
            url: '/archivos/v1/subir',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function (xhr) {
                GLOBAL.componets.procesando();
                ARCHIVOS.componets.limpiarContent();
            },
            complete: function (jqXHR, textStatus) {
                if(textStatus != 'error'){
                    var data = jqXHR.responseJSON;
                    var status = parseInt(data.status);
                    if(status != 200){
                        GLOBAL.computed.toast(data.detail, status);
                        $('#form-archivo').find('.btn-primary').removeClass('disabled');
                        $('#form-archivo').find('.btn-link').removeClass('disabled');
                        $('#form-archivo').find('.btn-primary').html('Cargar Archivo');                        
                    }
                }
                GLOBAL.computed.secure();
            },
            error: function (jqXHR, textStatus) {
                $('#form-archivo').find('.btn-primary').removeClass('disabled');
                $('#form-archivo').find('.btn-link').removeClass('disabled');
                $('#form-archivo').find('.btn-primary').html('Cargar Archivo');
            }
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
    },
    archivo: function (path, folderId) {
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
                                <span class="nav-link btn-span">&nbsp;</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar()"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar()"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>        
                        </ul>
                        <div class="scrollTable">
                            <form enctype="multipart/form-data" class="form-data pr-3" id="form-archivo">
                                <p>
                                    Los tipos de archivos permitidos de ofimática unicamente se permiten los siguientes tipos hojas de calculo XLSX y CSV, tenga en cuenta que los archivos unicamente deben contener los títulos de columnas desde la primera fila primera columna no puede contener títulos adicionales, las columnas necesarias. Según el tipo de análisis es necesario que los archivos cuentes con las siguientes columnas:
                                </p>
                                <p>
                                    <h6 class="mb-0">Ley de Benford</h6><hr/> Aplica para el tipo archivo Movimiento las columnas necesarias según el formato varían, para Columnas Naturaleza y Valor se debe elegir la columna VALOR la cual es de tipo numérica para el formato Columnas Débitos y Créditos se eligen las columnas numéricas DÉBITO y CRÉDITO.
                                </p>
                                <p>
                                    <h6 class="mb-0">Araña</h6><hr/> Aplica el tipo de archivo Movimiento según el formato tenga en cuenta, para Columnas Naturaleza y Valor se debe elegir las columnas NÚMEROS DE CUENTA, COMPROBANTE, DOCUMENTO, NATURALEZA y VALOR, para los archivo Columnas Débitos y Créditos se debe elegir las columnas NÚMEROS DE CUENTA, COMPROBANTE, DOCUMENTO, DÉBITO y CRÉDITO.
                                </p>
                                <p>
                                    <h6 class="mb-0">Listas de Control</h6><hr/> Aplica el tipo de archivo Movimiento es necesario configurar las columnas de IDENTIFICACIÓN y NOMBRE.
                                </p>
                                <p>
                                    <h6 class="mb-0">Indicadores de Cambio</h6><hr/> El tipo de archivo es Balance de Prueba es necesario 2 o más archivos de diferentes periodos para realizar el análisis las columnas necesarias son, NÚMEROS DE CUENTA, NOMBRE DE CUENTAS y SALDO FINAL.
                                </p>
                                <p>
                                    <h6 class="mb-0">Condiciones de cuenta</h6><hr/> Aplica el tipo de archivo Movimiento según el formato tienen variación, para Columnas Naturaleza y Valor debe definir IDENTIFICACIÓN, DOCUMENTO, CUENTAS, VALOR y BASE, para el formato Columnas Débitos y Créditos debe definir IDENTIFICACIÓN, DOCUMENTO, CUENTAS, DÉBITOS, CRÉDITOS Y BASE.
                                </p>
                                <div class="clearfix">
                                    <p class="float-left">
                                        Cantidad máxima de archivos: <span id="lim-cant">N/A</span>/<span id="lim-limite">N/A</span><br/>
                                    </p>
                                    <p class="float-right">
                                        Cantidad máxima de filas: <span id="lim-filas">N/A</span> filas<br/>
                                    </p>
                                </div>
                                <p>
                                    Directorio destino: <span id="path-archivo">` + path + `</span>
                                    <input name="folderId" type="hidden" value="` + folderId + `" />
                                    <input name="id" type="hidden" value="" />
                                </p>
                                <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="archivo" name="archivo">
                                    <label class="custom-file-label" for="archivo">Cargar archivo</label>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="tipo" onChange="ARCHIVOS.methods.limitesList()">
                                        <option value="mov">Movimiento</option>
                                        <option value="blp">Balance de Prueba</option>
                                        <option value="cxc" disabled>Cuentas por Cobrar</option>
                                        <option value="cxp" disabled>Cuentas por Pagar</option>
                                    </select>
                                </div>
                                <hr class="">
                                <button type="button" class="btn btn-primary float-right" onclick="ARCHIVOS.methods.subirArchivo(true)">Cargar archivo</button>
                                <button type="button" class="btn btn-link float-right" onCLick="ARCHIVOS.componets.limpiarContent()">Cancelar</button>                
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
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <input type="hidden" name="archivoId" value="">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-archivo" role="tabpanel" aria-labelledby="nav-archivo-tab">
                    <div id="body-archivo">
                        <ul class="nav position-relative">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onclick="ARCHIVOS.methods.configArchivo(true)"><i class="fas fa-cog"></i> Configurar</span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link btn-span" onclick="RESULTADOS.methods.listarCarpetas(true, GLOBAL.empresaId, true)"><i class="fas fa-filter"></i> Filtrar Resultados</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.dtable)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.dtable)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>        
                        </ul>
                        <div class="scrollTable" style="overflow: hidden;">
                            <table id="table-archivo" class="display table table-bordered table-hover table-sm table-striped" style="width: 100%;">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`);
    },
    configTipo: function(tipo, formato, name, columnDef) {
        var r = 'string', k = false;
        if(columnDef[0] == 'campo'){
            var e = columnDef[1];
            if(e == 'num'){
                r = 'num';
            }else if(e == 'float'){
                r = 'float';
            }else if(e == 'string'){
                r = 'string';
            }else if(e == 'date'){
                r = 'date';
            }
        }else{
            var r = columnDef[0];
        }
        if(tipo == 'mov'){
            k = `<select name="` + name + `" class="form-control config-archivo" onchange="ARCHIVOS.methods.vista(true, '` + name + `')">
                <option value="string" ` + ((r == 'string') ? 'selected="selected"' : '') + `>LETRAS Y NÚMEROS</option>
                <option value="float" ` + ((r == 'float') ? 'selected="selected"' : '') + `>NÚMERO DECIMAL</option>
                <option value="num" ` + ((r == 'num') ? 'selected="selected"' : '') + `>NÚMERO</option>
                <option value="date" ` + ((r == 'date') ? 'selected="selected"' : '') + `>FECHA</option>
                <option value="cta" ` + ((r == 'cta') ? 'selected="selected"' : '') + `>NÚMEROS DE CUENTAS&sup1;</option>
                <option value="comp" ` + ((r == 'comp') ? 'selected="selected"' : '') + `>COMPROBANTE&sup1;</option>
                <option value="doc" ` + ((r == 'doc') ? 'selected="selected"' : '') + `>DOCUMENTO&sup1;</option>
                <option value="identificacion" ` + ((r == 'identificacion') ? 'selected="selected"' : '') + `>IDENTIFICACION&sup1;</option>
                <option value="nombre" ` + ((r == 'nombre') ? 'selected="selected"' : '') + `>NOMBRE&sup1;</option>
                <option value="base" ` + ((r == 'base') ? 'selected="selected"' : '') + `>BASE&sup1;</option>`;
            if(formato == 'naturaleza'){
                k = k + `<option value="tipo" ` + ((r == 'tipo') ? 'selected="selected"' : '') + `>NATURALEZA&sup1;</option>
                <option value="valor" ` + ((r == 'valor') ? 'selected="selected"' : '') + `>VALOR&sup1;</option>`;                
            }else if(formato == 'debehaber'){
                k = k + `<option value="debe" ` + ((r == 'debe') ? 'selected="selected"' : '') + `>DÉBITO&sup1;</option>
                <option value="haber" ` + ((r == 'haber') ? 'selected="selected"' : '') + `>CRÉDITO&sup1;</option>`
            }
            k = k + `</select>`;
        }else if(tipo == 'blp'){
            k = `<select name="` + name + `" class="form-control config-archivo" onchange="ARCHIVOS.methods.vista(true, '` + name + `')">
                <option value="string" ` + ((r == 'string') ? 'selected="selected"' : '') + `>LETRAS Y NUMEROS</option>
                <option value="float" ` + ((r == 'float') ? 'selected="selected"' : '') + `>NÚMERO DECIMAL</option>
                <option value="num" ` + ((r == 'num') ? 'selected="selected"' : '') + `>NÚMERO</option>
                <option value="date" ` + ((r == 'date') ? 'selected="selected"' : '') + `>FECHA</option>
                <option value="cta" ` + ((r == 'cta') ? 'selected="selected"' : '') + `>NÚMEROS DE CUENTAS&sup1;</option>
                <option value="ctan" ` + ((r == 'ctan') ? 'selected="selected"' : '') + `>NOMBRES DE CUENTAS&sup1;</option>
                <option value="valor" ` + ((r == 'valor') ? 'selected="selected"' : '') + `>SALDO FINAL&sup1;</option>
            </select>`;            
        }
        return k;
    },
    configModal: function (datos) {
        var deferred = $.Deferred();
        $.when(
            { datos: datos }
        ).then(function (datos) {
            var tipo = '', mov, blp, cxp, cxc, fnat, fdhb;
            switch (GLOBAL.archivoFormato) {
                case 'naturaleza':
                    fnat = 'selected';
                    break;
                case 'debehaber':
                    fdhb = 'selected';
                    break;
            }
            switch (GLOBAL.archivoTipo) {
                case 'mov':
                    tipo = 'Movimiento';
                    mov = 'selected';
                    break;
                case 'blp':
                    tipo = 'Balance de Prueba';
                    blp = 'selected';
                    break;
                case 'cxp':
                    tipo = 'Cuentas por Pagar';
                    cxp = 'selected';
                    break;
                case 'cxc':
                    tipo = 'Cuentas por Cobrar';
                    cxc = 'selected';
                    break;
                default:
                    tipo = 'N/A';
                    break;
            }                
            $('#ventanaModal .modal-body').html(
                `<p class="text-justify">
                    El archivo <span class="text-truncate" style="max-width: 250px;"><b>` + GLOBAL.archivoNombre + `</b></span>, se define como <b>` + tipo + `</b>, usted puede cambiar teniendo en cuenta que se deben redefinir nuevamente los tipos de columnas, 
                    los siguientes tipos marcados (<sup>1</sup>), no es posible seleccionar m&aacute;s de uno por archivo.
                </p>
                <form id="form-archivo-config">
                    <div class="form-group">
                        <div class="row" style="margin-right: -9px; margin-left: -9px;">
                            <div class="col-sm-4">
                                <label class="col-form-label text-muted" for="archivoTipo">Tipo de archivo</label>
                                <select class="form-control" id="archivoTipo" name="archivoTipo" onChange="ARCHIVOS.methods.cofingCambio();">
                                    <option value="mov" ` + mov + `>Movimiento</option>
                                    <option value="blp" ` + blp + `>Balance de Prueba</option>
                                    <option value="cxc" ` + cxc + ` disabled>Cuentas por Cobrar</option>
                                    <option value="cxp" ` + cxp + ` disabled>Cuentas por Pagar</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label class="col-form-label text-muted" for="archivoTipo">Formato</label>
                                <select class="form-control" id="archivoFormato" name="archivoFormato" onChange="ARCHIVOS.methods.cofingFormato();" ` + (GLOBAL.archivoTipo == 'blp' ? 'disabled':'') + `>
                                    <option value="naturaleza" ` + fnat + `>Columnas Naturaleza y Valor</option>
                                    <option value="debehaber" ` + fdhb + `>Columnas Debitos y Creditos</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="text-muted text-left" style="padding-top: calc(.375rem + 1px); padding-bottom: calc(.375rem + 1px);">&nbsp</div>
                                <button type="button" class="btn btn-primary btn-block" onClick="ARCHIVOS.computed.columnasPorDefecto()">
                                    <i class="fas fa-magic"></i>&nbspValores por defecto
                                </button>                                        
                            </div>
                        </div>
                    </div>
                    <div class="scrollTable" style="height: calc(100vh - 420px);"><table id="table-config-archivo" class="table table-hover table-striped"></table></div>
                </form>`
            );                
            return datos;
        }).then(function (datos) {
            var x = 0, columnDef;
            $.each(datos.datos.data.encabezado, function (key, value) {
                x++;
                if(GLOBAL.computed.array_key_exists(key, datos.datos.data.columnDef)){
                    columnDef = datos.datos.data.columnDef[key];
                }else{
                    columnDef = null;
                }
                $('form#form-archivo-config #table-config-archivo').append(
                    `<tr>
                        <td style="padding-top: 15px; height: 54px;" class="text-muted">` + x + ". " + value + `</td>
                        <td style="width: 250px;">
                            <div class="form-group">
                                ` + ARCHIVOS.componets.configTipo(datos.datos.data.tipo, datos.datos.data.formato, key, columnDef) + `
                            </div>                        
                        </td>
                    </tr>`
                );
            });
            deferred.resolve();
        });
        return deferred.promise();        
    },
    digitoModal: function () {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford-digito">
                <div class="row mb-3 filtro-benford">
                    <div class="col-sm-4">
                        <div class="text-wrap" style="padding-top: 7px;">
                          COLUMNA: <span id="span-columna">N/A</span>
                        </div>
                    </div>
                    <div class="col-sm-8">
                            <button id="filtro-digito" type="button" class="btn btn-formulario float-right">Filtrar</button>
                            <div class="input-group col-sm-4 float-right">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" style="font-size: 14px">Max</span>
                                </div>
                                <input style="font-size: 14px !important" type="text" id="max-benford" class="form-control" aria-label="Minimo" aria-describedby="basic-addon1">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="ARCHIVOS.methods.filtroMaterialidad('max-benford')" id="button-addon1"><i class="far fa-window-maximize"></i></button>
                                </div>
                            </div>
                            <div class="input-group col-sm-4 float-right">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" style="font-size: 14px">Min</span>
                                </div>
                                <input style="font-size: 14px !important" type="text" id="min-benford" class="form-control" aria-label="Minimo" aria-describedby="basic-addon1">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="ARCHIVOS.methods.filtroMaterialidad('min-benford')" id="button-addon2"><i class="far fa-window-maximize"></i></button>
                                </div>        
                            </div>                    
                    </div>
                </div>    
                <div class="scrollTable" style="overflow: hidden;">
                    <table id="table-digito" class="display table table-bordered table-hover table-sm table-striped" style="width: 100%;">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>`);
    },
    spiderModal: function (cuenta, spider, naturaleza) {
        $('#ventanaModal .modal-body').html(
            `<form id="form-benford-digito">
                <div class="row mb-3 filtro-benford">
                    <div class="col-sm-6">
                        <div class="text-wrap" style="padding-top: 7px;">
                          ARAÑA: <span id="span-cuenta">` + spider + `</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-wrap" style="padding-top: 7px;">
                          CUENTA: <span id="span-cuenta">` + cuenta + (naturaleza == 'd' ? ' Débito' : ' Crédito') + `</span>
                        </div>
                    </div>
                </div>    
                <div class="scrollTable" style="overflow: hidden;">
                    <table id="table-spider" class="display table table-bordered table-hover table-sm table-striped" style="width: 100%;">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>`);
    }
}
$(document).on('click','table.materialidad-filtro td span[id$="val"],table.materialidad-filtro td span[id$="mate"],table.materialidad-filtro td span[id$="erto"],table.materialidad-filtro td span[id$="imno"]', function () {
    let valor = $(this).html();
    $('input#' + ARCHIVOS.inputFiltro).val(valor.replaceAll('.',''));
    ARCHIVOS.modal.modal('hide');
});