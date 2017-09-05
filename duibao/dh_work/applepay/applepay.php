<?php

/**
 * 苹果支付接口
 */

//断开连接后继续执行，参数用法详见手册
// ignore_user_abort(true);


//引入主文件
require_once("../lib/c.core.php");


if( empty($_REQUEST) ){
	exit('error,no parameter');
}

//获取当前文件名称
$mname = basename(__FILE__,'.php');
$g_input = $_SERVER["REQUEST_URI"]; //获取原始get数据和访问路径
$p_input = file_get_contents("php://input"); //获取原始post数据

//定义日志数据存放变量
$hy_logstr  = "\n".'APPLEPAYLOG_BEGIN-----------------------------------------------------------'."\n".
		date('Y-m-d H:i:s').'    '.$mname."\n".
		'get:    '.$g_input."\n".
		'post:    '.HyItems::hy_tospace($p_input)."\n";

$hy_logpath = HY_REPLOGPATH.date('Y-m').'/'.$mname.'/';
$hy_logname = $mname.'_'.date('Y-m-d').'.log';




$apple_receipt = HyItems::arrayItem ( $_REQUEST, 'apple_receipt' );  //苹果内购的验证收据,由客户端传过来
$userid        = HyItems::arrayItem ( $_REQUEST, 'userid' );  //用户id

//这里内容是需要base64加密
$jsonData = array(
		'receipt-data' => $apple_receipt,
);
$jsonData = json_encode($jsonData);
// $hy_logstr .= $jsonData."\n";


// $appurl = 'https://buy.itunes.apple.com/verifyReceipt';  //正式验证地址
$appurl = 'https://sandbox.itunes.apple.com/verifyReceipt'; //测试验证地址

$hy_logstr .= $appurl."\n";



$res = HyItems::vpost($appurl,$jsonData);


$content = isset($res['content'])?$res['content']:'';

echo $content;

$jsonarr = json_decode($content,1);

$status = isset($jsonarr['status'])?$jsonarr['status']:'';
$receipt_product_id = isset($jsonarr['receipt']['product_id'])?$jsonarr['receipt']['product_id']:'';

if('0'===(string)$status && 'com.cn.xyxk.duibao002'==$receipt_product_id) {
	
	$hy_logstr .= '触发会员更新操作---'.$receipt_product_id."\n";
	
	
	$HyDb = new HyDb();
	
	$sql_user = "select id,is_lock,vipflag,vip_endtime_one,
				vip_endtime_two,jiguangid,phone,tokenkey
				from xb_user
				where id='".$userid."'";
	$list_user = $HyDb->get_row($sql_user);
	if(count($list_user)<=0) {
		$hy_logstr .= '未找到用户数据---'.$userid."\n";
			
	}else {
			
		$u_id              = $list_user ['id'];
		$u_is_lock         = $list_user ['is_lock'];
		$u_vipflag         = $list_user ['vipflag'];
		$u_vip_endtime_one = $list_user ['vip_endtime_one'];
		// $u_vip_endtime_two = $list_user['vip_endtime_two'];
		$u_jiguangid       = $list_user ['jiguangid'];
		$u_phone           = $list_user ['phone'];
		$u_tokenkey        = $list_user ['tokenkey'];
			
		if($u_vipflag==1 && strtotime($u_vip_endtime_one)>time()) {
			//已经是会员，不在进行会员更新操作
			$hy_logstr .= '已经是会员---'.$userid.'---'.$u_vipflag.'---'.$u_vip_endtime_one."\n";
	
		}else {
			//更新会员操作
			$sql_update = "update xb_user set vipflag='1',vip_endtime_one='".date('Y-m-d H:i:s',(time()+30*24*60*60))."'
										where id='".$u_id."' and ((vip_endtime_one<='".date('Y-m-d H:i:s')."' and vipflag='1') or vipflag='10')";
			$hy_logstr .= $sql_update."\n";
	
			$HyDb->execute($sql_update);
	
	
		}
			
			
			
	}
	
	
	
	
	
	
}



// print_r($content);
$hy_logstr .= $content."\n";


//将日志数据写入文件
HyItems::hy_writelog($hy_logpath, $hy_logname, $hy_logstr);








