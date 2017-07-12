<?php
$miyaostr = isset($_GET['miyaostr'])?$_GET['miyaostr']:'';

$pan = substr($miyaostr,0,1);
if($pan=='|') {
	$urldata = 'miyaostr='.urlencode($miyaostr);
	header('Location:./cardmsgshow.php?'.$urldata);
	
}




?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<title>流量兑换</title>
		<link rel="stylesheet" href="../public/css/frozen.css">
		<script src="../public/js/zepto.min.js"></script>
		<script src="../public/js/frozen.js"></script>
		<script type="text/javascript">
		$("document").ready(function(){
			
			$("#button_duihuan").click(function(){
				var phone = $("#phone").val();
				var miyao = $("#miyao").val();
				var vcode = $("#vcode").val();

				if(phone.length!=11) {
					$("#msg_content").empty();
					$("#msg_content").append('error,手机号不能为空');
					hy_dialog_show();
					return false;
				}
				if(vcode.length!=4) {
					$("#msg_content").empty();
					$("#msg_content").append('error,验证码不能为空');
					hy_dialog_show();
					return false;
				}
				if(miyao.length<15) {
					$("#msg_content").empty();
					$("#msg_content").append('error,密钥不能为空');
					hy_dialog_show();
					return false;
				}
				$.ajax({
					type: "POST",
					async: false,
					url: "duihuan_exec.php",
					data: "phone="+phone+"&miyao="+miyao+"&vcode="+vcode,
					success: function(data){
						//验证码刷新
						fleshVerify();
						$("#vcode").val('');
						
						if(data=='ok') {
							$("#msg_content").empty();
							$("#msg_content").append('恭喜您，流量充值订单已经成功提交，请耐心等待流量充值结果返回');
							hy_dialog_show();
						}else {
							//alert(data);
							$("#msg_content").empty();
							$("#msg_content").append(data);
							hy_dialog_show();
						}
						
					}
				});

			});
			
			
			//=======================================================================
			
			
			$("#button_select").click(function(){
				var sel_phone = $("#sel_phone").val();
				var sel_vcode = $("#sel_vcode").val();

				if(sel_phone.length!=11) {
					$("#msg_content").empty();
					$("#msg_content").append('error,手机号不能为空');
					hy_dialog_show();
					return false;
				}
				if(sel_vcode.length!=4) {
					$("#msg_content").empty();
					$("#msg_content").append('error,验证码不能为空');
					hy_dialog_show();
					return false;
				}
				$.ajax({
					type: "POST",
					async: false,
					url: "duihuanhistory.php",
					data: "sel_phone="+sel_phone+"&sel_vcode="+sel_vcode,
					success: function(data){
						//验证码刷新
						sel_fleshVerify();
						$("#sel_vcode").val('');
						
						if(data.substring(0,5)=='error') {
							//alert(data);
							$("#msg_content").empty();
							$("#msg_content").append(data);
							hy_dialog_show();
						}else {
							$("#historydata").empty();
							$("#historydata").append(data);
						}
						
						
					}
				});
				
			});
			
		});

		
		function fleshVerify(){ 
			//重载验证码
			var time = new Date().getTime();
			document.getElementById('imagevcode').src= './codeimage.php?time='+time;
		}
		function sel_fleshVerify(){ 
			//重载验证码
			var time = new Date().getTime();
			document.getElementById('sel_imagevcode').src= './codeimagesel.php?time='+time;
		}
		
		function hy_dialog_show(){
			$(".ui-dialog").dialog("show");
		}
		function hy_dialog_hide(){
			$(".ui-dialog").dialog("hide");
		}
		
		</script>

		<style type="text/css">
			.mytable tr td{
				padding:6px 3px;
			}
		</style>
	</head>
	<body ontouchstart="">
		<!--<header class="ui-header ui-header-positive ui-border-b">
			<h1>流量兑换</h1>
		</header>
		
		<section class="ui-container ui-center">
		
		<table class="mytable" style="margin-top:30px;">
			<tr>
				<td align="right">手机号:</td>
				<td align="left" colspan="2"><input type="text" name="phone" id="phone" maxlength="11" size="20" ></td>
			</tr>
			<tr>
				<td align="right">兑换密钥:</td>
				<td align="left" colspan="2"><input type="text" name="miyao" id="miyao" maxlength="18" size="20" value="<?php echo $miyaostr;?>" ></td>
			</tr>
			<tr>
				<td align="right">验证码:</td>
				<td align="left">
				<input type="text" name="vcode" id="vcode" maxlength="4" size="5" >
				</td>
				<td align="left">
				<img src="codeimage.php" style="margin:0px;" id="imagevcode" onclick="fleshVerify()"/>
				<a href="#" onclick="fleshVerify()" >换一个</a>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				<input type="button" id="button_duihuan" value="确认兑换" class="ui-btn-s" style="width:200px;height:36px;">
				</td>
			</tr>
		</table>
		
		
		


		</section>-->
		
		
		
		<div class="ui-dialog">
			<div class="ui-dialog-cnt">
				<header class="ui-dialog-hd ui-border-b">
					<h3>对话框提示</h3>
				</header>
				<div class="ui-dialog-bd">
					<div id="msg_content"></div>
				</div>
				<div class="ui-dialog-ft">
					<button type="button" data-role="button" onclick="hy_dialog_hide()">确认</button>
				</div>
			</div>
		</div>
		
		
		<section class="ui-container">
			<section id="tab">
				<div class="demo-item">
					<p class="demo-desc"></p>
					<div class="demo-block">
						<div class="ui-tab">
							<ul class="ui-tab-nav ui-border-b">
								<li class="current">流量兑换</li>
								<li>记录查询</li>
							</ul>
							<ul class="ui-tab-content" style="width:200%;height:auto;" >
								<li>
								<!-- ----------------------------------------------------------- -->
								<section class="ui-container ui-center" style="height:auto;">
								<table class="mytable" style="margin-top:30px;margin-bottom:30px;">
									<tr>
										<td align="right" colspan="3"></td>
									</tr>
									<tr>
										<td align="right">手机号:</td>
										<td align="left" colspan="2"><input type="text" name="phone" id="phone" maxlength="11" size="20" ></td>
									</tr>
									<tr>
										<td align="right">兑换密钥:</td>
										<td align="left" colspan="2"><input type="text" name="miyao" id="miyao" maxlength="18" size="20" value="<?php echo $miyaostr;?>" ></td>
									</tr>
									<tr>
										<td align="right">验证码:</td>
										<td align="left">
										<input type="text" name="vcode" id="vcode" maxlength="4" size="5" >
										</td>
										<td align="left">
										<img src="codeimage.php" style="margin:0px;" id="imagevcode" onclick="fleshVerify()"/>
										<a href="#" onclick="fleshVerify()" >换一个</a>
										</td>
									</tr>
									<tr>
										<td colspan="3">
										<input type="button" id="button_duihuan" value="确认兑换" class="ui-btn-s" style="width:200px;height:36px;">
										</td>
									</tr>
								</table>
								</section>
								<!-- ----------------------------------------------------------- -->
								</li>
								<li>
								<!-- ----------------------------------------------------------- -->
								<section class="ui-container ui-center" style="height:auto;">
								<table class="mytable" style="margin-top:30px;margin-bottom:30px;">
									<tr>
										<!--  <td align="right" colspan="3"><a href="http://xbshop.xinyouxingkong.com/index.php/Mobile/Index/index.html" style="color:orange;">前往商城首页</a></td>-->
									</tr>
									<tr>
										<td align="right">手机号:</td>
										<td align="left" colspan="2"><input type="text" name="sel_phone" id="sel_phone" maxlength="11" size="20" ></td>
									</tr>
									<tr>
										<td align="right">验证码:</td>
										<td align="left">
										<input type="text" name="sel_vcode" id="sel_vcode" maxlength="4" size="5" >
										</td>
										<td align="left">
										<img src="codeimagesel.php" style="margin:0px;" id="sel_imagevcode" onclick="sel_fleshVerify()"/>
										<a href="#" onclick="sel_fleshVerify()" >换一个</a>
										</td>
									</tr>
									<tr>
										<td colspan="3">
										<input type="button" id="button_select" value="查询" class="ui-btn-s" style="width:200px;height:36px;">
										</td>
									</tr>
								</table>
								
								
								<div id="historydata">
								</div>
								</section>
								
								
								
								
								
								
								<!-- ----------------------------------------------------------- -->
								</li>
							</ul>
						</div>
					</div>
				</div>
			</section>
		</section>
		 <script>
		(function (){
			var tab = new fz.Scroll('.ui-tab', {
				role: 'tab',
				autoplay: false,
				interval: 1000
			});
			/* 滑动开始前 */
			tab.on('beforeScrollStart', function(fromIndex, toIndex) {
				console.log(fromIndex,toIndex);// from 为当前页，to 为下一页
				//fleshVerify();
				//sel_fleshVerify();
			})
		})();
		</script>
		
	</body>
</html>
