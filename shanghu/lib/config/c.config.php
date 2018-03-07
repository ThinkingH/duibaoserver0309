<?php


/** 
 * c.config.php 业务接口配置
 * author yu
 * 
 */


//定义微信支付日志存放目录

define('HY_CZLOGPATH',HYONEPATH.'/chongzhilog/'); //流量充值日志

define('HY_REPLOGPATH',HYONEPATH.'/reportlog/'); //状态报告日志


//定义与调用方校验的密钥
define('DYF_CKEY','d02f34ffd8c1a6788fd49e387e189f8e');

//支付入口地址
define('SITEPAYURL','http://xbapp.xinyouxingkong.com/dh_work/interface/pay/shanghuruzhu1.php');

//定义流量密钥和参数
define('DLPT_URL','http://114.215.87.192/dc_work/interface/dcinit.php'); //短流平台通讯地址
define('DLPT_CPID','180002'); //上家分配的渠道编号
define('DLPT_CKEY','6278687b361dd936165128313a3fdb95'); //上家分配的通讯密钥


