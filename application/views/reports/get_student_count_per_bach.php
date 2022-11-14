<div class="card shadow mb-4">    
            <div class="card-header py-1 row text-primary ">

                <div class="m-0 font-weight-bold text-primary col-md-6">Number of Students in each  department and bach</div>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Year</th>
                         <th>Program</th>
                        <th>Admission Type</th>                       
                        <th># Students</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $totalstudents=0;
                    $index=1; foreach ($students_per_bach as $u) { ?>
                      
                        <tr><td><?php echo $index++?> </td>
                            <td><?php echo $u['dept_name']; ?></td>
                            <td><?php echo $u['Year']; ?></td>
                            <td><?php echo $u['admission']; ?></td>
                             <td><?php echo $u['Program']; ?></td>
                            <td><?php echo $u['noofstud']; ?></td>

                        </tr>
                    <?php
                    $totalstudents=$totalstudents+$u['noofstud']; 
                    
                    
                    } ?>
                         
                       
                        </tbody>
                        <tfoot>
                            
                            <tr class="bg-gray-100">  <td colspan="5" class="text-right text-danger"> Total  number of students </td>
                                <td class="text-primary"> <?php echo $totalstudents ?> </td> </tr>  
                            
                        </tfoot>
                </table>

            </div>
      

</div>




