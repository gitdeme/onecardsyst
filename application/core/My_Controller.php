<?php

class SupperCtr extends CI_Controller {

    var $user;
    var $today;
    var $date_time;
    var $current_time;
    var $roles = [];
    var $granted_menus = [];

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Africa/Addis_Ababa');
        $this->today = date("Y-m-d");
        $this->date_time = date("Y-m-d h:i:s");
        $this->current_time = date("h:i:s");
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $this->load->model("Menus_model");
            //  $granted_menus= $this->Menus_model->get_granted_menus();
            $ug = [0];
            $this->user = $this->ion_auth->user()->row();
            $this->db->select('group_id');
            $roles = $this->db->get_where('users_groups', array('user_id' => $this->user->id))->result_array();
            foreach ($roles as $row) {
                array_push($ug, $row['group_id']);
                //  array_push($this->roles,$row['']);
            }
            $class = $this->uri->segment(1, 0);
            $action = $this->uri->segment(2, 0);

            //can access controller and method
            $action_name = $class . '.' . $action;
//           print_r($ug);
//          exit();
            // echo $action_name ;
            //  echo    $admin= $this->ion_auth->is_admin();
            // exit();

            if ($action_name != '0.0') {
                $this->db->select('action_name,activity_id ,group_id');
                $this->db->from('useractivities ua');
                //`activityID`, `action_name`, `action_description`, `isDeleted`
                $this->db->join('group_permission gp', 'gp.activity_id=ua.activityID');
                //SELECT `userRoleID`, `activity_id`, `group_id`, `createdOn`, `createdBy`, `isDeleted` FROM `` WHERE 1
                $this->db->where_in('gp.group_id', $ug);
                $this->db->where('ua.action_name', $action_name);
                $results = $this->db->get()->result_array();
                if (count($results) < 1) {
                    redirect('errors/index');
                    exit();
                }
            }


            $this->roles = $ug;
        }
    }

}
