<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8'); //return json string
?>
<?php require_once("../include/TopHead.php"); ?>
<?php
require_once($Connection . 'conn_db.php');
$Zip = sprintf("SELECT town.Name,town.Post,city.Name AS Cityname FROM town,city WHERE town.AutoNo=city.AutoNo AND town.townNo='%d'", $_GET["AutoNo"]);
$Zip_rs = $link->query($Zip);
$Zip_num = $Zip_rs->rowCount();

if ($Zip_num > 0) {
    $Zip_rows = $Zip_rs->fetch();
    $retcode = array(
        'c' => true,
        'Post' => $Zip_rows['Post'],
        'Name' => $Zip_rows['Name'],
        'Cityname' => $Zip_rows['Cityname']
    );
} else {
    $retcode = array('c' => false, 'm' => "找不到相關資料。");
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);

return;
