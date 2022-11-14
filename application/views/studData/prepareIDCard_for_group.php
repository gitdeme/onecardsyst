<div class="row" ng-app="csa" ng-controller="macc">

    <div class="col-md-12 bg-white pl-3 pr-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
            Register Student who applied for ID Card then    Generate Student ID Card in pdf format</h6>
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
        <?php echo form_open("IDCard/save_id_card_request") ?>
        <div class="row">


            <div class="col-md-6">
                <div class="form-group">                   
                    <label for="studentID"> <span translate>Student ID No. </span> <span class="text-danger">*</span></label>
                    <input type="text" name="studentID" id="studentID" ng-model="studentID" required     class="form-control">
                </div> 

            </div> 
            <div class="col-md-6">
                <br/>
                <button class="btn btn-success" type="submit"> Save </button>                          
            </div>

        </div>
        <?php
        echo form_close();
        ?>
        <div class="col-lg-12">
            <table class="table table-bordered table-striped  table-sm" id="dataTable" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> Full Name</th>
                        <th> Sex </th>   
                        <th>Year </th>
                        <th> ID Number </th>  
<!--                        <th>Program </th>
                        <th>Adm.Type</th>  -->
                        <th>Department </th>
                        <th>Requested On</th>
                        <!--<th>Registered  By</th>--> 
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($requests as $k) {
                        ?>
                        <tr>
                            <td> <?php echo $i++; ?>  </td>
                            <td><?php echo $k['FullName']; ?></td>
                            <td><?php echo $k['Sex']; ?></td> 
                            <td><?php echo $k['Year']; ?></td>                            
                            <td><?php echo $k['IDNumber']; ?> </td>             
<!--                            <td><?php echo $k['Program']; ?></td> 
                            <td><?php echo $k['admission']; ?></td> -->
                            <td><?php echo $k['dept_name']; ?></td> 
                            <td><?php echo $k['created_on']; ?></td>
                            <!--<td><?php echo $k['created_by']; ?></td>-->

                            <td style="white-space: nowrap">

                                <a href="<?php echo site_url('IDCard/save_id_card_request?request_id=' . $k['request_id'] . '& complete=true'); ?>" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span> Complete</a> 
                                <a onclick="return confirmDelete()"   href="<?php echo site_url('IDCard/save_id_card_request?request_id=' . $k['request_id'] . ' & delete=true'); ?>"  class="btn btn-danger btn-sm"><span class="fa fa-trash"></span> Delete</a>
                           </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


            <div class="row text-center"> 
                <div class="col-md-12">

                    <a  href="print_id_card?print_id_for_group=true & print_id=true" class="btn btn-success"> <i class="fa fa-file-pdf" > Generate ID card front Side</i> </a>
                    <a  href="print_ID_card_back_side?print_id_back_side_for_group=true & print_id_back_side=true" class="btn btn-success"> <i class="fa fa-file-pdf"> Generate ID card back Side</i> </a>


                </div>

            </div>
        </div>


    </div>

</div>
<?php







