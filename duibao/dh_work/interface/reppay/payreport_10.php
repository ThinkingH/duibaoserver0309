<?php

//10通道订单创建

//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);


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
			
			//给他会员
			if('DELIVRD'==$payarray['stat']) {
				$tmp_logstr  = '成功支付，触发会员更新判断---'.$userid."\n";
				$HyRep->hy_log_str_add($tmp_logstr);
				unset($tmp_logstr);
				
				$userid = trim($HyRep->__get('remark'));
				
				$sql_user = "select id,is_lock,vipflag,vip_endtime_one,
							vip_endtime_two,jiguangid,phone,tokenkey
							from xb_user
							where id='".$userid."'";
				$list_user = $HyRep->__get('HyDb')->get_row($sql_user);
				if(count($list_user)<=0) {
					$tmp_logstr  = '未找到用户数据---'.$userid."\n";
					$HyRep->hy_log_str_add($tmp_logstr);
					unset($tmp_logstr);
					
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
						$tmp_logstr  = '已经是会员---'.$userid.'---'.$u_vipflag.'---'.$u_vip_endtime_one."\n";
						$HyRep->hy_log_str_add($tmp_logstr);
						unset($tmp_logstr);
						
					}else {
						//更新会员操作
						$sql_update = "update xb_user set vipflag='1',vip_endtime_one='".date('Y-m-d H:i:s',(time()+30*24*60*60))."'
										where id='".$u_id."' and ((vip_endtime_one<='".date('Y-m-d H:i:s')."' and vipflag='1') or vipflag='10')";
						$tmp_logstr  = $sql_update."\n";
						$HyRep->hy_log_str_add($tmp_logstr);
						unset($tmp_logstr);
						
						$HyRep->__get('HyDb')->get_row($sql_update);
						
						
					}
					
					
					
				}
				
				
				
			}else {
				
			}
			
			
			
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
