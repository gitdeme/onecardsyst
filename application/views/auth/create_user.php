
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> <?php echo lang('create_user_heading'); ?>   </h6>
    </div>
    <div class="card-body">
        <div class="text-danger" id="infoMessage"><?php echo $message; ?></div>

        <?php echo form_open("auth/create_user"); ?>
        <div class="row"> 

            <div class="col-md-6">
                <?php echo lang('create_user_fname_label', 'first_name'); ?> <span class="text-danger"> *</span> <br />
                <?php echo form_input($first_name); ?>
            </div>

            <div class="col-md-6">
                <?php echo lang('create_user_lname_label', 'last_name'); ?> <span class="text-danger"> *</span> <br />
                <?php echo form_input($last_name); ?>
            </div>
        </div>
        <?php
        if ($identity_column !== 'email') {
            echo '<p>';
            echo lang('create_user_identity_label', 'identity');
            echo '<br />';
            echo form_error('identity');
            echo form_input($identity);
            echo '</p>';
        }
        ?>

        <p>
            <?php echo lang('create_user_company_label', 'company'); ?> <span class="text-danger">(if ticker, on which cafe he/she assigned) for other Workers make it all *</span> <br />
            <?php
            $extratributes = array('id' => 'company', 'class' => 'form-control');//
            echo form_dropdown("company", $company,$this->form_validation->set_value('company') ,$extratributes);
            
            ?>
        </p>
        
         <p>
            <label> Door Number  </label>
          <?php 
            $extratributes = array('id' => 'door', 'class' => 'form-control');//
           
            echo form_dropdown("door", $doors, $this->form_validation->set_value('door') ,$extratributes);
          
          ?>  
            
            
        </p>  
        
        <p>
            <label> User faculty(for associate registrars)  </label>
          <?php 
            $extratributes = array('id' => 'user_collage', 'class' => 'form-control');//
            echo form_dropdown("user_collage", $collages, $this->form_validation->set_value('user_collage') ,$extratributes);
          
          ?>  
            
            
        </p>

        <p>
            <?php echo lang('create_user_email_label', 'email'); ?>  <span class="text-danger"> *</span><br />
<?php echo form_input($email); ?>
        </p>

        <p>
            <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
<?php echo form_input($phone); ?>
        </p>

        <p>
            <?php echo lang('create_user_password_label', 'password'); ?> <span class="text-danger"> *</span> <br />
<?php echo form_input($password); ?>
        </p>

        <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?><span class="text-danger"> *</span>  <br />
<?php echo form_input($password_confirm); ?>
        </p>

    </div>
    <div class="box-footer col-md-12">
        <?php
        $submitattri = array('type' => 'submit', 'value' => lang('create_user_submit_btn'), 'class' => 'btn btn-success');
        echo form_submit($submitattri);
        ?>
        <a href="<?php echo site_url("Auth/index") ?>" class="btn btn-danger"> <i class="fa fa-close"></i> Close</a>
    </div>

<?php echo form_close(); ?> 

</div>

