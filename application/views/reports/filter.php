
<div class="card shadow p-0 m-0">
    <div class="card-header py-3 ">
        <h6 class="m-0 font-weight-bold text-primary"> Filter  Report in a range of days  </h6>
    </div>
    <div class="card-body p-2" style="min-height: 300px">

        <!--<input type="date" name="daterange"  placeholder="Month/Day/Year" />-->

        <!--        <div class="input-append date" id="datepicker" data-date="12-02-2020" data-date-format="dd-mm-yyyy">
                    <input size="16" type="text" value="12-02-2019" readonly>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>-->

        <div class="alert alert-errorr  text-danger" id="alert" >
            <strong></strong>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        cafeteria Number 
                        <select name="selectcafe_filter" class="form-control" id="selectcafe_filter">
                            <option value="">...All...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>

                        </select>  

                    </th>

                    <th>
                        Start date
                        <input type="text" name="From" class="form-control" id="date-start" data-date-format="yyyy-mm-dd" data-date="2012-02-20"/>
                        <!--<input type="text" class="form-control" name="initialdate" id="initialdate"/>-->
                    </th>
                    <th>
                        End date
                        <input class="form-control" name="To" id="date-end" data-date-format="yyyy-mm-dd" data-date="2012-02-25"/>
                        <!--<input class="form-control" type="text" name="destinationdate"  id="destinationdate" />--> 
                    </th>
                    <th><button id="submitduration" class="btn btn-success"> Submit </button></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" id="displayfilterdreport" align="center"></td></tr>
                <tr bgcolor="white" style="display:none; border-color: #ffffff;" id="resultprint">

                    <td colspan="5" align="right">
                        <div class="form-group">
                            <button  class="btn btn-success form-group"  onclick="PrintDivData('displayfilterdreport');"><i class="fa fa-print fa-2x"> Print Report</i> </button>  

                        </div>
                    </td>
                </tr>  


            </tbody>

        </table>




      

    </div>
</div>