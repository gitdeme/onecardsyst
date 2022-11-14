
<div class="row p-1">
    <div class="col-lg-12 bg-white" ng-app="csa" ng-controller="macc" >
        <div class="row badge-info"><div class="col-md-12"> Allow/Permit Student to Gate into the campus /deny student not to enter into the university </div>  </div>
        <form name="manageForm" id="form1" method="post">
            <div class="form-group row">         
                <div  class="col-lg-3" >                   
                    <label for="faculity"> <span translate>Faculty </span> <span class="text-danger">*</span></label>
                    <select name="faculity" id="faculity" ng-model="student.faculity_code" required
                            class="form-control" ng-change="getDepartments()">
                        <option value="">Select  Faculty</option>
                        <option ng-repeat="option in faculities" value="{{option.faculity_code}}">
                            {{ option.faculity_name}}
                        </option>
                    </select>
                </div> 

                <div class="col-lg-3">
                    <label for="department"> <span> Department   </span> </label>
                    <select name="department" id="department" ng-model="student.dept_code" required
                            class="form-control">
                        <option value="">Select department</option>
                          <option value="0">All</option>
                        <option ng-repeat="option in departments" value="{{option.dept_code}}">
                            {{ option.dept_name}}
                        </option>
                    </select>
                </div> 

                            

                <div class="col-lg-2">                   
                    <label for="band"> <span translate> Year </span> <span class="text-danger">*</span></label>
                    <select name="band" id="band" ng-model="student.band" required
                            class="form-control" ng-change="">
                        <option value="">Select  Band</option>
                         <option value="0">All</option>
                        <option ng-repeat="option in bands" value="{{option.band_id}}">
                            {{ option.band_name}}
                        </option>
                    </select>
                </div> 



                <div class="col-lg-2">   

                    <label for="program"> <span translate>program </span> <span class="text-danger">*</span></label>
                    <select name="program" id="band" ng-model="student.program" required
                            class="form-control" ng-change="">
                        <option value="">Select  Program</option>
                        <option ng-repeat="option in programs" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>


                </div> 
                <div class="col-lg-2">                   
                    <label for="year"> <span translate>Year</span> <span class="text-danger">*</span></label>
                    <select name="year" id="band" ng-model="student.acyear" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Year E.C.</option>
                        <option ng-repeat="option in years" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>
                </div> 



            </div>

            <div class="form-group text-center">
                <button class="btn btn-success pull-right" style="margin-right: 10px" ng-click="getActiveStudents()"> Search  </button>

            </div>
            <div class="col-lg-12">

                <table  sortable="true" ng-table="activeStudentstable"  class="table table-bordered table-hover table-responsive table-sm" filter="true">
                    <tr ng-repeat="row in $data">
                        <td title="'#'"> {{ $index + 1}} </td>                        ​​
                        ​​ <td title="'Full Name'" filter="{FullName:'text'}" sortable="'FullName'">
                            <span ng-bind="row.FullName">  </span>
                        </td>
                        <td title="'ID'" ng-bind="row.IDNumber" filter="{ IDNumber: 'text'}" sortable="'IDNumber'">
                        </td>   
                        <td title="'Sex'"  sortable="'Sex'">
                            <span ng-bind="row.Sex">  </span>
                        </td>
                        <td title="'Year'" ng-bind="row.Year"  sortable="'Year'">
                        </td>                                      
                        <td title="'Status'"   sortable="'is_active'">
                            <label class="badge-success"  ng-if="row.is_active_gate === '1'" > Active  </label>  
                            <label class="badge-danger" ng-if="row.is_active_gate === '0'" > Inactive  </label>  
                        </td>  
                        <td  title="Operation"> 
                            <a href="#" class="btn btn-outline-success btn-sm"> 
                                <i  style="color: red"  class="fa fa-unlock" ng-click="deactivateStudents(row)" ng-if="row.is_active_gate === '1'"> Deny</i>
                                <i  style="color: green"  class="fa fa-lock" ng-click="activateStudents(row)" ng-if="row.is_active_gate === '0'"> Permit</i>
                            </a>  
                            <a href="" class="btn btn-outline-warning btn-sm"> Wanted Person  </a>

                        </td>
                    </tr>
                </table>   


            </div>
            <div class="row">
               
                <div class="col-md-12 text-center" id="showButtons" style="visibility: hidden">
                    <button class="btn btn-success pull-right "  ng-click="activateAllStudents()" >   Permit All   </button>
                    <button class="btn btn-danger pull-right" style="margin-right: 10px" ng-click="deactivateAllStudents()"> Deny All  </button>
                </div>
            </div>
        </form>

    </div>
</div>



