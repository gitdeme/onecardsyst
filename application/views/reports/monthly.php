<div class="card shadow p-0 m-0">
    <div class="card-header py-3 ">
        <h6 class="m-0 font-weight-bold text-primary"> Monthly report </h6>
    </div>
    <div class="card-body p-2" style="min-height: 300px">

        <table width="100%">
            <tr>
                <th class="text-right"> Select Month </th>
                <td>
                    <select name="selectmonth" class="form-control"  id="selectmonthm" >
                        <option value="">..Select Month..</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>            
                    </select>  


                </td>
                <th class="text-right"> Select Year</th>

                <td>
                    <select name="selectyear" class="form-control" id="selectyearm" >
                        <option value="">..Select Year..</option>
                        <?php
                        $dts = date("Y");
                        for ($i = $dts; $i >= 2015; $i--) {
                            echo "<option value='$i'>" . $i . "</option>";
                        }
                        ?>      
                    </select> 
                </td>
                <th>  cafeteria Number</th>
                <td>

                    <select class="form-control" name="selectcafe2" id="selectcafe2_m">
                        <option value="">...All...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>

                    </select>
                </td>
            </tr>
            <tr><td colspan="6" id="displaymonthreport"></td></tr>
            <tr bgcolor="white" style="display:none; border-color: #ffffff;" id="resultprint"><td colspan="2" align="right"  ><input type="button" value="Print Report" onclick="PrintDivData('displaymonthreport');"/> </td></tr>
        </table>
    </div>
</div>
