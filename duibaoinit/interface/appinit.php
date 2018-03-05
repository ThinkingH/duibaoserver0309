<?php

/**
 * 兑宝接口入口
 */

//断开连接后继续执行，参数用法详见手册
//ignore_user_abort(true);

//错误警告
error_reporting(E_ALL);
ini_set('display_errors', '1');


//引入主文件
require_once("../lib/c.core.php");


//获取当前文件名称
$mname = basename(__FILE__,'.php');

if( empty($_REQUEST) ){
	exit('error,no parameter');
}

$true_ip = HyItems::hy_get_client_ip();
$getbaseurl   = $_SERVER["REQUEST_URI"]; //当前目录地址及get参数
$postbaseurl  = file_get_contents("php://input"); //原始post数据

$log_str = "\n".'duibaolog-------------------------------------------------------'."\n".
		date('Y-m-d H:i:s').'    '.$mname."\n".'    '.$getbaseurl."\n".$postbaseurl."\n";


//文件的路径
$filepath = LOGPATH.date('Y_m').'/';

//文件的名称
$filename = date('Y_m_d').'_'.$mname;


$inputdataarr = array();


$inputdataarr['version']         = trim(HyItems::arrayItem ( $_REQUEST, 'version' ));    //接口版本,后台接口版本，与app无关,每个version版本均对应一个版本秘钥,100~999
$inputdataarr['system']          = trim(HyItems::arrayItem ( $_REQUEST, 'system' ));    //操作系统，IOS/ANDROID/PC，非IOS、PC全部默认为ANDROID
$inputdataarr['sysversion']      = trim(HyItems::arrayItem ( $_REQUEST, 'sysversion' ));    ////APP系统版本，例100
$inputdataarr['thetype']         = trim(HyItems::arrayItem ( $_REQUEST, 'thetype' ));    //操作类型编号，4位长度，第一位为内部层级版本号，后三位自定义
$inputdataarr['nowtime']         = trim(HyItems::arrayItem ( $_REQUEST, 'nowtime' ));    //时间戳，预留字段，用于后期校验增加安全性使用
$inputdataarr['md5key']          = trim(HyItems::arrayItem ( $_REQUEST, 'md5key' ));     //MD5校验值采用md5(version+system+sysversion+thetype+nowtime+ckey)//通讯层校验

$inputdataarr['usertype']        = trim(HyItems::arrayItem ( $_REQUEST, 'usertype' ));   //用户类型，1为正常用户，2-临时用户 3-其他用户
$inputdataarr['userid']          = trim(HyItems::arrayItem ( $_REQUEST, 'userid' ));     //用户在平台的标识编号
$inputdataarr['userkey']         = trim(HyItems::arrayItem ( $_REQUEST, 'userkey' ));    //用户通讯的校验密钥

$inputdataarr['keyong_jifen']          = trim(HyItems::arrayItem ( $_REQUEST, 'keyong_jifen' ));     //用户可用积分
$inputdataarr['dongjie_jifen']         = trim(HyItems::arrayItem ( $_REQUEST, 'dongjie_jifen' ));    //用户冻结积分


$inputdataarr['phone']           = trim(HyItems::arrayItem ( $_REQUEST, 'phone' ));       //手机号必须为11位
$inputdataarr['vcode']           = trim(HyItems::arrayItem ( $_REQUEST, 'vcode' ));       //手机下发验证码

$inputdataarr['pagesize']        = trim(HyItems::arrayItem ( $_REQUEST, 'pagesize' ));            //每页的条数，数值介于1到200之间
$inputdataarr['page']            = trim(HyItems::arrayItem ( $_REQUEST, 'page' ));            //数据请求对应页数

$inputdataarr['yijian']          = trim(HyItems::arrayItem ( $_REQUEST, 'yijian' ));       //用户反馈的意见内容
$inputdataarr['sex']             = trim(HyItems::arrayItem ( $_REQUEST, 'sex' ));           //性别，1男，2女，3保密
$inputdataarr['birthday']        = trim(HyItems::arrayItem ( $_REQUEST, 'birthday' ));      //生日
$inputdataarr['nickname']        = trim(HyItems::arrayItem ( $_REQUEST, 'nickname' ));       //用户昵称
$inputdataarr['userlevel']       = HyItems::arrayItem ( $_REQUEST, 'userlevel' );            // 用户等级，预留，默认1级
$inputdataarr['describes']       = trim(HyItems::arrayItem ( $_REQUEST, 'describes' ));       //用户描述介绍
$inputdataarr['headimgurl']      = trim(HyItems::arrayItem ( $_REQUEST, 'headimgurl' ));       //headimgurl


$inputdataarr['houzhui']        = trim(HyItems::arrayItem ( $_REQUEST, 'houzhui' ));             //图片的后缀
$inputdataarr['imgdata']        = trim(HyItems::arrayItem ( $_REQUEST, 'imgdata' ));             //图片

$inputdataarr['jiguangid']        = trim(HyItems::arrayItem ( $_REQUEST, 'jiguangid' ));             //极光id
$inputdataarr['openid']           = trim(HyItems::arrayItem ( $_REQUEST, 'openid' ));             //微信的openid

$inputdataarr['nowid']       = trim(HyItems::arrayItem ( $_REQUEST, 'nowid' ));  //请求的数据id字段
$inputdataarr['typeid']       = trim(HyItems::arrayItem ( $_REQUEST, 'typeid' ));  //类型id字段（1文字评论，2图片评论）



$inputdataarr['productid']       = trim(HyItems::arrayItem ( $_REQUEST, 'productid' ));  //商品id
$inputdataarr['orderid']       = trim(HyItems::arrayItem ( $_REQUEST, 'orderid' ));  //订单id
$inputdataarr['tid']       = trim(HyItems::arrayItem ( $_REQUEST, 'tid' ));  //订单数量

$inputdataarr['sharequan']           = trim(HyItems::arrayItem ( $_REQUEST, 'sharequan' ));//是否分享到朋友圈  设置固定值666
$inputdataarr['sharefriend']         = trim(HyItems::arrayItem ( $_REQUEST, 'sharefriend' ));//是否分享到好友    设置固定值888

$inputdataarr['lat']         = trim(HyItems::arrayItem ( $_REQUEST, 'lat' ));//纬度
$inputdataarr['lng']         = trim(HyItems::arrayItem ( $_REQUEST, 'lng' ));//经度

$inputdataarr['imgwidth']        = trim(HyItems::arrayItem ( $_REQUEST, 'imgwidth' ));
$inputdataarr['imgheight']        = trim(HyItems::arrayItem ( $_REQUEST, 'imgheight' ));

$inputdataarr['type']        = trim(HyItems::arrayItem ( $_REQUEST, 'type' ));  //接口返回数据的类型
$inputdataarr['typename']        = trim(HyItems::arrayItem ( $_REQUEST, 'typename' ));  //子类数据名称
$inputdataarr['proname']        = trim(HyItems::arrayItem ( $_REQUEST, 'proname' ));  //店铺名称
$inputdataarr['over_datetime']        = trim(HyItems::arrayItem ( $_REQUEST, 'over_datetime' ));  //截止时间



$inputdataarr['yuanprice']         = HyItems::arrayItem ( $_REQUEST, 'yuanprice' );//原价
$inputdataarr['nowprice']          = HyItems::arrayItem ( $_REQUEST, 'nowprice ' );//现价
$inputdataarr['discount']          = HyItems::arrayItem ( $_REQUEST, 'discount' );//折扣

$inputdataarr['touserid']  = trim(HyItems::arrayItem ( $_REQUEST, 'touserid' ));
$inputdataarr['cid']      = trim(HyItems::arrayItem ( $_REQUEST, 'cid' ));
$inputdataarr['dtype']   = trim(HyItems::arrayItem ( $_REQUEST, 'dtype' ));  //删除层级类型id---m主表评论---c字表回复
$inputdataarr['quanid']   = trim(HyItems::arrayItem ( $_REQUEST, 'quanid' ));  //评论优惠券id

$inputdataarr['code']  = trim(HyItems::arrayItem ( $_REQUEST, 'code' ));  //微信请求code

$inputdataarr['tag1']  = trim(HyItems::arrayItem ( $_REQUEST, 'tag1' ));  //极光推送关联标签
$inputdataarr['tag2']  = trim(HyItems::arrayItem ( $_REQUEST, 'tag2' ));  //极光推送关联标签
$inputdataarr['tag3']  = trim(HyItems::arrayItem ( $_REQUEST, 'tag3' ));  //极光推送关联标签

$inputdataarr['shouhuoren']  = trim(HyItems::arrayItem ( $_REQUEST, 'shouhuoren' ));  //收货人
$inputdataarr['province']  = trim(HyItems::arrayItem ( $_REQUEST, 'province' ));  //省份
$inputdataarr['city']  = trim(HyItems::arrayItem ( $_REQUEST, 'city' ));  //城市
$inputdataarr['address']  = trim(HyItems::arrayItem ( $_REQUEST, 'address' ));  //地址
$inputdataarr['zipcode']  = trim(HyItems::arrayItem ( $_REQUEST, 'zipcode' ));  //邮编
$inputdataarr['is_default']  = trim(HyItems::arrayItem ( $_REQUEST, 'is_default' ));  //默认值
$inputdataarr['address_id']  = trim(HyItems::arrayItem ( $_REQUEST, 'address_id' ));  //地址id




if('IOS'!=$inputdataarr['system'] && 'PC'!=$inputdataarr['system']) {
	$inputdataarr['system'] = 'ANDROID';
}

if(!is_numeric($inputdataarr['version'])) {
	$echojsonstr = HyItems::echo2clientjson('101','接口版本号不能为空');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}
if(!is_numeric($inputdataarr['thetype']) || strlen($inputdataarr['thetype'])!=4) {
	$echojsonstr = HyItems::echo2clientjson('102','操作类型编号格式错误');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}
if(!is_numeric($inputdataarr['nowtime']) || abs(time()-$inputdataarr['nowtime'])>31*24*60*60) {
	$echojsonstr = HyItems::echo2clientjson('103','时间戳错误');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}
if(strlen($inputdataarr['md5key'])!=32) {
	$echojsonstr = HyItems::echo2clientjson('104','md5key格式错误');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}


//接口版本号的校验码fd5112f036eea77f23bac0bbbadbe592
$clientversionkeyarr = json_decode(CILENTVERSIONJSON,true);
$ckey = isset($clientversionkeyarr[$inputdataarr['version']])?$clientversionkeyarr[$inputdataarr['version']]:'';

if($ckey=='') {
	$echojsonstr = HyItems::echo2clientjson('105','接口版本不存在');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}

$checkstring = $inputdataarr['version'].$inputdataarr['system'].$inputdataarr['sysversion'].$inputdataarr['thetype'].$inputdataarr['nowtime'].$ckey;
if(md5($checkstring)!=$inputdataarr['md5key']) {
	if('127.0.0.1'==$true_ip) {
		//跳过md5key校验
		if($inputdataarr['md5key']=='00000000111111112222222233333333') {
			//通过
		}else {
			$echojsonstr = HyItems::echo2clientjson('106','md5key校验不通过');
			$log_str = $echojsonstr."\n";
			HyItems::hy_writelog($filepath, $filename, $log_str);
			exit($echojsonstr);
		}
	}
}else {
	//通过
	$echojsonstr = HyItems::echo2clientjson('144','校验失败');
	$log_str = $echojsonstr."\n";
	HyItems::hy_writelog($filepath, $filename, $log_str);
	exit($echojsonstr);
}


//判断用户,当用户类型为1时，认为该用户为正式用户，需要进行用户校验
if(1==$inputdataarr['usertype']) {
	if($inputdataarr['userid']=='' || ''==$inputdataarr['userkey']) {
		$echojsonstr = HyItems::echo2clientjson('107','正式用户参数不正确');
		$log_str = $echojsonstr."\n";
		HyItems::hy_writelog($filepath, $filename, $log_str);
		exit($echojsonstr);
	}
}else if(2==$inputdataarr['usertype']){
	//匿名用户
	if($inputdataarr['userid']=='' || ''==$inputdataarr['userkey']){
		$echojsonstr = HyItems::echo2clientjson('108','匿名用户参数不正确');
		$log_str = $echojsonstr."\n";
		HyItems::hy_writelog($filepath, $filename, $log_str);
		exit($echojsonstr);
	}
}else{
	//当非正常用户时，置空
	$inputdataarr['userid'] = '';
	$inputdataarr['userkey'] = '';
}

//=======================================================
if('请输入手机号'==$inputdataarr['phone']) {
	$inputdataarr['phone'] = '';
}

if('请选择折扣'==$inputdataarr['discount']) {
	$inputdataarr['discount'] = '';
}

if('优惠信息名称'==$inputdataarr['proname']) {
	$inputdataarr['discount'] = '';
}
if('undefined'==$inputdataarr['nickname']) {
	$inputdataarr['nickname'] = '';
}



$HySixCon = new HyXbCon($inputdataarr);

$HySixCon->controller();

//将数据写入日志文件
HyItems::hy_writelog($filepath, $filename, $log_str);












// $url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/dd_system';
// define('XMAINURL',$url);

// //获取当前文件名称
// $mname = basename(__FILE__,'.php');

// //敏感字段处理
// $sensitive = dirname(__FILE__);  //D:\www\www2\xm_duibao\duibao\lib
// define('SEURL',$sensitive);

// if( empty($_REQUEST) ){
// 	exit('error,no parameter');
// }

// $xb_get   = $_SERVER["REQUEST_URI"]; //当前目录地址及get参数
// $xb_post  = file_get_contents("php://input"); //原始post数据

// $log_str = "\n".'REP_BEGIN-----------------------------------------------------------'."\n".
// 		date('Y-m-d H:i:s').'    '.$mname."\n".
// 		'get:    '.$xb_get."\n".
// 		'post:    '.$xb_post."\n";


// //文件的路径
// $filepath = LOGPATH;

// //文件的名称
// $filename = date('Y-m-d').'.log';

// //将数据写入日志文件
// HyItems::hy_writelog($filepath, $filename, $log_str); 



// $thetype         = HyItems::arrayItem ( $_REQUEST, 'thetype' );    //操作类型编号，如101,102,103
// $nowtime         = HyItems::arrayItem ( $_REQUEST, 'nowtime' );    //时间戳，预留字段，用于后期校验增加安全性使用
// $md5key          = HyItems::arrayItem ( $_REQUEST, 'md5key' );     //MD5校验值
// $usertype        = HyItems::arrayItem ( $_REQUEST, 'usertype' );   //用户类型，1为正常用户，2为匿名用户，其他的也归为匿名用户 初始化访问时填3匿名用户
// $userid          = HyItems::arrayItem ( $_REQUEST, 'userid' );     //用户在平台的标识编号，平台全部以用户的标识编号作为用户的区分初始化访问时置空
// $userkey         = HyItems::arrayItem ( $_REQUEST, 'userkey' );    //用户通讯的校验密钥 初始化访问时置空




// $phone           = HyItems::arrayItem ( $_REQUEST, 'phone' );                      //手机号必须为11位
// $vcode           = HyItems::arrayItem ( $_REQUEST, 'vcode' );       //手机下发验证码

// $yijian          = HyItems::arrayItem ( $_REQUEST, 'yijian' );       //用户反馈的意见内容
// $keyong_jifen    = HyItems::arrayItem ( $_REQUEST, 'keyong_jifen' );  //可用积分
// $dongjie_jifen   = HyItems::arrayItem ( $_REQUEST, 'dongjie_jifen' ); //冻结积分
// $sex             = HyItems::arrayItem ( $_REQUEST, 'sex' );           //性别，1男，2女，3保密
// $birthday        = HyItems::arrayItem ( $_REQUEST, 'birthday' );      //生日
// $userlevel       = HyItems::arrayItem ( $_REQUEST, 'userlevel' );      // 用户等级，预留，默认1级
// $nickname        = HyItems::arrayItem ( $_REQUEST, 'nickname' );       //用户昵称

// $mobile          = HyItems::arrayItem ( $_REQUEST, 'mobile' );          //联系人手机号
// $shouhuoren      = HyItems::arrayItem ( $_REQUEST, 'shouhuoren' );      //收货人
// $province        = HyItems::arrayItem ( $_REQUEST, 'province' );        //省份
// $city            = HyItems::arrayItem ( $_REQUEST, 'city' );            //城市
// $address         = HyItems::arrayItem ( $_REQUEST, 'address' );         //详细的收货地址
// $zipcode         = HyItems::arrayItem ( $_REQUEST, 'zipcode' );         //邮政编码
// $is_default      = HyItems::arrayItem ( $_REQUEST, 'is_default' );      //是否设置为默认地址
// $address_id     = HyItems::arrayItem ( $_REQUEST, 'address_id' );      //收货地址的唯一标识编号
// $count           = HyItems::arrayItem ( $_REQUEST, 'count' );            //每页的条数，数值介于1到20之间
// $page            = HyItems::arrayItem ( $_REQUEST, 'page' );            //数据请求对应页数

// $taskid          = HyItems::arrayItem ( $_REQUEST, 'taskid' );          //任务id编号
// $tid             = HyItems::arrayItem ( $_REQUEST, 'tid' );             //要删除的推送id编号，多个id编号以英文逗号（,）分隔z

// $tasktype        = HyItems::arrayItem ( $_REQUEST, 'tasktype' );             //任务分组的类型

// $houzhui        = HyItems::arrayItem ( $_REQUEST, 'houzhui' );             //图片的后缀
// $imgdata        = HyItems::arrayItem ( $_REQUEST, 'imgdata' );             //图片

// $jiguangid        = HyItems::arrayItem ( $_REQUEST, 'jiguangid' );             //极光id

// $openid           = HyItems::arrayItem ( $_REQUEST, 'openid' );             //微信的openid

// $quantype           = HyItems::arrayItem ( $_REQUEST, 'quantype' );           //优惠券类型的分类--eg kfc,bsk
// $quanshow           = HyItems::arrayItem ( $_REQUEST, 'quanshow' );           //优惠券的展现形式
// $quanid             = HyItems::arrayItem ( $_REQUEST, 'quanid' );           //优惠券id
// $collect            = HyItems::arrayItem ( $_REQUEST, 'collect' );           //收藏的类型1-收藏 2-取消收藏
// $kindtype            = HyItems::arrayItem ( $_REQUEST, 'kindtype' );           //类型

// $keystr            = HyItems::arrayItem ( $_REQUEST, 'keystr' );           //兑换秘钥

// $libao            = HyItems::arrayItem ( $_REQUEST, 'libao' );   //新手礼包

// $typeid         = HyItems::arrayItem ( $_REQUEST, 'typeid' );  //商品类型编号
// $proname        = HyItems::arrayItem ( $_REQUEST, 'proname' );  //商品的名称
// $productid        = HyItems::arrayItem ( $_REQUEST, 'productid' );
// $type           = HyItems::arrayItem ( $_REQUEST, 'type' );  //商品展现类型

// $keyong_jifen           = HyItems::arrayItem ( $_REQUEST, 'keyong_jifen' );  
// $keyong_money           = HyItems::arrayItem ( $_REQUEST, 'keyong_money' );

// $shoptype           = HyItems::arrayItem ( $_REQUEST, 'shoptype' );//商品的详细类型
// $shopchildtype           = HyItems::arrayItem ( $_REQUEST, 'shopchildtype' );//商品的详细类型

// $sharequan           = HyItems::arrayItem ( $_REQUEST, 'sharequan' );//是否分享到朋友圈  设置固定值666
// $sharefriend         = HyItems::arrayItem ( $_REQUEST, 'sharefriend' );//是否分享到好友    设置固定值888

// $lat         = HyItems::arrayItem ( $_REQUEST, 'lat' );//纬度
// $lng         = HyItems::arrayItem ( $_REQUEST, 'lng' );//经度

// $yuanprice         = HyItems::arrayItem ( $_REQUEST, 'yuanprice' );//原价
// $nowprice         = HyItems::arrayItem ( $_REQUEST, 'nowprice' );//现价
// $discount         = HyItems::arrayItem ( $_REQUEST, 'discount' );//折扣

// $over_datetime         = HyItems::arrayItem ( $_REQUEST, 'over_datetime' );
// $headimgurl         = HyItems::arrayItem ( $_REQUEST, 'headimgurl' );

// $width        = HyItems::arrayItem ( $_REQUEST, 'width' );
// $height        = HyItems::arrayItem ( $_REQUEST, 'height' );



// $touserid  = HyItems::arrayItem ( $_REQUEST, 'touserid' );
// $cid      = HyItems::arrayItem ( $_REQUEST, 'cid' );
// $dtype   = HyItems::arrayItem ( $_REQUEST, 'dtype' );  //删除层级类型id---m主表评论---c字表回复
// $nowid  = HyItems::arrayItem ( $_REQUEST, 'nowid' );  //评论列表id字段


// $code  = HyItems::arrayItem ( $_REQUEST, 'code' );  //微信请求code

// $button  = HyItems::arrayItem ( $_REQUEST, 'button' );  //安卓和ios开关

// $tag1  = HyItems::arrayItem ( $_REQUEST, 'tag1' );  //极光推送标签
// $tag2  = HyItems::arrayItem ( $_REQUEST, 'tag2' );  //极光推送标签
// $tag3  = HyItems::arrayItem ( $_REQUEST, 'tag3' );  //极光推送标签




// //初步判断传递的参数是否正确
// if($thetype=='' || $thetype<101) {
	
// 	$echoarr = array();
// 	$echoarr['returncode']='error';
// 	$echoarr['returnmsg'] = '操作类型编号错误';
				
// 	echo json_encode($echoarr);
// 	return false;
// }

// if($usertype=='') {
	
// 	$echoarr = array();
// 	$echoarr['returncode']='error';
// 	$echoarr['returnmsg'] = '用户类型不能为空';
// 	echo json_encode($echoarr);
// 	return false;
// }
// if($md5key==''||strlen($md5key)!='32') {
// 	$echoarr = array();
// 	$echoarr['returncode']='error';
// 	$echoarr['returnmsg'] = 'md5key客户端校验错误';
// 	echo json_encode($echoarr);
// 	return false;
// }


// $inputdataarr = array(
// 		'thetype'       => $thetype,
// 		'nowtime'       => $nowtime,
// 		'md5key'        => $md5key,
// 		'usertype'      => $usertype,
// 		'userid'        => $userid,
// 		'userkey'       => $userkey,
// 		'phone'         => $phone,
// 		'vcode'         => $vcode,
// 		'yijian'        => $yijian,
// 		'keyong_jifen'  => $keyong_jifen,
// 		'dongjie_jifen' => $dongjie_jifen,
// 		'sex'           => $sex,
// 		'birthday'      => $birthday,
// 		'userlevel'     => $userlevel,
// 		'nickname'      => $nickname,
// 		'mobile'          => $mobile,
// 		'shouhuoren'      => $shouhuoren,
// 		'province'        => $province,
// 		'city'            => $city,
// 		'address'         => $address,
// 		'zipcode'         => $zipcode,
// 		'is_default'      => $is_default,
// 		'address_id'     => $address_id,
// 		'count'           => $count,
// 		'page'            => $page,
// 		'taskid'          => $taskid,
// 		'tid'             => $tid,
// 		'geturl'          => $xb_get,
// 		'posturl'         => $xb_post,
// 		'tasktype'        => $tasktype,
// 		'houzhui'         => $houzhui,
// 		'imgdata'        => $imgdata,
// 		'jiguangid'     => $jiguangid,
// 		'openid'        => $openid,
// 		'quantype'        => $quantype,
// 		'quanshow'        => $quanshow,
// 		'quanid'          => $quanid,
// 		'collect'        => $collect,
// 		'kindtype'        => $kindtype,
// 		'libao'           => $libao,
// 		'typeid'          => $typeid,
// 		'proname'         => $proname,
// 		'type'            => $type,
// 		'productid'        => $productid,
// 		'keyong_jifen'      => $keyong_jifen,
// 		'keyong_money'      => $keyong_money,
// 		'shoptype'        => $shoptype,
// 		'shopchildtype'   => $shopchildtype,
// 		'sharequan'        => $sharequan,
// 		'sharefriend'   => $sharefriend,
// 		'lat' => $lat,
// 		'lng' => $lng,
		
// 		'yuanprice' => $yuanprice,
// 		'nowprice' => $nowprice,
// 		'discount' => $discount,
// 		'over_datetime' => $over_datetime,
// 		'keystr' => $keystr,
		
// 		'headimgurl' => $headimgurl,
// 		'width' => $width,
// 		'height' => $height,
		
// 		'dtype' => $dtype,
// 		'cid'   => $cid,
// 		'nowid' => $nowid,
// 		'touserid' => $touserid,
// 		'code'     => $code,
// 		'button'     => $button,
// 		'tag1'     => $tag1,
// 		'tag2'     => $tag2,
// 		'tag3'     => $tag3,
 		
// );


// $HyXbCon = new HyXbCon($inputdataarr);

// $HyXbCon->controller();










