<?php

class Action extends SupperCtr {

    function __construct() {
        parent::__construct();
        $this->load->model('Action_model');
        // $this->load->model('UIConstants');
        $this->load->library(['ion_auth', 'form_validation']);
    }

    function view_actions() {
        $data['actions'] = $this->Action_model->get_all_actions();
        $data['_view'] = 'auth/view_actions';
        $this->load->view('layouts/main', $data);
    }

    function create_action() {
        // $data['labels']=  $this->UIConstants->const_position;

        $this->form_validation->set_rules('action_name', 'action_name', 'required');
        $this->form_validation->set_rules('action_description', 'action_description', 'required');
        if ($this->form_validation->run()) {

            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'action_name' => strtolower($this->input->post('action_name')),
                    'action_description' => $this->input->post('action_description'),
                    "action_menu_group" => $this->input->post('action_group'),
                    "action_label" => $this->input->post('action_label'),
                    "action_icon" => $this->input->post('action_icon'),
                );

                $position_id = $this->Action_model->add_action($params);
                redirect('action/view_actions');
            }
        } else {
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data["menus"] = $this->Action_model->get_menus();
            $data['_view'] = 'auth/create_action';
            $this->load->view('layouts/main', $data);
        }
    }

    function edit_action($actionID) {

        $data['action'] = $this->Action_model->get_action($actionID);

        if (isset($data['action']['activityID'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'action_name' => $this->input->post('action_name'),
                    'action_description' => $this->input->post('action_description'),
                    "action_menu_group" => $this->input->post('action_group'),
                    "action_label" => $this->input->post('action_label'),
                    "action_icon" => $this->input->post('action_icon'),
                    "action_order" => $this->input->post('action_order'),
                );

                $this->Action_model->update_action($actionID, $params);
                redirect('action/view_actions');
            } else {
                $data['_view'] = 'auth/edit_action';
                $data["menus"] = $this->Action_model->get_menus();
                $this->load->view('layouts/main', $data);
            }
        } else
            show_error('The position you are trying to edit does not exist.');
    }

    /*
     * Deleting position
     */

    function remove($actionID) {
        $position = $this->Action_model->get_action($actionID);
        // check if the position exists before trying to delete it
        if (isset($position['activityID'])) {
            $this->Action_model->delete_action($actionID);
            redirect('action/view_actions');
        } else
            show_error('The position you are trying to delete does not exist.');
    }

    function remove_group_permission($permission_id) {
        $position = $this->Action_model->get_group_role($permission_id);
        // check if the position exists before trying to delete it
        if (isset($position['userRoleID'])) {
            $this->Action_model->delete_permission($permission_id);
            redirect('action/view_group_permissions');
        } else
            show_error('The position you are trying to delete does not exist.');
    }

    function group_permission() {
        if ($this->input->post('save_permission') && !$this->input->post('user_group_sent')) {
            $role = $this->input->post('role');
            $permissions = $this->input->post('actions[]');
            //   $this->Action_model->delete_previous_permission(array("group_id"=>$role))
            foreach ($permissions as $p) {
                $param = array(
                    'activity_id' => $p,
                    'group_id' => $role,
                    'createdOn' => $this->date_time,
                    'createdBy' => $this->user->first_name . ' ' . $this->user->last_name
                );

                $is_exist = $this->Action_model->permission_exist($p, $role);
                if (count($is_exist) == 0) {
                    $id = $this->Action_model->add_permission($param);
                    // echo $p . '-->' . $role . '<br/>';
                }
            }
            redirect("action/view_group_permissions");


            // print_r($permissions);
        } else {
            $data['group_id'] = 1;
            if ($this->input->post("user_group_sent")) {
                $group_id = $this->input->post("role");
                $data['group_id'] = $group_id;
            }
            $data['actions'] = $this->Action_model->get_all_actions();
            $data['group_permissions'] = $this->Action_model->get_all_permited_actions_for_group($data['group_id']);
            $permited_activity_ids = [];
            foreach ($data['group_permissions'] as $row) {
                $permited_activity_ids[] = $row['activity_id'];
            }

            $temp_data = [];
            foreach ($data['actions'] as $row) {
                $activity_id = $row['activityID'];
                if (in_array($activity_id, $permited_activity_ids)) {
                    $row["checked"] = "checked";
                } else {
                    $row["checked"] = "";
                }
                $temp_data[] = $row;
            }

            $data['actions'] = $temp_data;
            $data['groups'] = $this->ion_auth->groups()->result();
            $data['_view'] = 'auth/group_permission';
            //  exit();
            $this->load->view('layouts/main', $data);
        }
    }

    function view_group_permissions() {
        $data['permissions'] = $this->Action_model->get_all_permited_actions();
        $data['_view'] = 'auth/view_group_actions';
        $this->load->view('layouts/main', $data);
    }

}
