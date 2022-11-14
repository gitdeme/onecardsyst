
<div class="card shadow p-0 m-0">
   

        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary"> Daily report  </h6>
        </div>
        <div class="card-body p-2">
            <table width="100%"><tr>
                    
                      <th class="text-right">   cafeteria Number</th>
                        <td>
                            <select class="form-control" name="selectcafe2" id="selectcafe2">
                                        <option value="">...All...</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>  
                            
                        </td>
                        <th class="text-right"> Year </th>
                    <td>
                        <select name="select1" id="selectyeard"  >
                            <option value="">...select Year...</option>
                            <?php
                            $dts = date("Y-m-d");
                            for ($i = 2011; $i <= $dts; $i++) {
                                echo "<option value='$i'>" . $i . "</option>";
                            }
                            ?>

                        </select>
                    </td> 
                    <th>Month</th>
                    <td> <select name="selectmonth"  id="selectmonthd" >
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
                    <td>  Date</td>
                    <td>
                        <select name="select3" id="selectdated">
                            <option value="">...select date...</option>
                            <?php
                            for ($i = 1; $i <= 31; $i++) {

                                if ($i < 10)
                                    $j = "0" . $i;
                                else
                                    $j = $i;


                                echo "<option value='$j'>" . $j . "</option>";
                            }
                            ?>
                        </select> </td>
                      

                </tr>


                <tr><td colspan="8" id="displayDayreport"></td></tr>
                <tr bgcolor="white" style="display:none; border-color: #ffffff;" id="resultprint"><td colspan="3" align="right"  >
                        <input type="button" value="Print Report" onclick="PrintDivData('displayDayreport');"/> </td></tr>

            </table>
        </div>
</div>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

