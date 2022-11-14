

<!--<div class=" alert alert-info"> here you can feed non-cafe student list to the system to deny students from getting cafe service. you can also deny a single student by searching by student id number.   </div>-->

<div class="row">
     <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">  Allow or Deny students to Cafeteria/         </h6>
    </div>
    <div class="col-lg-12 p-3 bg-white" ng-app="csa" ng-controller="macc" >
        
        
        <form class="form-inline">
            <div class="form-group">                   
                <label for="year"> <span translate>Academic Year</span> <span class="text-danger">*</span></label>
                <select name="year" id="band" ng-model="facModel.acyear" required ng-change="getCafeStudents()"
                        class="form-control" ng-change="">
                    <option value="">Select  Academic Year E.C.</option>
                    <option ng-repeat="option in years" value="{{option}}">
                        {{ option}}
                    </option>
                </select>
            </div> 
            <div class="form-group">                   
                <label for="program"> <span translate>Admission Type </span> <span class="text-danger">*</span></label>
                <select name="program" id="program" ng-model="facModel.program" required ng-change="getCafeStudents()"
                        class="form-control" ng-change="">
                    <option value="">Select  Program</option>
                    <option ng-repeat="option in programs" value="{{option}}">
                        {{ option}}
                    </option>
                </select>
            </div>  
        </form>

        <table sortable="true" ng-table="nocafeUserstable"  class="table table-bordered table-hover table-responsive">
            <tr ng-repeat="row in $data">
                <td title="'#'"> {{ $index + 1}} </td>
                ​​
                ​​ <td title="'First Name'" filter="{ FullName: 'text'}" sortable="'FullName'">
                    <span ng-bind="row.FullName">  </span>
                </td>
                <td title="'ID'" ng-bind="row.IDNumber" filter="{ IDNumber: 'text'}" sortable="'IDNumber'">
                </td>
                <td title="'Sex'" filter="{ Sex: 'text'}" sortable="'Sex'">
                    <span ng-bind="row.Sex">  </span>
                </td>


                <td title="'Department'" ng-bind="row.dept_name" filter="{ dept_name: 'text'}" sortable="'dept_name'">
                </td>
                <td title="'Status'"  filter="{ Status: 'text'}" sortable="'Status'">
                    <span ng-bind="row.Status" class="bg-success text-white" ng-if="row.Status === 'Cafe'" >   </span> 
                    <span ng-bind="row.Status" class="bg-danger text-gray-100" ng-if="row.Status === 'None-Cafe'" >   </span> 
                </td>
                <td title="'Photo'">
                    <img ng-src="<?php echo base_url() . "/resources/" ?>{{row.photo}}" width="100" height="80"/ alt="No Photo">
                </td>

                <td width="10%"> 

                    <button class="btn btn-danger btn-sm" ng-if="row.Status === 'None-Cafe'" ng-click="changeToCafeUser(row)">  Allow Cafe service </button> 
                    <button class="btn btn-success btn-sm"  ng-if="row.Status === 'Cafe'" ng-click="changeToNoneCafeUser(row)">  Deny  Cafe service </button> 
                </td>
            </tr>
        </table>  

    </div>  
</div>




