
<div class="row" ng-app="csa" ng-controller="macc">

    <div class="col-md-12 bg-white pl-3 pr-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Manage Students for cafe service  </h6>
        </div>
        <form name="listform" id="form1" method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">                   
                        <label for="faculity"> <span translate>Faculty </span> <span class="text-danger">*</span></label>
                        <select name="faculty_code" id="faculty_code" ng-model="card.faculty_code" required                 class="form-control" ng-change="getDepartments()">
                            <option value="">Select  Faculty</option>
                            <option ng-repeat="option in faculities" value="{{option.faculity_code}}">
                                {{ option.faculity_name}}
                            </option>
                        </select>

                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group">

                        <label for="department">  Department  <span class="text-danger"> *</span> </label>
                        <select name="department" id="department" ng-model="card.dept_code" required
                                class="form-control">
                            <option value="">Select department</option>
                            <option value="0">All</option>
                            <option ng-repeat="option in departments" value="{{option.dept_code}}">
                                {{ option.dept_name}}
                            </option>
                        </select>
                    </div> 

                </div>
                
                
                   <div class="col-md-3">                   
                    <label for="Program"> <span translate>Program.</span> <span class="text-danger">*</span></label>
                    <select name="Program" id="band" ng-model="card.program" required
                            class="form-control" ng-change="">
                        <option value="">Select  Program</option>
                        <option value="Degree"> Degree  </option>                      
                        <option value="Masters"> Masters  </option> 
                        <option value="PHD"> PHD  </option> 


                    </select>


                </div> 
             

                <div class="col-md-3">                   
                    <label for="admissionType"> <span translate>Admission Type </span> <span class="text-danger">*</span></label>
                    <select name="admissionType" id="band" ng-model="card.admissionType" required
                            class="form-control" ng-change="">
                        <option value="">Select  Admission Type </option>
                        <option value="Regular">  Regular  </option>
                        <option value="Summer">  Summer  </option>

                    </select>

                </div>  
            </div>
            <div class="row ">

                <div class="col-md-3"> 
                    <label for="band"> <span translate>Year </span> <span class="text-danger">*</span></label>
                    <select name="band" id="band" ng-model="card.band" required
                            class="form-control" ng-change="">
                        <option value="">Select  Year</option>
                         <option value="0"> All </option>
                        <option ng-repeat="option in bands" value="{{option.band_id}}">
                            {{ option.band_name}}
                        </option>
                    </select>

                </div>
                
                
                
                <div class="col-md-2">                   
                    <label for="acyear"> <span translate>Academic Year  E.C.</span> <span class="text-danger">*</span></label>
                    <select name="acyear" id="band" ng-model="card.acyear" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Year E.C.</option>
                        <?php
                        $gy = date("Y");
                        for ($y = 2012; $y < ($gy - 6); $y++) {
                            echo "<option value='$y'>" . $y . "</option>";
                        }
                        ?>


                    </select>


                </div>
                <div class="col-md-2">                   
                    <label for="semester"> <span translate>Academic semester</span> <span class="text-danger">*</span></label>
                    <select name="semester" id="semester" ng-model="card.semester" required
                            class="form-control">
                        <option value="">Select  Semester</option>
                        <option value="I"> I  </option>                      
                        <option value="II"> II  </option> 
                        <option value="Summer"> Summer  </option> 
                    </select>

                </div>  

                <div class="form-group text-center">
                    <p>&nbsp;</p>
                    <button class="btn btn-success pull-right" style="margin-right: 10px" ng-click="getActiveStudents()"> Search  </button>

                </div>
            </div>


            <div class="col-lg-12">

                <table  sortable="true" ng-table="activeStudentstable" width="100%"  class="table table-bordered table-hover table-responsive table-sm" filter="true">
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
                          <td title="'Department'" ng-bind="row.dept_name" filter="{dept_name: 'text'}"  sortable="'dept_name'">
                        </td>  
                        <td title="'Status'"   sortable="'is_active'">
                            <label class="badge-success"  ng-if="row.is_active === 'Active'" > Active  </label>  
                            <label class="badge-danger" ng-if="row.is_active=== 'Inactive'" > Inactive  </label>  
                        </td>  
                        <td  title="Operation"> 
                            <a href="#" class="btn btn-outline-success btn-sm"> 
                                <i  style="color: red"  class="fa fa-unlock" ng-click="deactivateStudents(row)" ng-if="row.is_active_gate === 'Active'"> Deny</i>
                                <i  style="color: green"  class="fa fa-lock" ng-click="activateStudents(row)" ng-if="row.is_active_gate === 'Inactive'"> Permit</i>
                            </a>  
                            <a href="" class="btn btn-outline-warning btn-sm"> Wanted Person  </a>

                        </td>
                    </tr>
                </table>   


            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-success pull-right "  ng-click="activateAllStudents()" >   Permit All   </button>
                    <button class="btn btn-danger pull-right" style="margin-right: 10px" ng-click="deactivateAllStudents()"> Deny All  </button>
                </div>
            </div>
        </form>
    </div>

</div>
<?php


