<div class="container-fluid">
<hr>
    <div class="row">
        <!-- 熱賣商品 -->
        <div class="col-md-2 text-danger display-1 sticky-top my-auto">
            <div class="d-flex flex-row">
                <span>熱</span>
            </div>
            <div class="d-flex flex-row-reverse">
                <span>賣</span>
            </div>
            <div class="d-flex flex-row">
                <span>商</span>
            </div>
            <div class="d-flex flex-row-reverse">
                <span>品</span>
            </div>
        </div>
        <div class="col-md-10">
            <!-- 產品資料表 -->
<?php //建立product藥妝商品RS
$maxRows_rs = 8;
$pageNum_rs = 0;
if (isset($_GET['pageNum_rs'])) {
    $pageNum_rs = $_GET['pageNum_rs'];
}
$startRow_rs = 1 * $maxRows_rs;
//自行規整版本
$outlay = "pd.p_id,pd.classid,pd.p_name,pd.p_intro,pd.p_price,py.fonticon,py.cname,id.dir_name,id2.dir_name AS upfile,pi.img_id,pi.img_file";
$First_Table = sprintf("SELECT %s FROM product as pd ", $outlay);
$Join_Table = "";
$Join_Table .= "LEFT JOIN product_img AS pi ON pd.p_id = pi.p_id ";
$Join_Table .= "LEFT JOIN pyclass AS py on pd.classid=py.classid ";
$Join_Table .= "LEFT JOIN img_direct AS id on pd.classid=id.classid ";
$Join_Table .= "LEFT JOIN img_direct AS id2 on py.uplink=id2.classid ";
$Order = "ORDER BY pd.p_id DESC ";
$WHERE = "WHERE pi.sort =1 AND p_open=1 ";

$WHERE .= sprintf("");
    $queryFirst = sprintf("%s %s %s %s", $First_Table, $Join_Table, $WHERE, $Order);
$query = sprintf("%s LIMIT %d,%d", $queryFirst, $startRow_rs, $maxRows_rs);
$pList01 = $link->query($query);
$i = 1; //控制每列row產生
?>
<?php if ($pList01->rowCount() != 0) { ?>
    <?php while ($pList01_Rows = $pList01->fetch()) { ?>
        <?php if ($i % 4 == 1) { ?>
            <div class="row text-center">
            <?php } ?>
            <!-- 卡片Card樣板 -->
            <div class="card col-md-3">
                <img src="./product_img/<?php echo $pList01_Rows['upfile'] . '/' . $pList01_Rows['dir_name'] . '/' . $pList01_Rows['img_file']; ?>" class="card-img-top" alt="<?php echo $pList01_Rows['p_name']; ?>" title="<?php echo $pList01_Rows['p_name']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $pList01_Rows['p_name']; ?></h5>
                    <p class="card-text"><?php echo mb_substr($pList01_Rows['p_intro'], 0, 30, "utf-8"); ?></p>
                    <p>
                        <?php echo $pList01_Rows['p_price']; ?></p>
                    <a href="./goods.php?p_id=<?=$pList01_Rows['p_id'];?>" class="btn btn-primary">更多資訊</a>
                    <button id="button01" name="button01[]" type="button[]" class="btn btn-success color-success" onclick="addcart(<?= $pList01_Rows['p_id']; ?>)">加入購物車</button>
                </div>
            </div>
            <?php if ($i % 4 == 0 || $i == $pList01->rowCount()) { ?>
            </div>
        <?php } ?>
    <?php $i++;    } ?>
    <!-- 換頁按鈕 -->
    <div class="row mt-2">
        <?php
        if (isset($_GET['totalRows_rs'])) {
            $totalRows_rs = $_GET['totalRows_rs'];
        } else {
            $all_rs = $link->query($queryFirst);
            $totalRows_rs = $all_rs->rowCount();
            $totalRows_rs=1;
        }
        $totalPage_rs = ceil($totalRows_rs / $maxRows_rs) - 1;
        $prev_rs = "&laquo;";
        $next_rs = "&raquo;";
        $separator = "|";
        $max_links = 20;
        $pages_rs = buildNavigation($pageNum_rs, $totalPage_rs, $prev_rs, $next_rs, $separator, $max_links, true, 3, "rs");
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php echo $pages_rs[0] . $pages_rs[1] . $pages_rs[2]; ?>
            </ul>
        </nav>
    </div>
<?php } else { ?>
    <div class="alert alert-danger" role="alert">
        抱歉，沒有相關產品
    </div>
<?php } ?>






             
        </div>
    </div>
</div>