<div class="card shadow mb-4" ng-app="csa" ng-controller="macc">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Import students</h6>
    </div>
    <div class="card-body">
        <form name="listform" id="form1" method="post" enctype="multipart/form-data" action="import_students" >
            <div class="row" >  <div class="col-md-12 aler alert-info"> 
                    <?php
                                    
                    if (isset($message)) {
                        echo $message;                                             
                    $al=  array_count_values($id_list);
                    $index=0;
                        foreach($al as $r=>$v ){
                            if($v>1){
                                $index++;
                              //  echo $r."==>".$x."<br/>";
                               echo "<p class='text-danger'>$index .The student with  ID number $r found $v times</p>";
                            }
                        }
                        
                        
                    }
                    ?>
                    <?php
                    if (isset($columns)) {
                        foreach ($columns as $value) {
                            echo "<h3 class='text-danger'>" . $value . " <h3> ";
                        }
                    }
                    ?>
                </div> 

            </div>

            <div class="form-group row">  
                <!--                <div class="col-lg-3"> 
                                    <label for="faculity"> <span translate>Faculty </span> <span class="text-danger">*</span></label>
                                    <select name="faculity" id="faculity" ng-model="facModel.faculity_code" required
                                            class="form-control" ng-change="getDepartments()">
                                        <option value="">Select  Faculty</option>
                                        <option ng-repeat="option in faculities" value="{{option.faculity_code}}">
                                            {{ option.faculity_name}}
                                        </option>
                                    </select>
                                </div> -->


                <!--                <div class="col-md-4"> 
                                    <label  for="stream_code"> Stream <span class="text-danger">*</span> </label>
                                    <select name="stream_code" id="stream_code" ng-model="facModel.stream_code" required      class="form-control">
                                        <option value="0" >None</option>
                                        <option ng-repeat="option in streams" value="{{option.stream_ID}}">
                                            {{ option.stream_name}}
                                        </option>
                                    </select>
                
                                </div>-->


            </div>


            <div class="form-group row">
                <!--   <div class="col-lg-3"> 
   
                       <label  for="admission"> Admission Type <span class="text-danger">*</span> </label>
                       <select name="admission" id="dept_code" ng-model="facModel.admission" required"
                               class="form-control">
                           <option value="">Select admission</option>
   
                           <option value="Degree">   First Degree</option>
                           <option value="Masters">  Masters</option>
                           <option value="PHD">   PHD</option>
                           </option>
                       </select>
   
   
   
                   </div> 
                   <div class="col-lg-3">
                       <label for="program"> <span translate>program </span> <span class="text-danger"> * </span></label>
                       <select name="program" id="program" ng-model="facModel.program" required     class="form-control" ng-change="">
                           <option value="">Select  Program</option>
                           <option ng-repeat="option in programs" value="{{option}}">
                               {{ option}}
                           </option>
                       </select>  
                   </div>-->
                <div class="col-md-2">
                    <label for="acyear"> <span translate>Academic Year  E.C.</span> <span class="text-danger">*</span></label>
                    <select name="acyear" id="acyear" ng-model="facModel.acyear" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Year E.C.</option>
                        <option ng-repeat="option in years" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>


                </div>
                <div class="col-md-2">
                    <label for="acsemester"> <span translate>Academic Semester </span> <span class="text-danger">*</span></label>
                    <select name="acsemester" id="acsemester" ng-model="facModel.acsemester" required
                            class="form-control" ng-change="">
                        <option value="">Select  Academic Semester</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="Summer">Summer</option>
                    </select>  

                </div>

            </div>
                <!--<div class="col-md-2">                   
                    <label for="band"> <span translate>Band </span> <span class="text-danger">*</span></label>
                    <select name="band" id="band" ng-model="facModel.band" required
                            class="form-control" ng-change="">
                        <option value="">Select  Band</option>
                        <option ng-repeat="option in bands" value="{{option.band_id}}">
                            {{ option.band_name}}
                        </option>
                    </select>
                </div> 
            </div>-->

                <div class="form-group row">
                    <div class="col-md-6">
                         <label class="well-sm">Select Excel file that contain student list. the excel format looks like    </label>  
                        <input class="btn btn-primary"  type="file" name="upload_file" id="fileToUpload2"/>                        
                    </div>
                    <div class="col-md-6" style="visibility: hidden" id="uploadprogressbar">
                         <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                        
                    </div>

                </div>
              
                <div class="form-group row">
                    <div class="col-md-12 text-center ">
                        <button  class="btn btn-success btn-lg" type="submit" name="uploadstudentbutton" id="uploadstudentbutton" value="Start Upload"  onclick="uploadFileStudentList();"> <i class="fa fa-upload">  </i> Start Upload</button> 
                        <input class="btn btn-danger btn-lg" type="reset" name="form_reset" value="clear form"/>

                    </div>
                </div>
        </form>

    </div>
</div>

