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
    <title>中铁国恒-注册</title>
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
    <!--CurrentSiteInfo:120.26.200.169-->
    
    <!-- <script src="js/common.popup.js"></script> -->
</head>
<body class="home">
            <header>
                <span class="left">
                 <a href="index.php"><i class="iconfont icon-home"></i></a>
                </span>
                <span class="title">注册</span>
                <span class="right">
                </span>

            </header>
<article>
<form action="userregister.php" id="form0" method="post"> 
       <div class="inputbox">
            <div class="item">
                <input id="MobilePhone" maxlength="11" name="MobilePhone" placeholder="请输入您的手机号" type="text" value="" />
            </div>
            
            <div class="item">
                <input id="ValidationCode" name="ValidationCode" placeholder="请输入手机验证码" type="text" value="" />
                <button type="button" id="btngetvalidationcode" class="yz-btn" value="获取验证码">获取验证码</button>
                <!-- <button id="sendMessage" type="button">点击发送手机验证码</button> -->
            </div>
            
            <div class="item">
                <input id="Password" name="Password" placeholder="设置密码" type="password" />
            </div>
            
        </div>
        
        <div class="inputbox">
            <div class="item">
                <input id="UserTrueName" name="UserTrueName" placeholder="请输入您的姓名" type="text" value="" />
            </div>
            
            <div class="item">
                <input id="CompanyName" name="CompanyName" placeholder="请输入所属公司" type="text" value="" />
            </div>
            
            <div class="item">
                <input id="btnregister" name="btnregister" class="btn btn-primary" type="submit" value="立即注册">
            </div>
            
        </div>
        
        <input type="submit" id="submitLogin" style="width:0px; height:0px; border:0px;" />
</form>
<div class="lead">
    
    已经有账号？ <a href="login.php" class="lead-btn">立即登录</a>
</div>


</article>

<script type="text/javascript">
    /* var popup = new commonpopup(); */
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
            }, 1000)
        }
    }
 
    //手机号的验证
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
        
		//用户手机号的判断
        $.post("code.php", { mobile: $("#MobilePhone").val(), codeType: "register" }, function (d) {
           /*  alert(d); */
             if (d=='fail') {
            	  alert("验证码下发失败", d.message);
                  $("#btngetvalidationcode").get(0).removeAttribute("disabled");
            }else if (d=='resign') {
            	  alert("该用户已经注册过，请直接登录！", d.message);
                  $("#btngetvalidationcode").get(0).removeAttribute("disabled");
            }else if(d=='success'){
				alert('验证码下发成功');
				return false;
             }else {
				alert(d);
				return false;
             }
        });
    });

    
    $("#btnregister").click(function () {
        if (!$(this).hasClass("btn-primary"))//已经点击注册，在服务端没有返回之前不能再次点击注册
        {
            return;
        }

        if (validate()) {
            $("#btnregister").removeClass("btn-primary");
            $("#submitLogin").click();
        }
    });

    function validate() {
        if ($.trim($("#MobilePhone").val()) == "") {
            alert("错误", "请输入您的手机号码", function () { $("#MobilePhone").focus(); });
            return false;
        }

        if ($.trim($("#ValidationCode").val()) == "") {
            alert("错误", "请输入手机验证码", function () { $("#ValidationCode").focus(); });
            return false;
        }

        if ($.trim($("#Password").val()) == "") {
            alert("错误", "请输入密码", function () { $("#Password").focus(); });
            return false;
        }

        var passwordLength = $("#Password").val().length;
        if (passwordLength < 6 || passwordLength > 25) {
            alert("错误", "密码长度应该在6到25位之间", function () { $("#Password").focus(); });
            return false;
        }

        return true;
    }

    function onRegisterSuccess(responseData) {
        if (responseData != null && responseData.success) {
            show("", "注册成功，欢迎来到中铁国恒！", function () { window.location.href = responseData.attr; }, 2000);
        }
        else {
            alert("错误", responseData.message);
            $("#btnregister").addClass("btn-primary");//添加样式，表示可以再次点击注册。
        }
    }
</script>

    

   


</body>
</html>
