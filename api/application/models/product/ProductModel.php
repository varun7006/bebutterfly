<?php
///var/www/html/bebutterfly/api/application/models/product/productModel.php
class productModel extends CI_Model {

    public function getProductList() {
        $this->db->select("a.*,b.name as shop_name,c.name as category_name,d.name as brand_name");
        $this->db->from("product_details as a");
        $this->db->join("shop_details as b","a.shop_id=b.id");
        $this->db->join("category as c","a.category_id=c.id","LEFT");
        $this->db->join("brands as d","a.brand_id=d.id","LEFT");
        $this->db->where("a.tstatus","TRUE");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function saveNewProduct($dataArr) {
        $result = $this->db->insert("product_details", $dataArr);
        $insertId = $this->db->insert_id();
        if (count($result) > 0) {
            return array("status" => "SUCCESS", "value" => $insertId, "msg" => "product details saved successfully.");
        } else {
            return array("status" => "ERR", "value" => "-1", "msg" => "unable to save new product.");
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
