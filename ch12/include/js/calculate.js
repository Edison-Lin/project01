/**
 * 
 * @param {*} collections       為[id, name, 總數, 單價]的陣列集合
 * @param {*} couponInfo        [type, discount]
 * @param {*} deliveryInfo      [type, cost]
 * @param {*} amount 
 * @param {*} infoListId 
 * @param {*} priceElementId 
 * 
 * @returns 明細
 */
function calculate_price(collections, couponInfo, deliveryInfo, amount, infoListId, priceElementId, isBooking)
{
    var total_price = 0;
    var infoList = "";

    // calculate collection price
    var collectionList = "";
    if(collections.length>0)
    {
        collectionList += "訂單內容：<br>";
    }

    var collectionPrice = 0;
    var collectionCountS = 0;
    for(var i=0;i<collections.length;i++)
    {
        collectionName = collections[i][1];
        perPrice = parseInt(collections[i][3], 10);
        count= parseInt(collections[i][2], 10);
        collectionCountS += count;
        countPrice = (perPrice * count);

        collectionPrice += countPrice;
        collectionList += collectionName + "： " + perPrice + "元 * " + count + " = " + countPrice + "元<br>";
    }

    if(collectionList.length>0)
    {
        // 試吃與採購的數量計算不是以原先的amount，
        var unit = "單";
        if(isBooking==0)
        {   // 專屬禮盒
            unit = "盒";
        }

        // 計算小計
        infoList = collectionList + "<hr>小計：" + collectionPrice + "元 * " + amount + unit +"<br>";
        collectionPrice *= parseInt(amount, 10);

        // 試吃與採購的數量計算不是以原先的amount
        if(isBooking==1)
        {   // 試吃與採購
            amount = collectionCountS;
        }

        // 盒數折扣
        if(amount>=50 && amount<100)
        {
            collectionPrice = Math.ceil(collectionPrice * 0.95);
            infoList += "盒數折扣後：" + collectionPrice + "元 (滿50盒 打95折)<br>";
        }
        else if(amount>=100 && amount<200)
        {
            collectionPrice = Math.ceil(collectionPrice * 0.9);
            infoList += "盒數折扣後：" + collectionPrice + "元 (滿100盒 打9折)<br>";
        }
        else if(amount>=200)
        {
            collectionPrice = Math.ceil(collectionPrice * 0.85);
            infoList += "盒數折扣後：" + collectionPrice + "元 (滿200盒 打85折)<br>";
        }

        // 計算運費
        deliveryMethod = deliveryInfo[0];
        deliveryCost = deliveryInfo[1];
        total_price += parseInt(deliveryCost, 10);
        infoList += "運費： " + deliveryCost + "元";
        infoList += (deliveryCost>0)? "(十盒一箱)":"";
        infoList += "<br>";

        // 加入優惠券
        if(couponInfo[0]==1)
        {
            var discount = parseFloat(couponInfo[1]);
            if(discount>0 && discount<1)
            {
                total_price += Math.ceil(collectionPrice * discount);
                infoList += "優惠券： 打" + (discount*10) + "折<br>";
            }
            else if(discount>1)
            {
                total_price += collectionPrice;
                total_price -= discount;
                infoList += "優惠券： 折" + discount + "元<br>";
            }
            else if(discount==0)
            {
                total_price += collectionPrice;
                total_price -= deliveryCost;
                infoList += "優惠券： 折" + deliveryCost + "元(免運)<br>";
            }
            else
            {
                // 折扣內容不明
                total_price += collectionPrice;
            }
        }
        else
        {
            // 沒使用優惠券，或類型不明
            total_price += collectionPrice;
        }

        infoList += "<hr>總計 " + total_price + "元";

        // 顯示資訊
        infoListId = "#" + infoListId;
        $(infoListId).val("");
        $(infoListId).val(infoList);

        priceInfo = total_price;
        priceElementId = "#" + priceElementId;
        $(priceElementId).val("");
        if(amount>0)
        {
            $(priceElementId).val(priceInfo);
        }
        else
            $(priceElementId).val(0);

        infoList += (deliveryMethod==1 && deliveryCost==0)? "<br>(常溫10盒以上免運)":"";
    }

    return infoList;
}

function deliveryCalculate()
{

}