<?php

//10通道订单创建


// 页面超时设置
set_time_limit(3600);

//引入主文件
require_once("../../lib/c.core.php");

//支付通道标识id
$payid = 10;


//获取当前文件名称及父目录
$mname = basename(dirname(__FILE__)).'_'.basename(__FILE__,'.php');


//----------------------------------------------------------
//将接收到的数据写入日志文件

//获取IP地址
$ip = HyItems::hy_get_client_ip();

$g_input = $_SERVER["REQUEST_URI"];
$p_input = file_get_contents("php://input");



$siteid      = HyItems::arrayItem ( $_REQUEST, 'siteid' );
$typeid      = HyItems::arrayItem ( $_REQUEST, 'typeid' );
$stat        = HyItems::arrayItem ( $_REQUEST, 'stat' );
$paymoney    = HyItems::arrayItem ( $_REQUEST, 'paymoney' );
$sj_orderid  = HyItems::arrayItem ( $_REQUEST, 'myorderid' );
$myorderid   = HyItems::arrayItem ( $_REQUEST, 'siteorderid' );
$ystatus     = HyItems::arrayItem ( $_REQUEST, 'ystatus' );
$ymessage    = HyItems::arrayItem ( $_REQUEST, 'ymessage' );
$md5key      = HyItems::arrayItem ( $_REQUEST, 'md5key' );
$tcid        = HyItems::arrayItem ( $_REQUEST, 'tcid' );
$openid      = HyItems::arrayItem ( $_REQUEST, 'openid' );


if($siteid=='') {
	exit('siteid_null');
}
if($stat=='') {
	exit('stat_null');
}
if($myorderid=='') {
	exit('siteorderid_null');
}
if($md5key=='') {
	exit('md5key_null');
}





$payarray = array();
$payarray['mname']         = $mname;
$payarray['payid']         = $payid;
$payarray['true_ip']       = $ip;
$payarray['g_input']       = $g_input;
$payarray['p_input']       = $p_input;
$payarray['siteid']        = $siteid;
$payarray['typeid']        = $typeid;
$payarray['stat']          = $stat;
$payarray['paymoney']      = $paymoney;
$payarray['sj_orderid']    = $sj_orderid;
$payarray['myorderid']     = $myorderid;
$payarray['ystatus']       = $ystatus;
$payarray['ymessage']      = $ymessage;
$payarray['md5key']        = $md5key;
$payarray['tcid']          = $tcid;
$payarray['openid']        = $openid;





$HyRep = new HyRep($payarray);
$ret = $HyRep->hy_init();








if(false!==$ret) {
	//md5(siteid+stat+paymoney+myorderid+siteorderid+ckey)
	$md5keystring = $payarray['siteid'].
					$payarray['stat'].
					$payarray['paymoney'].
					$payarray['sj_orderid'].
					$payarray['myorderid'].
					$HyRep->__get('cg_passwd');
	$mymd5key = md5($md5keystring);
	
	if($mymd5key==$md5key) {
		//通过判断，执行更新
		
		$ret = $HyRep->func_order_update();
		
		if($ret) {
			//再此进行逻辑处理判断，对内同步执行参数处理
			
			
			
			
			
		}
		
		
		
	}else {
		//判断未通过
		//将日志追加到日志变量
		$tmp_logstr  = 'md5key---校验未通过---'.$mymd5key.'---'.$md5key.'---md5('.$md5keystring.")\n";
		$HyRep->hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
		
		
		
		
	}
	
	
	
	
}else {
	//初始错误
	
}





echo 'ok';
