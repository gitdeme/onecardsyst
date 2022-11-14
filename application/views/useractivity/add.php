<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Useractivity Add</h3>
            </div>
            <?php echo form_open('useractivity/add'); ?>
          	<div class="box-body">
          		<div class="row clearfix">
					<div class="col-md-6">
						<label for="activityID" class="control-label">ActivityID</label>
						<div class="form-group">
							<input type="text" name="activityID" value="<?php echo $this->input->post('activityID'); ?>" class="form-control" id="activityID" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="activityName" class="control-label">ActivityName</label>
						<div class="form-group">
							<input type="text" name="activityName" value="<?php echo $this->input->post('activityName'); ?>" class="form-control" id="activityName" />
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