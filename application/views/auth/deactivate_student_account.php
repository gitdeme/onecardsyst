
<div class="card shadow mb-4">
    <div class="card-header py-1 row">
        <div class="m-0 font-weight-bold text-primary col-md-6"><h1><?php echo lang('deactivate_heading'); ?></h1></div>

    </div>
    <?php echo form_open("auth/deactivate_student_account/" . $user->id); ?>

    <div class="card-body text-primary bg-warning">
        <div class="col-12">
            <h3><?php echo sprintf(lang('deactivate_subheading'), $user->username); ?></h3>
            <p> 
                <?php echo lang('deactivate_confirm_y_label', 'confirm'); ?>
                <input type="radio" name="confirm" value="yes" checked="checked" />
                <?php echo lang('deactivate_confirm_n_label', 'confirm'); ?>
                <input type="radio" name="confirm" value="no" />
            </p>

            <?php echo form_hidden($csrf); ?>
            <?php echo form_hidden(['id' => $user->id]); ?>

        </div>



    </div>
    <div class="card-footer">
        <?php
        $attributes = array("class" => "btn btn-danger");

        echo form_submit('submit', lang('deactivate_submit_btn'), $attributes);
        ?> 

    </div>
<?php echo form_close(); ?>
</div>



