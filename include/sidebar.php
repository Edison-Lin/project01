<!-- 產品資訊欄 -->
<div class="accordion" id="accordionExample">
    <?php
    $SQLstring = "SELECT * FROM pyclass WHERE level=1 ORDER BY sort";
    $pyclass01 = $link->query($SQLstring);
    $i = 1;
    ?>
    <?php
    while ($pyclass01_Rows = $pyclass01->fetch()) {
        $i = $pyclass01_Rows['classid'];
    ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne<?php echo $i; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne<?php echo $i; ?>">
                    <i class="fas <?php echo $pyclass01_Rows['fonticon']; ?> fa-fw"></i><?php echo $pyclass01_Rows['cname']; ?>
                </button>
            </h2>
            <?php
            //使用第一層類別查詢
            if (isset($_GET['level']) && $_GET['level'] == 1) {
                $ladder = $_GET['classid'];
            } elseif (isset($_GET['classid'])) {
                //如果使用類別查詢需取得上一層類別
                $SQLstring = "SELECT uplink FROM pyclass WHERE level=2 AND classid=" . $_GET['classid'];
                $classid_rs = $link->query($SQLstring);
                $data = $classid_rs->fetch();
                $ladder = $data['uplink'];
            } else {
                $ladder = 1;
            }
            //列出產品類別第二層
            $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_Rows['classid']);
            $pyclass02 = $link->query($SQLstring);
            ?>
            <div id="collapseOne<?php echo $i; ?>" class="accordion-collapse collapse <?php echo ($i == $ladder) ? 'show' : ''; ?>" aria-labelledby="headingOne<?php echo $i; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table class="table">
                        <tbody>
                            <?php
                            while ($pyclass02_Rows = $pyclass02->fetch()) {
                            ?>
                                <tr>
                                    <td><a href="drugstore.php?classid=<?php echo $pyclass02_Rows['classid']; ?>"><em class="fas <?= $pyclass02_Rows['fonticon']; ?> fa-fw"></em><?= $pyclass02_Rows['cname'] ?></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
        $i++;
    }
    ?><!-- 迴圈尾 -->
</div>
<div class="sidebar mt-3">
    <!-- 搜尋區域 -->
    <form id="search" name="search" action="drugstore.php" method="get">
        <div class="input-group ">
            <input type="text" class="form-control" name="search_name" id="search_name2" placeholder="Search..." value="<?php echo (isset($_GET['search_name'])) ? $_GET['search_name'] : ''; ?>" required>
        
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="fas fa-search fa-lg"></i>尋找</button>
            </span>
        </div>
    </form>
</div>