<div class="card col">
                            <div class="card-header" style="color:#007bff;">
                                <i class="fas fa-truck fa-flip-horizontal me-1"></i>配送資訊
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">收件人資訊:</h4>
                                <h5 class="card-title">姓名: <?= $cname; ?></h5>
                                <p class="card-text">電話: <?= $mobile; ?></p>
                                <p class="card-text">郵遞區號: <?= $myzip; ?></p>
                                <p class="card-text">地址: <?= $address; ?></p>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">選擇其他收件人</button>
                            </div>
                        </div>