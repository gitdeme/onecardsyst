<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List of Absentees</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>IDNO.</th>
                        <th>department</th>
                        <th>Exit On</th>
                        <th>Return on</th>
                        <th># of days</th>
                        <th> operation </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $i = 0;
                    foreach ($absentees as $row) {
                        $i++
                        ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $row['FullName'] ?></td>
                            <td><?php echo $row['IDNumber'] ?></td>
                            <td><?php echo $row['dept_name'] ?></td>
                            <td><?php echo $row['exitDate'] ?></td>
                            <td><?php echo $row['entryDate'] ?></td>
                            <td><?php echo $row['noAbsent'] ?></td>
                            <td> <a class="text-primary" href="">  close case </a> </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


