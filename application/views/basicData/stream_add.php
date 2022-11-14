

<div class="card shadow p-0 m-0">
    <form name="dept" method="post" action="add_stream" >

        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary">Add Stream  </h6>
        </div>
        <div class="card-body p-2">
         
            <div class="col-md-6">
                <label for="dept_code" class="control-label"><span class="text-danger"> *</span>department  </label>
                <div class="form-group">
                     
                      <select name="dept_code" name="dept_code" class="form-control">
                                    <?php if (sizeof($departments) > 1) {
                                        ?>
                                        <option value="">  select department</option>
                                        <?php
                                    }
                                    foreach ($departments as $f) {
                                        $selected = ($f['dept_code'] == $this->input->post('dept_code')) ? 'selected="selected"' : "";
                                        echo '<option value="' . $f['dept_code'] . '" ' . $selected . '>' . $f['dept_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                    <span class="text-danger"><?php echo form_error('fac_code'); ?></span>
           
                </div>
            </div>
              <div class="col-md-6">
                <label for="stream_name" class="control-label"> <span class="text-danger"> *</span>Stream Name  </label>
                <div class="form-group">
                    <input type="text" name="stream_name" value="<?php echo $this->input->post('stream_name'); ?>" class="form-control" id="stream_name" required=""/>
               <span class="text-danger"><?php echo form_error('stream_name'); ?></span>
                </div>
            </div>
            <div class="form-group text-center">
                <input type="submit" name="save_dept" value="Save" class="btn btn-success"/>   
                <a class="btn btn-danger" href="<?php echo site_url("BasicData/view_streams"); ?>"> Cancel</a>
      
            </div>               

        </div>
    </form>
</div>

