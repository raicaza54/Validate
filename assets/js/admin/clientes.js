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
var ADMINCLIENTES = ADMINCLIENTES || {};
//Procesos
ADMINCLIENTES.methods = {
    listar: function () {
        
    }
}
//Conexion con backend
ADMINCLIENTES.computed = {
    dataListar: function () {
        
    }
}
//Html
ADMINCLIENTES.componets = {
    tabs: function () {
        $('#content').html(
            `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-clientes-tab" data-toggle="tab" href="#nav-clientes" role="tab" aria-controls="nav-clientes" aria-selected="true">Clientes</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active clearfix" id="nav-clientes" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <div id="body-clientes">
                        <ul class="nav position-relative">
                            <li class="nav-item" onclick="EMPRESAS.methods.activarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-check"></i> Seleccionar</a>
                            </li>
                            <li class="nav-item" onclick="EMPRESAS.methods.editarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-pen"></i> Editar</a>
                            </li>
                            <li class="nav-item" onclick="EMPRESAS.methods.eliminarEmpresa(this)">
                                <a class="nav-link" href="#"><i class="fas fa-trash"></i> Eliminar</a>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px;" id="maximizar">
                                <span id="requestfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.maximizar(GLOBAL.computed.dtable)"><i class="far fa-window-maximize"></i> Pantalla Completa</span>
                            </li>
                            <li class="nav-item position-absolute" style="right: 0px; display: none;" id="restaurar">
                                <span id="exitfullscreen" class="nav-link btn-span" onclick="GLOBAL.computed.restaurar(GLOBAL.computed.dtable)"><i class="far fa-window-restore"></i> Restaurar</span>
                            </li>
                        </ul>
                        <div class="scrollTable" style="overflow: hidden;">
                            <table id="table-clientes" class="display table table-bordered table-hover table-sm table-striped" style="width: 100%">
                                <thead style="width: 100%;">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>NIT</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`);
    }    
}
