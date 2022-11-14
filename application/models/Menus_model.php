<?php

class Menus_model extends CI_Model {

    var $roles = [];

    function __construct() {
        parent::__construct();
        $ug = [0]; 
        
        if($this->ion_auth->logged_in()){
             $this->user = $this->ion_auth->user()->row();
              $this->db->select('group_id');
            $roles = $this->db->get_where('users_groups', array('user_id' => $this->user->id))->result_array();
            foreach ($roles as $row) {
                array_push($ug, $row['group_id']);
                //  array_push($this->roles,$row['']);
            }
            $this->roles = $ug;
             
        }
       
      
           
      
    }

    function get_granted_menus() {
        $granted_menu = [];
        $this->db->select("*");
        $this->db->from("menus");
        $this->db->order_by("menu_order");
        $menus = $this->db->get()->result_array();
        foreach ($menus as $row) {
            $this->db->select("activityID, action_name,action_description, action_icon, action_label,action_order");
            $this->db->from("useractivities a");
            $this->db->join("group_permission gp", "gp.activity_id=a.activityID");
            $this->db->order_by("a.action_order", "ASC");
            $this->db->where_in("gp.group_id", $this->roles);
            $this->db->where("a.action_menu_group", $row['menu_id']);
            $this->db->group_by("a.activityID");
            $submenu = $this->db->get()->result_array();
            if (count($submenu) != 0) {
                $row["submenu"] = $submenu;
                array_push($granted_menu, $row);
            }
        }
        return $granted_menu;
    }

}
