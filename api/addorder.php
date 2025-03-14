<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');
//return json string
?>
<?php require_once("../include/TopHead.php"); ?>
<?php
require_once($Connection . 'conn_db.php');
(!isset($_SESSION)) ? session_start() : "";

if (isset($_SESSION['emailid']) && $_SESSION['emailid'] != "") {
    $emailid = $_SESSION['emailid'];
    $addressid = $_POST['addressid'];
    $ip=$_SERVER['REMOTE_ADDR'];
    $orderid=date('Ymdhis').rand(10000,99999);//自行產生時間+訂單編號
    $query = sprintf("INSERT INTO uorder (orderid,emailid,addressid,howpay,paystatus,status) VALUES('%s','%d','%d','3','35','7') ", $orderid,$emailid,$addressid);
    $result = $link->query($query);
    if ($result) {
        $query = sprintf("UPDATE cart SET orderid='%s',emailid='%d',status='8' WHERE ip='%s' AND orderid is NULL", $orderid,$emailid,$ip);
    $result = $link->query($query);
        $retcode = array('c' => "1", 'm' => "謝謝您，系統已完成結帳，請在首頁查閱訂單處理狀態。");
        
    } else {
        //當寫入資料庫超時，會直接回傳錯誤訊息
        $retcode = array('c' => "0", 'm' => "抱歉!資料無法寫入後台資料庫，請聯絡管理人員。");
    }
    echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
}
return;
?>