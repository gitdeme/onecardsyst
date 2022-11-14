<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calenders extends SupperCtr {

    public function __construct() {
        parent::__construct();
        $this->load->model("Calenders_model");
        $this->load->library("form_validation");
    }

    //fuculties CRUD

    function view_calenders() {
        $data['calenders'] = $this->Calenders_model->get_all_calenders();
        $data['_view'] = 'calender/calender_index';
        $this->load->view('layouts/main', $data);
    }

    function set_calender() {

        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('end_date', 'end date', 'required');
        $this->form_validation->set_rules('dept_code[]', 'department', 'required');
        $this->form_validation->set_rules('stud_year[]', 'Student Year', 'required');
        $this->form_validation->set_rules('acyear', 'Academic Year', 'required');
        $this->form_validation->set_rules('semester', 'Semester', 'required');
        $this->form_validation->set_rules('program', 'Program', 'required');
        $this->form_validation->set_rules('admission_type', 'admission Type', 'required');

        if ($this->form_validation->run()) {
            $depts = $this->input->post('dept_code');
            $years = $this->input->post('stud_year');
            for ($d = 0; $d < count($depts); $d++) {
                $department = $depts[$d];
                for ($i = 0; $i < count($years); $i++) {
                    $params = array(
                        'dept_id' => $department,
                        'stud_year' => $years[$i],
                        'semester' => $this->input->post('semester'),
                        'acyear' => $this->input->post('acyear'),
                        'admission_type' => $this->input->post('admission_type'),
                        'program' => $this->input->post('program'),
                        'start_date' => $this->input->post('start_date'),
                        'end_date' => $this->input->post('end_date'),
                        'is_active' => 1
                    );


                    $cal_id = $this->Calenders_model->save("calenders", $params);
                }
            }
            redirect('Calenders/view_calenders');
        } else {
            $this->load->model("BasicData_model");
            $orderby = "faculity_code ASC, dept_name ASC";
            $data['departments'] = $this->BasicData_model->get_all_departments($orderby);
            $data['_view'] = 'calender/calender_add';
            $this->load->view('layouts/main', $data);
        }
    }

    function view_departments() {
        $data['departments'] = $this->BasicData_model->get_all_departments();
        $data['_view'] = 'basicData/department_view';
        $this->load->view('layouts/main', $data);
    }

    //`meal_type`, `start_time`, `end_time`, `start_time_backup`, `end_time_backup`
    function view_meal_time() {
        $data['meal_hours'] = $this->BasicData_model->get_all_meal_types();
        $data['_view'] = 'basicData/meal_time_view';
        $this->load->view('layouts/main', $data);
    }

    function edit_meal_time($meal_type) {
        if ($this->input->post("update_meal_time")) {
            $start_time = $this->input->post("start_time");
            $end_time = $this->input->post("end_time");
            $meal_type = $this->input->post("meal_type");
            $param = array("start_time" => $start_time, "end_time" => $end_time);

            $status = $this->BasicData_model->update('meal_time', $param, array("meal_type" => $meal_type));
            if ($status) {
                $data['message'] = "<h class='text-success'> Successfully Updated </h3>";
            } else {
                $data['message'] = "<h class='text-danger'>Operation Falied </h3>";
            }
            $data['meal_hour'] = $this->BasicData_model->get_meal_feeding_time($meal_type);
            $data['_view'] = 'basicData/meal_time_edit';
            $this->load->view('layouts/main', $data);
        } else {
            $data['meal_hour'] = $this->BasicData_model->get_meal_feeding_time($meal_type);

            if (count($data['meal_hour']) > 0) {
                $data['_view'] = 'basicData/meal_time_edit';
                $this->load->view('layouts/main', $data);
            } else {
                $data['meal_hours'] = $this->BasicData_model->get_all_meal_types();
                $data['_view'] = 'basicData/meal_time_view';
                $this->load->view('layouts/main', $data);
            }
        }
    }

    function add_faculty() {
        // $data['faculties'] = $this->BasicData_model->get_all_faculties();  
        $this->form_validation->set_rules('faculty', 'faculty', 'required|trim');
        // $this->form_validation->set_rules('kebeleCode', 'kebeleCode', 'required');
        // $data['labels'] = $this->UIConstants->const_kebele;
        if ($this->form_validation->run()) {
            $params = array(
                'faculity_name' => $this->input->post('faculty'),
//                'createdBy' => $this->user->email,
//                'createdOn' => $this->today,
//                'isDeleted' => 0,
//                'kebeleCode' => $this->input->post('kebeleCode'),
            );

            $faculty_id = $this->BasicData_model->save("faculities", $params);
            redirect('BasicData/view_faculties');
        } else {
            $data['_view'] = 'basicData/faculty_add';
            $this->load->view('layouts/main', $data);
        }
    }

    function add_department() {

        // $data['faculties'] = $this->BasicData_model->get_all_faculties();  
        $this->form_validation->set_rules('dept_name', 'department name', 'required|trim');
        $this->form_validation->set_rules('fac_code', 'Faculty code', 'required|trim');
        // $this->form_validation->set_rules('kebeleCode', 'kebeleCode', 'required');
        // $data['labels'] = $this->UIConstants->const_kebele;
        if ($this->form_validation->run()) {
            $params = array(
                'dept_name' => $this->input->post('dept_name'),
                'faculity_code' => $this->input->post('fac_code'),
//                'createdBy' => $this->user->email,
//                'createdOn' => $this->today,
                'is_deleted' => 0,
//                'kebeleCode' => $this->input->post('kebeleCode'),
            );

            $kebele_id = $this->BasicData_model->save("departments", $params);
            redirect('BasicData/view_departments');
        } else {
            $data['faculties'] = $this->BasicData_model->get_all_faculties();
            $data['_view'] = 'basicData/department_add';
            $this->load->view('layouts/main', $data);
        }
    }

    function edit_department($departmentID) {
        // check if the kebele exists before trying to edit it
        $data['department'] = $this->BasicData_model->get_department($departmentID);
        $data['faculties'] = $this->BasicData_model->get_all_faculties();
        $this->form_validation->set_rules('dept_name', 'Department Code', 'required');
        $this->form_validation->set_rules('fac_code', "Department's Faculity", 'required');

        if ($this->form_validation->run()) {

            if (isset($data['department']['dept_code'])) {
                if (isset($_POST) && count($_POST) > 0) {
                    $params = array(
                        'dept_name' => $this->input->post('dept_name'),
                        'faculity_code' => $this->input->post('fac_code'),
                    );
                    $where = array("dept_code" => $departmentID);
                    $this->BasicData_model->update("departments", $params, $where);
                    redirect('BasicData/view_departments');
                }
            } else {
                show_error('The department you are trying to edit does not exist.');
            }
        } else {
            $data['_view'] = 'basicData/department_edit';
            $this->load->view('layouts/main', $data);
        }
    }

    function edit_faculty($fac_code) {
        // $data['faculties'] = $this->BasicData_model->get_all_faculties();
        // $data['_view'] = 'basicData/faculty_view';
        // $this->load->view('layouts/main', $data);
        // check if the kebele exists before trying to edit it
        $data['faculty'] = $this->BasicData_model->get_faculty($fac_code);
        //$data['faculties'] = $this->BasicData_model->get_all_faculties();
        $this->form_validation->set_rules('faculty', 'Faculty Name', 'required');
        // $this->form_validation->set_rules('fac_code', "Department's Faculity", 'required');

        if ($this->form_validation->run()) {

            if (isset($data['faculty']['faculity_code'])) {
                if (isset($_POST) && count($_POST) > 0) {
                    $params = array(
                        'faculity_name' => $this->input->post('faculty'),
                            // 'faculity_code' => $this->input->post('fac_code'),
                    );
                    $where = array("faculity_code" => $fac_code);
                    $this->BasicData_model->update("faculities", $params, $where);
                    redirect('BasicData/view_faculties');
                }
            } else {
                show_error('The department you are trying to edit does not exist.');
            }
        } else {
            $data['_view'] = 'basicData/faculty_edit';
            $this->load->view('layouts/main', $data);
        }
    }

    /*
     * Adding a new kebele
     */

    function add_stream() {

        // $data['faculties'] = $this->BasicData_model->get_all_faculties();  
        $this->form_validation->set_rules('stream_name', 'Stream name', 'required|trim');
        $this->form_validation->set_rules('dept_code', 'Dept Name', 'required|trim');
        // $this->form_validation->set_rules('kebeleCode', 'kebeleCode', 'required');
        // $data['labels'] = $this->UIConstants->const_kebele;
        if ($this->form_validation->run()) {
            $params = array(
                'stream_name' => $this->input->post('stream_name'),
                'dept_code' => $this->input->post('dept_code'),
                'is_deleted' => 0,
            );

            $this->BasicData_model->save("streams", $params);
            redirect('BasicData/view_streams');
        } else {
            $data['departments'] = $this->BasicData_model->get_all_departments();
            $data['_view'] = 'basicData/stream_add';
            $this->load->view('layouts/main', $data);
        }
    }

    function view_streams() {
        $data['streams'] = $this->BasicData_model->get_all_streams();
        $data['_view'] = 'basicData/stream_view';
        $this->load->view('layouts/main', $data);
    }

    /*
     * Editing a stream
     */

    function edit_stream($stream_id) {
        // check if the kebele exists before trying to edit it
        $data['stream'] = $this->BasicData_model->get_stream($stream_id);
        $this->form_validation->set_rules('dept_code', "stream's Department", 'required');
        $this->form_validation->set_rules('stream_name', 'Stream Name', 'required');
        $data['departments'] = $this->BasicData_model->get_all_departments();
        if ($this->form_validation->run()) {

            if (isset($data['stream']['stream_ID'])) {
                if (isset($_POST) && count($_POST) > 0) {
                    $params = array(
                        'stream_name' => $this->input->post('stream_name'),
//                    'createdBy' => $this->input->post('createdBy'),
//                    'createdOn' => $this->input->post('createdOn'),
                        'dept_code' => $this->input->post('dept_code'),
                    );

                    $this->BasicData_model->update("streams", $params, array('stream_ID' => $stream_id));
                    redirect('BasicData/view_streams');
                }
            } else {
                show_error('The kebele you are trying to edit does not exist.');
            }
        } else {
            $data['_view'] = 'basicData/stream_edit';
            $this->load->view('layouts/main', $data);
        }
    }

    /*
     * Deleting faculty
     */

    function remove_faculty($id) {
        // $fac = $this->BasicData_model->get_kebele($kebeleID);
        // check if the kebele exists before trying to delete it
        //  if (isset($kebele['kebeleID'])) {
        $where = array("faculity_code" => $id);
        $this->BasicData_model->delete("faculities", $where);
        redirect('BasicData/view_faculties');
//        } else
//            show_error('The kebele you are trying to delete does not exist.');
    }

    function remove_department($id) {
        // $fac = $this->BasicData_model->get_kebele($kebeleID);
        // check if the kebele exists before trying to delete it
        //  if (isset($kebele['kebeleID'])) {
        $where = array("dept_code" => $id);
        $this->BasicData_model->delete("departments", $where);
        redirect('BasicData/view_departments');
//        } else
//            show_error('The kebele you are trying to delete does not exist.');
    }

}
