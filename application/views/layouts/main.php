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
        <!-- -->
        <!--<link href="<?php //echo base_url()               ?>resources/bsframework/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">-->

        <!-- Custom styles for this template-->
        <link href="<?php echo base_url() ?>resources/bsframework/css/sb-admin-2.min.css" rel="stylesheet"/>
        <!-- Custom styles for this page -->
        <link href="<?php echo base_url() ?>resources/bsframework/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>resources/b_datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YYN53F1LN0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-YYN53F1LN0');
</script>
    </head>

    <body id="page-top">
<script>
      // Replace with your view ID.
      var VIEW_ID = 'G-VPJXVK6Z09';
    
      // Query the API and print the results to the page.
      function queryReports() {
        gapi.client.request({
          path: '/v4/reports:batchGet',
          root: 'https://analyticsreporting.googleapis.com/',
          method: 'POST',
          body: {
            reportRequests: [
              {
                viewId: VIEW_ID,
                dateRanges: [
                  {
                    startDate: '7daysAgo',
                    endDate: 'today'
                  }
                ],
                metrics: [
                  {
                    expression: 'ga:sessions'
                  }
                ]
              }
            ]
          }
        }).then(displayResults, console.error.bind(console));
      }
    
      function displayResults(response) {
        var formattedJson = JSON.stringify(response.result, null, 2);
        document.getElementById('query-output').value = formattedJson;
      }
    </script>
    
    <!-- Load the JavaScript API client and Sign-in library. -->
    <script src="https://apis.google.com/js/client:platform.js"></script>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav  bg-gradient-success sidebar sidebar-dark accordion  " id="accordionSidebar">
                <?php if (!isset($role)) { ?>
                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                        <!--        <div class="sidebar-brand-icon rotate-n-15">
                                  <i class="fas fa-user"></i>
                                </div>-->

                        <div class="sidebar-brand-text mx-3">OCS</div>
                    </a>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">

                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('welcome/index'); ?>">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider">

                    <?php
                    $ci = &get_instance();
                    $ci->load->model("Menus_Model");
                    $menus = $ci->Menus_Model->get_granted_menus();
                    foreach ($menus as $row) {
//                        action_menu_group
                        ?>
                    
                        <li class="nav-item">

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $row['menu_id'] ?>" aria-expanded="true" aria-controls="collapse<?php echo $row['menu_id'] ?>">

                                <i class="<?php echo $row['icon'] ?>"></i> <span> <?php echo $row["label"] ?></span>
                            </a>
                            <div  id="collapse<?php echo $row['menu_id'] ?>" class="collapse" aria-labelledby="heading<?php echo $row['menu_id'] ?>" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <?php
                                    foreach ($row["submenu"] as $subrow) {
                                        $name = $subrow['action_name'];
                                      $url = str_replace(".", "/", $name);
                                  
                                        ?>
                                        <a class="collapse-item" href="<?php echo site_url($url); ?>"><i class="<?php echo $subrow['action_icon'] ?>"></i>   <?php echo $subrow['action_label'] ?></a>
                                    <?php } ?>

                                </div>  

                            </div>

                        </li>
                    <?php } ?>

                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>
                <?php } ?>
            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-2  static-top shadow" style="padding-top: 0px; padding-bottom: 0px;">

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1 text-center">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    One Card System
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">

                                </div>
                            </li>
                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter"> 3</span>
                                </a>
                      
                            </li>

                            <!-- Nav Item - Messages -->


                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                        <?php
                                        $user = $this->ion_auth->user()->row();
                                        if (!empty($user)) {
                                            echo $user->first_name . " " . $user->last_name;
                                        }
                                        ?>

                                    </span>
                                    <img class="img-profile rounded-circle" src="<?php echo base_url() ?>resources/imgs/admin_128.png">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?php echo site_url('auth/profile') ?>">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="<?php echo site_url('auth/change_password') ?>">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Change password
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"  href="<?php echo site_url("auth/logout") ?>"  data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>

                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid p-0">

                        <!--<Page Heading--> 

                        <?php
                        if (isset($_view)) {
                            $this->load->view($_view);
                        }
                        ?>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Woldia University</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"> </i>
          
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!--<script src="<?php // echo base_url()               ?>resources/bsframework/vendor/bootstrap/js/bootstrap.min.js"></script>-->

        <!-- Core plugin JavaScript-->
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="<?php echo base_url() ?>resources/bsframework/js/sb-admin-2.min.js"></script>
        <script src="<?php echo base_url() ?>resources/js/angular.min.js"></script>
        <script src="<?php echo base_url() ?>resources/js/ng-table.js"></script>

        <script src="<?php echo base_url() ?>resources/js/myjs.js"></script>
        <script src="<?php echo base_url() ?>resources/js/myangular.js"></script>
        <script src="<?php echo base_url() ?>resources/js/proctor_ajs.js" type="text/javascript"></script>
        <link href="<?php echo base_url() ?>resources/js/ng-table.css" rel="stylesheet" type="text/css"/>


        <!-- Page level plugins -->
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url() ?>resources/bsframework/vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url() ?>resources/b_datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>resources/js/my_datepicker.js" type="text/javascript"></script>
    </body>

</html>
