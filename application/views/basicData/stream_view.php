
<div class="card shadow p-0 m-0">
        
<!--        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary">List of Faculty   </h6>
        </div>-->
        <div class="card-body p-2">
            <div class="text-right mb-2"> <a class="btn btn-success" href="<?php echo site_url("BasicData/add_stream"); ?>">  <i class="fa fa-plus-circle"></i> Add New Stream</a> </div>
          
            <div class="table-responsive">
               <table class="table" id="dataTable" width="100%" cellspacing="0">
             
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stream Name</th>
                        <th>Department Name</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($streams as $k) {
                        ?>
                        <tr>
                            <td> <?php echo $i++; ?>  </td>
                             <td><?php echo $k['stream_name']; ?></td>
                            <td><?php echo $k['dept_name']; ?></td>

                            <td>
                                <a href="<?php echo site_url('BasicData/edit_stream/' . $k['stream_ID']); ?>" class="btn btn-outline-success btn-sm"><span class="fas fa-edit"></span> Edit</a>  &nbsp; &nbsp;
                                <a onclick="return confirmDelete();"   href="<?php echo site_url('BasicData/remove_stream/' . $k['stream_ID']); ?>"  class="btn btn-outline-danger btn-sm"><span class="fa fa-trash"></span> Delete</a>
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


