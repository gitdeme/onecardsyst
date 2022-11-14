<div class="row" ng-app="csa" ng-controller="macc">

    <div class="col-md-12 bg-white pl-3 pr-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Generate Student ID Card</h6>
        </div>
        <form name="listform" id="form1" method="post">
            <div class="card-body">
                
                
         
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">                   
                        <label for="faculity"> <span translate>Faculty </span> <span class="text-danger">*</span></label>
                        <select ng-model="card.faculty_code"  name="faculty_code" id="faculity"  required  class="form-control" ng-change="getDepartments()">
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
                             <option value="0"> All </option>
                            <option ng-repeat="option in departments" value="{{option.dept_code}}">
                                {{ option.dept_name}}
                            </option>
                        </select>
                    </div> 

                </div>
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
                <div class="col-md-3">                   
                    <label for="admissionType"> <span translate>Admission Type </span> <span class="text-danger">*</span></label>
                    <select name="admissionType" id="band" ng-model="card.admissionType" required
                            class="form-control" ng-change="">
                        <option value="">Select  Admission Type </option>
                        <option ng-repeat="option in programs" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>

                </div>  
            </div>
            <div class="row ">

                <div class="col-md-2">                   
                    <label for="acyear"> <span translate>Academic Year  E.C.</span> <span class="text-danger">*</span></label>
                    <select name="acyear" id="band" ng-model="card.acyear" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Year E.C.</option>
                        <option ng-repeat="option in years" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>

                </div>  
                <div class="col-md-2">                   
                    <label for="program"> <span translate>Program</span> <span class="text-danger">*</span></label>
                    <select name="program" id="band" ng-model="card.program" required
                            class="form-control">
                        <option value="">Select  Program</option>
                        <option value="Degree"> Degree  </option>                      
                        <option value="Masters"> Masters  </option> 
                        <option value="PHD"> PHD  </option> 
                    </select>

                </div>  
                  <div class="col-md-2">                   
                    <label for="section"> <span translate>Section</span> <span class="text-danger">*</span></label>
                    <select name="section" id="section" ng-model="card.section"  class="form-control">
                        <option selected value=""> All </option>                       
                        <option ng-repeat="n in range" value="{{n}}">
                            {{ n}}
                        </option>                    
                    </select>
                </div> 

                <div class="col-md-6 text-center p-3">
                    <button class="btn btn-success pull-right" ng-click="getStudentsByCrieteria(1)"> Search  </button>
                    <a ng-show="card.acyear" class="btn btn-success btn-sm" href="<?php echo site_url("IDCard/print_id_card") ?>?faculty_code={{card.faculty_code}} && dept_code={{card.dept_code}} && program={{card.program}}&&band={{ card.band}}&&acyear={{card.acyear}} && print_id=true && admissionType={{card.admissionType}} && section={{card.section}}"> Generate ID Card Front </a>
                    <a ng-show="card.acyear" class="btn btn-success btn-sm"  href="<?php echo site_url("IDCard/print_id_card") ?>?faculty_code={{card.faculty_code}}&&dept_code={{card.dept_code}} && program={{card.program}}&&band={{ card.band}}&&acyear={{card.acyear}} && print_id=true  && admissionType={{card.admissionType}} && section={{card.section}} && print_back_side=12345qwertr"> Generate ID Card Back</a>
                    <button class="btn btn-danger btn-sm" ng-click="___delete_imported_student()">  Delete All</button>
                </div>  
            </div>


            <div class="col-lg-12">
                <table sortable="true" ng-table="studentsTable"  class="table table-bordered table-hover" filter="true">
                    <tr ng-repeat="row in $data">
                        <td title="'#'"> {{ $index + 1}} </td>
                        ​​
                        ​​ <td title="'Full Name'" filter="{FullName:'text'}" sortable="'FullName'">
                            <span ng-bind="row.FullName">  </span>
                        </td>
                        <td title="'Sex'" filter="{ Sex: 'text'}" sortable="'Sex'">
                            <span ng-bind="row.Sex">  </span>
                        </td>
                         <td title="'Department'" filter="{ dept_name: 'text'}" sortable="'dept_name'">
                            <span ng-bind="row.dept_name">  </span>
                        </td>
                        <td title="'Year'" ng-bind="row.Year" filter="{ Year: 'text'}" sortable="'Year'">
                        </td>
                        <td title="'ID'" ng-bind="row.IDNumber" filter="{ IDNumber: 'text'}" sortable="'IDNumber'">
                        </td>               

                        <td style="white-space: nowrap"> 
                            <a class="btn btn-success btn-sm" href="<?php echo site_url("IDCard/print_id_card") ?>?id_number={{row.IDNumber}} && print_id=true"> <i  class="fa fa-print fa-2a"></i> </a><span class="text-gray-100"> ......</span> 
                                
                            <button class="btn btn-danger btn-sm" id="" ng-click="delete_student(row)"> <i  class="fa fa-trash fa-2a"></i> </button>
                        </td>
                    </tr>
                </table>    
            </div>
            </div>
        </form>
          
    </div>

</div>
<?php



