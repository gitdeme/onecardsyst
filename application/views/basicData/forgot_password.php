
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>resources/icon/amhara.png"  />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Land Management</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/bootstrap.min.css'); ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/font-awesome.min.css'); ?>">
        <!-- Ionicons -->
        <!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
        <!-- Datetimepicker -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/bootstrap-datetimepicker.min.css'); ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/AdminLTE.min.css'); ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/_all-skins.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('resources/DataTables/datatables.min.css'); ?>">
        <!--amharic datepicker-->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/datepicker/css/jquery.calendars.picker.css') ?>"> 
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/select2/css/select2.min.css') ?>"> 

        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/mystyle.css') ?>"> 


    </head>

    <body>
        <div class="wrapper">

            <!-- Left side column. contains the logo and sidebar -->

            <!-- Content Wrapper. Contains page content -->
            
            <div >
                <!-- Main content -->
                <section class="content">
                    <div class="well-sm" style="background-color: white" >
                        
                       <h1><?php echo lang('forgot_password_heading'); ?></h1>
                    <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>

                    <div id="infoMessage"><?php echo $message; ?></div>

                    <?php echo form_open("auth/forgot_password"); ?>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            
                      <label for="identity"><?php echo (($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label)); ?></label> 
                        <?php echo form_input($identity); ?>
                        </div> 
                    </div>

                    <div class="col-md-2 form-group">
                        <br/>
                        <?php echo form_submit('submit', lang('forgot_password_submit_btn'),"class='btn btn-primary'"); ?></p>
                    </div>
                    <?php echo form_close(); ?>   
                        
                    </div>
                    


                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <strong>Powered By <a href="#">Woldia University</a> </strong>
            </footer>



        </div>
        <!-- ./wrapper -->

        <!-- jQuery 2.2.3 -->
        <script src="<?php echo site_url('resources/js/jquery-2.2.3.min.js'); ?>"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo site_url('resources/js/bootstrap.min.js'); ?>"></script>

        <!-- AdminLTE App -->
        <script src="<?php echo site_url('resources/js/app.min.js'); ?>"></script>
        <!-- AdminLTE for demo purposes -->
        <!--<script src="<?php //echo site_url('resources/js/demo.js');             ?>"></script>-->
        <!-- DatePicker -->

        <script src="<?php echo site_url('resources/js/global.js'); ?>"></script>
        <!--<script src="<?php //echo site_url('resources/js/jquery-3.3.1.min.js');             ?>"></script>-->
        <!--<script src="<?php //echo site_url('resources/js/smart-table.min.js');           ?>"></script>-->




    </body>
</html>


