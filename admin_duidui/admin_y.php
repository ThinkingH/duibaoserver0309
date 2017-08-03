<?php

	$bucketarr = array(
			'img-duibaoxinyouxingkong' => 'http://osv2nvwyw.bkt.clouddn.com/',
			'duibao-basic'    => 'http://ot9nqx2pm.bkt.clouddn.com/',
			'duibao-business' => 'http://ot9nz4ril.bkt.clouddn.com/',
			'duibao-find'     => 'http://ot9ny2h5q.bkt.clouddn.com/',
			'duibao-shop'     => 'http://ot9nwdfs7.bkt.clouddn.com/',
			'duibao-user'     => 'http://ot9n52hqq.bkt.clouddn.com/',
			
	);
	
	$bucketstr = serialize($bucketarr);
	define('BUCKETSTR',$bucketstr);
	
	/* $arr = unserialize(BUCKETSTR);
	echo $arr['duibao-business']; */
	
	
	$path = dirname(__file__);
	//echo $path;//D:\www\www2\admin_duidui﻿
	
	define('XMAINPATH',$path.'/');
	
	$picpath = dirname(dirname(__file__));
	define('PICPATH',$picpath.'/');
	//echo dirname(__file__);
	
	$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/dd_system';
	define('XMAINURL',$url);
	
	//自定义session前缀，主要用于系统区分
	define('HYSESSQZ','dd_');
	
	//定义平台名称
	define('HY_SYSTEM_NAME','兑宝后台系统');
	
	//平台名称后是否显示IP---true/false
	define('HY_SHOW_IP',true);
	
	//定义项目名
	define('APP_NAME','Index');
	
	//定义项目所在路径
	define('APP_PATH','./Index/');
	
	//开启调试模式，建议新手开启
	define('APP_DEBUG',true);
	
	//apk存放地址链接http://120.27.34.239:8009/dd_system/Public/Uploads/apk/
	define('URL_APK','http://xbapp.xinyouxingkong.com/dd_system');
	
	
	
	//加载入口文件
	require './ThinkPHP/ThinkPHP.php';
	
	


?>
