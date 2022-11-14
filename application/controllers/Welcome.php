<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends SupperCtr {

    public function __construct() {
        parent::__construct();

        $this->load->model("Cafeteria_model");
    }

    public function index() {
        if (count($this->roles) == 1 && $this->roles[0] == 4) {
            redirect("Welcome/cafeteria_authorization_form");
        } else {
            $data['_view'] = "defualt/defualt";
            $this->load->view('layouts/main', $data);
        }
    }

    function cafeteria_authorization_form() {
        $data['role'] = 4;
        if ($this->input->get('barcode')) {
            $this->authorize_for_service();
            exit();
        } else {
            $data['_view'] = "cafeteria_authorization_form";
            $this->load->view('layouts/main', $data);
        }
    }

    private function has_special_permission($barcode) {
        $array_rows = $this->Cafeteria_model->get_special_permission($barcode, $this->today);
        $count = count($array_rows);
        if ($count > 0) {
            // $response = array("message" => "", "status" => 0);
            $lastdate = $array_rows['lastDate'];
            $lastdate_time = strtotime($lastdate);
            $today_time = strtotime($this->today);
            if ($lastdate_time < $today_time) {
                $permited = true;
                $days = $this->Cafeteria_model->computeDateDifference($lastdate, $this->today);
                $array_rows['message'] = "Special Permission will be expired after $days ";
                $array_rows['status'] = TRUE;
            } else {
                $array_rows['message'] = "Special Permission has been expired";
                $array_rows['status'] = FALSE;
            }
        } else {
            $array_rows['message'] = "No Special Permission";
            $array_rows['status'] = FALSE;
        }
        return $array_rows;
    }

    private function map_for_ui($data) {
        $result['full_name'] = $data["FullName"];
        $result['ID'] = $data["Id_Number"];
        $result['department'] = $data["dept_name"];
        $result['sex'] = $data["Sex"];
        $result['year'] = $data["Year"];
        $result['program'] = $data["Program"];
        $result['special_case'] = "";
        return $result;
    }

    private function meal_type() {
        $hours = strtotime(date("h:i:s a"));
        //breakfast feedtime
        $breakfast_start = strtotime("6:05:00 am");
        $breakfast_end = strtotime("9:30:00 am");
        //lunch feed time
        $lunch_start = strtotime("10:50:00 am");
        $lunch_end = strtotime("2:59:00 pm");
        //dinner time
        $dinner_start = strtotime("3:50:00 pm");
        $dinner_end = strtotime("6:50:00 pm");
        if ($hours <= $breakfast_end && $hours >= $breakfast_start) {
            return 1; //breakfast time
        } elseif ($hours >= $lunch_start && $hours <= $lunch_end) {
            return 2; //lunch time
        } elseif ($hours <= $dinner_end && $hours >= $dinner_start) {
            return 3; //dinner time
        } else {
            return 0;
        }
    }

    private function authorize_for_service() {
        $barcode = $this->input->get('barcode');
        $cafenumber = $this->user->company;
        $kurs = 0;
        $misa = 0;
        $erat = 0;
        $array_rows = $this->Cafeteria_model->get_student($barcode);
        $num_rows = count($array_rows);
        $permited = FALSE;
        $response = array("status" => 0, "special_case" => "");
        $where = array("IDNumber" => $barcode, "Date" => $this->today);
        if ($num_rows == 0) {
            $array_rows = $this->has_special_permission($barcode);
            if ($array_rows['status']) {
                $response = $this->map_for_ui($array_rows);
                $response['special_case'] = $array_rows['message'];
                $permited = true;
            } else {
                $response['special_case'] = $array_rows['message'];
                $permited = FALSE;
                $response['photopath'] = base_url() . "resources/imageFiles/error.png";
            }
        }

        if ($num_rows > 0 || $permited == true) {
            $array_rows = $array_rows[0];
            $id = $array_rows['Id_Number'];
            $response = $this->map_for_ui($array_rows);
            $photoName = $array_rows['photo'];
            if ($photoName != '') {
                $imagename = $photoName;
            } else {
                $imagename = "imageFiles/male.jpeg";
            }
            $response['photopath'] = base_url() . "resources/" . $imagename;
            $response["status"] = 0;
            if (!empty($id)) {
                $date = $this->today;
                $time = $this->current_time;
                $cafe = $array_rows['CafeNumber'];
                $noncafe = $array_rows['Status'];
                $shift = $array_rows['Shift_Cafe'];
                $mealType = $this->meal_type(); // it can be 1,2,3 or 0()

                if ($noncafe == "Cafe") {
                    // if ($cafe == $cafenumber || $shift == $cafenumber) {

                    if ($mealType == 1) {
                        $result10 = $this->Cafeteria_model->get_meal_card($barcode, $this->today);
                        $count = count($result10);
                        if ($count == 0) {
                            $kurs = 1;
                            $params = array("IDNumber" => $barcode, "brakefast" => $kurs, "lunch" => $misa, "dinner" => $erat, "Date" => $this->today, "breakfastFeedTime" => $this->current_time, "lunchFeedTime" => $this->current_time, "dinnerFeedTime" => $this->current_time);
                            $this->Cafeteria_model->save_meal_card($params);
                            $response["message"] = "Have a nice breakfast </br>መልካም ቁርስ ";
                            $response['status'] = 1;
                        } else {
                            if ($result10[0]['brakefast'] == 0) {
                                $params = array(`brakefast` => 1, "breakfastFeedTime" => $this->current_time);
                                $this->Cafeteria_model->update_meal_card($where, $params);
                                $response["message"] = "Have a nice breakfast <br/> መልካም ቁርስ";
                                $response['status'] = 1;
                            } else {
                                $response["message"] = "This student has already  had breakfast <br/> ቁርስ በልቷል ድጋሚ አገልግሎት አንዳያገኝ። ";
                                $response['status'] = 0;
                            }
                        }
                    }
                    ///~~~~~~~~~~~ lunch~~~~~~~~~~~~~~~~~~~~~|| $hours >=$lunch_start2 && $hours <= $lunch_end2
                    elseif ($mealType == 2) {
                        $result10 = $this->Cafeteria_model->get_meal_card($barcode, $this->today);
                        $count = count($result10);
//print_r($result10);

                        if ($count == 0) {
                            $misa = 1;
                            $params = array("IDNumber" => $barcode, "brakefast" => $kurs, "lunch" => $misa, "dinner" => $erat, "Date" => $this->today, "breakfastFeedTime" => $this->current_time, "lunchFeedTime" => $this->current_time, "dinnerFeedTime" => $this->current_time);
                            $this->Cafeteria_model->save_meal_card($params);
                            $response['message'] = "have a nice Lunch <br/> መልካም ምሳ";
                            $response['status'] = 1;
                        } else {
                            if ($result10[0]['lunch'] == 0) {
                                $params = array("lunch" => 1, "lunchFeedTime" => $this->current_time);
                                $this->Cafeteria_model->update_meal_card($where, $params);
                                $response["message"] = "Have a nice Lunch<br/>  መልካም ምሳ";
                                $response['status'] = 1;
                            } else {
                                $response['message'] = "This student has already  had  lunch። ምሳ በልቷል ድጋሚ አገልግሎት አንዳያገኝ። ";
                            }
                        }
                    } elseif ($mealType == 3) {
                        $result10 = $this->Cafeteria_model->get_meal_card($barcode, $this->today);
                        $count = count($result10);
                        $erat = 1;
                        if ($count == 0) {
                            $params = array("IDNumber" => $barcode, "brakefast" => $kurs, "lunch" => $misa, "dinner" => $erat, "Date" => $this->today, "breakfastFeedTime" => $this->current_time, "lunchFeedTime" => $this->current_time, "dinnerFeedTime" => $this->current_time);
                            $this->Cafeteria_model->save_meal_card($params);
                            $response['message'] = "have a nice dinner <br/> መልካም እራት";
                            $response['status'] = 1;
                        } else {
                            if ($result10[0]['dinner'] == 0) {
                                $params = array("dinner" => 1, "dinnerFeedTime" => $this->current_time);
                                $this->Cafeteria_model->update_meal_card($where, $params);
                                $response["message"] = "Have a nice dinner. መልካም እራት";
                                $response['status'] = 1;
                            } else {
                                $response['message'] = "This student has already  had  dinner.<br/> እራት በልቷል ድጋሚ አገልግሎት እንዳያገኝ።";
                                $response["special_case"] = "";
                            }
                        }
                    } else {
                        $response['message'] = "Service Denied</br> የመመገቢያ ሰአት እስከሚደርስ በትእግስት ይጠብቁ።";
                        $response["special_case"] = "";
                    }
                    /*  } else {
                      $response['message'] = "ይህ ተማሪ የተመደበው ካፌ $cafe  ስለሆነ አገልግሎት አንዳያገኝ።";
                      $response["special_case"] = "";
                      } */
                } else {
                    $response["message"] = "None-Cafe Student.</br> ነን ካፌ ተማሪ ስለሆነ አገልግሎት አንዳያገኝ።";
                    $response["special_case"] = "";
                }
            } else {
                $response["message"] = "የትኛዉም ካፍተሪያ ላይ ያልተመደበ ተማሪ ስለሆነ አገልግሎት ለማግኘት መመደብ አለበት </br> ";
                $response["special_case"] = "";
            }
        } else {
            $result = $this->Cafeteria_model->get_student_by_id(array("bcID" => $barcode));
            if (count($result) > 0) {
                $response = $this->map_for_ui($result[0]);
                $response["message"] = "Service Denied</br> Inactive student።";
                $response["special_case"] = "";
                 $response["status"] = 0;
            } else {
                $response["message"] = "Service Denied</br> የማይታወቅ መታወቂያ ቁጥር ስለሆነ አገልግሎት አንዳያገኝ።";
                $response["special_case"] = "";
            }
        }
        echo json_encode($response);
    }

}
