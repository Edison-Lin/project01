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
<?php require_once($phpFileDir . "IfNoLogin.php"); ?>
<!-- 判斷是否沒有購物時，跳回cart.php -->
<?php
if (false) { //判斷是否沒有購物時，跳回cart.php
    if (!isset($_SESSION['cart-check']) || $_SESSION['cart-check'] != true) {
        $sPath = "cart.php";
        header(sprintf("Location:%s", $sPath));
    }
}
 ?>
<!doctype html>
<html lang="zh-tw">

<head>
    <!-- 引入網頁標頭 -->
    <?php require_once($phpFileDir . "headfile.php"); ?>
    <style type="text/css">
        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-bottom: none;
            border-top: 1px solid #dee2e6;
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
                    <!-- 引入結帳模組 -->
                    <?php require_once($phpFileDir . "chkout_content.php"); ?>
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
<!-- Modal <其他收件人>彈出視窗 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">收件人資訊
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col">
                                <input type="text" name="cname" id="cname" class="form-control" placeholder="收件人姓名">
                            </div>
                            <div class="col">
                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="收件人電話">
                            </div>
                            <div class="col">
                                <select name="myCity" id="myCity" class="form-select">
                                    <option value="">請選擇市區</option>
                                    <?php $city = "SELECT * FROM city WHERE State=0";
                                    $city_rs = $link->query($city);
                                    while ($city_rows = $city_rs->fetch()) { ?>
                                        <option value="<?= $city_rows['AutoNo']; ?>"><?= $city_rows['Name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col">
                                <select name="myTown" id="myTown" class="form-select">
                                    <option value="">請選擇地區</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="hidden" name="myZip" id="myZip" value="">
                                <label for="address" id="add_label" name="add_label">郵遞區號：</label>
                                <input type="text" id="address" name="address" class="form-control" placeholder="地址" value="">
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center">
                            <div class="col-auto">
                                <button type="button" class="btn btn-success" id="recipient" name="recipient">新增收件人</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <?php
                    $SQLstring = sprintf("SELECT *,city.Name AS ctName,town.Name AS toName FROM addbook,city,town WHERE emailid='%d' AND addbook.myzip=town.Post AND town.AutoNo=city.AutoNo ORDER BY setdefault DESC, addressid ASC;", $_SESSION['emailid']);
                    $addbook_rs = $link->query($SQLstring);
                    ?>
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">收件人姓名</th>
                                <th scope="col">電話</th>
                                <th scope="col">地址</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($data = $addbook_rs->fetch()) { ?>

                                <tr>
                                    <th scope="row">
                                        <input type="radio" name="gridRadios" id="gridRadios" value="<?= $data['addressid']; ?>" <?= ($data['setdefault']) ? 'checked' : '' ?>>
                                    </th>
                                    <td><?= $data['cname']; ?></td>
                                    <td><?= $data['mobile']; ?></td>
                                    <td><?php echo $data['myzip'] . $data['ctName'] . $data['toName'] . $data['address']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉Close</button>

                </div>
            </div>
        </div>
    </div>
<!-- 進行中畫面(轉圈圈!) -->
<div id="loading" name="loading" style="display:none; position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(255,255,255,0.5); z-index:9999;"><i class="fas fa-spinner fa-spin fa-5x fa-fw" style="position:absolute; top:50%; left:50%;"></i></div>
</body>

</html>
<!-- 引入js.php -->
<?php require_once($phpFileDir . "jsfile.php") ?>
<script>
    $(function() {
        //結帳處理程式
        $("#btn04").click(function() {
            let msg = "系統將進行結帳處理，請確認產品數量、金額與收件人資訊是否正確!";
            if (!confirm(msg)) return false;
            $("#loading").show();
            var addressid = $('input[name="gridRadios"]:checked').val();
            $.ajax({
                url: apidirect + 'addorder.php',
                type: 'post',
                dataType: 'json',
                data: {
                    addressid: addressid,
                },
                success: function(response) {
                    if (response.c == true) {
                        alert(response.m);
                        window.location.href = 'index.php';
                    } else {
                        alert("Database response error:" + response.m);
                    }
                },
                error: function(response) {
                    alert("目前系統無法連接到後台資料庫!");
                }
            });
        });

        //新增收件人程式
        $('#recipient').click(function() {
            var validate = 0,
                msg = "";
            var cname = $('#cname').val();
            var mobile = $('#mobile').val();
            var myZip = $('#myZip').val();
            var address = $('#address').val();
            var checkphone = /^[0]{1}[9]{1}[0-9]{8}$/;
            if (cname == "") {
                msg = msg + "收件人不得為空白!\n";
                validate = 1;
            }
            if (mobile == "") {
                msg = msg + "電話不得為空白!\n";
                validate = 1;
            } else if (checkphone.test(mobile) == false) {
                msg = msg + "電話格式有誤!\n";
            }
            if (myZip == "") {
                msg = msg + "郵遞區號不得為空白!\n";
                validate = 1;
            }
            if (address == "") {
                msg = msg + "地址不得為空白!\n";
                validate = 1;
            }
            if (validate) {
                alert(msg);
                return false;
            }

            //新增收件人寫入資料庫
            $.ajax({
                url: apidirect + 'addbook.php',
                type: 'post',
                dataType: 'json',
                data: {
                    cname: cname,
                    mobile: mobile,
                    myZip: myZip,
                    address: address,
                },
                success: function(data) {
                    if (data.c == true) {
                        alert(data.m);
                        window.location.reload();
                    } else {
                        alert("Database response error:" + data.m);
                    }
                },
                error: function(data) {
                    alert("目前系統無法連接到後台資料庫!");
                }
            });
        });

        //更換收件人程序
        $("input[name=gridRadios]").change(function() {
            var addressid = $(this).val();
            $.ajax({
                url: apidirect + 'changeaddr.php',
                type: 'post',
                dataType: 'json',
                data: {
                    addressid: addressid,
                },
                success: function(response) {
                    if (response.c == true) {
                        alert(response.m);
                        window.location.reload();
                    } else {
                        alert("Database response error:" + response.m);
                    }
                },
                error: function(response) {
                    alert("目前系統無法連接到後台資料庫!");
                }
            });
        });

        //取得縣市代碼後查詢鄉鎮市名稱
        $("#myCity").change(function() {
            var CNo = $("#myCity").val();
            if (CNo == "") {
                return false;
            }
            $('#myZip').val('');
            $("#add_label").html('郵遞區號：');
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
                $("#myZip").val('');
                $("#add_label").html('');
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
                        $("#add_label").html('郵遞區號：' + data.Post + data.Cityname + data.Name);
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