
<div class="card shadow p-0 m-0">


    <div class="card-header py-3 ">
        <h6 class="m-0 font-weight-bold text-primary"> Yearly Report  </h6>
    </div>
    <div class="card-body p-2" style="min-height: 300px">
        <table width="100%"><tr>
                <th class="text-right"> cafeteria Number</th>
                <td>
                    <select class="form-control" name="selectcafe2" id="selectcafe2_y">
                        <option value="">...All...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>

                    </select> 
                </td>
                <th>  Year</th>
                <td align="center">
                    <select name="select1" id="selectyeary"  class="form-control">
                        <option value="">...select Year...</option>
                        <?php
                        $dts = date("Y");
                        for ($i = $dts; $i >=2015 ; $i--) {
                            echo "<option value='$i'>" . $i . "</option>";
                        }
                        ?>

                    </select>
                </td>
                

            </tr>
            <tr> <td colspan="4"> <hr/>  </td></tr>
            <tr>
                
                <td colspan="4"  id="displayyearreport"></td></tr>
            <tr bgcolor="white" style="display:none; border-color: #ffffff;" id="resultprint"><td  align="right"  >
                    <button    class="btn btn-primary" onclick="PrintDivData('displayyearreport');"> <i class="fa fa-print"></i>  Print </button></td></tr>
        </table>
    </div>
</div>