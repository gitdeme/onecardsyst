<div class="card shadow mb-4">
 
      
            <div class="card-header py-1 pt-0 row">
              
             
            <h6 class="m-0 font-weight-bold text-primary">
                Stop cafe Service/ Start cafe Service 
            </h6>
        

            </div>
    
            <div class="card-body text-center">
                
                <?php
                if(isset($message)){
                    
                  echo "<h1> $message  </h1>"  ;
                 //   echo "<h1> $number_of_row_affected  </h1>"  ;
                  
                    
                }
                
                
                ?>
                
                <a onclick="return assure_action()" href="<?php  echo site_url("ManageStudent/stop_or_permit_cafeteria_service?restart=true")?>"> <i class="fa fa-power-off fa-4x" style="color: red">   Restart Service </i> </a>
                
<!--                <table width="100%" class="table table-striped table-sm " id="dataTable" cellpadding="0" >
                    <thead>  <tr>
                        <th>#</th>						
                        <th> Action Name</th>
                        <th>Description</th>
                        <th  style="white-space: nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                        
                        
                        
                    </tbody>
                </table>-->

            </div>
      
  
</div>


