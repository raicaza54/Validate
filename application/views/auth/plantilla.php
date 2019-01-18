<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">

        <!-- FontAwesome ICON -->
        <?= asset_css('fontawesome/css/fontawesome.min.css') ?>
        <?= asset_css('fontawesome/css/brands.min.css') ?>
        <?= asset_css('fontawesome/css/solid.min.css') ?>
        <?= asset_css('auth/estilo.css') ?>
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">

        <title>Auditor</title>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col col-lg-5" style="margin-top: 4em;">
                    <?=$body?>
                </div>
            </div>
        </div>        


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.slim.min.js') ?>"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>"></script>
    </body>
</html>