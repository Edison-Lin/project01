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
<?php
// 取得要返回的PHP頁面
if (isset($_GET['sPath'])) {
    // $sPath=$_GET['sPath'].".php";
    $sPath = $_GET['sPath'];
} else {
    // 登入完成預設要進入首頁
    $sPath = "index.php";
}
// 檢查是否完成登入驗證
if (isset($_SESSION['login'])) {
    // header(sprintf("Location: %s", $sPath));
    echo "<script>window.location.href='" . $sPath . "'; </script>";
}
?>
<!doctype html>
<html lang="zh-tw">

<head>
    <!-- 引入網頁標頭 -->
    <?php require_once($phpFileDir . "headfile.php"); ?>
    <!-- 會員登入頁面專用CSS樣式設定 -->
    <style type="text/css">
        .col-md-10 {
            background-repeat: no-repeat;
            background-image: linear-gradient(rgb(138, 236, 47), rgb(93, 28, 136));
        }

        /* Card component */
        .mycard.mycard-container {
            max-width: 400px;
            height: 450px;
        }

        .mycard {
            background-color: #f7f7f7;
            padding: 20px 25px 30px;
            margin: 0 auto 25px;
            margin-top: 50px;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        .profile-img-card {
            margin: 0 auto 10px auto;
            display: block;
            width: 100px;
        }

        .profile-name-card {
            font-size: 20px;
            text-align: center;
        }

        .form-signin input[type="email"],
        .form-signin input[type="password"],
        .form-signin button {
            width: 100%;
            height: 44px;
            font-size: 16px;
            display: block;
            margin-bottom: 20px;
        }

        .btn.btn-signin {
            font-weight: 700;
            background-color: rgb(104, 145, 162);
            color: white;
            height: 38px;
            transition: background-color 1s;
        }

        .btn.btn-signin:hover,
        .btn.btn-signin:active,
        .btn.btn-signin:focus {
            background-color: rgb(12, 97, 33);
        }

        .other a {
            color: rgb(184, 17, 109);
        }

        .other a:hover,
        .other a:active,
        .other a:focus {
            color: rgb(12, 97, 33);
        }
    </style>
</head>

<body>
    <!-- 頁首 Header -->
    <section id="header">
        <!-- 引入導覽列 -->
        <?php require_once($phpFileDir . "navbar.php"); ?>
    </section>
    <!-- 內容區 Content -->
    <section id="content">
        <div class="container-fluid">
            <div class="row">
                <!-- 左側內容區 -->
                <div class="col-md-2">
                    <!-- 搜尋欄 -->
                    <!-- 引入sidebar分類導覽 -->
                    <?php require_once($phpFileDir . "sidebar.php"); ?>
                    <!-- 引入熱銷商品模組 -->
                    <?php require_once($phpFileDir . "hot.php"); ?>
                </div>
                <!-- 右側內容區 -->
                <div class="col-md-10">
                    <!-- 會員登入HTML頁面 -->
                    <div class="mycard mycard-container">
                        <img id="profile-img" class="profile-img-card" src="images/logo03.svg" alt="logo">
                        <p id="profile-name" class="profile-name-card"> 會員登入</p>
                        <form action="" method="POST" class="form-signin" id="form1">
                            <input type="email" id="inputAccount" name="inputAccount" class="form-control" placeholder="Account" required autofocus />
                            <input type="password" id="inputPassword" name="inputPassword"
                                class="form-control" placeholder="Password"
                                autocomplete="current-password" required>
                            <div class="row d-flex">
                                <div class="col-3">
                                    <input type="hidden" name="captcha" id="captcha" value="">
                                    <a href="javascript:void(0);" title="按我更新認證" onclick="getCaptcha();">
                                        <canvas id="can"></canvas>
                                    </a>
                                </div>
                                <div class="col-5" style="margin-left: 120px !important;">
                                    <input type="text" name="recaptcha" id="recaptcha" class="form-control" placeholder="請輸入認證碼" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-signin mt-4">sign in</button>
                        </form>
                        <div class="other mt-5 text-center">
                            <a href="register.php?sPath=<?= $sPath; ?>">New user</a>/<a href="#">Forget the password?</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <hr>
    <section id="scontent">
        <!-- 引入scontent 服務說明-->
        <?php require_once($phpFileDir . "scontent.php"); ?>
    </section>
    <section id="footer">
        <!-- 引入footer 聯絡資訊-->
        <?php require_once($phpFileDir . "footer.php"); ?>
    </section>
    <div id="loading" name="loading" style="display:none; position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(255,255,255,0.5); z-index:9999;"><i class="fas fa-spinner fa-spin fa-5x fa-fw" style="position:absolute; top:50%; left:50%;"></i></div>
</body>
<!-- 引入js.php -->
<?php require_once($phpFileDir . "jsfile.php") ?>
<script type="text/javascript" src="./js/commlib.js"></script>
<script type="text/javascript">
    $(function() {
        $("#form1").submit(function() {
            const inputAccount = $("#inputAccount").val();
            const inputPassword = MD5($("#inputPassword").val());
            const captcha = $("#captcha").val();
            const recaptcha = $("#recaptcha").val();
            $("#loading").show();
            // 利用$ajax函數呼叫後台的auth_user.php驗證帳號密碼
            if(recaptcha==captcha){
                $.ajax({
                    url: apidirect + 'auth_user.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        inputAccount: inputAccount,
                        inputPassword: inputPassword,
                    },
                    success: function(data) {
                        if (data.c == true) {
                            alert(data.m);
                            console.log(data.m);
                            window.location.href = "<?php echo $sPath; ?>";
                        } else {
                            alert(data.m);
                            console.log(data.m);
                        }
                    },
                    error: function(data) {
                        alert('系統目前無法連接到後台資料庫。');
                    }
                });
            }else{
                alert("驗證碼錯誤，請重新再試一次!");
            }
        });
    });
    //產生驗證碼
    // 驗證form #reg表單

    function getCaptcha() {
        var inputTxt = document.getElementById("captcha");
        // var inputTxt = $("#captcha");
        //can為canvas 的ID名稱
        //150=影像寬，50=影像高，blue=影像背景顏色
        //white=文字顏色，28px=文字大小，5=認證碼長度
        inputTxt.value = captchaCode("can", 200, 60, "pink", "red", "32px", 5);
    }
    $(function() {
        getCaptcha();
    });
</script>

</html>