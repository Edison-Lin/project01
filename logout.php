<?php
(!isset($_SESSION)) ? session_start() : "";
// if(!isset($_SESSION))session_start(); 
// 如果SESSION沒有啟動，則啟動SESSION功能，這是跨網頁變數存取
?>
<?php
$_SESSION['login']=null;
$_SESSION['emailid']=null;
$_SESSION['email']=null;
$_SESSION['cname']=null;
unset($_SESSION['login']);
unset($_SESSION['emailid']);
unset($_SESSION['email']);
unset($_SESSION['cname']);
if(isset($_GET['sPath'])){
    // $sPath=$_GET['sPath'].".php";
    $sPath=$_GET['sPath'];
}else{
    // 登入完成預設要進入首頁
    $sPath="index.php";
}
// $sPath="index.php";
header(sprintf("location:%s",$sPath));
?>