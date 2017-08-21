<?php

//10通道订单创建


// 页面超时设置
set_time_limit(3600);

//引入主文件
require_once("../../lib/c.core.php");

//支付通道标识id
$payid = 10;

$paytype = 14;  //14为h5支付
$thereport_url = HY_THEBASEURL.'dh_work/interface/reppay/payreport_10.php';


//获取当前文件名称及父目录
$mname = basename(dirname(__FILE__)).'_'.basename(__FILE__,'.php');


//----------------------------------------------------------
//将接收到的数据写入日志文件

//获取IP地址
$ip = HyItems::hy_get_client_ip();

$g_input = $_SERVER["REQUEST_URI"];
$p_input = file_get_contents("php://input");



$client_ip     = HyItems::arrayItem ( $_REQUEST, 'client_ip' );
$goodsname     = HyItems::arrayItem ( $_REQUEST, 'goodsname' );  //商品名称,50个字符以内
$paymoney      = HyItems::arrayItem ( $_REQUEST, 'paymoney' );  //支付金额分
$myorderid     = HyItems::arrayItem ( $_REQUEST, 'myorderid' );  //支付时的订单号，唯一
$tcid          = HyItems::arrayItem ( $_REQUEST, 'tcid' );
$tmppaytype    = HyItems::arrayItem ( $_REQUEST, 'paytype' );
$thenotify_url = HyItems::arrayItem ( $_REQUEST, 'thenotify_url' );
$remark        = HyItems::arrayItem ( $_REQUEST, 'remark' ); //当做透传参数使用


//重置支付类型
if($tmppaytype!='' && is_numeric($tmppaytype)) {
	$paytype = $tmppaytype;
}


if(''==$client_ip) {
	exit('client_ip_null');
}
if(''==$goodsname) {
	exit('goodsname_null');
}
if(!is_numeric($paymoney)) {
	exit('paymoney_error');
}
if(''==$myorderid) {
	exit('myorderid_null');
}

if($thenotify_url=='') {
	$thenotify_url = HY_THEBASEURL.'dh_work/interface/reppay/paycallback_10.php';;
}

$payarray = array();
$payarray['mname']         = $mname;
$payarray['payid']         = $payid;
$payarray['true_ip']       = $ip;
$payarray['g_input']       = $g_input;
$payarray['p_input']       = $p_input;
$payarray['paytype']       = $paytype;
$payarray['client_ip']     = $client_ip;
$payarray['goodsname']     = $goodsname;
$payarray['paymoney']      = $paymoney;
$payarray['myorderid']     = $myorderid;
$payarray['thereport_url'] = $thereport_url;
$payarray['thenotify_url'] = $thenotify_url;
$payarray['tcid']          = $tcid;
$payarray['remark']        = $remark;



$HyPay = new HyPay($payarray);
$ret = $HyPay->hy_init();

if(false!==$ret) {
	
	//请求上家创建支付
	$createarr = array();
	$createarr['siteid']        = $HyPay->__get('cg_account');
	$createarr['paytypeid']     = $HyPay->__get('d_paytype');
	$createarr['siteorderid']   = $HyPay->__get('d_myorderid');
	$createarr['paymoney']      = $HyPay->__get('d_paymoney');
	$createarr['goodsname']     = $HyPay->__get('d_goodsname');
	$createarr['client_ip']     = $HyPay->__get('d_client_ip');
	$createarr['thereport_url'] = $thereport_url;
	$createarr['thenotify_url'] = $thenotify_url;
	$createarr['tcid']          = $tcid;
	$createarr['spare1']        = '2'; //1代收银台，2不带收银台
	//md5(siteid+siteorderid+paymoney+goodsname+thereport_url+thenotify_url+ckey)
	$createarr['md5key']        = md5($createarr['siteid'].
									$createarr['siteorderid'].
									$createarr['paymoney'].
									$createarr['goodsname'].
									$createarr['thereport_url'].
									$createarr['thenotify_url'].
									$HyPay->__get('cg_passwd')
									);
	
	
	$posturl = $HyPay->__get('cg_createurl');
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
	$HyPay->hy_log_str_add($tmp_logstr);
	unset($tmp_logstr);
	
	$jsonarr = json_decode($content,1);
	//{"returncode":"100","returnmsg":"\u6210\u529f\u83b7\u53d6\u8ba1\u8d39\u6570\u636e","myorderid":"15000110000341569858414907666348","siteorderid":"100001120170622815724","h5url":"http:\/\/pay.iuuc.net\/zfpay\/h5\/h5fee.php?h5id=201706280122295420"}
	
	$rr_returncode = isset($jsonarr['returncode'])?$jsonarr['returncode']:'';
	$rr_returnmsg  = isset($jsonarr['returnmsg'])?$jsonarr['returnmsg']:'';
	$rr_sj_orderid = isset($jsonarr['myorderid'])?$jsonarr['myorderid']:'';
	$rr_myorderid  = isset($jsonarr['siteorderid'])?$jsonarr['siteorderid']:'';
	$rr_jumpurl    = isset($jsonarr['h5url'])?$jsonarr['h5url']:'';
	
	$HyPay->__set('ystatus',$rr_returncode);
	$HyPay->__set('ymessage',$rr_returnmsg);
	$HyPay->__set('d_sj_orderid',$rr_sj_orderid);
	$HyPay->__set('d_jumpurl',$rr_jumpurl);
	
	
	if('100'==$rr_returncode) {
		//获取成功
		$HyPay->__set('payflag',20);
	}else {
		$HyPay->__set('payflag',21);
	}
	
	
	$HyPay->func_order_insert();
	
	
	echo $content;
	
	
	
	
	
}else {
	//初始错误
	
}

