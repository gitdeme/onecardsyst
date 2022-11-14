<?php

 
class Useraccount_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get useraccount by 
     */
    function get_useraccount($id)
    {
        return $this->db->get_where('useraccount',array(''=>$id))->row_array();
    }
        
    /*
     * Get all useraccount
     */
    function get_all_useraccount()
    {
        $this->db->order_by('', 'desc');
        return $this->db->get('useraccount')->result_array();
    }
        
    /*
     * function to add new useraccount
     */
    function add_useraccount($params)
    {
        $this->db->insert('useraccount',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update useraccount
     */
    function update_useraccount($id,$params)
    {
        $this->db->where('',$id);
        return $this->db->update('useraccount',$params);
    }
    
    /*
     * function to delete useraccount
     */
    function delete_useraccount($id)
    {
        return $this->db->delete('useraccount',array(''=>$id));
    }
}
