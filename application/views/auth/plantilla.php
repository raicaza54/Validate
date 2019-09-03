<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.3.1/css/bootstrap.min.css') ?>">

        <!-- FontAwesome ICON -->
        <?= asset_css('fontawesome/css/fontawesome.min.css') ?>
        <?= asset_css('fontawesome/css/brands.min.css') ?>
        <?= asset_css('fontawesome/css/solid.min.css') ?>
        <?= asset_css('auth/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.3.1/js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">

        <title>Validate</title>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-md-center">
                <?php if(in_array($this->uri->segment(2), ['forgot_password','login','reset_password'])): ?>
                <div class="col col-lg-5" style="margin-top: 2.5em;">
                    <?=$body?>
                </div>
                <?php else: ?>
                <div class="col" style="margin-top: 2.5em;">
                    <?=$body?>
                </div>                
                <?php endif; ?>
            </div>
        </div>        
        <nav id="footer-validate" class="navbar fixed-bottom navbar-validate">
            <span class="navbar-text pull-left">
                GEO Informatic Solutions S.A.
            </span>
            <span class="navbar-text pull-right">
                2019
            </span>
        </nav>
    </body>
</html>