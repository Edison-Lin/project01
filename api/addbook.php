<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');
//return json string
?>
<?php require_once("../include/TopHead.php"); ?>
<?php
require_once($Connection . 'conn_db.php');
(!isset($_SESSION)) ? session_start() : "";

if (isset($_SESSION['emailid']) && $_SESSION['emailid']!="") {
    $emailid = $_SESSION['emailid'];
    $cname = $_POST['cname'];
    $mobile = $_POST['mobile'];
    $myZip = $_POST['myZip'];
    $address = $_POST['address'];
    $query = sprintf("UPDATE addbook SET setdefault='0' WHERE emailid='%d' AND setdefault='1'", $emailid);
    $result = $link->query($query);
    $query = "INSERT INTO addbook (setdefault,emailid,cname,mobile,myzip,address) VALUES ('1','$emailid','$cname','$mobile','$myZip','$address') ;";
    $result = $link->query($query);
    if ($result) {
        $retcode = array('c' => "1", 'm' => "收件人資訊已新增。");
    } else {
        //當寫入資料庫超時，會直接回傳錯誤訊息
        $retcode = array('c' => "0", 'm' => "抱歉!資料無法寫入後台資料庫，請聯絡管理人員。");
    }
    echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
}
return;

?>