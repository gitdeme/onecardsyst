<div class="row">

    <div class="col-md-12 bg-white pl-3 pr-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Update Student Information
            </h6>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                $message = $this->session->flashdata('message');
                if (!empty($message)) {
                    $status = $this->session->flashdata('status');
                    if ($status) {
                        echo "<div class='alert alert-success alert-dismissable'> " . $message . "</div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'> " . $message . "</div>";
                    }
                }
                ?>  
            </div>  

        </div>
        <?php
        echo form_open("ManageStudent/update_student_info");
        ?>
        <div class="row col-md-12">


            <div class="col-md-6">
                <div class="form-group">                   
                    <label for="studentID"> <span translate>Student ID No. </span> <span class="text-danger">*</span></label>
                    <input type="text" name="studentID" id="studentID"  value="<?php  echo isset($student_info['IDNumber'])?$student_info['IDNumber']: ""  ?>"  required     class="form-control">
                </div> 

            </div> 
            <div class="col-md-6">
                <br/>
                <button class="btn btn-success" type="submit" value="search" name="search"> Search </button>                          
            </div>

        </div>
<?php
if (isset($student_info)) {
    $fullName=$student_info['FullName'];
    $fullName_array=  explode(" ",$fullName );
       
    ?>

            <div class="col-md-12 row">
                <hr/>
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="first_name"> <span translate>First Name </span> <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo trim( $fullName_array[0]) ?>" required     class="form-control">
                        <input type="hidden" name="id" id="id" value="<?php echo $student_info['IDNumber'] ?>" required     class="form-control">
                    </div> 

                </div> 
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="father_name"> <span translate>Father Name </span> <span class="text-danger">*</span></label>
                        <input type="text" name="father_name" id="father_name"  required  value="<?php echo trim( $fullName_array[1] ) ?>"    class="form-control">
                    </div> 

                </div> 
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="grand_father_name"> <span translate> Grand Father Name </span> <span class="text-danger">*</span></label>
                        <input type="text" name="grand_father_name" id="grand_father_name"  required  value="<?php echo trim( $fullName_array[2] ) ?>"   class="form-control">
                    </div> 

                </div> 

            </div>
            <div class="col-md-12 row">
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="sex"> <span translate>Sex </span> <span class="text-danger">*</span></label>
                        <input type="text" name="sex" id="sex"  required value="<?php echo $student_info['Sex'] ?>" readonly   class="form-control">
                    </div> 

                </div>  

                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="year"> <span translate>Year </span> <span class="text-danger">*</span></label>
                        <input type="text" name="year" id="year"  required  value="<?php echo $student_info['Year'] ?>"  readonly   class="form-control">
                    </div> 

                </div>
                
                  <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="nationality"> <span translate>Nationality </span> <span class="text-danger">*</span></label>
                        <input type="text" name="nationality" id="year"  required  value="<?php echo $student_info['nationality'] ?>"    class="form-control">
                    </div> 

                </div> 

            </div>

            <div class="row col-md-12">
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="program"> <span translate>Program </span> <span class="text-danger">*</span></label>
                        <input type="text" name="program" id="program"  required  value="<?php echo $student_info['Program'] ?>"  readonly   class="form-control">
                    </div> 

                </div>  
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="admission_type"> <span translate>Admission Type</span> <span class="text-danger">*</span></label>
                        <input type="text" name="admission_type" id="admission_type"  value="<?php echo $student_info['admission'] ?>"    readonly  class="form-control">
                    </div> 

                </div> 
                <div class="col-md-4">
                    <div class="form-group">                   
                        <label for="department"> <span translate>Department</span> <span class="text-danger">*</span></label>
                        <input type="text" name="department" id="admission_type"  value="<?php echo $student_info['dept_name'] ?>"   required  readonly  class="form-control">
                    </div> 


                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success" name="update_student_data" id="update_student_data"><i class="fa fa-save">  </i> Save Changes</button>
                    <a  class="btn btn-danger" href="<?php echo site_url("ManageStudent/update_student_info"); ?>" id="cancel_update"><i class="fas fas-close">  </i> Close</a>
                    <p> </p>
                    <p> </p>
                </div>


            </div>

    <?php
}
echo form_close();
?>
        <div class="col-lg-12">

            <!--              
                            <tbody>
<?php
$i = 1;
//   foreach ($requests as $k) {
?>
                                    <tr>
                                        <td> <?php echo $i++; ?>  </td>
                                        <td><?php echo $k['FullName']; ?></td>
                                        <td><?php echo $k['Sex']; ?></td> 
                                        <td><?php echo $k['Year']; ?></td>                            
                                        <td><?php echo $k['IDNumber']; ?> </td>             
                                        <td><?php echo $k['Program']; ?></td> 
                                        <td><?php echo $k['admission']; ?></td> 
                                        <td><?php echo $k['dept_name']; ?></td> 
                                        <td><?php echo $k['created_on']; ?></td>
                                        <td><?php echo $k['created_by']; ?></td>
            
                                        <td style="white-space: nowrap">
            
                                            <a href="<?php echo site_url('ManageStudent/save_id_card_request?request_id=' . $k['request_id'] . '& complete=true'); ?>" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span> Complete</a> 
                                            <a onclick="return confirmDelete()"   href="<?php echo site_url('ManageStudent/save_id_card_request?request_id=' . $k['request_id'] . ' & delete=true'); ?>"  class="btn btn-danger btn-sm"><span class="fa fa-trash"></span> Delete</a>
                                       </td>
                                    </tr>
<?php ?>
                            </tbody>
                        </table>-->

            <!--
                        <div class="row text-center"> 
                            <div class="col-md-12">
            
                                <a  href="print_id_card?print_id_for_group=true & print_id=true" class="btn btn-success"> <i class="fa fa-file-pdf" > Generate ID card front Side</i> </a>
                                <a  href="print_ID_card_back_side?print_id_back_side_for_group=true & print_id_back_side=true" class="btn btn-success"> <i class="fa fa-file-pdf"> Generate ID card back Side</i> </a>
            
            
                            </div>
            
                        </div>-->
        </div>


    </div>

</div>
<?php









