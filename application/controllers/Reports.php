<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends SupperCtr {

    public $leaveToday = 0;
    public $returnToday = 0;
    public $returnTomorrow = 0;
    public $returnAfterTomorrow = 0;
    public $withFamily = 0, $time;

    public function __construct() {
        parent::__construct();

        $this->load->model("Report_model");
    }

    function filter_from_to_report() {
        if (isset($_POST['From'], $_POST['To'])) {
            $intial = $_POST['From'];
            $destination = $_POST['To'];
            $cafenumber = $_POST['cafenumber'];
            $responce = $this->Report_model->display_filterd_report($intial, $destination, $cafenumber);
            echo $responce;
            exit();
        }

        $data['_view'] = "reports/filter";
        $this->load->view("layouts/main", $data);
    }
    function get_lost_id_report(){        
        $data['students_per_bach'] = $this->Report_model->get_student_lost_id_card();
        $data['_view'] = "reports/lost_id_cards";
        $this->load->view("layouts/main", $data);
    }

    function get_monthly_report() {
        if (isset($_POST['year'], $_POST['month'])) {
            $selected_year = $_POST['year'];
            $selected_month = $_POST['month'];
            $cafenumber = $_POST['cafenumber'];
            $responce = $this->Report_model->display_month_report($selected_year, $selected_month, $cafenumber);
            echo $responce;
            exit();
        }
        $data['_view'] = "reports/monthly";
        $this->load->view("layouts/main", $data);
    }

    function get_one_day_report() {
        if (isset($_POST['year1'], $_POST['month1'], $_POST['dates1'])) {
            $year = $_POST['year1'];
            $month = $_POST['month1'];
            $date = $_POST['dates1'];
            $cafenumber = $_POST['cafenumber'];

            $respoce1 = $this->Report_model->display_day_report($year, $month, $date, $cafenumber);
            echo $respoce1;
            exit();
        }
        $data['_view'] = "reports/daily";
        $this->load->view("layouts/main", $data);
    }

    function get_yearly_report() {

        if (isset($_POST['yearonly'])) {
            $year = $_POST['yearonly'];
            $cafenumber = $_POST['cafenumber'];
            $responce = $this->Report_model->display_year_report($year, $cafenumber);
            echo $responce;
            exit();
        }

        $data['_view'] = "reports/yearly";
        $this->load->view("layouts/main", $data);
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

    function get_live_stastistics() {
        $this->compute_prediction();
        $query = "SELECT ci.CafeNumber,  count(ci.CafeNumber) as number_of_students  FROM `cafeinfo` ci inner join students st on st.bcID=ci.IDNumber where (st.is_active='Active' and ci.Status='Cafe')  GROUP BY ci.CafeNumber";
        $results = $this->db->query($query)->result_array();
        $data['num_of_students'] = $results;      
        $this->db->select("ci.CafeNumber,  count(ci.CafeNumber) as number_of_students");
        $this->db->from("exceptionalstudent es");
        $this->db->join("students st", "st.bcID=es.barcode");
        $this->db->join("cafeinfo ci", "st.bcID=ci.IDNumber");
        $this->db->where(array("st.is_active" => 0, "es.permissionStatus" => 1));
        $this->db->group_by("ci.CafeNumber");
        $result2 = $this->db->get()->result_array();
       
        
        
        
         $sum_of_stud = 0;
        // $numberofstudents=0;
        foreach ($results as $row) {
         $numberofstudents=   $row['number_of_students'];
              foreach ($result2 as $t){
                   if($row["CafeNumber"]==$t['CafeNumber'] ){
                       $numberofstudents=$numberofstudents + $t['number_of_students'];
                       
                   }
               }
            
            
            $sum_of_stud = $sum_of_stud + $numberofstudents;
        }
        
        

        $hours = strtotime(date("h:i:s a"));
        $breakfast_start = strtotime("6:05:00 am");
        $breakfast_end = strtotime("9:30:00 am");
        $lunch_start = strtotime("10:50:00 am");
        $lunch_end = strtotime("2:59:00 pm");
        $dinner_start = strtotime("3:50:00 pm");
        $dinner_end = strtotime("7:30:00 pm");
        $mealtime = 'brakefast';
        if ($hours <= $breakfast_end && $hours >= $breakfast_start) {
            $mealtime = 'brakefast';
        } elseif ($hours >= $lunch_start && $hours <= $lunch_end) {
            $mealtime = 'lunch';
        } elseif ($hours <= $dinner_end && $hours >= $dinner_start) {
            $mealtime = 'dinner';
        }
        $data["meal_time"] = $mealtime;
        $cat = $mealtime . "=1";
        $ate = $this->db->query("select m.* from mealcard m inner join students st on st.bcID=m.IDNumber  where (st.is_active='Active' and m.Date = '$this->today' ) and $cat ")->result_array();
        $data["ate"] = count($ate);
        $data["expected_consumers"] = $sum_of_stud - $this->returnTomorrow - $this->returnAfterTomorrow - $this->withFamily;
        $data["not_ate"] = $data["expected_consumers"] - $data["ate"];
        $data['_view'] = "reports/live_statistics";
        $this->load->view("layouts/main", $data);
    }

    function get_audit_report() {

        $data['_view'] = "reports/audit_page";
        $this->load->view("layouts/main", $data);
    }

    function get_consuption_report() {

        $data['_view'] = "reports/audit_page";
        $this->load->view("layouts/main", $data);
    }

    function get_student_count_per_bach() {

        $data['students_per_bach'] = $this->Report_model->get_student_count_per_bach();
        $data['_view'] = "reports/get_student_count_per_bach";
        $this->load->view("layouts/main", $data);
    }

    function get_none_cafe_students() {
        $data['none_cafe_students'] = $this->Report_model->get_none_cafe_students();
        $data['_view'] = "reports/get_none_cafe_students";
        $this->load->view("layouts/main", $data);
    }

    function get_cafeteria_audit() {
        $data['records'] = $this->Report_model->get_cafeteria_audit();
        $data['_view'] = "reports/get_cafe_audit";
        $this->load->view("layouts/main", $data);
    }

//
//echo "<table class='table' width=50% align=center border=1 style='color:navy ; font-family:vijaya; border-collapse:collapse;font-size:20px'>
//    <tr><th colspan=2>Students in each Cafeteria </th></tr>
//    <tr><th>ካፍቴሪያ  </th><th>የተመደቡ ተማሪዎች ብዛት</th></tr>";
//for ($i = 0;$i < sizeof($cafeterias);$i++) {
//$query = "SELECT  `CafeNumber` FROM `cafeinfo` WHERE CafeNumber LIKE '$cafeterias[$i]'";
//$result = mysqli_query($con, $query)or die(mysqli_error($con));
//$numrows = mysqli_num_rows($result);
//echo "<tr><td align=center>" . $cafeterias[$i] . " </td><td align=center> " . $numrows . " </td></tr>";
//}
//echo "<tr '><td align=center style='font-family:vijaya; font-size:30px; color:#0066FF'>"
//. " ካፍቴሪያ  " . "\t\t" . $cafenumber . "</td>"
//. "<td  id=mealstatus style='color:black;font-size:30px;font-family:vijaya; color:#0066FF'>    </td>"
//. "</tr></table>";
//}
//
}
