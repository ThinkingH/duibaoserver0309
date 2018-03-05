

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
    <title>中铁国恒-登录账号</title>
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
    
    <link href="css/home_1.css" rel="stylesheet">
    <script src="js/common.popup.js"></script>
    
    <script type="text/javascript">
  	 $("document").ready(function(){
  		 
  		$("#btnLogin").click(function() {
  			
  			var phone  = $("#MobilePhone").val();
  			var password = $("#Password").val();
  			
  			if(phone==''){
  				
  				alert('请输入账号！');
  		  		return false;
  			}
  			
  			if(password==''){
  				alert('请输入密码！');
  		  		return false;
  			}
  			
  			$panduan='no';
  			if(account!='' && password!=''){
  			 $.ajax({
                 type: "POST",
                 async: false,
                 url: "login_user.php",
                 data: "phone="+phone+"&password="+password,
                 success: function(data){
                        if(data=='error'){
                        	$panduan='pderror';
                        }
                 }
             });
  			}
  			
  		if($panduan=='pderror'){
  			
  			alert('账户或密码错误,不存在！');
		  	return false;
  		}	
  			
  			
  		});
  		 
  	 });
  	</script>

</head>
<body class="home">
            <header>
                <span class="left">
                            <a href="index.php"><i class="iconfont icon-home" style="background: url('./images/12.jpg') no-repeat center;"></i></a>

                </span>
                <span class="title">登录账号</span>
                <span class="right">
                </span>

            </header>
<!-- data-ajax-success="onLoginSuccess"  -->
<article>
<form action="userlogin.php" id="form0" method="post">     
   <div class="inputbox">
            <div class="item error">
                <input id="MobilePhone" name="MobilePhone" placeholder="请输入您的手机号码" type="text" value="" />
            </div>
            
            <div class="item error">
                <input id="Password" name="Password" placeholder="请输入您的密码" type="password" />
                <a href="password.php" class="forget">忘记密码</a>
            </div>
            
             <div class="item">
                <input type="submit" name="submit" id="btnLogin" class="btn btn-primary">
            </div>
            
        </div>
        
</form>
   
 <div class="lead">
        还没有账号？ <a href="register.php" class="lead-btn">立即注册</a>
    </div>
</article>

<script>
  

   /*   var popup = new commonpopup();

    $(function () {
        $("#btnLogin").click(function () {
            if (check()) {
                $("#submitLogin").click();
            }
        });
    });  */

    /*  function check() {
        if ($.trim($("#MobilePhone").val()) == "") {
            popup.alert("错误", "请输入手机号码", function () { $("#MobilePhone").focus(); });
            return false;
        }

        if ($.trim($("#Password").val()) == "") {
            popup.alert("错误", "请输入密码", function () { $("#Password").focus(); });
            return false;
        }

        return true;
    }  */

   /*   function onLoginSuccess(responseData) {
        if (responseData != null && responseData.success) {
            popup.show("", "登录成功", function () { window.location.href = responseData.attr; }, 1500);
        }
        else {
            popup.alert("错误", responseData.message);
        }  
     }  */
</script>

    

    


</body>
</html>
