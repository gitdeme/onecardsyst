<div class="card shadow mb-4">

    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">  Give Permission by selecting role  </h6>

    </div>
    <?php $attributes=array("name"=>"role_form", "id"=>"role_form");
    echo form_open("action/group_permission", $attributes) ?>
    <div class="card-body">

        <div class="col-md-12">
            <label for="role" class="control-label"><span class="text-danger">*</span> Role</label>
            <div class="form-group">
                <select name="role" id="role_options" class="form-control">
                    <?php
                    foreach ($groups as $g) {
                        $selected=$g->id==$group_id?'selected':"";
                        echo '<option  value="'. $g->id.'"  '.$selected.' > ' . $g->name . '(' . $g->description . ')</option>';
                    }
                    ?>
                </select>

            </div>
        </div>   

        <table width="100%" class="table table-striped" id="dataTable">

            <thead>  <tr>
                    <th>#</th>	
                    <th>select</th>
                    <th> Action Name</th>
                    <th>Description</th>                        
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($actions as $p) {
                    $aid = $p['activityID'];
                    ?>
                    <tr>
                        <td>  <?php echo $i++ ?></td>
                        <td><input type="checkbox" name="actions[]" <?php echo $p['checked'] ?>  class="checkbox" value="<?php echo $aid ?>" /> </td>
                        <td><?php echo $p['action_name']; ?></td>
                        <td><?php echo $p['action_description']; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="col-md-12 text-center">

            <input type="submit" name="save_permission" value="save" class="btn btn-success" style="width: 100px" />  
        </div>
        <?php echo form_close() ?>

    </div>


</div>


