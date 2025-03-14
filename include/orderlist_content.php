<?php
//建立訂單查詢
$maxRows_rs = 5;    //分頁起始數量
$pageNum_rs = 0;    //起始頁=0
if (isset($_GET['pageNum_order_rs'])) {
    $pageNum_rs = $_GET['pageNum_order_rs'];
}
$startRow_rs = $pageNum_rs * $maxRows_rs;
//列出uorder資料表查詢
$queryFirst = sprintf("SELECT uorder.orderid,uorder.create_date as orderTime,uorder.remark,ms1.msname as howpay, ms2.msname as status, addbook.* 
                    FROM uorder,addbook,multiselect as ms1, multiselect as ms2 
                    WHERE ms2.msid=uorder.status 
                    AND ms1.msid=uorder.howpay  
                    AND uorder.emailid='%d' 
                    AND uorder.addressid=addbook.addressid 
                    ORDER BY uorder.create_date DESC", $_SESSION['emailid']);
$query = sprintf("%s LIMIT %d,%d", $queryFirst, $startRow_rs, $maxRows_rs);
$order_rs = $link->query($query);
$i = 21; //控制第一筆訂單開啟 
?>
<h3>電商藥粧：訂單查詢</h3>
<?php if ($order_rs->rowCount() != 0) { ?>
    <div class="accordion px-2" id="accordion_order">
        <?php while ($data01 = $order_rs->fetch()) { ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne<?= $i; ?>">
                    <a class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $i; ?>" aria-expanded="true" aria-controls="collapseOne<?= $i; ?>">
                        <div class="table-responsive-md" style="width:100%;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td width="10%">訂單編號</td>
                                        <td width="20%">下單日期時間</td>
                                        <td width="15%">付款方式</td>
                                        <td width="15%">訂單狀態</td>
                                        <td width="10%">收件人</td>
                                        <td width="20%">地址</td>
                                        <td width="10%">備註說明</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $data01['orderid']; ?></td>
                                        <td><?= $data01['orderTime']; ?></td>
                                        <td><?= $data01['howpay']; ?></td>
                                        <td><?= $data01['status']; ?></td>
                                        <td><?= $data01['cname']; ?></td>
                                        <td><?= $data01['address']; ?></td>
                                        <td><?= $data01['remark']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </a>
                </h2>
                <div id="collapseOne<?= $i; ?>" class="accordion-collapse collapse <?php echo ($i == 21) ? 'show' : ''; ?>" aria-labelledby="headingOne<?= $i; ?>" data-bs-parent="#accordion_order">
                    <div class="accordion-body">
                        <?php
                        $outlay = "cart.cartid,cart.qty,pd.p_id,pd.classid,pd.p_name,pd.p_intro,pd.p_price,py.fonticon,py.cname,id.dir_name,id2.dir_name AS upfile,pi.img_id,pi.img_file,ms1.msname as status ";
                        $First_Table = sprintf("SELECT %s FROM cart", $outlay);
                        $Join_Table = "LEFT JOIN multiselect as ms1 ON ms1.msid=cart.status ";
                        $Join_Table .= "LEFT JOIN product as pd ON cart.p_id=pd.p_id ";
                        $Join_Table .= "LEFT JOIN product_img AS pi ON pd.p_id = pi.p_id ";
                        $Join_Table .= "LEFT JOIN pyclass AS py on pd.classid=py.classid ";
                        $Join_Table .= "LEFT JOIN img_direct AS id on pd.classid=id.classid ";
                        $Join_Table .= "LEFT JOIN img_direct AS id2 on py.uplink=id2.classid ";
                        $Order = "ORDER BY cartid DESC ";
                        $WHERE = "WHERE pi.sort =1 ";
                        $WHERE .= sprintf("AND cart.orderid='%s'", $data01['orderid']);
                        $SQLstring = sprintf("%s %s %s %s", $First_Table, $Join_Table, $WHERE, $Order);
                        echo '<script>console.log("SQL查詢：' . $SQLstring . '");</script>';
                        $cart_rs = $link->query($SQLstring);
                        $ptotal = 0; //定義累加變數初始值=0
                        ?>

                        <!-- 訂貨明細 -->
                        <div class="table-responsive-md" style="width:100%;">
                            <table class="table table-hover mt-3">
                                <thead>
                                    <tr class="text-bg-primary">
                                        <td width="10%">產品編號</td>
                                        <td width="10%">圖片</td>
                                        <td width="30%">名稱</td>
                                        <td width="10%">價格</td>
                                        <td width="10%">數量</td>
                                        <td width="15%">小計</td>
                                        <td width="15%">狀態</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($cart_data = $cart_rs->fetch()) { ?>
                                        <tr>
                                            <td><?= $cart_data['p_id']; ?></td>
                                            <td><img src="./product_img/<?php echo $cart_data['upfile'] . '/' . $cart_data['dir_name'] . '/' . $cart_data['img_file']; ?>" alt="<?= $cart_data['p_name']; ?>" class="img-fluid">
                                            </td>
                                            <td> <?= $cart_data['p_name']; ?></td>
                                            <td>
                                                <h4 class="color_e600a0 pt-1"><?= $cart_data['p_price']; ?></h4>
                                            </td>
                                            <td>
                                                <?= $cart_data['qty']; ?>
                                            </td>
                                            <td>
                                                <h4 class="color_e600a0 pt-1">$<?= $cart_data['p_price'] * $cart_data['qty']; ?></h4>
                                            </td>
                                            <td>
                                                <?= $cart_data['status']; ?>
                                            </td>
                                        </tr>
                                    <?php $ptotal += $cart_data['p_price'] * $cart_data['qty'];
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">累計：<?= $ptotal; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">運費：100</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="color_red">總計：<?= $ptotal + 100; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /訂貨明細 -->
                    </div>
                </div>
                <hr>
            </div>
        <?php $i++;
        } ?>
    </div>
    <?php
    if (isset($_GET['totalRows_rs'])) {
        $totalRows_rs = $_GET['totalRows_rs'];
    } else {
        $all_rs = $link->query($queryFirst);
        $totalRows_rs = $all_rs->rowCount();
    }
    $totalPage_rs = ceil($totalRows_rs / $maxRows_rs) - 1;
    $prev_rs = "&laquo;";
    $next_rs = "&raquo;";
    $separator = "|";
    $max_links = 20;
    $pages_rs = buildNavigation($pageNum_rs, $totalPage_rs, $prev_rs, $next_rs, $separator, $max_links, true, 3, "order_rs");
    ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php echo $pages_rs[0] . $pages_rs[1] . $pages_rs[2]; ?>
        </ul>
    </nav>
<?php } else { ?>
    <div class="alert alert-info" role="alert">
        抱歉!目前還沒有任何訂單。
    </div>
<?php } ?>