
<div class="card shadow mb-4" ng-app="myApp" ng-controller="procter">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Register  Students before they leave the University</h6>
    </div>
    <div class="card-body">

        <!--        <form name="form2" method="post">
                    <div class="row">
                    <div class="col-md-6">
                        <label> Code <span class="text-danger"> * </span></label>
                        <input type="text" name="bcID" ng-blur="getStudentByID()" class="form-control"/> 
                    </div>
                    <div class="col-md-6">
                        <label>Full Name </label>
                        <input type="text" class="form-control" disabled="true" name="fname"/>
                    </div>
                    </div>
                    <div class="col-lg-6">
                        <label> Departure Date <span class="text-danger"> * </span></label> 
                        <input type="text" id="depd" value="" name="depd"  class="form-control"/> 
        
                    </div>
                    <div class="col-lg-6">
                        <label> When do you come back? <span class="text-danger"> * </span></label>
                        <input type="text" name="tbd" id="tbd" class="form-control"/>     
        
                    </div> 
                    <div class="form-group">
                        <label>   Reason <span class="text-danger"> * </span></label>
                        <textarea name="reason" class="form-control">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="saveleave" class="btn btn-success" value="Save"/>
        
                    </div>
                </form> -->

        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissable" ng-show="showsuccess"> <span>{{message}}  </span> </div>
            <div class="alert alert-danger" ng-show="showError"> <span>{{message}}  </span> </div>
            <form name="form2" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label> Code/ID Number</label>
                        <input type="text" name="bcID" ng-model="permit.bcID" class="form-control" ng-blur="getStudentByID()"/> 
                        <span ng-show="validID" style="color: red"> This Code/ID is not available </span>
                    </div>
                    <div class="col-lg-6">
                        <label>Full Name </label>
                        <input type="text" class="form-control" ng-model="permit.name" disabled="true" name="fname"/>

                    </div>
                </div>
                <div class="row">
                <div class="col-lg-3">

                    <label> Departure Date</label> 
                    <input type="text" id="depd" data-date-format="yyyy-mm-dd" ng-model="permit.depd" name="depd"  ng-value="{{today}}" class="form-control"/> 

                </div>
                <div class="col-lg-3">
                    <label> When do you come back?</label> 
                    <input type="text" name="tbd" data-date-format="yyyy-mm-dd"  ng-model="permit.tbd"  id="tbd" class="form-control"/>     

                </div> 
                  
                <div class="col-md-6">
                    <label>  Remark</label>
                    <textarea name="reason"  ng-model="permit.reason" class="form-control">                                       
                    </textarea>
                </div> 
                </div>
                <div class="form-group">
                    <button  name="saveleave" ng-disabled="validID" class="btn btn-success" value="savepermision" ng-model="permit.savepermision" ng-click="savePermision()"> Save</button>
                    <input type="reset" class="btn btn-danger" name="reset" value="Reset"/>
                </div>
            </form> 
            <div >
                <table ng-table="permitedtableParams" class="table table-bordered table-hover">
                    <tr ng-repeat="row in $data">
                        <td title="'#'"> {{ $index + 1}} </td>
                        <td title="'FullName'" filter="{ FullName: 'text'}" sortable="'FullName'">
                            <span ng-bind="row.FullName">  </span>
                        </td>
                        <td title="'ID Number'" filter="{ Id_Number: 'text'}" sortable="'Id_Number'">
                            <span ng-bind="row.Id_Number">  </span>
                        </td>
                        <td title="'Departure Date'" ng-bind="row.departureDate" filter="{ departureDate: 'text'}" sortable="'departureDate'">
                        </td>

                        <td title="'Return Date'" ng-bind="row.returnDate" filter="{ returnDate: 'text'}" sortable="'returnDate'">
                        </td>
                        <td title="'Reason'" ng-bind="row.reason" filter="{ reason: 'text'}" sortable="'reason'">
                        </td>
                        <td> 
                            <a href="#"> <i  style="color: red" class="fa fa-trash" ng-click="deletePermision(row)"></i>  </a> 
                        </td>
                    </tr>
                </table>



            </div>

        </div> 
    </div>
</div>


