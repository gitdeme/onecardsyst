

<div class="card shadow mb-4">

    <div class="row border-bottom-info">
        <div class="col-md-12"><h3 class="box-title"><?php echo lang('create_group_heading'); ?></h3></div>
       
    </div>

    <div class="card-body">
        <?php echo form_open("auth/create_group"); ?>
        <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>

        <p> <span class="text-danger"> *</span>
            <?php echo lang('create_group_name_label', 'group_name'); ?> <br />
            <?php echo form_input($group_name); ?>
        </p>

        <p>
            <?php echo lang('create_group_desc_label', 'description'); ?> <br />
            <?php echo form_input($description); ?>
        </p>

        <p><?php // echo form_submit('submit', lang('create_group_submit_btn'));   ?></p>
        <div class="col-md-12">
            <?php
            $submitattri = array('type' => 'submit', 'value' => lang('create_group_submit_btn'), 'class' => 'btn btn-success');
            echo form_submit($submitattri);
            ?>
            <a href="<?php echo site_url("auth/view_groups")?>" class="btn btn-danger"> <i class="fa fa-close">Close </i>   </a>
        </div>
        <?php echo form_close(); ?>  

    </div>


</div>
