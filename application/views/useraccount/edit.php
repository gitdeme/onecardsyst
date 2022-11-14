<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Useraccount Edit</h3>
            </div>
			<?php echo form_open('useraccount/edit/'.$useraccount['']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<label for="password" class="control-label">Password</label>
						<div class="form-group">
							<input type="text" name="password" value="<?php echo ($this->input->post('password') ? $this->input->post('password') : $useraccount['password']); ?>" class="form-control" id="password" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="firstName" class="control-label">FirstName</label>
						<div class="form-group">
							<input type="text" name="firstName" value="<?php echo ($this->input->post('firstName') ? $this->input->post('firstName') : $useraccount['firstName']); ?>" class="form-control" id="firstName" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="middleName" class="control-label">MiddleName</label>
						<div class="form-group">
							<input type="text" name="middleName" value="<?php echo ($this->input->post('middleName') ? $this->input->post('middleName') : $useraccount['middleName']); ?>" class="form-control" id="middleName" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="lastName" class="control-label">LastName</label>
						<div class="form-group">
							<input type="text" name="lastName" value="<?php echo ($this->input->post('lastName') ? $this->input->post('lastName') : $useraccount['lastName']); ?>" class="form-control" id="lastName" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="empID" class="control-label">EmpID</label>
						<div class="form-group">
							<input type="text" name="empID" value="<?php echo ($this->input->post('empID') ? $this->input->post('empID') : $useraccount['empID']); ?>" class="form-control" id="empID" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="sex" class="control-label">Sex</label>
						<div class="form-group">
							<input type="text" name="sex" value="<?php echo ($this->input->post('sex') ? $this->input->post('sex') : $useraccount['sex']); ?>" class="form-control" id="sex" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="positionID" class="control-label">PositionID</label>
						<div class="form-group">
							<input type="text" name="positionID" value="<?php echo ($this->input->post('positionID') ? $this->input->post('positionID') : $useraccount['positionID']); ?>" class="form-control" id="positionID" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="phone" class="control-label">Phone</label>
						<div class="form-group">
							<input type="text" name="phone" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $useraccount['phone']); ?>" class="form-control" id="phone" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="username" class="control-label">Username</label>
						<div class="form-group">
							<input type="text" name="username" value="<?php echo ($this->input->post('username') ? $this->input->post('username') : $useraccount['username']); ?>" class="form-control" id="username" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="loginattemps" class="control-label">Loginattemps</label>
						<div class="form-group">
							<input type="text" name="loginattemps" value="<?php echo ($this->input->post('loginattemps') ? $this->input->post('loginattemps') : $useraccount['loginattemps']); ?>" class="form-control" id="loginattemps" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="lastlogout" class="control-label">Lastlogout</label>
						<div class="form-group">
							<input type="text" name="lastlogout" value="<?php echo ($this->input->post('lastlogout') ? $this->input->post('lastlogout') : $useraccount['lastlogout']); ?>" class="has-datetimepicker form-control" id="lastlogout" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="lastlogin" class="control-label">Lastlogin</label>
						<div class="form-group">
							<input type="text" name="lastlogin" value="<?php echo ($this->input->post('lastlogin') ? $this->input->post('lastlogin') : $useraccount['lastlogin']); ?>" class="has-datetimepicker form-control" id="lastlogin" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="isActive" class="control-label">IsActive</label>
						<div class="form-group">
							<input type="text" name="isActive" value="<?php echo ($this->input->post('isActive') ? $this->input->post('isActive') : $useraccount['isActive']); ?>" class="form-control" id="isActive" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="isDeleted" class="control-label">IsDeleted</label>
						<div class="form-group">
							<input type="text" name="isDeleted" value="<?php echo ($this->input->post('isDeleted') ? $this->input->post('isDeleted') : $useraccount['isDeleted']); ?>" class="form-control" id="isDeleted" />
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
            	<button type="submit" class="btn btn-success">
					<i class="fa fa-check"></i> Save
				</button>
	        </div>				
			<?php echo form_close(); ?>
		</div>
    </div>
</div>