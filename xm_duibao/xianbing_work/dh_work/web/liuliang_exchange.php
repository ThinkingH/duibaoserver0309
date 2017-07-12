<?php

//流量兑换页面

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
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title></title>
	<link rel="stylesheet" href="../public/css/weui.min.css"/>
	<script src="../public/js/zepto.min.js"></script>
	<script type="text/javascript">
		$("document").ready(function(){
			
			$('#phone').bind('input propertychange', function() { 
				//进行相关操作 
				var phone = $(this).val();
				if(phone.length>=11) {
						
					$.ajax({
						type: "POST",
						async: false,
						url: "/wx_work/interface/xy/operatorprovince.php",
						data: "phone="+phone,
						success: function(data){
							$("#phonecontent").empty();
							$("#phonecontent").append(data);
							
						}
					});
					
					$.ajax({
						type: "POST",
						async: false,
						url: "/wx_work/interface/xy/productlist.php",
						data: "phone="+phone,
						success: function(data){
							$("#danxuanlist").empty();
							$("#danxuanlist").append(data);
							
						}
					});
					
					
				}
				
			});
			
			
			
			$("#hy_dialog1_suc_close").click(function() {
				$("#hy_dialog1").hide();
			});
			$("#hy_dialog1_err_close").click(function() {
				$("#hy_dialog1").hide();
			});
			$("#hy_dialog2_suc_close").click(function() {
				$("#hy_dialog2").hide();
			});
			
			
			$("#submit_pay").click(function() {
				
				
				<?php
				$nowdate   = date('Y-m-d');
				$begindate = date('Y-m-01');
				$last1date = date('Y-m-d', strtotime("$begindate +1 month -1 day"));
				//$last2date = date('Y-m-d', strtotime("$begindate +1 month -2 day"));
				//$last3date = date('Y-m-d', strtotime("$begindate +1 month -3 day"));
				//|| $nowdate==$last2date || $nowdate==$last3date 
				
				if( $nowdate==$last1date) {
					//月末最后1天不允许执行兑换操作
					?>
					
					$("#hy_dialog2_content").empty();
					$("#hy_dialog2_content").append('对不起，月底最后一天不允许执行流量兑换操作，给您带来不便请谅解');
					$("#hy_dialog2").show();
					return false;
					
					<?php
				}
				
				?>
				
				
				var phone = $("#phone").val();
				var miyao = $('#miyao').val();
				
				if(phone=='') {
					$("#hy_dialog2_content").empty();
					$("#hy_dialog2_content").append('请填写要兑换流量的手机号码');
					
					$("#hy_dialog2").show();
					return false;
				}
				
				if(miyao==null){
					$("#hy_dialog2_content").empty();
					$("#hy_dialog2_content").append('兑换秘钥不能为空');
					
					$("#hy_dialog2").show();
					return false;
				}else{
					//提交表单
					$("#liuliangform").submit();	
					
					//return false;
// 					$("#hy_dialog2_content").empty();
// 					$("#hy_dialog2_content").append(staocan);
					
// 					$("#hy_dialog2").show();
// 					return false;
					
				}
				
				
				
				
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
		$one_title = '送流量吧'; //分享标题
		$one_desc = '重磅福利!'; //分享描述
		$one_link = 'http://wx.51faba.cn/wx_work/web/liuliang_exchange.php'; // 分享链接
		$one_imgurl = 'http://wx.51faba.cn/wx_work/public/pic/fenxiang_1.jpg'; // 分享图标
		
		
		
		
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
<!-- 顶部标题栏------------------------------------ -->
<div style="background-color:#4AC7E0;height:35px;">
<div style="height:33px;width:33px;float:left;margin:0px;padding:2px;">
<a data-ajax="false" href="#"><image src="../public/pic/dh_w_l.png" style="width:30px;height:30px;" onclick="javascript:history.go(-1);"/></a>
</div>
<div style="height:32px;width:70%;float:left;margin-top:7px;text-align:center;">
<span>流量兑换</span>
</div>
<div style="height:33px;width:33px;float:right;margin:0px;padding:2px;">
<a data-ajax="false" href="http://wx.51faba.cn/wx_work/wx/wxshow/gerenzhongxin.php"><image src="../public/pic/dh_w_r.png" style="width:30px;height:30px;" /></a>
</div>
</div>
<!-- ------------------------------------ -->


<form action="../interface/wxbuy/wx_exchange_buy.php" method="post" name="liuliangform" id="liuliangform" >

<input type="hidden" name="openId_md5" value="<?php echo md5($openId);?>" />

<div class="weui_cells_title"><b>&nbsp;</b></div>

<div class="weui_cells weui_cells_form">
	<div class="weui_cell">
		<div class="weui_cell_hd">
			<label class="weui_label">手机</label>
		</div>
		<div class="weui_cell_bd weui_cell_primary">
			<input type="text" id="phone" name="phone" class="weui_input" maxlength="11" style="font-weight:bold;" placeholder="请输入要充值的手机号"/>
		</div>
		
	</div>
	
</div>
<div class="weui_cells_tips" id="phonecontent"></div>

<div class="weui_cells weui_cells_form">
	<div class="weui_cell">
		<div class="weui_cell_hd">
			<label class="weui_label">秘钥</label>
		</div>
		<div class="weui_cell_bd weui_cell_primary">
			<input type="text" id="miyao" name="miyao" class="weui_input" maxlength="32" style="font-weight:bold;" placeholder="请输入流量兑换秘钥"/>
		</div>
		
	</div>
	
</div>



<div class="weui_btn_area">
	<a class="weui_btn weui_btn_primary" href="#" id="submit_pay">立即兑换</a>
</div>

</form>

<br/><br/>
<div style="margin:0px 5px;">
<font color="red" size="-1">
	兑换秘钥说明：流量兑换秘钥分为三种，移动以YD开头，联通已LT开头，电信以DX开头，充值手机号必须与兑换码所绑定流量产品对应的运营商一致
</font>
</div>

<!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="hy_dialog1" style="display: none;">
	<div class="weui_mask"></div>
	<div class="weui_dialog">
		<div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
		<div class="weui_dialog_bd"></div>
		<div class="weui_dialog_ft">
			<a href="javascript:;" class="weui_btn_dialog default" id="hy_dialog1_err_close">取消</a>
			<a href="javascript:;" class="weui_btn_dialog primary" id="hy_dialog1_suc_close">确定</a>
		</div>
	</div>
</div>
<!--END dialog1-->
<!--BEGIN dialog2-->
<div class="weui_dialog_alert" id="hy_dialog2" style="display: none;">
	<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
			<div class="weui_dialog_bd" id="hy_dialog2_content"></div>
			<div class="weui_dialog_ft">
			<a href="javascript:;" class="weui_btn_dialog primary" id="hy_dialog2_suc_close">确定</a>
		</div>
	</div>
</div>
<!--END dialog2-->


</body>
</html>
