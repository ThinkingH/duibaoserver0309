// 初始化应用程序
var myApp = new Framework7({
    modalTitle: '',
    modalButtonOk: '确定'
});


// 出口选择器引擎
var $$ = Dom7;


// 添加视图
var mainView = myApp.addView('.view-main', {
    domCache: true
});

var mySwiper = myApp.swiper('.swiper-container', {
    // pagination: '.swiper-pagination',
    speed: 400,
    spaceBetween: 100
});
var arrstrpop = new Array();
//0 报名成功
arrstrpop.push('<p style="font-size: 1.5rem;color: #E34E60;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">' +
    '报名成功<br>敬请期待开奖</p>' +
    '<a href="" class="button" id="ok"' +
    'style="background:-webkit-linear-gradient(top,#fd5300,#e3495a);border:0;' +
    'color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>');
//1 已开奖
arrstrpop.push('<p style="font-size: 1rem;color: #333333;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">' +
    '本期活动已结束<br>敬请关注近期活动</p>' +
    '<a href="" class="button" id="ok"' +
    'style="background-color:#616161;border:0;' +
    'color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>');
//2 活动结束
arrstrpop.push('<p style="font-size: 1rem;color: #333333;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">' +
    '本期活动已结束,请下载<br>兑宝APP，关注更多精彩活动</p>' +
    '<a href="" class="button" id="ok"' +
    'style="background-color:#616161;border:0;' +
    'color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>');
//3 积分不足
arrstrpop.push('<p style="font-size: 1rem;color: #333333;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">' +
    '对不起，目前您积分不足，<br>请参考活动详情，赚取积分！</p>' +
    '<a href="" class="button" id="ok"' +
    'style="background-color:#616161;border:0;' +
    'color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>');
//4 重复报名
arrstrpop.push('<p style="font-size: 1rem;color: #333333;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">' +
    '不能重复报名<br>敬请期待开奖</p>' +
    '<a href="" class="button" id="ok"' +
    'style="background-color:#616161;border:0;' +
    'color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>');
var phone = $$('#phone');

var mid = 1;
var old_mid = window.localStorage.getItem('mid');
if (mid == old_mid) {
    var mid_phone = window.localStorage.getItem(mid + '_phone');
    phone.val(mid_phone);
}
$$('#participation').on('click', function () {
    if (!checkMobile(phone.val())) {
        myApp.alert('手机号码格式不正确!');
    } else {
        $$.ajax({
            method: 'POST',
            url: 'duobaoexec.php',
            data: {
                mid: mid,
                phone: phone.val()
            },
            success: function (data) {
                try {
                    var json = JSON.parse(data)
                    if (json.code == 200) {
                        myApp.pickerModal('.picker-info')
                    } else if (json.code == 201) {
                        open_pop(3);
                    } else if (json.code == 3) {
                        open_pop(4);
                        window.localStorage.setItem('mid', mid);
                        window.localStorage.setItem(mid + '_phone', phone.val());
                    } else if (json.code == 1) {
                        open_pop(0, function () {
                            window.location.reload();
                        });
                        window.localStorage.setItem('mid', mid);
                        window.localStorage.setItem(mid + '_phone', phone.val());
                    } else if (json.code == 11) {
                        open_pop(2);
                    } else if (json.code == 12) {
                        open_pop(1);
                    } else {
                        myApp.alert(json.msg);
                    }
                } catch (e) {
                    myApp.alert('返回数据出错!');
                }

            },
            error: function (xhr, status) {
                myApp.alert('网络出错!');
            }
        });
    }
});

getdata();

/**
 * 获取数据
 */
function getdata() {
    $$.ajax({
        method: 'POST',
        url: 'main.php',
        data: {
            mid: mid,
            phone: phone.val()
        },
        success: function (data) {
            try {
                var json = JSON.parse(data);
                if (json.code == 1) {
                    $$('.type').text('所需' + json.score + '积分');
                    $$('.participants').text(json.nowcount);
                    $$('.maxcount').text(json.maxcount);
                    $$('.circle').text(json.nowcount + '/' + json.maxcount);
                    var www = ($$('.line-bg').width() - ($$('.circle').width() + 20)) * (json.nowcount * 100 / json.maxcount / 100);
                    $$('.line').css('width', www + 'px');
                    var zjxx = $$('.zjxx').children();
                    if (json.status == 3) {
                        zjxx.eq(0).css('color', '#E2495D').text('中奖序号');
                        zjxx.eq(1).css('color', '#E2495D').text(json.zjxuhao);
                    }
                    if (phone.val() != '') {
                        setuserinfo(json.userinfo);
                        $$('.cyxh').text(json.userinfo.xuhao);
                    }

                    settable(json.list);
                } else if (json.code == 10) {
                    myApp.alert(json.msg);
                } else {

                }
            } catch (e) {
                myApp.alert('返回数据出错!');
            }
        },
        error: function () {
            myApp.alert('网络出错!');
        }

    });
}

/**
 * 打开pop
 * @param i
 */
function open_pop(i, back) {
    $$('.inner').html(arrstrpop[i]);
    var popu = $$('.popups');
    if (!popu.hasClass('show')) {
        popu.addClass('show');
    }
    $$('#ok').on('click', function () {
        close_pop(back);
    });
    $$('.close-popups').on('click', function () {
        close_pop(back);
    });
}

/**
 * 关闭pop
 */

function close_pop(back) {
    var popu = $$('.popups');
    if (popu.hasClass('show')) {
        popu.removeClass('show');
    }
    $$('#ok').off('click');
    $$('.close-popups').off('click');
    if (typeof back != "undefined")
        back();
}

var oneself = $$('.oneself');
var oneself_c = oneself.children();
var others = oneself.nextAll();

/**
 * 设置用户信息
 */
function setuserinfo(userinfo) {
    oneself_c.eq(0).text(userinfo.xuhao);
    oneself_c.eq(1).text(userinfo.phone);
    oneself_c.eq(2).text(userinfo.create_datetime);
    oneself_c.css('display', 'table-cell');
}

/**
 * 设置表格信息
 */
function settable(list) {
    $$.each(others, function (i, v) {
        var _td = v.children;
        if (list[i]) {
            $$(_td[0]).text(list[i].xuhao);
            $$(_td[1]).text(list[i].phone);
            $$(_td[2]).text(list[i].create_datetime
            );
        } else {
            $$(_td).hide();
        }
    });
}

/**
 * 正则认证手机号码
 * @param {Object} phone
 */
function checkMobile(phone) {
    if (phone.length == 11) {
        var re = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        return re.test(phone);
    } else {
        return false;
    }
}

/**
 * 获取get请求参数
 * @param name
 * @returns {null}
 * @constructor
 */
function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}