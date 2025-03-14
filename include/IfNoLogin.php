<?php
// if (!isset($_SESSION['login'])||$_SESSION['db_base']!=$DB_BASE) {
if (!isset($_SESSION['login'])) {
    // $sPath="login.php?sPath=checkout.php";
    $sPath = "login.php?sPath=" . basename($_SERVER['PHP_SELF']);
    // $sPath="login.php?sPath=checkout";
    //網頁跳轉 
    //1.html語法
    header(sprintf("Location:%s", $sPath));
    //jcavascript 寫法
    // echo "<script>window.location.href = '$sPath';</script>";/* 返回上一頁*/
}
?>