<?php

class loginModel extends CI_Model {
    
    public function verifyUserLoginDetails($email) {
     
        $this->db->select("*");
        $result = $this->db->get_where("user_details",array("email"=>$email,"status"=>"TRUE"))->row_array();
        if(count($result)>0){
            return array("status"=>"SUCCESS","value"=>$result,"msg"=>"user login details are present.");
        }else{
            return array("status"=>"ERR","value"=>"-1","msg"=>"user login details are in-correct.");
        }
    }
}