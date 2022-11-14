<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authorization
 *
 * @author user
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Authorization  extends SupperCtr{
    
    function  authorize_on_library_gate(){
          if ($this->input->get('barcode_liberary')) {
           
              
              
            exit();
        }else{
           $data['_view'] = "gate/authorize_on_library_gate";
          $this->load->view("layouts/main", $data);  
        }
         
    }
  function  authorize_on_campus_gate(){
         if ($this->input->get('barcode')) {
            $this->authorize_for_service();
            exit();
        }else{
         $data['_view'] = "gate/authorize_on_campus_gate";
          $this->load->view("layouts/main", $data); 
        }
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
    //put your code here
}
