<div class="row">
    <div class="col-md-12">

        <div class="box-body">
            <!--<div class="col-lg-3">  </div>-->

            <div class="well-lg col-lg-7 " style="margin-left: 20%;background-color: white; padding-top: 5px">

                <h1 style="text-align: center; margin-top: 0px; padding-top: 0px"><?php //echo lang('login_heading');  ?>
                    
                    <i class="fa fa-user-circle fa-4x text-success" >  </i>
                    <!--<img class="img-circle" height="200"  width="200" src="<?php echo base_url() ?>resources/icons/login_icon.jpg"/>-->
                </h1>
                <!--<h6><?php echo lang('login_subheading'); ?></h6>-->

                <div class="bg-danger text-warning" id="infoMessage"><?php echo $message; ?></div>

                <?php echo form_open("auth/login"); ?>

                <h4 class="text-primary">
                    <?php echo lang('login_identity_label', 'identity'); ?>
                    <?php echo form_input($identity); ?>
                </h4>

                <h4 class="text-primary">
                    <?php echo lang('login_password_label', 'password'); ?>
                    <?php echo form_input($password); ?>
                </h4>

                <h4 class="text-primary">
                    <?php echo lang('login_remember_label', 'remember'); ?>
                    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                </h4>


                <p><?php
                    $attributes = array('name' => 'submit', 'class' => 'btn btn-success btn-block', 'type' => 'submit');
                    echo form_submit($attributes, lang('login_submit_btn'));
                    ?></p>

                <?php echo form_close(); ?>

                <p><a href="forgot_password"><?php echo lang('login_forgot_password'); ?></a></p>
            </div>
           
            <!--<div class="col-lg-2">  </div>-->
        </div>
    </div>
</div>