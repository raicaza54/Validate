<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/jstree/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/scroller/3.1.5/css/jquery.mCustomScrollbar.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/billboard/1.7.1/css/billboard.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/datatables/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/datatables/Select-1.2.6/css/select.bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/fontawesome/css/all.css') ?>" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= base_url('assets/library/toast/css/jquery.toast.min.css') ?>">
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">
        <?= load_assets('css') ?>
        <?php $assets = $this->config->item('assets'); ?>
        <script type="text/javascript">
            var base_url = '<?= base_url() ?>';
            var version = '<?= $assets['version'] ?>';
        </script>
        <script src="<?= base_url('assets/library/jquery/3.4.1/jquery.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/DataTables/datatables.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/Select-1.2.6/js/select.bootstrap.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/plug-ins/dataTables.scrollResize.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/billboard/1.7.1/js/d3.v5.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/billboard/1.7.1/js/billboard.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/fullscreen/0.6.0/jquery.fullscreen.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/toast/js/jquery.toast.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/cookie/2.2.0/js.cookie.js') ?>" type="text/javascript"></script>
        <?= asset_js('loader.js') ?>
        <?= load_assets('js') ?>
        <script type="text/javascript">
            $(function ($) {
                $.ajaxSetup({
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
            });
        </script>
        <title>Validate</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top bg-auditor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <?= asset_image('logo1.png" style="height: 30px;"')?>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills" id="pills-tab" role="tablist" style="margin-left: 40px;">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-principal-tab" data-toggle="pill" href="#pills-principal" role="tab" aria-controls="pills-principal" aria-selected="true">Principal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-datos-tab" data-toggle="pill" href="#pills-datos" role="tab" aria-controls="pills-datos" aria-selected="false">Datos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-analisis-tab" data-toggle="pill" href="#pills-analisis" role="tab" aria-controls="pills-analisis" aria-selected="false">Analizar</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav">
                <a class="nav-item nav-link nav-icon d-none" id="notificacion" href="#" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="Se esta cargando el archivo, espere un momento por favor">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </a>
                <a id="disco" class="nav-item nav-link nav-icon" href="#" onclick="PERFIL.methods.disco(this)" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="">
                    <i class="far fa-hdd"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="No tiene notificaciones">
                    <i class="fas fa-bell"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" onclick="PERFIL.methods.contrato(true);">
                    <i class="fas fa-cog"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#">
                    <i class="far fa-question-circle"></i>
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
        <nav id="sidebar">
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
            </div>            
        </nav>
        <div id="content" style="z-index: 1030;">
            <?= $body ?>
        </div>
        <nav id="footer-validate" class="navbar fixed-bottom navbar-validate">
            <span class="navbar-text pull-left">
                GEO Informatic Solutions S.A.
            </span>
            <span class="navbar-text pull-right">
                2019
            </span>
        </nav>        
        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="ventanaModal" aria-hidden="true">
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
                        <button type="button" class="btn btn-link" data-dismiss="modal">
                            Cancelar
                        </button>
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
