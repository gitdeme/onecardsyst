<div class="card shadow mb-4">
        <div class="col-md-12 row text-primary"> 
            <div class="col-md-6">  <h4>Permission  granted for each group </h4></div>
            <div class="col-md-6 text-right pt-2">
                <a href="<?php echo site_url('Action/create_action'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"> </i> Add Action </a> 
            </div>
        </div>
    <div class="card-body">
        <table width="100%" class="table table-striped table-hover table-sm" id="dataTable">
            <thead>  <tr>
                    <th>#</th>	
                    <th> Role</th>
                    <th> Action Description</th>
                    <th>Created on</th>
                    <th>Created By</th>
                    <th  style="white-space: nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                // print_r($permissions);
                foreach ($permissions as $p) {
                    $aid = $p['userRoleID'];
                    ?>
                    <tr>
                        <td>  <?php echo $i++ ?></td>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['action_description']; ?></td>
                        <td><?php echo $p['createdOn']; ?></td>
                        <td><?php echo $p['createdBy']; ?></td>
                        <td style="white-space: nowrap">                                  
                            <a onclick="return confirmDelete();"   href="<?php echo site_url('action/remove_group_permission/' . $aid); ?>" class="btn btn-outline-danger btn-sm" style=""><i class="fa fa-trash fa-xs"></i> Delete</a>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>

    </div>


</div>
