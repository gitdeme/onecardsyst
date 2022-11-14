<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            here you can select the your photo from your mobile or computer  then click on <b style="color: red">Start Upload photo</b> button</h6>
    </div>
    <div class="card-body">

        <form class="form" name="uploadformphoto" method="post" action="send_your_photo" enctype="multipart/form-data">
            <div class="row">
            <div class="col-xs-6">
                <div class="row" >
                    <div class="col-sm-12">
                        <h2>
                           3X4 photo size 
                        </h2>
                        <div class="form-group">
                            <input type="file" required="" name="yourphoto" id="yourphoto" accept="image/jpeg, image/png"  class="btn btn-primary" />
                        </div>
                    </div>               
                </div>
             
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" >
                            <input class="btn btn-success " type="submit" name="upload_your_photo" id="photoupload" value="Start Upload photo" />     
                        </div>   
                    </div>               

                </div>
            </div>
            
            <div class="col-sm-6">
                <?php 
                $photopath="";
                if(isset($user_info)) 
                {
                    $photopath= $user_info["photo"] ;
                
                }
                ?>
                <img class="img-fluid img-responsive text-info" src="<?php echo base_url().$photopath  ?>" Alt="Your photo will be displayed here if uploaded successfully" width="300" height="350"/>
                                
            </div>
            </div>
        </form>
        <?php
        if (isset($upload_status)) {
            if (count($upload_status) == 0) {
                echo "<div class='bg-success text-white'> <h2>Photo uploaded  successfully </h2></div>";
            }
            ?>        
            <table class="table text-danger">
                <?php foreach ($upload_status as $row) { ?>
                    <tr>
                        <td> <?php echo $row['file_name'] ?> </td>   
                        <td><?php echo $row['error'] ?>    </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>


