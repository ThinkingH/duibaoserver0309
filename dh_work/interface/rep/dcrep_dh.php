<?php

//流量充值结果通知文件
//内部兑换赠送流量接口，如果失败则更新秘钥状态值，不涉及微信支付操作


//引入主文件
require_once("../../lib/c.core.php");


//极光推送
$jiguang = new JiPush();



if( empty($_REQUEST) ){
	exit('error,no parameter');
}


//获取当前文件名称
$mname = basename(__FILE__,'.php');
$g_input = $_SERVER["REQUEST_URI"]; //获取原始get数据和访问路径
$p_input = file_get_contents("php://input"); //获取原始post数据

//定义日志数据存放变量
$hywx_logstr  = "\n".'WXPAYLOG_BEGIN-----------------------------------------------------------'."\n".
				date('Y-m-d H:i:s').'    '.$mname."\n".
				'get:    '.$g_input."\n".
				'post:    '.HyItems::hy_tospace($p_input)."\n";

$hywx_logpath = HY_REPLOGPATH.date('Y-m').'/'.$mname.'/';
$hywx_logname = $mname.'_'.date('Y-m-d').'.log';



$siteid     = HyItems::arrayItem ( $_REQUEST, 'siteid' );       //上家分配渠道编号，6位
$phone      = HyItems::arrayItem ( $_REQUEST, 'phone' );        //手机号，11位长度
$phone      = substr(rtrim($phone),-11); //手机号必须为11位
$sj_orderid = HyItems::arrayItem ( $_REQUEST, 'only_orderid' );    //上家定义的订单号
$my_orderid = HyItems::arrayItem ( $_REQUEST, 'site_orderid' );    //我们定义的订单号
$stat       = HyItems::arrayItem ( $_REQUEST, 'stat' );     //DELIVRD/FAIL
$ystatus    = HyItems::arrayItem ( $_REQUEST, 'ystatus' );     //原始错误码


if($phone=='') {
	$hywx_logstr .= 'error,手机号不能为空'."\n";
	HyItems::hy_writelog($hywx_logpath, $hywx_logname, $hywx_logstr);
	
	exit('error,手机号不能为空');
}
if($sj_orderid=='') {
	$hywx_logstr .= 'error,上家定义订单号不能为空'."\n";
	HyItems::hy_writelog($hywx_logpath, $hywx_logname, $hywx_logstr);
	
	exit('error,上家定义订单号不能为空');
}
if($my_orderid=='') {
	$hywx_logstr .= 'error,我们定义订单号不能为空'."\n";
	HyItems::hy_writelog($hywx_logpath, $hywx_logname, $hywx_logstr);
	
	exit('error,我们定义订单号不能为空');
}
if($stat=='') {
	$hywx_logstr .= 'error,状态判断值不能为空'."\n";
	HyItems::hy_writelog($hywx_logpath, $hywx_logname, $hywx_logstr);
	
	exit('error,状态判断值不能为空');
}




//数据库初始化
$HyDb = new HyDb();

//查询该单号是否存在
$sql_orderdata = "select id from dh_orderlist where only_orderid='".$my_orderid."' and phone='".$phone."' and flag='5' ";
$list_orderdata_id = $HyDb->get_one($sql_orderdata);

if($list_orderdata_id<=0) {
	echo 'error,此订单不存在或已经接收处理';
	$hywx_logstr .= '此订单不存在或已经接收处理'."\n";
	
}else {
	
	//更新查询到的此条数据
	if('DELIVRD'==$stat) {
		$sql_uporderno   = "update dh_orderlist set flag='1',over_datetime='".date('Y-m-d H:i:s')."',sj_errcode='".$ystatus."'
							where only_orderid='".$my_orderid."' and phone='".$phone."' and flag='5' ";
		
		$HyDb->execute($sql_uporderno);
		
		
		//添加极光推送
		$jiguang_sql  = "select phone,name from dh_orderlist where only_orderid='".$my_orderid."'";
		$jiguang_list = $HyDb->get_row($jiguang_sql);
		
		
		$jiguangid = $jiguang_list['phone'];
		$message = '《'.$jiguang_list['name'].'》已成功到账，请注意查看！';
		//$message   = '流量成功到账，请注意查看';
		
		//查找该用户对应的极光id
		$jiguangid_sql = "select jiguangid from xb_user where phone='".$jiguangid."'";
		$jiguangid_list = $HyDb->get_row($jiguangid_sql);
		
		$alias=$jiguangid_list['jiguangid'];
		
		
		//极光推送的设置
		$m_type = '';//推送附加字段的类型
		$m_txt = '';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
		$m_time = '86400';//离线保留时间
		$receive = array('alias'=>array($alias));//别名
		//$receive = array('alias'=>array('073dc8672c25d8d023328d06dbbd1230'));//别名
		$content = $message;
		//$message="";//存储推送状态
		
		$result = $jiguang->push($receive,$content,$m_type,$m_txt,$m_time);
		
		
		if($result){
			$res_arr = json_decode($result, true);
		
			if(isset($res_arr['error'])){                       //如果返回了error则证明失败
				echo $res_arr['error']['message'];          //错误信息
				$error_code=$res_arr['error']['code'];             //错误码
				switch ($error_code) {
					case 200:
						$message= '发送成功！';
						break;
					case 1000:
						$message= '失败(系统内部错误)';
						break;
					case 1001:
						$message = '失败(只支持 HTTP Post 方法，不支持 Get 方法)';
						break;
					case 1002:
						$message= '失败(缺少了必须的参数)';
						break;
					case 1003:
						$message= '失败(参数值不合法)';
						break;
					case 1004:
						$message= '失败(验证失败)';
						break;
					case 1005:
						$message= '失败(消息体太大)';
						break;
					case 1008:
						$message= '失败(appkey参数非法)';
						break;
					case 1020:
						$message= '失败(只支持 HTTPS 请求)';
						break;
					case 1030:
						$message= '失败(内部服务超时)';
						break;
					default:
						$message= '失败(返回其他状态，目前不清楚额，请联系开发人员！)';
						break;
				}
			}else{
				$message="发送成功！";
			}
		}else{//接口调用失败或无响应
			$message='接口调用失败或无响应';
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
	}else {
		$sql_uporderno   = "update dh_orderlist set flag='9',over_datetime='".date('Y-m-d H:i:s')."',sj_errcode='".$ystatus."'
							where only_orderid='".$my_orderid."' and phone='".$phone."' and flag='5' ";
		
		$HyDb->execute($sql_uporderno);
		
	}
	
	
}

//将日志数据写入文件
HyItems::hy_writelog($hywx_logpath, $hywx_logname, $hywx_logstr);


echo 'ok';

