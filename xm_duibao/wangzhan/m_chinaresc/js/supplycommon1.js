
/************************************************************

***************************************************/

var firstUrl='./interface/xianhuoziyuaninit.php';
var totalPage = 1;
var pageIndex = 0;
var titleCategory = 1;
/*  All = 1,
Sale = 2,
Port = 3,
VipCompany=4显示标题的类别：2(特价),4(大户),3(港口),1(现货) ，默认为现货1
*/
///初始化
$(function () {

    //$("#hidTotalPage").val(1);
    //$("#hidPageIndex").val(0);
    ///初始化加载默认的公司现货资源
    //InitSupplyListData();

    ///点击更多的时候触发加载事件
    $(".supplyContainerMore").click(function () {
        //InitSupplyListData();
        LoadSupply(firstUrl, 10, titleCategory);
    });

});

///载入现货资源数据
function LoadSupply(goUrl, pageSize, dataCategory) {
    titleCategory = dataCategory;
    if (goUrl != firstUrl) {
        $(".supplyContainer").html("");
        firstUrl = goUrl;
        totalPage = 1;
        pageIndex = 0;
    }


    if (totalPage > pageIndex) {
        pageIndex = pageIndex + 1;
        $.ajax({
            url: firstUrl,
            data: { pageIndex: pageIndex },
            type: 'get',
            dataType: 'json',
            async: true,
            beforeSend: function () {
                $(".supplyContainerMore").html("<div class=\"empty loading\"></div>").show();

            },
            success: function (data) {

                console.log(data);
                if (data.success) {
                    totalPage = parseInt(data.attr.TotalItem / pageSize + (data.attr.TotalItem % pageSize == 0 ? 0 : 1));
                    if (data.attr != undefined && data.attr.GoodsSupplyList.length > 0) {
                        for (var i = 0; i < data.attr.GoodsSupplyList.length; i++) {
                            var pageStartFlag = "";
                            if (i == 0) {
                                pageStartFlag = "pageStartFlag"
                            }
                            var html = "   <a class=\"inner-row " + pageStartFlag + " \"    href=\"./xianhuocontent.php?id=" + data.attr.GoodsSupplyList[i].SupplyId + "?datacategory=" + titleCategory + "\">" +
                                    "      <p class=\"clearfix\">  " +
                            "<span class=\"name\">" + data.attr.GoodsSupplyList[i].CoalCateName + "</span>" +
                            "<span class=\"name C-primary cargo\">" + data.attr.GoodsSupplyList[i].CargoName + "</span>  " +
                                "<span class=\"name kcal\">" + data.attr.GoodsSupplyList[i].CoalMainIndex + "</span>";
                            if (data.attr.GoodsSupplyList[i].SupplyType != undefined && data.attr.GoodsSupplyList[i].SupplyType == 1) {
                                html += "<i class=\"cash\"></i>";
                            } else if (data.attr.GoodsSupplyList[i].SupplyType != undefined && data.attr.GoodsSupplyList[i].SupplyType == 2) {
                                html += "<i class=\"futures\"></i>";
                            }
                            if (data.attr.GoodsSupplyList[i].IsSuperPrice != undefined && data.attr.GoodsSupplyList[i].IsSuperPrice) {
                                html += "<i class=\"very\"></i>";
                            }
                            html += "      <span class=\"row-right price C-warning\">";
                            if (data.attr.GoodsSupplyList[i].PublicPrice) {
                                html += "          <span>" + formatNumber(data.attr.GoodsSupplyList[i].Price, 0, 1) + "</span>" +
                              "                元/吨";
                            }
                            else {
                                html += "          待议";
                            }
                            html += "            </span>" +
                              "        </p>" +
                              "         <p class=\"clearfix\">" +
                              "             <span class=\"color-9 company\">" + data.attr.GoodsSupplyList[i].CompanyName + "</span>" +
                              "             <span>" + formatNumber(data.attr.GoodsSupplyList[i].JgWeight, 0, 1) + "</span>" +
                              "            <span>吨</span>" +
                              "            <span class=\"row-right color-9\">" + ChangeDateFormat(data.attr.GoodsSupplyList[i].UpdatedTime) + "</span>" +
                              "        </p>" +
                              "    </a>";

                            $(".supplyContainer").append(html);
                            $(".supplyContainerMore").empty().html("更多").show();
                            if (totalPage == pageIndex) {
                                $(".supplyContainerMore").html("<span >没有更多了</span>").show();
                            }
                        }

                    } else {
                        var tip = "<span>没有更多了</span>";
                        if ($(".supplyContainer a").length == 0) {
                            tip = "<div class=\"empty\"><div class=\"con\"> 抱歉！没有找到您需要的内容！</div></div>";
                        }
                        $(".supplyContainer").append(tip);
                        $(".supplyContainerMore").hide();

                    }


                }
                else {
                    $("#tipsContent").html(data.message);
                    $(".popup-tips").show().delay(2000).hide(0);
                }
            }
        });
    } else {
        //$(".supplyContainerMore").hide();
        $("#supplyContainerMore").html("已经到底了！");
        //  $(".popup-tips").show().delay(2000).hide(0);

    }
    //if (pageIndex > 1) {
    //    var top = $(".supplyContainer  .pageStartFlag").last().offset().top;
    //    //加载完后滚动到新位置
    //    $("html,body").animate({ scrollTop: top }, 500)
    //}
}



function ChangeDateFormat(cellval) {
    if (cellval == undefined || cellval == null || cellval == "") {
        return "";
    }
    var date = new Date(parseInt(cellval.replace("/Date(", "").replace(")/", ""), 10));
    var month = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
    var currentDate = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
    return date.getFullYear() + "-" + month + "-" + currentDate;
}


/** 
        * 将数值四舍五入后格式化. 
        * 
        * @param num 数值(Number或者String) 
        * @param cent 要保留的小数位(Number) 
        * @param isThousand 是否需要千分位 0:不需要,1:需要(数值类型); 
        * @return 格式的字符串,如'1,234,567.45' 
        * @type String 
        */
function formatNumber(num, cent, isThousand) {
    num = num.toString().replace(/\$|\,/g, '');

    // 检查传入数值为数值类型  
    if (isNaN(num))
        num = "0";

    // 获取符号(正/负数)  
    sign = (num == (num = Math.abs(num)));

    num = Math.floor(num * Math.pow(10, cent) + 0.50000000001);  // 把指定的小数位先转换成整数.多余的小数位四舍五入  
    cents = num % Math.pow(10, cent);              // 求出小数位数值  
    num = Math.floor(num / Math.pow(10, cent)).toString();   // 求出整数位数值  
    cents = cents.toString();               // 把小数位转换成字符串,以便求小数位长度  

    // 补足小数位到指定的位数  
    while (cents.length < cent)
        cents = "0" + cents;

    if (isThousand) {
        // 对整数部分进行千分位格式化.  
        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
            num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    }

    if (cent > 0)
        return (((sign) ? '' : '-') + num + '.' + cents);
    else
        return (((sign) ? '' : '-') + num);
}