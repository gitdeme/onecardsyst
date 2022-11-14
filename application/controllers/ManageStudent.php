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

    public function __construct() {
        parent::__construct();
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

    function manage_student_photo() {
        $data["students"] = array();
        if (filter_input(INPUT_POST, "search_photo")) {
            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $this->semester = filter_input(INPUT_POST, 'semester');
            $this->program = filter_input(INPUT_POST, 'program');
            $this->admissionType = filter_input(INPUT_POST, 'admission_type');
            $where = array('acyear' => $this->acyear, "st.is_active" => "Active", 'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType,);
            $data["students"] = $this->ManageStudent_model->get_student_with_photo($where);
            $data["criteria"] = $where;
        } elseif (filter_input(INPUT_GET, "delete_by_id")) {
            $id = filter_input(INPUT_GET, "delete_by_id");
            $update_data = array("photo" => Null, "updatedOn" =>$this->date_time, "updatedBy" => $this->username );
            $this->ManageStudent_model->update_student($update_data, $id);
//WDU1200331
//resources/studentphoto/WDU1200331.jpg
            $result =  $this->ManageStudent_model->getStudentsByCriteria(array("IDNumber"=> $id));
          //  print_r($result);
            if (count($result) > 0) {
                $this->program = $result[0]["Program"];
                $this->semester=$result[0]["semester"];
                $this->admissionType=$result[0]["admission"];
                $this->acyear=$result[0]["acyear"];
                
            }
              $where = array('acyear' => $this->acyear, "st.is_active" => "Active", 'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);
           
            $data["students"] = $this->ManageStudent_model->get_student_with_photo($where);
        }

        $data["criteria"] = array('acyear' => $this->acyear, 'semester' => $this->semester, 'Program' => $this->program, 'admission' => $this->admissionType);

        $data['_view'] = "studData/manage_photo";
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

    function send_your_photo() {
        if ($this->input->post("upload_your_photo")) {
            $filename = $this->user->username;
            $config['upload_path'] = 'resources/studentphoto';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size'] = 0;
            $config['max_width'] = 880;
            $config['max_height'] = 1112;
            $config['min_width'] = 200;
            $config['min_height'] = 250;
            $config['overwrite'] = true;
            $file_name_id = $config['file_name'] = $filename;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $errors = [];
            if (!$this->upload->do_upload("yourphoto")) {
                array_push($errors, array('error' => $this->upload->display_errors(), 'file_name' => $file_name_id));
            } else {
                $data_ = array('upload_data' => $this->upload->data());
                $filepath = "resources/studentphoto/" . $data_['upload_data']["file_name"];
                if (count($this->db->query("select * from students WHERE IDNumber='$filename'")->result_array()) > 0) {
                    $result = $this->db->simple_query("UPDATE students SET photo='$filepath' WHERE IDNumber='$filename'");
                } else {
                    $error["error"] = "This ID is not found please check it again";
                    $error["file_name"] = $filename;
                    array_push($errors, $error);
                    unlink("resources/" . $filepath);
                }
            }

            $data['upload_status'] = $errors;
        }
        $result = $this->ManageStudent_model->get_student_by_id($this->user->username);
        if (count($result) > 0) {
            $data['user_info'] = $result[0];
        }
        $data['_view'] = "studData/import_your_photo";
        $this->load->view("layouts/main", $data);
    }

    function upload_student_photo_in_mass() {
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

    function import_freshman_students() {
        if ($this->input->post("uploadstudentbutton")) {
            $duplicated = true;
            $number_of_student_imported = 0;
            $number_of_student_unimported = 0;
            $import_date = $this->today;
            $id_list = array();
// $admission = filter_input(INPUT_POST, "admission");
            $stream = 0; // filter_input(INPUT_POST, "stream_code");
//  $band = filter_input(INPUT_POST, "band");
            $acyear = filter_input(INPUT_POST, "acyear");
            $semester = filter_input(INPUT_POST, "acsemester");

            $admission = filter_input(INPUT_POST, "admission");
            $program = filter_input(INPUT_POST, "program");
//$program = filter_input(INPUT_POST, "program");
            $createdBy = $this->username;
            $query_status = [];
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $countfiles = count($_FILES['upload_file']['name']);
            $counter = 0;

            for ($i = 0; $i < $countfiles; $i++) {
                $filename = $_FILES['upload_file']['name'][$i];
                if (isset($_FILES['upload_file']['name'][$i]) && in_array($_FILES['upload_file']['type'][$i], $file_mimes)) {
                    $data['message'] = "<h3 class='text-success'>operation done</h3>";
                    $arr_file = explode('.', $_FILES['upload_file']['name'][$i]);
                    $extension = end($arr_file);
                    if ('csv' == $extension) {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                    } else {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    }
                    $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name'][$i]);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();

                    $h = 0;
                    $id_list = array();
                    $year = 1;
                    $faculty_name = "";
                    $dept_name = "";
                    $fac_id = 0;
                    $section = "";
                    $section_no = 0;
                    foreach ($sheetData as $row) {
                        if ($h == 32) {
                            $status = $this->get_cols_index_fresh($row);
                            if ($status === FALSE) {
                                $data['message'] = "<h1 class='text-danger'>Column name should be just like below. but the order doent matter.</h1>";
                                $data['columns'] = $row;
                                //  print_r($row);
                                break;
                            } else {
                                $h++;
                                continue;
                            }
                        }
                        if ($h == 21) {
                            $dept_name = trim($row[14]);
                            $dept_info = $this->BasicData_model->get_department_by_name($dept_name);
                            if (count($dept_info) != 0) {
                                $dept_code = $dept_info[0]["dept_code"];
                                $fac_id = $dept_info[0]["faculity_code"];
                            } else {
                                $data["message"] = "<h1>Please register department $dept_name</h1>";
                                break;
                            }
                        }
                        if ($h == 27) {
                            $section = $row[31];
                            $bracket_index = strpos($section, "(");
                            $section_no = substr($section, 0, $bracket_index);
                        }

                        if ($h < 33) {
                            $h++;
                            continue;
                        }
                        $name = $row[$this->fn_ind];
                        if (empty($name)) {
                            continue;
                        }
                        $h++;

                        $id = trim($row[$this->id_ind]);
                        if (!empty($id)) {
                            array_push($id_list, $id);
                            $exist = $this->ManageStudent_model->is_id_exist($id);
                            if (!$exist) {
                                $barcode = $this->generate_string_barcode(13);
                                $unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                                while (!$unique) {
                                    $barcode = $this->generate_string_barcode(13);
                                    $unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                                }
                                $row_data = array(
                                    "FullName" => $row[$this->fn_ind],
                                    'Sex' => $row[$this->sex_ind],
                                    'Year' => 1,
                                    'IDNumber' => $id,
                                    'bcID' => $barcode,
                                    'entry_year' => NULL,
                                    'dept_code' => $dept_code,
                                    'Stream' => $stream,
                                    'acyear' => $acyear,
                                    'semester' => "I",
                                    'Program' => $program,
                                    'admission' => $admission,
                                    'photo' => "",
                                    'is_active' => "Active",
                                    'createDate' => $this->today,
                                    'createdBy' => $this->username,
                                    'section_number' => $section_no,
                                );

                                $status = $this->ManageStudent_model->import_students($row_data);
                                if ($status) {
                                    $number_of_student_imported++;
                                } else {
                                    $number_of_student_unimported;
                                }
                            } else {
                                $update_data = array('Year' => $year, 'dept_code' => $dept_code, 'Stream' => $stream,
                                    'acyear' => $acyear, 'semester' => $semester, 'is_active' => "Active",
                                    'section_number' => $section_no, 'updatedOn' => $this->today, 'updatedBy' => $this->username);

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
                    // echo "<h1>Sheet $i complated</h1>";
                } else {
                    $data['message_file_type'] = "<h3 class='text-danger'> file type not existed </h3>";
                    continue;
                }
            }
            $data['id_list'] = $id_list;
            $data['counter'] = $number_of_student_imported;
            $data['_view'] = "studData/import_freshman_students";
            $this->load->view("layouts/main", $data);
        } else {
            $data['_view'] = "studData/import_freshman_students";
            $this->load->view("layouts/main", $data);
        }
    }

    function get_cols_index_fresh($colums) {
        if (is_array($colums)) {
            foreach ($colums as $key => $val) {
                $colums[$key] = trim($val);
            }
        }
        $this->fn_ind = array_search("Full Name", $colums);
        $this->sex_ind = array_search("Sex", $colums);
        $this->id_ind = array_search("Studetn ID", $colums);
        if ($this->fn_ind === FALSE || $this->sex_ind === FALSE || $this->id_ind === FALSE) {
            return FALSE;
        } else {
            return true;
        }
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

        if ($this->input->post("get_fac_for_gate_services")) {
            $this->get_faculties();
            exit();
        }


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
