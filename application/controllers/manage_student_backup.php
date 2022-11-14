<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ManageStudent1 extends SupperCtr {

    var $link, $barcodeID, $departureDate, $turnbackDate, $reason, $auto_ID, $SID;
    var $dept, $program, $band, $acyear;
    var $today, $current_time, $date_time, $username;
    var $fn_ind, $mn_ind, $ln_ind, $sex_ind, $id_ind;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Africa/Addis_Ababa');
        $this->today = date("Y-m-d");
        $this->current_time = date("h:i:s");
        $this->date_time = date("Y-m-d h:i:s");
        $this->load->model("ManageStudent_model");
        $this->username = "demeke";
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
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function prepare_id_card() {
        $data['_view'] = "studData/prepareIDCard";
        $this->load->view("layouts/main", $data);
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
            $import_date = $this->today;
            $dept_code = filter_input(INPUT_POST, "dept_code");
            $stream = filter_input(INPUT_POST, "stream_code");
            $band = filter_input(INPUT_POST, "band");
            $acyear = filter_input(INPUT_POST, "acyear");
            $semester = filter_input(INPUT_POST, "acsemester");
            $program = filter_input(INPUT_POST, "program");
            $createdDate = date("Y-m-d h:i:s");
            $createdBy = $this->username;
            $query_status = [];
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
               
                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                //  echo "<pre>";
                // print_r($sheetData);
                $r = 0;

                foreach ($sheetData as $row) {

                    if ($r == 0) {
                        $r++;
                        continue;
                    }
                    if ($r === 1) {
                        $status = $this->get_cols_index($row);

                        if ($status === FALSE) {
                            $data['message'] = "<h1 class='text-danger'>Column name should be just like below</h1>";
                            // $r++;
                            break;
                        } else {
                            $r++;
                            continue;
                        }
                    }
                    $id = $row[$this->id_ind];
                    if (!empty($id)) {
                        $exist = $this->ManageStudent_model->is_id_exist($id);
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
                                'Year' => $band,
                                'IDNumber' => $row[$this->id_ind],
                                'bcID' => $barcode,
                                'ExamID' => "",
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'Program' => $program,
                                'Priority' => 1,
                                'photo' => "",
                                'is_active' => "Active",
                                'createDate' => $createdDate,
                                'createdBy' => $this->username
                            );
                            // print_r($row_data);
                            $status = $this->ManageStudent_model->import_students($row_data);
                            $query_status = $status;
                            print_r($query_status);
                        } else {
                         
                            $update_data = array(
                                'Year' => $band,
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'is_active' => "Active",
                                'updatedOn' => $createdDate,
                                'updatedBy' => $this->username);
                            //  print_r($update_data);
                            $status = $this->ManageStudent_model->update_student($update_data, $id);
                            $query_status = $status;
                        }
                        //  print_r($query_status);
                    } else {

                        $data['message'] = "<h3 class='text-danger'> already existed</h3>";
                    }
                }
            } else {
                $data['message'] = "<h3 class='text-success'> file type not existed </h3>";
            }
            $data['message'] = "<h3 class='text-success'>operation done</h3>";
        }
        $data['_view'] = "studData/import_students";
        $this->load->view("layouts/main", $data);
    }

    private function get_cols_index($colums) {
        //     print_r($colums);
        $this->fn_ind = array_search("First Name", $colums);

        $this->mn_ind = array_search("Father Name", $colums);
        if (!$this->mn_ind) {
            $this->mn_ind = array_search("Middle Name", $colums);
        }

        $this->ln_ind = array_search("GFather Name", $colums);
        if (!$this->ln_ind) {
            $this->ln_ind = array_search("Last Name", $colums);
        }
        $this->sex_ind = array_search("Sex", $colums);
        $this->id_ind = array_search("Student ID", $colums);
        if (!$this->id_ind) {
            $this->id_ind = array_search("ID", $colums);
        }

        if ($this->fn_ind == FALSE || $this->mn_ind == FALSE || $this->ln_ind == FALSE || $this->sex_ind == FALSE || $this->id_ind == FALSE) {
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

    function get_student_to_prepare_IDCard() {

        $this->dept_code = filter_input(INPUT_POST, "dept_code");
        $this->program = filter_input(INPUT_POST, "program");
        $this->band = filter_input(INPUT_POST, "band");
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->get_studentsForIDDesign($this->dept_code, $this->program, $this->band, $this->acyear);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function print_id_card() {
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
        $style = array('position' => '', 'align' => 'C', 'stretch' => FALSE, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => 'auto', 'vpadding' => 'auto', 'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);

        $wdu = '<label style="margin: 0px 0px 0px 0px; font-size: 14px;color:white" > <b>Woldia University</b>  <br/> Student ID Card</label> ';
        $backinfo = "<label  style='font-size: 12px'>
                                Woldia University, Ethiopia <br/> </label> 
                            <label> POBox:</label><label> 400</label> 
                            <label> Phone: </label> 
                            <label> 0333412341 </label><br/> <label> Fax: +251 </label><br/>
                            <label>Website<Label> www.wldu.edu.et";
        $IDcardBackside = '<table border="1" style="border-collapse: collapse; width: 97mm; height:49mm">
                                <tr style="height:30px">
                                    <th> Year </th> 
                                    <th> Semester I </th>  
                                    <th> Semester II  </th> 
                                </tr>
                                <tr>
                                    <td> 2012 </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>2013 </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>2014 </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>2015 </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                                <tr>
                                    <td>2016 </td> 
                                    <td> </td>   
                                    <td> </td> 
                                </tr>
                            </table>';

//require_once 'barcode.php';
//  require "./angularservice.php";
// $op = new MySErvice();
        $result = array();
        if (filter_input(INPUT_GET, "id_number")) {
            $stud_id = filter_input(INPUT_GET, 'id_number');
            $result = $this->ManageStudent_model->get_student_by_id($stud_id);
        } else {
            $dept_code = filter_input(INPUT_GET, 'dept_code');
            $program = filter_input(INPUT_GET, 'program');
            $band = filter_input(INPUT_GET, 'band');
            $acyear = filter_input(INPUT_GET, 'acyear');
            $result = $this->ManageStudent_model->get_studentsForIDDesign($dept_code, $program, $band, $acyear);
        }

        $count = count($result);
        $html = "";
        $string = '';
        $temp = '';
        $p = 0;
        $p2 = 0;
        $no_back_ui = 0;
        $pdf->AddPage();
        $print_back = false;
        for ($i = 0; $i < $count; $i++) {
            $p++;
            $print_back = false;
            $row = $result[$i];
            $code = $row['bcID'];
            $id = $row['IDNumber'];
            $fullname = $row['FullName'];
            $program = $row['Program'];
            $dept = $row['dept_name'];
            $sex = $row['Sex'];
            $studentPhoto = base_url() . 'resources/imageFiles/WDU111318.jpg';
            $issuedDate = $this->today;
            $txt = "woldia un photo";

            if ($p == 1 || $p == 3 || $p == 5) {
                $position['x'] = 3;
            } else if ($p == 2 || $p == 4 || $p == 6) {
                $position['x'] = 107;
            }
            if ($p == 1 || $p == 2) {
                $position['y'] = 20;
            } else if ($p == 3 || $p == 4) {
                $position['y'] = 93;
            } else if ($p == 5 || $p == 6) {
                $position['y'] = 166;
            }

            $position['logo_x'] = $position['x'] + 7;
            $position['logo_y'] = $position['y'] + 1;
            $position['name_x'] = $position['Id_x'] = $position['dept_x'] = $position['sex_x'] = $position['issued_x'] = $position['x'];
//  $position['name_x'] = 3;
            $position['name_y'] = $position['y'] + 14;
// $position['Id_x'] = 3;
            $position['Id_y'] = $position['y'] + 19;
// $position['dept_x'] = 3;
            $position['sex_y'] = $position['y'] + 24; //48;
            $position['dept_y'] = $position['y'] + 29; //43
// $position['sex_x'] = 3;
//  $position['issued_x'] = 3;
            $position['issued_y'] = $position['y'] + 39; // 58;

            $position['photo_x'] = $position['x'] + 65; //65
            $position['photo_y'] = $position['y'] + 19; //38;
            $position['barcode_x'] = $position['x'] + 2;
            $position['barcode_y'] = $position['y'] + 47;

            $position['footer_x'] = $position['x'];
            $position['footer_y'] = $position['y'] + 64; // 83;

            $pdf->MultiCell(100, 67, "", 1, 'L', '', '', $position['x'], $position['y'], true);
            $pdf->SetFillColor(49, 61, 168);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->MultiCell(100, 13, $wdu, 1, 'C', TRUE, '', $position['x'], $position['y'], true, 0, true);
            $pdf->SetTextColor(18, 18, 18);
            $pdf->Image(base_url() . 'resources/imgs/wdulogo.png', $position['logo_x'], $position['logo_y'], 20, 20, '', '', '', true, 300, '', false, false, 1, true, false, false);
            $pdf->SetFillColor("#006cad");
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
            $pdf->writeHTMLCell(85, 5, $position['name_x'], $position['name_y'], '<b style="color:Blue">Full Name:</b>' . $fullname, 0);
            $pdf->writeHTMLCell(60, 5, $position['Id_x'], $position['Id_y'], '<b style="color:Blue">ID.No,:</b>' . $id, 0);
            $pdf->writeHTMLCell(60, 5, $position['sex_x'], $position['sex_y'], '<b style="color:Blue"> Sex: </b>' . $sex . ' <b style="color:Blue"> Program: </b>' . $program, 0);
            $pdf->writeHTMLCell(60, 5, $position['dept_x'], $position['dept_y'], '<b style="color:Blue"> Dept: </b>' . $dept, 0);
            $pdf->writeHTMLCell(60, 5, $position['issued_x'], $position['issued_y'], '<b style="color:Blue"> Issued Date: </b>' . $issuedDate, 0);
//Image($file, $x='', $y='', $w=0, $h=0,    $type='',   $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
            $pdf->Image($studentPhoto, $position['photo_x'], $position['photo_y'], 47, 36, '', '', '', true, 300, '', false, false, 1, true, false, false);
//write1DBarcode($code, $type, $x='', $y='',   $w='', $h='', $xres='', $style=array(), $align='')
            $pdf->write1DBarcode($code, 'C128A', $position['barcode_x'], $position['barcode_y'], 60, 16, 0.4, $style, 'N');
// $pdf->writeHTMLCell(100, 4, 3, 69, '<b>valid only renewed every semester</b>', 1);
//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
            $pdf->SetFillColor(189, 32, 37);
            $pdf->MultiCell(100, 4, '<b style="color:white"> only valid if renewed every semester</b>', 1, 'C', true, '', $position['footer_x'], $position['footer_y'], true, 0, true);
            if ($p == 6) {
                $p = 0;
                $pdf->AddPage();
                $print_back = TRUE;
                $no_back_ui = 6;
            }
            $p2++;
            $position2 = [];
            $mr = $count % 6;
            if ($mr == 1 && $p == 1) {
                $pdf->AddPage();
                $print_back = true;
                $no_back_ui = 1;
            } elseif ($mr == 2 && $p == 2) {
                $pdf->AddPage();
                $print_back = true;
                $no_back_ui = 2;
            } elseif ($mr == 3 && $p == 3) {
                $pdf->AddPage();
                $print_back = true;
                $no_back_ui = 3;
            } elseif ($mr == 4 && $p == 4) {
                $pdf->AddPage();
                $no_back_ui = 4;
            } elseif ($mr == 5 && $p == 5) {
                $pdf->AddPage();
                $print_back = true;
                $no_back_ui = 5;
            }
            if ($print_back) {
                for ($j = 1; $j <= $no_back_ui; $j++) {

                    if ($j == 1 || $j == 3 || $j == 5) {
                        $position2['x_'] = 107;
                    } else if ($j == 2 || $j == 4 || $j == 6) {
                        $position2['x_'] = 3;
                    }
                    if ($j == 1 || $j == 2) {
                        $position2['y_'] = 20;
                    } else if ($j == 3 || $j == 4) {
                        $position2['y_'] = 93;
                    } else if ($j == 5 || $j == 6) {
                        $position2['y_'] = 166;
                    }
                    $pdf->MultiCell(100, 68, "", 1, 'L', '', '', $position2['x_'], $position2['y_'], true);
// $pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);
                    $pdf->SetFillColor(49, 61, 168);
                    $pdf->SetTextColor(255, 255, 255);
                    $pdf->MultiCell(100, 15, $backinfo, 1, 'C', TRUE, '', $position2['x_'], $position2['y_'], true, 0, true);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Image('../wdulogo.png', $position2['x_'] + 1, $position2['y_'] + 1, 20, 24, '', '', '', true, 300, '', false, false, 1, true, false, false);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->MultiCell(100, 20, $IDcardBackside, 1, 'L', TRUE, '', $position2['x_'], $position2['y_'] + 20, true, 0, true);
                    $pdf->SetFillColor(189, 32, 37);
                    $pdf->MultiCell(100, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $position2['x_'], $position2['y_'] + 64, true, 0, true);
///“አእምሮ ሲከፈት ዓይኖች ማየት ይችላሉ” 
                }
                if (($count - 1) != $i) {
                    $pdf->AddPage();
                }
            }
        }

        $today = Date("Y-m-d H -i-s");
        $filename = "idcard" . $today;
        ob_end_clean();
        $pdf->Output($filename, 'D');
    }

    function assign_students_to_cafeteria() {
        if (filter_input(INPUT_POST, "getStudentsGroupedbyDepartment")) {
            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $result = $this->ManageStudent_model->getStudentsGroupedByDepartment($this->acyear, $this->program);
            echo json_encode($result);
            return 0;
        } else if (filter_input(INPUT_POST, 'assignstudents_to_cafe') == "assignstudents_to_cafe") {

            $dataString = filter_input(INPUT_POST, 'depwithBand');
            $dataArraylist = json_decode($dataString);
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";


            foreach ($dataArraylist as $row) {
                if ($row->CafeNumber != null) {
                    $studresult = $this->ManageStudent_model->getStudentsByCriteria($row->dept_code, $row->Year, $row->acyear, $row->program, $row->CafeNumber);
                    foreach ($studresult as $stud) {
                        $id = $stud['IDNumber'];
                        $bcode = $stud['bcID'];
                        $exist = $this->ManageStudent_model->getByID($id);
                        $result = $this->ManageStudent_model->assignStudentsToCafteria($id, $row->CafeNumber, $bcode, $exist);
                    }
                }
            }
            echo json_encode($result);
            return 0;
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
            $pd = filter_input(INPUT_POST, 'lastDate');
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
            $this->program = filter_input($type, 'program');
            $this->band = filter_input($type, 'band');
            $this->acyear = filter_input($type, 'acyear');
            if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
                $result = $this->ManageStudent_model->get_students_to_Controll_gate($this->dept_code, $this->program, $this->band, $this->acyear);
            } else {
                $result['message'] = "please enter valid value";
                $result['status'] = "failed";
            }
            echo json_encode($result);
            exit();
        }
        $data['_view'] = "studData/manage_student_to_gate";
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

        $this->dept_code = filter_input(INPUT_POST, 'dept_code');
        $this->program = filter_input(INPUT_POST, 'program');
        $this->band = filter_input(INPUT_POST, 'band');
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->deactivate_all_students($this->dept_code, $this->program, $this->band, $this->acyear);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }


        echo json_encode($result);
    }

    function activate_all() {

        $this->dept_code = filter_input(INPUT_POST, 'dept_code');
        $this->program = filter_input(INPUT_POST, 'program');
        $this->band = filter_input(INPUT_POST, 'band');
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        $result = [];
        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->activate_all_students($this->dept_code, $this->program, $this->band, $this->acyear);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

}


defined('BASEPATH') OR exit('No direct script access allowed');

//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ManageStudent extends SupperCtr {

    var $link, $barcodeID, $departureDate, $turnbackDate, $reason, $auto_ID, $SID;
    var $dept, $program, $admissionType, $band, $acyear;
    var $today, $current_time, $date_time, $username;
    var $fn_ind, $mn_ind, $ln_ind, $sex_ind, $id_ind, $faculty_ind, $department_ind;
    var $admissionType_ind, $program_ind, $year_ind;

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

    function import_students_list() {
        if ($this->input->post("uploadstudentbutton")) {
            $duplicated = true;
            $import_date = $this->today;
            $dept_code = filter_input(INPUT_POST, "dept_code");
            $stream = filter_input(INPUT_POST, "stream_code");
            $band = filter_input(INPUT_POST, "band");
            $acyear = filter_input(INPUT_POST, "acyear");
            $semester = filter_input(INPUT_POST, "acsemester");
            $program = filter_input(INPUT_POST, "program");
            $createdDate = date("Y-m-d h:i:s");
            $createdBy = $this->username;
            $query_status = [];
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {

                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                //print_r($sheetData);
                // exit();
                //  echo "<pre>";
                $r = 0;
                foreach ($sheetData as $row) {
                    $r++;
                    if ($r < 10) {
                        // print_r($row);
                        //  echo "<br/>";
                        continue;
                    }
                    print_r($row);
                    echo "<br/>";
                    /*
                      if ($r === 10) {
                      $status = $this->get_cols_index($row);
                      if ($status === FALSE) {
                      $data['message'] = "<h1 class='text-danger'>Column name should be just like below</h1>";
                      // $r++;
                      break;
                      } else {
                      $r++;
                      continue;
                      }
                      }
                     */
                    $id = ""; //$row[$this->id_ind];
                    if (!empty($id)) {
                        $exist = $this->ManageStudent_model->is_id_exist($id);
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
                                'Year' => $band,
                                'IDNumber' => $row[$this->id_ind],
                                'bcID' => $barcode,
                                'ExamID' => "",
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'Program' => $program,
                                'Priority' => 1,
                                'photo' => "",
                                'is_active' => "Active",
                                'createDate' => $createdDate,
                                'createdBy' => $this->username
                            );
                            // print_r($row_data);
                            // $status = $this->ManageStudent_model->import_students($row_data);
                            $query_status = $status;
                            //  print_r($query_status);
                        } else {

                            $update_data = array(
                                'Year' => $band,
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'is_active' => "Active",
                                'updatedOn' => $createdDate,
                                'updatedBy' => $this->username);
                            // print_r($update_data);
                            // $status = $this->ManageStudent_model->update_student($update_data, $id);
                            $query_status = $status;
                        }
                        //  print_r($query_status);
                    } else {

                        //  $data['message'] = "<h3 class='text-danger'> already existed</h3>";
                    }
                }
                exit();
            } else {
                $data['message'] = "<h3 class='text-success'> file type not existed </h3>";
            }
            $data['message'] = "<h3 class='text-success'>operation done</h3>";
        }
        $data['_view'] = "studData/import_students";
        $this->load->view("layouts/main", $data);
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
                //print_r($sheetData);
                // exit();
                //  echo "<pre>";
                $r = 0;
                $dept_code = 0;
                $fac_id = 0;

                foreach ($sheetData as $row) {
                    $r++;
                    if ($r == 1) {
                        $status = $this->get_cols_index($row);
                        if ($status === FALSE) {
                            $data['message'] = "<h1 class='text-danger'>Column name should be just like below. but the order doent matter.</h1>";
                            $data['columns'] = $row;

                            break;
                        } else {
                            continue;
                        }
                    }


                    $id = $row[$this->id_ind];
                    if (!empty($id)) {
                        $exist = $this->ManageStudent_model->is_id_exist($id);
                        $faculty_name = $row[$this->faculty_ind];
                        $dept_name = $row[$this->department_ind];
                        if (!$exist) {
                            $barcode = $this->generate_string_barcode(13);
                            $unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                            while (!$unique) {
                                $barcode = $this->generate_string_barcode(13);
                                $is_unique = $this->ManageStudent_model->chek_barcode_uniqueness($barcode);
                            }
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
                                $dept_code = $dept_info[0]["dept_code"];
                            }
                            $row_data = array(
                                "FullName" => $row[$this->fn_ind] . ' ' . $row[$this->mn_ind] . ' ' . $row[$this->ln_ind],
                                'Sex' => $row[$this->sex_ind],
                                'Year' => $row[$this->year_ind],
                                'IDNumber' => $row[$this->id_ind],
                                'bcID' => $barcode,
                                'entry_year' => NULL,
                                'dept_code' => $dept_code,
                                'Stream' => $stream,
                                'acyear' => $acyear,
                                'semester' => $semester,
                                'Program' => $row[$this->program_ind],
                                'admission' => $row[$this->admissionType_ind],
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
                            $dept_info = $this->BasicData_model->get_department_by_name($dept_name);
                            $dept_code = $dept_info[0]["dept_code"];
                            $update_data = array(
                                'Year' => $row[$this->year_ind],
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
                        $data['message'] = "<h3 class='text-danger'> The Student ID culumn can not have empty value </h3>";
                    }
                }
            } else {
                $data['message'] = "<h3 class='text-success'> file type not existed </h3>";
            }
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

        $this->dept_code = filter_input(INPUT_POST, "dept_code");
        $this->program = filter_input(INPUT_POST, "program");
        $this->band = filter_input(INPUT_POST, "band");
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        $this->admissionType = filter_input(INPUT_POST, 'admissionType');
        if ($this->dept_code != null && $this->band != null && $this->admissionType != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->get_studentsForIDDesign($this->dept_code, $this->program, $this->band, $this->acyear, $this->admissionType);
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
//            $backinfo = "<label  style='font-size: 12px'>
//                                Woldia University, Ethiopia <br/> </label> 
//                            <label> POBox: 400</label> 
//                            <label> Phone: 0333412341</label> 
//                            <label> Website: www.wldu.edu.et </label><br/> ";

//require_once 'barcode.php';
//  require "./angularservice.php";
// $op = new MySErvice();
            $result = array();
            $band = "";
            if (filter_input(INPUT_GET, "id_number")) {
                $stud_id = filter_input(INPUT_GET, 'id_number');
                $result = $this->ManageStudent_model->get_student_by_id($stud_id);
            } else {
                $dept_code = filter_input(INPUT_GET, 'dept_code');
                $program = filter_input(INPUT_GET, 'program');
                $band = filter_input(INPUT_GET, 'band');
                $acyear = filter_input(INPUT_GET, 'acyear');
                $admissionType = filter_input(INPUT_GET, 'admissionType');
                $result = $this->ManageStudent_model->get_studentsForIDDesign($dept_code, $program, $band, $acyear, $admissionType);
            }

            $count = count($result);
            $html = "";
            $string = '';
            $temp = '';
            $p = 0;
            $p2 = 0;
            $dept = "";
            $pdf->AddPage();
            $mr = $count % 6;
            //to deterime  the number of ID UI below 6 
            $odd_pages = $count - $mr;
            for ($i = 0; $i < $count; $i++) {
                $p++;
                // $print_back = false;
                $row = $result[$i];
                $code = $row['bcID'];
                $id = $row['IDNumber'];
                $fullname = ucwords(strtolower($row['FullName']));
                $admission = $row['Program'];
                $program = $row['admission'];
                $dept = $row['dept_name'];
                $sex = $row['Sex'];
                $band = $row['Year'];

                if (!empty($row['photo'])) {
                    $studentPhoto = base_url() . 'resources/' . $row['photo'];
                } else {
                    $studentPhoto = base_url() . 'resources/icons/user.png';
                }
                $issuedDate = $this->today;
                $txt = "woldia un photo";

                if ($p == 1 || $p == 3 || $p == 5) {
                    $position['x'] = 3;
                } else if ($p == 2 || $p == 4 || $p == 6) {
                    $position['x'] = 107;
                }
                if ($p == 1 || $p == 2) {
                    $position['y'] = 20;
                } else if ($p == 3 || $p == 4) {
                    $position['y'] = 93;
                } else if ($p == 5 || $p == 6) {
                    $position['y'] = 166;
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

                $position['photo_x'] = $position['x'] + 63; //65
                $position['photo_y'] = $position['y'] + 19; //38;
                $position['barcode_x'] = $position['x'] + 2;
                $position['barcode_y'] = $position['y'] + 47;

                $position['footer_x'] = $position['x'];
                $position['footer_y'] = $position['y'] + 63; // 83;

                $pdf->MultiCell(100, 67, "", 1, 'L', '', '', $position['x'], $position['y'], true);
                $pdf->SetFillColor(49, 61, 168);
                // $pdf->SetTextColor(255, 255, 255);
                $pdf->MultiCell(100, 13, $wdu, 1, 'C', TRUE, '', $position['x'], $position['y'], true, 0, true);
                $pdf->SetTextColor(18, 18, 18);
                $pdf->Image(base_url() . 'resources/imgs/wdulogo.png', $position['logo_x'], $position['logo_y'], 20, 20, '', '', '', true, 300, '', false, false, 1, true, false, false);

                // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
                // $pdf->writeHTMLCell(85, 5, $position['name_x'], $position['name_y'], '<b style="text-align:center; background-color:blue">' . $fullname . '</b>', 0);
                //new code line
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(49, 61, 168);
                $pdf->MultiCell(100, 5, $fullname, 1, 'C', TRUE, '', $position['name_x'], $position['name_y'], true, 0, true);

                $pdf->SetTextColor(18, 18, 18);
                $pdf->SetFillColor("#006cad");
                $pdf->writeHTMLCell(60, 5, $position['Id_x'], $position['Id_y'], '<b style="color:Blue">IDNo:</b><u>' . $id . '</u>', 0);
                $pdf->writeHTMLCell(60, 5, $position['sex_x'], $position['sex_y'], '<b style="color:Blue">Sex: </b><u>' . $sex . '</u>' . ' <b style="color:Blue">Program: </b><u>' . $program . '</u>', 0);
                $pdf->writeHTMLCell(70, 5, $position['dept_x'], $position['dept_y'], '<b style="color:Blue">Dept: </b><u>' . $dept . '</u>', 0);
                $pdf->writeHTMLCell(60, 5, $position['issued_x'], $position['issued_y'], '<b style="color:Blue">Admission Type:</b><u>' . $admission . '</u>', 0);
//Image($file, $x='', $y='', $w=0, $h=0,    $type='',   $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
                $pdf->Image($studentPhoto, $position['photo_x'], $position['photo_y'], 40, 36, '', '', '', false, 300, '', false, false, 1, true, false, false);
//write1DBarcode($code, $type, $x='', $y='',   $w='', $h='', $xres='', $style=array(), $align='')
                $pdf->write1DBarcode($code, 'C128A', $position['barcode_x'], $position['barcode_y'], 60, 16, 0.4, $style, 'N');
// $pdf->writeHTMLCell(100, 4, 3, 69, '<b>valid only renewed every semester</b>', 1);
//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
                // $pdf->SetFillColor(189, 32, 37);
                $pdf->SetFont('Times', '', 12);
                $pdf->SetFillColor(255, 255, 255);
                // $pdf->SetFont("abyssinicasil", '', 12);
                $pdf->MultiCell(100, 3, '<b>Nationality: <b style="color:blue">Ethiopian</b> <span style="color:white">......</span>   Registrar ...............</b> ', 1, 'C', true, '', $position['footer_x'], $position['footer_y'], true, 0, true);
                if ($p == 6) {
                    $p = 0;
                    $pdf->AddPage();
                    $print_back = TRUE;
                    $no_back_ui = 6;
                }
                /*
                  $p2++;
                  $position2 = [];
                  if ($mr == 1 && $p == 1 && $i > $odd_pages) {
                  $pdf->AddPage();
                  $print_back = true;
                  $no_back_ui = 1;
                  } elseif ($mr == 2 && $p == 2 && $i > $odd_pages) {
                  $pdf->AddPage();
                  $print_back = true;
                  $no_back_ui = 2;
                  } elseif ($mr == 3 && $p == 3 && $i > $odd_pages) {
                  $pdf->AddPage();
                  $print_back = true;
                  $no_back_ui = 3;
                  } elseif ($mr == 4 && $p == 4 && $i > $odd_pages) {
                  $pdf->AddPage();
                  $no_back_ui = 4;
                  } elseif ($mr == 5 && $p == 5 && $i > $odd_pages) {
                  $pdf->AddPage();
                  $print_back = true;
                  $no_back_ui = 5;
                  }
                 */
                /*

                  if ($print_back) {
                  for ($j = 1; $j <= $no_back_ui; $j++) {
                  if ($j == 1 || $j == 3 || $j == 5) {
                  $position2['x_'] = 107;
                  } else if ($j == 2 || $j == 4 || $j == 6) {
                  $position2['x_'] = 3;
                  }
                  if ($j == 1 || $j == 2) {
                  $position2['y_'] = 20;
                  } else if ($j == 3 || $j == 4) {
                  $position2['y_'] = 93;
                  } else if ($j == 5 || $j == 6) {
                  $position2['y_'] = 166;
                  }
                  $pdf->MultiCell(100, 68, "", 1, 'L', '', '', $position2['x_'], $position2['y_'], true);
                  // $pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);
                  $pdf->SetFillColor(49, 61, 168);
                  $pdf->SetTextColor(255, 255, 255);
                  $pdf->MultiCell(100, 15, $backinfo, 1, 'C', TRUE, '', $position2['x_'], $position2['y_'], true, 0, true);
                  $pdf->SetFillColor(255, 255, 255);
                  $pdf->Image('../wdulogo.png', $position2['x_'] + 1, $position2['y_'] + 1, 20, 24, '', '', '', true, 300, '', false, false, 1, true, false, false);
                  $pdf->SetTextColor(0, 0, 0);
                  $pdf->MultiCell(100, 20, $IDcardBackside, 1, 'L', TRUE, '', $position2['x_'], $position2['y_'] + 20, true, 0, true);
                  $pdf->SetFillColor(189, 32, 37);
                  $pdf->MultiCell(100, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $position2['x_'], $position2['y_'] + 64, true, 0, true);
                  ///“አእምሮ ሲከፈት ዓይኖች ማየት ይችላሉ”
                  }
                 */

//            if (($count - 1) != $i) {
//                $pdf->AddPage();
//            }
            }
            // }

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
        $dept_code = filter_input(INPUT_GET, 'dept_code');
        $program = filter_input(INPUT_GET, 'program');
        $band = filter_input(INPUT_GET, 'band');
        $acyear = filter_input(INPUT_GET, 'acyear');
        $admissionType = filter_input(INPUT_GET, 'admissionType');

        $result = $this->ManageStudent_model->get_studentsForIDDesign($dept_code, $program, $band, $acyear, $admissionType);
        $count = count($result);

        $p = 0;
        $wdu = '<label style="margin: 0px 0px 0px 0px; font-size: 14px;color:white" > <b>Woldia University</b>  <br/> Student ID Card</label> ';
        $backinfo = "<label  style='font-size: 12px'>Woldia University, Ethiopia  </label> <br/>
                            <label> POBox:400</label> 
                            <label> Phone:0335400706</label>  <br/>                           
                            <label>Website: www.wldu.edu.et</Label>";

        $IDcardBackside = '<table border="1" style="border-collapse: collapse; width: 97mm; height:49mm">
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
        $masasebiya = "<ol>ማሳሰቢያ:"
                . "<li>በዩኒቨርሲቲው በትምህርት ላይ እስካሉ ድረስ ካርዱ እንዳይለይዎት::</li>"
                . "<li>ካርዱ ቢጠፋ/ቢበላሽ አሳዉቆ በምትኩ ሌላ ማግኘት ይቻላል:: </li>"
                . "<li>ትምህርትዎን ሲጨርሱ/ሲያቋርጡ ካርዲን ማስረከብ አለብዎት::</li> </ol>";

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
            if ($p == 1 || $p == 3 || $p == 5) {
                $position2['x_'] = 107;
            } else if ($p == 2 || $p == 4 || $p == 6) {
                $position2['x_'] = 3;
            }
            if ($p == 1 || $p == 2) {
                $position2['y_'] = 20;
            } else if ($p == 3 || $p == 4) {
                $position2['y_'] = 93;
            } else if ($p == 5 || $p == 6) {
                $position2['y_'] = 166;
            }
            $pdf->MultiCell(100, 68, "", 1, 'L', '', '', $position2['x_'], $position2['y_'], true);
            // $pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);
            $pdf->SetFillColor(49, 61, 168);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->MultiCell(100, 15, $backinfo, 1, 'C', TRUE, '', $position2['x_'], $position2['y_'], true, 0, true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Image(base_url() . 'resources/icons/wdulogo.png', $position2['x_'] + 1, $position2['y_'] + 1, 20, 24, '', '', '', true, 300, '', false, false, 1, true, false, false);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont("abyssinicasil", '', 10);

            $pdf->MultiCell(100, 20, $IDcardBackside, 1, 'L', TRUE, '', $position2['x_'], $position2['y_'] + 15, true, 0, true);

            $pdf->SetFont("abyssinicasil", '', 6);
            $pdf->MultiCell(95, 20, $masasebiya, 0, 'L', TRUE, '', $position2['x_'] + 3, $position2['y_'] + 52, true, 0, true);

            $pdf->SetFont('Times', '', 10);
            $pdf->SetFillColor(189, 32, 37);
            $pdf->MultiCell(100, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $position2['x_'], $position2['y_'] + 64, true, 0, true);
            ///“አእምሮ ሲከፈት ዓይኖች ማየት ይችላሉ” 
            if ($p == 6) {
                $p = 0;
                $pdf->AddPage();
                // $print_back = TRUE;
                // $no_back_ui = 6;
            }
        }
        $today = Date("Y-m-d H-i-s");
        $filename = "id_card_back_side" . $today;
        ob_end_clean();
        $pdf->Output($filename, 'D');
    }

    function assign_students_to_cafeteria() {
        if (filter_input(INPUT_POST, "getStudentsGroupedbyDepartment")) {
            $this->program = filter_input(INPUT_POST, 'program');
            $this->acyear = filter_input(INPUT_POST, 'acyear');
            $this->admissionType = filter_input(INPUT_POST, 'admissionType');

            $result = $this->ManageStudent_model->getStudentsGroupedByDepartment($this->acyear, $this->program, $this->admissionType);
            echo json_encode($result);
            exit();
        } else if (filter_input(INPUT_POST, 'assignstudents_to_cafe') == "assignstudents_to_cafe") {

            $dataString = filter_input(INPUT_POST, 'depwithBand');
            $dataArraylist = json_decode($dataString);
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";


            foreach ($dataArraylist as $row) {
                if ($row->CafeNumber != null) {
                    $studresult = $this->ManageStudent_model->getStudentsByCriteria($row->dept_code, $row->Year, $row->acyear, $row->program, $row->semester, $row->CafeNumber);
                    foreach ($studresult as $stud) {
                        $id = $stud['IDNumber'];
                        $bcode = $stud['bcID'];
                        $exist = $this->ManageStudent_model->getByID($id);
                        $result = $this->ManageStudent_model->assignStudentsToCafteria($id, $row->CafeNumber, $bcode, $exist, $row->acyear, $row->semester);
                    }
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
            $pd = filter_input(INPUT_POST, 'lastDate');
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
            $this->program = filter_input($type, 'program');
            $this->band = filter_input($type, 'band');
            $this->acyear = filter_input($type, 'acyear');
            if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
                $result = $this->ManageStudent_model->get_students_to_Controll_gate($this->dept_code, $this->program, $this->band, $this->acyear);
            } else {
                $result['message'] = "please enter valid value";
                $result['status'] = "failed";
            }
            echo json_encode($result);
            exit();
        }
        $data['_view'] = "studData/manage_student_to_gate";
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

        $this->dept_code = filter_input(INPUT_POST, 'dept_code');
        $this->program = filter_input(INPUT_POST, 'program');
        $this->band = filter_input(INPUT_POST, 'band');
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->deactivate_all_students($this->dept_code, $this->program, $this->band, $this->acyear);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }


        echo json_encode($result);
    }

    function activate_all() {

        $this->dept_code = filter_input(INPUT_POST, 'dept_code');
        $this->program = filter_input(INPUT_POST, 'program');
        $this->band = filter_input(INPUT_POST, 'band');
        $this->acyear = filter_input(INPUT_POST, 'acyear');
        $result = [];
        if ($this->dept_code != null && $this->band != null && $this->acyear != null && $this->program != null) {
            $result = $this->ManageStudent_model->activate_all_students($this->dept_code, $this->program, $this->band, $this->acyear);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    function delete_student() {
        $data = ["message"=>"Something went wrong","status"=>"faild" ];
        if (filter_input(INPUT_POST, "IDNumber")) {
            $stud_id = filter_input(INPUT_POST, 'IDNumber');
            //$where=array("id_Number"=>$stud_id);
            $update_data = array("is_active" => "InActive");
           $status= $this->ManageStudent_model->update_student($update_data, $stud_id);
           if($status){
            $data['message'] = "Operation Done Successfully ";
            $data['status'] = "ok"; 
            echo json_encode($data); 
           }else{
             echo json_encode($data);  
           }          
            
        } else {
            echo json_encode($data);
        }
    }

}


