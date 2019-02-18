<!doctype html>
<html lang="es">
    <head>
        <!-- STYLESHEET -->
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">
        <!-- Bootstrap Scroller CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/scroller/3.1.5/css/jquery.mCustomScrollbar.min.css') ?>">
        <!-- JSTree CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/jstree/style.css') ?>">
        <!-- Billboard CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/billboard/1.7.1/css/billboard.min.css') ?>">
        <!-- Datatables CSS -->
        <link rel="stylesheet" src="<?= base_url('assets/library/datatables/datatables.min.css') ?>"></link>
        <!-- Select-1.2.6 -->
        <link rel="stylesheet" src="<?= base_url('assets/library/datatables/Select-1.2.6/css/select.bootstrap.min.css') ?>"></link>
        <!-- FontAwesome ICON -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <!-- Toast -->
        <link rel="stylesheet" href="<?= base_url('assets/library/toast/css/jquery.toast.min.css') ?>">
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">
        <?= load_assets('css') ?>

        <!-- JAVASCRIPT -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <!-- Bootstrap Scroller JS -->
        <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>" type="text/javascript"></script>
        <!-- JSTree JS -->
        <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>" type="text/javascript"></script>
        <!-- Datatables JS -->
        <script src="<?= base_url('assets/library/datatables/datatables.min.js') ?>" type="text/javascript"></script>
        <!-- Select-1.2.6 -->
        <script src="<?= base_url('assets/library/datatables/Select-1.2.6/js/select.bootstrap.min.js') ?>" type="text/javascript"></script>
        <!-- Billboard JS -->
        <script src="https://d3js.org/d3.v5.min.js" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/billboard/1.7.1/js/billboard.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/toast/js/jquery.toast.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/loader.js') ?>" type="module"></script>
        <!-- Optional JavaScript -->        
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
        <title>Verify</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top bg-auditor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <i class="fas fa-check-double" style="color: #729d39"></i>
                Verify
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
                        <a class="nav-link" id="pills-analisis-tab" data-toggle="pill" href="#pills-analisis" role="tab" aria-controls="pills-analisis" aria-selected="false">An&aacute;lisis</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="#">
                    <i class="fas fa-bell" style="margin-top: 6px; margin-right: 10px;"></i>
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
            <ul class="list-unstyled components" style="padding-top: 10px;">
                <div style="margin: 0px; padding: 0px 10px 0px 10px;" class="clearfix">
                    <div class="float-left">Explorador de archivos</div>
                    <div class="float-right" style="margin-right: -3px;">
                        <i class="btn-icon far fa-plus-square" data-toggle="popover" data-placement="top" data-content="Crear una carpeta"></i>
                        <i class="btn-icon far fa-trash-alt" data-toggle="popover" data-placement="top" data-content="Borrar una carpeta y su contenido"></i>
                        <i class="btn-icon fas fa-info-circle" data-toggle="popover" data-placement="top" data-content="Información de la carpeta"></i>
                    </div>
                </div>
                <div class="card" style="height: calc(100vh - 203px); overflow-y: auto;">
                    <div class="card-body" id="explorador-content">
                        <div class="selec-empresa text-center text-muted small no-seleccionable">
                            Debe Seleccionar<br/>una empresa
                        </div>
                    </div>
                </div>
            </ul>
        </nav>
        <nav id="sidebar" class="propiedades">
            <div class="card" style="height: calc(100vh - 170px); overflow-y: auto;">
                <div class="card-body">
                    <div class="selec-empresa text-center text-muted small no-seleccionable">
                        Debe Seleccionar<br/>una empresa
                    </div>                    
                </div>
            </div>            
        </nav>
        <div id="content">
            <?= $body ?>
        </div>
        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="ventanaModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ventanaModal">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>