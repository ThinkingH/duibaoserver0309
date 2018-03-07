<?php
header('Content-Type:text/html;charset=utf-8');
//文件的引入
require_once("../lib/c.core.php");

//入库链接
$url = 'http://xbapp.xinyouxingkong.com/admin/enter/';

//数据库的初始化
$HyDb = new HyDb(); 
$btnSubmit   = isset($_POST['btnSubmit'])?$_POST['btnSubmit']:'';
$company     = isset($_POST['company_name'])?$_POST['company_name']:'';
//$address  = isset($_POST['zitiaddress'])?$_POST['zitiaddress']:'';
$comaddress  = isset($_POST['company_address'])?$_POST['company_address']:'';
//$contacts = isset($_POST['company_contacts'])?$_POST['company_contacts']:'';
$contact    = isset($_POST['company_contact'])?$_POST['company_contact']:'';
$email      = isset($_POST['email'])?$_POST['email']:'';
$phone      = isset($_POST['phone'])?$_POST['phone']:'';

$passwd = '123456';
//session
$HySession = new HySession();

$code = $HySession->get('code');

$HySession->set('username',$phone);

if($btnSubmit!=''){
	
	 if($company==''){
		
		echo "<script type='text/javascript'>alert('公司名称不能为空！');</script>";
		exit;
	}
	
	if($comaddress==''){
		echo "<script type='text/javascript'>alert('公司地址不能为空！');</script>";
		exit;
	}
	
	if($contact==''){
		echo "<script type='text/javascript'>alert('联系人不能为空！');</script>";
		exit;
	}
	
	if($email==''){
		echo "<script type='text/javascript'>alert('邮箱不能为空！');</script>";
		exit;
	} 
	
	/* if($picurl==''){
		echo "<script type='text/javascript'>alert('营业执照不能为空！');</script>";
		exit;
	} */
	
	
	 if($phone==''){
		echo "<script type='text/javascript'>alert('手机号不能为空！');</script>";
		exit;
	}
	
	
	//地址经纬度的转换
	$urlj = 'http://api.map.baidu.com/geocoder?address=urlencode('.$comaddress.')&output=json&key=WPzUoVnSMWZXrUuSR5Vs22Cd17yhCZeD';
	$data = HyItems::vget($urlj);
	
	$truepath = json_decode($data['content'], true);
	
	if($truepath['status']=='OK'){//请求成功
		
		$lat = $truepath['result']['location']['lat'];
		$lng = $truepath['result']['location']['lng'];
	}else{
		$lat='';
		$lng='';
	}
	
	//判断手机号是否注册过
	$phoneselectsql  = "select id from shop_site where phone='".$phone."' or company='".$company."'";
	$phoneselectlist = $HyDb->get_all($phoneselectsql);
	if(count($phoneselectlist)>0){
		echo "<script type='text/javascript'>alert('用户已注册！');history.go(-1);</script>";
		exit;
	}
	
	
	//数据的入库操作
	$date=date('Y-m-d H:i:s');
	$insertsql = "insert into shop_site (flag,checkstatus,lianxiren,phone,password,company,address,email,create_datetime,lat,lng) values
		('1','1','".$contact."','".$phone."','".md5($passwd)."','".$company."','".$comaddress."','".$email."','".$date."','".$lat."','".$lng."')";
	
	$insertlist = $HyDb->execute($insertsql);
	
	if($insertlist===true){//
		//window.wxc.xcConfirm("注册成功！", window.wxc.xcConfirm.typeEnum.confirm);
		echo "<script type='text/javascript'>alert('注册成功！');window.location.href='http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php';</script>";
		//header("Location:http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php");
		exit;
	}else{
		echo "<script type='text/javascript'>alert('用户注册失败！');</script>";
		exit;
	}
	
	
}

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>兑宝商户后台</title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/register.css">
<!-- <script src="js/jquery-1.10.1.min.js"></script> -->
<script src="js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/xcConfirm.css"/>
<script src="js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>


</head>
<body>
<div id="dowebok">
  <div class="section login">
    <div class="headt" style="background:#000000;padding: 10px 0;height:30px;">
      <div class="container">
        <div class="row">
          <div class="headt_left"><img src="picture/icon.png" width="30px"></div>
          <div class="headt_right">
            <div class="lianx pull-left"><!-- <span class="tel_bj"></span> --></div>
          </div>
        </div>
      </div>
    </div>
    <div class="wrap" >
      <div class="container">
        <section class="row">
          <div class="generic">
            <p class="font6">申请开通服务<!-- <em class="pull-right"><a class="yzc" style="color:#25a48d; font-weight:700" href="#">已注册，直接登录</a></em> --></p>
            <form action="registefinish.php" method="post" onsubmit="return check()">
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>公司名称</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="company_name" placeholder="请填写完整公司名称">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
              <label class="col-xs-12 col-sm-5  control-label color999" ></label>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>公司所在地</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="company_address" id="company_address" placeholder="请填写公司地址">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
              <label class="col-xs-12 col-sm-5  control-label color999" ></label>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>联系人</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="company_contact" placeholder="请填写联系人">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
             <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>邮箱</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="email" placeholder="请输入邮箱">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>手机号</label>
              <div class="col-xs-5 col-sm-3 col-md-3    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="phone" id="phone" placeholder="请输入手机号">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
              <button type="button" class="btn btn-info" id="sendMessage">发送验证码</button> 
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>验证码</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="yanzhnegma" id="yanzhengma" placeholder="请输入验证码">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>开户优惠</label>
              <div class="col-xs-9 col-sm-6 col-md-6" style="height:30px;padding-top:5px">
              <div class="fg-line">
               <input type="submit" value="新商户499元抵扣" class="btn btn-primary" disabled
               			style="width:160px;height:20px;padding-top:2px;padding-bottom:2px; font-size:12px; text-align:center; background-color:#fff;border-color:red; color:red;">
               </div>
              </div>
            </div>
            
             <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>开户费用</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line" style="color:red">
                  1元
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
            
            <div class="form-group int_line">
               <div class="col-xs-12" style="padding-left: 15px;">
            <!--   <a href="uploadshop.php">下一步</a> -->
               <input class="btn btn-large btn-primary" name="btnSubmit" id="btnSubmit" type="submit" value="立即开通" style="width:300px; padding:6px 200; font-size:1.6rem; text-align:center; margin-bottom:12px;background-color:#25a48d;border-color:#25a48d; outline:none">
                
               </div>
            </div>
            </form>
          </div>
          <!--分线一下--> 
        </section>
      </div>
    </div>
    
  </div>
</div>

</body>
<script>
  //表单提交检查
  function check(){

	//公司名称
    company_name = company.val().trim();
    var company_flag = true;
    if(company_name == ''){
		window.wxc.xcConfirm("公司名称不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
		return false;
    }

    //公司地址
    company_address = $("#company_address").val().trim();
    if(company_address == ''){
    	window.wxc.xcConfirm("公司地址不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    //联系人
    var user_value = user.val().trim();
    if( user_value== ''){
      window.wxc.xcConfirm("联系人不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    //email
    var email_value = email.val().trim();
    var flag = true;
    if(email_value == ''){
      window.wxc.xcConfirm("邮箱不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }else{
      if(!/^([a-zA-Z0-9_\-\.\+]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(email_value)){  
        //if(!/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+){1,3}$/.test(email_value))
        alert('邮箱格式不正确！');
        return false;
      }
    }

    //手机号判断
    var phone_value = $("#phone").val().trim();
    var flag = true;
    if(phone_value == ''){
      window.wxc.xcConfirm("手机号不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;//!/^1[3,5,6,7,8][0-9]{9}$/.test(phone)
    }else{
      if(!/^1[3,5,6,7,8][0-9]{9}$/.test(phone_value)){  
        //if(!/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+){1,3}$/.test(email_value))
        alert('手机号格式不正确！');
        return false;
      }
    }

	//验证码
    var code_value = $("#yanzhengma").val().trim();
    if(code_value == ''){
      window.wxc.xcConfirm("验证码不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;//!/^1[3,5,6,7,8][0-9]{9}$/.test(phone)
    }



    
    
  }
//--------------------------------------------------------------------------------//

//公司名称
  var company = $("input[name=company_name]");
  company.blur(function(){
    check_company(company.val());
  });

  // 检测公司名称
  function check_company(value)
  {
    value = value.trim();
    if(value == '')
    {
      company.parent().next().html("公司名称不能为空！");
    }
  }

  //公司地址company_address
  var company_address = $("input[name=company_address]");
  company_address.blur(function(){
    var value = company_address.val().trim();
    if(value == '')
    {
    	company_address.parent().next().html('公司地址不能为空！');
    }else{
    	company_address.parent().next().html('');
    }
  });

  //联系人
  var user = $("input[name=company_contact]");
  user.blur(function(){
    var value = user.val().trim();
    if(value == '')
    {
      user.parent().next().html('联系人不能为空！');
    }else{
      user.parent().next().html('');
    }
  });

  //email
  var email = $("input[name=email]");
  email.blur(function(){
    email.parent().next().html('');
    var value = email.val().trim();
    if(value == '')
    {
      email.parent().next().html('邮箱不能为空！');
    }else{
      if(!/^([a-zA-Z0-9_\-\.\+]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value))  
      //if(!/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+){1,3}$/.test(value))
      {
        email.parent().next().html('邮箱格式不正确！');
      }
      
    }
  });

  //下发验证码
  $('#sendMessage').click(function(){
			
			//获取手机号
			var phone  = $("#phone").val();
		  	
		  	if(phone==''){
		  		window.wxc.xcConfirm("请填写手机号！", window.wxc.xcConfirm.typeEnum.confirm);
		  		return false;
		  	}

		  	if(!/^1[3,5,6,7,8][0-9]{9}$/.test(phone)){
		    	window.wxc.xcConfirm("请正确输入11位的手机号码！", window.wxc.xcConfirm.typeEnum.confirm);
		        return false;
		      }
		  	
		  /* 	if(!$.isNumeric(phone)||phone.length!=11){
		  		alert('请填写正确的手机号！');
		  		return false;
		  		
		  	} */
			
				//验证手机号是否注册过
				var errphone='no';
				 $.ajax({
			        url:"ajax_phone.php",
			        data:{phone:phone,type:1},
			        type:'GET',
			        async:false,
			        success:function(data){
						if(data=='phoneerror'){
							errphone = 'nopass';
						}
			        }
				 });
			
			
			if(errphone=='nopass'){
				window.wxc.xcConfirm("该手机已经注册过，请登录！", window.wxc.xcConfirm.typeEnum.confirm);
				return false;
			}
			
			
			//下发验证码
			var errmsg='no';
			var errcode='no';
			 $.ajax({
		        url:"ajax_phone.php",
		        data:{phone:phone,type:2},
		        type:'GET',
		        async:false,
		        success:function(data){
			      
					  if(data=='fail'){
						errcode='codefail';
					} 
					  if(data=='success'){
						errcode='ok';
					} 
					
		        }
			 }); 

			/*  if(errcode=='ok'){
				alert('验证码下发成功！');
				return false;
			}
			
			if(errcode=='codefail'){
				alert('短信验证码下发失败！');
				return false;
			} */
			
			//使button不可点
			
			$(this).attr('disabled','disabled');
			
			var t = 100;
			
			//启动定时器
			var inte = setInterval(function(){
				$('#sendMessage').html(t+"秒钟之后重新发送");
				t--;
				
				//判断
				if(t<0){
					$('#sendMessage').removeAttr('disabled');
					$('#sendMessage').html("免费获取验证码");
					//清空定时器
					clearInterval(inte);
				}
			},1000)
		
  });
	  
 
 
</script>
</html>