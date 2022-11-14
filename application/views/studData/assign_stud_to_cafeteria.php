<div class="card shadow mb-4" ng-app="csa" ng-controller="macc">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
           ተማሪዎችን በየካፍቴሪያው መመደብ/ Assign students to Cafeteria</h6>
    </div>
    <div class="card-body">
        <div id="notificationBox" class="bg-success text-gray-100" ng-show="success"> Operation Done Successfully  </div>
        <div id="notificationBox" class="bg-danger text-gray-200" ng-show="error">Something went wrong  </div>
        <div class="form-group row">
            <div  class="col-md-3">                   
                <label for="year"> <span translate>Academic Year</span> <span class="text-danger">*</span></label>
                <select name="year" id="band" ng-model="assign.acyear" required ng-change="getStudentsGroupedbyDepartment()"
                        class="form-control" ng-change="">
                    <option value="">Select  Academic Year E.C.</option>
                    <option ng-repeat="option in years" value="{{option}}">
                        {{ option}}
                    </option>
                </select>
            </div> 
              <div class="col-md-3">
                    <label for="acsemester"> <span translate>Academic Semester </span> <span class="text-danger">*</span></label>
                    <select name="acsemester" id="acsemester" ng-model="assign.acsemester" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Semester</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="Summer">Summer</option>
                    </select>  

                </div>
            <div class="col-md-3">                   
                <label for="admissionType"> <span translate>Admission Type </span> <span class="text-danger">*</span></label>
                <select name="admissionType" id="band" ng-model="assign.admissionType" required ng-change="getStudentsGroupedbyDepartment()"
                        class="form-control" ng-change="">
                    <option value="">Select  Admission Type</option>
<!--                    <option ng-repeat="option in programs" value="{{option}}">
                        {{ option}}
                    </option>-->
                    <option value="Regular">  Regular</option>
                    <option value="Summer">  Summer</option>
                </select>
            </div>
             <div class="col-md-3">                   
                <label for="program"> <span translate>Program</span> <span class="text-danger">*</span></label>
                <select name="program" id="band" ng-model="assign.program" required ng-change="getStudentsGroupedbyDepartment()"
                        class="form-control" ng-change="">
                    <option value="">Select  Program</option>
<!--                    <option ng-repeat="option in programs" value="{{option}}">
                        {{ option}}
                    </option>-->
                    <option value="Degree">  First Degree</option>
                    <option value="Masters">  Second Degree</option>
                      <option value="PHD">  Third Degree</option>
                </select>
            </div>
   
        </div>

        <table sortable="true" ng-table="deptStudntstable"  class="table table-bordered table-hover table-responsive">
            <tr ng-repeat="row in $data">
                <td title="'#'"> {{ $index + 1}} </td>
                <td title="'Department'" ng-bind="row.dept_name" filter="{ dept_name: 'text'}" sortable="'dept_name'">
                </td>
                <td title="'Year'" ng-bind="row.Year" filter="{ Year: 'text'}" sortable="'Year'">
                </td>  
                <td ng-bind="row.numberofstudent" sortable="'numberofstudent'" title="'# of student'">
                    
                    
                </td>
                
                <td title="'Cafe'"   sortable="'CafeNumber'" class="text-primary">
                    <span ng-bind="row.CafeNumber" ng-if="row.CafeNumber !== 0">  </span>
                    <label ng-if="row.CafeNumber == null" class="text-danger"> Not Assigned </label>   
                </td>
                <td width="30%"  title="'Select cafe Number'"> 
                    <input type="radio" name="{{row.dept_code + row.Year}}" class="radio-inline"  ng-value="1" ng-model="row.CafeNumber"/>1(Senior Cafe) &nbsp; &nbsp;
                    <input type="radio" name="{{row.dept_code + row.Year}}" class="radio-inline" ng-value="2" ng-model="row.CafeNumber"/>2 (Fresh Cafe) &nbsp;&nbsp;
                    <!--<input type="radio" name="{{row.dept_code + row.Year}}" class="radio-inline" ng-value="3" ng-model="row.CafeNumber"/>3 &nbsp;&nbsp;-->
                 </td>
                <td> 
                    <button class="btn btn-primary btn-sm" ng-click="assignDeptsStudents(row)"> Save</button>
                </td>
            </tr>
        </table>    

<!--        <button class="btn btn-success" ng-click="assignDeptsStudents()">  Save Changes</button>-->
    </div>
</div>

