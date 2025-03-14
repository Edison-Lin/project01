<nav class="navbar navbar-expand-lg d-flex justify-content-around bg-warning">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index_p01.php">
            <div class="d-flex flex-column">
                <img src="./images/qnap-logo-black.svg" class="img-fluid" alt="威聯通科技" loading="lazy">
                <img src="./images/logo.png" class="img-fluid" alt="友訊科技">
            </div>
        </a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <?php
                $SQLstring = "SELECT * FROM cart WHERE orderid is NULL AND ip='" . $_SERVER['REMOTE_ADDR'] . "'";
                $cart_rs = $link->query($SQLstring);
                ?>
                <?php if (true /* 開關<產品資訊>選項 */) { ?>
                    <!-- 產品資訊<list> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            產品資訊
                        </a>
                        <?php
                        $SQLstring = "SELECT * FROM pyclass WHERE level=1 ORDER BY sort";
                        $pyclass01 = $link->query($SQLstring);
                        ?>
                        <ul class="dropdown-menu">
                            <?php
                            while ($pyclass01_Rows = $pyclass01->fetch()) {
                            ?>
                                <li class="nav-item dropend">
                                    <a class="dropdown-item dropdown-toggle" href="drugstore.php?classid=<?php echo $pyclass01_Rows['classid']; ?>&level=1"><i class="fas <?php echo $pyclass01_Rows['fonticon']; ?> fa-fw"></i><?php echo $pyclass01_Rows['cname']; ?></a>
                                    <?php
                                    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_Rows['classid']);
                                    $pyclass02 = $link->query($SQLstring);
                                    ?>
                                    <ul class="dropdown-menu">
                                        <?php
                                        while ($pyclass02_Rows = $pyclass02->fetch()) {
                                        ?>
                                            <li><a class="dropdown-item" href="drugstore.php?classid=<?php echo $pyclass02_Rows['classid']; ?>"><em class="fas <?php echo  $pyclass02_Rows['fonticon']; ?> fa-fw"></em><?php echo  $pyclass02_Rows['cname'] ?></a></li>
                                        <?php } ?>

                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        企業專區
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">認識企業文化</a></li>
                        <li><a class="dropdown-item" href="#">全台門市資訊</a></li>
                        <li><a class="dropdown-item" href="#">供應商報價服務</a></li>
                        <li><a class="dropdown-item" href="#">加盟專區</a></li>
                        <li><a class="dropdown-item" href="#">投資人專區</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">最新活動</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./orderlist.php">查訂單</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./cart.php">購物車<span class="badge text-bg-success"><?php echo ($cart_rs) ? $cart_rs->rowCount() : ''; ?></span></a>
                </li>
            </ul>
            <form class="d-flex flex-column p-3" role="search" method="get" action="drugstore.php">
                <input class="form-control me-2" type="search" placeholder="搜尋關鍵字" aria-label="Search" name="search_name" id="search_name" value="<?php echo (isset($_GET['search_name'])) ? $_GET['search_name'] : ''; ?>" required>
                <button class="btn btn-outline-success text-bg-info m-1" type="submit"><i class="fas fa-search fa-lg"></i>&emsp;開始尋找</button>
            </form>
        </div>
        <div>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        會員
                    </a>
                    <ul class="dropdown-menu">

                        <li>
                            <a class="dropdown-item" href="./register.php">會員註冊</a>
                        </li>
                        <?php if (isset($_SESSION['login'])) { ?>
                            <li>
                                <a href="#" class="dropdown-item" onclick="btn_confirmlink('是否確認登出?','./logout.php?sPath=<?= basename($_SERVER['PHP_SELF']); ?>' )">會員登出</a>
                            </li>
                        <?php } else { ?>
                            <li>
                                <a class="dropdown-item" href="./login.php?sPath=<?= basename($_SERVER['PHP_SELF']); ?>">會員登入</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a class="dropdown-item" href="#">會員中心</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>