<?php

/**
 * 馅饼接口入口
 */

//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/dd_system';
define('XMAINURL',$url);

//引入主文件
require_once("../lib/c.core.php");


//获取当前文件名称
$mname = basename(__FILE__,'.php');



if( empty($_REQUEST) ){
	exit('error,no parameter');
}

$xb_get   = $_SERVER["REQUEST_URI"]; //当前目录地址及get参数
$xb_post  = file_get_contents("php://input"); //原始post数据

$log_str = "\n".'REP_BEGIN-----------------------------------------------------------'."\n".
		date('Y-m-d H:i:s').'    '.$mname."\n".
		'get:    '.$xb_get."\n".
		'post:    '.$xb_post."\n";


//文件的路径
$filepath = SPLOGPATH;

//文件的名称
$filename = date('Y-m-d').'.log';

//将数据写入日志文件
HyItems::hy_writelog($filepath, $filename, $log_str); 



$thetype         = HyItems::arrayItem ( $_REQUEST, 'thetype' );    //操作类型编号，如101,102,103
$nowtime         = HyItems::arrayItem ( $_REQUEST, 'nowtime' );    //时间戳，预留字段，用于后期校验增加安全性使用
$md5key          = HyItems::arrayItem ( $_REQUEST, 'md5key' );     //MD5校验值
$usertype        = HyItems::arrayItem ( $_REQUEST, 'usertype' );   //用户类型，1为正常用户，2为匿名用户，其他的也归为匿名用户 初始化访问时填3匿名用户
$userid          = HyItems::arrayItem ( $_REQUEST, 'userid' );     //用户在平台的标识编号，平台全部以用户的标识编号作为用户的区分初始化访问时置空
$userkey         = HyItems::arrayItem ( $_REQUEST, 'userkey' );    //用户通讯的校验密钥 初始化访问时置空


$typeid         = HyItems::arrayItem ( $_REQUEST, 'typeid' );  //商品类型编号

$count           = HyItems::arrayItem ( $_REQUEST, 'count' );            //每页的条数，数值介于1到20之间
$page            = HyItems::arrayItem ( $_REQUEST, 'page' );            //数据请求对应页数



//初步判断传递的参数是否正确
if($thetype=='' || $thetype<101) {
	
	$echoarr = array();
	$echoarr['returncode']='error';
	$echoarr['returnmsg'] = '操作类型编号错误';
				
	echo json_encode($echoarr);
	return false;
}

if($usertype=='') {
	
	$echoarr = array();
	$echoarr['returncode']='error';
	$echoarr['returnmsg'] = '用户类型不能为空';
	echo json_encode($echoarr);
	return false;
}
if($md5key==''||strlen($md5key)!='32') {
	$echoarr = array();
	$echoarr['returncode']='error';
	$echoarr['returnmsg'] = 'md5key客户端校验错误';
	echo json_encode($echoarr);
	return false;
}


$inputdataarr = array(
		'thetype'       => $thetype,
		'nowtime'       => $nowtime,
		'md5key'        => $md5key,
		'usertype'      => $usertype,
		'userid'        => $userid,
		'userkey'       => $userkey,
		'count'           => $count,
		'page'            => $page,
		'typeid'          => $typeid,
		
);


$HyXbCon = new HyXbCon($inputdataarr);

$HyXbCon->controller();










