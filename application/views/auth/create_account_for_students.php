<div class="card shadow mb-4">
    <div class="card-header py-1 row">
        <div class="m-0 font-weight-bold text-primary col-md-6"> Student account list</div>
        <div class="col-md-6 text-right" >  
            
            <a onclick="return createStudentaccountConfirm();" class="btn btn-success" href="<?php echo site_url("auth/create_account_for_students?key=acm123gdferkjhm583245ASJDHAhsgdASas") ?>">  <i class="fa fa-users-cog"> </i> <?php echo lang('index_create_student_account_link')  ?> </a>
            <!--<a class="btn btn-success"  href="<?php//echo site_url("auth/create_group") ?>"><?php echo lang('index_create_group_link')?>  </a>-->
            
            </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">          
            <div id="infoMessage" class="bg-info text-white"><?php echo $message; ?></div>
             
            <table   class="table table-striped table-bordered table-sm" id="dataTable" width="100%" >
                <thead>
                    <tr>
                        <th> No.</th>
                        <th><?php echo lang('index_fname_th'); ?></th>
                        <th><?php echo lang('index_lname_th'); ?></th>
                         <th><?php echo lang('index_username_th'); ?></th>
                        <th><?php echo lang('index_email_th'); ?></th>
                        <th> Work place  </th>
                         <th>Phone </th>
                        <th><?php echo lang('index_groups_th'); ?></th>
                        <th><?php echo lang('index_status_th'); ?></th>
                        <th><?php echo lang('index_action_th'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($users as $user):
                        ?>
                        <tr><td> <?php echo $i++ ?> </td>
                            <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                             <td><?php if ($user->company!=0){echo htmlspecialchars($user->company, ENT_QUOTES, 'UTF-8'); }?></td>
                              <td><?php echo htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php 
                                $atributes=array("class"=>"btn btn-outline-success btn-sm");
                                 $atribute2=array("class"=>"btn btn-outline-warning btn-sm");
                                foreach ($user->groups as $group): ?>
                                    <?php echo anchor("auth/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?><br />
                                <?php endforeach ?>
                            </td>
                            <td><?php echo ($user->active) ? anchor("auth/deactivate_student_account/" . $user->id, lang('index_active_link'),$atributes) : anchor("auth/activate_student_account/" . $user->id, lang('index_inactive_link'),$atribute2); ?></td>
                            <td><?php  
                            echo anchor("auth/edit_student_account/" . $user->id, 'Edit', $atributes); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>  

        </div>
    </div>
</div>


