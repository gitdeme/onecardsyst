<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cafeteria_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student($barcode) {
        $where = array("st.bcID" => $barcode, "st.is_active" => "Active");
        $this->db->select("st.FullName,st.Program, st.IDNumber,st.Sex,st.Year,dept.dept_name, st.dept_code, st.photo, ci.Id_Number,ci.CafeNumber, ci.Status, ci.Shift_Cafe");
        $this->db->from("students st");
        $this->db->join("cafeinfo ci", "ci.IDNumber=st.bcID", 'left');
        $this->db->join("departments dept", "dept.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    function get_special_permission($barcode, $today) {

        $where = array("ex.barcode" => $barcode, "ex.lastDate" => "<=" . $today);
        $this->db->select("ex.barcode, ex.ID, ex.lastDate, ex.permissionStatus, ci.IDNumber,st.Sex, st.photo, ci.Id_Number,  ci.CafeNumber, ci.Status, ci.Shift_Cafe");
        $this->db->from("cafeinfo ci");
        $this->db->join("students st", "st.bcID=ci.IDNumber");
        $this->db->join("exceptionalstudent ex", "st.bcID=ex.barcode");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    function get_meal_card($barcode, $today) {
        $where = array("IDNumber" => $barcode, "Date" => $today);
        $this->db->select("*");
        $this->db->from("mealcard");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    function save_meal_card($params) {
        $this->db->insert('mealcard', $params);
        return $this->db->insert_id();
    }

    function update_meal_card($where, $params) {
        $this->db->where($where);
        return $this->db->update('mealcard', $params);
    }

    function computeDateDifference($start, $end) {
        $start_ = date_create($start);
        $end_ = date_create($end);
        $interval = date_diff($end_, $start_, FALSE);
        return $number_of_date = $interval->format("%a");
    }
    function get_student_by_id($where){         
        $this->db->select("st.FullName,st.Program, st.IDNumber as Id_Number,st.Sex,st.Year,dept.dept_name, st.dept_code, st.photo");
        $this->db->from("students st");      
        $this->db->join("departments dept", "dept.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

}

?>