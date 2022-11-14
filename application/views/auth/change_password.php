<div class="card shadow mb-4">

   <div class="card-header py-1">
        <h3 class="box-title"> <?php echo lang('change_password_heading'); ?></h3>

    </div>
    <?php echo form_open("auth/change_password"); ?>
    <div class="card-body">

        <div class="text-danger" id="infoMessage"><?php echo $message; ?></div>

        <p>
            <?php echo lang('change_password_old_password_label', 'old_password'); ?> <br />
            <?php echo form_input($old_password); ?>
        </p>

        <p>
            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length); ?></label> <br />
            <?php echo form_input($new_password); ?>
        </p>

        <p>
            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm'); ?> <br />
            <?php echo form_input($new_password_confirm); ?>
        </p>

        <?php echo form_input($user_id); ?>
        <p><?php echo form_submit('submit', lang('change_password_submit_btn'), "class='btn btn-success'"); ?>
            <a href="<?php echo site_url('') ?>" class="btn btn-danger" ><i class="fa fa-close"> Cancel </i> </a>

        </p>




    </div>

    <?php echo form_close(); ?>

</div>



