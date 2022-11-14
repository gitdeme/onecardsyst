<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageStudent_model extends CI_Model {

    function delete_imported_student($where) {
        $this->db->where($where);
        return $this->db->delete("students");
    }

    public function getCafeStudents($acyear = null, $program = null) {

        $where = array("st.acyear" => $acyear, "Admission" => $program, "st.is_active" => "Active");
        $this->db->select("ci.Roll_Number,ci.Status,ci.CafeNumber, st.photo, st.FullName,st.Sex ,st.IDNumber, st.dept_code, dep.dept_name");
        $this->db->from("cafeinfo ci");
        $this->db->join("students st", "st.bcID=ci.IDNumber");
        $this->db->join("departments dep", "dep.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }
    
 public function get_student_with_photo($where) {

       // $where = array("st.acyear" => $acyear, "Admission" => $program, "st.is_active" => "Active");
        $this->db->select("st.*,dep.dept_name ");
        $this->db->from("students st");        
        $this->db->join("departments dep", "dep.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    public function denyCafeService($SID) {

        $params = array("Status" => "None-Cafe");
        $this->db->where("Id_Number", $SID);
        $r = $this->db->update('cafeinfo', $params);
        if ($r) {
            $rows['message'] = "Operation Done Successfully";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed " . mysqli_error($this->link);
            $rows['status'] = "failed ";
        }
        return $rows;
    }

    public function save_cafe_service_change($data) {

        return $this->db->insert("cafe_audit", $data);
    }

    public function allowCafeService($SID) {
        $params = array("Status" => "Cafe");
        $this->db->where("Id_Number", $SID);
        $r = $this->db->update('cafeinfo', $params);
        if ($r) {
            $rows['message'] = "Operation Done Successfully";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed " . mysqli_error($this->link);
            $rows['status'] = "failed ";
        }
        return $rows;
    }

    public function get_streams($dept_code) {
        $result = $this->db->query("SELECT  * FROM  streams  where dept_code='$dept_code' and is_deleted=0")->result_array();
        return $result;
    }

    public function get_departments($faculity_code) {
        $result = $this->db->query("SELECT  * FROM  departments  where faculity_code='$faculity_code' and is_deleted=0")->result_array();
        $rows = array();
        foreach ($result as $r) {
            $r["streams"] = $this->get_streams($r['dept_code']);
            $rows[] = $r;
        }
        return $rows;
    }

    public function get_study_faculities() {

        $result = $this->db->query("SELECT  * FROM  faculities  where is_deleted=0")->result_array();
        $rows = array();
        foreach ($result as $r) {
            $r["depts"] = $this->get_departments($r['faculity_code']);
            $rows[] = $r;
        }
        return $rows;
    }

    public function get_studentsForIDDesign($where) {
//and ( program='$program' and status='active')";
        $this->db->select("st.*, dept.dept_name, dept.dept_code, dept.faculity_code");
        $this->db->from("students st");
        $this->db->join("departments dept", "dept.dept_code=st.dept_code ");
        $this->db->where($where);
        $this->db->order_by('dept_name ASC');
        $this->db->order_by('Year ASC');
        $this->db->order_by('section_number ASC');
        $this->db->order_by('FullName ASC');
        return $this->db->get()->result_array();

        //  return $query = $result = $this->db->query("SELECT st.*, dp.dept_name FROM students st INNER JOIN departments dp on"
        //        . " dp.dept_code=st.dept_code  WHERE  (st.dept_code='$dept_code' and Program='$admissionType' and Year='$band' and acyear='$acyear' and st.admission='$program' and st.is_active='Active') order by st.FullName")->result_array();
    }

    public function getStudentsGroupedByDepartment($where) {
//and ( program='$program' and status='active')";
        $this->db->select("dep.dept_name,st.acyear,st.Program, st.Year,st.admission, st.semester, dep.dept_code, ci.CafeNumber, count(dep.dept_code) as numberofstudent");
        $this->db->from("students st");
        $this->db->join("cafeinfo ci", 'ci.IDNumber=st.bcID', 'left');
        $this->db->join("departments dep", "dep.dept_code=st.dept_code");
        $this->db->where($where);
        $this->db->group_by(array("dep.dept_name", "st.Year"));
        $this->db->order_by("dep.dept_name", "ASC");
        $this->db->order_by("st.Year", "ASC");
        return $this->db->get()->result_array();
    }

    public function getByID($id) {
//and ( program='$program' and status='active')";
        $result = $this->db->query("SELECT * FROM `cafeinfo` WHERE   Id_Number='$id'")->result_array();
        if (count($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function assignStudentsToCafteria($id, $cafeNumber, $barcode, $isexist, $acyear = null, $semseter = null) {
        $rows = array();
        $today = date("Y-m-d");
        if ($isexist) {
            $this->db->query("UPDATE cafeinfo SET CafeNumber='$cafeNumber',semester='$semseter', academic_year='$acyear', registration_date='$today'  WHERE Id_Number ='$id' ");
            $rows["message"] = "Operation done successfully";
            $rows['status'] = "ok";
        } else {
            $query2 = $this->db->query("INSERT INTO `cafeinfo`( `Id_Number`, `IDNumber`, `Status`, `registration_date`, `semester`, academic_year,`CafeNumber`, `Shift_Cafe`, `Photo`) VALUES ('$id','$barcode','Cafe','$today','$semseter','$acyear','$cafeNumber','0','')");

            if ($query2) {
                $rows["message"] = "Operation done successfully";
                $rows['status'] = "ok";
            } else {
                $rows["message"] = "error";
                $rows['status'] = "Failed";
            }
        }
        return $rows;
    }

    public function getStudentsByCriteria($where) {
        $this->db->select("*");
        $this->db->from("students st");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    public function get_student_by_ID_or_barcode($id) {
        return $result = $this->db->query("SELECT  FullName  FROM students  WHERE IDNumber='$id' or bcID='$id' ")->result_array();
    }

    public function get_student_by_ids($idlist) {
        $this->db->select("st.*,dept.dept_name");
        $this->db->from("students st");
        $this->db->join("departments dept", "dept.dept_code=st.dept_code");
        $this->db->where_in("IDNumber", $idlist);
        return $this->db->get()->result_array();
        //  return $query = $this->db->query("SELECT st.*, dept.dept_name FROM students st INNER JOIN departments dept on dept.dept_code=st.dept_code WHERE  IDNumber='$id'")->result_array();
    }

    private function is_exist($id, $pd) {
        $result = $this->db->query("SELECT * FROM exceptionalstudent where (barcode='$id' or ID='$id') and lastDate='$pd'")->result_array();
        if (count($result) > 0) {
            return true;
        }

        return FALSE;
    }

    function grant_special_permission($id, $pd, $status, $permiter, $createdon) {
        $result = $this->db->query("SELECT * FROM students where IDNumber='$id' or bcID='$id'")->result_array();
        $response = array("message" => "Operation Failed", "status" => 0);
        if ($this->is_exist($id, $pd)) {
            $response["message"] = "Already existed";
            $response["status"] = 0;
// echo json_encode($response);
            return $response;
        }

        if (count($result) > 0) {
            $bcid = $result[0]['bcID'];
            $id = $result[0]['IDNumber'];


            $result = date("Y-m-d h:i:s", strtotime($pd));
            $insert_Permission = $this->db->query("INSERT INTO exceptionalstudent VALUES( '','$bcid','$id' ,'$result','$status','$permiter','$createdon')");
            if ($insert_Permission) {
                $response["message"] = "Successfully done";
                $response["status"] = 1;
            } else {
                $response["message"] = "Operation failed !! ";
                $response["status"] = 0;
            }
        } else {
            $response["message"] = "Operation failed !! due to invalide id";
            $response["status"] = 0;
        }
        return $response;
    }

    public function get_student_by_id($id) {
        return $query = $this->db->query("SELECT st.*, dept.dept_name, st.dept_code FROM students st INNER JOIN departments dept on dept.dept_code=st.dept_code WHERE  IDNumber='$id'")->result_array();
    }

    public function is_id_exist($id) {
        $result = $this->db->query("SELECT IDNumber FROM `students` WHERE  IDNumber='$id'")->result_array();
        if (count($result) == 0) {
            return FALSE;
        }
        return TRUE;
    }

    public function get_students_to_Controll_gate($where) {
        //$this->dept_code, $this->program, $this->admissionType, $this->band, $this->acyear, $this->semseter);
        $this->db->select("st.*, dep.*");
        $this->db->from("students st");
        $this->db->join("departments dep", "dep.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();

        // return $query = $this->db->query("SELECT * FROM students where (dept_code='$dept_code' and Program='$program'  and admission='$admissionType'  and  Year='$band' and acYear='$acyear' and semester='$semester')")->result_array();
    }

    public function activate_student($id) {
        $query = $this->db->query("UPDATE `students` SET `is_active` = 'Active' WHERE `students`.`IDNumber` = '$id'");

        if ($query) {
            $rows['message'] = "Operation Done Successfully ";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed ";
            $rows['status'] = "failed ";
        }
        return $rows;
    }

    public function deactivate_student($id) {
        $query = $this->db->query("UPDATE `students` SET `is_active` = 'Inactive' WHERE `students`.`IDNumber` = '$id'");
        if ($query) {
            $rows['message'] = "Operation Done Successfully ";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed ";
            $rows['status'] = "failed ";
        }

        return $rows;
    }

    public function deactivate_all_students($where) {
        //   $query = $this->db->query("UPDATE `students` SET `is_active_gate` = '0' WHERE dept_code = '$dept_code'  and  Program='$program' and Year='$band' and acyear='$acyear'");
        $this->db->where($where);
        $query = $this->db->update("students", array("is_active" => "Inactive"));
        if ($query) {
            $rows['message'] = "Operation Done Successfully ";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed ";
            $rows['status'] = "failed ";
        }
        return $rows;
    }

    public function activate_all_students($where) {
        //  $query = $this->db->query("UPDATE `students` SET `is_active_gate` = '1' WHERE dept_code = '$dept_code'  and  Program='$program' and Year='$band'  and acyear='$acyear'");
        $this->db->where($where);
        $query = $this->db->update("students", array("is_active" => "Active"));
        $rows = [];
        if ($query) {
            $rows['message'] = "Operation Done Successfully";
            $rows['status'] = "ok";
        } else {
            $rows['message'] = "Operation Failed ";
            $rows['status'] = "failed ";
        }
        return $rows;
    }

    public function import_students($data) {
        return $this->db->insert("students", $data);
    }

    public function chek_barcode_uniqueness($barcode) {
        $this->db->select("bcID");
        $this->db->from("students");
        $this->db->where(array("bcID" => $barcode));
        $result = $this->db->get()->result_array();
        if (count($result) < 1) {
            return true;
        }
        return FALSE;
    }

    function make_students_in_active($update_data, $where) {
        $this->db->where($where);
        return $this->db->update('students', $update_data);
    }

    function update_student($update_data, $id) {
        $this->db->where('IDNumber', $id);
        return $this->db->update('students', $update_data);
    }
     function update_department($update_data, $dept_code) {
        $this->db->where('dept_code', $dept_code);
        return $this->db->update('departments', $update_data);
    }

    function get_special_permissions() {
        $where = array("permissionStatus" => 1);
        $this->db->select("st.Program, st.FullName,st.Sex ,st.IDNumber, st.dept_code, dep.dept_name, ex.*");
        $this->db->from("exceptionalstudent ex");
        $this->db->join("students st", "st.bcID=ex.barcode");
        $this->db->join("departments dep", "dep.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    function is_id_card_request_not_exist($where) {
        $result = $this->db->get_where("id_card_requests", $where)->result_array();
        if (count($result) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function save_id_card_request($data) {

        return $this->db->insert("id_card_requests", $data);
    }

    function update_id_card_request($update_data, $id) {
        $this->db->where('request_id', $id);
        return $this->db->update('id_card_requests', $update_data);
    }

    function get_id_card_requests($where) {
        $this->db->select("r.*, st.FullName,st.bcID, st.nationality, st.Sex, st.Year,st.photo, st.IDNumber, st.Program, st.admission,dept.dept_code, dept.dept_name");
        $this->db->from("id_card_requests  r");
        $this->db->join("students st", "st.IDNumber=r.student_id");
        $this->db->join("departments dept", "dept.dept_code=st.dept_code");
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

}
