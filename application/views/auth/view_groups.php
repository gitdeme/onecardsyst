
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row"> 
            <div class="col-md-6 "> <h6 class="m-0 font-weight-bold text-primary">List of Roles</h6> </div>

            <div class="col-md-6 text-right"><a href="<?php echo site_url('auth/create_group'); ?>" class="btn btn-success btn-sm"> <i class="fa fa-plus-circle">  </i>  Create Role </a>  </div>
        </div>
    </div>

<div class="card-body">

    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Group Name</th>
                    <th>Group Description </th>						
                    <th style="">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;

                foreach ($groups as $s) {
                    ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo $s->name ?></td>
                        <td><?php
                            echo $s->description;
                            ?>
                        </td>
                        <!--<td><?php //echo $s['createdOn'];    ?></td>-->
                        <td>
                            <a href="<?php echo site_url('auth/edit_group/' . $s->id); ?>" class="btn btn-sm btn-outline-success py-0" style="font-size: 0.8em;"><span class="fa fa-edit"></span> Edit</a> 
                            <a onclick="return confirmDelete();"  href="<?php echo site_url('auth/remove_group/' . $s->id); ?>" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" ><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>


        </table>
    </div>
</div>
</div>


