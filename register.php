<?php
(!isset($_SESSION)) ? session_start() : "";
// if(!isset($_SESSION))session_start(); 
// 如果SESSION沒有啟動，則啟動SESSION功能，這是跨網頁變數存取
?>
<!-- 這是將資料庫，連接程式載入 -->
<?php require_once('Connections/conn_db.php') ?>
<!-- 載入檔案資料夾變數 -->
<?php require_once("include/TopHead.php"); ?>
<!-- 載入共用php函式庫 -->
<?php require_once($phpFileDir . "php_lib.php"); ?>

<!doctype html>
<html lang="zh-tw">

<head>
    <!-- 引入網頁標頭 -->
    <?php require_once($phpFileDir . "headfile.php"); ?>
    <style>
        .input-group>.form-control {
            width: 100%;
        }

        span.error-tips,
        span.error-tips::before {
            font-family: "Font Awesome 5 Free";
            color: red;
            font-weight: 900;
            content: "\f0c4";
        }

        span.valid-tips,
        span.valid-tips::before {
            font-family: "Font Awesome 5 Free";
            color: lightgreen;
            font-weight: 900;
            content: "\f00c";
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
                    <!-- 會員註冊畫面 -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1>會員註冊畫面</h1>
                            <p>請輸入相關資料，*為必須輸入欄位</p>
                        </div>
                    </div>
                    <?php
                    // 註冊帳號
                    if (isset($_POST['formct1']) && $_POST['formct1'] == 'reg') {
                        echo "<script>console.log('註冊程式已執行!');</script>";
                        $email = $_POST['email'];
                        $pw1 = md5($_POST['pw1']);
                        $cname = $_POST['cname'];
                        $tssn = $_POST['tssn'];
                        $birthday = $_POST['birthday'];
                        $mobile = $_POST['mobile'];
                        $myzip = $_POST['myZip'] == '' ? NULL : $_POST['myZip'];
                        $address = $_POST['address'] == '' ? NULL : $_POST['address'];
                        $imgname = $_POST['uploadname'] == '' ? NULL : $_POST['uploadname'];
                        $insertsql = "INSERT INTO member (email,pw1,cname,tssn,birthday,imgname) VALUES ('$email','$pw1','$cname','$tssn','$birthday','$imgname')";
                        $Result = $link->query($insertsql);
                        $emailid = $link->lastInsertId();   //讀剛新增會員編號
                        if ($Result) {
                            //將會員的姓名、電話、地址寫入addbook
                            $insertsql = "INSERT INTO addbook (emailid,setdefault,cname,mobile,myzip,address) VALUES ('$emailid','1','$cname','$mobile','$myzip','$address')";
                            $Result = $link->query($insertsql);
                            $_SESSION['login'] = true;  //設定會員註冊完直接登入
                            $_SESSION['emailid'] = $emailid;
                            $_SESSION['email'] = $email;
                            $_SESSION['cname'] = $cname;
                            echo "<script Type='text/javascript'>alert('謝謝您，會員資料已完成註冊');location.href='index.php';</script>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-8 offset-2 text-left">
                            <form id="reg" name="reg" action="register.php" method="POST">
                                <div class="input-group mb-3">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="*請輸入email帳號" autocomplete="off">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" name="pw1" id="pw1" class="form-control" placeholder="*請輸入密碼">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" name="pw2" id="pw2" class="form-control" placeholder="*請再次確認密碼">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="cname" id="cname" class="form-control" placeholder="*請輸入姓名">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="tssn" id="tssn" class="form-control" placeholder="請輸入身分證號">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="birthday" id="birthday" onfocus="(this.type='date')" class="form-control" placeholder="*請選擇生日">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="請輸入手機號碼">
                                </div>
                                <div class="input-group mb-3">
                                    <select name="myCity" id="myCity" class="form-control">
                                        <option value="">請選擇市區</option>
                                        <?php $city = "SELECT * FROM city WHERE State=0";
                                        $city_rs = $link->query($city);
                                        while ($city_rows = $city_rs->fetch()) { ?>
                                            <option value="<?= $city_rows['AutoNo']; ?>"><?= $city_rows['Name']; ?></option>
                                        <?php } ?>
                                    </select><br>
                                    <select name="myTown" id="myTown" class="form-control">
                                        <option value="">請選擇地區</option>
                                    </select>
                                </div>
                                <label for="address" class="form-label" id="zipcode" name="zipcode">郵遞區號：地址
                                </label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="myZip" id="myZip" value="">
                                    <input type="text" name="address" id="address" class="form-control" placeholder="請輸入後續地址">
                                </div>
                                <label for="fileToUpload" class="form-label">上傳圖片</label>
                                <div class="input-group mb-3">
                                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" title="請上傳圖片圖示" accept="image/x-png,image/jpeg,image/gif,image/jpg," style="width: 100%;">
                                    <p class="mt-1">
                                        <button type="button" class="btn btn-danger" id="uploadForm" name="uploadForm">開始上傳</button>
                                    </p>
                                    <div id="progress-div01" class="progress" style="width:100%;display:none;">
                                        <div id="progress-bar01" class="progress-bar progress-bar-striped" role="progressbar" style="width:0%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <input type="hidden" name="uploadname" id="uploadname" value="" />
                                    <img id="showimg" name="showimg" src="" alt="photo" style="display: none;" class="img-fluid">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="captcha" id="captcha" value="">
                                    <a href="javascript:void(0);" title="按我更新認證" onclick="getCaptcha();">
                                        <canvas id="can"></canvas>
                                    </a>
                                    <input type="text" name="recaptcha" id="recaptcha" class="form-control" placeholder="請輸入認證碼">
                                </div>
                                <input type="hidden" name="formct1" id="formct1" value="reg">
                                <div class="input-group mb-3">
                                    <button type="submit" class="btn btn-success btn-lg">送出</button>
                                </div>
                            </form>
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
</body>
<!-- 引入js.php -->
<?php require_once($phpFileDir . "jsfile.php") ?>
<script type="text/javascript" src="./js/commlib.js"></script>
<script type="text/javascript" src="./js/jquery.validate.js"></script>

<script type="text/javascript">
    //自訂身分證格式驗證
    jQuery.validator.addMethod("tssn", function(value, element, param) {
        var tssn = /^[a-zA-Z]{1}[1-2]{1}[0-9]{8}$/;
        return this.optional(element) || (tssn.test(value));
    });
    //自訂手機格式驗證
    jQuery.validator.addMethod("checkphone", function(value, element, param) {
        var checkphone = /^[0]{1}[9]{1}[0-9]{8}$/;
        return this.optional(element) || (checkphone.test(value));
    });
    //自訂郵遞區號驗證
    jQuery.validator.addMethod("checkMyTown", function(value, element, param) {
        return (value !== "");
    });
    //驗證form #reg表單
    $('#reg').validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: apidirect + 'checkemail.php'
            },
            pw1: {
                required: true,
                maxlength: 20,
                minlength: 4,
            },
            pw2: {
                required: true,
                equalTo: '#pw1'
            },
            cname: {
                required: true,
            },
            tssn: {
                required: true,
                tssn: true,
            },
            birthday: {
                required: true,
            },
            mobile: {
                required: true,
                checkphone: true,
            },
            address: {
                required: true,
            },
            myTown: {
                checkMyTown: true,
            },
            recaptcha: {
                required: true,
                equalTo: '#captcha',
            },
        },
        messages: {
            email: {
                required: 'email信箱不得為空白!!',
                email: 'email信箱格式有誤',
                remote: 'email信箱已經註冊過了',
            },
            pw1: {
                required: '密碼不得為空白!!',
                maxlength: '密碼最大長度為20位(4-20位英文字母與數字的組合)',
                minlength: '密碼最小長度為4位(4-20位英文字母與數字的組合)',
            },
            pw2: {
                required: '確認密碼不得為空白!!',
                equalTo: "兩次輸入的密碼必須一致!",
            },
            cname: {
                required: '使用者名稱不得為空白!!',
            },
            tssn: {
                required: '使用者身分證不得為空白!!',
                tssn: '身分證ID格式有誤',
            },
            birthday: {
                required: '生日不得為空白!!',
            },
            mobile: {
                required: '手機號碼不得為空白!!',
                checkphone: '手機號碼格式有誤',
            },
            address: {
                required: '地址不得為空白!!',
            },
            myTown: {
                checkMyTown: '需選擇郵遞區號!!',
            },
            recaptcha: {
                required: '驗證碼不得為空白!!',
                equalTo: '驗證碼需相同!!',
            },
        },
    });
    //取得元素ID
    function getId(el) {
        return document.getElementById(el);
    }

    //圖示上傳處理
    $("#uploadForm").click(function(e) {
        // console.log("點擊圖片上傳!  1次");
        var fileName = $('#fileToUpload').val();
        var idxDot = fileName.lastIndexOf(".") + 1;
        let extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile == "jpg" || extFile == "jpeg" || extFile == "png" || extFile == "gif") {
            $('#progress-div01').css("display", "flex");
            let file1 = getId("fileToUpload").files[0];
            let formdata = new FormData();
            formdata.append("file1", file1);
            let ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", apidirect + "file_upload_parser.php");
            ajax.send(formdata);
            return false;
        } else {
            alert('目前只支援jpg,jpeg,png,gif檔案格式上傳！');
        }
    });
    //上傳過程顯示百分比
    function progressHandler(event) {
        let percent = Math.round((event.loaded / event.total) * 100);
        $("#progress-bar01").css("width", percent + "%");
        $("#progress-bar01").html(percent + "%");
    }
    //上傳完成處理顯示圖片
    function completeHandler(event) {
        let data = JSON.parse(event.target.responseText)
        if (data.success == true) {
            $('#uploadname').val(data.fileName);
            $('#showimg').attr({
                'src': 'uploads/' + data.fileName,
                'style': 'display:block;'
            });
            $('button.btn.btn-danger').attr({
                'style': 'display:none;'
            });
        } else {
            alert(data.error);
        }
    }
    //Upload Failed:上傳發生錯誤處理
    function errorHandler(event) {
        alert("Upload Failed:上傳發生錯誤!");
    }
    //Upload Aborted:上傳作業取消處理
    function abortHandler(event) {
        alert("Upload Aborted:上傳作業取消!");
    }


    //產生驗證碼
    // 驗證form #reg表單

    function getCaptcha() {
        var inputTxt = document.getElementById("captcha");
        // var inputTxt = $("#captcha");
        //can為canvas 的ID名稱
        //150=影像寬，50=影像高，blue=影像背景顏色
        //white=文字顏色，28px=文字大小，5=認證碼長度
        inputTxt.value = captchaCode("can", 220, 80, "lightblue", "red", "40px", 6);
    }
    $(function() {


        getCaptcha();

        //取得縣市代碼後查詢鄉鎮市名稱
        $("#myCity").change(function() {
            var CNo = $("#myCity").val();
            if (CNo == "") {
                return false;
            }
            $.ajax({
                url: apidirect + 'Town_ajax.php',
                type: 'post',
                dataType: 'json',
                data: {
                    CNo: CNo,
                },
                success: function(data) {
                    if (data.c == true) {
                        $("#myTown").html(data.m);
                        $("#myzip").val("");
                    } else {
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("目前系統無法連接到後台資料庫!");
                }
            });
        });

        //取得鄉鎮市代碼，查詢郵遞區號放入#myZip,#zipcode
        $("#myTown").change(function() {
            var AutoNo = $("#myTown").val();
            if (AutoNo == "") {
                return false;
            }
            $.ajax({
                url: apidirect + 'Zip_ajax.php',
                type: 'get',
                dataType: 'json',
                data: {
                    AutoNo: AutoNo,
                },
                success: function(data) {
                    if (data.c == true) {
                        $("#myZip").val(data.Post);
                        $("#zipcode").html(data.Post + data.Cityname + data.Name);
                    } else {
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("目前系統無法連接到後台資料庫!");
                }
            });
        });
    });
</script>

</html>