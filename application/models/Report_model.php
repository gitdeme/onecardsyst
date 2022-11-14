<?php

class Report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function display_year_report($year, $cafenumber) {
        $tempfiles = '';
        $breakfast = array();
        $lunch = array();
        $dinner = array();
        $total_meal = array();
        $nbreakfast = array();
        $nlunch = array();
        $ndinner = array();
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'october', 'November', 'December');
        for ($i = 1; $i < 13; $i++) {
            $dnr = 0;
            $brk = 0;
            $lnch = 0;
            if ($i < 10) {
                $month = "0" . $i;
            } else
                $month = $i;

            $year_month = $year . "-" . $month;

            if ($cafenumber == '') {
                $query = "SELECT  * FROM `mealcard` WHERE  Date LIKE '%$year_month%' ";
                //  $query2 = "SELECT ci.*, st.is_active FROM cafeinfo ci INNER JOIN students st on st.IDNumber=ci.Id_Number WHERE   (ci.Status='Cafe' AND ci.registration_date LIKE '%$selected_year%') and st.is_active='Active'";

                $query2 = "SELECT ci.*, st.is_active FROM cafeinfo ci INNER JOIN students st on st.IDNumber=ci.Id_Number WHERE   (Status='Cafe' AND registration_date LIKE '%$year%') and st.is_active='Active'";
            } else {
                //SELECT stud.IDNumber,stud.Year,assignedstud.IDNumber FROM assignedstudents
                //as assignedstud INNER JOIN students as stud ON stud.IDNumber=assignedstud.IDNumber WHERE stud.Year='5'")
                $query = "SELECT meal.*,cafinfo.IDNumber  FROM mealcard as meal INNER JOIN cafeinfo as cafinfo ON meal.IDNumber=cafinfo.IDNumber WHERE  meal.Date LIKE '%$year_month%'  AND cafinfo.CafeNumber='$cafenumber' ";
                //  $query2 = "SELECT ci.*, st.is_active  FROM cafeinfo ci INNER JOIN students st on ci.Id_Number=st.IDNumber WHERE   (Status='Cafe' AND CafeNumber='$cafenumber') AND (registration_date LIKE '%$selected_year%'  and st.is_active='Active')";

                $query2 = "SELECT ci.*, st.is_active FROM cafeinfo ci INNER JOIN students st on ci.Id_Number=st.IDNumber WHERE   (Status='Cafe' AND CafeNumber='$cafenumber') AND (registration_date LIKE '%$year%') and st.is_active='Active'";
            }
            $query_result = $this->db->query($query)->result_array(); //mysqli_query($this->link, $query) or die(mysql_error($this->link));
            array_push($total_meal, count($query_result));
            $query_result2 = $this->db->query($query2)->result_array(); //mysqli_query($this->link, $query2) or die(mysql_error($this->link));
            $numrows = count($query_result2);
            foreach ($query_result as $rows) {
                if ($rows['brakefast'] == 1)
                    $brk++;
                if ($rows['lunch'] == 1)
                    $lnch++;
                if ($rows['dinner'] == 1)
                    $dnr++;
            }
            array_push($breakfast, $brk);
            array_push($lunch, $lnch);
            array_push($dinner, $dnr);
            array_push($nbreakfast, $numrows - $brk);
            array_push($nlunch, $numrows - $lnch);
            array_push($ndinner, $numrows - $dnr);
        }
        $tempfiles = $tempfiles . "<table width=100% border=1 style='border-collapse: collapse'>            
            <tr bgcolor='#FFFFCE' ><th colspan='9' align='center' style='color:#333399'>" . $year . " Cafeteria " . $cafenumber . " Report </th></tr>
             <tr><th  rowspan=2>Months</th>
              <th colspan=2>Breakfast</th>
              <th colspan=2>Lunch</th>
              <th colspan=2>Dinner</th>
             <th colspan=2>Total</th>
             </tr>
        <tr style='background-image:url(images/bgimage.png);'>
            <th>#S ate</th><th>#S no ate </th>
            <th>#S ate</th><th>#S no ate</th>
            <th>#S ate</th><th>#S no ate </th>
            <th>#S ate</th><th>#S no ate </th></tr>";

        for ($k = 0; $k < sizeof($breakfast); $k++) {
            if ($k % 2 == 0)
                $trbg = '#CEDDB3';
            else
                $trbg = '#FFFFFF';
            $tempfiles = $tempfiles . " <tr bgcolor=$trbg><td>" . $months[$k] . "</td>
                        <td align='center'>" . $breakfast[$k] . "</td> <td align='center'><font color='#800000'>" . $nbreakfast[$k] . "</font></td>
                        <td align='center'>" . $lunch[$k] . " <td align='center'><font color='#800000'>" . $nlunch[$k] . "</font></td>
                        <td align='center'>" . $dinner[$k] . "</td><td align='center'><font color='#800000'>" . $ndinner[$k] . "</font></td>
                        <td align='center'>" . ($breakfast[$k] + $lunch[$k] + $dinner[$k] ) . "</td> 
                        <td align='center'><font color='#800000'>" . ($nbreakfast[$k] + $nlunch[$k] + $ndinner[$k] ) . "</font></td></tr> ";
        }
        $tempfiles = $tempfiles . "<tr style='text-align:center' bgcolor=#CCFFCCFF>
            <th>Total</th>
                 <th>" . array_sum($breakfast) . "</th>
                <th><font color=#800000>" . array_sum($nbreakfast) . "</font></th>
            <th>" . array_sum($lunch) . "</th><th><font color=#800000>" . array_sum($nlunch) . "</font></th>
            <th> " . array_sum($dinner) . "</th><th><font color=#800000>" . array_sum($ndinner) . "</font></th>
            <th >" . ( array_sum($breakfast) + array_sum($lunch) + array_sum($dinner)) . "</th><th><font color=#800000>" . ( array_sum($nbreakfast) + array_sum($nlunch) + array_sum($ndinner)) . "</font></th> </tr></table>";

        return $tempfiles;
    }

    function display_month_report($selected_year, $selected_month, $cafenumber) {
        $breakfast = array();
        $lunch = array();
        $dinner = array();
        $datearray = array();
        $nbreakfast = array();
        $nlunch = array();
        $ndinner = array();

        $tempfiles = '';
        // $total_meal = array();
        $dt1 = $selected_month;
        $year_month = $selected_year . "-" . $selected_month;
        $monthlen = 0;
        if ($dt1 == '01' || $dt1 == '03' || $dt1 == '05' || $dt1 == '07' || $dt1 == '08' || $dt1 == '10' || $dt1 == '12')
            $monthlen = 31;
        if ($dt1 == '04' || $dt1 == '06' || $dt1 == '09' || $dt1 == '11')
            $monthlen = 30;
        if ($dt1 == '02')
            $monthlen = 28;
        $month_text = $this->convert_month_string($selected_month);
        $tempfiles = $tempfiles . "<table width=100% border=1 style='border-collapse: collapse'>            
            <tr bgcolor='#FFFFCE' ><th colspan='9' align='center' style='color:#333399'>" . $month_text . " " . $selected_year . " Cafeteria " . $cafenumber . " Report </th></tr>
                <tr><th  rowspan=2>Date(G.C)</th>
                 <th colspan=2>Breakfast</th>
                 <th colspan=2>Lunch</th>
                 <th colspan=2>Dinner</th>
                 <th colspan=2>Total</th>
  </tr>
  <tr style='background-image:url(images/bgimage.png)'>
            <th>#S ate</th><th> #S No ate </th>
            <th>#S ate</th><th> #S No ate </th>
            <th>#S ate</th><th>#S No ate</th>
             <th>#S ate</th><th>#S No ate </th>";
        for ($i = 1; $i <= $monthlen; $i++) {
            $brk = 0;
            $lnch = 0;
            $dnr = 0;

            if ($i < 10)
                $dt = "0" . $i;
            else
                $dt = $i;
            $year_month_date = $year_month . "-" . $dt;


            if ($cafenumber == '') {
                $query = "SELECT * FROM `mealcard` WHERE Date LIKE '$year_month_date' ";
                $query2 = "SELECT ci.*, st.is_active FROM cafeinfo ci INNER JOIN students st on st.IDNumber=ci.Id_Number WHERE   (ci.Status='Cafe' AND ci.registration_date LIKE '%$selected_year%') and st.is_active='Active'";
            } else {
                $query = "SELECT meal.*,cafinfo.IDNumber  FROM mealcard as meal
                        INNER JOIN cafeinfo as cafinfo ON meal.IDNumber=cafinfo.IDNumber 
                        WHERE  meal.Date LIKE '$year_month_date'  AND cafinfo.CafeNumber='$cafenumber' ";
                // $query2 = "SELECT * FROM cafeinfo WHERE   Status='Cafe' AND CafeNumber='$cafenumber'";
                $query2 = "SELECT ci.*, st.is_active  FROM cafeinfo ci INNER JOIN students st on ci.Id_Number=st.IDNumber WHERE   (Status='Cafe' AND CafeNumber='$cafenumber') AND (registration_date LIKE '%$selected_year%'  and st.is_active='Active')";
            }
            $query_result = $this->db->query($query)->result_array(); // mysqli_query($this->link, $query) OR die(mysql_error($this->link));
            $query_result2 = $this->db->query($query2)->result_array();
            ; //mysqli_query($this->link, $query2) or die(mysql_error($this->link));
            $numrows = count($query_result2);

            foreach ($query_result as $rows) {
                if ($rows['brakefast'] == 1) {
                    $brk++;
                }
                if ($rows['lunch'] == 1) {
                    $lnch++;
                }
                if ($rows['dinner'] == 1) {
                    $dnr++;
                }
            }
            array_push($breakfast, $brk);
            array_push($lunch, $lnch);
            array_push($dinner, $dnr);


            array_push($nbreakfast, $numrows - $brk);
            array_push($nlunch, $numrows - $lnch);
            array_push($ndinner, $numrows - $dnr);
            array_push($datearray, $year_month_date);
        }
        for ($k = 0; $k < sizeof($breakfast); $k++) {
            if ($k % 2 == 0) {
                $trbg = '#DFF3D9';
            } else {
                $trbg = '#FFFFFF';
            }
            $tempfiles = $tempfiles . " <tr bgcolor=$trbg><td>" . $datearray[$k] . "</td>
                        <td align='center'>" . $breakfast[$k] . "</td> <td align='center'><font color='#800000'>" . $nbreakfast[$k] . "</font></td>
                        <td align='center'>" . $lunch[$k] . " <td align='center'><font color='#800000'>" . $nlunch[$k] . "</font></td>
                        <td align='center'>" . $dinner[$k] . "</td><td align='center'><font color='#800000'>" . $ndinner[$k] . "</font></td>
                        <td align='center'>" . ($breakfast[$k] + $lunch[$k] + $dinner[$k] ) . "</td> 
                        <td align='center'><font color='#800000'>" . ($nbreakfast[$k] + $nlunch[$k] + $ndinner[$k] ) . "</font></td></tr> ";
        }

        $tempfiles = $tempfiles . "<tr style='text-align:center'  bgcolor=#CCFFCCFF>
            <th>Total</th>
            <th>" . array_sum($breakfast) . "</th>
            <th><font color=#800000>" . array_sum($nbreakfast) . "</font></th>
            <th>" . array_sum($lunch) . "</th><th><font color=#800000>" . array_sum($nlunch) . "</font></th>
            <th> " . array_sum($dinner) . "</th><th><font color=#800000>" . array_sum($ndinner) . "</font></th>
            <th >" . ( array_sum($breakfast) + array_sum($lunch) + array_sum($dinner)) . "</th><th><font color=#800000>" . ( array_sum($nbreakfast) + array_sum($nlunch) + array_sum($ndinner)) . "</font></th> </tr></table>";
        return $tempfiles;
    }

    function display_day_report($year, $month, $date, $cafenumber) {
        $brk = 0;
        $lnch = 0;
        $dnr = 0;
        $tempfiles = '';
        $year_mon_date = $year . "-" . $month . "-" . $date;
        if ($cafenumber == '') {
            $query = "SELECT *  FROM `mealcard` WHERE Date LIKE '$year_mon_date' ";
            $query2 = "SELECT * FROM cafeinfo WHERE   Status='Cafe' AND registration_date LIKE '%$year%' ";
        } else {
            $query2 = "SELECT * FROM cafeinfo WHERE   (Status='Cafe' AND CafeNumber='$cafenumber') AND registration_date LIKE '%$year%'";
            $query = "SELECT meal.*,cafinfo.IDNumber  FROM mealcard as meal INNER JOIN cafeinfo as cafinfo ON meal.IDNumber=cafinfo.IDNumber WHERE  meal.Date LIKE '$year_mon_date'  AND cafinfo.CafeNumber='$cafenumber' ";
        }
        $query_result = $this->db->query($query)->result_array();
        $query_result2 = $this->db->query($query2)->result_array(); //mysqli_query($this->link, $query2) or die(mysql_error($this->link));
        $numrows = count($query_result2);
        $tempfiles = $tempfiles . "<table width=100% border=1 style='border-collapse: collapse'>            
    <tr bgcolor='#FFFFCE' ><th colspan='9' align='center' style='color:#333399'>" . $year_mon_date . " Cafeteria " . $cafenumber . " Report</th></tr>
     <tr><th  rowspan=2>Date</th>
    <th colspan=2>Breakfast</th>
    <th colspan=2>Lunch</th>
    <th colspan=2>Dinner</th>
    <th colspan=2>Total</th>
  </tr>
  <tr style='background-image:url(images/bgimage.png);'>
            <th><img src='images/yebelu.jpg' height=30 width=50/></th><th><img src='images/yalbelu.jpg' height=30 width=50/> </th>
            <th><img src='images/yebelu.jpg' height=30 width=50/></th><th><img src='images/yalbelu.jpg' height=30 width=50/> </th>
            <th><img src='images/yebelu.jpg' height=30 width=50/></th><th><img src='images/yalbelu.jpg' height=30 width=50/> </th>
            <th><img src='images/yebelu.jpg' height=30 width=50/></th><th><img src='images/yalbelu.jpg' height=30 width=50/> </th>";
        foreach ($query_result as $rows) {
            if ($rows['brakefast'] == 1)
                $brk++;
            if ($rows['lunch'] == 1)
                $lnch++;
            if ($rows['dinner'] == 1)
                $dnr++;
        }
        $tempfiles = $tempfiles . " <tr ><td>" . $year_mon_date . "</td>
                        <td align='center'>" . $brk . "</td> 
                            <td align='center'><font color='#800000'>" . ( $numrows - $brk) . "</font></td>
                        <td align='center'>" . $lnch . "</td> 
                            <td align='center'><font color='#800000'>" . ($numrows - $lnch) . "</font></td>
                        <td align='center'>" . $dnr . "</td>
                         <td align='center'><font color='#800000'>" . ($numrows - $dnr) . "</font></td>
                        <td align='center'>" . ($brk + $lnch + $dnr ) . "</td> 
                        <td align='center'><font color='#800000'>" . (($numrows - $brk) + ($numrows - $lnch) + ($numrows - $dnr) ) . "</font></td></tr> ";
        return $tempfiles;
    }

    function display_filterd_report($intial, $destination, $cafenumber) {

        $array_date = array();
        $breakfast = array();
        $lunch = array();
        $dinner = array();
        $tempfiles = '';
        if ($cafenumber == '') {
            $query = "SELECT  * FROM `mealcard` WHERE Date >= '$intial' AND Date <= '$destination' GROUP BY  Date   ";
        } else {

            $query = "SELECT meal.*,cafinfo.IDNumber  FROM mealcard as meal INNER JOIN cafeinfo as cafinfo ON meal.IDNumber=cafinfo.IDNumber WHERE  (meal.Date >= '$intial' AND meal.Date <= '$destination')  AND cafinfo.CafeNumber='$cafenumber' GROUP BY  Date ";
            //  $query2 = "SELECT * FROM cafeinfo WHERE   (Status='Cafe' AND CafeNumber='$cafenumber') AND registration_date LIKE '%$year%'";
        }

        $query_result = $this->db->query($query)->result_array();

        foreach ($query_result as $rows) {
            array_push($array_date, $rows['Date']);
        }
        for ($i = 0; $i < sizeof($array_date); $i++) {
            $brk = 0;
            $lnch = 0;
            $dnr = 0;

            $date_1 = $array_date[$i];
            $query = "SELECT *  FROM `mealcard` WHERE Date LIKE '$date_1' ";
            $result_query = $this->db->query($query)->result_array();
            foreach ($result_query as $rows) {
                if ($rows['brakefast'] == 1) {
                    $brk++;
                }

                if ($rows['lunch'] == 1) {
                    $lnch++;
                }

                if ($rows['dinner'] == 1) {
                    $dnr++;
                }
            }

            array_push($breakfast, $brk);
            array_push($lunch, $lnch);
            array_push($dinner, $dnr);
        }

        $tempfiles = $tempfiles . "<table width=100% border=1 style='border-collapse: collapse'>
           <tr bgcolor='#FFFFCE'><td colspan='5' align='center' style='color:#0C4563;' >Cafeteria " . $cafenumber . " Report From " . $intial . "  To " . $destination . "</td></tr>
           <tr bgcolor='#FFFFCE'> <th>Dates G.C</th>
            <th>BreakFast</th>
            <th>Lunch</th>
            <th> Dinner</th>
            <th>Total</th> </tr>";
        for ($k = 0; $k < sizeof($array_date); $k++) {
            $tempfiles = $tempfiles . " <tr ><td>" . $array_date[$k] . "</td>
                            <td align='center'>" . $breakfast[$k] . "</td>
                            <td align='center'>" . $lunch[$k] . "</td>
                            <td align='center'>" . $dinner[$k] . "</td>
                            <td align='center'>" . ($breakfast[$k] + $dinner[$k] + $lunch[$k] ) . "</td></tr> ";
        }

        $tempfiles = $tempfiles . "<tr bgcolor=#CCFFCCFF>
            <th>Total</th>
            <th>" . array_sum($breakfast) . "</th>
            <th>" . array_sum($lunch) . "</th>
            <th> " . array_sum($dinner) . "</th>
            <th>" . ( array_sum($breakfast) + array_sum($lunch) + array_sum($dinner)) . "</th> </tr></table>";
        return $tempfiles;
    }

    function convert_month_string($month) {
        if ($month == '01') {
            $month = 'January';
        } elseif ($month == '02') {
            $month = 'February';
        } elseif ($month == '03') {
            $month = 'March';
        } elseif ($month == '04') {
            $month = 'April';
        } elseif ($month == '05') {
            $month = 'May';
        } elseif ($month == '06') {
            $month = 'June';
        } elseif ($month == '07') {
            $month = 'July';
        } elseif ($month == '08') {
            $month = 'August';
        } elseif ($month == '09') {
            $month = 'September';
        } elseif ($month == 10) {
            $month = 'October';
        } elseif ($month == 11) {
            $month = 'November';
        } elseif ($month == 12) {
            $month = 'December';
        }
        return $month;
    }

    function transfer_students($school, $cafenumber_moveTo, $cafenumber_moveFrom) {
        if ($school == 'all') {
            $query = " UPDATE `cafeinfo` SET      
                     Shift_Cafe='$cafenumber_moveTo' WHERE CafeNumber='$cafenumber_moveFrom'";
            $result = mysqli_query($this->link, $query) or ( die(mysql_error($this->link)));
            $query2 = "SELECT Shift_Cafe from cafeinfo WHERE Shift_Cafe='$cafenumber_moveTo' ";
            $num_rows = mysqli_num_rows(mysql_query($query2));
            echo "<font color=green size=3px>" . $num_rows . ' Students Succesfully transfered to Cafteria ' . $cafenumber_moveTo . "</font>";
        } else {
            $query = "SELECT stud.School,stud.Department,stud.Stream,cafeuser.* FROM cafeinfo
             as cafeuser INNER JOIN students as stud ON cafeuser.id_Number=stud.IDNumber WHERE cafeuser.CafeNumber='$cafenumber_moveFrom'   ";
            $result = mysqli_query($this->link, $query) or die(mysql_error($this->link));
            $num_rows = mysqli_num_rows($result);
            while ($rows = mysql_fetch_array($result)) {

                $query3 = " UPDATE `cafeinfo` SET      
                     Shift_Cafe='$cafenumber_moveTo' WHERE CafeNumber='$cafenumber_moveFrom'";
                mysql_query($query3) or ( die(mysql_error()));
            }
            echo "<font color=green size=3px>" . $num_rows . ' Students Succesfully transfered to Cafteria ' . $cafenumber_moveTo . "</font>";
        }
    }

    function reset_transfer($reset_to) {
        $query3 = " UPDATE `cafeinfo` SET  Shift_Cafe='0',    
                     CafeNumber='$reset_to' WHERE CafeNumber='$reset_to'";
        $result = mysqli_query($this->link, $query3) or die(mysqli_query($this->link, $query3));

        if ($result) {
            echo '<font color=green size=3px>All students are transferred  Successfuly to previous cafeteria</font>';
        } else {
            echo '<font color=red size=3px>transfer faild Please try again</font>';
        }
    }

    function update_password($username, $cpass, $npass) {
        // require_once 'db.php';
        $cpass = md5($cpass);
        $npass = md5($npass);
        $query = "SELECT  *   FROM `cafteriamember` WHERE Useraname='$username' and Password='$cpass'";
        $result = mysqli_query($this->link, $query) or die(mysql_error());
        if (mysqli_num_rows($result) == 1) {
            $sql = "UPDATE cafteriamember SET Password='$npass' WHERE Useraname='$username'";
            mysqli_query($this->link, $sql) or die(mysql_error());
            echo '<font color=green>Successfuly updated</font>';
        } else {
            echo '<font color=red>   Incorrect Current  Password</font>';
        }
    }

    function resetpassword($un, $phn, $rol) {
        $user = mysqli_real_escape_string($this->link, $un);
        $phone = mysqli_real_escape_string($this->link, $phn);
        $in = '';
        $temp = '';
        // $newpass=mysql_real_escape_string($np);
        $role = mysqli_real_escape_string($this->link, $rol);
        //SELECT `Roll_Number`, `FirstName`, `LastName`, `Phone`, ``,
// `Password`, `Control`, `Cafteria`, `UserPrivilage` FROM `cafteriamember` WHERE 1
        $sql = "SELECT * FROM cafteriamember WHERE Useraname='$user' AND UserPrivilage='$role' AND Phone='$phone'";
        $result = mysqli_query($this->link, $sql) or die(mysql_errno());
        if (mysqli_num_rows($result) != 0) {
            $in = $this->genpwd(10);
            $resetpassword = md5($in);
            $resut = mysqli_query($$this->link, "UPDATE cafteriamember SET Password='$resetpassword'  WHERE Useraname='$user'") or die(mysql_error());
            if ($resut) {
                //  $subject="Hi, this the reseted password from astu odms";
                $temp = $temp . "<font color=green size=3px>  successfully reseted <br/> now take this password <br/></font><font color=blue size=2px>" . $in . "</font>";
            }
        } else {
            $temp = $temp . "<font color=red size=3px>  username or role  is not correct </font>";
        }
        return $temp;
    }

    function genpwd($cnt) {
        $pwd = str_shuffle('abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*');
        return substr($pwd, 0, $cnt);
    }

    function get_student_count_per_bach() {
        $this->db->select("d.dept_name, s.Year,s.Program, s.admission, count(s.bcID) as noofstud ");
        $this->db->from("students s");
        $this->db->join("departments d", "d.dept_code=s.dept_code");
        $this->db->where("s.is_Active", "Active");
        $this->db->group_by(array("s.dept_code", "s.Year"));
        $this->db->order_by("d.dept_name ASC", "s.Year ASC");
        return $this->db->get()->result_array();
    }

    function get_student_lost_id_card() {
        $this->db->select("d.dept_name,f.faculity_name, s.Year,s.Program, s.admission, count(s.bcID) as noofstud ");
        $this->db->from("students s");
        $this->db->join("id_card_requests i", "i.student_id=s.IDNumber");
        $this->db->join("departments d", "d.dept_code=s.dept_code");
        $this->db->join("faculities f", "f.faculity_code=d.faculity_code");
        $this->db->where("i.print_status", 2);
        $this->db->group_by(array("d.faculity_code","s.admission","s.Program"));
        $this->db->order_by("f.faculity_name ASC");
        return $this->db->get()->result_array();
    }

    function get_none_cafe_students() {
        $this->db->select("d.dept_name,s.FullName,s.Sex, s.Year, s.IDNumber, ,s.Program, s.admission ");
        $this->db->from("students s");
        $this->db->join("departments d", "d.dept_code=s.dept_code");
        $this->db->join("cafeinfo ci", "ci.IDNumber=s.bcID");
        $this->db->where(array("s.is_Active" => "Active", "ci.Status" => "None-Cafe"));
        $this->db->order_by("s.FullName", "ASC");
        return $this->db->get()->result_array();
    }

    function get_cafeteria_audit() {
        $this->db->select("d.dept_name,s.FullName,s.Sex, s.Year, s.IDNumber, ,s.Program, s.admission, ca.changed_to, ca.action_performed_by, ca.action_performed_on, ca.audit_id, u.email, u.first_name, u.last_name");
        $this->db->from("students s");
        $this->db->join("departments d", "d.dept_code=s.dept_code");
        $this->db->join("cafeinfo ci", "ci.IDNumber=s.bcID");
        $this->db->join("cafe_audit ca", "ca.student_id=ci.Id_Number");
        $this->db->join("users u", "u.email=ca.action_performed_by");
        $this->db->where(array("s.is_Active" => "Active"));
        $this->db->order_by("ca.audit_id", "DESC");
        return $this->db->get()->result_array();
    }

}
