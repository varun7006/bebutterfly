<?php

class productModel extends CI_Model {

    public function getUserList() {
        $this->db->select("a.id,concat(a.first_name,a.last_name) as name,a.email,a.mobile_no,a.user_type");
        $this->db->from("user_details as a");
        $this->db->where("a.status","TRUE");
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function getShopOwnerList() {
        $this->db->select("a.id,concat(a.first_name,a.last_name)");
        $this->db->from("user_details as a");
        $this->db->where("a.status","TRUE");
        $this->db->where("a.user_details","SHOPOWNER");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function saveNewUserDetails($dataArr) {
        $result = $this->db->insert("user_details", $dataArr);
        $insertId = $this->db->insert_id();
        if (count($result) > 0) {
            return array("status" => "SUCCESS", "value" => $insertId, "msg" => "user details saved successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to save new user.");
        }
    }

    public function updateUserDetails($dataArr, $updateId) {
        $this->db->where('id', $updateId);
        $updateResult = $this->db->update('users', $dataArr);
        
        if ($this->db->affected_rows() > 0) {
            return array("status" => "SUCCESS", "value" => "1", "msg" => "user details saved successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to updae user details.");
        }
    }
    
    public function deleteUser($deleteId) {
        $this->db->where('id', $deleteId);
        $deleteResult = $this->db->update('users', array("status"=>'FALSE'));
        if ($this->db->affected_rows() > 0) {
            return array("status" => "SUCCESS", "value" => "1", "msg" => "user deleted successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to delete user.");
        }
    }
    
    public function saveUserByExcel($dataArr) {
        $result = $this->db->insert_batch('users',$dataArr);
       
        if (count($result) > 0) {
            return array("status" => "SUCCESS", "value" => $result, "msg" => "user details saved successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to save user by excel.");
        }
    }

}
