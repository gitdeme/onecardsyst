
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Basic Cafeteria Statistical information</h6>
    </div>
    <div class="card-body">

        <table class="table">
            <tr>
                <td  style="width: 50%">  Number of students who leaves today </td> 
                <td> <?php echo $summery['leave_today'] ?>   </td> 
            </tr> 

            <tr>
                <td>  Number of students who will come back today </td> 
                <td> <?php echo $summery['return_today'] ?>  </td> 
            </tr> 
            <tr>
                <td>  Number of students who will come back tomorrow </td> 
                <td> <?php echo $summery['return_tomorrow'] ?>   </td> 
            </tr> 
            <tr>
                <td>  Number of students who will come back after tomorrow </td> 
                <td> <?php echo $summery['return_aftertomorrow'] ?>   </td> 
            </tr> 
            <tr>
                <td>  Number of students who will not return in three days  </td> 
                <td> <?php echo $summery['with_family'] ?>   </td> 
            </tr> 
            <tr>
                <td>  Number of students who are using cafeteria service. </td> 
                <td> <?php echo $summery['cafe_students'] ?>   </td> 
            </tr> 
            <tr>
                <td>  Number of students who are Non-cafeteria. </td> 
                <td> <?php echo $summery['non_cafe_students'] ?>   </td> 
            </tr> 

            <tr class="text-success">
                <td> <h3> Expected Number of Students who will use cafeteria service today</h3> </td> 
                <td> <h1><?php echo $summery['available_cafe_clients'] ?> </h1>  </td> 
            </tr> 

        </table>







    </div>



