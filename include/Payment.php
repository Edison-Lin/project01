<div class="card col ms-3">
    <div class="card-header" style="color:#000;">
        <i class="fas fa-credit-card me-1"></i>付款方式
    </div>
    <div class="card-body">
        <!--  -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true" style="color:#007bff !important; font-size:14px;">貨到付款</button></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" style="color:#007bff !important; font-size:14px;">信用卡</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false" style="color:#007bff !important; font-size:14px;">銀行轉帳</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="epay-tab" data-bs-toggle="tab" data-bs-target="#epay" type="button" role="tab" aria-controls="epay" aria-selected="false" style="color:#007bff !important; font-size:14px;">電子支付</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <h4 class="card-title pt-3">收件人資訊:</h4>
                <h5 class="card-title">姓名: <?= $cname; ?></h5>
                <p class="card-text">電話: <?= $mobile; ?></p>
                <p class="card-text">郵遞區號: <?php echo $myzip . $ctName . $toName; ?></p>
                <p class="card-text">地址: <?= $address; ?></p>

            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table class="table caption-top">
                    <caption>選擇付款帳戶</caption>
                    <thead>
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="35%">信用卡系統</th>
                            <th scope="col" width="30%">發卡銀行</th>
                            <th scope="col" width="30%">信用卡號</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><input type="radio" name="creditCart" id="creditCart[]" checked></th>
                            <td><img src="./images/Visa_Inc._logo.svg" alt="visa" class="img-fluid"></td>
                            <td>玉山商業銀行</td>
                            <td>1234 ****</td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="radio" name="creditCart" id="creditCart[]"></th>
                            <td><img src="./images/MasterCard_Logo.svg" alt="master" class="img-fluid"></td>
                            <td>玉山商業銀行</td>
                            <td>1234 ****</td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="radio" name="creditCart" id="creditCart[]"></th>
                            <td><img src="./images/UnionPay_logo.svg" alt="unionpay" class="img-fluid"></td>
                            <td>玉山商業銀行</td>
                            <td>1234 ****</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <button type="button" class="btn btn-outline-success">使用其他信用款付款</button>
            </div>
            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <h4 class="card-title pt-3">Atm 匯款資訊：</h4>
                <img src="./images/Cathay-bk-rgb-db.svg" alt="cathay" class="img-fluid">
                <h5 class="card-title">匯款銀行：國泰世華銀行 銀行代碼： 013</h5>
                <h5 class="card-title">姓名：林小強</h5>
                <p class="card-text">匯款帳號：1234-4567-7890-1234</p>
                <p class="card-text">備註：匯款完成後，需要一到兩個工作天，待系統入款完成後，將以簡訊通知訂單完成付款</p>
            </div>
            <div class="tab-pane fade" id="epay" role="tabpanel" aria-labelledby="epay-tab" tabindex="0">
                <table class="table caption-top">
                    <caption>選擇電子支付方式：</caption>
                    <thead>
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="35%">電子支付系統</th>
                            <th scope="col" width="60%">電子支付公司</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><input type="radio" name="epay" id="epay[]" checked></th>
                            <td><img src="./images/Apple_Pay_logo.svg" alt="applepay" class="img-fluid"></td>
                            <td>Apple Pay</td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="radio" name="epay" id="epay[]"></th>
                            <td><img src="./images/Line_pay_logo.svg" alt="linepay" class="img-fluid"></td>
                            <td>Line Pay</td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="radio" name="epay" id="epay[]"></th>
                            <td><img src="./images/JKOPAY_logo.svg" alt="jkopay" class="img-fluid"></td>
                            <td>JKOPAY</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <button type="button" class="btn btn-outline-success">使用其他電子支付方式</button>
            </div>
        </div>
        <!--  -->
    </div>
</div>