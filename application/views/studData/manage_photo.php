<div class="row">

    <div class="col-md-12 bg-white pl-3 pr-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> Manage Photo                </h6>
        </div>

        <div class="card-body">
            <form  method="post" action="manage_student_photo">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">                   
                            <label for="year"> <span translate>Academic Year</span> <span class="text-danger">*</span></label>
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
                            <span class="text-danger"><?php echo form_error('acyear'); ?></span>
                        </div> 
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">                   
                            <label for="semester"> <span translate>Semester </span> <span class="text-danger">*</span></label>
                            <select name="semester" name="semester" class="form-control" required="">
                                <option  value=""> Select semester</option>
                                <option value="I" <?php echo $criteria["semester"] == "I" ? "Selected" : "" ?>> I</option>
                                <option value="II" <?php echo $criteria["semester"] == "II" ? "Selected" : "" ?>> II</option>
                                <option value="Summar" <?php echo $criteria["semester"] == "Summar" ? "Selected" : "" ?>> Summer</option>
                            </select>
                            <span class="text-danger"><?php
                                echo form_error('acyear');
                                ?></span>
                        </div> 
                    </div>    


                    <div class="col-md-2">
                        <div class="form-group">                   
                            <label for="program"> <span translate>Program </span> <span class="text-danger">*</span></label>
                            <select name="program" name="program" class="form-control" required>
                                <option value="" > Select Program</option>
                                <option value="Degree" <?php echo $criteria["Program"] == "Degree" ? "Selected" : "" ?>> Degree</option>
                                <option value="Master" <?php echo $criteria["Program"] == "Master" ? "Selected" : "" ?>> Masters</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('program'); ?></span>
                        </div>  
                    </div>

                    <div class="col-md-2">

                        <div class="form-group">
                            <label for="admission_type" class="control-label"><span class="text-danger"> *</span>Admission Type </label>
                            <select name="admission_type" name="admission_type" class="form-control" required>
                                <option value=""> Select Admission Type</option>
                                <option value="Regular" <?php echo $criteria["admission"] == "Regular" ? "Selected" : "" ?> > Regular</option>
                                <option value="Extension" <?php echo $criteria["admission"] == "Extension" ? "Selected" : "" ?>> Extension</option>
                                <option value="Summar" <?php echo $criteria["admission"] == "Summar" ? "Selected" : "" ?>> Summer</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('admission_type'); ?></span>

                        </div>

                    </div>


                    <div class="col-md-2 pt-1">
                        <div class="form-group">
                            <br/>   
                            <label for="search_photo"  class="control-label"> </label>
                            <button class="btn btn-success" name="search_photo" value="Search" type="submit"> <i class="fa fa-search"> </i> Search  </button>

                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive row">
                <div class="col-md-12">
                    <table   class="table table-bordered table-hover table-responsive" id="dataTable">
                        <thead>
                        <th>N<u>o</u></th>
                        <th> Full Name </th> 
                        <th> ID Number </th> 
                        <th> Department </th> 
                        <th> Sex </th>
                        <th>  Photo</th>
                        <th>  Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($students as $s) {
                                ?>
                                <tr>
                                    <td title="'#'"><?php echo $i++ ?></td>
                                    <td> <?php echo $s['FullName'] ?>  </td>

                                    <td> <?php echo $s['IDNumber'] ?>    </td>
                                    <td>      <?php echo $s['dept_name'] ?>   </td>
                                    <td> <?php echo $s['Sex'] ?>           </td>

                                    <td title="'Photo'">  <img src="<?php echo base_url() . $s['photo'] ?>" width="100" height="80" alt="No Photo"/>       </td>
                                    <td style="white-space: nowrap">  
<?php if(!empty($s['photo'] )){ ?>
                                        <a class="btn btn-danger btn-sm" href="<?php echo site_url("ManageStudent/manage_student_photo?delete_by_id=" . $s['IDNumber']) ?>"> Remove Phto</a>
<?php }?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>  


                </div>
            </div>
        </div>  
    </div>

</div>



