<p class="text-white text-center" style="">
    <?= asset_image('logo3.png" style="height: 100px;"')?>
</p>
<div class="card shadow-sm">
    <div class="card-body" style="background-color: #fafafa;">
        <h5 class="text-center"><?= lang('login_heading'); ?></h5>
        <p class="text-center"><?= lang('login_subheading'); ?></p>
        <?php if(strlen($message)): ?>
        <div id="infoMessage" class="alert alert-secondary"><?= $message; ?></div>
        <?php endif; ?>
        <?= form_open("auth/login"); ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <?= form_input($identity, NULL, 'class="form-control '.(form_error('identity') ? 'is-invalid':'').'" placeholder="'.lang('login_identity_label').'" required'); ?>
            <div class="invalid-feedback">
              <?= form_error('identity') ?>
            </div>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-key"></i>
                </span>
            </div>
            <?= form_input($password, NULL, 'class="form-control '.(form_error('password') ? 'is-invalid':'').'" placeholder="'.lang('login_password_label').'" required'); ?>
            <div class="invalid-feedback">
              <?= form_error('password') ?>
            </div>            
        </div>
        <div class="form-group form-check">
            <?= form_checkbox('remember', '1', FALSE, 'id="remember" class="form-check-input"'); ?>
            <?= lang('login_remember_label', 'remember', array('class' => 'form-check-label')); ?>
        </div>        
        <?= form_submit('submit', lang('login_submit_btn'), 'class="btn btn-primary btn-block mb-2"'); ?>
        <?= form_close(); ?>
        <div class="text-center">
            <a href="forgot_password">
                <i class="fas fa-question-circle"></i>
                <?= lang('login_forgot_password'); ?>
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function() {
            $('#identity').focus();
        }, 1500);
    });
</script>
