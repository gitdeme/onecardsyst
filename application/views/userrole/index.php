<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Userroles Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('userrole/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>UserRoleID</th>
						<th>Username</th>
						<th>ActivityID</th>
						<th>CreatedOn</th>
						<th>CreatedBy</th>
						<th>IsDeleted</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($userroles as $u){ ?>
                    <tr>
						<td><?php echo $u['userRoleID']; ?></td>
						<td><?php echo $u['username']; ?></td>
						<td><?php echo $u['activityID']; ?></td>
						<td><?php echo $u['createdOn']; ?></td>
						<td><?php echo $u['createdBy']; ?></td>
						<td><?php echo $u['isDeleted']; ?></td>
						<td>
                            <a href="<?php echo site_url('userrole/edit/'.$u['']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('userrole/remove/'.$u['']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
