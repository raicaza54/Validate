<nav class="navbar navbar-expand-lg fixed-top menu-auditor">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-principal" role="tabpanel" aria-labelledby="pills-principal-tab">
            <div class="menu-card-icon">
                <div class="icono item-disabled" data-toggle="popover" data-placement="bottom" data-content="Creación de nuevas empresas">
                    <?= asset_image('svg/120-browser-9.svg') ?>
                    <span>Crear</span>
                </div>
                <div class="icono">
                    <?= asset_image('svg/125-open-book.svg') ?>
                    <span>Propiedades</span>
                </div>
                <div class="icono">
                    <?= asset_image('svg/110-analytics-21.svg') ?>
                    <span>Seleccionar</span>
                </div>
                <div class="titulo">Empresas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono">
                    <?= asset_image('svg/063-server-2.svg') ?>
                    <span>Archivo</span>
                </div>
                <div class="icono item-disabled">
                    <?= asset_image('svg/117-analytics-23.svg') ?>
                    <span>Conexi&oacute;n</span>
                </div>
                <div class="titulo">Importar</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono" data-toggle="popover" data-placement="bottom" data-content="Se exporta la base de datos en un archivo CSV separado por punto y comas (;)">
                    <?= asset_image('svg/085-txt.svg') ?>
                    <span>CSV</span>
                </div>
                <div class="icono item-disabled">
                    <?= asset_image('svg/075-xls.svg') ?>
                    <span>Excel</span>
                </div>
                <div class="titulo">Exportar</div>
            </div>
            <div class="menu-separador"></div>
        </div>
        <div class="tab-pane fade" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
            <div class="menu-card-icon">
                <div class="icono item-disabled">
                    <?= asset_image('svg/036-database.svg') ?>
                    <span>Duplicar</span>
                </div>
                <div class="titulo">Datos</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono item-disabled">
                    <?= asset_image('svg/001-data-storage.svg') ?>
                    <span>Indice</span>
                </div>
                <div class="icono item-disabled">
                    <?= asset_image('svg/068-server-7.svg') ?>
                    <span>Ordenar</span>
                </div>
                <div class="titulo">Orden</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono">
                    <?= asset_image('svg/102-analytics-16.svg') ?>
                    <span>Buscar</span>
                </div>
                <div class="icono">
                    <?= asset_image('svg/044-resume.svg') ?>
                    <span>Buscar Siguiente</span>
                </div>
                <div class="icono">
                    <?= asset_image('svg/005-algorithm.svg') ?>
                    <span>Ir a</span>
                </div>
                <div class="titulo">B&uacute;squeda</div>
            </div>
            <div class="menu-separador"></div>
        </div>
        <div class="tab-pane fade" id="pills-analisis" role="tabpanel" aria-labelledby="pills-analisis-tab">
            <div class="menu-card-icon">
                <div class="icono">
                    <?= asset_image('svg/070-analytics.svg') ?>
                    <span>Ejecutar</span>
                </div>
                <div class="titulo">Tareas</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card">
                <div class="d-flex flex-column">
                    <span>
                        <i class="fas fa-list-alt"></i> Ley de Benford
                    </span>                    
                    <span class="item-disabled">
                        <i class="fas fa-list-alt"></i> Detectar omisiones
                    </span>
                </div>
                <div class="d-flex flex-column">
                    <span class="item-disabled">
                        <i class="fas fa-list-alt"></i> Clave duplicados
                    </span>
                    <span>
                        <i class="fas fa-list-alt"></i> Araña
                    </span>
                </div>
                <div class="titulo">Explorar</div>
            </div>
            <div class="menu-separador"></div>
        </div>
    </div>            
</nav>