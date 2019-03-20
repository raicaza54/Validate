<nav class="navbar navbar-expand-lg fixed-top menu-auditor">
    <div id="empresaActiva"></div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-principal" role="tabpanel" aria-labelledby="pills-principal-tab">
            <div class="menu-card-icon">
                <div class="icono" id="mn-crear" data-toggle="popover" data-placement="bottom" data-content="Creación de nuevas empresas">
                    <i class="far fa-hospital"></i>
                    <span>Crear</span>
                </div>
                <div class="icono" id="mn-propiedades">
                    <i class="far fa-building"></i>
                    <span>Propiedades</span>
                </div>
                <div class="icono" id="mn-listar" onclick="EMPRESAS.methods.listarEmpresas()">
                    <i class="fas fa-city"></i>
                    <span>Listar</span>
                </div>
                <div class="titulo">Empresas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="mn-archivo" onclick="ARCHIVOS.methods.cargarArchivo()">
                    <i class="fas fa-file-upload"></i>
                    <span>Archivo</span>
                </div>
                <div class="icono" id="mn-conexion">
                    <i class="fas fa-network-wired"></i>
                    <span>Conexi&oacute;n</span>
                </div>
                <div class="titulo">Importar</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="mn-CSV" data-toggle="popover" data-placement="bottom" data-content="Se exporta la base de datos en un archivo CSV separado por punto y comas (;)">
                    <i class="fas fa-file-csv"></i>
                    <span>CSV</span>
                </div>
                <div class="icono" id="mn-excel">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </div>
                <div class="titulo">Exportar</div>
            </div>
            
        </div>
        <div class="tab-pane fade" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
            <div class="menu-card-icon">
                <div class="icono" id="mn-duplicar">
                    <i class="fas fa-copy"></i>
                    <span>Duplicar</span>
                </div>
                <div class="titulo">Datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="mn-indices">
                    <i class="fas fa-sort-amount-up"></i>
                    <span>Indice</span>
                </div>
                <div class="icono" id="mn-ordenar">
                    <i class="fas fa-sort-numeric-down"></i>
                    <span>Ordenar</span>
                </div>
                <div class="titulo">Orden</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="mn-filtrar">
                    <i class="fas fa-filter"></i>
                    <span>Filtrar</span>
                </div>
                <div class="icono" id="mn-siguiente">
                    <i class="fas fa-arrow-alt-circle-right"></i>
                    <span>Buscar Siguiente</span>
                </div>
                <div class="icono" id="mn-datoIr">
                    <i class="fab fa-searchengin"></i>
                    <span>Ir a</span>
                </div>
                <div class="titulo">B&uacute;squeda</div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-analisis" role="tabpanel" aria-labelledby="pills-analisis-tab">
            <div class="menu-card-icon">
                <div class="icono" id="mn-ejecutar">
                    <i class="fas fa-retweet"></i>
                    <span>Volver a Ejecutar</span>
                </div>
                <div class="titulo">Tareas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" id="mn-benford" onclick="BENFORD.methods.parametros();">
                    <i class="fas fa-chart-bar"></i> 
                    <span>Ley de Benford</span>
                </div>
                <div class="icono" id="mn-spider" onclick="SPIDER.methods.parametros();">
                    <i class="fas fa-spider"></i>
                    <span>Araña</span>
                </div>
                <div class="icono" id="mn-manipulacion" onclick="MANIPULACION.methods.parametros();">
                    <i class="fas fa-user-edit"></i>
                    <span>Manipulaci&oacute;n</span>
                </div>
                <div class="icono" id="mn-materialidad">
                    <i class="fas fa-vote-yea"></i>
                    <span>Materialidad</span>
                </div>
                <div class="titulo">An&aacute;lisis de datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card">
                <div class="d-flex flex-column">
                    <span id="mn-dictamen">
                        <i class="far fa-comment-dots"></i> Dictamen
                    </span>                    
                    <span id="mn-papeles">
                        <i class="fas fa-mail-bulk"></i> Papeles de Trabajo
                    </span>
                </div>
                <div class="d-flex flex-column">
                    <span id="mn-marcas">
                        <i class="fas fa-clipboard-check"></i> Marcas de Auditoria
                    </span>
                    <span id="mn-analisis">
                        <i class="fas fa-chart-line"></i> An&aacute;lisis de CXP/CXC
                    </span>
                </div>
                <div class="titulo">Documentos</div>
            </div>
        </div>
    </div>            
</nav>