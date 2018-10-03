<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
//require_once APPPATH . '/core/PHPExcel.php';
//
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product extends MY_Controller {

    public function __construct() {
        parent::__construct();
//        echo 'cd';exit;
        $this->load->model("product/productModel", "modelObj");
        $this->load->model("core/coreModel", "coreObj");
    }

    public function index() {
        $this->load->view('user/user_view');
    }

    public function getProductList() {
        $userList = $this->modelObj->getProductList();
        if (count($userList) > 0) {
            echo json_encode(array("status" => "SUCCESS", "value" => $userList, "msg" => "Product details are present."));
        } else {
            echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "No Porduct Found."));
        }
    }

    public function saveNewProduct() {

        $dataArr = json_decode($this->input->raw_input_stream, TRUE)['product_data'];
        if (count($dataArr) > 0) {
            $saveResult = $this->modelObj->saveNewProduct($dataArr);
            echo json_encode(array("status" => "SUCCESS", "value" => $saveResult, "msg" => "Details Saved Successfully"));
        } else {
            echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "Please all the details"));
        }
    }

    public function updateUser() {
        $dataArr = json_decode($this->input->post("data"), TRUE);
        $updateId = $this->input->post("id");
        unset($dataArr['repassword']);
        if ($updateId != null && $updateId != '') {
            $updateResult = $this->modelObj->updateUserDetails($dataArr, $updateId);
            echo json_encode($updateResult);
        }
    }

    public function deleteUser() {
        $deleteId = $this->input->post("id");
        if ($deleteId != null && $deleteId != '') {
            $deleteResult = $this->modelObj->deleteUser($deleteId);
            echo json_encode($deleteResult);
        }
    }

    public function saveExcel() {

        $excelResult = array();
        if (!empty($_FILES)) {
            $excelResult = $this->coreObj->excelUpload();
            $objPHPExcel = PHPExcel_IOFactory::load($excelResult['value']);
            $sheetData = $objPHPExcel->getActiveSheet()->rangeToArray('A1:F105');
            $headercolArr = array("NAME", "EMAIL", "WEBSITE", "MOBILE NO.", "ADDRESS", "COUNTRY");

            foreach ($headercolArr as $key => $value) {
                if ($sheetData[0][$key] != $value) {
                    echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "Invalid File Format"));
                    exit;
                }
            }
            $insertArr = array();
            for ($i = 1; $i < count($sheetData); $i++) {
                if (((isset($sheetData[$i][0])) && trim($sheetData[$i][0] != null) && trim($sheetData[$i][0]) != '')) {
                    $tmpArr = array();
                    $tmpArr['name'] = $sheetData[$i][0];
                    $tmpArr['email'] = $sheetData[$i][1];
                    $tmpArr['website'] = $sheetData[$i][2];
                    $tmpArr['mobile_no'] = $sheetData[$i][3];
                    $tmpArr['address'] = $sheetData[$i][4];
                    $insertArr[] = $tmpArr;
                } else {
                    break;
                }
            }
            if (count($insertArr) > 0) {
                $insertResult = $this->modelObj->saveUserByExcel($insertArr);
                echo json_encode($insertResult);
            } else {
                echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "unable to save new user."));
            }
        }
    }

}
