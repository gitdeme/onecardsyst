

<div class="card shadow mb-4">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="border-bottom-info">
                <h3 class="box-title">Edit action/activity</h3>
            </div>
            <?php echo form_open('action/edit_action/' . $action['activityID']); ?>
            <div  class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <label for="action_name" class="control-label">Action Name<span class="text-danger"> *</span></label>

                        <input type="text" name="action_name" value="<?php echo ($this->input->post('action_name') ? $this->input->post('action_name') : $action['action_name']); ?>" class="form-control" id="action_name" />

                    </div>
                    <div  class="col-md-6">
                        <label for="action_description" class="control-label">Action Description</label>

                        <input type="text" name="action_description" value="<?php echo ($this->input->post('action_description') ? $this->input->post('action_description') : $action['action_description']); ?>" class="form-control" id="action_description" />

                    </div>

                    <div  class="col-md-6">
                        <label for="action_group" class="control-label"> <span class="text-danger"> *</span> in which menu this Action shall grouped? </label>


                        <select name="action_group"  class="form-control">                           
                            <?php
                            if (empty($action['action_menu_group'])) {
                                echo "<option value=''> None</option>";
                            }

                            foreach ($menus as $f) {
                                $selected = ( ($f['menu_id'] == $this->input->post('action_group')) || ($f['menu_id'] == $action['action_menu_group'])) ? 'selected="selected"' : "";
                                echo '<option value="' . $f['menu_id'] . '" ' . $selected . '>' . $f['label'] . '</option>';
                            }
                            ?>
                        </select>


                    </div>

                    <div class="form-group">
                        <label for="action_label" class="control-label"> <span class="text-danger"> *</span> Action Label</label>

                        <input type="text" name="action_label" value="<?php echo ($this->input->post('action_label') ? $this->input->post('action_label') : $action['action_label']); ?>"  class="form-control" id="action_label" required=""/>
                    </div>


                    <div class="form-group">
                        <label for="action_icon" class="control-label"> Action Icon</label>
                        <input type="text" name="action_icon" value="<?php echo ($this->input->post('action_icon') ? $this->input->post('action_icon') : $action['action_icon']); ?>" class="form-control" id="action_icon" />

                    </div>

                    <div class="form-group">
                        <label for="action_order" class="control-label"> Action Order</label>
                        <input type="text" name="action_order" value="<?php echo ($this->input->post('action_order') ? $this->input->post('action_order') : $action['action_order']); ?>" class="form-control" id="action_order" />

                    </div>  



                </div>
                <div class="box-footer col-md-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a href="<?php echo site_url("action/view_actions") ?>" class="btn btn-danger"> <i class="fa fa-close">Close </i>   </a>

                </div>				
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


