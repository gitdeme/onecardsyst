<?php

class Errors extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        //  $data['actions'] = $this->Action_model->get_all_actions();
        $data['_view'] = 'error/error';
        $data["heading"] = "Permission Error";
        $data["message"] = "You dont have Permission to do this activity";
        $this->load->view('errors/html/error_general.php', $data);
        // // $this->load->view('layouts/main', $data);
    }

}




