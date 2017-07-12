<?php

//红包抽奖

//引入主文件
require_once("../lib/c.core.php");


//获取用户openid，并将获取到的openid写入session，防止多次访问造成的获取不到openid
$HySession = new HySession();
$sess_user_openid = $HySession->get('user_openid');
if($sess_user_openid=='') {
	//获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();
	if($openId!='') {
		$HySession->set('user_openid',$openId);
	}
}else {
	$openId = $sess_user_openid;
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>红包抽奖</title>

<link rel="stylesheet" href="../public/hongbao/css/csshake.min.css">
<style>
button{border: none;}

/*红包*/
.red{width: 70%; padding-top: 130%; margin:30px 15%; position: relative;}
.red>span{width: 100%; height: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat; top: 0; left: 0; position: absolute;}
.red>button{position: absolute; top: 38%; left: 30%; font-size: 14px; width: 40%; height: 24%; border-radius: 100%; background: #fdc339; color: #fff;}
.red-jg{display: none; position:absolute; top: 40%; text-align: center; padding: 10px;}
.red-jg>h1{font-size: 20px; color: #ffc000; line-height: 40px;}
.red-jg>h5{color: #fff;}

/*按钮*/
.t-btn{padding: 10px;}
.t-btn>button{width: 100%; background: #ff4242; border-radius: 5px; color: #fff; line-height: 40px; font-size: 14px;}

</style>

<script src="../public/js/zepto.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	<?php
	
	//红包展示内容变量定义
	$showdatastr = '';
	
	//数据库初始化
	$HyDb = new HyDb();
	
	$sql_userhashongbao  = "select id,ttype,flag,tval
							from hongbaolist
							where wxname='".$openId."'
							and create_datetime>='".date('Y-m-01 00:00:00')."'";
	$list_userhashongbao = $HyDb->get_all($sql_userhashongbao);
	
	if(count($list_userhashongbao)>=1) {
		
		$showdatastr .= '<h1>很遗憾！</h1>';
		$showdatastr .= '<h3>每个用户每月只能领取一个红包，您已经领取过</h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/user_hongbaolist.php">查看红包记录</a></h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/liuliang_buy.php">立即购买流量</a></h3>';
		
		?>
		// 在带有red样式的div中添加shake-chunk样式
		$('.red').addClass('shake-chunk');
		// 点击按钮500毫秒后执行以下操作
		
		// 在带有red样式的div中删除shake-chunk样式
		$('.red').removeClass('shake-chunk');
		// 将redbutton按钮隐藏
		$('.redbutton').css("display" , "none");
		// 修改red 下 span   背景图
		$('.red > span').css("background-image" , "url(../public/hongbao/img/red-y.png)");
		// 修改red-jg的css显示方式为块
		$('.red-jg').css("display" , "block");
		
		
		<?php
		
		
	}
	?>
	
	
	// 点击redbutton按钮时执行以下全部
	$('.redbutton').click(function(){
		
		$.ajax({
			type: "POST",
			async: false,
			url: "./hongbao_huoqu.php",
			data: "key=5d15af0172251a8cafa06001efab922e",
			success: function(data){
				$("#hongbaomsgshow").empty();
				$("#hongbaomsgshow").append(data);
			}
		});
		
		
		// 在带有red样式的div中添加shake-chunk样式
		$('.red').addClass('shake-chunk');
		// 点击按钮500毫秒后执行以下操作
		setTimeout(function(){
			// 在带有red样式的div中删除shake-chunk样式
			$('.red').removeClass('shake-chunk');
			// 将redbutton按钮隐藏
			$('.redbutton').css("display" , "none");
			// 修改red 下 span   背景图
			$('.red > span').css("background-image" , "url(../public/hongbao/img/red-y.png)");
			// 修改red-jg的css显示方式为块
			$('.red-jg').css("display" , "block");
		},300);
	});
	
});
</script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
	<?php
		$appId = HY_APPID;
		$nowpageurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		$timestamp = time();
		$nonceStr = uniqid().mt_rand(10000,99999);
		$HyJsapiticket = new HyJsapiticket();
		$jsapiticket = $HyJsapiticket->get_jsapiticket();
		$signature = sha1('jsapi_ticket='.$jsapiticket.'&noncestr='.$nonceStr.'&timestamp='.$timestamp.'&url='.$nowpageurl);
		
		//分享图标说明及链接
		$one_title = '送流量吧送红包'; //分享标题
		$one_desc = '520红包送不停，嗨翻全场！'; //分享描述
		$one_link = 'http://wx.51faba.cn/wx_work/web/hongbaoshow.php'; // 分享链接
		$one_imgurl = 'http://wx.51faba.cn/wx_work/public/pic/fenxiang_2.png'; // 分享图标
		
		
		
		
	?>
	wx.config({
		debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		appId: '<?php echo $appId;?>', // 必填，公众号的唯一标识
		timestamp: <?php echo $timestamp;?>, // 必填，生成签名的时间戳
		nonceStr: '<?php echo $nonceStr;?>', // 必填，生成签名的随机串
		signature: '<?php echo $signature;?>',// 必填，签名，见附录1
		jsApiList: ['checkJsApi','onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	wx.ready(function(){
		// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
		wx.onMenuShareAppMessage({
			//分享给朋友
			title: '<?php echo $one_title;?>', // 分享标题
			desc: '<?php echo $one_desc;?>', // 分享描述
			link: '<?php echo $one_link;?>', // 分享链接
			imgUrl: '<?php echo $one_imgurl;?>', // 分享图标
			type: '', // 分享类型,music、video或link，不填默认为link
			dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			success: function () { 
				// 用户确认分享后执行的回调函数
				//alert('ok');
			},
			cancel: function () { 
				// 用户取消分享后执行的回调函数
				//alert('no');
			}
		});
		wx.onMenuShareTimeline({
			//分享到朋友圈
			title: '<?php echo $one_title;?>', // 分享标题
			link: '<?php echo $one_link;?>', // 分享链接
			imgUrl: '<?php echo $one_imgurl;?>', // 分享图标
			success: function () { 
				// 用户确认分享后执行的回调函数
				//alert('ok');
			},
			cancel: function () { 
				// 用户取消分享后执行的回调函数
				//alert('no');
			}
		});
		
	});
	
	
</script>
</head>
<body>
<!-- 红包 -->
<div class="red"><!-- shake-chunk -->
	<span style="background-image: url(../public/hongbao/img/red-w.png);"></span>
	<button class="redbutton" type="领取红包">拆红包</button>
	<div class="red-jg" id="hongbaomsgshow">
		<?php echo $showdatastr; ?>
	</div>
</div>



</body>
</html>