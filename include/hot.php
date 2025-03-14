<!-- 站長推薦，熱銷產品 -->
<div class="card text-center mt-3" style="border:none;">
    <div class="card-body">
        <h3 class="card-title">站長推薦，熱銷產品</h3>
    </div>
    <?php
    //建立熱銷商品查詢--(林自強老師版本)
    // $SQLstring = "SELECT * FROM hot,product,product_img WHERE hot.p_id=product_img.p_id AND hot.p_id=product.p_id AND product_img.sort=1 ORDER BY h_sort";

    //自行規整版本
    $outlay = "hot.p_id,pd.p_name,hot.h_sort,id.dir_name,id2.dir_name AS upfile,pi.img_id,pi.img_file";
    $First_Table = sprintf("SELECT %s FROM hot ", $outlay);
    $Join_Table = "";
    $Join_Table .= "LEFT JOIN product as pd ON hot.p_id=pd.p_id ";
    $Join_Table .= "LEFT JOIN product_img AS pi ON pd.p_id = pi.p_id ";
    $Join_Table .= "LEFT JOIN pyclass AS py on pd.classid=py.classid ";
    $Join_Table .= "LEFT JOIN img_direct AS id on pd.classid=id.classid ";
    $Join_Table .= "LEFT JOIN img_direct AS id2 on py.uplink=id2.classid ";
    $Order = "ORDER BY hot.h_sort";
    $WHERE = "WHERE pi.sort =1 AND p_open=1 ";

    $WHERE .= sprintf("");
    $SQLstring = sprintf("%s %s %s %s", $First_Table, $Join_Table, $WHERE, $Order);
    
    $hot = $link->query($SQLstring);
    ?>
    <?php
    while ($data = $hot->fetch()) { ?>
        <img src="./product_img/<?php echo $data['upfile'] . '/' .
                                            $data['dir_name'] . '/' . $data['img_file']; ?>" class="card-img-top" alt="HOT<?= $data['h_sort'] ?>" title="<?= $data['p_name'] ?>">
    <?php } ?>
</div>