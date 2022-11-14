<?php

class Gate extends SupperCtr {

    public $link;
    public $leaveToday = 0;
    public $returnToday = 0;
    public $returnTomorrow = 0;
    public $returnAfterTomorrow = 0;
    public $withFamily = 0, $date, $time;

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Africa/Addis_Ababa');
        $this->date = date("Y-m-d");
        $this->time = date("h:i:s");
        $this->load->model("Gate_model");
    }

    public function view_students_with_family() {
        
    }

    function give_exit_permission() {

        if ($this->input->post('bcID')) {
            $type = INPUT_POST;
            $barcodeID = filter_input($type, 'bcID');
            $departureDate = filter_input($type, 'depd');
            $turnbackDate = filter_input($type, 'tbd');
            $reason = filter_input($type, 'reason');
            $result = array("Message" => "", "ok" => true);
            //$date = date("Y-m-d");
            $r = $this->Gate_model->get_student_by_id($barcodeID);
            $barcodeID = $r[0]['bcID'];
            $query_insert = "INSERT INTO `studentbreak`(`bcID`, `departureDate`, `returnDate`, `reason`) VALUES ('$barcodeID','$departureDate', '$turnbackDate', '$reason')";
            if ($barcodeID != null && $departureDate != null && $turnbackDate != null) {
                if ($this->db->query($query_insert)) {
                    $result["Message"] = "Successfully Completed";
                } else {
                    echo "error occured";
                    $result["ok"] = FALSE;
                }
            } else {
                $result["Message"] = "Pleaase enter valid data";
                $result["ok"] = FALSE;
            }
            echo json_encode($result);
            exit();
        } else {
            
            $data['_view'] = 'gate/exit_permission';
            $this->load->view('layouts/main', $data);
        }
    }

    public function delete_record() {
        $id = filter_input(INPUT_POST, 'auto_ID');
        $result = array('Message' => "", "ok" => true);
        $query = "delete  FROM studentbreak where auto_ID='$id'";
        if ($this->db->query($query)) {
            $result["Message"] = "Successfully Deleted";
        } else {
            $result["Message"] = "Unable to delete the record";
            $result["ok"] = FALSE;
        }

        json_encode($result);
    }

   private function getStudentsOnBreak() {
        $query = "SELECT * FROM studentbreak sb inner join cafeinfo ci on ci.IDNumber=sb.bcID  inner join students st on st.IDNumber=ci.Id_Number  WHERE sb.is_returned=0 order by sb.auto_ID desc";
        $result = $this->db->query($query)->result_array();
        echo json_encode($result);
    }

    private function compute_prediction() {
        $today = $this->today;
        //stringto datetime
        $today2 = strtotime($today);
        $tomorrow = strtotime($today . '+ 1 day');
        $aftertomorrow = strtotime($today . '+ 2 days');
        $query = "SELECT * FROM studentbreak WHERE is_returned=0 and returnDate>='$today'";
        $result = $this->db->query($query)->result_array();
        foreach ($result as $row) {
            $departureDate = strtotime($row['departureDate']);
            $returnDate = strtotime($row['returnDate']);
            if ($departureDate == $today2) {
                $this->leaveToday++;
            }
            if ($returnDate == $today2) {
                $this->returnToday++;
            } else if ($returnDate == $tomorrow) {
                $this->returnTomorrow++;
            } elseif ($returnDate == $aftertomorrow) {
                $this->returnAfterTomorrow++;
            } else {
                $this->withFamily++;
            }
        }
    }

    function get_students_info_on_leave() {
//        $today = date("Y-m-d");
//        //stringto datetime
//        $today2 = strtotime($today);
//        $tomorrow = strtotime($today . '+ 1 day');
//        $aftertomorrow = strtotime($today . '+ 2 days');
//        $query = "SELECT * FROM studentbreak WHERE is_returned=0 and returnDate>='$today'";
//        $result = $this->db->query($query)->result_array();
//        foreach ($result as $row) {
//            $departureDate = strtotime($row['departureDate']);
//            $returnDate = strtotime($row['returnDate']);
//            if ($departureDate == $today2) {
//                $this->leaveToday++;
//            }
//            if ($returnDate == $today2) {
//                $this->returnToday++;
//            } else if ($returnDate == $tomorrow) {
//                $this->returnTomorrow++;
//            } elseif ($returnDate == $aftertomorrow) {
//                $this->returnAfterTomorrow++;
//            } else {
//                $this->withFamily++;
//            }
//        }
        $this->compute_prediction();
        // $result = $this->db->query("select * from students where is_active='Active' ")->result_array();
        $summery['leave_today'] = $this->leaveToday;
        $summery['return_today'] = $this->returnToday;
        $summery['return_tomorrow'] = $this->returnTomorrow;
        $summery['return_aftertomorrow'] = $this->returnAfterTomorrow;
        $summery['with_family'] = $this->withFamily;

        $students = $this->db->query("SELECT * FROM cafeinfo ci INNER JOIN students st on st.IDNUmber=ci.Id_Number where st.is_active='Active' and ci.Status='Cafe' ")->result_array();
        $summery['cafe_students'] = count($students);
        $non_cafe_students = $this->db->query("SELECT * FROM cafeinfo ci INNER JOIN students st on st.IDNumber=ci.Id_Number where st.is_active='Active' and ci.Status='Non-Cafe' ")->result_array();

        $summery['non_cafe_students'] = count($non_cafe_students);

        $summery["available_cafe_clients"] = $summery['cafe_students'] - $this->returnTomorrow - $this->returnAfterTomorrow - $this->withFamily;

        $data['summery'] = $summery;
        $data['_view'] = 'reports/future_stastistics';
        $this->load->view('layouts/main', $data);
    }

    function get_absent_students() {
        $query = "SELECT abs.auto_ID, st.FullName,st.Sex ,st.IDNumber, dep.dept_name,  abs.bcID, abs.exitDate, abs.entryDate, abs.noAbsent, abs.status FROM absent_student abs INNER JOIN students st on st.bcID=abs.bcID INNER JOIN departments dep on dep.dept_code=st.dept_code  WHERE  abs.status=1";
        $result = $this->db->query($query)->result_array();
        $data['absentees'] = $result;
        $data['_view'] = 'gate/absentees';
        $this->load->view('layouts/main', $data);
        //  echo json_encode($result);
    }

  function   authorize_student_on_library_gate(){
         if (!empty($this->input->post("barcode_liberary"))) {
            $barcodeID = $this->input->post("barcode_liberary");
            $where = array("st.bcID" => $barcodeID); //"st.is_active_gate" =>1
            $result_array = $this->Gate_model->get_active_student_by_barcode($where);
            if (count($result_array) > 0) {
                $row = $result_array[0];
                $stud_status = $row ['is_active_gate'];
                $response['full_name'] = ucwords( strtolower( $row["FullName"]));
                $response['ID'] = $row["IDNumber"];
                $response['sex'] = $row["Sex"];
                $response['year'] = $row["Year"];
                //   $dept_name=$row["dept_code"];
                $response['department'] = $row["dept_name"];
                if (empty($row['photo'])) {
                    $response['photopath'] = base_url() . 'resources/icons/student3_128.png';
                } else {
                    $response['photopath'] = base_url() . 'resources/' . $row['photo'];
                }

                if ($stud_status == 1) {
                    $response['message'] = "አንኳን ደህና መጡ!!";
                    $response['status'] = 1;
                } else if ($stud_status == 0) {
                    $response['message'] = " መግባት አልተፈቀደለትም/ የተባረረ ተማሪ";
                    $response['status'] = 0;
                } else if ($stud_status == 2) {
                    $response['message'] = "ተፈላጊ ሰው!! መግባት አልተፈቀደለትም";
                    $response['status'] = 0;
                }
            } else {
                $response["message"] = "የማይታወቅ መታወቂያ/ መግባት አልተፈቀደለትም";
                $response['photopath'] = base_url() . 'resources/icons/error.png';
                $response["status"] = 0;
            }

            echo json_encode($response);
            return;
        }else{
         $data['_view'] = "gate/authorize_on_library_gate";
          $this->load->view("layouts/main", $data); 
        }
  }
    
    
    //only validate whether  the student is valid or not to enter into compound  
    public function authorize_on_campus_gate() {

        if (!empty($this->input->post("bcID_campus_gate"))) {
            $barcodeID = $this->input->post("bcID_campus_gate");
            $where = array("st.bcID" => $barcodeID); //"st.is_active_gate" =>1
            $result_array = $this->Gate_model->get_active_student_by_barcode($where);
            if (count($result_array) > 0) {
                $row = $result_array[0];
                $stud_status = $row ['is_active_gate'];
                $response['full_name'] = ucwords( strtolower( $row["FullName"]));
                $response['ID'] = $row["IDNumber"];
                $response['sex'] = $row["Sex"];
                $response['year'] = $row["Year"];
                //   $dept_name=$row["dept_code"];
                $response['department'] = $row["dept_name"];
                if (empty($row['photo'])) {
                    $response['photopath'] = base_url() . 'resources/icons/student3_128.png';
                } else {
                    $response['photopath'] = base_url() . 'resources/' . $row['photo'];
                }

                if ($stud_status == 1) {
                    $response['message'] = "አንኳን ደህና መጡ!!";
                    $response['status'] = 1;
                } else if ($stud_status == 0) {
                    $response['message'] = " መግባት አልተፈቀደለትም/ የተባረረ ተማሪ";
                    $response['status'] = 0;
                } else if ($stud_status == 2) {
                    $response['message'] = "ተፈላጊ ሰው!! መግባት አልተፈቀደለትም";
                    $response['status'] = 0;
                }
            } else {
                $response["message"] = "የማይታወቅ መታወቂያ/ መግባት አልተፈቀደለትም";
                $response['photopath'] = base_url() . 'resources/icons/error.png';
                $response["status"] = 0;
            }

            echo json_encode($response);
            return;
        } else {
            $data['_view'] = 'gate/gate_autherization';
            $this->load->view('layouts/main', $data);
        }
    }
//old one
    public function validate_id_on_gate() {
        $message = "";
        $type = INPUT_POST;

        $date = date("Y-m-d");
        $time = date("h:i:s");
        $imagename = "";
        $status = 1;
        $response = [];
        //$data = [];
        if (!empty($this->input->post('bcID'))) {
            $barcodeID = filter_input($type, 'bcID');
            $recordType = filter_input($type, 'gateType');
            $q_result = [];
            $where = array("st.bcID" => $barcodeID, "st.is_active" => "Active");
            //  $row = $this->db->query("SELECT *  FROM  students st INNER JOIN departments dept on st.dept_code=dept.dept_code  WHERE st.bcID='$barcodeID' and st.is_active='Active'")->result_array();
            $result_array = $this->Gate_model->get_active_student_by_barcode($where);
            if (count($result_array) > 0) {
                $row = $result_array[0];
                $response['full_name'] = $row["FullName"];
                $response['ID'] = $row["IDNumber"];
                $response['sex'] = $row["Sex"];
                $response['year'] = $row["Year"];
                //   $dept_name=$row["dept_code"];
                $response['department'] = $row["dept_name"];
                if (empty($row['photo'])) {
                    $response['photopath'] = base_url() . 'resources/imgs/african_128.png';
                } else {
                    $response['photopath'] = base_url() . 'resources/' . $row['photo'];
                }

                if ($recordType == "out") {
                    $result = $this->check_out($barcodeID);
                    if ($result) {
                        $response['message'] = "You can go out";
                        $response['status'] = 1;
                    }
                } else if ($recordType == "in") {
                    $result = $this->check_in($barcodeID);
                    $response['message'] = $result['message'];
                    $response['status'] = $result['status'];
                }
            } else {
                $response["message"] = "Unknown Student";
                $response['photopath'] = base_url() . 'resources/icons/error.png';
                $response["status"] = 0;
            }

            echo json_encode($response);
            return;
        } else {
            $data['_view'] = 'gate/gate_autherization';
            $this->load->view('layouts/main', $data);
        }
    }

    private function check_in($barcodeID = "ZOTHNL88XRG5T") {
        $response = [];
        $where = array("bcID" => $barcodeID, "status" => 0);
        $result_array = $this->Gate_model->get_student_attendance($where);
        if (count($result_array) > 0) {
            $result = $result_array[0];
            $auto_id = $result['auto_id'];
            $affected_row = $this->db->query("UPDATE `attendance` SET  entryDate='$this->date',entryTime='$this->time', status=1 WHERE auto_id='$auto_id'");
            $extdate = $result['exitDate'];
            $interval = $this->compute_date_difference($this->date, $extdate);
            $allowed = $this->is_on_vacation($barcodeID);
            if ($interval > 0 && $allowed == FALSE) {
                $status = 1; //active or decision is not made                   
                $query_insert = $this->db->query("INSERT INTO `absent_student`(`bcID`, `exitDate`, `entryDate`, `noAbsent`, `status`) VALUES ('$barcodeID','$extdate','$this->date',$interval,'$status')");
                $response['status'] = 1;
                $response['message'] = "Welcome! \n Get in";
            } else if ($interval > 0 && $allowed == true) {
                $message = "welcome!!\n ይግቡ \n you have waited $interval days";
                $response['message'] = $message;
                $response['status'] = 1;
            } else {
                $response['message'] = "Welcome! \n Get in";
                $response['status'] = 1;
            }
        } else {
            $response["message"] = "መታወቂያዎን ሳያሳዩ ነዉ የወጡት";
            $response['status'] = 0;
        }
        // print_r($response);

        return $response;
    }

    private function check_out($barcodeID) {
        //$query_result = $this->db->query("SELECT * from attendance WHERE bcID='$barcodeID' and status=0")->row_array();
        $where = array("bcID" => $barcodeID, "status" => 0);
        $result = $this->Gate_model->get_student_attendance($where);

        if (count($result) == 0) {
            $status = $this->db->query("INSERT INTO `attendance`(`bcID`, `exitDate`, `exitTime`, `status`) VALUES ('$barcodeID','$this->date','$this->time',0)");
            return $status;
        } else {
            return true;
        }
    }

    private function compute_date_difference($leav, $entry) {
        $leaveTime = date_create($leav);
        $returnTime = date_create($entry);
        $interval = date_diff($returnTime, $leaveTime, FALSE);
        return $number_of_date = $interval->format("%a");
    }

    private function is_on_vacation($bcID) {
        $result = $this->db->query("SELECT *  FROM `studentbreak` WHERE bcID='$bcID' and is_returned=0")->result_array();

        if (count($result) > 0) {
            $id = $result[0]['auto_ID'];
            $query = $this->db->query("UPDATE studentbreak set is_returned=1 WHERE  auto_ID=$id");

            return true;
        }
        return FALSE;
//auto_ID`, `bcID`, `departureDate`, `returnDate`, `reason`, `is_returned`
    }

    public function get_student_info_by_id_or_barcode() {
        if (filter_input(INPUT_POST, 'get_student_by_id')) {
            $id = filter_input(INPUT_POST, 'ID');
            $result = $this->Gate_model->get_student_by_id($id);
            echo json_encode($result);
        }
    }

}
