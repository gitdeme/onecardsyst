
<div class="card shadow mb-4">    
    <div class="card-header py-1 row text-primary ">

        <div class="m-0 font-weight-bold text-primary col-md-6"> Cafeteria user change history </div>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Sex</th>
                    <th>ID Number</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Admission Type</th>                       
                    <th>Program</th>
                    <th>Changed To</th>
                    <th>Changed by</th>
                    <th>Changed on</th>


                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                foreach ($records as $u) {
                    ?>

                    <tr><td><?php echo $index++ ?> </td>
                        <td><?php echo ucwords(strtolower($u['FullName'])); ?></td>
                        <td><?php echo $u['Sex']; ?></td>
                        <td><?php echo $u['IDNumber']; ?></td>
                        <td><?php echo $u['dept_name']; ?></td>
                        <td><?php echo $u['Year']; ?></td>
                        <td><?php echo $u['admission']; ?></td>
                        <td><?php echo $u['Program']; ?></td>
                        <td><?php echo $u['changed_to']; ?></td>
                        <td><?php echo $u['first_name']." ". $u['last_name']; ?></td>
                        <td><?php echo $u['action_performed_on']; ?></td>

                    </tr>
                    <?php
                }
                ?>


            </tbody>

        </table>

    </div>


</div>



