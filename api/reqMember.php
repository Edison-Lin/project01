<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');
//return json string
$Connection = "../Connections/";
require_once($Connection . 'conn_db.php');


if (isset($_GET['emailid']) && $_GET['emailid'] != "") {
    $emailid = $_GET['emailid'];
    $birthday = $_GET['birthday'];
    $cname = $_GET['cname'];
    $imgname = $_GET['imgname'];
    $tssn = $_GET['tssn'];
    $query = sprintf("UPDATE member SET cname='%s', birthday='%s', imgname='%s', tssn='%s' WHERE emailid='%d'", $cname, $birthday, $imgname, $tssn, $emailid);
    $result = $link->query($query);
    if ($result) {
        (!isset($_SESSION)) ? session_start() : "";
        $_SESSION['cname'] = $cname;
        $_SESSION['imgname'] = $imgname; //加入會員頭像
        $retcode = array('c' => "1", 'm' => "會員資訊已更新。");
    } else {
        $retcode = array('c' => "0", 'm' => "抱歉!資料無法寫入後台資料庫，請聯絡管理人員。");
    }
    echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
}
return;
