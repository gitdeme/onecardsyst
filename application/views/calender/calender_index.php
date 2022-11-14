


<div class="card shadow p-0 m-0">

    <!--        <div class="card-header py-3 ">
                <h6 class="m-0 font-weight-bold text-primary">List of Faculty   </h6>
            </div>-->
    <div class="card-body p-2">
        <div class="text-right mb-2"> <a class="btn btn-success" href="<?php echo site_url("Calenders/set_calender"); ?>">  <i class="fa fa-plus-circle"></i> Add New</a> </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department </th>                                             
                        <th> Academic Year</th>
                        <th> semester</th>
                        <th>Year </th> 
                        <th> Admission</th>
                        <th> Program</th>
                        <th> start date</th>
                        <th> End date</th>
                        <th> Status</th>

                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($calenders as $k) {
                        ?>
                        <tr>
                             <td> <?php echo $i++; ?>  </td>
                            <td><?php echo $k['dept_name']; ?></td>                                                  
                            <td>  <?php echo $k['acyear']; ?></td>
                              <td>  <?php echo $k['semester']; ?></td>
                              <td>  <?php echo $k['stud_year']; ?></td>    
                            <td>  <?php echo $k['admission_type']; ?></td>    
                            <td>  <?php echo $k['program']; ?></td>   
                            <td>  <?php echo $k['start_date']; ?></td>
                            <td>  <?php echo $k['end_date']; ?></td>
                            <td>  <?php echo $k['is_active']; ?></td>
                            <td style="white-space: nowrap">
                                <a href="<?php echo site_url('Calenders/edit_faculty/' . $k['cal_row_id']); ?>" class="btn btn-outline-success btn-sm"><span class="fas fa-edit"></span> Edit</a>  &nbsp; &nbsp;
                                <a onclick="return confirmDelete();"   href="<?php echo site_url('Calenders/remove_faculty/' . $k['cal_row_id']); ?>"   class="btn btn-outline-danger btn-sm"><span class="fa fa-trash"></span> Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>



