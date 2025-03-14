<!-- 廣告輪播carousel區 -->
<?php $carouse_start = 0; //廣告輪播起始頁
?>
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <?php //建立廣告輪播carousel資料查詢
    $SQLstring = "SELECT * FROM carousel WHERE caro_online=1 ORDER BY caro_sort";
    $carousel = $link->query($SQLstring);
    ?>
    <div class="carousel-indicators">
        <?php
        for ($i = 0; $i < $carousel->rowCount(); $i++) {
        ?>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?= $i ?>" class="<?= activeshow($i, $carouse_start) ?>" aria-current="true" aria-label="Slide <?= $i ?>">
            </button>

        <?php } ?>
    </div>
    <div class="carousel-inner">
        <?php $i = 0;
        while ($data = $carousel->fetch()) { ?>
            <div class="carousel-item <?php echo activeshow($i, $carouse_start); ?>">
                <a href="./goods.php?p_id=<?=$data['p_id'];?>">
                    <img src="./images/carousel/<?php echo $data['caro_pic'] ?>" class="d-block w-100" alt="<?php echo $data['caro_title']; ?>" style="height: 400px;">
                </a>
                <div class="carousel-caption d-none d-md-block">
                    <h5><?php echo $data['caro_title']; ?></h5>
                    <h3><?php echo $data['caro_content']; ?></h3>
                </div>
            </div>
        <?php $i++;
        } ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>