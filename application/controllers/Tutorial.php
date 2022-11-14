<?php

/* Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
  Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
  Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tutorial extends CI_Controller {

    var $today;
    var $current_time;

    public function __construct() {
        parent::__construct();

        $this->today = date("Y-m-d");
        $this->current_time = date("h:i:s");
        $this->load->model("BasicData_model");
        $this->load->library("form_validation");
    }

    function tutorials() {
        $data['departments'] = $this->BasicData_model->get_all_departments();
        $tutorials = [];
        foreach ($data['departments'] as $row) {
            $t["title"] = $row['dept_name'];
            $t["description"] = $row['faculity_code'];
            $tutorials[] = $t;
        }
        echo json_encode($tutorials);
        //  $data['_view'] = 'basicData/department_view';
        // $this->load->view('layouts/main', $data);
    }

    function getCustomers() {
        $data['departments'] = $this->BasicData_model->get_all_departments();
        $tutorials = [];
        foreach ($data['departments'] as $row) {
            $t["dept"] = $row['dept_name'];
            $t["code"] = $row['faculity_code'];
            $tutorials[] = $t;
        }

        echo json_encode($tutorials);
        //  $data['_view'] = 'basicData/department_view';
        // $this->load->view('layouts/main', $data);
    }

    function addCustomer($data = null) {
        $postdata = json_decode(file_get_contents("php://input"));

        $params = array(
            'dept_name' => $postdata->dept,
            'faculity_code' => $postdata->code,
//                'createdBy' => $this->user->email,
//                'createdOn' => $this->today,
            'is_deleted' => 0,
//                'kebeleCode' => $this->input->post('kebeleCode'),
        );
        // echo json_encode($params);
        //   $request = json_decode($postdata);
        //  $request->data->model
        //  exit();

        $kebele_id = $this->BasicData_model->save("departments", $params);
        if (isset($kebele_id)) {
            echo json_encode(array("message" => "operation Done successfully", "status" => true));
        } else {
            echo json_encode(array("message" => "operation Failed", "status" => false));
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
