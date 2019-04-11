<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/scroller/3.1.5/css/jquery.mCustomScrollbar.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/jstree/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/billboard/1.7.1/css/billboard.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/datatables/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/library/datatables/Select-1.2.6/css/select.bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/fontawesome/css/all.css') ?>" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= base_url('assets/library/toast/css/jquery.toast.min.css') ?>">
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">
        <?= load_assets('css') ?>
        <script type="text/javascript">
            var base_url = '<?= base_url()?>';
        </script>
        <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/DataTables/datatables.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/Select-1.2.6/js/select.bootstrap.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/plug-ins/dataTables.scrollResize.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/plug-ins/numeric-comma.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/plug-ins/num-html.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/datatables/plug-ins/formatted-num.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/billboard/1.7.1/js/d3.v5.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/billboard/1.7.1/js/billboard.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/fullscreen/0.6.0/jquery.fullscreen.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/toast/js/jquery.toast.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/cookie/2.2.0/js.cookie.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/loader.js?version=5') ?>" type="module"></script>
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
                <a class="nav-item nav-link" href="#" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="Espacio en Disco 200 / 500Gb 
                    <div class='progress'>
                        <div class='progress-bar w-75' role='progressbar' aria-valuenow='75' aria-valuemin='0' aria-valuemax='100'></div>
                    </div>
                    <p style='margin: 10px 0px 0px;'>
                        Usted puede solicitar ampliar el espacio seg&oacute;n sus necesidades
                    </p>">
                    <i class="far fa-hdd" style="margin: 6px 5px 0px 5px;"></i>
                    <div class="progress" style="height: 2px;">
                        <div class="progress-bar <?= random_element(array('bg-danger','bg-info','bg-warning'))?>" role="progressbar" style="width: <?=rand(10, 80)?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>                    
                </a>
                <a class="nav-item nav-link" href="#">
                    <i class="fas fa-bell" style="margin: 6px 5px 0px 5px;"></i>
                </a>
                <a class="nav-item nav-link" href="#">
                    <i class="far fa-question-circle" style="margin: 6px 5px 0px 5px;"></i>
                </a>
                <span class="navbar-text text-white">
                    <?= $this->session->first_name . ' ' . $this->session->last_name ?>
                </span>
                <a class="nav-item nav-link" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt" style="margin-top: 6px;"></i>
                </a>
            </div>
        </nav>
        <?php $this->load->view('plantilla/menu'); ?>
        <!-- Sidebar -->
        <nav id="sidebar">
            <ul class="list-unstyled components" style="padding-top: 15px;">
                <div style="margin: 0px; padding: 0px 10px 0px 10px;" class="clearfix">
                    <div class="float-left" style="margin-left: 5px;">Explorador de archivos</div>
                    <div class="float-right" style="margin: 0px;">
                        <i class="btn-icon far fa-plus-square" data-toggle="popover" data-placement="top" data-content="Crear una carpeta" onclick="EXPLORADOR.methods.crearCarpeta()"></i>
                        <i class="btn-icon far fa-edit" data-toggle="popover" data-placement="top" data-content="Renombrar carpeta" onclick="EXPLORADOR.methods.editarCarpeta()"></i>
                        <i class="btn-icon far fa-trash-alt" data-toggle="popover" data-placement="top" data-content="Borrar una carpeta y su contenido" onclick="EXPLORADOR.methods.borrarCarpeta()"></i>
                    </div>
                </div>
                <div class="card shadow-sm" style="height: calc(100vh - 243px); overflow-y: auto;">
                    <div class="card-body" id="explorador-content">
                        <div class="selec-empresa text-center text-muted small no-seleccionable">
                            Debe Seleccionar<br/>una empresa
                        </div>
                    </div>
                </div>
            </ul>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
</html>
