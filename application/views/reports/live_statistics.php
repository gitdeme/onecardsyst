

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Number students assigned in each cafeteria and   Live  statistics</h6>
    </div>
    <div class="card-body">

        <table class="table">
            <tr> <th> Cafeteria </th>  <th> # of assigned Students </th>   </tr>
           <?php
         
           
           $numberofstudents=0;
           foreach ($num_of_students as $row){
               $numberofstudents=$row['number_of_students'];                          
               
           ?>
            <tr>
                <td><?php echo $row["CafeNumber"] ?>  </td> 
                <td> <?php echo $numberofstudents ?>   </td> 
            
            </tr> 
           <?php }?>


        </table>
        <hr/>

        <table class="table table-striped text-primary">
            <tr> 
                <td>  Expected number of students who will eat today(ዛሬ ሊመገቡ የሚቸሉ ተማሪዎቸ ቁጥር)) </td>  
                <td><button class="btn btn-success btn-circle btn-lg"><?php echo $expected_consumers ?> </span></button>  </td>
            
            </tr>
             <tr> 
                <td>  Number of students who ate <?php  echo $meal_time ?> (እስካሁኗ ድቂቃ ድረስ የተመገቡ)</td>  
                <td>   <button class="btn btn-success btn-circle btn-lg"> <?php echo $ate ?> </button></td>
            
            </tr>
             <tr> 
                <td>  Number of students who not ate <?php  echo $meal_time ?>(ገና ያልተመገቡ )</td>  
                <td>   <button class="btn btn-danger btn-circle btn-lg"><?php echo $not_ate ?></button>  </td>
            
            </tr>
            
            
        </table>





    </div>



<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

