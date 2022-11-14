<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ManageStudent extends SupperCtr {

    var $link, $barcodeID, $departureDate, $turnbackDate, $reason, $auto_ID, $SID;
    var $dept, $program, $admissionType, $band, $acyear, $semester;
    var $today, $current_time, $date_time, $username;
    var $fn_ind, $mn_ind, $ln_ind, $sex_ind, $id_ind, $faculty_ind, $department_ind;
    var $admissionType_ind, $program_ind, $year_ind;
    var $faculty_code;
    var $wdu = '<label style="margin: 0px 0px 0px 0px; font-size: 14px;color:white" > <b>Woldia University</b>  <br/> Student ID Card</label> ';
    var $backinfo = "<label  style='font-size: 12px'>Woldia University, Ethiopia  </label> <br/>
                            <label> POBox:400</label> 
                            <label> Phone:0335400706</label>  <br/>                           
                            <label>Website: www.wldu.edu.et</Label>";
    var $IDcardBackside = '<table border="1" style="border-collapse: collapse; width: 96mm; height:49mm">
                             <tr> <th colspan="3" style="text-align:center"> Invalid unless renewed/በየሰሚስተሩ ካልታደሰ አያገለግልም </th> </tr> 
                            <tr> <th rowspan="2">Academic Year </th>
                            <th colspan="2" style="text-align:center;"> Renewal</th></tr>  
                            <tr style="height:30px">                                   
                                    <th> Semester I </th>  
                                    <th> Semester II  </th> 
                                </tr>
                                <tr>
                                    <td>20........E.C </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>20........E.C </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>20........E.C </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>20........E.C </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>20........E.C </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                            </table>';
    var $masasebiya = "<ol>ማሳሰቢያ:"
            . "<li>በዩኒቨርሲቲው በትምህርት ላይ እስካሉ ድረስ ካርዱ እንዳይለይዎት::</li>"
            . "<li>ካርዱ ቢጠፋ/ቢበላሽ አሳዉቆ በምትኩ ሌላ ማግኘት ይቻላል:: </li>"
            . "<li>ትምህርትዎን ሲጨርሱ/ሲያቋርጡ ካርዲን ማስረከብ አለብዎት::</li> </ol>";

    public function __construct() {
        parent::__construct();
// date_default_timezone_set('Africa/Addis_Ababa');
// $this->today = date("Y-m-d");
// $this->current_time = $this->current_time;
// $this->date_time = date("Y-m-d h:i:s");
        $this->load->model("ManageStudent_model");
        $this->load->model("BasicData_model");
        $this->username = $this->user->email;
    }

    function get_cafteria_client() {

        if (filter_input(INPUT_POST, "getcafestudents")) {
            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $result = $this->ManageStudent_model->getCafeStudents($this->acyear, $this->program);
            echo json_encode($result);
            exit();
        }
        $data['_view'] = "studData/administer_cafe_user";
        $this->load->view("layouts/main", $data);
    }

    function changetononecafeuser() {
        $this->SID = filter_input(INPUT_POST, 'IDNumber');
        if ($this->SID != null) {
            $result = $this->ManageStudent_model->denyCafeService($this->SID);
            if ($result) {
                $data = array("student_id" => $this->SID, "changed_to" => "None-Cafe", "action_performed_by" => $this->username, "action_performed_on" => $this->date_time);
                $this->ManageStudent_model->save_cafe_service_change($data);
            }
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function changetocafeuser() {
        $this->SID = filter_input(INPUT_POST, 'IDNumber');
        if ($this->SID != null) {
            $result = $this->ManageStudent_model->allowCafeService($this->SID);
            if ($result) {
                $data = array("student_id" => $this->SID, "changed_to" => "Cafe", "action_performed_by" => $this->username, "action_performed_on" => $this->date_time);
                $this->ManageStudent_model->save_cafe_service_change($data);
            }
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function import_photo() {
        if ($this->input->post("upload_photo")) {
            $photo_nums = count($_FILES['files']['name']);
            $name = $_FILES["files"]["name"];
// if ($name != '') {
// $check = 0;
// $invalid_photo = [];
            $unupload = [];
            $errors = [];
            for ($i = 0; $i < $photo_nums; $i++) {
// $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                $config['upload_path'] = 'resources/imageFiles';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size'] = 0;
                $config['max_width'] = 0;
                $config['max_height'] = 0;
                $config['overwrite'] = true;
                $file_name_id = $config['file_name'] = $_FILES['files']['name'][$i];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload("file")) {
                    array_push($errors, array('error' => $this->upload->display_errors(), 'file_name' => $file_name_id));
                } else {
                    $data_ = array('upload_data' => $this->upload->data());
                    $id = strtoupper($this->RemoveExtension($file_name_id));


                    $filepath = 'imageFiles/' . $file_name_id;
                    if (count($this->db->query("select * from students WHERE IDNumber='$id'")->result_array()) > 0) {
                        $result = $this->db->simple_query("UPDATE students SET photo='$filepath' WHERE IDNumber='$id'");
                    } else {
                        $error["error"] = "This ID is not found please check it again";
                        $error["file_name"] = $id;
                        array_push($errors, $error);
                        unlink("resources/" . $filepath);
                    }

//$this->load->view('upload_success', $data);
                }
//   print_r($errors);
                $data['upload_status'] = $errors;


                /*
                  if ($stl == 7) {
                  $temp = "";
                  for ($j = 0; $j < 7; $j++) {
                  $temp = $temp . $fileName[$j];
                  if ($j == 0) {
                  $temp = $temp . "/";
                  } else if ($j == 4) {
                  $temp = $temp . "/";
                  }
                  }
                  $fileName = strtoupper($temp);
                  }
                 */
            }
        }
        $data['_view'] = "studData/import_photo";
        $this->load->view("layouts/main", $data);
    }

    private function RemoveExtension($strName) {
        $ext = strrchr($strName, '.');
        if ($ext !== false) {
            $strName = substr($strName, 0, -strlen($ext));
        }
        return $strName;
    }

    function import_students() {
        if ($this->input->post("uploadstudentbutton")) {




            $duplicated = true;
            $number_of_student_imported = 0;
            $number_of_student_unimported = 0;
            $import_date = $this->today;
// $admission = filter_input(INPUT_POST, "admission");
            $stream = 0; // filter_input(INPUT_POST, "stream_code");
//  $band = filter_input(INPUT_POST, "band");
            $acyear = filter_input(INPUT_POST, "acyear");
            $semester = filter_input(INPUT_POST, "acsemester");
//$program = filter_input(INPUT_POST, "program");

            $createdBy = $this->username;
            $query_status = [];
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
                $data['message'] = "<h3 class='text-success'>operation done</h3>";
                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $h = 0;
                $dept_code = 0;
                $fac_id = 0;
                $id_list = array();
                $year = 0;
                $program = "";
                $admission = "";
                $faculty_name = "";
                $dept_name = "";
                $fac_id = 0;
                foreach ($sheetData as $row) {

                    if ($h == 9) {
                        $status = $this->get_cols_index($row);
                        if ($status === FALSE) {
                            $data['message'] = "<h1 class='text-danger'>Column name should be just like below. but the order doent matter.</h1>";
                            $data['columns'] = $row;
                            print_r($row);
                            break;
                        } else {
                            $h++;
                            continue;
                        }
                    }
                    if ($h < 9) {
                        $h++;
                        continue;
                    }

                    if (!empty($row[$this->faculty_ind]) && !empty($row[$this->fn_ind])) {
                        $faculty_name = $row[$this->faculty_ind];
                    }

                    if (!empty($row[$this->department_ind]) && !empty($row[$this->fn_ind])) {
                        $dept_name = $row[$this->department_ind];
                        $dept_info = $this->BasicData_model->get_department_by_name($dept_name);
                        if (count($dept_info) == 0) {
                            $faculty_info = $this->BasicData_model->get_faculty_by_name($faculty_name);
                            $fac_id = 0;
                            if (count($faculty_info) == 0) {
                                $fac_data = array("faculity_name" => $faculty_name, "is_deleted" => 0);
                                $fac_id = $this->BasicData_model->save("faculities", $fac_data);
                            } else {
                                $fac_id = $faculty_info[0]['faculity_code'];
                            }
                            $dept_data = array("dept_name" => $dept_name, "faculity_code" => $fac_id, "is_deleted" => 0);
                            $dept_code = $this->BasicData_model->save("departments", $dept_data);
                        } else {
                            $prev_fac_name = $dept_info[0]['faculity_name'];
                            $dept_code = $dept_info[0]["dept_code"];
                            if ($faculty_name != $prev_fac_name) {
                                $faculty_info = $this->BasicData_model->get_faculty_by_name($faculty_name);
                                $fac_id = 0;
                                if (count($faculty_info) == 0) {
                                    $fac_data = array("faculity_name" => $faculty_name, "is_deleted" => 0);
                                    $fac_id = $this->BasicData_model->save("faculities", $fac_data);
                                } else {
                                    $fac_id = $faculty_info[0]['faculity_code'];
                                }
                                $update_data = array("faculity_code" => $fac_id);
                                $this->ManageStudent_model->update_department($update_data, $dept_code);
                            }
                        }
                    }


                    if (!empty($row[$this->year_ind]) && !empty($row[$this->id_ind])) {
                        $year = $row[$this->year_ind];
                    }
                    if (!empty($row[$this->program_ind]) && !empty($row[$this->id_ind])) {
                        $program = $row[$this->program_ind];
                    }
                    if (!empty($row[$this->admissionType_ind]) && !empty($row[$this->id_ind])) {
                        $admission = $row[$this->admissionType_ind];
                    }
                    $name = $row[$this->fn_ind];
                    if (empty($name)) {
                        continue;
                    }

//echo "<p> $faculty_name ----   $dept_name---  $year   --- $program ---- $admission ---- $name </p>";
                    $h++;


                    $id = trim($row[$this->id_ind]);
                    if (!empty($id)) {
                        array_push($id_list, $id);
                        $exist = $this->ManageStudent_model->is_id_exist($id);
//  $faculty_name = $row[$this->faculty_ind];
//  $dept_name = $row[$this->department_ind];
                        if (!$exist) {
                            $barcode = $this->generate_string_barcode(13);
                            $unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                            while (!$unique) {
                                $barcode = $this->generate_string_barcode(13);
                                $is_unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                            }

                            $row_data = array(
                                "FullName" => $row[$this->fn_ind] . ' ' . $row[$this->mn_ind] . ' ' . $row[$this->ln_ind],
                                'Sex' => $row[$this->sex_ind],
                                'Year' => $year,
                                'IDNumber' => $id,
                                'bcID' => $barcode,
                                'entry_year' => NULL,
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'Program' => $program,
                                'admission' => $admission,
                                'photo' => "",
                                'is_active' => "Active",
                                'createDate' => $this->today,
                                'createdBy' => $this->username
                            );

                            $status = $this->ManageStudent_model->import_students($row_data);
                            if ($status) {
                                $number_of_student_imported++;
                            } else {
                                $number_of_student_unimported;
                            }
                        } else {

                            $update_data = array(
                                'Year' => $year,
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'is_active' => "Active",
                                'updatedOn' => $this->today,
                                'updatedBy' => $this->username);

                            $status = $this->ManageStudent_model->update_student($update_data, $id);
                            if ($status) {
                                $number_of_student_imported++;
                            } else {
                                $number_of_student_unimported++;
                            }
                        }
                    } else {
                        continue;
//  $data['message'] = "<h3 class='text-danger'> The Student ID culumn can not have empty value </h3>";
                    }
                }
            } else {
                $data['message'] = "<h3 class='text-success'> file type not existed </h3>";
            }
            $data['id_list'] = $id_list;
            $data['counter'] = $number_of_student_imported;
            $data['_view'] = "studData/import_students";
            $this->load->view("layouts/main", $data);
        } else {
            $data['_view'] = "studData/import_students";
            $this->load->view("layouts/main", $data);
        }
    }

    private function get_cols_index($colums) {
        if (is_array($colums)) {
            foreach ($colums as $key => $val) {
                $colums[$key] = trim($val);
            }
        }

        $this->fn_ind = array_search("First Name", $colums);
        $this->mn_ind = array_search("Father Name", $colums);
        $this->ln_ind = array_search("GFather Name", $colums);
        $this->admissionType_ind = array_search("Adm.Clas.", $colums);
        if (!$this->admissionType_ind) {
            $this->admissionType_ind = array_search("Admission ", $colums);
        }
        $this->faculty_ind = array_search("College", $colums);
        $this->department_ind = array_search("Stream", $colums);
        $this->sex_ind = array_search("Sex", $colums);
        $this->id_ind = array_search("Student ID", $colums);
        $this->year_ind = array_search("Year", $colums);
        $this->program_ind = array_search("Program", $colums);
        if ($this->faculty_ind === FALSE || $this->fn_ind === FALSE || $this->mn_ind === FALSE || $this->ln_ind === FALSE || $this->sex_ind === FALSE || $this->id_ind === FALSE || $this->year_ind === FALSE || $this->program_ind === FALSE) {
            return FALSE;
        } else {
            return true;
        }
    }

    private function generate_string_barcode($length) {
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $charsLength = strlen($characters) - 1;
        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $randnumber = mt_rand(0, $charsLength);
            $string = $string . $characters[$randnumber];
        }
        return $string;
    }

    function get_faculties() {

        $result = $this->ManageStudent_model->get_study_faculities();
//    $result= $op->get_departments("F01");
        echo json_encode($result);
    }

    private function get_student_to_prepare_IDCard() {

        $this->faculty_code = filter_input(INPUT_POST, "faculty_code");
        $this->dept_code = filter_input(INPUT_POST, "dept_code");
        $this->program = filter_input(INPUT_POST, "program");
        $this->band = filter_input(INPUT_POST, "band");
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        $this->admissionType = filter_input(INPUT_POST, 'admissionType');
        $where = array('st.is_active' => 'Active');
        if ($this->dept_code != null && $this->band != null && $this->admissionType != null && $this->acyear != null && $this->program != null) {
            if ($this->dept_code == 0 && $this->band == 0) {
                $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code != 0 && $this->band == 0) {
                $where = array('st.dept_code' => $this->dept_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code == 0 && $this->band != 0) {
                $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code != 0 && $this->band != 0) {
                $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            }

            //  $where=array('st.dept_code'=>$dept_code, 'st.Program'=>$admissionType,  'st.Year'=>$band,  'st.acyear'=>$acyear,  'st.admission'=>$program, 'st.is_active'=>'Active' );

            $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function print_id_card() {


        if ($this->input->get('print_id')) {
            $this->load->library('Pdf');
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
// set font
            $pdf->SetFont('Times', '', 12);
//  $pdf->SetFont("abyssinicasil", '', 12);
// $pdf->SetFont("ethiopiajiret",'',12);  

            $style = array('position' => '', 'align' => 'C', 'stretch' => FALSE, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => 'auto', 'vpadding' => 'auto', 'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);

            $wdu = '<label style="margin: 0px 0px 0px 0px; font-size: 14px;color:white" > <b>Woldia University</b>  <br/> Student ID Card</label> ';

            $result = array();
            $band = "";
            if (filter_input(INPUT_GET, "id_number")) {
                $stud_id = filter_input(INPUT_GET, 'id_number');
                $result = $this->ManageStudent_model->get_student_by_id($stud_id);
                $single_print_back = true;
            } else if ($this->input->get("print_id_for_group")) {
                $where = array('print_status' => 0, 'r.is_deleted' => 0);
                $result = $this->ManageStudent_model->get_id_card_requests($where);
            } else {

                $this->faculty_code = filter_input(INPUT_GET, "faculty_code");
                $this->dept_code = filter_input(INPUT_GET, "dept_code");
                $this->program = filter_input(INPUT_GET, "program");
                $this->band = filter_input(INPUT_GET, "band");
                $this->acyear = filter_input(INPUT_GET, 'acyear');
                $this->admissionType = filter_input(INPUT_GET, 'admissionType');
                $where = array('st.is_active' => 'Active','st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType);

                 if ($this->dept_code == 0 && $this->band == 0) {
                        $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
                    } else if ($this->dept_code != 0 && $this->band == 0) {
                        $where = array('st.dept_code' => $this->dept_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
                    } else if ($this->dept_code == 0 && $this->band != 0) {
                        $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
                    } else if ($this->dept_code != 0 && $this->band != 0) {
                        $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
                    }

                    $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
                
            }

            $count = count($result);
            $html = "";
            $string = '';
            $temp = '';
            $p = 0;
            $p2 = 0;
            $dept = "";
            $fontsize = 12;
            $pdf->AddPage();
            $mr = $count % 6;
//to deterime  the number of ID UI below 6 
            $odd_pages = $count - $mr;
            for ($i = 0; $i < $count; $i++) {

                $p++;
                $row = $result[$i];
                $code = $row['bcID'];
                $id = $row['IDNumber'];
                $fullname = ucwords(strtolower($row['FullName']));
                $admission = $row['Program'];
                $program = $row['admission'];
                $dept = $row['dept_name'];
                $sex = $row['Sex'];
                $band = $row['Year'];
                $nationality = $row['nationality'];
                if (strlen($dept) > 57) {
                    $fontsize = 10;
                }
                if (!empty($row['photo'])) {
                    $studentPhoto = base_url() . 'resources/' . $row['photo'];
                } else {
                    $studentPhoto = base_url() . 'resources/icons/user.png';
                }
//  $issuedDate = $this->today;
                $txt = "woldia un photo";
                if ($p == 1 || $p == 3 || $p == 5) {
                    $position['x'] = 0;
                } else if ($p == 2 || $p == 4 || $p == 6) {
                    $position['x'] = 112;
                }
                if ($p == 1 || $p == 2) {
                    $position['y'] = 10;
                } else if ($p == 3 || $p == 4) {
                    $position['y'] = 93;
                } else if ($p == 5 || $p == 6) {
                    $position['y'] = 176;
                }

                $position['logo_x'] = $position['x'] + 7;
                $position['logo_y'] = $position['y'] + 1;
                $position['name_x'] = $position['Id_x'] = $position['dept_x'] = $position['sex_x'] = $position['issued_x'] = $position['x'];
//  $position['name_x'] = 3;
                $position['name_y'] = $position['y'] + 13;
// $position['Id_x'] = 3;
                $position['Id_y'] = $position['y'] + 19;
// $position['dept_x'] = 3;
                $position['sex_y'] = $position['y'] + 24; //48;
                $position['dept_y'] = $position['y'] + 29; //43
// $position['sex_x'] = 3;
//  $position['issued_x'] = 3;
                $position['issued_y'] = $position['y'] + 39; // 58;

                $position['photo_x'] = $position['x'] + 61; //65
                $position['photo_y'] = $position['y'] + 19; //38;
                $position['barcode_x'] = $position['x'] + 2;
                $position['barcode_y'] = $position['y'] + 44;

                $position['footer_x'] = $position['x'];
                $position['footer_y'] = $position['y'] + 60; // 83;

                $pdf->MultiCell(98, 66, "", 1, 'L', '', '', $position['x'], $position['y'], true);
                
                $pdf->SetFillColor(49, 61, 168);
// $pdf->SetTextColor(255, 255, 255);
                $pdf->MultiCell(98, 13, $this->wdu, 1, 'C', TRUE, '', $position['x'], $position['y'], true, 0, true);
                $pdf->SetTextColor(18, 18, 18);
                $pdf->Image(base_url() . 'resources/imgs/wdulogo.png', $position['logo_x'], $position['logo_y'], 20, 18, '', '', '', true, 300, '', false, false, 1, true, false, false);

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
// $pdf->writeHTMLCell(85, 5, $position['name_x'], $position['name_y'], '<b style="text-align:center; background-color:blue">' . $fullname . '</b>', 0);
//new code line
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(49, 61, 168);
                $pdf->MultiCell(98, 5, $fullname, 1, 'C', TRUE, '', $position['name_x'], $position['name_y'], true, 0, true);
                $pdf->SetTextColor(18, 18, 18);
                $pdf->SetFillColor("#006cad");
                $pdf->writeHTMLCell(60, 5, $position['Id_x'], $position['Id_y'], '<b style="color:Blue">IDNo:</b><u>' . $id . '</u>', 0);
                $pdf->writeHTMLCell(60, 5, $position['sex_x'], $position['sex_y'], '<b style="color:Blue">Sex: </b><u>' . $sex . '</u>' . ' <b style="color:Blue">Program: </b><u>' . $program . '</u>', 0);
                $pdf->writeHTMLCell(65, 5, $position['dept_x'], $position['dept_y'], '<b style="color:Blue;">Dept: </b><u style="font-size:' . $fontsize . '">' . $dept . '</u>', 0);
                $pdf->writeHTMLCell(60, 5, $position['issued_x'], $position['issued_y'], '<b style="color:Blue">Admission Type:</b><u>' . $admission . '</u>', 0);
//Image($file, $x='', $y='', $w=0, $h=0,    $type='',   $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
               
                if (empty($row['photo'])) {
                    $pdf->SetFillColor("#006cad");
                    $pdf->MultiCell(35, 36, ' ', 1, 'C', false, '', $position['photo_x'], $position['photo_y'], true, 0, true);
                } else {
                    $pdf->Image($studentPhoto, $position['photo_x'], $position['photo_y'], 40, 36, '', '', '', false, 300, '', false, false, 1, true, false, false);
                }

//write1DBarcode($code, $type, $x='', $y='',   $w='', $h='', $xres='', $style=array(), $align='')
               $pdf->write1DBarcode($code, 'C128A', $position['barcode_x'], $position['barcode_y'], 58, 16, 0.4, $style, 'N');
// $pdf->writeHTMLCell(100, 4, 3, 69, '<b>valid only renewed every semester</b>', 1);
//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
// $pdf->SetFillColor(189, 32, 37);
                $pdf->SetFont('Times', '', 12);
                $pdf->SetFillColor(255, 255, 255);
// $pdf->SetFont("abyssinicasil", '', 12);
                $pdf->MultiCell(97, 3, '<b>Nationality:<b style="color:blue">' . $nationality . '</b> <span style="color:white">......</span>   Registrar ...............</b> ', 1, 'C', true, '', $position['footer_x'], $position['footer_y'], true, 0, true);
                if ($p == 6 && $i !== $count - 1) {
                    $p = 0;
                    $pdf->AddPage();
                }
                if ($i < $count - 2) {
                    $next_row = $result[$i + 1];
                    if ($next_row['dept_code'] != $row['dept_code']) {
                        $p = 0;
                        $pdf->AddPage();
                    }
                }
            }
            if ($single_print_back) {
                $pdf->SetFont('Times', '', 12);
                $pdf->AddPage();
                $position2['x_'] = 112;
                $position2['y_'] = 10;
                $pdf->MultiCell(98, 66, "", 1, 'L', '', '', $position2['x_'], $position2['y_'], true);
// $pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);
                $pdf->SetFillColor(49, 61, 168);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->MultiCell(98, 15, $this->backinfo, 1, 'C', TRUE, '', $position2['x_'], $position2['y_'], true, 0, true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Image(base_url() . 'resources/icons/wdulogo.png', $position2['x_'] + 1, $position2['y_'] + 1, 20, 22, '', '', '', true, 300, '', false, false, 1, true, false, false);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("abyssinicasil", '', 10);
                $pdf->MultiCell(98, 20, $this->IDcardBackside, 1, 'L', TRUE, '', $position2['x_'], $position2['y_'] + 15, true, 0, true);
                $pdf->SetFont("abyssinicasil", '', 6);
                $pdf->MultiCell(94, 20, $this->masasebiya, 0, 'L', TRUE, '', $position2['x_'] + 3, $position2['y_'] + 52, true, 0, true);
                $pdf->SetFont('Times', '', 10);
                $pdf->SetFillColor(189, 32, 37);
                $pdf->MultiCell(98, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $position2['x_'], $position2['y_'] + 64, true, 0, true);
///“አእምሮ ሲከፈት ዓይኖች ማየት ይችላሉ” 
            }

            $today = Date("Y-m-d H-i-s");
            $filename = "IDcard_front_" . $dept . '_' . $band . '_' . $program . '_' . $today . '.pdf';
            ob_end_clean();
            $pdf->Output($filename, 'D');
        } else if ($this->input->post('preview_students_to_print_id_card')) {
            $this->get_student_to_prepare_IDCard();
            exit();
        } else {
            $data['_view'] = "studData/prepareIDCard";
            $this->load->view("layouts/main", $data);
        }
    }

    function print_ID_card_back_side() {
        $position2 = [];
        if ($this->input->get("print_id_back_side_for_group")) {
            $where = array('print_status' => 0, 'r.is_deleted' => 0, 'r.created_by' => $this->username);
            $result = $this->ManageStudent_model->get_id_card_requests($where);
        } else {
            $this->faculty_code = filter_input(INPUT_GET, "faculty_code");
            $this->dept_code = filter_input(INPUT_GET, "dept_code");
            $this->program = filter_input(INPUT_GET, "program");
            $this->band = filter_input(INPUT_GET, "band");
            $this->acyear = filter_input(INPUT_GET, 'acyear');
            $this->admissionType = filter_input(INPUT_GET, 'admissionType');
            $where = array('st.is_active' => 'Active','st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType,);

            if ($this->dept_code == 0 && $this->band == 0) {
                $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code != 0 && $this->band == 0) {
                $where = array('st.dept_code' => $this->dept_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code == 0 && $this->band != 0) {
                $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            } else if ($this->dept_code != 0 && $this->band != 0) {
                $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
            }
            $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
         
        }
        $count = count($result);  

        $p = 0;
        $this->load->library('Pdf');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
// set font
        $pdf->SetFont('Times', '', 12);
        $pdf->AddPage();
        for ($i = 0; $i < $count; $i++) {
           $p++;
            $row = $result[$i];
            if ($p == 1 || $p == 3 || $p == 5) {
                $position2['x_'] = 0;
            } else if ($p == 2 || $p == 4 || $p == 6) {
                $position2['x_'] = 112;
            }
            if ($p == 1 || $p == 2) {
                $position2['y_'] = 10;
            } else if ($p == 3 || $p == 4) {
                $position2['y_'] = 93;
            } else if ($p == 5 || $p == 6) {
                $position2['y_'] = 176;
            }
            $pdf->MultiCell(97, 66, "", 1, 'L', '', '', $position2['x_'], $position2['y_'], true);
            // $pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);           
            $pdf->SetFillColor(49, 61, 168);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->MultiCell(97, 14, $this->backinfo, 1, 'C', TRUE, '', $position2['x_'], $position2['y_'], true, 0, true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Image(base_url() . 'resources/icons/wdulogo.png', $position2['x_'] + 1, $position2['y_'] + 1, 20, 24, '', '', '', true, 300, '', false, false, 1, true, false, false);
            $pdf->SetTextColor(0, 0, 0);
             $pdf->SetFont("abyssinicasil", '', 9);
            $pdf->MultiCell(97, 20, $this->IDcardBackside, 1, 'L', TRUE, '', $position2['x_'], $position2['y_'] + 15, true, 0, true);
             $pdf->SetFont("abyssinicasil", '', 6);
            $pdf->MultiCell(93, 20, $this->masasebiya, 0, 'L', TRUE, '', $position2['x_'] + 2, $position2['y_'] + 50, true, 0, true);
            $pdf->SetFont('Times', '', 9);
            $pdf->SetFillColor(189, 32, 37);
            $pdf->MultiCell(97, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $position2['x_'], $position2['y_'] + 62, true, 0, true);

///“አእምሮ ሲከፈት ዓይኖች ማየት ይችላሉ” 
            if ($p == 6 && $i !== $count - 1) {
                $p = 0;
                $pdf->AddPage();
            }


            if ($i < $count - 2) {
                $next_row = $result[$i + 1];
                if ($next_row['dept_code'] != $row['dept_code']) {
                    $p = 0;
                    $pdf->AddPage();
                }
            }
        }

        $today = Date("Y-m-d H-i-s");
        $filename = "id_card_back_side" . $this->dept_code . '_' . $this->band . '_' . $this->program . '_' . $this->today . '.pdf';
        ob_end_clean();
        $pdf->Output($filename, 'D');
    }

    function assign_students_to_cafeteria() {
        if (filter_input(INPUT_POST, "getStudentsGroupedbyDepartment")) {
            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $this->admissionType = filter_input(INPUT_POST, 'admissionType');
            $this->semester = filter_input(INPUT_POST, 'acsemester');


            $where = array('st.acyear' => $this->acyear, 'st.semester' => $this->semester,
                'st.Program' => $this->program, 'st.admission' => $this->admissionType, "st.is_active" => 'Active');

            $result = $this->ManageStudent_model->getStudentsGroupedByDepartment($where);

            echo json_encode($result);
            exit();
        } else if (filter_input(INPUT_POST, 'assignstudents_to_cafe') == "assignstudents_to_cafe") {

            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $this->admissionType = filter_input(INPUT_POST, 'admission');
            $this->semester = filter_input(INPUT_POST, 'semester');
            $cafe_number = filter_input(INPUT_POST, 'CafeNumber');
            $year = filter_input(INPUT_POST, 'Year');
            $dept_code = filter_input(INPUT_POST, 'dept_code');

            $where = array('st.acyear' => $this->acyear, 'st.semester' => $this->semester,
                'st.Program' => $this->program, 'st.admission' => $this->admissionType, 'st.Year' => $year, 'st.dept_code' => $dept_code, "st.is_active" => 'Active');

            $data_update = array('st.acyear' => $this->acyear, 'st.semester' => $this->semester, 'CafeNumber' => $cafe_number, 'st.Year' => $year);

// $data_save=array('st.acyear'=>  $this->acyear, 'st.semester'=> $this->semester,'CafeNumber'=>$cafe_number);
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
            if ($cafe_number != null) {
                $studresult = $this->ManageStudent_model->getStudentsByCriteria($where);
                foreach ($studresult as $stud) {
                    $id = $stud['IDNumber'];
                    $bcode = $stud['bcID'];
                    $exist = $this->ManageStudent_model->getByID($id);
                    $result = $this->ManageStudent_model->assignStudentsToCafteria($id, $cafe_number, $bcode, $exist, $this->acyear, $this->semester);
                    $result['message'] = "OperationDone";
                    $result['status'] = "ok";
                }
            }

            echo json_encode($result);
            exit();
        }

        $data['_view'] = "studData/assign_stud_to_cafeteria";
        $this->load->view("layouts/main", $data);
    }

    function allow_service_by_exceptional_case() {
        if (filter_input(INPUT_POST, 'get_student_by_id')) {
            $id = filter_input(INPUT_POST, 'ID');
            $result = $this->ManageStudent_model->get_student_by_ID_or_barcode($id);
            $responce = [];
            if (count($result) > 0) {
                $responce = $result[0];
                $responce['status'] = 1;
            } else {
                $responce['message'] = "This student is not available or the ID/code may be incorrect";
                $responce['status'] = 0;
            }
            echo json_encode($responce);
            return 0;
        } else if (filter_input(INPUT_POST, 'special_permission_save')) {
            $id = filter_input(INPUT_POST, 'studid');
            $pd = filter_input(INPUT_POST, 'lastpermissionDate');
            $permiter = $this->username;
// $result = $this->ManageStudent_model->get_student_by_ID_or_barcode($id)[0];
            $status = 1;
            $result = $this->ManageStudent_model->grant_special_permission($id, $pd, $status, $permiter, $this->date_time);
            $data['operation_status'] = $result;
// print_r($data['operation_status'] );
//exit();
        }
        $data['special_permissions'] = $this->ManageStudent_model->get_special_permissions();
        $data['_view'] = "studData/special_permission";
        $this->load->view("layouts/main", $data);
    }

    function revoke_permission($id) {
        $query = "UPDATE exceptionalstudent SET permissionStatus=0   WHERE auto_ID=$id";
        $resulr = $this->db->query($query);
        $data['special_permissions'] = $this->ManageStudent_model->get_special_permissions();
        $data['_view'] = "studData/special_permission";
        $this->load->view("layouts/main", $data);
    }

    function register_inouts() {
        $data['_view'] = "studData/input_registration";
        $this->load->view("layouts/main", $data);
    }

    function manage_students_to_gate() {
        $type = INPUT_POST;
        if (filter_input(INPUT_POST, "mangestudents") == "mangestudents") {
            $this->dept_code = filter_input($type, 'dept_code');
            $faculity_code = filter_input($type, 'faculity_code');
            $this->program = filter_input($type, 'program');
            $this->admissionType = filter_input($type, "admissionType");
            $this->band = filter_input($type, 'band');
            $this->acyear = filter_input($type, 'acyear');
            $this->semester = filter_input($type, 'semester');

            if ($this->band == 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
            } else if ($this->band != 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, "st.Year" => $this->band, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
            } else if ($this->band == 0 && $this->dept_code != 0) {
                $where = array('st.dept_code' => $this->dept_code, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
            } else {
                $where = array("st.Year" => $this->band, 'st.dept_code' => $this->dept_code, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
            }




            if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null && $this->semester != null && $this->admissionType != null) {
                $result = $this->ManageStudent_model->get_students_to_Controll_gate($where);
//  $result['status'] = "ok";
            } else {
                $result['message'] = "please enter valid value";
                $result['status'] = "failed";
            }
            echo json_encode($result);
            exit();
        }
        $data['_view'] = "studData/manage_students_for_cafe_service";
        $this->load->view("layouts/main", $data);
    }

    public function activate_student() {
        $id = filter_input(INPUT_POST, 'IDNumber');
        $result = $this->ManageStudent_model->activate_Student($id);
        echo json_encode($result);
    }

    public function deactivate_student() {
        $id = filter_input(INPUT_POST, 'IDNumber');
        $result = $this->ManageStudent_model->deactivate_Student($id);
        echo json_encode($result);
    }

    function deactivate_all() {
        $type = INPUT_POST;
        $faculity_code = filter_input($type, 'faculity_code');
        $this->dept_code = filter_input($type, 'dept_code');
        $this->program = filter_input($type, 'program');
        $this->admissionType = filter_input($type, "admissionType");
        $this->band = filter_input($type, 'band');
        $this->acyear = filter_input($type, 'acyear');
        $this->semester = filter_input($type, 'semester');

        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null && $this->semester != null && $this->admissionType != null) {
            if ($this->band == 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
                $resultarray = $this->ManageStudent_model->get_students_to_Controll_gate($where);
                $result = [];
                foreach ($resultarray as $row) {
                    $id = $row['IDNumber'];
                    $result = $this->ManageStudent_model->deactivate_Student($id);
                }
                echo json_encode($result);
                exit();
            } else if ($this->band != 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, "st.Year" => $this->band, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
                $resultarray = $this->ManageStudent_model->get_students_to_Controll_gate($where);
                foreach ($resultarray as $row) {
                    $id = $row['IDNumber'];
                    $result = $this->ManageStudent_model->deactivate_Student($id);
                }
                echo json_encode($result);
                exit();
            } else if ($this->band == 0 && $this->dept_code != 0) {
                $where = array('dept_code' => $this->dept_code, 'acyear' => $this->acyear,
                    'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);
                $result = $this->ManageStudent_model->deactivate_all_students($where);
                echo json_encode($result);
                exit();
            } else {
                $where = array("Year" => $this->band, 'dept_code' => $this->dept_code, 'acyear' => $this->acyear,
                    'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);
                $result = $this->ManageStudent_model->deactivate_all_students($where);
                echo json_encode($result);
                exit();
            }
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
            echo json_encode($result);
            exit();
        }
    }

    function activate_all() {
        $type = INPUT_POST;
        $faculity_code = filter_input($type, 'faculity_code');
        $this->dept_code = filter_input($type, 'dept_code');
        $this->program = filter_input($type, 'program');
        $this->admissionType = filter_input($type, "admissionType");
        $this->band = filter_input($type, 'band');
        $this->acyear = filter_input($type, 'acyear');
        $this->semester = filter_input($type, 'semester');
//        $where = array("Year" => $this->band, 'dept_code' => $this->dept_code, 'acyear' => $this->acyear,
//            'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);

        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null && $this->semester != null && $this->admissionType != null) {
            if ($this->band == 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
                $resultarray = $this->ManageStudent_model->get_students_to_Controll_gate($where);
                $result = [];
                foreach ($resultarray as $row) {
                    $id = $row['IDNumber'];
                    $result = $this->ManageStudent_model->activate_Student($id);
                }
                echo json_encode($result);
                exit();
            } else if ($this->band != 0 && $this->dept_code == 0) {
                $where = array("dep.faculity_code" => $faculity_code, "st.Year" => $this->band, 'st.acyear' => $this->acyear,
                    'st.semester' => $this->semester, 'st.Program' => $this->program, 'st.admission' => $this->admissionType);
                $resultarray = $this->ManageStudent_model->get_students_to_Controll_gate($where);
                foreach ($resultarray as $row) {
                    $id = $row['IDNumber'];
                    $result = $this->ManageStudent_model->activate_Student($id);
                }
                echo json_encode($result);
                exit();
            } else if ($this->band == 0 && $this->dept_code != 0) {
                $where = array('dept_code' => $this->dept_code, 'acyear' => $this->acyear,
                    'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);
                $result = $this->ManageStudent_model->activate_all_students($where);
                echo json_encode($result);
                exit();
            } else {
                $where = array("Year" => $this->band, 'dept_code' => $this->dept_code, 'acyear' => $this->acyear,
                    'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);
                $result = $this->ManageStudent_model->activate_all_students($where);
                echo json_encode($result);
                exit();
            }
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
            echo json_encode($result);
            exit();
        }
    }

    function delete_student() {
        $data = ["message" => "Something went wrong", "status" => "faild"];
        if (filter_input(INPUT_POST, "IDNumber")) {
            $stud_id = filter_input(INPUT_POST, 'IDNumber');
//$where=array("id_Number"=>$stud_id);
            $update_data = array("is_active" => "InActive");
            $status = $this->ManageStudent_model->update_student($update_data, $stud_id);
            if ($status) {
                $data['message'] = "Operation Done Successfully ";
                $data['status'] = "ok";
                echo json_encode($data);
            } else {
                echo json_encode($data);
            }
        } else {
            echo json_encode($data);
        }
    }

    function delete_imported_student() {
        $data = ["message" => "Something went wrong", "status" => "faild"];
        $dept_code = filter_input(INPUT_POST, 'dept_code');
        $program = filter_input(INPUT_POST, 'program');
        $band = filter_input(INPUT_POST, 'band');
        $acyear = filter_input(INPUT_POST, 'acyear');
        $admissionType = filter_input(INPUT_POST, 'admissionType');

        if (!empty($dept_code) && !empty($program) && !empty($band) && !empty($acyear) && !empty($admissionType)) {

            $where = array("dept_code" => $dept_code, "Program" => $admissionType, "admission" => $program, "acyear" => $acyear);

            $status = $this->ManageStudent_model->delete_imported_student($where);
            if ($status) {
                $data['message'] = "Operation Done Successfully ";
                $data['status'] = "ok";
                echo json_encode($data);
            } else {
                echo json_encode($data);
            }
        } else {
            $data = ["message" => "Please Enter the all criterion", "status" => "faild"];
            echo json_encode($data);
        }
    }

    function save_id_card_request() {

        if ($this->input->post('studentID')) {
            $message = "";
            $status = "";
            $student_id = $this->input->post('studentID');
            $requested_data = array(
                "student_id" => $student_id,
                "created_on" => $this->today,
                "created_by" => $this->username,
                "print_status" => 0,
                "is_deleted" => 0);
            $where = array("student_id" => $student_id, "print_status" => 0, "is_deleted" => 0);
            if ($this->ManageStudent_model->is_id_exist($student_id)) {

                if ($this->ManageStudent_model->is_id_card_request_not_exist($where)) {
                    $status = $this->ManageStudent_model->save_id_card_request($requested_data);
                    if ($status) {
                        $message = "Operation done successfully";
                        $status = true;
// echo json_encode($data);
                    } else {
                        $message = "Operation Failed";
                        $status = false;
//echo json_encode($data);
                    }
                } else {
                    $message = "This Student already registered";
                    $status = false;
                }
            } else {
                $message = "This ID Number is not found in student list. Operation Failed";
                $status = false;
// echo json_encode($data);
            }
            $this->session->set_flashdata("message", $message);
            $this->session->set_flashdata("status", $status);
            redirect("ManageStudent/save_id_card_request");
        } else if ($this->input->get("complete")) {
            $request_id = $this->input->get("request_id");
            $data_update = array("print_status" => 2);
            $this->ManageStudent_model->update_id_card_request($data_update, $request_id);
            $message = "This student request completed";
            $status = true;
            $this->session->set_flashdata("message", $message);
            $this->session->set_flashdata("status", $status);
        } else if ($this->input->get("delete")) {
            $request_id = $this->input->get("request_id");
            $data_update = array("is_deleted" => 1);
            $this->ManageStudent_model->update_id_card_request($data_update, $request_id);
            $message = "This student request deleted";
            $status = true;
            $this->session->set_flashdata("message", $message);
            $this->session->set_flashdata("status", $status);
        }

        $where = array('print_status !=' => 2, 'r.is_deleted' => 0);
        $data['requests'] = $this->ManageStudent_model->get_id_card_requests($where);
        $data['_view'] = "studData/prepareIDCard_for_group";
        $this->load->view("layouts/main", $data);
    }

    function update_student_info() {
        $message = "";
        $status = false;
        if ($this->input->post('studentID')) {
            $student_id = trim($this->input->post('studentID'));
            $result = $this->ManageStudent_model->get_student_by_id($student_id);

            if (count($result) > 0) {
                $data['student_info'] = $result[0];
                $status = true;
            } else {
                $message = "This ID Number is not found";
                $status = false;
            }
            $this->session->set_flashdata("message", $message);
            $this->session->set_flashdata("status", $status);
        }
        if ($this->input->post('id')) {

            $id = $this->input->post("id");
            $full_name = $this->input->post("first_name") . " " . $this->input->post("father_name") . " " . $this->input->post("grand_father_name");
            $update_data = array(
                'Year' => $this->input->post("year"), //admission_type
// 'dept_code' => $this->input->post("dept_code"),
                "nationality" => $this->input->post("nationality"),
                "FullName" => $full_name,
                'updatedOn' => $this->today,
                'updatedBy' => $this->username);
            $status = $this->ManageStudent_model->update_student($update_data, $id);

            if ($status) {
                $status = true;
                $message = "Successfully Updated";
                $result = $this->ManageStudent_model->get_student_by_id($id);
                $data['student_info'] = $result[0];
            } else {
                $status = false;
                $message = "Not updated. Operation Faild!!";
            }
            $this->session->set_flashdata("message", $message);
            $this->session->set_flashdata("status", $status);
        }

        $data['_view'] = "studData/update_student_info";
        $this->load->view("layouts/main", $data);
    }

    function stop_or_permit_cafeteria_service() {

        if ($this->input->get("restart")) {
            $data['message'] = "service terminated";
            $where = array("is_active" => "Active");
            $data = array("is_active" => "Inactive");
            $result = $this->ManageStudent_model->make_students_in_active($data, $where);
            $data['number_of_row_affected'] = $result;
        }
        $data['_view'] = "studData/stop_permit_cafe_service";
        $this->load->view("layouts/main", $data);
    }

    function set_student_section() {
        if ($this->input->post('uploadstudentsection')) {

            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
                $data['message'] = "<h3 class='text-success'>operation done</h3>";
                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $i = 0;
                foreach ($sheetData as $row) {
                    if ($i == 0) {
                        $i++;
                        continue;
                    }
                    $i++;
                    $id = $row["Student ID"];
                    $section = $row["Section"];
                    $data_update = array("section" => $section);
                    $result = $this->ManageStudent_model->update_student($data_update, $id);
                    if ($result) {
                        $data['message'] = "Section updated Successfully";
                    } else {
                        $data['message'] = "Section Not updated";
                    }
                }
            } else {
                $data['message'] = "The file format is not allowed";
            }
        } else {
            $data['_view'] = "studData/import_students_section";
            $this->load->view("layouts/main", $data);
        }
    }

}
