<?php include_once 'head-admin.php'; ?>
    <body>
        <nav class="navbar navbar-expand-sm fixed-top bg-admin contenedor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <?= asset_image('logo3.png" style="height: 30px;"')?>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills" id="pills-tab" role="tablist" style="margin-left: 40px;">
                    <li class="nav-item">
                        <a class="nav-link nav-icon active" id="pills-principal-tab" data-toggle="pill" href="#pills-principal" role="tab" aria-controls="pills-principal" aria-selected="true">Principal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon" id="pills-configuracion-tab" data-toggle="pill" href="#pills-configuracion" role="tab" aria-controls="pills-configuracion" aria-selected="true">Configuraci&oacute;n</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav">
                <?php if(in_array('1', $this->session->grupos)): ?>
                <a class="nav-link active nav-icon" href="/" style="font-size: 1rem;">
                    Validate System - PHP <?=(float)phpversion()?>
                </a>
                <?php endif; ?>
                <a class="nav-item nav-link nav-icon d-none" id="notificacion" href="#" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="Se esta cargando el archivo, espere un momento por favor">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="No tiene notificaciones">
                    <i class="fas fa-bell"></i>
                </a>
                <a class="nav-item nav-link nav-icon" href="#">
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
        <?php $this->load->view('plantilla/admin-menu'); ?>
        <!-- Sidebar -->
        <div style="min-width: 1280px; position: relative;">
            <div id="content" style="z-index: 1030; min-width: 500px; width: 100%">
                <?= $body ?>
            </div>
        </div>
        <nav id="footer-validate" class="navbar fixed-bottom navbar-admin">
            <div class="container-fluid p-0 pt-2 pb-2">
                <div class="col-sm-3 p-0 text-white">
                    GEO Informatic Solutions S.A.
                </div>                
                <div class="col-sm-6 p-0 text-center text-white">
                    Validate System <?=VERSION?>
                </div>
                <div class="col-sm-3 p-0">
                    &nbsp;
                </div>
            </div>
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
