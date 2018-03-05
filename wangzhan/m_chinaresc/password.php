<!DOCTYPE html>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">
    <title>中铁国恒-忘记密码</title>
    <link href="/content/images/ico/72.png" sizes="72x72" rel="apple-touch-icon-precomposed">
    <link href="/content/images/ico/144.png" sizes="144x144" rel="apple-touch-icon">
    <link href="/content/images/ico/57.png" sizes="57x57" rel="apple-touch-icon">
    <link href="/content/images/ico/114.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/swiper.min.css" rel="stylesheet" />
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery.unobtrusive-ajax.min.js"></script>
    <script src="js/swiper.js"></script>
    <script> document.addEventListener("touchstart", function () { }, true);</script>
    <!--CurrentSiteInfo:121.40.224.55-->
    
    <!-- <script src="js/common.popup.js"></script> -->

</head>
<body class="home">
            <header>
                <span class="left">
                            <a href="index.php"><i class="iconfont icon-home" ></i></a>

                </span>
                <span class="title">忘记密码</span>
                <span class="right">
                </span>

            </header>


    

<article>
<form action="userpassword.php" id="form0" method="post">    
    <div class="inputbox">
            <div class="item">
                <input id="MobilePhone" maxlength="11" name="MobilePhone" placeholder="请输入您的手机号" type="text" value="" />
            </div>
            <div class="item error">
                <input id="MobileValidationCode" name="MobileValidationCode" placeholder="请输入手机验证码" type="text" value="" />
                <button type="button" id="btngetvalidationcode" class="yz-btn" value="获取验证码">获取验证码</button>
            </div>
        </div>
        <div class="inputbox">
            <div class="item">
                <input id="NewPassword" maxlength="25" name="NewPassword" placeholder="请您输入新密码" type="password" value="" />
            </div>
            <div class="item">
                <input id="ConfirmedNewPassword" maxlength="25" name="ConfirmedNewPassword" placeholder="请您再次输入新密码" type="password" value="" />
            </div>
            <div class="item">
                <input type="submit" class="btn btn-primary" name="btnsubmit" id="btnsubmit" value="提交">
            </div>
        </div>
<div class="lead">
    
    已经有账号？ <a href="login.php" class="lead-btn">立即登录</a>
</div>
        <input type="submit" id="submitLogin" style="width:0px; height:0px; border:0px;" />
</form></article>

<script type="text/javascript">
   /*  var popup = new commonpopup(); */
    var wait = 90;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value = "获取验证码";
            wait = 90;
        } else {
            o.setAttribute("disabled", true);
            o.value = "重新发送(" + wait + ")";
            wait--;
            setTimeout(function () {
                time(o)
            },
                    1000)
        }
    }

    $("#btngetvalidationcode").click(function () {
        if ($.trim($("#MobilePhone").val()) == "") {
            alert("请输入您的手机号码")
            $("#MobilePhone").focus();
            return false;
        } else if ($.trim($("#MobilePhone").val()).length != 11) {
            alert("手机号码格式不对")
            $("#MobilePhone").focus();
            return false;
        }


        $("#btngetvalidationcode").get(0).setAttribute("disabled", true);

        $.post("passwdcode.php", { mobile: $("#MobilePhone").val(), codeType: "forgetpassword" }, function (d) {
//alert(d);
        	  if (d=='fail') {
           	      alert("验证码下发失败", d.message);
                 $("#btngetvalidationcode").get(0).removeAttribute("disabled");
           } 

        	  if (d=='nosign') {
           	      alert("该用户没有注册过，请进行注册！", d.message);
                 $("#btngetvalidationcode").get(0).removeAttribute("disabled");
           } 
           /*  if (d.success) {
                time($("#btngetvalidationcode").get(0));
            }
            else {
                popup.show("", d.message, null, 3000);
                $("#btngetvalidationcode").get(0).removeAttribute("disabled");
            } */
        });
    });

    $("#btnsubmit").click(function () {
        if (validate()) {
            $("#submitLogin").click();
        }
    });

    function validate() {
        if ($.trim($("#MobilePhone").val()) == "") {
            alert("错误", "请输入您的手机号码", function () { $("#MobilePhone").focus(); });
            return false;
        }

        if ($.trim($("#MobileValidationCode").val()) == "") {
            alert("错误", "请输入手机验证码", function () { $("#ValidationCode").focus(); });
            return false;
        }

        if ($("#NewPassword").val() == "") {
            alert("错误", "请您输入新密码", function () { $("#NewPassword").focus(); });
            return false;
        }

        var passwordLength = $("#NewPassword").val().length;
        if (passwordLength < 6 || passwordLength > 25) {
            alert("错误", "密码长度应该在6到25位之间", function () { $("#NewPassword").focus(); });
            return false;
        }


        if ($("#ConfirmedNewPassword").val() == "") {
            alert("错误", "请您再次输入新密码", function () { $("#ConfirmedNewPassword").focus(); });
            return false;
        }

        var passwordLength = $("#ConfirmedNewPassword").val().length;
        if (passwordLength < 6 || passwordLength > 25) {
            alert("错误", "密码长度应该在6到25位之间", function () { $("#ConfirmedNewPassword").focus(); });
            return false;
        }

        if ($("#NewPassword").val() != $("#ConfirmedNewPassword").val()) {
            show("提示", "两次输入密码不一致");
            return false;
        }

        return true;
    }

  /*   function onChangePasswordSuccess(data) {
        if (data.success) {
            popup.show("", "密码修改成功", function () { window.location.href = data.attr.returnUrl; }, 2000);
        }
        else {
            popup.alert("错误", data.message);
        }
    } */
</script>

    

    

</body>
</html>
