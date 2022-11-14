<?php

class Action_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * Get position by activityID
     */

    function get_action($actionID) {
        return $this->db->get_where('useractivities', array('activityID' => $actionID))->row_array();
    }
     	
     function get_group_role($group_role_id) {
        return $this->db->get_where('group_permission', array('userRoleID' => $group_role_id))->row_array();
    }
    function get_menus(){
    return    $this->db->get("menus")->result_array();
    }



    /*
     * Get all positions
     */

    function get_all_actions() {
        $this->db->order_by('activityID', 'desc');
        return $this->db->get('useractivities')->result_array();
    }

    /*
     * function to add new position
     */

    function add_action($params) {
        $this->db->insert('useractivities', $params);
        return $this->db->insert_id();
    }

    function add_permission($params) {
        $this->db->insert('group_permission', $params);
        return $this->db->insert_id();
    }

    function permission_exist($actionID, $group_id) {
        return $this->db->get_where('group_permission', array('activity_id' => $actionID, 'group_id' => $group_id))->result_array();
    }
    
    function get_all_permited_actions_for_group($group_id) {
        $where = array("isDeleted" => 0, "group_id"=>$group_id);
        $this->db->select("activity_id");
        $this->db->from("group_permission");
        //SELECT `userRoleID`, `activity_id`, `group_id`, `createdOn`, `createdBy`, `isDeleted` FROM `group_permission` WHERE 1
       // $this->db->join("groups g", "g.id=gp.group_id");
        //SELECT `id`, `name`, `description` FROM `groups` WHERE 1
       // $this->db->join("useractivities ua", "ua.activityID=gp.activity_id","left");
        //SELECT `activityID`, `action_name`, `action_description`, `isDeleted` FROM `useractivities` WHERE 1       
        $this->db->where($where);
       // $this->db->order_by('gp.group_id', 'desc');
        return $this->db->get()->result_array();
    }
    function get_all_permited_actions() {
        $where = array("gp.isDeleted" => 0);
        $this->db->select("`gp.*,g.*,ua.* ");
        $this->db->from("group_permission gp");
        //SELECT `userRoleID`, `activity_id`, `group_id`, `createdOn`, `createdBy`, `isDeleted` FROM `group_permission` WHERE 1
        $this->db->join("groups g", "g.id=gp.group_id");
        //SELECT `id`, `name`, `description` FROM `groups` WHERE 1
        $this->db->join("useractivities ua", "ua.activityID=gp.activity_id");
        //SELECT `activityID`, `action_name`, `action_description`, `isDeleted` FROM `useractivities` WHERE 1       
        $this->db->where($where);
        $this->db->order_by('gp.group_id', 'desc');
        return $this->db->get()->result_array();
    }
        function get_group_permission($group_id) {
        $where = array("gp.isDeleted" => 0, 'gp.activity_id'=>$group_id);
        $this->db->select("`gp.*, g.*, ua.*");
        $this->db->from("group_permission gp");
        //SELECT `userRoleID`, `activity_id`, `group_id`, `createdOn`, `createdBy`, `isDeleted` FROM `group_permission` WHERE 1
        $this->db->join("groups g", "g.id=gp.group_id");
        //SELECT `id`, `name`, `description` FROM `groups` WHERE 1
        $this->db->join("useractivities ua", "ua.activityID=gp.activity_id");
        //SELECT `activityID`, `action_name`, `action_description`, `isDeleted` FROM `useractivities` WHERE 1       
        $this->db->where($where);
        $this->db->order_by('gp.group_id', 'desc');
        return $this->db->get()->result_array();
    }

    /*
     * function to update action
     */

    function update_action($actionID, $params) {
        $this->db->where('activityID', $actionID);
        return $this->db->update('useractivities', $params);
    }

    /*
     * function to delete action
     */
 function delete_previous_permission($where) {
        return $this->db->delete('group_permission', $where);
    }
    function delete_action($actionID) {
        return $this->db->delete('useractivities', array('activityID' => $actionID));
    }
    function  delete_permission($permission_id){
      return $this->db->delete('group_permission', array('userRoleID' => $permission_id));   
    }

}
