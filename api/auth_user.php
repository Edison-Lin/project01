<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');
//return json string
(!isset($_SESSION))?session_start():"";
?>
<?php require_once("../include/TopHead.php"); ?>
<?php
require_once($Connection . 'conn_db.php');
if (isset($_POST['inputAccount']) && isset($_POST['inputPassword'])) {
    $inputAccount = $_POST['inputAccount'];
    $inputPassword = $_POST['inputPassword'];
    $query = sprintf("SELECT * FROM member WHERE email='%s' AND pw1='%s'", $inputAccount, $inputPassword);
    $result = $link->query($query);
    if ($result) {
        if ($result->rowCount() == 1) {
            $data = $result->fetch();
            if ($data['active']) {
                $_SESSION['login'] = true;
                $_SESSION['db_base']=$DB_BASE;
                $_SESSION['emailid'] = $data['emailid'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['cname'] = $data['cname'];
                $_SESSION['imgname'] = $data['imgname'];//加入會員頭像
                $retcode = array('c' => "1", 'm' => "會員驗證成功!");
            } else {
                $retcode = array('c' => "2", 'm' => "會員帳號被鎖定!請聯絡管理人員。");
            }
        }else{
            $retcode = array('c' => "2", 'm' => "帳號或密碼錯誤!需要重新輸入。");
        }
    } else {
        //當寫入資料庫超時，會直接回傳錯誤訊息
        $retcode = array('c' => "0", 'm' => "抱歉!會員驗證失敗，請聯絡管理人員。");
    }
    echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
}
return;
?>