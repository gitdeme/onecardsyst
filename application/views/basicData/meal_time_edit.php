<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Edit meal time</h6>
    </div>
    <div class="card-body">

          <?php echo form_open('BasicData/edit_meal_time/' .$meal_hour['meal_type']); ?>
            
            <div class="row" >  <div class="col-md-12 aler alert-info"> 
                    <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    ?>

                </div>  </div>

            <div class="form-group row">  

                <div class="col-lg-3"> 

                    <label  for="start_time"><?php echo $meal_hour['meal_type'] ?> Start Time <span class="text-danger">*</span> </label>
                    <input name="start_time" id="start_time" value="<?php echo $meal_hour['start_time'] ?>"  class="form-control" required/>

                    <input type="hidden" name="meal_type" value="<?php echo $meal_hour['meal_type'] ?>"/>


                </div> 
                   <div class="col-lg-3"> 

                    <label  for="end_time"><?php echo $meal_hour['meal_type'] ?>  End Time <span class="text-danger">*</span> </label>
                    <input name="end_time" id="end_time" value="<?php echo $meal_hour['end_time'] ?>" class="form-control" required/>




                </div> 




            </div>



            <div class="form-group row">
                <div class="col-md-12 text-center ">
                    <button class="btn btn-primary" type="submit" value="update_meal_time" name="update_meal_time"> <i class="fa fa-save">  </i> Save Change</button> 
                    <a class="btn btn-warning"href="<?php echo site_url("BasicData/view_meal_time")?>" > Cancel</a>

                </div>
            </div>
        </form>

    </div>
</div>


