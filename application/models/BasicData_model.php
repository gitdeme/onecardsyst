<?php

class BasicData_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_meal_feeding_time($meal_type) {
        return $this->db->get_where('meal_time', array('meal_type' => $meal_type))->row_array();
    }

  
    function get_all_faculties() {
        $where = array("is_deleted" => 0);
        $this->db->select("*");
        $this->db->from("faculities");
        $this->db->where($where);
        $this->db->order_by('faculity_name', 'ASC');
        return $this->db->get()->result_array();
    }

    function get_department($dept_id) {
        return $this->db->get_where('departments', array('dept_code' => $dept_id))->row_array();
    }

    function get_faculty($faculty_code) {
        return $this->db->get_where('faculities', array('faculity_code' => $faculty_code))->row_array();
    }

    function get_department_by_name($dept_name) {
        $where = array('dept_name' => $dept_name);
        $this->db->select("d.*,f.faculity_name");
        $this->db->from("departments d");
        $this->db->join("faculities f", "f.faculity_code=d.faculity_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    function get_faculty_by_name($faculty_name) {
        return $this->db->get_where('faculities', array('faculity_name' => $faculty_name))->result_array();
    }

    function get_stream($stream_id) {
        return $this->db->get_where('streams', array('stream_ID' => $stream_id))->result_array();
    }

    function get_all_departments($orderby=null) {
        if(empty($orderby)){
             $orderby=  'dept_name ASC'  ;
        }
        $where = array("is_deleted" => 0);
        $this->db->select("*");
        $this->db->from("departments");
        $this->db->where($where);
        $this->db->order_by($orderby);
        return $this->db->get()->result_array();
    }

    function get_all_streams() {
        $where = array("st.is_deleted" => 0);
        $this->db->select("*");
        $this->db->from("streams st");
        $this->db->join("departments d", "d.dept_code=st.dept_code");
        $this->db->where($where);
        $this->db->order_by('d.dept_name', 'ASC');
        return $this->db->get()->result_array();
    }

    function get_all_meal_types() {
        $this->db->select("*");
        $this->db->from("meal_time");
        return $this->db->get()->result_array();
    }


    /*
     * function to add new kebele
     */

    function save($table, $params) {
        $this->db->insert($table, $params);
        return $this->db->insert_id();
    }

    function update($table, $params, $where) {
        $this->db->where($where);
        return $this->db->update($table, $params);
    }

    function delete($table, $where) {
        return $this->db->delete($table, $where);
    }

}
