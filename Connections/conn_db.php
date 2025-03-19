<?php
// 設定時區
date_default_timezone_set("Asia/Taipei");

$dsn_local = "mysql:host=localhost;dbname=expstore;charset=utf8";
$user_local = "sales";
$password_local = "123456";

$dsn_remote = "mysql:host=sql202.infinityfree.com;dbname=if0_38544621_expstore;charset=utf8";
$user_remote = "if0_38544621";
$password_remote = "Ctf0Lix6fGkh";

try {
    // 先嘗試連接 InfinityFree 資料庫（僅限在 InfinityFree 網站內部運行）
    $link = new PDO($dsn_remote, $user_remote, $password_remote);
    echo "<script>console.log('"."成功連接 InfinityFree 資料庫！"."');</script>";
} catch (PDOException $e) {
    // 若失敗，則改用本機資料庫
    try {
        $link = new PDO($dsn_local, $user_local, $password_local);
        echo "<script>console.log('"."使用本機資料庫！"."');</script>";
    } catch (PDOException $e) {
        die("資料庫連線失敗：" . $e->getMessage());
    }
}

// 設定 PDO 屬性
$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$link->exec("set names utf8");

$DB_BASE = 'expstore';
?>
