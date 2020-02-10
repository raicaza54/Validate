<?php include_once 'head.php'; ?>
    <body>
        <nav class="navbar navbar-expand-sm fixed-top bg-auditor contenedor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <?= asset_image('logo3.png" style="height: 30px;"')?>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills" id="pills-tab" role="tablist" style="margin-left: 40px;">
                    <li class="nav-item">
                        <a class="nav-link nav-icon active" id="pills-principal-tab" data-toggle="pill" href="#pills-principal" role="tab" aria-controls="pills-principal" aria-selected="true">Principal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon" id="pills-datos-tab" data-toggle="pill" href="#pills-datos" role="tab" aria-controls="pills-datos" aria-selected="false">Datos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon" id="pills-analisis-tab" data-toggle="pill" href="#pills-analisis" role="tab" aria-controls="pills-analisis" aria-selected="false">Analizar</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav">
                <?php if(in_array('1', $this->session->grupos)): ?>
                <a class="nav-link active nav-icon" href="admin" style="font-size: 1rem;">
                    Administrador
                </a>
                <?php endif; ?>
                <a class="nav-item nav-link nav-icon d-none" id="notificacion" href="#" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" data-content="Se esta cargando el archivo, espere un momento por favor">
                    <i class="fas fas fa-sync fa-spin"></i>
                </a>
                <a id="disco" class="nav-item nav-icon nav-link" href="#" onclick="PERFIL.methods.disco(this)" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" data-content="">
                    <i class="far fa-hdd"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true" data-content="No tiene notificaciones">
                    <i class="fas fa-bell"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" onclick="PERFIL.methods.contrato(true);">
                    <i class="fas fa-cog"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" onclick="AYUDA.componets.tab();">
                    <i class="fas fa-question-circle"></i>
                </a>
                <span class="navbar-text text-white" style="margin: 0px 10px;">
                    <?= $this->session->first_name . ' ' . $this->session->last_name ?>
                </span>
                <a class="nav-item nav-link nav-icon" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>                

    </nav>
    <?php $this->load->view('plantilla/menu'); ?>
    <!-- Sidebar -->
    <div style="min-width: 1334px; position: relative;">
        <nav id="sidebar" style="min-width: 378px">
        <ul class="nav nav-tabs" id="explorador" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tabexplorador-tab" data-toggle="tab" href="#tabexplorador" role="tab" aria-controls="tabexplorador" aria-selected="true">Archivos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabresultados-tab" data-toggle="tab" href="#tabresultados" role="tab" aria-controls="tabresultados" aria-selected="false">Resultados</a>
            </li>
        </ul>
        <div class="tab-content shadow-sm" id="exploradorContent">
            <div class="tab-pane fade show active" id="tabexplorador" role="tabpanel" aria-labelledby="tabexplorador-tab">
                <div class="explorador-icon">
                    <i class="btn-icon fas fa-check <?=$this->permisos->viewaccess('exp-archivos-actualizar')?>" id="exp-archivos-actualizar" data-toggle="popover" data-placement="top" data-content="Actualizar carpetas" onclick="EXPLORADOR.computed.dblclicktr(true)"></i>
                    <i class="btn-icon fas fa-sync-alt <?=$this->permisos->viewaccess('exp-archivos-actualizar')?>" id="exp-archivos-actualizar" data-toggle="popover" data-placement="top" data-content="Actualizar carpetas" onclick="EXPLORADOR.methods.listarCarpetas(this, GLOBAL.empresaId)"></i>
                    <i class="btn-icon far fa-plus-square <?=$this->permisos->viewaccess('exp-archivos-crear')?>" id="exp-archivos-crear" data-toggle="popover" data-placement="top" data-content="Crear una carpeta" onclick="EXPLORADOR.methods.crearCarpeta(this)"></i>
                    <i class="btn-icon far fa-edit <?=$this->permisos->viewaccess('exp-archivos-editar')?>" id="exp-archivos-editar" data-toggle="popover" data-placement="top" data-content="Renombrar carpeta" onclick="EXPLORADOR.methods.editarCarpeta(this)"></i>
                    <i class="btn-icon far fa-trash-alt <?=$this->permisos->viewaccess('exp-archivos-borrar')?>" id="exp-archivos-borrar" data-toggle="popover" data-placement="top" data-content="Borrar una carpeta y su contenido" onclick="EXPLORADOR.methods.borrarCarpeta(this)"></i>
                </div>
                <div class="card" style="height: calc(100vh - 245px); overflow-y: auto; padding: 10px 0px;">
                    <div class="card-body" id="explorador-content">
                        <div class="selec-empresa text-center text-muted small no-seleccionable">
                            Debe Seleccionar<br/>una empresa
                        </div>
                    </div>
                </div>                    
            </div>
            <div class="tab-pane fade" id="tabresultados" role="tabpanel" aria-labelledby="tabresultados-tab">
                <div class="explorador-icon">
                    <i class="btn-icon fas fa-check <?=$this->permisos->viewaccess('exp-resultados-actualizar')?>" id="exp-resultados-actualizar" data-toggle="popover" data-placement="top" data-content="Actualizar carpetas" onclick="RESULTADOS.computed.dblclicktr(true)"></i>
                    <i class="btn-icon fas fa-sync-alt <?=$this->permisos->viewaccess('exp-resultados-actualizar')?>" id="exp-resultados-actualizar" data-toggle="popover" data-placement="top" data-content="Actualizar carpetas" onclick="RESULTADOS.methods.listarCarpetas(this, GLOBAL.empresaId)"></i>
                    <i class="btn-icon far fa-plus-square <?=$this->permisos->viewaccess('exp-resultados-crear')?>" id="exp-resultados-crear" data-toggle="popover" data-placement="top" data-content="Crear una carpeta" onclick="RESULTADOS.methods.crearCarpeta(this)"></i>
                    <i class="btn-icon far fa-edit <?=$this->permisos->viewaccess('exp-resultados-editar')?>" id="exp-resultados-editar" data-toggle="popover" data-placement="top" data-content="Renombrar carpeta" onclick="RESULTADOS.methods.editarCarpeta(this)"></i>
                    <i class="btn-icon far fa-trash-alt <?=$this->permisos->viewaccess('exp-resultados-borrar')?>" id="exp-resultados-borrar" data-toggle="popover" data-placement="top" data-content="Borrar una carpeta y su contenido" onclick="RESULTADOS.methods.borrarCarpeta(this)"></i>
                </div>                    
                <div class="card shadow-sm" style="height: calc(100vh - 245px); overflow-y: auto; padding: 10px 0px;">
                    <div class="card-body" id="resultados-content">
                        <div class="selec-empresa text-center text-muted small no-seleccionable">
                            Debe Seleccionar<br/>una empresa
                        </div>
                    </div>
                </div>                    
            </div>
        </nav>
        <div id="content" style="z-index: 1030; min-width: 956px;">
            <?= $body ?>
        </div>
    </div>
        <nav id="footer-validate" class="navbar fixed-bottom navbar-validate">
            <span class="navbar-text pull-left">
                GEO Informatic Solutions S.A.&nbsp;&nbsp;&nbsp;&nbsp;<?=VERSION?>
            </span>
        </nav>        
        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="ventanaModal" aria-hidden="true" data-keyboard="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" data-keyboard="false">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ventanaModal">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <div id="btn-extra1" class="flex-grow-1"></div>
                        <button type="button" class="btn btn-link" data-dismiss="modal">
                            Cancelar
                        </button>
                        <div id="btn-extra"></div>
                        <button type="button" class="btn btn-primary">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5ce5fcc0d07d7e0c6394f340/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
</html>
