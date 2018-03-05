<?php

// 会员购买

// 页面超时设置
set_time_limit(600);

// 引入主文件
require_once("../../lib/c.core.php");


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width,initial-scale=1,user-scalable=0">
<script src="https://cdn.bootcss.com/layer/3.0.3/mobile/layer.js"></script>
</head>
<body></body>


<?php

$userid   = HyItems::arrayItem ( $_REQUEST, 'userid' );
$checkkey = HyItems::arrayItem ( $_REQUEST, 'checkkey' );

// $userid= '1';
// $checkkey='454bc848813158b386a3c6e361f6f295';

// echo $userid;

if (! is_numeric($userid )) {
	echo "
		<script>
		layer.open({
			content: '参数不正确，创建支付失败'
			,btn: '我知道了'
			,yes:function(){
				try{
					window.postMessage(JSON.stringify({
							'type': 'close'
						}));	
				}catch(e){
					  layer.closeAll();
				}
			}
		});
		</script>";
	exit ('error,参数不正确' );
}


$HyDb = new HyDb ();

$sql_user = "select id,is_lock,vipflag,vip_endtime_one,vip_endtime_two,jiguangid,phone,tokenkey
			from xb_user
			where id='".$userid."'";
$list_user = $HyDb->get_row ( $sql_user );

if (count ( $list_user ) <= 0) {
	echo "<script>
		layer.open({
			content: '未找到该用户数据，无法创建支付订单'
			,btn: '我知道了'
			,yes:function(){
				try{
					window.postMessage(JSON.stringify({
							'type': 'close'
						}));
				}catch(e){
					  layer.closeAll();
				}
			}
		});
		</script>";
	exit ('error,未找到该用户数据，无法创建支付订单' );
	
} else {
	
	$u_id              = $list_user ['id'];
	$u_is_lock         = $list_user ['is_lock'];
	$u_vipflag         = $list_user ['vipflag'];
	$u_vip_endtime_one = $list_user ['vip_endtime_one'];
	// $u_vip_endtime_two = $list_user['vip_endtime_two'];
	$u_jiguangid       = $list_user ['jiguangid'];
	$u_phone           = $list_user ['phone'];
	$u_tokenkey        = $list_user ['tokenkey'];
	
	
	//校验用户秘钥是否正确
	//md5加密
	$newmd5key = md5($u_id.$u_tokenkey.MD5KEY);
	//echo $u_id.'_'.$u_tokenkey.'_'.MD5KEY;
	if($checkkey!=$newmd5key) {
		echo "<script>
		layer.open({
			content: '通讯数据校验错误，无法正常创建支付订单'
			,btn: '我知道了'
			,yes:function(){
				try{
					window.postMessage(JSON.stringify({
							'type': 'close'
						}));
				}catch(e){
					  layer.closeAll();
				}
			}
		});
		</script>";
		exit ('error,通讯数据校验错误，无法正常创建支付订单');
		
	}
	
	if ($u_vipflag == 10) {
		// 通过
	} else if ($u_vipflag == 1 && strtotime ( $u_vip_endtime_one ) > time ()) {
		// 通过
	} else {
		echo "<script>
		layer.open({
			content: '您已经是vip会员用户，请过期后再来购买'
			,btn: '我知道了'
			,yes:function(){
				try{
					window.postMessage(JSON.stringify({
							'type': 'close'
						}));
				}catch(e){
					  layer.closeAll();
				}
			}
		});
		</script>";
		//echo '111111111111111111111111111';
		
		exit ('error,您已经是vip会员用户，请过期后再来购买' );
	}
	
	
	$ggarr = array ();
	$ggarr ['client_ip'] = '127.0.0.1';
	$ggarr ['goodsname'] = '兑宝VIP会员';
	$ggarr ['paymoney'] = '1800';
	$ggarr ['myorderid'] = $userid . 'vip' . date ( 'YmdHis' ) . mt_rand ( 100, 999 );
	$ggarr ['thenotify_url'] = HY_THEBASEURL.'h5pay/reppay/paycallback_10.php?myorderid='.urlencode($ggarr['myorderid']);
	$ggarr ['remark'] = $userid;
	$geturl = HY_THEBASEURL.'h5pay/pay/paycreate_10.php?' . HyItems::hy_urlcreate ( $ggarr );
	
	
	$res = HyItems::vget ( $geturl );
	$content = isset ( $res ['content'] ) ? $res ['content'] : '';
	
	$jsonarr = json_decode ( $content, 1 );
	
	$rr_returncode = isset ( $jsonarr ['returncode'] ) ? $jsonarr ['returncode'] : '';
	$rr_returnmsg = isset ( $jsonarr ['returnmsg'] ) ? $jsonarr ['returnmsg'] : '';
	$rr_sj_orderid = isset ( $jsonarr ['myorderid'] ) ? $jsonarr ['myorderid'] : '';
	$rr_myorderid = isset ( $jsonarr ['siteorderid'] ) ? $jsonarr ['siteorderid'] : '';
	$rr_jumpurl = isset ( $jsonarr ['h5url'] ) ? $jsonarr ['h5url'] : '';
	
// 	print_r($content);
	
	if ($rr_returncode == 100 && $rr_jumpurl != '') {
		echo "<script>window.location.href='".$rr_jumpurl."';</script>";
		exit;
		
	} else {
		echo "
		<script>
		layer.open({
			content: '订单创建失败，请稍后重试11'
			,btn: '我知道了'
			,yes:function(){
				try{
					window.postMessage(JSON.stringify({
							'type': 'close'
						}));
				}catch(e){
					  layer.closeAll();
				}
			}
		});
		</script>";
		exit ('error,订单创建失败，请稍后重试11' );
		
	}
}




