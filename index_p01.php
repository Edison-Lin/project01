<?php
(!isset($_SESSION)) ? session_start() : "";
// if(!isset($_SESSION))session_start(); 
// 如果SESSION沒有啟動，則啟動SESSION功能，這是跨網頁變數存取
?>
<!-- 這是將資料庫，連接程式載入 -->
<?php require('Connections/conn_db.php') ?>
<!-- 載入檔案資料夾變數 -->
<?php require_once("include/TopHead.php"); ?>
<!-- 載入共用php函式庫 -->
<?php require_once($phpFileDir . "php_lib.php"); ?>
<!doctype html>
<html lang="zh-tw">
<head>
    <?php require_once($phpFileDir . "headfile.php"); ?>
</head>
<body>
    <div>
        <section id="mainmenu"  class="container-fluid">
            <!--  進場影像  -->
        </section>
        <section id="mainmenu-bottom">
            <!--  naverbar進入的空間  -->
        </section>
         <section id="navbar" class="fixed-top"> 
            <?php require_once($phpFileDir . "navbar.php"); ?>
        </section>
        <section id="news">
            <?php require_once($phpFileDir . "news.php"); ?>
        </section>
        <section id="Latest Product">
            <?php require_once($phpFileDir . "Latest Product.php"); ?>
        </section>
        
        <section id="content">
            <?php require_once($phpFileDir . "index_hot.php"); ?>
        </section>
        <hr>
        <section id="scontent">
            <?php require_once($phpFileDir . "scontent.php"); ?>
        </section>
        <section id="footer">
            <?php require_once($phpFileDir . "footer.php"); ?>
        </section>
    </div>
</body>
<?php require_once($phpFileDir . "jsfile.php"); ?>
<script type="text/javascript">
    $(function() {
        $(window).scroll(function() {
            var scroll_position = $(window).scrollTop()/2 ;
            $('#mainmenu').css({
                'background-position-x': -scroll_position + 'px',
            });
        });
    });
</script>
<script>
 
</script>

</html>