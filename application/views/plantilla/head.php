<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.3.1/css/bootstrap.min.css') ?>">
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
            var ayudame = '<?=$this->session->flashdata('ayudame') !== NULL ? 1 : 0 ?>';
        </script>
        <script src="<?= base_url('assets/library/jquery/3.4.1/jquery.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/jstree/jquery.ui.touch.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.3.1/js/bootstrap.min.js') ?>" type="text/javascript"></script>
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
        <title>Validate System</title>
    </head>
