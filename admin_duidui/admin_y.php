<?php
	
	$path = dirname(__file__);
	define('XMAINPATH',$path.'/');
	
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
