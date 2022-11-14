<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?php echo base_url() ?>resources/icons/wdulogo.png" />
        <title>One Card System</title>

        <!-- Custom fonts for this template-->
        <link href="<?php echo base_url() ?>resources/bsframework/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template-->
        <link href="<?php echo base_url() ?>resources/bsframework/css/sb-admin-2.min.css" rel="stylesheet">

    </head>

    <body class="bg-gradient-light">

        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9 mt-lg-5 p-1">
                    <?php
                    $this->load->view("auth/login");
                    ?> 
                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo base_url() ?>resources/bsframework/js/sb-admin-2.min.js"></script>

    </body>

</html>
