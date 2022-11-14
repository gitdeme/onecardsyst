<?php

class Calenders_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_meal_feeding_time($meal_type) {
        return $this->db->get_where('meal_time', array('meal_type' => $meal_type))->row_array();
    }

    function get_all_calenders() {
        $where = array("is_deleted" => 0);
        $this->db->select("d.dept_name, c.*");
        $this->db->from("calenders c");
        $this->db->join("departments d", "d.dept_code=c.dept_id");
        $this->db->join("faculities f", "f.faculity_code=d.faculity_code");
        // $this->db->where($where);
        $this->db->order_by("f.faculity_name ASC, d.dept_name ASC, c.stud_year ASC ");
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

    function get_all_departments() {
        $where = array("is_deleted" => 0);
        $this->db->select("*");
        $this->db->from("departments");
        $this->db->where($where);
        $this->db->order_by('dept_name', 'ASC');
        return $this->db->get()->result_array();
    }

    /*
     * function to add new 
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
