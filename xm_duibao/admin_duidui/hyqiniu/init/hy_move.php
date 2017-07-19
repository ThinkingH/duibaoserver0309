<?php

require_once('./HyItems.php');


//----------------------------------------------------------
//获取IP地址
$ip = HyItems::hy_get_client_ip();
//IP鉴权数组存储
$ipjianquanarr = unserialize(IPARRSTR);
if(!in_array($ip, $ipjianquanarr)) {
	exit('#ip_error');
}

$z_frombucket = HyItems::arrayItem( $_REQUEST, 'frombucket' );  //要移动文件所在的空间名称
$z_tobucket   = HyItems::arrayItem( $_REQUEST, 'tobucket' );    //要移动到的空间名称
$z_fromname   = HyItems::arrayItem( $_REQUEST, 'fromname' );    //原文件名称
$z_toname     = HyItems::arrayItem( $_REQUEST, 'toname' );      //新的文件名称

if($z_frombucket=='') {
	exit('#frombucket_null');
}
if($z_tobucket=='') {
	exit('#tobucket_null');
}
if($z_fromname=='') {
	exit('#fromname_null');
}
if($z_toname=='') {
	exit('#toname_null');
}




require_once('./core.php');


use Qiniu\Storage\BucketManager;

//初始化BucketManager
$bucketMgr = new BucketManager($hy_auth);

$r = $bucketMgr->move($z_frombucket, $z_fromname, $z_tobucket, $z_toname);

if($r===null) {
	//执行成功
	echo 'ok';
	
	
}else {
	//上传错误
	//获取错误参数
	$r_err = HyItems::hy_object2array($r);
	$r_err_statusCode = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']) ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']:'';
	$r_err_body       = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body'])       ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body']:'';
	$r_err_error      = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error'])      ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error']:'';
	
	echo '#error_'.$r_err_statusCode.'_'.$r_err_error;
	
	
	
	
	
	
	
}




