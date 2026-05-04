/**
 * Materialidad - jQuery plugin 0.0.1
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2020-12-19
 */
var MATERIALIDAD = MATERIALIDAD || {};
MATERIALIDAD.cuentas = [];
MATERIALIDAD.methods = {
    parametros: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Balance de Pruebas 
                    para realizar la Materialidad`);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Balance de Pruebas 
                    para realizar la Materialidad`);
                return;
            }
            if(typeof idArchivo !== 'undefined'){
                GLOBAL.archivoId = idArchivo;
            }
            MATERIALIDAD.methods.valores();
        }
    },
    recalcular: function (obj) {
        if((!$(obj).hasClass('item-disabled')) || (obj == true)){
            if(GLOBAL.computed.isNull(GLOBAL.empresaId)){
                GLOBAL.computed.toast(
                    `Debes seleccionar una empresa y un archivo de Balance de Pruebas 
                    para realizar la Materialidad`);
                return;
            }
            if(parseInt(GLOBAL.archivoId) <= 0){
                GLOBAL.computed.toast(
                    `Debes seleccionar un archivo de Balance de Pruebas 
                    para realizar la Materialidad`);
                return;
            }
            if(typeof idArchivo !== 'undefined'){
                GLOBAL.archivoId = idArchivo;
            }
            MATERIALIDAD.methods.valores(1);
        }
    },
    valores: function(recalcular = 0) {
        var datos = [];
        if(recalcular == 1){
            datos = MATERIALIDAD.computed.recalcular(GLOBAL.archivoId);
        }else{
            datos = MATERIALIDAD.computed.cargaDatos(GLOBAL.archivoId);
        }
        datos.then(function (data) {
            if(data.status == 200){
                MATERIALIDAD.componets.procesar(data);
            }
            return data;
        }).then(function (data) {
            if(data.status == 200){
                IMask(document.getElementById('utladi'), {mask: Number, scale: 1, min: 5, max: 10, thousandsSeparator: '.'});
                IMask(document.getElementById('utlope'), {mask: Number, scale: 1, min: 7, max: 10, thousandsSeparator: '.'});                    
                IMask(document.getElementById('utlbru'), {mask: Number, scale: 1, min: 3, max: 5, thousandsSeparator: '.'});                    
                IMask(document.getElementById('ingope'), {mask: Number, scale: 1, min: 0.5, max: 1, thousandsSeparator: '.'});                    
                IMask(document.getElementById('activo'), {mask: Number, scale: 1, min: 0.5, max: 1, thousandsSeparator: '.'});                    
                IMask(document.getElementById('patrim'), {mask: Number, scale: 1, min: 5, max: 7, thousandsSeparator: '.'});
            }
            $("body").mLoading('hide');
            return data;
        }).then(function (data) {
            if(data.data.utladi == undefined) MATERIALIDAD.methods.calculos();
            GLOBAL.computed.maximizar();
        });        
    },
    calculos: function(){
        $(['utladi','utlope','utlbru','ingope','activo','patrim']).each(function (id, val){
            let rango = $('input#' + val).val().replace(',','.');
            let valor = $('input#' + val + '_val').val();
            let mate = (rango * valor) / 100;
            let erto = (75 * mate) / 100;
            let imno = (5 * mate) / 100;
            $('input#' + val + '_mate').val(mate);
            $('input#' + val + '_erto').val(erto);
            $('input#' + val + '_imno').val(imno);
            $('span#' + val + '_mate').html(GLOBAL.computed.number_format(mate,2,',','.'));
            $('span#' + val + '_erto').html(GLOBAL.computed.number_format(erto,2,',','.'));
            $('span#' + val + '_imno').html(GLOBAL.computed.number_format(imno,2,',','.'));            
        });
    },
    verMas: function(e) {
        if ($('div.more').is(':visible')) {
            $('div.more').slideUp(400, function(){
                $(e).html('Leer mas');
            });
        } else {
            $('div.more').slideDown(400, function(){
                $(e).html('Leer menos');
            });
        }
    }
}
MATERIALIDAD.computed = {
    cargaDatos: function (id) {
        return $.ajax({
            url: '/materialidad/v1/parametros',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            beforeSend: function (xhr) {
                $("body").mLoading();
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    $("body").mLoading('hide');
                }
                GLOBAL.computed.secure();
            }
        });
    },
    recalcular: function (id) {
        return $.ajax({
            url: '/materialidad/v1/recalcular',
            type: "POST",
            dataType: 'json',
            data:{id: id},
            beforeSend: function (xhr) {
                $("body").mLoading();
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    $("body").mLoading('hide');
                }
                GLOBAL.computed.secure();
            }
        });
    },
    guardarDatos: function (id) {
        var form_data = $('form#form-materialidad').serializeArray();
        form_data.push({ name: "archivoId", value: GLOBAL.archivoId });
        $('div.summernote.wysiwyg').each(function (k,v) {
            form_data.push({ name: $(v).attr('name'), value: v.innerHTML });
        });        
        return $.ajax({
            url: '/materialidad/v1/save',
            type: "POST",
            dataType: 'json',
            data: {form: form_data},
            beforeSend: function (xhr) {
                $("body").mLoading();
            },
            complete: function (jqXHR, textStatus) {
                var data = jqXHR.responseJSON;
                var status = parseInt(data.status);
                if(status != 200){
                    GLOBAL.computed.toast(data.detail, status);
                }else{
                    $("body").mLoading('hide');
                    GLOBAL.computed.toast(`Se han almacenado los datos correctamente`);                    
                }
                GLOBAL.computed.secure();
            }
        });
    }
}
MATERIALIDAD.componets = {
    procesar: function (e) {
        $('#content').html(`
            <nav id="tabs-materialidad">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-materialidad-tab" data-toggle="tab" href="#nav-materialidad" role="tab" aria-controls="nav-materialidad" aria-selected="true">Materialidad</a>
                    <a class="nav-item nav-link nav-file" href="#" onClick="GLOBAL.computed.selectNode()" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + ((GLOBAL.archivoNombre != null) ? GLOBAL.archivoNombre : '')  + `<span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-materialidad" role="tabpanel" aria-labelledby="nav-materialidad-tab">
                    <div id="body-archivo">
                        <ul class="nav position-relative" id="menu-tab" role="tablist">
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="MATERIALIDAD.computed.guardarDatos(true)">
                                    <i class="fas fa-materialidad"></i>
                                    Guardar
                                </span>
                            </li>        
                            <li class="nav-item">
                                <span class="nav-link btn-span" onClick="RESULTADOS.methods.modalResultados(this, 'materialidad')">
                                    Aplicar y Guardar PDF
                                </span>
                            </li>` +
                            (e.data.archivoeq == 0 ?
                            `<li class="nav-item">
                                <span class="nav-link btn-span" onClick="MATERIALIDAD.methods.recalcular(this);">
                                    <i class="fas fa-materialidad"></i>
                                    Recalcular valores del nuevo archivo
                                </span>
                            </li>` : '')
                            + `<li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.materialidad)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.materialidad)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>            
                        </ul>
                        <div class="scrollTable">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="spills-d1" role="tabpanel" aria-labelledby="spills-d1-tab">
                                    <div class="summernote wysiwyg summernote-addinit" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer1">` + (e.data.hasOwnProperty('summer1') ? e.data.summer1 : '') + `</div>
                                    <div id="body-materialidad" style="margin: 0 auto;">
                                        <form id="form-materialidad" autocomplete="off">` + 
                                            (e.data.archivoeq == 0 ?
                                            `<div class="alert alert-warning mb-2" role="alert" style="margin-bottom: 0px">
                                                El archivo que se ha seleccionado no corresponde con el archivo que se ha calculado la materialidad, desea recalcular los valores con el nuevo archivo seleccionado
                                            </div>` : '')
                                            + `<div style="padding: 10px 10px 0px 10px;">
                                                <p>
                                                    Tenga en cuenta lo siguiente:
                                                </p>
                                                    <ul style="margin-bottom: 0px" class="ul-no-padding">
                                                        <li>La estructura de propiedad de la entidad y la forma en la que se financia (por ejemplo, si una entidad se financia sólo mediante deuda en lugar de patrimonio, los usuarios pueden prestar mayor atención a los activos, y a los derechos sobre estos, que a los beneficios de la entidad.</li>
                                                    </ul>
                                                    <div class="more" style="display: none">
                                                        <ul class="ul-no-padding">
                                                            <li>El beneficio antes de impuestos de las operaciones continuadas se utiliza a menudo para entidades con fines de lucro. Cuando el beneficio antes de impuestos de las operaciones continuadas es volátil, pueden ser adecuadas otras referencias, tales como el margen bruto o los ingresos ordinarios totales.</li>
                                                            <li>En relación con la referencia elegida, los datos financieros relevantes por lo general incluyen los resultados y las situaciones financieras de periodos anteriores, los resultados y la situación financiera hasta la fecha, así como los presupuestos y los pronósticos para el periodo actual, ajustados para tener en cuenta tanto cambios significativos en las circunstancias de la entidad (por ejemplo, una adquisición de un negocio significativo) como cambios relevantes en las condiciones del entorno económico o sectorial en el que la entidad opera. Por ejemplo, cuando para una determinada entidad, la importancia relativa para los estados financieros en su conjunto se determina, como punto de partida, sobre la base de un porcentaje del beneficio antes de impuestos de las operaciones continuadas, si concurren circunstancias que dan lugar a una reducción o aumento excepcional de dicho beneficio, el auditor puede llegar a la conclusión de que para calcular la importancia relativa para los estados financieros en su conjunto es más adecuado utilizar una cifra normalizada de beneficio antes de impuestos de las operaciones continuadas, basada en resultados pasados.</li>
                                                        </ul>        
                                                        Existe una relación entre el porcentaje y la referencia elegida, de tal modo que un porcentaje aplicado al beneficio antes de impuestos de las operaciones continuadas será por lo general mayor que el porcentaje que se aplique a los ingresos ordinarios totales. Por ejemplo, el auditor puede considerar que el cinco por ciento del beneficio antes de impuestos de las operaciones continuadas es adecuado para una entidad con fines de lucro en un sector industrial, mientras que puede considerar que el uno por ciento de los ingresos ordinarios totales o de los gastos totales es apropiado para una entidad sin fines de lucro. Sin embargo, según las circunstancias, pueden considerarse adecuados porcentajes mayores o menores.
                                                    </div>
                                                <p class="clearfix mb-0">
                                                    <a class="more float-right" href="#!" onclick="MATERIALIDAD.methods.verMas(this)">Leer mas</a>
                                                </p>
                                            </div>
                                            <a class="nav-item nav-link px-0 py-0 float-left" href="#" onClick="GLOBAL.computed.selectNodeId(` + e.data.archivo.id + `)" aria-controls="nav-archivo" aria-selected="false"><span class="d-inline-block text-truncate" id="archivoNombre" style="max-width: 500px;">` + e.data.archivo.nombre  + `<span></a>
                                            <table class="table table-striped table-hover table-bordered">
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
                                                        <td class="text-right"><input autocomplete="false" id="utladi" name="utladi" value="` + GLOBAL.computed.number_format((e.data.utladi ? e.data.utladi : '5'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.utladi_val,2,',','.') + `<input id="utladi_val" name="utladi_val" type="hidden" value="` + e.data.utladi_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="utladi_mate" name="utladi_mate" value="` + e.data.utladi_mate + `"/><span id="utladi_mate">` + GLOBAL.computed.number_format(e.data.utladi_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utladi_erto" name="utladi_erto" value="` + e.data.utladi_erto + `"/><span id="utladi_erto">` + GLOBAL.computed.number_format(e.data.utladi_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utladi_imno" name="utladi_imno" value="` + e.data.utladi_imno + `"/><span id="utladi_imno">` + GLOBAL.computed.number_format(e.data.utladi_imno,2,',','.') + `</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Utilidad Operacional</td>
                                                        <td class="text-right">7,0% a 10,0%</td>        
                                                        <td class="text-right"><input autocomplete="false" id="utlope" name="utlope" value="` + GLOBAL.computed.number_format((e.data.utlope ? e.data.utlope : '7'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.utlope_val,2,',','.') + `<input id="utlope_val" name="utlope_val" type="hidden" value="` + e.data.utlope_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="utlope_mate" name="utlope_mate" value="` + e.data.utlope_mate + `"/><span id="utlope_mate">` + GLOBAL.computed.number_format(e.data.utlope_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utlope_erto" name="utlope_erto" value="` + e.data.utlope_erto + `"/><span id="utlope_erto">` + GLOBAL.computed.number_format(e.data.utlope_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utlope_imno" name="utlope_imno" value="` + e.data.utlope_imno + `"/><span id="utlope_imno">` + GLOBAL.computed.number_format(e.data.utlope_imno,2,',','.') + `</span></td>        
                                                    </tr>        
                                                    <tr>
                                                        <td class="text-left">Utilidad Bruta</td>
                                                        <td class="text-right">3,0% a 5,0%</td>        
                                                        <td class="text-right"><input autocomplete="false" id="utlbru" name="utlbru" value="` + GLOBAL.computed.number_format((e.data.utlbru ? e.data.utlbru : '3'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>        
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.utlbru_val,2,',','.') + `<input id="utlbru_val" name="utlbru_val" type="hidden" value="` + e.data.utlbru_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="utlbru_mate" name="utlbru_mate" value="` + e.data.utlbru_mate + `"/><span id="utlbru_mate">` + GLOBAL.computed.number_format(e.data.utlbru_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utlbru_erto" name="utlbru_erto" value="` + e.data.utlbru_erto + `"/><span id="utlbru_erto">` + GLOBAL.computed.number_format(e.data.utlbru_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="utlbru_imno" name="utlbru_imno" value="` + e.data.utlbru_imno + `"/><span id="utlbru_imno">` + GLOBAL.computed.number_format(e.data.utlbru_imno,2,',','.') + `</span></td>        
                                                    </tr>        
                                                    <tr>        
                                                        <td class="text-left">Ingresos Operacionales</td>
                                                        <td class="text-right">0,5% a 1,0%</td>        
                                                        <td class="text-right"><input autocomplete="false" id="ingope" name="ingope" value="` + GLOBAL.computed.number_format((e.data.ingope ? e.data.ingope : '0,5'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>        
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.ingope_val,2,',','.') + `<input id="ingope_val" name="ingope_val" type="hidden" value="` + e.data.ingope_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="ingope_mate" name="ingope_mate" value="` + e.data.ingope_mate + `"/><span id="ingope_mate">` + GLOBAL.computed.number_format(e.data.ingope_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="ingope_erto" name="ingope_erto" value="` + e.data.ingope_erto + `"/><span id="ingope_erto">` + GLOBAL.computed.number_format(e.data.ingope_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="ingope_imno" name="ingope_imno" value="` + e.data.ingope_imno + `"/><span id="ingope_imno">` + GLOBAL.computed.number_format(e.data.ingope_imno,2,',','.') + `</span></td>        
                                                    </tr>        
                                                        <td class="text-left">Activos</td>
                                                        <td class="text-right">0,5% a 1,0%</td>        
                                                        <td class="text-right"><input autocomplete="false" id="activo" name="activo" value="` + GLOBAL.computed.number_format((e.data.activo ? e.data.activo : '0,5'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>        
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.activo_val,2,',','.') + `<input id="activo_val" name="activo_val" type="hidden" value="` + e.data.activo_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="activo_mate" name="activo_mate" value="` + e.data.activo_mate + `"/><span id="activo_mate">` + GLOBAL.computed.number_format(e.data.activo_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="activo_erto" name="activo_erto" value="` + e.data.activo_erto + `"/><span id="activo_erto">` + GLOBAL.computed.number_format(e.data.activo_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="activo_imno" name="activo_imno" value="` + e.data.activo_imno + `"/><span id="activo_imno">` + GLOBAL.computed.number_format(e.data.activo_imno,2,',','.') + `</span></td>        
                                                    </tr>        
                                                    <tr>        
                                                        <td class="text-left">Patrimonio</td>
                                                        <td class="text-right">5,0% a 7,0%</td>        
                                                        <td class="text-right"><input autocomplete="false" id="patrim" name="patrim" value="` + GLOBAL.computed.number_format((e.data.patrim ? e.data.patrim : '5'),2,',','.') + `" class="form-control text-right" maxlength="10" onkeyup="MATERIALIDAD.methods.calculos()"></td>
                                                        <td class="text-right">` + GLOBAL.computed.number_format(e.data.patrim_val,2,',','.') + `<input id="patrim_val" name="patrim_val" type="hidden" value="` + e.data.patrim_val + `" /></td>
                                                        <td class="text-right"><input type="hidden" id="patrim_mate" name="patrim_mate" value="` + e.data.patrim_mate + `"/><span id="patrim_mate">` + GLOBAL.computed.number_format(e.data.patrim_mate,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="patrim_erto" name="patrim_erto" value="` + e.data.patrim_erto + `"/><span id="patrim_erto">` + GLOBAL.computed.number_format(e.data.patrim_erto,2,',','.') + `</span></td>
                                                        <td class="text-right"><input type="hidden" id="patrim_imno" name="patrim_imno" value="` + e.data.patrim_imno + `"/><span id="patrim_imno">` + GLOBAL.computed.number_format(e.data.patrim_imno,2,',','.') + `</span></td>        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>        
                                    </div>
                                    <div class="summernote wysiwyg summernote-addend" onclick="GLOBAL.componets.editarSummer(this)" title="Click para editar" name="summer2">` + (e.data.hasOwnProperty('summer2') ? e.data.summer2 : '')  + `</div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>`);        
    }
}