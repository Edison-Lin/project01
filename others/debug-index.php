<?php
(!isset($_SESSION)) ? session_start() : "";
// if(!isset($_SESSION))session_start(); 
// 如果SESSION沒有啟動，則啟動SESSION功能，這是跨網頁變數存取
?>
<!-- 這是將資料庫，連接程式載入 -->
<?php require('./Connections/conn_db.php') ?>
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
    <div class="container-fluid">
        
        <section id="navbar" class="fixed-top d-none">
            <?php require_once($phpFileDir . "navbar.php"); ?>
        </section>
        
        <section id="Latest Product">
            <?php require_once($phpFileDir . "Latest Product.php"); ?>
        </section>
    </div>
</body>
<?php require_once($phpFileDir . "jsfile.php"); ?>
<script type="text/javascript">
    $(function() {
        $(window).scroll(function() {
            var scroll_position = $(window).scrollTop() / 2;
            $('#mainmenu').css({
                'background-position-x': -scroll_position + 'px',
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        let $navbar = $("#navbar");
        let $mainmenu = $("#mainmenu");
        let $body = $("body");

        function toggleNavbar() {
            let mainmenuTop = $mainmenu.offset().top; // 取得 mainmenu 的位置
            if ($(window).scrollTop() > mainmenuTop) {
                $navbar.removeClass("d-none"); // 顯示 navbar
                $body.addClass("PushDown"); // 下推 body            
            } else {
                $navbar.addClass("d-none"); // 隱藏 navbar
                $body.removeClass("PushDown"); // body 復原
            }
        }

        $(window).on("scroll", toggleNavbar);
    });
</script>

</html>