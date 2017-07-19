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

$z_bucket = HyItems::arrayItem( $_REQUEST, 'bucket' );    //要列取的空间名称
$z_prefix = HyItems::arrayItem( $_REQUEST, 'prefix' );    //要列取文件的公共前缀
$z_marker = HyItems::arrayItem( $_REQUEST, 'marker' );    //上次列举返回的位置标记，作为本次列举的起点信息
$z_limit  = HyItems::arrayItem( $_REQUEST, 'limit' );      //本次列举的条目数

if($z_bucket=='') {
	exit('#bucket_null');
}
if(!is_numeric($z_limit)) {
	exit('#limit_error');
}





require_once('./core.php');


use Qiniu\Storage\BucketManager;

//初始化BucketManager
$bucketMgr = new BucketManager($hy_auth);

$r = $bucketMgr->listFiles($z_bucket, $z_prefix, $z_marker, $z_limit);


$r_iterms = isset($r[0])?$r[0]:null;
$r_marker = isset($r[1])?$r[1]:null;
$r_err = isset($r[2])?$r[2]:null;




if($r_err===null) {
	//执行成功
	
	$echoarr = array(
			'markes' => $r_marker,
			'iterms' => $r_iterms,
	);
	
	echo json_encode($echoarr);
	
	
	
}else {
	//上传错误
	//获取错误参数
	$r_err = HyItems::hy_object2array($r);
	$r_err_statusCode = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']) ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']:'';
	$r_err_body       = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body'])       ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body']:'';
	$r_err_error      = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error'])      ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error']:'';
	
	echo '#error_'.$r_err_statusCode.'_'.$r_err_error;
	
	
	
	
	
	
	
}




