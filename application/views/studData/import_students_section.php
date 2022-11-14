<div class="card shadow mb-4" ng-app="csa" ng-controller="macc">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Import students</h6>
    </div>
    <div class="card-body">
        <form name="listform" id="form1" method="post" enctype="multipart/form-data" action="set_student_section" >
            <div class="row" >  <div class="col-md-12 aler alert-info"> 
                    <?php
                                    
                    if (isset($message)) {
                        echo $message;                                         
                
                        
                    }
                    ?>
                
                </div> 

            </div>

        

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
                        <button  class="btn btn-success btn-lg" type="submit" name="uploadstudentsection" id="uploadstudentbutton" value="Start Upload"  > <i class="fa fa-upload">  </i> Start Upload</button> 
                     
                    </div>
                </div>
        </form>

    </div>
</div>

