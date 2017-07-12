<?php

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
	<title>流量充值测试</title>
	<link rel="stylesheet" href="../public/css/weui.min.css"/>
	<script src="../public/js/zepto.min.js"></script>
	<script type="text/javascript">
		$("document").ready(function(){
			
			$.ajax({
				type: "POST",
				async: false,
				url: "/wx_work/interface/xy/productlist.php",
				data: "phone=13800138000",
				success: function(data){
					$("#danxuanlist").empty();
					$("#danxuanlist").append(data);
					
				}
			});
			
			
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
				
				var phone = $("#phone").val();
				var staocan = $('.weui_check:checked').val();
				
				if(phone=='') {
					$("#hy_dialog2_content").empty();
					$("#hy_dialog2_content").append('请填写要充值流量的手机号码');
					
					$("#hy_dialog2").show();
					return false;
				}
				
				if(staocan==null){
					$("#hy_dialog2_content").empty();
					$("#hy_dialog2_content").append('请选择对应流量套餐');
					
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
</head>
<body>

<form action="../interface/wxbuytest/wx_pay_show.php" method="post" name="liuliangform" id="liuliangform" >

<input type="hidden" name="openId_md5" value="<?php echo md5($openId);?>" />

<div class="weui_cells_title"><b>流量直充测试</b></div>

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

<div class="weui_cells weui_cells_checkbox" id="danxuanlist">

</div>




<div class="weui_btn_area">
	<!-- <input type="submit" id="submit_pay" class="weui_btn weui_btn_primary" /> -->
	<a class="weui_btn weui_btn_primary" href="#" id="submit_pay">立即充值</a>
</div>

</form>

<!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="hy_dialog1" style="display: none;">
	<div class="weui_mask"></div>
	<div class="weui_dialog">
		<div class="weui_dialog_hd"><strong class="weui_dialog_title">系统提示</strong></div>
		<div class="weui_dialog_bd">自定义弹窗内容，居左对齐显示，告知需要确认的信息等</div>
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
