<div class="card shadow mb-4">
    
            <div class="card-header py-1 pt-0 row">
                <div class="col-md-6 pt-0">  <h3 class="box-title mt-0 pb-0"> List of Activities  </h3></div>
                <div class="col-md-6 text-right mt-2">
                    <a href="<?php echo site_url('Action/create_action'); ?>" class="btn btn-success btn-sm mt-0"><i class="fa fa-plus-circle"> </i> Add Action </a> 
                </div>
            </div>
    
            <div class="card-body">
                
                <table width="100%" class="table table-striped table-sm " id="dataTable" cellpadding="0" >
                    <thead>  <tr>
                        <th>#</th>						
                        <th> Action Name</th>
                         <th> Action Label</th>
                        <th>Description</th>
                        <th  style="white-space: nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    foreach ($actions as $p) { 
                        $aid=$p['activityID'];
                        ?>
                        <tr>
                            <td>  <?php echo $i++ ?></td>
                            <td><?php echo $p['action_name']; ?></td>
                            <td><?php echo $p['action_label']; ?></td>
                            <td><?php echo $p['action_description']; ?></td>
                            <td style="white-space: nowrap">
                                <a href="<?php echo site_url('action/edit_action/'.$aid ); ?>" class="btn btn-outline-success btn-sm" ><span class="fa fa-edit"></span> Edit</a> 
                                <a onclick="return confirmDelete();"   href="<?php echo site_url('action/remove/'. $aid); ?>" class="btn btn-outline-danger btn-sm"><span class="fa fa-trash"></span> Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
      
  
</div>
