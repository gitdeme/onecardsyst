<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Userrole Add</h3>
            </div>
            <?php echo form_open('userrole/add'); ?>
          	<div class="box-body">
          		<div class="row clearfix">
					<div class="col-md-6">
						<label for="userRoleID" class="control-label">UserRoleID</label>
						<div class="form-group">
							<input type="text" name="userRoleID" value="<?php echo $this->input->post('userRoleID'); ?>" class="form-control" id="userRoleID" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="username" class="control-label">Username</label>
						<div class="form-group">
							<input type="text" name="username" value="<?php echo $this->input->post('username'); ?>" class="form-control" id="username" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="activityID" class="control-label">ActivityID</label>
						<div class="form-group">
							<input type="text" name="activityID" value="<?php echo $this->input->post('activityID'); ?>" class="form-control" id="activityID" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="createdOn" class="control-label">CreatedOn</label>
						<div class="form-group">
							<input type="text" name="createdOn" value="<?php echo $this->input->post('createdOn'); ?>" class="has-datetimepicker form-control" id="createdOn" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="createdBy" class="control-label">CreatedBy</label>
						<div class="form-group">
							<input type="text" name="createdBy" value="<?php echo $this->input->post('createdBy'); ?>" class="form-control" id="createdBy" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="isDeleted" class="control-label">IsDeleted</label>
						<div class="form-group">
							<input type="text" name="isDeleted" value="<?php echo $this->input->post('isDeleted'); ?>" class="form-control" id="isDeleted" />
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