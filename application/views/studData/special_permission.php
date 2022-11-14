<div id="allowforlimitedTime">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Special case permission for  limited period of time</h6>
        </div>
        <div class="card-body">
            <?php echo form_open("manageStudent/allow_service_by_exceptional_case") ?>                                       
              
                <div class="row bg-gradient-light">
                    <div class="col-md-3">
                        <!--<label> Student ID</label>-->
                        <input type="text" name="studid" placeholder="Enter ID or Barcode" id="studid"  required=""   class="form-control"/>                                              
                        <div class="text-danger" id="iderror">  </div>
                    </div>      
                    <div class="col-md-3">
                        <!--<label>Full  Name</label>-->
                        <input type="text" name="name" placeholder="Student Name will display"  readonly="" required=""  id="studentname" class="form-control" />                                              
                    </div>   
                    <div class="col-md-3">
                        <!--<label>  Permission Expiry Date</label>-->
                        <input data-date-format="yyyy-mm-dd" data-provide="datepicker" type="text" name="lastpermissionDate"  placeholder="Enter permission Expiry Date"  required=""  id="lastpermissionDate" class="lastpermissionDate form-control" /> 
                   

                    </div>

                    <div class="col-md-3">
                       
                        <button  type="submit"  name="special_permission_save" value="Save"  class="btn btn-success" id="sp">  <i class="fa fa-save"> save  </i> </button>

                    </div>
                </div>


          <?php echo form_close() ?>
            <hr class="text-black-50"/>
           <div class="table-responsive">
            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>IDNO.</th>
                        <th>department</th>
                        <th>Program</th>
                        <th>expiry date</th>
                        <th>created by</th>
                        <th> created on </th>
                        <th> </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $i = 0;
                    foreach ($special_permissions as $row) {
                        $i++;
                                $id=$row['auto_ID'];
                        ?>
                        <tr>
                         
                            <td><?php echo $i ?></td>
                            <td><?php echo $row['FullName'] ?></td>
                            <td><?php echo $row['IDNumber'] ?></td>
                            <td><?php echo $row['dept_name'] ?></td>
                             <td><?php echo $row['Program'] ?></td>
                            <td><?php echo $row['lastDate'] ?></td>                           
                            <td><?php echo $row['created_by'] ?></td>
                             <td><?php echo $row['created_on'] ?></td>
                             <td> <a class="btn btn-outline-danger btn-sm " href="<?php  echo site_url("ManageStudent/revoke_permission/$id")?>"> Remove </a> </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
            

        </div>
    </div>
</div>

