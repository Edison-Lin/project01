/**
 * 計算總計金額與根據資訊輸出明細
 * 
 * @param {Array}       collections         為[id, name, 數量, 單價, type]的陣列集合。
 * @param {Array}       deliveryInfo        [type, name, carton_boxs, base_cost, base_diff]。
 * @param {Array}       couponInfo          [type, discount]: 類型與折扣數，type=1時，discount在0~1間為打 x 折，大於1為折 y 元，。
 * @param {Array}       usepoint            [points, rate]: points=使用的紅利點數，rate=每點兌換的金額。
 * @param {Array}       bonusList           目前由call的頁面產生。其他優惠條件，格式[[type, bonus], [type, bonus]...]，目前僅顯示訊息不影響計算。
 * @param {string}      payment             付款方式。
 * @param {Array}       infoListIds         顯示資訊的元件id，由 [總金額elemebt id, 明細element id, 運費element id] 組成。
 * 
 * @returns [總金額/-1, info_list/錯誤訊息]
 */
function calculate_price(collections, deliveryInfo, couponInfo, usepoint, bonusList, payment, infoListIds, packageDiscount) {
    var total_price = 0;    // 總金額
    var deliveryCost = 0;   // 運費
    var info_list = "";     // 明細
    var delivery_description = "";      // 運費資訊
    var discount_description = "";      // 折扣紀錄

    // 判斷是否有選擇品項
    if (collections.length == 0) {
        return total_price;
    }
    // 檢查infoListIds
    if (infoListIds.length != 3) {
        info_list = "Error: 視窗元件數量錯誤";
        return [-1, info_list];
    }

    // 取得套餐優惠
    packageDiscount = parseFloat(packageDiscount);

    // 取得優惠商品
    var arrayS = [];
    var countS = 0;
    var arrayB = [];
    var countB = 0;
    var arrayR = [];
    var countR = 0;
    var normalIndexStart = -1;
    for (normalIndexStart = 0; normalIndexStart < collections.length; normalIndexStart++) {
        i = normalIndexStart;
        
        type = collections[i][4];
        if (type == "N") {
            // 表示該index始(包含)，皆為一般商品
            break;
        }

        switch(type)
        {
            case "S":
                countS += parseInt(collections[i][2], 10);

                arrayS.push(collections[i]);

                break;
            case "B":
                countB += parseInt(collections[i][2], 10);

                if(arrayB.length==0)
                {
                    arrayB.push(collections[i]);
                }
                else if(packageDiscount>0 && packageDiscount<1)
                {
                    arrayB = insert(collections[i], arrayB);
                }
                else
                {
                    arrayB.push(collections[i]);
                }

                break;
            case "R":
                countR += parseInt(collections[i][2], 10);
                
                if(arrayR.length==0)
                {
                    arrayR.push(collections[i]);
                }
                else if(packageDiscount>0 && packageDiscount<1)
                {
                    arrayR = insert(collections[i], arrayR);
                }
                else
                {
                    arrayR.push(collections[i]);
                }

                break;
        }
    }
    
    // 處理BR兩區不成組的數量
    if(countB != countR)
    {
        pairCountDiff = 0;
        ori_pairCountDiff = 0;

        // 分辨要處理哪一套餐區
        arrayDetached = [];
        if(countB>countR)
        {
            // 取得差
            pairCountDiff = countB - countR;
            // 設定處理的套餐區
            arrayDetached = arrayB.slice();

            // 寫回處理後商品數量
            ori_pairCountDiff = pairCountDiff;
        }
        else
        {
            // 取得差
            pairCountDiff = countR - countB;
            // 設定處理的套餐區
            arrayDetached = arrayR.slice();

            // 寫回處理後商品數量
            ori_pairCountDiff = pairCountDiff;
        }

        // 處理不成組的部分
        for(i=arrayDetached.length-1 ; i>=0 && pairCountDiff>0 ; i--)
        {
            iAmount = arrayDetached[i][2];
            if(iAmount>pairCountDiff)
            {   // 扣除不成組後有餘
                tmpArray = arrayDetached[i].slice();
                tmpArray[2] = pairCountDiff;
                collections.push(tmpArray);

                arrayDetached[i][2] = arrayDetached[i][2] - pairCountDiff;

                pairCountDiff = 0;
            }
            else if(iAmount==pairCountDiff)
            {   // 扣除不成組後為0
                tmpArray = arrayDetached.pop();
                collections.push(tmpArray);
                
                pairCountDiff = 0;
            }
            else
            {   // 不成組數>目前商品的數量
                tmpArray = arrayDetached.pop();
                // 取得目前商品的數量
                tAmount = tmpArray[2];  

                // 放置到collection尾端
                collections.push(tmpArray);

                pairCountDiff = pairCountDiff - tAmount;
            }
        }

        // 將陣列結果寫回套餐區
        if(countB>countR)
        {
            arrayB = arrayDetached;
            countB = countB - ori_pairCountDiff;
        }
        else
        {
            arrayR = arrayDetached;
            countR = countR - ori_pairCountDiff;
        }
    }

    // 檢查套餐產生流程是否錯誤
    if(countB!=countR)
    {
        info_list = "Error: 套餐優惠產生失敗";
        return [-1, info_list];
    }

    // 計算一般品項金額與產生明細
    var collectionAmount = 0;
    var collectionPrice = 0;    // 商品總金額
    var collectionList = "";    // 商品明細
    for (i = normalIndexStart; i < collections.length; i++) {
        collectionName = collections[i][1];
        perPrice = parseInt(collections[i][3], 10);
        amount = parseInt(collections[i][2], 10);
        type = collections[i][4];

        countPrice = (perPrice * amount);

        collectionAmount += amount;
        collectionPrice += countPrice;
        collectionList += collectionName + "： " + perPrice + "元 * " + amount + " = " + countPrice + "元<br>";
    }

    total_price += collectionPrice;

    // 取得數量折扣後金額
    var afterAmountDiscount = calculateAmountDiscount(total_price, collectionAmount);
    total_price = parseInt(afterAmountDiscount[0]);
    discount_description += afterAmountDiscount[1];
    discount_description += (afterAmountDiscount[1].length > 0) ? "<br>" : "";

    // 取得優惠券折扣後金額
    var couponType = "";
    var couponDiscount = "";
    if (couponInfo.length == 2) {
        couponType = couponInfo[0];
        couponDiscount = couponInfo[1];
    }
    else {
        info_list = "Error: 優惠券資訊格式錯誤";
        return [-1, info_list];
    }

    var afterCouponDiscount = calculateCouponDiscount(couponType, couponDiscount, total_price);
    total_price = parseInt(afterCouponDiscount[0]);
    if (total_price <= 0 && afterCouponDiscount[1].length>0) {
        info_list = "Error: 優惠券金額大於商品金額";
        return [-1, info_list];
    }
    discount_description += afterCouponDiscount[1];
    discount_description += (afterCouponDiscount[1].length > 0) ? "<br>" : "";

    // 優惠為商品的折扣，因此運費在計算優惠後加回
    // 計算運費
    if(deliveryInfo[0]=="-1")
    {
        info_list = "Error: 請選擇運送方式";
        return [-1, info_list];
    }

    var totalAmount = collectionAmount + countB + countR + countS;  // 運費由全部商品數量合計
    var deliveryCostInfo;
    if (deliveryInfo.length == 5) {
        deliveryType = deliveryInfo[0];
        deliveryName = deliveryInfo[1];
        carton_boxs = deliveryInfo[2];
        deliveryBaseCost = deliveryInfo[3];
        deliveryBaseDiff = deliveryInfo[4];

        deliveryCostInfo = deliveryCalculate(deliveryType, deliveryName, deliveryBaseCost, deliveryBaseDiff, totalAmount, carton_boxs);
    }
    else {
        info_list = "Error: 運費資訊格式錯誤";
        return [-1, info_list];
    }

    // 免運應置於優惠處
    deliveryCost = parseInt(deliveryCostInfo[0]);
    if (deliveryCostInfo[1].indexOf('免運') > 0) {
        discount_description += deliveryCostInfo[1] + "<br>";
    }
    else {
        if (deliveryCostInfo[1].length > 0)
            delivery_description = "(" + deliveryCostInfo[1] + ")<br>";
    }

    // 取貨付款加收
    var payAdd = 0;
    if (payment.indexOf("取貨付款") == 0 && deliveryInfo[0] != 0) {
        total_price += 30;
        payAdd += 30;
        delivery_description += "宅配付現加收金額： 30元<br>";
    }
    total_price += deliveryCost;

    // 取得紅利集點兌換
    var pointDiscount;
    if (usepoint.length == 2) {
        points = usepoint[0];
        rate = usepoint[1];

        pointDiscount = getMemberPointDiscount(points, rate);
    }
    else {
        info_list = "Error: 會員紅利資訊格式錯誤";
        return [-1, info_list];
    }

    pd = pointDiscount[0];
    if (pd > total_price) {
        info_list = "Error: 紅利兌換金額大於商品金額";
        return [-1, info_list];
    }

    // 處理紅利使用後金額(到目前為止，未加入促銷與套餐商品之金額)
    total_price -= parseInt(pd, 10);

    // 處理優惠明細(優惠券+紅利)
    discount_description += pointDiscount[1];
    discount_description += (pointDiscount[1].length > 0) ? "<br>" : "";
    discount_description += bonusList;
    discount_description += (bonusList.length > 0) ? "<br>" : "";

    // 處理促銷/套餐商品
    if(countS!=0)
    {
        for(i=0; i<arrayS.length; i++)
        {
            collectionName = arrayS[i][1];
            perPrice = parseInt(arrayS[i][3], 10);
            amount = parseInt(arrayS[i][2], 10);

            countPrice = (perPrice * amount);

            total_price += countPrice;
            collectionPrice += countPrice;
            collectionList += collectionName + "(促)： " + perPrice + "元 * " + amount + " = " + countPrice + "元<br>";
        }
    }
    if(countB!=0 && countB==countR)
    {   // 必須成組

        if(packageDiscount>0 && packageDiscount<1)
        {   // 打折
            for(i=0; i<arrayB.length; i++)
            {
                collectionName = arrayB[i][1];
                perPrice = parseInt(arrayB[i][3], 10);
                amount = parseInt(arrayB[i][2], 10);

                countPrice = parseInt((perPrice * amount)*packageDiscount, 10);
                countPriceN = perPrice * amount;    // 依原價

                total_price += countPrice;
                collectionPrice += countPriceN;
                collectionList += collectionName + "(B)： " + perPrice + "元 * " + amount + " = " + countPriceN + "元<br>";
            }

            for(i=0; i<arrayR.length; i++)
            {
                collectionName = arrayR[i][1];
                perPrice = parseInt(arrayR[i][3], 10);
                amount = parseInt(arrayR[i][2], 10);

                countPrice = parseInt((perPrice * amount)*packageDiscount, 10);
                countPriceN = perPrice * amount;    // 依原價

                total_price += countPrice;
                collectionPrice += countPriceN;
                collectionList += collectionName + "(R)： " + perPrice + "元 * " + amount + " = " + countPriceN + "元<br>";
            }
        }   
        else
        {   // 折價
            for(i=0; i<arrayB.length; i++)
            {
                collectionName = arrayB[i][1];
                perPrice = parseInt(arrayB[i][3], 10);
                amount = parseInt(arrayB[i][2], 10);

                countPrice = (perPrice * amount);

                total_price += countPrice;
                collectionPrice += countPrice;
                collectionList += collectionName + "(△)： " + perPrice + "元 * " + amount + " = " + countPrice + "元<br>";
            }

            for(i=0; i<arrayR.length; i++)
            {
                collectionName = arrayR[i][1];
                perPrice = parseInt(arrayR[i][3], 10);
                amount = parseInt(arrayR[i][2], 10);

                countPrice = (perPrice * amount);

                total_price += countPrice;
                collectionPrice += countPrice;
                collectionList += collectionName + "(◇)： " + perPrice + "元 * " + amount + " = " + countPrice + "元<br>";
            }

            total_price -= (packageDiscount * countB);
        }

        // 加入優惠明細
        packageStr = '△紅區 + ◇藍區商品 ' + countB + '組，每組享 ';
        if(packageDiscount>0 && packageDiscount<1)
        {
            pdf = packageDiscount * 100;
            pdfi = parseInt(pdf, 10);

            if(pdfi%10 == 0)
            {
                pdfi = parseInt(pdfi/10, 10);
            }

            packageStr +=  pdfi + " 折";
        }
        else
        {
            packageStr += packageDiscount + " 元折扣";
        }

        discount_description += packageStr + "<br>";
    }

    // 組合明細
    info_list += collectionList;
    info_list += "運費： " + deliveryCost + " 元<br>";
    info_list += delivery_description;
    info_list += "<hr>";
    info_list += "數量： " + totalAmount + " 盒<br>";
    info_list += "小計： " + (collectionPrice + deliveryCost + payAdd) + " 元<br>";
    info_list += "<hr>";

    if (discount_description.length > 0) {
        info_list += "優惠資訊：<br>";
        info_list += discount_description;
    }
    else {
        info_list += "優惠資訊： 無<br>";
    }

    info_list += "<hr>";
    info_list += "總計： " + total_price + " 元";

    // 填入視窗元件
    var totalPriceElement = "#" + infoListIds[1];
    var infoElement = "#" + infoListIds[0];
    var deliveryElement = "#" + infoListIds[2];

    $(totalPriceElement).val(total_price);
    $(infoElement).html(info_list);
    $(deliveryElement).val(deliveryCost);

    return [total_price, info_list];
}

function deliveryCalculate(deliveryType, deliveryName, deliveryBaseCost, deliveryBaseDiff, amount, carton_boxs) {
    // 轉換，避免未轉型態而計算時發生錯誤
    deliveryBaseCost = parseInt(deliveryBaseCost, 10);
    deliveryBaseDiff = parseInt(deliveryBaseDiff, 10);
    amount = parseInt(amount, 10);
    carton_boxs = parseInt(carton_boxs, 10);

    var total_DeliveryCost = 0;
    var delivery_description = "";

    if (deliveryName.indexOf("本島") >= 0) {
        switch (deliveryType) {
            case "1":
                // 常溫
                q = Math.floor(amount / carton_boxs);
                r = amount % carton_boxs;

                if (q >= 1) {    // 大於等於一箱免運
                    total_DeliveryCost = 0;
                    delivery_description = "常溫宅配" + carton_boxs + "盒(含)以上免運。";
                }
                else {
                    if (r > 1) {   // 大於1盒
                        total_DeliveryCost = deliveryBaseCost + deliveryBaseDiff;
                        delivery_description = "兩盒(含)以上未滿一箱以一箱計。";
                    }
                    else {   // 等於1盒
                        total_DeliveryCost = deliveryBaseCost;
                    }
                }

                break;
            case "2":
                // 冷藏
                q = Math.floor(amount / carton_boxs);
                r = amount % carton_boxs;

                total_DeliveryCost = (deliveryBaseCost + deliveryBaseDiff * 2) * q;    // 一箱的價格

                switch (r) {
                    case 0:
                        delivery_description = carton_boxs + "盒一箱。";
                        break;
                    case 1:
                        total_DeliveryCost += deliveryBaseCost;
                        delivery_description = carton_boxs + "盒一箱。";
                        break;
                    default:
                        // 大於1條則以箱計
                        total_DeliveryCost += deliveryBaseCost + deliveryBaseDiff * 2;
                        delivery_description = "兩盒(含)以上未滿一箱以一箱計。";
                        break;
                }

                break;
            default:
        }
    }
    else {
        // 外島
        switch (deliveryType) {
            case "1":
                // 常溫
                q = Math.floor(amount / carton_boxs);
                r = amount % carton_boxs;

                if (q >= 1) {   // 大於等於一箱免運
                    total_DeliveryCost = 0;
                    delivery_description = "常溫宅配" + carton_boxs + "盒(含)以上免運。";
                }
                else {   // 1~9盒
                    total_DeliveryCost = deliveryBaseCost;
                    delivery_description = "未滿一箱以一箱計。";
                }

                break;
            case "2":
                // 冷藏
                q = Math.floor(amount / carton_boxs);
                r = amount % carton_boxs;

                total_DeliveryCost = (deliveryBaseCost) * q;    // 一箱的價格
                total_DeliveryCost += (r > 0) ? (deliveryBaseCost) : 0;     // 未滿一箱(有餘數)，則加上base價格
                delivery_description = carton_boxs + "盒一箱，未滿一箱則以一箱計。";
                break;
            default:
        }
    }

    return [total_DeliveryCost, delivery_description];
}

/**
 * 
 * @param {*} total_price       
 * @param {*} amount        
 * 
 * @returns []
 */
function calculateAmountDiscount(total_price, amount) {
    // 轉換，避免未轉型態而計算時發生錯誤
    amount = parseInt(amount, 10);

    var price = parseInt(total_price, 10);
    var discount_description = "";

    if (amount >= 50 && amount < 100) {
        price = Math.round(price * 0.95);
        discount_description = "滿50盒，打95折";
    }
    else if (amount >= 100 && amount < 200) {
        price = Math.round(price * 0.9);
        discount_description = "滿100盒，打9折";
    }
    else if (amount >= 200) {
        price = Math.round(price * 0.85);
        discount_description = "滿200盒，打85折";
    }

    return [price, discount_description];
}

function calculateCouponDiscount(couponType, couponDiscount, total_price) {
    // 轉換，避免未轉型態而計算時發生錯誤
    couponDiscount = parseFloat(couponDiscount);

    var price = parseInt(total_price, 10);
    var discount_description = "";

    if (couponType == 1) {
        if (couponDiscount > 0 && couponDiscount < 1) {
            price = Math.floor(total_price * couponDiscount);

            discount_description = "優惠券： " + (1 - couponDiscount) * 100 + "% off";
        }
        else if (couponDiscount > 1) {
            price -= couponDiscount;
            discount_description = "優惠券： 折" + couponDiscount + "元";
        }
        else {
            // 折扣內容不明
        }
    }

    return [price, discount_description];
}

function getMemberPointDiscount(usePoint, rate) {
    // 轉換，避免未轉型態而計算時發生錯誤
    usePoint = parseInt(usePoint, 10);
    rate = parseInt(rate, 10);

    var discount = 0;
    var discount_description = "";

    if (usePoint > 0) {
        discount = usePoint * rate;
        discount_description = "使用紅利點數: " + usePoint + " 點，折 " + discount + " 元(每點折 " + rate + " 元)";
    }

    return [discount, discount_description];
}

function insert(element, array) {
    array.splice(locationOf(element, array), 0, element);

    return array;
}

function locationOf(element, array, start, end) {
    start = start || 0;
    end = end || array.length;

    var pivot = parseInt(start + (end - start) / 2, 10);

    var ePrice = parseInt(element[3], 10);
    var pPrice = parseInt(array[pivot][3], 10);

    if (end - start <= 1 || pPrice === ePrice) {
        if (pivot == 0) {
            if (pPrice > ePrice) {
                return 0;
            }
            else {
                return pivot + 1;
            }
        }
        else {
            return pivot + 1;
        }
    }

    if (pPrice < ePrice) {
        return locationOf(element, array, pivot, end);
    }
    else {
        return locationOf(element, array, start, pivot);
    }
}