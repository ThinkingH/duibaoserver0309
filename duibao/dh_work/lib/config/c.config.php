<?php


/** 
 * c.config.php 业务接口配置
 * author yu
 * 
 */


//定义微信支付日志存放目录

define('HY_CZLOGPATH',HYONEPATH.'/chongzhilog/'); //流量充值日志

define('HY_REPLOGPATH',HYONEPATH.'/reportlog/'); //状态报告日志


define('HY_PAYCREATELOG',HYONEPATH.'/paycreatelog/'); //支付订单创建日志
define('HY_PAYREPORTLOG',HYONEPATH.'/payreportlog/'); //支付订单状态报告日志
define('HY_PAYSELECTLOG',HYONEPATH.'/payselectlog/'); //支付查询日志


define('HY_THEBASEURL','http://120.27.34.239:8018/');


//客户端校验值
define('MD5KEY','527aa50704b8e9e2529e1a03e6ccd912');



//定义与调用方校验的密钥
define('DYF_CKEY','d02f34ffd8c1a6788fd49e387e189f8e');



//定义流量密钥和参数
define('DLPT_URL','http://114.215.87.192/dc_work/interface/dcinit.php'); //短流平台通讯地址
define('DLPT_CPID','180002'); //上家分配的渠道编号
define('DLPT_CKEY','6278687b361dd936165128313a3fdb95'); //上家分配的通讯密钥

