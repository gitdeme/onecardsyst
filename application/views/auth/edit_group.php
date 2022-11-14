
<div class="card shadow mb-4">
    <div class="card-header py-1 text-primary  row">
            
<!--                <h3 class="box-title"><?php //echo lang('edit_group_heading'); ?></h3>-->
                <div class="box-tools">            
                    <?php echo lang('edit_group_subheading'); ?>
                </div>
            </div>
        
            <div class="card-body">
                    <?php echo form_open(current_url()); ?>
                <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>
                <p>
                    <?php echo lang('edit_group_name_label', 'group_name'); ?> <br />
                    <?php echo form_input($group_name); ?>
                </p>

                <p>
                    <?php echo lang('edit_group_desc_label', 'description'); ?> <br />
                    <?php echo form_input($group_description); ?>
                </p>

                <p><?php //echo form_submit('submit', lang('edit_group_submit_btn')); ?></p>


          

            <div class="box-footer">
                <?php
                $submitattri = array('type' => 'submit', 'value' => lang('edit_group_submit_btn'), 'class' => 'btn btn-success');
                echo form_submit($submitattri);
                
                ?>
                <a href="<?php echo site_url("auth/view_groups") ?>" class="btn btn-danger"> <i class="fa fa-close">Close </i>  </a>
            </div>
            <?php echo form_close(); ?>  
        </div>
   
</div>


