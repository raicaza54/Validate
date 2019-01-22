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
        <?= asset_css('fontawesome/css/fontawesome.min.css') ?>
        <?= asset_css('fontawesome/css/brands.min.css') ?>
        <?= asset_css('fontawesome/css/solid.min.css') ?>
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">

        <title>Auditor</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top bg-auditor">
            <a class="navbar-brand" href="#!" style="font-weight: 600">
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
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>
        <?php $this->load->view('plantilla/menu'); ?>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar">
                <ul class="list-unstyled components" style="padding-top: 10px;">
                    <p style="margin: 0px; padding-top: 0px;">
                        Explorador de archivos
                    </p>
                    <div class="card">
                        <div class="card-body">
                                        <div id="dragdropTree">
                                            <ul>
                                                <li>Admin
                                                    <ul>
                                                        <li data-jstree='{"opened":true}'>Settings
                                                            <ul>
                                                                <li data-jstree='{"type":"file"}'>Website settings</li>
                                                                <li data-jstree='{"opened":true}'>Users settings
                                                                    <ul>
                                                                        <li data-jstree='{"selected":true,"type":"file"}'>Users Accounts</li>
                                                                        <li data-jstree='{"type":"file"}'>Users Groups</li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"opened":true}'>Newsletters
                                                            <ul>
                                                                <li data-jstree='{"type":"file"}'>Newsletter one</li>
                                                                <li data-jstree='{"type":"file"}'>Newsletter two</li>
                                                                <li data-jstree='{"type":"file"}'>Newsletter three</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"icon":"fa fa-folder-open"}'>Comments</li>
                                                        <li data-jstree='{"opened":true}'>Plugins
                                                            <ul>
                                                                <li data-jstree='{"type":"file"}'>Plugin one</li>
                                                                <li data-jstree='{"type":"file"}'>Plugin two</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"icon":"fa fa-user"}'>Users</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"type":"file"}'>Modules</li>
                                            </ul>
                                        </div>
                        </div>
                    </div>
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="#">Home 1</a>
                            </li>
                            <li>
                                <a href="#">Home 2</a>
                            </li>
                            <li>
                                <a href="#">Home 3</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div id="content">
                <?= $body ?>
            </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.slim.min.js') ?>"></script>
            <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>"></script>
            <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>"></script>
            <!-- Bootstrap Scroller JS -->
            <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
            <!-- JSTree JS -->
            <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>"></script>
            <?= asset_js('plantilla/sidebar.js') ?>
    </body>
</html>