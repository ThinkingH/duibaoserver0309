<?php

//正式空间地址
	$bucketarr = array(
			//本地不在存储图片，上传完毕后直接删除，严格要求不使用的图片必须删除(调用封装的七牛删除接口)
			'duibao-basic'    => 'http://oymkhn027.bkt.clouddn.com/',  //基础公共图片存放，公共图片图标，循环展示图片，默认图片，抽奖小页面等公共静态资源图片
			'duibao-user'     => 'http://oyojv7be2.bkt.clouddn.com/',  //用户图片存放，头像，用户其他数据
			'duibao-business' => 'http://oyojteo81.bkt.clouddn.com/',  //商家图片存放，如商家营业执照，认证扫描图片，合同等
			'duibao-find'     => 'http://oyoj423p4.bkt.clouddn.com/',  //发现图片存放，用户发布的发现数据内容图片
			'duibao-shop'     => 'http://oyojvph72.bkt.clouddn.com/',  //商城图片存放，各种商品图片
	
	);
	
// 	//测试版空间存储
// 	$bucketarr = array(
// 			'duibao-basic'    => 'http://ou040h90h.bkt.clouddn.com/',
// 			'duibao-business' => 'http://ou04tbzs7.bkt.clouddn.com/',
// 			'duibao-find'     => 'http://ou04n5703.bkt.clouddn.com/',
// 			'duibao-shop'     => 'http://ou04vpu93.bkt.clouddn.com/',
// 			'duibao-user'     => 'http://ou04zj06v.bkt.clouddn.com/',
		
// 	);
	
	$bucketstr = json_encode($bucketarr);
	define('QINIUBUCKETSTR',$bucketstr);
	
	//本地七牛的访问链接
	//define('QINIUURL','http://127.0.0.1:8001/hyqiniu/init/');
	//define('QINIUURL','http://127.0.0.1/hyqiniu/init/');
	define('QINIUURL','http://127.0.0.1:8001/hyqiniunew/init/');
	
	$path = dirname(__file__);
	define('XMAINPATH',$path.'/');
	
	$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/dd_system';
	define('XMAINURL',$url);
	
	//自定义session前缀，主要用于系统区分
	define('HYSESSQZ','duidui_');
	
	//定义平台名称
	define('HY_SYSTEM_NAME','兑宝商户后台系统');
	
	//平台名称后是否显示IP---true/false
	define('HY_SHOW_IP',true);
	
	//定义项目名
	define('APP_NAME','Index');
	
	//定义项目所在路径
	define('APP_PATH','./Index/');
	
	//开启调试模式，建议新手开启
	define('APP_DEBUG',true);
	
	//apk存放地址链接http://120.27.34.239:8009/dd_system/Public/Uploads/apk/http://120.27.34.239:8018/admin_shop/admin_y.php
	//define('URL_APK','http://120.27.34.239:8018/admin_shop');
	define('URL_APK','http://xbapp.xinyouxingkong.com/admin_shop');
	
	
	//加载入口文件
	require './ThinkPHP/ThinkPHP.php';
	
	


?>
