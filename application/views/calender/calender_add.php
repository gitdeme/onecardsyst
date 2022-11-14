

<div class="card shadow p-0 m-0">
    <form name="dept" method="post" action="set_calender">

        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary">Add New Calender  </h6>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-md-6">
                    <label for="start_date" class="control-label"> <span class="text-danger"> *</span>Start Date  </label>
                    <div class="form-group">
                        <input type="date" name="start_date" value="<?php echo $this->input->post('start_date'); ?>" class="form-control" id="start_date" required=""/>
                        <span class="text-danger"><?php echo form_error('start_date'); ?></span>
                    </div>
                </div>
<!--                 <div class="form-group">
                  <label>Date and time:</label>
                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime"/>
                        <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>-->
                
                
                <div class="col-md-6">
                    <label for="end_date" class="control-label"> <span class="text-danger"> *</span>End Date  </label>
                    <div class="form-group">
                        <input type="date" name="end_date" value="<?php echo $this->input->post('end_date'); ?>" class="form-control" id="end_date" required=""/>
                        <span class="text-danger"><?php echo form_error('end_date'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <label for="dept_code" class="control-label  "><span class="text-danger"> *</span>department </label>
                    <div class="form-group">
                        <select name="dept_code[]"  name="dept_code" class="form-control" multiple>
                            <?PHP
                            
                          $i=0;
                          $prev_code=0;
                            foreach ($departments as $d) {
                                   $fac=$d['faculity_code'] ;
                                  if($fac!=$prev_code){    
                                      if($i%2==0){
                                            $bgclass="bg-gray-200";
                                      }else{
                                           $bgclass=""; 
                                      }
                                      $i++;
                                    
                                   //  echo "<optiongroup class='$bgclass'>";
                                  }
                                $selected = ($d['dept_code'] == $this->input->post('dept_code'))?'selected="selected"' : "";
                                echo '<option class="'.$bgclass.'" value="'.$d['dept_code'] . '" '. $selected .'>' .$d['dept_name'] . '</option>';
                                                     
                              
                            $prev_code=$fac;
                            
                            
                            }
                            
                            ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('dept_code'); ?></span>

                    </div>
                </div>
                <div class="col-md-6 ">
                    <label for="stud_year" class="control-label"><span class="text-danger"> *</span>Student Year </label>
                    <div class="form-group">
                        <select name="stud_year[]" name="stud_year" class="form-control" multiple>
                            <?PHP
                          
                            for ($i = 1; $i < 6; $i++) {
                                echo "<option value='$i'> $i </option>";
                            }
                            ?>
                        </select>
                        <span class="text-danger"><?php
                            echo form_error('stud_year');                         
                            ?></span>

                    </div>
                </div>

            </div>  
            <div class="row">
                <div class="col-md-6 ">
                    <label for="acyear" class="control-label"><span class="text-danger"> *</span>Academic Year </label>
                    <div class="form-group">
                        <select name="acyear" name="acyear" class="form-control" required="">
                            <?PHP
                            $year = date('Y');
                            $month = date('m');
                            if ($month >= 9) {
                                $year = date('Y') - 7;
                            } else {
                                $year = date('Y') - 8;
                            }
                            $end = $year + 3;

                            for ($i = $year; $i < $end; $i++) {
                                // $selected = ($d['dept_code'] == $this->input->post('dept_code')) ? 'selected="selected"' : "";
                                echo "<option value='$i'> $i </option>";
                            }
                            ?>
                        </select>
                        <span class="text-danger"><?php
                            echo form_error('acyear');

                            ?></span>

                    </div>
                </div>
                <div class="col-md-6 ">
                    <label for="semester" class="control-label"><span class="text-danger"> *</span>Academic Semester </label>
                    <div class="form-group">
                        <select name="semester" name="semester" class="form-control" required="">
                            <option class="bg-primary" value=""> Select Academic semester</option>
                            <option value="I"> I</option>
                            <option value="II"> II</option>
                            <option value="Summar"> Summer</option>
                        </select>
                        <span class="text-danger"><?php
                            echo form_error('acyear');
                            ?></span>

                    </div>
                </div>   

            </div>

            <div class="row">
                <div class="col-md-6 ">
                    <label for="program" class="control-label"><span class="text-danger"> *</span>Program  </label>
                    <div class="form-group">
                        <select name="program" name="program" class="form-control" required>
                            <option value=""> Select Program</option>
                            <option value="Degree"> Degree</option>
                            <option value="Masters"> Masters</option>
                        </select>
                        <span class="text-danger"><?php echo form_error('program'); ?></span>

                    </div>
                </div>
                <div class="col-md-6 ">
                    <label for="admission_type" class="control-label"><span class="text-danger"> *</span>Admission Type </label>
                    <div class="form-group">
                        <select name="admission_type" name="admission_type" class="form-control" required>
                            <option value=""> Select Admission Type</option>
                            <option value="Regular"> Regular</option>
                            <option value="Extension"> Extension</option>
                            <option value="Summar"> Summer</option>
                        </select>
                        <span class="text-danger"><?php echo form_error('admission_type'); ?></span>

                    </div>
                </div>   

            </div>

        </div>
        <div class="card-footer">

            <div class="col-md-12">

                <div class="form-group text-center">
                    <input type="submit" name="save_calender" value="Save" class="btn btn-success"/>
                    <a class="btn btn-danger" href="<?php echo site_url("Calenders/view_calenders"); ?>"> Cancel</a>

                </div>  


            </div>

        </div>
    </form>
</div>









