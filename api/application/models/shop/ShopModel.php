<?php

class ShopModel extends CI_Model {

    public function getShopList() {
        $this->db->select("a.id,a.name,a.gst_no,concat(b.first_name,' ',b.last_name) as owner_name,concat(a.address_line_1,' ',a.address_line_2,' ',a.address_line_3) as shop_address,c.name as country_name,d.name as state_name,e.name as city_name");
        $this->db->from("shop_details as a");
        $this->db->join("user_details as b","a.owner_id=b.id");
        $this->db->join("country as c","a.country_id=c.id","LEFT");
        $this->db->join("state as d","a.state_id=d.id","LEFT");
        $this->db->join("city as e","a.city_id=e.id","LEFT");
        $this->db->where("a.tstatus","TRUE");
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function getShopOwnerList() {
        $this->db->select("a.id,concat(a.first_name,' ',a.last_name) as name");
        $this->db->from("user_details as a");
        $this->db->where("a.status","TRUE");
        $this->db->where("a.user_type","SHOPOWNER");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function saveNewShopDetails($dataArr) {
        $result = $this->db->insert("shop_details", $dataArr);
        $insertId = $this->db->insert_id();
        if (count($result) > 0) {
            return array("status" => "SUCCESS", "value" => $insertId, "msg" => "Shop details saved successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to save new Shop.");
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
