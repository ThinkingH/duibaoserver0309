//获取地理位置开始
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    }
    else { x.innerHTML = "Geolocation is not supported by this browser."; }


}

function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
    //baidu
    var url = "http://api.map.baidu.com/geocoder/v2/?ak=C93b5178d7a8ebdb830b9b557abce78b&callback=renderReverse&location=" + latlon + "&output=json&pois=0";
    $.ajax({
        type: "GET",
        dataType: "jsonp",
        url: url,
        beforeSend: function () {
            $(".location").html('定位中');
        },
        success: function (json) {
            if (json.status == 0) {
                $(".location").html(json.result.addressComponent.city);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $(".location").html("");//latlon + "地址位置获取失败"
        }
    });
}


function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}

//获取地理位置结束

var swiper = new Swiper('.fullSlide', {
    pagination: '#full-pagination',
    paginationClickable: true,
    loop: true,
    autoplay: 2500,
    autoplayDisableOnInteraction: false
    //autoplay: 2500,
    //autoplayDisableOnInteraction: false
});
var swiper2 = new Swiper('.cjlist', {
    paginationClickable: true,
    direction: 'vertical',
    loop: true,
    autoplay: 2500,
    autoplayDisableOnInteraction: false
});
var swiper3 = new Swiper('.haiyunslide', {
    pagination: '#haiyun-pagination',
    loop: true,
    autoplay: 2500,
    autoplayDisableOnInteraction: false
});
$(function () {
    getLocation();//获取地理位置
    //地区选择
    $("#popregion").click(function () {
        $(".popup-region").toggle();
    })
    $(".popup-region li").click(function () {
        $(".popup-region").hide();
    })
    //$(".popup-attention-bottom").click(function () {
    //    $(".popup").show();
    //    $(".attention").show();
    //})
    //$(".popup").click(function () {
    //    $(".popup").hide();
    //    $(".attention").hide();
    //})
    $(".close").click(function() {
        $("#IsWxBrowser").hide();
    });
    $(".content").click(function() {
        /*location.href = "/Home/App";*/
    });
    if (isWeiXin()) {
        $("#IsWxBrowser").show();
    }
});
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }
}