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

$z_bucket   = HyItems::arrayItem( $_REQUEST, 'bucket' );    //要上传的空间名称
$z_filepath = HyItems::arrayItem( $_REQUEST, 'filepath' );  //要上传文件的本地路径--需要绝对路径
$z_savename = HyItems::arrayItem( $_REQUEST, 'savename' );  //上传到七牛后保存的文件名

if($z_bucket=='') {
	exit('#bucket_null');
}
if($z_filepath=='') {
	exit('#filepath_null');
}
if($z_savename=='') {
	exit('#savename_null');
}

//判断该文件是否存在
if(!file_exists($z_filepath)) {
	exit('#filepath_error');
}




require_once('./core.php');



// 引入上传类
use Qiniu\Storage\UploadManager;

// 要上传的空间
$bucket = $z_bucket;

// 生成上传 Token
$token = $hy_auth->uploadToken($bucket);

// 要上传文件的本地路径
$filePath = $z_filepath;

// 上传到七牛后保存的文件名
$key = $z_savename;

// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
$r = $uploadMgr->putFile($token, $key, $filePath);

$r_ret = isset($r[0])?$r[0]:null;
$r_err = isset($r[1])?$r[1]:null;


if($r_err!==null) {
	//上传错误
	//获取错误参数
	$r_err = HyItems::hy_object2array($r_err);
	$r_err_statusCode = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']) ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['statusCode']:'';
	$r_err_body       = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body'])       ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['body']:'';
	$r_err_error      = isset($r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error'])      ? $r_err[''."\0".'Qiniu\\Http\\Error'."\0".'response']['error']:'';
	
	echo '#error_'.$r_err_statusCode.'_'.$r_err_error;
	
	
}else {
	//数据上传正常
	
	echo json_encode($r_ret);
	
	
	
}






