
<div class="card shadow p-0 m-0">

    <!--        <div class="card-header py-3 ">
                <h6 class="m-0 font-weight-bold text-primary">List of Faculty   </h6>
            </div>-->
    <div class="card-body p-2">
        <div class="row">
            <div class="col-md-6 text-center mb-2 text-primary">  List of meal Feeding Time </div>
               </div>

        <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Meal Type</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th> Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($meal_hours as $k) {
                        ?>
                        <tr>
                            <td> <?php echo $i++; ?>  </td>
                            <td><?php echo $k['meal_type']; ?></td>
                            <td><?php echo $k['start_time']; ?></td>
                            <td><?php echo $k['end_time']; ?></td>
                            <td>
                                <a href="<?php echo site_url('BasicData/edit_meal_time/' . $k['meal_type']); ?>" data-provide="toggle" title="Edit feeding time" class="btn btn-outline-success btn-sm"><span class="fa fa-edit">  Edit </span> </a>  &nbsp; &nbsp; &nbsp;
<!--<a href="<?php echo site_url('BasicData/reset_meal_time/' . $k['meal_type']); ?>" data-provide="toggle" title="reset feeding time to normal"><span class="fa fa-backward"> Reset to Normal</span> </a>-->  

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


