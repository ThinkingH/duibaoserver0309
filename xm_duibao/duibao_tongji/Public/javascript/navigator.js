var browser = {
    versions: function () {
        var u = navigator.userAgent,
            app = navigator.appVersion;
        return {
            mobile: !!u.match(/AppleWebKit.*Mobile.*/),
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
            android: u.indexOf("Android") > -1 || u.indexOf("Linux") > -1,
            iPhone: u.indexOf("iPhone") > -1,
            iPad: u.indexOf("iPad") > -1
        };
    }(),
    bUA: function () {
        //判断 浏览器 类型
        var ex = navigator.userAgent,
            bUA;
        if (ex.indexOf("MSIE") >= 0) {
            bUA = "Internet Explorer 10  Or Earlier"; // IE浏览器
        } else if (ex.indexOf("Firefox") >= 0) {
            bUA = "Firefox";
        } else if (ex.indexOf("Chrome") >= 0) { // 谷歌浏览器
            bUA = "Chrome";
            if (ex.indexOf("360SE") >= 0) { // 360安全浏览器
                bUA = "360SE";
            } else if (ex.indexOf("360EE") >= 0) { // 360极速浏览器
                bUA = "360EE";
            } else if (ex.indexOf("SE") >= 0 && ex.indexOf("360SE") == -1) { // 搜狗浏览器
                bUA = "SouGou";
            } else if (ex.indexOf("Maxthon") >= 0) { // 遨游浏览器
                bUA = "Maxthon";
            }
        } else if (ex.indexOf("UCBrowser") >= 0 || ex.indexOf("UCWEB") >= 0) { // UC浏览器
            bUA = "UCBrowser";
        } else if (ex.indexOf("Opera") >= 0) { // Opera浏览器
            bUA = "Opera";
        } else if (ex.indexOf("Safari") >= 0) { // 苹果浏览器
            bUA = "Safari";
        } else if (ex.indexOf("Netscape") >= 0) {
            bUA = "Netscape";
        } else if (ex.indexOf("like Gecko") >= 0 && ex.indexOf("Trident") >= 0) {
            bUA = "Internet Explorer 11 Or Later"; // IE11 以后，不再用 MSIE
        } else {
            bUA = "Other Broswer";
        }
        return bUA;
    }(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
};
console.log("userAgent 内容:" + navigator.userAgent);
console.log("是否为移动终端: " + browser.versions.mobile);
console.log("是否 ios: " + browser.versions.ios);
console.log("是否 android: " + browser.versions.android);
console.log("语言: " + browser.language);
console.log("浏览器内核：" + browser.bUA);