<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Useractivities Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('useractivity/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>ActivityID</th>
						<th>ActivityName</th>
						<th>IsDeleted</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($useractivities as $u){ ?>
                    <tr>
						<td><?php echo $u['activityID']; ?></td>
						<td><?php echo $u['activityName']; ?></td>
						<td><?php echo $u['isDeleted']; ?></td>
						<td>
                            <a href="<?php echo site_url('useractivity/edit/'.$u['']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('useractivity/remove/'.$u['']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
