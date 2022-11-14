

<div class="card shadow mb-4">

    <div class="box-header text-primary">
        <h3 class="box-title"> Create Actions/Activities</h3>

    </div>

    <div class="card-body">
        <?php echo form_open("action/create_action"); ?>
        <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>


        <div class="form-group">
            <label for="action_name" class="control-label"> <span class="text-danger"> *</span> Action Name{Controller.methodName}</label>

            <input type="text" name="action_name" value="<?php echo $this->input->post('action_name'); ?>" class="form-control" id="action_name" required=""/>


        </div>

        <div class="form-group">
            <label for="action_description" class="control-label"> <span class="text-danger"> *</span> Action Description</label>

            <input type="text" name="action_description" value="<?php echo $this->input->post('action_description'); ?>" class="form-control" id="action_description" required=""/>


        </div>

        <div class="form-group">
            <label for="action_group" class="control-label"> <span class="text-danger"> *</span> in which menu this Action shall grouped? </label>


            <select name="action_group"  class="form-control">
                <option value=""> None</option>
                <?php
                foreach ($menus as $f) {
                    $selected = ($f['menu_id'] == $this->input->post('action_group')) ? 'selected="selected"' : "";
                    echo '<option value="' . $f['menu_id'] . '" ' . $selected . '>' . $f['label'] . '</option>';
                }
                ?>
            </select>

        </div>

        <div class="form-group">
            <label for="action_label" class="control-label"> <span class="text-danger"> *</span> Action Label</label>

            <input type="text" name="action_label" value="<?php echo $this->input->post('action_label'); ?>" class="form-control" id="action_label" required=""/>


        </div>


        <div class="form-group">
            <label for="action_icon" class="control-label"> Action Icon</label>
            <input type="text" name="action_icon" value="<?php echo $this->input->post('action_icon'); ?>" class="form-control" id="action_icon" />

        </div>

        <div class="form-group">
            <label for="action_order" class="control-label"> Action Order</label>
            <input type="text" name="action_order" value="<?php echo $this->input->post('action_order'); ?>" class="form-control" id="action_order" />

        </div>

        <div class="col-md-12">
            <?php
            $submitattri = array('type' => 'submit', 'value' => 'Save', 'class' => 'btn btn-success');
            echo form_submit($submitattri);
            ?>
            <a href="<?php echo site_url("action/view_actions") ?>" class="btn btn-danger"> <i class="fa fa-close">Close </i>   </a>
        </div>
        <?php echo form_close(); ?>  
    </div>



</div>



