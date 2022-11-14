

<div class="card shadow p-0 m-0">
    <form name="dept" method="post" action="add_department" >

        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary">Add New Department  </h6>
        </div>
        <div class="card-body p-2">

            <div class="col-md-6">
                <label for="dept_name" class="control-label"> <span class="text-danger"> *</span>Department Name  </label>
                <div class="form-group">
                    <input type="text" name="dept_name" value="<?php echo $this->input->post('dept_name'); ?>" class="form-control" id="dept_name" required=""/>
               <span class="text-danger"><?php echo form_error('dept_name'); ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <label for="faculty_code" class="control-label"><span class="text-danger"> *</span>department  faculty/school </label>
                <div class="form-group">
                     
                      <select name="fac_code" name="fac_code" class="form-control">
                                    <?php if (sizeof($faculties) > 1) {
                                        ?>
                                        <option value="">  faculty/school  of the department</option>
                                        <?php
                                    }
                                    foreach ($faculties as $f) {
                                        $selected = ($f['faculity_code'] == $this->input->post('faculity_code')) ? 'selected="selected"' : "";
                                        echo '<option value="' . $f['faculity_code'] . '" ' . $selected . '>' . $f['faculity_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                    <span class="text-danger"><?php echo form_error('fac_code'); ?></span>
           
                </div>
            </div>
            <div class="form-group text-center">
                <input type="submit" name="save_dept" value="Save" class="btn btn-success"/>
                  <a class="btn btn-danger" href="<?php echo site_url("BasicData/view_departments"); ?>"> Cancel</a>
      
            </div>
                

        </div>
    </form>
</div>









