<?php


/** 
 * c.config.php 业务接口配置
 */

//支付接口参数配置
define('HY_PAYCREATELOG',TURE_PATH.'/paylog/paycreatelog/'); //支付订单创建日志
define('HY_PAYREPORTLOG',TURE_PATH.'/paylog/payreportlog/'); //支付订单状态报告日志
define('HY_PAYSELECTLOG',TURE_PATH.'/paylog/payselectlog/'); //支付订单状态报告日志

define('HY_THEBASEURL','http://xbapp.xinyouxingkong.com/duibaoinit/');

//客户端校验值
define('MD5KEY','527aa50704b8e9e2529e1a03e6ccd912');


define('ECHOSTRLOGFLAG',true);  //true/false输出日志记录写入标识

//日志存放根目录
define( 'LOGPATH' , TURE_PATH.'log/' );

//临时图片存储路径
define( 'TMPPICPATH' , TURE_PATH.'tmppicfile/' );

//sql查询数据缓存数据的存储
define('TMPSQLPATH',TURE_PATH.'tmpsqlfile/');

//缓存更新时间,每5分钟更新时间
define('TMPSQLTIME',200);

//版本号
$cilentversionkeyarr = array(
		'300' => 'fd5112f036eea77f23bac0bbbadbe592',
);
$cilentversionjson = json_encode($cilentversionkeyarr);

//客户端校验值JSON字符串
define('CILENTVERSIONJSON',$cilentversionjson);


//短信验证码下发校验key
define('MSGSENDKEY','e0f8978c0677a01aeac12cc90eed0949');

//网站根目录定义
define('BASEURL','http://127.0.0.1:8002/');

//本地七牛的访问链接
//define('QINIUURL','http://127.0.0.1:8001/hyqiniu/init/');
//define('QINIUURL','http://127.0.0.1/hyqiniu/init/');
//正式的七牛访问链接,新兑宝接口和旧兑宝接口，七牛上传的地址不一样
define('QINIUURL','http://127.0.0.1:8001/hyqiniunew/init/');

$bucketarr = array(
		//本地不在存储图片，上传完毕后直接删除，严格要求不使用的图片必须删除(调用封装的七牛删除接口)
		'duibao-basic'    => 'http://oymkhn027.bkt.clouddn.com/',  //基础公共图片存放，公共图片图标，循环展示图片，默认图片，抽奖小页面等公共静态资源图片
		'duibao-user'     => 'http://oyojv7be2.bkt.clouddn.com/',  //用户图片存放，头像，用户其他数据
		'duibao-business' => 'http://oyojteo81.bkt.clouddn.com/',  //商家图片存放，如商家营业执照，认证扫描图片，合同等
		'duibao-find'     => 'http://oyoj423p4.bkt.clouddn.com/',  //发现图片存放，用户发布的发现数据内容图片
		'duibao-shop'     => 'http://oyojvph72.bkt.clouddn.com/',  //商城图片存放，各种商品图片

);


$bucketstr = serialize($bucketarr);
define('BUCKETSTR',$bucketstr);

$bucketstr = json_encode($bucketarr);
define('QINIUBUCKETSTR',$bucketstr);


//积分与金钱转换的折扣
define('DISCOUNT','100');

// 流量的下发接口
define('XAIFALIULIANGURL','http://xbapp.xinyouxingkong.com/dh_work/interface/dhinit.php');

//对内转发调用地址
define('URLSEND','http://xbapp.xinyouxingkong.com/duidui/interface/xbinit.php');


//图片访问的链接地址
define('URLPATH','http://xbapp.xinyouxingkong.com/duidui/picture/');











// //正式服务器上的空间存储
// $bucketarr = array(
		//  'duibao-basic'    => 'http://ot9nqx2pm.bkt.clouddn.com/',
		//  'duibao-business' => 'http://ot9nz4ril.bkt.clouddn.com/',
		//  'duibao-find'     => 'http://ot9ny2h5q.bkt.clouddn.com/',
		//  'duibao-shop'     => 'http://ot9nwdfs7.bkt.clouddn.com/',
		//  'duibao-user'     => 'http://ot9n52hqq.bkt.clouddn.com/',

// );

//测试版空间存储
// $bucketarr = array(
// 		'duibao-basic'    => 'http://ou040h90h.bkt.clouddn.com/',
// 		'duibao-business' => 'http://ou04tbzs7.bkt.clouddn.com/',
// 		'duibao-find'     => 'http://ou04n5703.bkt.clouddn.com/',
// 		'duibao-shop'     => 'http://ou04vpu93.bkt.clouddn.com/',
// 		'duibao-user'     => 'http://ou04zj06v.bkt.clouddn.com/',
	
// );



// //apk更新后台的地址
// define('URLUPDATE','http://xbapp.xinyouxingkong.com/dd_system/');

// //上传头像的存放位置
// define('IMAGEPATH','/data/wwwroot/duibao/duidui/picture/touxiang/');

// //广告图片的保存
// define('ADIMAGEPATH','/data/wwwroot/duibao/duidui/picture/advertisement/');


