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
    <h3>電商藥粧：購物車</h3>
    <?php if ($cart_rs->rowCount() != 0) { ?>
        <a href="./drugstore.php" name="btn01" class="btn btn-primary">繼續購物</a>
        <button type="button" id="btn01" name="btn01" class="btn btn-info" onclick="window.history.go(-1);">回到上一頁</button>
        <button type="button" id="btn03" name="btn03" class="btn btn-success" onclick="btn_confirmlink('確定清空購物車','<?=$apiFileDir;?>shopcart_del.php?mode=2');">清空購物車</button>
        <a href="checkout.php" id="btn04" name="btn04" class="btn btn-warning">前往結帳</a>
        <div class="table-responsive-md">
            <table class="table table-hover mt-3">
                <thead>
                    <td width="10%">產品編號</td>
                    <td width="10%">圖片</td>
                    <td width="25%">名稱</td>
                    <td width="15%">價格</td>
                    <td width="10%">數量</td>
                    <td width="15%">小計</td>
                    <td width="15%">下次再買</td>
                </thead>
                <tbody>
                    <?php while ($cart_data = $cart_rs->fetch()) { ?>
                        <tr>
                            <td><?= $cart_data['p_id']; ?></td>
                            <td><img src="./product_img/<?php echo $cart_data['upfile'] . '/' . $cart_data['dir_name'] . '/' . $cart_data['img_file']; ?>" alt="<?= $cart_data['p_name']; ?>" class="img-fluid">
                            </td>
                            <td> <?= $cart_data['p_name']; ?></td>
                            <td>
                                <h4 class="color_e600a0 pt-1">$<?= $cart_data['p_price']; ?></h4>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" id="qty[]" name="qty[]" class="form-control" value="<?= $cart_data['qty']; ?>" min="1" max="20" required style="min-width: 60px;" cartid="<?php echo $cart_data['cartid']; ?>">
                                </div>
                            </td>

                            <td>
                                <h4 class="color_e600a0 pt-1">$<?= $cart_data['p_price'] * $cart_data['qty']; ?></h4>
                            </td>
                            <td><button type="button" id="btn[]" name="btn[]" class="btn btn-danger" onclick="btn_confirmlink('確定刪除本資料?','<?=$apiFileDir;?>shopcart_del.php?mode=1&cartid=<?= $cart_data['cartid']; ?>')">取消</button></td>
                        </tr>
                    <?php $ptotal += $cart_data['p_price'] * $cart_data['qty'];
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">總計：<?= $ptotal; ?></td>
                    </tr>
                    <tr>
                        <td colspan="7">運費：100</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="color_red">總計：<?= $ptotal + 100; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            抱歉，目前購物車沒有相關產品
        </div>
    <?php } ?>