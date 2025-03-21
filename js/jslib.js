function btn_confirmlink(message, url) {
    if (message == "" || url == "")
        return false;
    if (confirm(message))
        window.location = url;
    return false;
}
function addcart(p_id) {
    var qty = $("#qty").val();
    if (qty <= 0) {
        alert("產品數量不得為0或者負數，請再次修改數量!");
        return false;
    }
    if (qty == undefined) {
        qty = 1;
    }
    if (qty >= 20) {
        alert("由於採購限制，產品數量購買限制在20個單位以下!");
        return false;
    }
    var path='api/';
    //利用jquery $.ajax函數呼叫後台的addcart.php
    $.ajax({
        url: path+'addcart.php',
        type: 'get',
        dataType: 'json',
        data: {
            p_id: p_id,
            qty: qty,
        },
        success: function(data) {
            if (data.c == true) {
                alert(data.m);
                window.location.reload();
            } else {
                alert(data.m);
            }
        },
        error: function(data) {
            alert('系統目前無法連接到後台資料庫。');
        }
    });
}