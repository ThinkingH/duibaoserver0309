<?php
//流量密钥兑换执行函数

//引入主文件
require_once("../lib/c.core.php");

//接收参数



$miyaostr = HyItems::arrayItem ( $_REQUEST, 'miyaostr' );



$marr = explode('|',$miyaostr);

$typeid = isset($marr[1])?trim($marr[1]):'';
$miyao = isset($marr[2])?trim($marr[2]):'';


if(''==$typeid || ''==$miyao) {
	exit('error');
}else {
	
	$HyDb = new HyDb();
	
	$sql_goods = "select  goods_id,shop_type,goods_name from duibaoshop.tp_goods where goods_id='".$typeid."'";
	
	$list_goods =$HyDb->get_row($sql_goods);
	
	$goodsname = $list_goods['goods_name'];
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}





?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<title>兑换信息</title>
		<link rel="stylesheet" href="../public/css/frozen.css">
		<script src="../public/js/zepto.min.js"></script>
		<script src="../public/js/frozen.js"></script>
		<script type="text/javascript">
		$("document").ready(function(){
			
			
			
		});

		
		
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
								<li class="current">商品对换信息</li>
							</ul>
							<ul class="ui-tab-content" style="width:200%;height:auto;" >
								<li>
								<!-- ----------------------------------------------------------- -->
								<section class="ui-container ui-center" style="height:auto;">
								<table class="mytable" style="margin-top:30px;margin-bottom:30px;">
									<tr>
										<td align="right" width="25%">商品名称:</td>
										<td align="left" width="75%">
										<?php echo $goodsname;?>
										</td>
									</tr>
									<tr>
										<td align="right">兑换码:</td>
										<td align="left">
											<?php echo $miyao;?>
										</td>
									</tr>
								</table>
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



















