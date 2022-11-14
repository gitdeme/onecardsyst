<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Useraccount Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('useraccount/add'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus-circle">  </i>   አዲስ ለመመዝገብ </a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Password</th>
						<th>FirstName</th>
						<th>MiddleName</th>
						<th>LastName</th>
						<th>EmpID</th>
						<th>Sex</th>
						<th>PositionID</th>
						<th>Phone</th>
						<th>Username</th>
						<th>Loginattemps</th>
						<th>Lastlogout</th>
						<th>Lastlogin</th>
						<th>IsActive</th>
						<th>IsDeleted</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($useraccount as $u){ ?>
                    <tr>
						<td><?php echo $u['password']; ?></td>
						<td><?php echo $u['firstName']; ?></td>
						<td><?php echo $u['middleName']; ?></td>
						<td><?php echo $u['lastName']; ?></td>
						<td><?php echo $u['empID']; ?></td>
						<td><?php echo $u['sex']; ?></td>
						<td><?php echo $u['positionID']; ?></td>
						<td><?php echo $u['phone']; ?></td>
						<td><?php echo $u['username']; ?></td>
						<td><?php echo $u['loginattemps']; ?></td>
						<td><?php echo $u['lastlogout']; ?></td>
						<td><?php echo $u['lastlogin']; ?></td>
						<td><?php echo $u['isActive']; ?></td>
						<td><?php echo $u['isDeleted']; ?></td>
						<td>
                            <a href="<?php echo site_url('useraccount/edit/'.$u['']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('useraccount/remove/'.$u['']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
