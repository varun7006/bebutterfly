<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        
        parent::__construct();

//        if (!$this->session->userdata('user_id')) {
//            $this->output->set_status_header('401');
//            exit;
//        }
    }

}
