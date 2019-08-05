<nav id="menu-validate" class="navbar navbar-expand-lg fixed-top menu-auditor shadow-sm">
    <div id="empresaActiva"></div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-principal" role="tabpanel" aria-labelledby="pills-principal-tab">
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-listar')?>" id="mpe-empresa-listar" onclick="EMPRESAS.methods.listarEmpresas(this)">
                    <i class="fas fa-archive"></i>
                    <span>Listar</span>
                </div>                
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="far fa-address-card"></i>
                    <span>&nbsp;&nbsp;Crear&nbsp;&nbsp;</span>
                </div>
                <?php /*<div class="icono < $this->permisos->viewaccess('mpe-empresa-propiedades')?>" id="mpe-empresa-propiedades">
                    <i class="far fa-building"></i>
                    <span>Propiedades</span>
                </div>*/ ?>
                <div class="titulo">Clientes</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-importar-archivo')?>" id="mpe-importar-archivo" onclick="ARCHIVOS.methods.cargarArchivo(this)">
                    <i class="fas fa-user-friends"></i>
                    <span>Listar</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mpe-importar-archivo')?>" id="mpe-importar-archivo" onclick="ARCHIVOS.methods.cargarArchivo(this)">
                    <i class="fas fa-user-plus"></i>
                    <span>Crear</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="fas fa-user-cog"></i>
                    <span>Roles</span>
                </div>                
                <div class="titulo">Usuarios</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="fas fa-user-tag"></i>
                    <span>Por vencer</span>
                </div>                
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="fas fa-user-times"></i>
                    <span>Morosos</span>
                </div>
                <div class="titulo">Filtrar Clientes</div>
            </div>
            <div class="menu-separador"></div>
            <div class="menu-card-icon">
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="fas fa-file-contract"></i>
                    <span>Vigentes</span>
                </div>
                <div class="icono <?=$this->permisos->viewaccess('mpe-empresa-crear')?>" id="mpe-empresa-crear" onclick="EMPRESAS.methods.crearEmpresas(this)">
                    <i class="far fa-handshake"></i>
                    <span>Presupuesto</span>
                </div>
                <div class="titulo">Contratos</div>
            </div>            
        </div>
    </div>            
</nav>