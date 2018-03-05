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



$myorderid  = HyItems::arrayItem ( $_REQUEST, 'myorderid' ); //我方平台订单号

if($myorderid=='') {
	exit('myorderid_null');
}


$payarray = array();
$payarray['mname']         = $mname;
$payarray['payid']         = $payid;
$payarray['true_ip']       = $ip;
$payarray['g_input']       = $g_input;
$payarray['p_input']       = $p_input;
$payarray['myorderid']     = $myorderid;


// print_r($payarray);


$HySel = new HySel($payarray);
$ret = $HySel->hy_init();




if(false!==$ret) {
	
	//获取该订单结果
	$retsel = $HySel->func_data_check();
	
	if($retsel=='UNKNOWN') {
		//调用上家反向查询
		
		
		//请求上家创建支付
		$createarr = array();
		$createarr['siteid']      = $HySel->__get('cg_account');
		$createarr['siteorderid'] = $HySel->__get('d_myorderid');
		//md5(siteid+onlyorderid+siteorderid+ckey)
		$createarr['md5key']      = md5($createarr['siteid'].$createarr['siteorderid'].$HySel->__get('cg_passwd'));
		//echo $createarr['siteid'].$createarr['d_myorderid'].$HySel->__get('cg_passwd')."\n";
		
		$posturl = $HySel->__get('cg_selecturl');
		$postdata = HyItems::hy_urlcreate($createarr);
		
		$res = HyItems::vpost($posturl,$postdata);
		
		
		$content  = isset($res['content'])  ? $res['content'] : '';
		$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
		$run_time = isset($res['run_time']) ? $res['run_time'] : '';
		$errorno  = isset($res['errorno']) ? $res['errorno'] : '';
		
		//将日志追加到日志变量
		$tmp_logstr  = 'run_time    '.$run_time.'    httpcode    '.$httpcode.'    errorno    '.$errorno."\n".
				$posturl.'    '.$postdata.
				HyItems::hy_tospace($content)."\n";
		$HySel->hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
		
		$jsonarr = json_decode($content,1);
		//{"returncode":"100","returnmsg":"\u67e5\u8be2\u6210\u529f","status":"30","siteid":"10000","typeid":"1112","paymoney":"1","openid":"","only_orderid":"12000710000111269975766569866831","site_orderid":"1000011201707111521059709","create_datetime":"2017-07-11 15:21:06","over_datetime":"2017-07-11 15:21:17"}
// 		echo $content;
		
		$rr_returncode = isset($jsonarr['returncode'])?$jsonarr['returncode']:'';
		$rr_returnmsg  = isset($jsonarr['returnmsg'])?$jsonarr['returnmsg']:'';
		$rr_siteid     = isset($jsonarr['siteid'])?$jsonarr['siteid']:'';
		$rr_typeid     = isset($jsonarr['typeid'])?$jsonarr['typeid']:'';
		$rr_paymoney   = isset($jsonarr['paymoney'])?$jsonarr['paymoney']:'';
		$rr_status     = isset($jsonarr['status'])?$jsonarr['status']:'';
		$rr_openid     = isset($jsonarr['openid'])?$jsonarr['openid']:'';
		$rr_sj_orderid = isset($jsonarr['only_orderid'])?$jsonarr['only_orderid']:'';
		$rr_myorderid  = isset($jsonarr['siteorderid'])?$jsonarr['siteorderid']:'';
		
		
		$d_stat = 'FAIL';
		if('30'==$rr_status) {
			
			$HySel->__set('d_stat',$d_stat);
			$HySel->__set('d_ystatus',$rr_returncode);
			$HySel->__set('d_ymessage',$rr_returnmsg);
			$HySel->__set('d_ymessage',$rr_returnmsg);
			$HySel->__set('d_openid',$rr_openid);
			
			
			$narr = array();
			$narr['siteid']   = $rr_siteid;
			$narr['typeid']   = $rr_typeid;
			$narr['stat']     = $d_stat;
			$narr['paymoney'] = $rr_paymoney;
			$narr['myorderid'] = $rr_sj_orderid;
			$narr['siteorderid'] = $rr_myorderid;
			$narr['ystatus']  = $rr_returncode;
			$narr['ymessage'] = $rr_returnmsg;
			$narr['md5key'] = md5($narr['siteid'].
					$narr['stat'].
					$narr['paymoney'].
					$narr['sj_orderid'].
					$narr['myorderid'].
					$HySel->__get('cg_passwd'));
			
			$neibusendurl = HY_THEBASEURL.'reppay/shop_payreport_11.php?'.HyItems::hy_urlcreate($narr);
			
			$res = HyItems::vget($neibusendurl);
			
			$content  = isset($res['content'])  ? $res['content'] : '';
			$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
			$run_time = isset($res['run_time']) ? $res['run_time'] : '';
			$errorno  = isset($res['errorno']) ? $res['errorno'] : '';
			
			//将日志追加到日志变量
			$tmp_logstr  = 'run_time    '.$run_time.'    httpcode    '.$httpcode.'    errorno    '.$errorno."\n".
					$neibusendurl."\n".
					HyItems::hy_tospace($content)."\n";
			$HySel->hy_log_str_add($tmp_logstr);
			unset($tmp_logstr);
			
			$d_stat = 'DELIVRD';
			
		}else {
			$d_stat = 'FAIL';
			
		}

		echo $d_stat;
		
		
	}else {
		echo $retsel;
		
		
	}
	
	
}else {
	//初始错误
	echo 'SYSTEM_ERROR';
	
	
}


