<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> <?php echo lang('edit_user_heading'); ?> : <?php echo lang('edit_user_subheading'); ?>  </h6>
    </div>

    <!--    
                <div class="box-header">
                    <h3 class="box-title"> </h3>
                    <div class="box-tools">
                
    <?php echo lang('edit_user_subheading'); ?>
                    </div>
                </div>-->
    <div class="card-body">

        <div class="bg-info" id="infoMessage"><?php echo $message; ?></div>

        <?php echo form_open(uri_string()); ?>

        <p>
            <?php echo lang('edit_user_fname_label', 'first_name'); ?><span class="text-danger"> *</span> <br />
            <?php echo form_input($first_name); ?>
        </p>

        <p>
            <?php echo lang('edit_user_lname_label', 'last_name'); ?> <span class="text-danger"> *</span><br />
            <?php echo form_input($last_name); ?>
        </p>



        <p>
            <?php echo lang('edit_user_phone_label', 'phone'); ?> <br />
            <?php echo form_input($phone); ?>
        </p>
        <p>
            <?php echo lang('edit_user_email_label', 'email'); ?> <br />
            <?php echo form_input($email); ?>
        </p>
        <p>
            <?php echo lang('edit_user_password_label', 'password'); ?><span class="text-danger"> *</span> <br />
            <?php echo form_input($password); ?>
        </p>

        <p>
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?> <span class="text-danger"> *</span><br />
            <?php echo form_input($password_confirm); ?>
        </p>


        <?php echo form_hidden('id', $user->id); ?>
        <?php echo form_hidden($csrf); ?>

        <div class="col-md-12">    <?php
            $attributes = array("class" => "btn btn-success", "type" => "submit", "value" => "Save Change");

            echo form_submit($attributes);
            ?>
            <a href="<?php echo site_url("Auth/create_account_for_students") ?>" class="btn btn-danger"> Close</a>
        </div>  

        <?php echo form_close(); ?>




    </div>

</div>









