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

$userid   = HyItems::arrayItem ( $_REQUEST, 'userid' );//用户id
$checkkey = HyItems::arrayItem ( $_REQUEST, 'checkkey' );//校验参数
$productionnum = HyItems::arrayItem ( $_REQUEST, 'pronum' );//商品数量
$productid     = HyItems::arrayItem ( $_REQUEST, 'proid' );//商品id

/*  $userid='1';
$checkkey='454bc848813158b386a3c6e361f6f295';
$productionnum='1';
$productid='4';  */

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
	
	//商品价格 数量 名称
	$production_sql  = "select * from shop_product where id='".$productid."'";
	$production_list = $HyDb->get_row ( $production_sql ); 
	
	if(count($production_list)<=0){
		echo "<script>
		layer.open({
			content: '该商品不存在，无法正常创建支付订单'
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
		exit ('error,该商品不存在，无法正常创建支付订单' );
	}
	
	
	
	$goodsname = $production_list['name'];
	$client_ip = $_SERVER["REMOTE_ADDR"];
	$paymoney  = $productionnum*$production_list['price'];
	$myorderid = $userid.date('YmdHis').mt_rand(1000, 9999);//支付订单号
	$productorder = 'D'.$userid.date('YmdHis').mt_rand(100, 999);//商品订单号
	
	
	$ggarr = array ();
	$ggarr ['client_ip'] = $client_ip;
	$ggarr ['goodsname'] = $goodsname;
	$ggarr ['paymoney'] = $paymoney;
	$ggarr ['myorderid'] = $myorderid;
	$ggarr ['thenotify_url'] = HY_THEBASEURL.'dh_work/interface/reppay/shop_paycallback_11.php?myorderid='.urlencode($ggarr['myorderid']);
	$ggarr ['remark'] = $productorder;
 	$geturl = HY_THEBASEURL.'dh_work/interface/pay/production_paycreate_11.php?' . HyItems::hy_urlcreate ( $ggarr );
	
	
	$res = HyItems::vget ( $geturl );
	$content = isset ( $res ['content'] ) ? $res ['content'] : '';
	
	$jsonarr = json_decode ( $content, 1 );
	
	$rr_returncode = isset ( $jsonarr ['returncode'] ) ? $jsonarr ['returncode'] : '';
	$rr_returnmsg = isset ( $jsonarr ['returnmsg'] ) ? $jsonarr ['returnmsg'] : '';
	$rr_sj_orderid = isset ( $jsonarr ['myorderid'] ) ? $jsonarr ['myorderid'] : '';
	$rr_myorderid = isset ( $jsonarr ['siteorderid'] ) ? $jsonarr ['siteorderid'] : '';
	$rr_jumpurl = isset ( $jsonarr ['h5url'] ) ? $jsonarr ['h5url'] : '';
	
	// print_r($content);
	
	if ($rr_returncode == 100 && $rr_jumpurl != '') {
		
		
		//插入订单
		//查找用户的地址
		$sql_address = "select id from xb_user_address where userid='".$userid."' and is_default ='9' ";
		$list_address = $HyDb->get_row($sql_address);;
		
		$product_sql  = "select * from shop_product where id='".$productid."' ";
		$product_list = $HyDb->get_row($product_sql);
		
		//总金额
		$totalmoney = $product_list['price']*$productionnum;
		
		$sql_shopinsert = "insert into shop_userbuy (userid,typeid,childtypeid,status,
							mtype,name,productnum,orderno,price,productid,
							pingjia,order_createtime,address_id,zhifu_order )
						values ('".$userid."','".$product_list['typeid']."','".$product_list['typeidchild']."','20',
								'".$product_list['xushi_type']."','".$product_list['name']."','".$productionnum."','".$productorder."',
								'".$totalmoney."','".$productid."','9','".date('Y-m-d H:i:s')."','".$list_address['id']."','".$myorderid."' )";
		//echo $sql_shopinsert;exit;
		$list_shopinsert = $HyDb->execute($sql_shopinsert);
		
		echo "<script>window.location.href='".$rr_jumpurl."';</script>";
		
		exit;
	} else {
		echo "
		<script>
		layer.open({
			content: '订单创建失败，请稍后重试'
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
		exit ('error,订单创建失败，请稍后重试' );
		
	}
	
	
	
}




