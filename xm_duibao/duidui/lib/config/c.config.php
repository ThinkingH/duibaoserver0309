<?php


/** 
 * c.config.php 业务接口配置
 * author yu
 * 
 */


//与上家对接数据日志存放路径/data/wwwroot/duibao/log

define( 'LOGPATH' , '/data/wwwroot/duibao/log/xb_log/' );
define( 'SPLOGPATH' , '/data/wwwroot/duibao/log/xbshop_log/' );

//测试用
/* define( 'REPLOGPATH' , BASE_XBWORK_TURE_PATH.'duidui_log/' ); */
//客户端校验值
define('MD5KEY','527aa50704b8e9e2529e1a03e6ccd912');

//积分与金钱转换的折扣
define('DISCOUNT','100');

//轮播图片
//define('PICPATH',TURE_PATH.'picture/');

//图片访问的链接地址
define('URLPATH','http://xbapp.xinyouxingkong.com/duidui/picture/');
//define('URLPATH','http://114.215.222.75:8001/duidui/picture/');
//apk更新后台的地址
define('URLUPDATE','http://xbapp.xinyouxingkong.com/dd_system/');

//流量的下发接口
define('XAIFALIULIANGURL','http://xbapp.xinyouxingkong.com/dh_work/interface/dhinit.php');

//define('URLUPDATE','http://114.215.222.75:8001/dd_system/');
//对内转发调用地址
define('URLSEND','http://xbapp.xinyouxingkong.com/duidui/interface/xbinit.php');
//define('URLSEND','http://114.215.222.75:8001/duidui/interface/xbinit.php');
//上传头像的存放位置
define('IMAGEPATH','/data/wwwroot/duibao/duidui/touxiang/');


