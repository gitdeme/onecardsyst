


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            here you can select the student photo from your directory that are saved by the student id.</h6>
    </div>
    <div class="card-body">
       
        <form class="form-inline" name="uploadformphoto" method="post" action="import_photo" enctype="multipart/form-data">
            
            <div class="form-group">
                <input type="file" name="files[]" id="fileToUpload3"  class="btn btn-primary" multiple="multiple" />
            </div>
            <div class="form-group">
                <input class="btn btn-success " type="submit" name="upload_photo" id="photoupload" value="Upload photo" />     
            </div>                                                                                                                                                                                                                                  
        </form>
        <?php if(isset($upload_status)) 
        {    
            if(count($upload_status)==0){
              echo "<div class='bg-success text-white'> <h2>Operation Done successfully </h2></div>"  ;
            }
            ?>        
        <table class="table text-danger">
          <?php foreach ($upload_status as $row){ ?>
            <tr>
                <td> <?php echo $row['file_name'] ?> </td>   
                <td><?php echo $row['error'] ?>    </td>
            </tr>
          <?php } ?>
        </table>
        <?php }?>
    </div>
</div>


