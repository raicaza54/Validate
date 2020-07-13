<p class="text-white text-center">
    <?= asset_image('logo3.png"')?>
</p>
<div class="card">
    <div class="card-body">
        <h5 class="text-center"><?= lang('forgot_password_heading'); ?></h5>
        <p class=""><?= sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>
        <?php if(strlen($message)): ?>
        <div id="infoMessage" class="alert alert-secondary"><?= $message; ?></div>
        <?php endif; ?>
        <?= form_open("auth/forgot_password"); ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <?= form_input($identity, NULL, 'class="form-control '.(form_error('identity') ? 'is-invalid':'').'" placeholder="'.((($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label))).'"'); ?>
            <div class="invalid-feedback">
              <?= form_error('identity') ?>
            </div>            
        </div>
        <?= form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-primary btn-block mb-2"'); ?>
        <?= form_close(); ?>
        <div class="text-center">
            <a href="login">
                <?= lang('login_btn_login'); ?>
            </a>                    
        </div>
    </div>
</div>
