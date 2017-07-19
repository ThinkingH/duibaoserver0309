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

$z_delbucket = HyItems::arrayItem( $_REQUEST, 'delbucket' );  //要删除文件所在的空间名称
$z_delname   = HyItems::arrayItem( $_REQUEST, 'delname' );      //要删除文件的名称

if($z_delbucket=='') {
	exit('#delbucket_null');
}
if($z_delname=='') {
	exit('#delname_null');
}




require_once('./core.php');


use Qiniu\Storage\BucketManager;

//初始化BucketManager
$bucketMgr = new BucketManager($hy_auth);

$r = $bucketMgr->delete($z_delbucket,$z_delname);

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




