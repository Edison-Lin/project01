<?php
$Connection="../Connections/";
include_once($Connection."conn_db.php");
if(isset($_GET['emailid'])){
    $emailid=$_GET['emailid'];
    $PWOld=MD5($_GET['PWOld']);
    $query=sprintf("SELECT emailid FROM member WHERE emailid='%d' AND pw1='%s'",$emailid,$PWOld);
    $result=$link->query($query);
    $row=$result->rowCount();
    if($row!=0){
        echo 'true';
        return;
    }
}
echo 'false';
return;
?>