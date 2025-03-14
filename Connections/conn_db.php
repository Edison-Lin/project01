<?php
//PDO sql資料庫連線程式
$dsn = "mysql:host=localhost;dbname=expstore;charset=utf8";
$user="sales";
$password="123456";
$link=new PDO($dsn,$user,$password);
date_default_timezone_set("Asia/Taipei");
//php 5.3.6 以前版本
$link->exec("set names utf8");
$DB_BASE='expstore';
?>