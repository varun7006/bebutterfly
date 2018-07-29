<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("login/LoginModel", "modelObj");
    }

    public function index() {
        $this->load->view('login/login_view');
    }

    public function checkUserLogin() {
        $inputData = json_decode($this->input->raw_input_stream, TRUE);
        $username = $inputData["username"];
        $password = $inputData["password"];

        $loginResult = $this->modelObj->verifyUserLoginDetails($username);

        if ($loginResult['status'] == 'SUCCESS') {
            if (password_verify($password, $loginResult['value']['password'])) {
                $sessionData = array(
                    "user_id" => $loginResult['value']['id'],
                    "name" => $loginResult['value']['first_name']." ".$loginResult['value']['last_name'],
                    "email" => $loginResult['value']['email'],
                    "mobile_no" => $loginResult['value']['mobile_no'],
//                    "address" => $loginResult['value']['address'],
//                "country" => $loginResult['value']['country_id'],
                    "user_type" => $loginResult['value']['user_type']
                );
                $this->session->set_userdata($sessionData);
                echo json_encode(array("status" => "SUCCESS", "value" => $sessionData, "msg" => "user login details are present."));
            } else {
                echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "Invalid Login Credentials"));
            }
        } else {
            echo json_encode($loginResult);
        }
    }

    public function logOutUser() {

        $this->session->sess_destroy();

        echo '{"status":"SUCCESS","msg":"Logout Successfully","value":"1"}';
    }

}
