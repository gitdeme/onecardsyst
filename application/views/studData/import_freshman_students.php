<div class="card shadow mb-4" ng-app="csa" ng-controller="macc">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Import freshman Students</h6>
    </div>
    <div class="card-body">
        <form name="listform" id="form1" method="post" enctype="multipart/form-data" action="import_freshman_students" >
            <div class="row" >  <div class="col-md-12 aler alert-info"> 
                    <?php
                    if(isset($message_file_type)){
                        echo $message_file_type+" "."records imported";
                    }
                    
                    if (isset($message)) {
                        echo $counter ." ";
                        echo $message;
                        $al = array_count_values($id_list);
                        $index = 0;
                        foreach ($al as $r => $v) {
                            if ($v > 1) {
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
                <div class="col-md-3">                   
                    <label for="program"> <span translate>Program</span> <span class="text-danger">*</span></label>
                    <select name="program" id="band" ng-model="card.program" required
                            class="form-control">
                        <option value="">Select  Program</option>
                        <option value="Degree"> Degree  </option>                      
                        <option value="Masters"> Masters  </option> 
                        <option value="PHD"> PHD  </option> 
                    </select>

                </div>  

                <div class="col-lg-3">
                    <label for="admission"> <span translate>Admission Type </span> <span class="text-danger"> * </span></label>
                    <select name="admission" id="admission" ng-model="card.admission" required     class="form-control" ng-change="">
                        <option value="">Select  admission</option>
                        <option ng-repeat="option in programs" value="{{option}}">
                            {{ option}}
                        </option>
                    </select>  
                </div>
                <div class="col-md-2">
                    <label for="acyear"> <span translate>Academic Year  E.C.</span> <span class="text-danger">*</span></label>
                    <select name="acyear" id="acyear" ng-model="card.acyear" required
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
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="well-sm">Select Excel file that contain student list. the excel format looks like    </label>  
                    <input class="btn btn-primary"  type="file" multiple name="upload_file[]" id="fileToUpload2"/>                        
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


