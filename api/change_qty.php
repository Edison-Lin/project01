<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8'); //return json string
?>
<?php require_once("../include/TopHead.php"); ?>
<?php
require_once($Connection . 'conn_db.php');
if (isset($_POST['cartid']) && isset($_POST['qty'])) {
    $qty = $_POST['qty'];
    $cartid = $_POST['cartid'];
    // $query = "UPDATE cart SET qty='$qty' WHERE cart.cartid='$cartid'";
    $query = sprintf("UPDATE cart SET qty='%d' WHERE cart.cartid=%d",$qty,$cartid);
    
    $result = $link->query($query);
    $string = "謝謝您!訂購產品數量已更新，目前共下訂" . $qty . "個";
    if ($result) {
        $retcode = array('c' => "1", 'm' => $string);
    } else {
        //當寫入資料庫超時，會直接回傳錯誤訊息
        $retcode = array('c' => "0", 'm' => "抱歉!資料無法寫入後台資料庫，請聯絡管理人員。");
    }
    echo json_encode($retcode);
}
return;
