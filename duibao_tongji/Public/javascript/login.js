$(document).ready(function () {
    var
        _username = $('#username'),
        _password = $('#password'),
        _submit = $('.submit');

    document.onkeydown = function (event) {
        var e = event || window.event || arguments.callee.caller.arguments[0];

        if (e && e.keyCode == 13) { // enter 键
            _submit.trigger("click");
        }
    };
    _submit.on('click', function () {
        if ($.trim(_username.val()) == '') {
            layer.tips('用户名不能为空！', _username, {
                tips: [1, '#78BA32']
            });
        } else if ($.trim(_password.val()) == '') {
            layer.tips('密码不能为空！', _password, {
                tips: [1, '#78BA32']
            });
        } else {
            //window.location.href = 'index.html';
            $.ajax({
                type: 'POST',
                url: '../Login/login',
                data: {
                    'username': _username.val(),
                    'password': _password.val()
                },
                success: function (data) {
                    try {
                        var json = JSON.parse(data);
                        if (json.code == 0) {
                            window.location.href = '../Index/index';
                        } else if (json.code == 1) {
                            layer.msg('用户名或密码错误！', {icon: 5});
                        } else {
                            layer.msg('返回数据出错！', {icon: 5});
                        }
                    } catch (e) {
                        layer.msg('返回数据出错！', {icon: 5});
                    }
                },
                error: function (e) {
                    layer.msg('网络出错！', {icon: 2});
                }
            });
        }
    });
});