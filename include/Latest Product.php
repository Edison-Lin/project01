<div class="row">
    <div class="col-xl-12 text-center my-auto">
        <h2>新品推薦</h2>
    </div>
</div>
<div class="row">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php //
            $maxRows_rs = 12;
            $pageNum_rs = 0;
            if (isset($_GET['pageNum_rs'])) {
                $pageNum_rs = $_GET['pageNum_rs'];
            }
            $startRow_rs = $pageNum_rs * $maxRows_rs;
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
            // echo $query;
            $pList01 = $link->query($query);
            $i = 1; //控制每列row產生
            ?>
            <?php if (true) { ?>
                <?php if ($pList01->rowCount() != 0) { ?>
                    <?php while ($pList01_Rows = $pList01->fetch()) { ?>
                        <?php if ($i % 4 == 1) { ?>
                            <!-- 新品推播第一頁 -->
                            <?php if ($i == 1) { ?>
                                <div class="carousel-item active">
                                    <div class="row">
                                    <?php } else { ?>
                                        <div class="carousel-item">
                                            <div class="row">
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="card col-md-3 h-100">
                                            <img src="./images/<?php echo $pList01_Rows['upfile'] . '/' .
                                                                    $pList01_Rows['dir_name'] . '/' . $pList01_Rows['img_file']; ?>" class="card-img-top" alt="<?php echo $pList01_Rows['p_name']; ?>" title="<?php echo $pList01_Rows['p_name']; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $pList01_Rows['p_name']; ?></h5>
                                                <p class="card-text">
                                                    <?php echo mb_substr($pList01_Rows['p_intro'], 0, 30, "utf-8"); ?>
                                                </p>
                                                <p>
                                                    <?php echo $pList01_Rows['p_price']; ?></p>
                                                <div class="mt-auto">
                                                    <a href="./goods.php?p_id=<?= $pList01_Rows['p_id']; ?>" class="btn btn-primary">更多資訊</a>
                                                    <button id="button01" name="button01[]" type="button[]" class="btn btn-success color-success" onclick="addcart(<?= $pList01_Rows['p_id']; ?>)">加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($i % 4 == 0 || $i == $pList01->rowCount()) { ?>
                                            </div>
                                        </div>
                                <?php }
                                        $i++;
                                    } ?>
                        <?php }
                } ?>

                                    </div>
                                </div>
        </div>