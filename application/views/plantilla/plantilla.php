<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">
        <!-- Bootstrap Scroller CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/scroller/3.1.5/css/jquery.mCustomScrollbar.min.css') ?>">
        <!-- JSTree CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/jstree/style.css') ?>">
        <!-- FontAwesome ICON -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">

        <title>Auditor</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top bg-auditor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <i class="fas fa-feather-alt" style="color: #FF8104"></i>
                Auditor
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
                <span class="navbar-text text-white">
                    Kevin Enriquez
                </span>
                <a class="nav-item nav-link" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt" style="margin-top: 6px;"></i>
                </a>
            </div>
        </nav>
        <?php $this->load->view('plantilla/menu'); ?>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar">
                <ul class="list-unstyled components" style="padding-top: 10px;">
                    <p style="margin: 0px; padding: 0px 10px 0px 10px;">
                        Explorador de archivos
                    </p>
                    <div class="card">
                        <div class="card-body">
                            <div id="simpleTree" obj="tree">
                                <ul obj="tree">
                                    <li data-jstree='{"opened":true}' obj="tree">2018
                                        <ul obj="tree">
                                            <li obj="tree" data-jstree='{"opened":true}'>Enero
                                                <ul obj="tree">
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                                </ul>
                                            </li>
                                            <li obj="tree" data-jstree='{"opened":true}'>Febrero
                                                <ul obj="tree">
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li obj="tree" data-jstree='{"opened":true}'>2019
                                        <ul obj="tree">
                                        <li obj="tree" data-jstree='{"opened":true}'>Enero
                                            <ul obj="tree">
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                    <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </ul>
            </nav>
            <div id="content">
                <?= $body ?>
            </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.min.js') ?>"></script>
            <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>"></script>
            <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>"></script>
            <!-- Bootstrap Scroller JS -->
            <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
            <!-- JSTree JS -->
            <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>"></script>
            <?= asset_js('plantilla/sidebar.js') ?>
    </body>
</html>