<?php
                    $SQLstring = sprintf("SELECT *,city.Name AS ctName,town.Name AS toName FROM addbook,city,town WHERE emailid='%d' AND setdefault='1' AND addbook.myzip=town.Post AND town.AutoNo=city.AutoNo ", $_SESSION['emailid']);
                    $addbook_rs = $link->query($SQLstring);
                    if ($addbook_rs && $addbook_rs->rowCount() != 0) {
                        $data = $addbook_rs->fetch();
                        $cname = $data['cname'];
                        $mobile = $data['mobile'];
                        $myzip = $data['myzip'];
                        $address = $data['address'];
                        $ctName = $data['ctName'];
                        $toName = $data['toName'];
                    } else {
                        $cname = '';
                        $mobile = '';
                        $myzip = '';
                        $address = '';
                        $ctName = '';
                        $toName = '';
                    } ?>
                    <h3>會員：<?= $_SESSION['cname']; ?>　結帳作業</h3>
                    <?php
                    $outlay = "cart.cartid,cart.qty,pd.p_id,pd.classid,pd.p_name,pd.p_intro,pd.p_price,py.fonticon,py.cname,id.dir_name,id2.dir_name AS upfile,pi.img_id,pi.img_file";
                    $First_Table = sprintf("SELECT %s FROM cart", $outlay);
                    $Join_Table = "LEFT JOIN product as pd ON cart.p_id=pd.p_id ";
                    $Join_Table .= "LEFT JOIN product_img AS pi ON pd.p_id = pi.p_id ";
                    $Join_Table .= "LEFT JOIN pyclass AS py on pd.classid=py.classid ";
                    $Join_Table .= "LEFT JOIN img_direct AS id on pd.classid=id.classid ";
                    $Join_Table .= "LEFT JOIN img_direct AS id2 on py.uplink=id2.classid ";
                    $Order = "ORDER BY cartid DESC ";
                    $WHERE = "WHERE pi.sort =1 AND p_open=1 AND ip='".$_SERVER['REMOTE_ADDR'] ."' AND orderid is NULL ";
                    $WHERE .= sprintf("");
                    $SQLstring = sprintf("%s %s %s %s", $First_Table, $Join_Table, $WHERE, $Order);
                    echo '<script>console.log("SQL查詢：'.$SQLstring.'");</script>';
                    $cart_rs = $link->query($SQLstring);
                    $ptotal = 0; //定義累加變數初始值=0
                    ?>
                    <div class="row">
                        <!-- >配送資訊Card -->
                        <?php require_once('Delivery.php'); ?>
                        
                        <!-- 付款方式Card -->
                        <?php require_once('Payment.php'); ?>
                    </div>
                    <!-- 訂貨明細 -->
                    <div class="table-responsive-md" style="width:90%;">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr class="text-bg-primary">
                                    <td width="10%">產品編號</td>
                                    <td width="10%">圖片</td>
                                    <td width="30%">名稱</td>
                                    <td width="15%">價格</td>
                                    <td width="15%">數量</td>
                                    <td width="20%">小計</td>
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
                                <tr>
                                    <td colspan="7">
                                        <button type="button" id="btn04" name="btn04" class="btn btn-danger mr-2"><i class="fas fa-cart-arrow-down pr-2"></i>確認結帳</button>
                                        <button type="button" id="btn05" name="btn05" class="btn btn-warning" onclick="window.history.go(-1);"><i class="fas fa-undo-alt pr-2"></i>回上一頁</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- /訂貨明細 -->