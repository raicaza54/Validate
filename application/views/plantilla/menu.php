<nav id="menu-validate" class="navbar navbar-expand-lg fixed-top menu-auditor shadow-sm">
    <div id="empresaActiva"></div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-principal" role="tabpanel" aria-labelledby="pills-principal-tab">
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-listar')?>" id="mpe-empresa-listar" onclick="EMPRESAS.methods.listarEmpresas(this)">
                    <i class="fas fa-city"></i>
                    <span>Listar</span>
                </div>                
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="far fa-hospital"></i>
                    <span>Crear</span>
                </div>
                <?php /*<div class="icono < $this->permisos->viewaccess('mpe-empresa-propiedades')?>" id="mpe-empresa-propiedades">
                    <i class="far fa-building"></i>
                    <span>Propiedades</span>
                </div>*/ ?>
                <div class="titulo">Empresas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-importar-archivo')?>" id="mpe-importar-archivo" onclick="ARCHIVOS.methods.cargarArchivo(this)">
                    <i class="fas fa-file-upload"></i>
                    <span>Archivo</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mpe-importar-conexion')?>" id="mpe-importar-conexion">
                    <i class="fas fa-network-wired"></i>
                    <span>Conexi&oacute;n</span>
                </div>
                <div class="titulo">Importar</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-exportar-csv')?>" id="mpe-exportar-csv" data-toggle="popover" data-placement="bottom" data-content="Se exporta la base de datos en un archivo CSV separado por punto y comas (;)">
                    <i class="fas fa-file-csv"></i>
                    <span>CSV</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mpe-exportar-excel')?>" id="mpe-exportar-excel" onclick="ARCHIVOS.methods.descargarExcel(this)">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </div>
                <div class="titulo">Exportar</div>
            </div>
            
        </div>
        <div class="tab-pane fade" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mda-datos-duplicar')?>" id="mda-datos-duplicar">
                    <i class="fas fa-copy"></i>
                    <span>Duplicar</span>
                </div>
                <div class="titulo">Datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mda-orden-indice')?>" id="mda-orden-indice">
                    <i class="fas fa-sort-amount-up"></i>
                    <span>Indice</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mda-orden-columna')?>" id="mda-orden-columna">
                    <i class="fas fa-sort-numeric-down"></i>
                    <span>Ordenar</span>
                </div>
                <div class="titulo">Orden</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mda-busqueda-filtrar')?>" id="mda-busqueda-filtrar">
                    <i class="fas fa-filter"></i>
                    <span>Filtrar</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mda-busqueda-siguiente')?>" id="mda-busqueda-siguiente">
                    <i class="fas fa-arrow-alt-circle-right"></i>
                    <span>Buscar Siguiente</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mda-busqueda-ir')?>" id="mda-busqueda-ir">
                    <i class="fab fa-searchengin"></i>
                    <span>Ir a</span>
                </div>
                <div class="titulo">B&uacute;squeda</div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-analisis" role="tabpanel" aria-labelledby="pills-analisis-tab">
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mal-ejecutar-recargar')?>" id="mal-ejecutar-recargar">
                    <i class="fas fa-retweet"></i>
                    <span>Volver a Ejecutar</span>
                </div>
                <div class="titulo">Tareas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mal-analisis-benford')?>" id="mal-analisis-benford" onclick="BENFORD.methods.parametros(this);">
                    <i class="fas fa-chart-bar"></i> 
                    <span>Ley de Benford</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mal-analisis-spider')?>" id="mal-analisis-spider" onclick="SPIDER.methods.parametros(this);">
                    <i class="fas fa-spider"></i>
                    <span>Araña</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mal-analisis-manipulacion')?>" id="mal-analisis-manipulacion" onclick="MANIPULACION.methods.parametros(this);">
                    <i class="fas fa-user-secret"></i>
                    <span>Manipulaci&oacute;n</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mal-analisis-listas')?>" id="mal-analisis-listas">
                    <i class="fas fa-fire"></i>
                    <span>Listas de Control</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mal-analisis-materialidad')?>" id="mal-analisis-materialidad">
                    <i class="fas fa-vote-yea"></i>
                    <span>Materialidad</span>
                </div>                
                <div class="titulo">An&aacute;lisis de datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card">
                <div class="d-flex flex-column">
                    <span class="<?=$this->permisos->viewaccess('mal-documentos-dictamen')?>" id="mal-documentos-dictamen">
                        <i class="far fa-comment-dots"></i> Dictamen
                    </span>                    
                    <span class="<?=$this->permisos->viewaccess('mal-documentos-papeles')?>" id="mal-documentos-papeles">
                        <i class="fas fa-mail-bulk"></i> Papeles Certificados
                    </span>
                </div>
                <div class="d-flex flex-column">
                    <span class="<?=$this->permisos->viewaccess('mal-documentos-marcas')?>" id="mal-documentos-marcas">
                        <i class="fas fa-clipboard-check"></i> Marcas de Auditoria
                    </span>
                    <span class="<?=$this->permisos->viewaccess('mal-documentos-cxpc')?>" id="mal-documentos-cxpc">
                        <i class="fas fa-chart-line"></i> An&aacute;lisis de CXP/CXC
                    </span>
                </div>
                <div class="titulo">Documentos</div>
            </div>
        </div>
    </div>            
</nav>