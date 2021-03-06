<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
//require_once APPPATH . '/core/PHPExcel.php';
//
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Shop extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("shop/shopModel", "modelObj");
//        $this->load->model("core/coreModel", "coreObj");
    }

    public function index() {
        $this->load->view('user/user_view');
    }

    public function getShopList() {
        $userList = $this->modelObj->getShopList();
        if (count($userList) > 0) {
            echo json_encode(array("status" => "SUCCESS", "value" => $userList, "msg" => "user details are present."));
        } else {
            echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "unable to save new user."));
        }
    }

    public function getShopOwnerList() {
        $ownerList = $this->modelObj->getShopOwnerList();
        if (count($ownerList) > 0) {
            echo json_encode(array("status" => "SUCCESS", "value" => $ownerList, "msg" => "user details are present."));
        } else {
            echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "No user details are present."));
        }
    }

    public function generateUserExcel() {
        require_once APPPATH . "core/phpspreadsheet/vendor/autoload.php";
        $userList = $this->modelObj->getUserList();

        if ($userList['status'] == 'SUCCESS' && $userList['value']['count'] > 0) {
            $spreadsheet = new Spreadsheet();
            $row = 1;
            $col = 0;
            $headingArr = array("S.No", "Name", "Email", "Website", "Phone No", "Adddress", "Comment");
            foreach ($headingArr as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
            foreach ($userList['value']['list'] as $key => $value) {
                $col = 0;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, ($key + 1));
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['name']);
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['email']);
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['website']);
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['mobile_no']);
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['address']);
                $col++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value['comment']);
                $col++;
                $row++;
            }


            $Excel_writer = new Xlsx($spreadsheet);
            $fileName = 'exported_clients.xlsx';
            if (ob_get_contents())
                ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=exported_clients.xlsx");
            header("Cache-Control: max-age=0");
            $Excel_writer->save('php://output');
            exit;
        }else {
            echo "No Client Found";
            exit;
        }
    }

    public function saveNewShop() {

        $dataArr = json_decode($this->input->raw_input_stream, TRUE)['shop_data'];
        if (count($dataArr) > 0) {
            if (isset($dataArr['name']) && !empty($dataArr['name']) && isset($dataArr['owner_id']) && !empty($dataArr['owner_id'])) {
                $saveResult = $this->modelObj->saveNewShopDetails($dataArr);
                echo json_encode(array("status" => "SUCCESS", "value" => $saveResult, "msg" => "Details Saved Successfully"));
            } else {
                echo json_encode(array("status" => "ERR", "value" => "-1", "msg" => "Please fill all the manadatory details"));
                exit;
            }
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
