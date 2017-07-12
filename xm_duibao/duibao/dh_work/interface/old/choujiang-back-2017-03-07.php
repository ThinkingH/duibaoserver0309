<?php

/**
 * 九宫格转盘抽奖
 */

//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);


//引入主文件
require_once("../lib/c.core.php");
require_once("./jiangpin_array.php"); //奖品概率数组


if( empty($_REQUEST) ){
	//exit('error,no parameter');
}

$main_day_max = '3'; //每日最大抽奖次数
$main_xiaohaoscore = '20'; //每次抽奖消耗积分


$HySession = new HySession();
$userkey = $HySession->get('session_userid'); //userid
$checkkey = $HySession->get('session_checkkey'); //md5(userid+userkey)


//-------------------------------------------------------------
//用户id和key校验







//-------------------------------------------------------------
//判断用户是否可以进行本次抽奖








//-------------------------------------------------------------
//减去对应抽奖积分








//-------------------------------------------------------------
//减去对应抽奖积分
$randarr = array();
foreach ($main_choujiang_jiangpin_arr as $keya => $vala) {
	$randarr[$vala['id']] = $vala['gailv'];
}

$xuanzhong = getRand($randarr);
if($xuanzhong=='') {
	$xuanzhong = '0';
}


echo $xuanzhong;

$f_type  = isset($main_choujiang_jiangpin_arr[$xuanzhong]['type'])?$main_choujiang_jiangpin_arr[$xuanzhong]['type']:''; //类型，暂时不用
$f_score = isset($main_choujiang_jiangpin_arr[$xuanzhong]['score'])?$main_choujiang_jiangpin_arr[$xuanzhong]['score']:''; //对应积分
$f_angle = isset($main_choujiang_jiangpin_arr[$xuanzhong]['angle'])?$main_choujiang_jiangpin_arr[$xuanzhong]['angle']:''; //奖品描述



//将记录写入session中
$HySession->set('f_type',$f_type);//userid
$HySession->set('f_score',$f_score);//md5(userid+userkey)
$HySession->set('f_angle',$f_angle);//md5(userid+userkey)


//-------------------------------------------------------------
//把中奖积分增加上去













//关于中奖概率算法
function getRand($proArr) {
	$result = '';

	//概率数组的总概率精度
	$proSum = array_sum($proArr);

	//概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset ($proArr);

	return $result;
}







