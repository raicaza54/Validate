<nav class="navbar navbar-expand-lg fixed-top menu-auditor">
    <div id="empresaActiva"></div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-principal" role="tabpanel" aria-labelledby="pills-principal-tab">
            <div class="menu-card-icon">
                <div class="icono" id="PrinCrear" data-toggle="popover" data-placement="bottom" data-content="Creación de nuevas empresas">
                    <i class="far fa-hospital"></i>
                    <span>Crear</span>
                </div>
                <div class="icono" id="PrinPropiedades">
                    <i class="far fa-building"></i>
                    <span>Propiedades</span>
                </div>
                <div class="icono" id="PrinListar" onclick="EMPRESAS.methods.listarEmpresas()">
                    <i class="fas fa-city"></i>
                    <span>Listar</span>
                </div>
                <div class="titulo">Empresas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="PrinArchivo">
                    <i class="fas fa-file-upload"></i>
                    <span>Archivo</span>
                </div>
                <div class="icono" id="PrinConexion">
                    <i class="fas fa-network-wired"></i>
                    <span>Conexi&oacute;n</span>
                </div>
                <div class="titulo">Importar</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="PrinCSV" data-toggle="popover" data-placement="bottom" data-content="Se exporta la base de datos en un archivo CSV separado por punto y comas (;)">
                    <i class="fas fa-file-csv"></i>
                    <span>CSV</span>
                </div>
                <div class="icono" id="PrinExcel">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </div>
                <div class="titulo">Exportar</div>
            </div>
            <div class="menu-separador"></div>
        </div>
        <div class="tab-pane fade" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
            <div class="menu-card-icon">
                <div class="icono" id="DatoDuplicar">
                    <i class="fas fa-copy"></i>
                    <span>Duplicar</span>
                </div>
                <div class="titulo">Datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="DatoIndices">
                    <i class="fas fa-sort-amount-up"></i>
                    <span>Indice</span>
                </div>
                <div class="icono" id="DatoOrdenar">
                    <i class="fas fa-sort-numeric-down"></i>
                    <span>Ordenar</span>
                </div>
                <div class="titulo">Orden</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="DatoBuscar">
                    <i class="fas fa-search"></i>
                    <span>Buscar</span>
                </div>
                <div class="icono" id="DatoSiguiente">
                    <i class="fas fa-arrow-alt-circle-right"></i>
                    <span>Buscar Siguiente</span>
                </div>
                <div class="icono" id="DatoIr">
                    <i class="fas fa-eye"></i>
                    <span>Ir a</span>
                </div>
                <div class="titulo">B&uacute;squeda</div>
            </div>
            <div class="menu-separador"></div>
        </div>
        <div class="tab-pane fade" id="pills-analisis" role="tabpanel" aria-labelledby="pills-analisis-tab">
            <div class="menu-card-icon">
                <div class="icono" id="AnalEjecutar">
                    <i class="fas fa-retweet"></i>
                    <span>Volver a Ejecutar</span>
                </div>
                <div class="titulo">Tareas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card">
                <div class="d-flex flex-column">
                    <span id="AnalBenford" onclick="BENFORD.methods.parametros();">
                        <i class="fas fa-chart-bar"></i> Ley de Benford
                    </span>                    
                    <span id="AnalOmisiones">
                        <i class="fas fa-list-alt"></i> Detectar omisiones
                    </span>
                </div>
                <div class="d-flex flex-column">
                    <span id="AnalDuplicados">
                        <i class="fas fa-list-alt"></i> Clave duplicados
                    </span>
                    <span id="AnalSpider">
                        <i class="fas fa-spider"></i> Araña
                    </span>
                </div>
                <div class="titulo">Explorar</div>
            </div>
            <div class="menu-separador"></div>
        </div>
    </div>            
</nav>