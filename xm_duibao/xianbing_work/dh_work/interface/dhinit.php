<?php

/**
 * 流量密钥兑换生成接口
 */

//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);


//引入主文件
require_once("../lib/c.core.php");


if( empty($_REQUEST) ){
	exit('error,no parameter');
}


//本接口地址硬性限制，只允许127.0.0.1的本机ip访问
//主要接收以下几个参数
//运营商---移动1，联通2，电信3
//流量兆数---1G使用1024
//流量可使用的范围，1全国，2单省
//流量的可充值范围，1全国，2单省
//唯一单号---统一单号访问时直接返回原有可用的兑换密钥结果，避免重复获取输出


$gateway   = HyItems::arrayItem ( $_REQUEST, 'gateway' );   //运营商---移动1，联通2，电信3
$mbps      = HyItems::arrayItem ( $_REQUEST, 'mbps' );      //流量兆数---1G使用1024
$ttype     = HyItems::arrayItem ( $_REQUEST, 'ttype' );     //流量可使用的范围，1全国，2单省
//$provtype  = HyItems::arrayItem ( $_REQUEST, 'provtype' );  //流量的可充值范围，1全国，2单省
$orderno   = HyItems::arrayItem ( $_REQUEST, 'orderno' );  //唯一订单号，避免单笔订单重复充值
$client_ip = HyItems::hy_get_client_ip(); //获取用户真实访问ip
$userid    = HyItems::arrayItem ( $_REQUEST, 'userid' );  //用户的唯一标识
$youxiaoday = HyItems::arrayItem ( $_REQUEST, 'youxiaoday' );  //有效天数，默认31天


$spname      = HyItems::arrayItem ( $_REQUEST, 'name' );  //商品名称
$describe  = HyItems::arrayItem ( $_REQUEST, 'describe' );  //商品描述

// echo $client_ip.'<br>';

//本接口值允许127.0.0.1本机ip调用访问
if('127.0.0.1' != $client_ip && '114.215.222.75' != $client_ip && '120.27.34.239' != $client_ip) {
	exit('error,仅限本机ip地址访问调用');
}

if($orderno=='' || strlen($orderno)<15) {
	exit('error,订单号不能为空,且长度必须大于15位');
}
if($gateway!='1' && $gateway!='2' && $gateway!='3' ) {
	exit('error,运营商不能为空');
}
if($spname=='') {
	exit('error,商品名称不能为空');
}
if(!is_numeric($mbps) || $mbps<=0) {
	exit('error,流量兆数不能为空');
}
if(!is_numeric($youxiaoday) || $youxiaoday<=0) {
	$youxiaoday = 31;
}


//判断传递的兆数是否存在于对应允许列表中
$hy_gateway_mbps_arr = array(5,10,20,30,50,70,100,150,200,300,500,1024,2048);

if(!in_array($mbps,$hy_gateway_mbps_arr)) {
	exit('error,流量兆数只能为5,10,20,30,50,70,100,150,200,500,1024,2048');
}




$inputdataarr = array(
		'gateway'  => $gateway,
		'mbps'     => $mbps,
		'ttype'    => $ttype,
		//'provtype' => $provtype,
		'orderno'  => $orderno,
		'userid'   => $userid,
		'youxiaoday' => $youxiaoday,
		'spname' => $spname,
		'describe' => $describe,
);




$HyMiyao = new HyMiyao($inputdataarr);

$HyMiyao->miyao_create();










