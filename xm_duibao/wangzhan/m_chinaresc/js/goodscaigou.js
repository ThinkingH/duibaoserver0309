$(function () {
    PostCaigouList(1, "");
});
function PostCaigouList(page, obj) {
    GetLoading(obj);
    $.ajax({
        url: "./interface/caigouinit.php",
        data: { page: page },
        type: "Post",
        success: function (json) {
            if (json.success) {
                GetCaigouHtml(json.attr)
            }
            else {
                alert(json.message);
            }
        }
    });
}

function GetCaigouHtml(data) {
    var html = "";
    if (data.caigous != null) {
        var caigous = eval("(" + data.caigous + ")");
        for (var i = 0; i < caigous.length; i++) {
            html += "<div class=\"pro-wrap\" cgid=\"" + caigous[i].Id + "\">";
            html += "<div class=\"row\">";
            html += "<div class=\"row2\">";
            html += "<span class=\"warning\">" + caigous[i].CoalCateName + ":</span><span class=\"pm\">" + caigous[i].StandardBrand + "</span>";
            html += "</div>";
            html += "<div class=\"row2\">";
            if (caigous[i].CompanyNo != null && caigous[i].CompanyNo != "") {
                html += "<span>公司:<span>" + caigous[i].CompanyNo + "</span></span>";
            }
            html += "</div>";

            var indexs = caigous[i].Indexs;
            var th = "";
            var td = "";
            if (indexs != null) {
                var keys = Object.keys(indexs);
                for (var k = 0; k < keys.length; k++) {
                    th += "<th>" + keys[k] + "</th>";
                    td += "<td>" + caigous[i].Indexs[keys[k]] + "</td>";
                }
            }
            else {
                th = "<th></th>";
                td += "<td>暂无主指标</td>";
            }
            html += "<table style=\"display: none;\">";
            html += "<thead><tr>" + th + "</tr></thead>";
            html += "<tbody><tr>" + td + "</tr></tbody>";
            html += "</table>";

            html += "</div>";
            html += "<div class=\"row\">";
            html += "<div class=\"row2\">";
            html += "<span>交割地:<span class=\"warning\">" + caigous[i].Standard + "</span></span>";
            html += "</div>";
            html += "<div class=\"row2\">";
            html += "<span>价格:";
            if (caigous[i].StandardPriceMoney != "") {
                html += "<span class=\"warning\">" + caigous[i].StandardPriceMoney + "</span>元/吨"
            }
            else {
                html += "<span class=\"warning\">待议</span>"
            }
            html += "</span>";
            html += "</div>";
            html += "</div>";
            html += "<div class=\"row\">";
            /*html += "<div class=\"row2\">";
            html += "<span>交易员:<span class=\"warning\">" + caigous[i].BelongStaffName + "</span></span>";
            html += "</div>";*/
            html += "<div class=\"row2\">";
            html += "<span>数量:<span class=\"warning\">" + caigous[i].StandardTon + "</span>吨</span>";
            html += "</div>";
            html += "</div>";
           // html += "<div class=\"row\"><div class=\"row2\"><p class=\"time\"><span>" + caigous[i].CreatedTime + "</span></p></div><div class=\"row2\"><span class=\"time\">已有<span class=\"warning\">" + caigous[i].OfferCount + "</span>家报价</span></div></div>";
            //html += "<p class=\"time\"><span>" + caigous[i].CreatedTime + "</span></p>";
           /* html += "<div class=\"show_zb\" onclick=\"ShowIndexs(this,'采购指标',1);\"><i class=\"iconfont icon-wenjian\"></i>查看指标</div>";*/
            html += "<div class=\"row\">";
           /* html += "<div class=\"row2\">";
            html += "<a href=\"/caigou/CaigouRequirement?caigouId=" + caigous[i].Id + "\" class=\"btn btn-min " + caigous[i].ProgressClass + "\">" + caigous[i].Progress + "</a>";
            html += "</div>";*/
           /* html += "<div class=\"row2\">";
            html += "<a href=\"tel:" + caigous[i].Telephone + "\" class=\"btn btn-primary btn-min\">电话联系</a>";
            html += "</div>";*/
            html += "</div>";
            html += "</div>";
        }

    }
    $(".caigou_list .loading").remove();
    console.log(data.page);
    console.log(data.pageCount);
    if (data.page < data.pageCount) {
        html += "<a class=\"btn-all\" href=\"javascript:void(0)\" onclick=\"PostCaigouList(" + (data.page + 1) + ",this)\">更多</a>";
    }
    if (data.page == 1) {
        $(".caigou_list").html(html);
    }
    else {
        $(".caigou_list").append(html);
    }
}
function GetLoading(obj) {
    var html = "<div class=\"empty loading\"></div>";
    if (obj != "") {
        $(obj).parent().append(html);
        $(obj).remove();
    }
}