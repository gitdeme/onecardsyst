<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gate_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_active_student_by_barcode($where) {
        $this->db->where($where);
        $this->db->from("students st");
        $this->db->join("departments dept", "st.dept_code=dept.dept_code");
        return $this->db->get()->result_array();
    }

    function get_student_attendance($where) {
        $this->db->where($where);
        return $this->db->get("attendance")->result_array();
    }

    public function get_student_by_id($id) {
        $query = "SELECT * FROM `students` WHERE  IDNumber='$id' or bcID='$id'";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

}
