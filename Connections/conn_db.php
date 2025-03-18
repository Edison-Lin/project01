<?php
//PDO sql資料庫連線程式
$dsn = "mysql:host=sql202.infinityfree.com;dbname=if0_38544621_expstore;charset=utf8";
$user="if0_38544621";
$password="Ctf0Lix6fGkh";
$link=new PDO($dsn,$user,$password);
date_default_timezone_set("Asia/Taipei");
//php 5.3.6 以前版本
$link->exec("set names utf8");
$DB_BASE='expstore';
?>