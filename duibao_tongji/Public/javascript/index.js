$(document).ready(function () {
    var _body = $('body'),
        menu_iocn = $('.menu-iocn'),
        mian_iframe = $('#mian_iframe');

    if (browser.versions.mobile) {
        if (!_body.hasClass('ishide')) {
            _body.addClass('ishide');
        }
    }

    var stats_href = window.localStorage.getItem('stats_href');
    if (stats_href === null) {
        stats_href = '../Index/home';
    }
    mian_iframe.attr('src', stats_href);


    $('#accordion').find('a').on('click', function () {
        var data_href = $(this).attr('data-href');
        if (data_href == '#') {
            layer.msg('该功能正在开发中！', {icon: 0});
        } else if (data_href != undefined) {
            mian_iframe.attr('src', data_href);
            window.localStorage.setItem('stats_href', data_href);
        }
    });

    menu_iocn.on('click', function () {
        if (_body.hasClass('ishide')) {
            _body.removeClass('ishide');
        } else {
            _body.addClass('ishide');
        }
    });


    $('.logout').on('click', function () {
        window.location.href = '../Login/loginout';
    });
});