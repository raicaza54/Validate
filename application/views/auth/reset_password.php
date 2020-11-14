<p class="text-white text-center">
    <?= asset_image('logo3.png"')?>
</p>
<div class="card">
    <div class="card-body">
        <h5 class="text-center mb-3"><?php echo lang('reset_password_heading');?></h5>
        <?php if(strlen($message)): ?>
        <div id="infoMessage" class="alert alert-secondary"><?= $message; ?></div>
        <?php endif; ?>
        <?= form_open('auth/reset_password/' . $code); ?>
        <div style="width: 100%;" for="new"><?=sprintf(lang('reset_password_new_password_label', 'new'), $min_password_length)?></div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-key"></i>
                </span>
            </div>
            <?= form_input($new_password, NULL, 'class="form-control" required'); ?>
            <div class="invalid-feedback">
              <?= form_error('new') ?>
            </div>
        </div>
        <div style="width: 100%;" for="new_confirm"><?=sprintf(lang('reset_password_new_password_confirm_label', 'new_confirm'))?></div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-check"></i>
                </span>
            </div>
            <?= form_input($new_password_confirm, NULL, 'class="form-control" required'); ?>
            <div class="invalid-feedback">
              <?= form_error('new_confirm') ?>
            </div>
        </div>
	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>        
        <?= form_submit('submit', lang('reset_password_submit_btn'), 'class="btn btn-primary btn-block mb-2"'); ?>
        <?= form_close(); ?>
        <div class="text-center">
            <a href="login">
                <?= lang('login_btn_login'); ?>
            </a>                    
        </div>
    </div>
</div>