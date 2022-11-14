<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IDCard extends SupperCtr {

    var $link, $barcodeID, $departureDate, $turnbackDate, $reason, $auto_ID, $SID;
    var $dept, $program, $admissionType, $band, $acyear, $semester;
    var $today, $current_time, $date_time, $username;
    var $fn_ind, $mn_ind, $ln_ind, $sex_ind, $id_ind, $faculty_ind, $department_ind;
    var $admissionType_ind, $program_ind, $year_ind;
    var $faculty_code;
    var $wdu = '<label style="margin: 0px 0px 0px 0px; font-size: 14px;color:white" > <b>Woldia University</b>  <br/> Student ID Card</label> ';
    var $backinfo = "<label> <b>Woldia University, Ethiopia </b> </label> <br/>
                            <label><b> POBox:400 </b></label>   <label><b> Phone:0335400706 </b></label>  <br/> <label> <b>Website: www.wldu.edu.et</b></Label>";
    var $IDcardBackside = '<table border="1" style="border-collapse: collapse; width: 95mm; height:49mm">
                             <tr> <th colspan="3" style="text-align:center"> Invalid unless renewed/በየሰሚስተሩ ካልታደሰ አያገለግልም </th> </tr> 
                            <tr> <th style="text-align:right;" rowspan="2">Academic Year </th>
                            <th colspan="2" style="text-align:center;"> Renewal</th></tr>  
                            <tr style="height:30px">     <th> Semester I </th>   <th> Semester II  </th></tr>                                 
                              <tr>  <td style="text-align:right;">20........E.C </td>   <td> </td>  <td> </td>    </tr>
                                <tr> <td style="text-align:right;">20........E.C </td> <td> </td><td> </td> </tr>
                            <tr>  <td style="text-align:right;">20........E.C </td>   <td> </td>    <td> </td>  </tr>
                              <tr><td style="text-align:right;">20........E.C </td>  <td> </td>  <td> </td>  </tr>
                               
                                     </table>';
    var $masasebiya ="<ol>ማሳሰቢያ:"
            . "<li>በዩኒቨርሲቲው በትምህርት ላይ እስካሉ ድረስ ካርዱ እንዳይለይዎት::</li>"
            . "<li>ካርዱ ቢጠፋ/ቢበላሽ አሳዉቆ በምትኩ ሌላ ማግኘት ይቻላል:: </li>"
            . "<li>ትምህርትዎን ሲጨርሱ/ሲያቋርጡ ካርዲን ማስረከብ አለብዎት::</li> </ol>";
    var $pdf = null;
    var $position = [];
    var $position2 = [];

    public function __construct() {
        parent::__construct();
        $this->load->model("ManageStudent_model");
        $this->load->model("BasicData_model");
        $this->username = $this->user->email;
    }

    private function get_faculties() {

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
        $section = filter_input(INPUT_POST, 'section');
       
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
          if (!empty($section)) {
          $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active', 'st.section_number' => $section);
          }

         
         //$where=array('st.dept_code'=>$dept_code, 'st.Program'=>$admissionType,  'st.Year'=>$band,  'st.acyear'=>$acyear,  'st.admission'=>$program, 'st.is_active'=>'Active' );
       // $where = $this->collect_parameters();
        $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
        } else {
            $result['message'] = "please enter valid value";
            $result['status'] = "failed";
        }

        echo json_encode($result);
    }

    private function set_positions($p) {
        if ($p == 1 || $p == 3 || $p == 5) {
            $this->position['x'] = 5;
        } else if ($p == 2 || $p == 4 || $p == 6) {
            $this->position['x'] = 110;
        }
        if ($p == 1 || $p == 2) {
            $this->position['y'] = 15;
        } else if ($p == 3 || $p == 4) {
            $this->position['y'] = 98;
        } else if ($p == 5 || $p == 6) {
            $this->position['y'] = 181;
        }
        $this->position['logo_x'] = $this->position['x'] + 3;
        $this->position['logo_y'] = $this->position['y'] + 1;
        $this->position['name_x'] = $this->position['Id_x'] = $this->position['dept_x'] = $this->position['sex_x'] = $this->position['issued_x'] = $this->position['x'];
        $this->position['name_y'] = $this->position['y'] + 13;
        $this->position['Id_y'] = $this->position['y'] + 19;
        $this->position['sex_y'] = $this->position['y'] + 24; //48;
        $this->position['dept_y'] = $this->position['y'] + 29; //43
        $this->position['issued_y'] = $this->position['y'] + 39; // 58;
        $this->position['photo_x'] = $this->position['x'] + 61; //65
        $this->position['photo_y'] = $this->position['y'] + 19; //38;
        $this->position['barcode_x'] = $this->position['x'] + 5;
        $this->position['barcode_y'] = $this->position['y'] + 44;
        $this->position['footer_x'] = $this->position['x'];
        $this->position['footer_y'] = $this->position['y'] + 60; // 83;
    }

    private function set_positions_($p) {
        if ($p == 1 || $p == 3 || $p == 5) {
            $this->position2['x_'] = 107;
        } else if ($p == 2 || $p == 4 || $p == 6) {
            $this->position2['x_'] = 3;
        }
        if ($p == 1 || $p == 2) {
            $this->position2['y_'] = 15;
        } else if ($p == 3 || $p == 4) {
            $this->position2['y_'] = 98;
        } else if ($p == 5 || $p == 6) {
            $this->position2['y_'] = 181;
        }
    }

    function print_id_card() {
        if ($this->input->post("getfac")) {
            $this->get_faculties();
            exit();
        }
      //  echo $this->input->get("print_back_side");
      //  exit();
      $print_back_side=$this->input->get("print_back_side");
        if($print_back_side=="12345qwertr"){
        //    echo "welcome";
          $this->print_ID_card_back_side();
            
            exit(); 
        }
        $for_group = false;
        if ($this->input->get('print_id')) {
            $this->set_pdf_config();
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
                $where = array('print_status' => 0, 'r.is_deleted' => 0, 'r.created_by' => $this->username);
                $result = $this->ManageStudent_model->get_id_card_requests($where);
                $for_group = true;
            } else {
                $where = $this->collect_parameters();           
                $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
            }
            $this->set_pdf_config();
            $count = count($result);
            $p = 0;
            $dept = "";
            $fontsize = 12;
            for ($i = 0; $i < $count; $i++) {
                $p++;
                $this->set_positions($p);
                $this->pdf->SetFont('Times', '', 12);
                $row = $result[$i];
                $code = $row['bcID'];
                $id = $row['IDNumber'];
                $fullname ='<b>'. ucwords(strtolower($row['FullName'])).'</b>';
                $admission = $row['admission'];
                $program = $row['Program'];
                $dept = $row['dept_name'];
                $sex = $row['Sex'];
                $band = $row['Year'];
                $nationality = $row['nationality'];
                if (strlen($dept) > 57) {
                    $fontsize = 10;
                }
                if (!empty($row['photo'])) {
                    $studentPhoto = base_url() . $row['photo'];
                } else {
                    $studentPhoto = base_url() . 'resources/icons/user.png';
                }
				//rgb(14, 174, 223)
                $this->pdf->MultiCell(96, 66, "", 1, 'L', '', '', $this->position['x'], $this->position['y'], true);
                $this->pdf->SetFillColor(49, 61, 168);
			  //rgb(88, 164, 188)
			 // $this->pdf->SetFillColor(88, 168, 188);
                $this->pdf->MultiCell(96, 13, $this->wdu, 1, 'C', TRUE, '', $this->position['x'], $this->position['y'], true, 0, true);
                $this->pdf->SetTextColor(18, 18, 18);
                $this->pdf->Image(base_url() . 'resources/imgs/wdulogo.png', $this->position['logo_x'], $this->position['logo_y'], 20, 18, '', '', '', true, 300, '', false, false, 1, true, false, false);

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
// $pdf->writeHTMLCell(85, 5, $position['name_x'], $position['name_y'], '<b style="text-align:center; background-color:blue">' . $fullname . '</b>', 0);
//new code line
                $this->pdf->SetTextColor(255, 255, 255);
              //  $this->pdf->SetFillColor(49, 61, 168);
			  
                $this->pdf->SetTextColor(18, 18, 18);
                $this->pdf->SetFillColor(255, 255, 255);				 
                $this->pdf->MultiCell(95, 5, $fullname, 0, 'L', TRUE, '', $this->position['name_x'], $this->position['name_y'], true, 0, true);
                $this->pdf->SetTextColor(18, 18, 18);
                $this->pdf->SetFillColor("#006cad");
                $this->pdf->writeHTMLCell(60, 5, $this->position['Id_x'], $this->position['Id_y'], '<b style="color:Blue">IDNo:</b><u>' . $id . '</u>', 0);
                $this->pdf->writeHTMLCell(60, 5, $this->position['sex_x'], $this->position['sex_y'], '<b style="color:Blue">Sex: </b><u>' . $sex . '</u>' . ' <b style="color:Blue">Program: </b><u>' . $program . '</u>', 0);
                $this->pdf->writeHTMLCell(65, 5, $this->position['dept_x'], $this->position['dept_y'], '<b style="color:Blue;">Dept: </b><u style="font-size:' . $fontsize . '">' . $dept . '</u>', 0);
                $this->pdf->writeHTMLCell(60, 5, $this->position['issued_x'], $this->position['issued_y'], '<b style="color:Blue">Admission Type:</b><u>' . $admission . '</u>', 0);
//Image($file, $x='', $y='', $w=0, $h=0,    $type='',   $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())

                if (empty($row['photo'])) {
                    $this->pdf->SetFillColor("#006cad");
                    $this->pdf->MultiCell(35, 36, ' ', 1, 'C', false, '', $this->position['photo_x'], $this->position['photo_y'], true, 0, true);
                } else {
                    $this->pdf->Image($studentPhoto, $this->position['photo_x'], $this->position['photo_y'], 40, 36, '', '', '', false, 300, '', false, false, 1, true, false, false);
                }
//QRCODE,H
//PDF417
//write1DBarcode($code, $type, $x='', $y='',   $w='', $h='', $xres='', $style=array(), $align='')
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $this->position['barcode_x'], $this->position['barcode_y'], 16, 16,  $style, 'N');
				// $this->pdf->write2DBarcode($code, 'PDF417', $this->position['barcode_x'], $this->position['barcode_y'], 58, 16, 0.4, $style, 'N');
				
// $pdf->writeHTMLCell(100, 4, 3, 69, '<b>valid only renewed every semester</b>', 1);
//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
// $pdf->SetFillColor(189, 32, 37);
                $this->pdf->SetFont('Times', '', 12);
				//rgb(208, 213, 212 )
                $this->pdf->SetFillColor(208, 213, 212);
// $pdf->SetFont("abyssinicasil", '', 12);
                $this->pdf->MultiCell(96, 3, '<b>Nationality:<b style="color:blue">' . $nationality . '</b> <span style="color:white">......</span>   Registrar ...............</b> ', 1, 'C', true, '', $this->position['footer_x'], $this->position['footer_y'], true, 0, true);
                if ($p == 6 && $i !== $count - 1) {
                    $p = 0;
                    $this->pdf->AddPage();
                }

                if (($i < $count - 2) && !$for_group) {
                    $next_row = $result[$i + 1];
                    if ($next_row['dept_code'] != $row['dept_code']) {
                        $p = 0;
                        $this->pdf->AddPage();
                    }
                }
            }
            /*
            if ($single_print_back) {
                $this->pdf->SetFont('Times', '', 10);
                $this->pdf->AddPage();
                $this->position2['x_'] = 106;
                $this->position2['y_'] = 15;
                $this->pdf->MultiCell(96, 66, "", 1, 'L', '', '', $this->position2['x_'], $this->position2['y_'], true);
//  $this->pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);
                $this->pdf->SetFillColor(49, 61, 168);
                $this->pdf->SetTextColor(255, 255, 255);
                $this->pdf->MultiCell(96, 15, $this->backinfo, 1, 'C', TRUE, '', $this->position2['x_'], $this->position2['y_'], true, 0, true);
                $this->pdf->SetFillColor(255, 255, 255);
                $this->pdf->Image(base_url() . 'resources/icons/wdulogo.png', $this->position2['x_'] + 1, $this->position2['y_'] + 1, 20, 22, '', '', '', true, 300, '', false, false, 1, true, false, false);
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pdf->SetFont("abyssinicasil", '', 10);
                $this->pdf->MultiCell(96, 20, $this->IDcardBackside, 1, 'L', TRUE, '', $this->position2['x_'], $this->position2['y_'] + 15, true, 0, true);
                $this->pdf->SetFont("abyssinicasil", '', 6);
                $this->pdf->MultiCell(94, 20, $this->masasebiya, 0, 'L', TRUE, '', $this->position2['x_'] + 3, $this->position2['y_'] + 52, true, 0, true);
                $this->pdf->SetFont('Times', '', 10);
                $this->pdf->SetFillColor(189, 32, 37);
                $this->pdf->MultiCell(96, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $this->position2['x_'], $this->position2['y_'] + 64, true, 0, true);
            }
*/
            $today = Date("Y-m-d H-i-s");
            $filename = "IDcard_front_" . $dept . '_' . $band . '_' . $program . '_' . $today . '.pdf';
            ob_end_clean();
            $this->pdf->Output($filename, 'D');
        } else if ($this->input->post('preview_students_to_print_id_card')) {
            $this->get_student_to_prepare_IDCard();
            exit();
        } else {
            $data['_view'] = "studData/prepareIDCard";
            $this->load->view("layouts/main", $data);
        }
    }

    private function set_pdf_config() {
        $this->load->library('Pdf');
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // $this->pdf->SetMargins(0, 10, 0, true);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $this->pdf->setLanguageArray($l);
        }
        $this->pdf->AddPage();
    }

    private function collect_parameters() {
        $this->faculty_code = filter_input(INPUT_GET, "faculty_code");
        $this->dept_code = filter_input(INPUT_GET, "dept_code");
        $this->program = filter_input(INPUT_GET, "program");
        $this->band = filter_input(INPUT_GET, "band");
        $this->acyear = filter_input(INPUT_GET, 'acyear');
        $this->admissionType = filter_input(INPUT_GET, 'admissionType');
		  $section = filter_input(INPUT_POST, 'section');
       
          $where = array('st.is_active' => 'Active');
          if ($this->dept_code != null && $this->band != null && $this->admissionType != null && $this->acyear != null && $this->program != null)
		{
          if ($this->dept_code == 0 && $this->band == 0) {
          $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
          } else if ($this->dept_code != 0 && $this->band == 0) {
          $where = array('st.dept_code' => $this->dept_code, 'st.Program' => $this->program, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
          } else if ($this->dept_code == 0 && $this->band != 0) {
          $where = array('dept.faculity_code' => $this->faculty_code, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
          } else if ($this->dept_code != 0 && $this->band != 0) {
          $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active');
          }
          if (!empty($section)) {
          $where = array('st.dept_code' => $this->dept_code, 'st.Year' => $this->band, 'st.Program' => $this->program, 'st.Year' => $this->band, 'st.acyear' => $this->acyear, 'st.admission' => $this->admissionType, 'st.is_active' => 'Active', 'st.section_number' => $section);
          }
	
		
        return $where;
    }
	}

    function print_ID_card_back_side() {
		
		
        $for_group = false;
        if ($this->input->get("print_id_back_side_for_group")) {
            $where = array('print_status' => 0, 'r.is_deleted' => 0, 'r.created_by' => $this->username);
            $result = $this->ManageStudent_model->get_id_card_requests($where);
            $for_group = true;
        } else {
            $where = $this->collect_parameters();
            $result = $this->ManageStudent_model->get_studentsForIDDesign($where);
        }
        $count = count($result);
        $p = 0;
        $this->set_pdf_config();
		  $style = array('position' => '', 'align' => 'C', 'stretch' => FALSE, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => 'auto', 'vpadding' => 'auto', 'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, 'text' => false, 'font' => 'helvetica', 'fontsize' => 10, 'stretchtext' => 4);
           
        for ($i = 0; $i < $count; $i++) {
            $p++;
            $row = $result[$i];
			 $code = $row['bcID'];
			 
            $this->set_positions_($p);
            $this->pdf->SetFont('Times', '', 9);
            $this->pdf->MultiCell(96, 64, "", 1, 'L', '', '', $this->position2['x_'], $this->position2['y_'], true);
            //  $this->pdf->MultiCell(95, 48, "", 1, 'L', '', '', 3, 40, true);           
           $this->pdf->SetFillColor(49, 61, 168);
			 // $this->pdf->SetFillColor(88, 168, 188);
            $this->pdf->SetTextColor(255, 255, 255);
            $this->pdf->MultiCell(96, 14, $this->backinfo, 1, 'C', TRUE, '', $this->position2['x_'], $this->position2['y_'], true, 0, true);
            $this->pdf->SetFillColor(255, 255, 255);
            $this->pdf->Image(base_url() . 'resources/icons/wdulogo.png', $this->position2['x_'] + 3, $this->position2['y_'] + 1, 20, 24, '', '', '', true, 300, '', false, false, 1, true, false, false);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->SetFont("abyssinicasil", '', 9);
            $this->pdf->MultiCell(96, 20, $this->IDcardBackside, 1, 'L', TRUE, '', $this->position2['x_'], $this->position2['y_'] + 15, true, 0, true);
            $this->pdf->SetFont("abyssinicasil", '', 6);
            $this->pdf->MultiCell(90, 20, $this->masasebiya, 0, 'L', TRUE, '', $this->position2['x_'] + 1, $this->position2['y_'] + 46, true, 0, true);
            $this->pdf->SetFillColor(189, 112, 37);
			
			//$this->pdf->MultiCell(30, 16, $code, 0, 'L', TRUE, '', $this->position2['x_'] + 65, $this->position2['y_'] + 45, true, 0, true);
           
			$this->pdf->write1DBarcode($code, 'C128A', $this->position2['x_'] + 60, $this->position2['y_'] + 45, 35, 15, 0.4, $style, 'N');
			$this->pdf->SetFont('Times', '', 8);
            $this->pdf->SetFillColor(189, 32, 37);
            $this->pdf->MultiCell(96, 4, '<b style="color:white;"> Open mind open eyes </b>', 1, 'C', true, '', $this->position2['x_'], $this->position2['y_'] + 62, true, 0, true);
            if ($p == 6 && $i !== $count - 1) {
                $p = 0;
                $this->pdf->AddPage();
            }

            if (($i < $count - 2) && !$for_group) {
                $next_row = $result[$i + 1];
                if ($next_row['dept_code'] != $row['dept_code']) {
                    $p = 0;
                    $this->pdf->AddPage();
                }
            }
        }

        $today = Date("Y-m-d H-i-s");
        $filename = "id_card_back_side" . $this->dept_code . '_' . $this->band . '_' . $this->program . '_' . $this->today . '.pdf';
        ob_end_clean();
        $this->pdf->Output($filename, 'D');
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
            redirect("IDCard/save_id_card_request");
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

        $where = array('print_status !=' => 2, 'r.is_deleted' => 0, 'r.created_by' => $this->username);
        $data['requests'] = $this->ManageStudent_model->get_id_card_requests($where);
        $data['_view'] = "studData/prepareIDCard_for_group";
        $this->load->view("layouts/main", $data);
    }

}
