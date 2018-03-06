<?php


//引入主文件
require_once("./lib/c.core.php");

header("Content-Type:text/html;charset=utf-8");

$mid = HyItems::arrayItem($_REQUEST, 'mid');
$phone = HyItems::arrayItem($_REQUEST, 'phone');
$type  = HyItems::arrayItem($_REQUEST, 'type');

//$mid='1';
if (!is_numeric($mid)) {
    $retarr = array(
        'code' => '10',
        'msg' => '非法操作',
    );
    exit(json_encode($retarr));
}
if (!is_numeric($phone) || strlen($phone) != 11) {
    $retarr = array(
        'code' => '10',
        'msg' => '手机号码格式不正确',
    );
    exit(json_encode($retarr));
}


$HyDb = new HyDb();


$sql_getmain = "select * from duobao_main where id='" . $mid . "' order by id desc limit 1";

$list_getmain = $HyDb->get_row($sql_getmain);

if (count($list_getmain) <= 0) {
    $retarr = array(
        'code' => '10',
        'msg' => '活动不存在',
    );
    exit(json_encode($retarr));
}


$m_id = $list_getmain['id'];
$m_imgurl = $list_getmain['imgurl'];
$m_name = $list_getmain['name'];
$m_maxperson = $list_getmain['maxperson'];//参与人数
$m_miaoshu = $list_getmain['miaoshu'];
$m_score = $list_getmain['score'];//所需积分
$m_start_datetime = $list_getmain['start_datetime'];//开始时间
$m_end_datetime = $list_getmain['end_datetime'];//结束时间
$m_zjxuhao = $list_getmain['zjxuhao'];//中奖编号
$m_kjtime = $list_getmain['kjtime'];//开奖时间
$m_zjphone = $list_getmain['zjphone'];//中奖手机号
$m_create_datetime = $list_getmain['create_datetime'];


//获取该手机号对应积分
//$phone_jifen = '2000';
$sql_userscore = "select id,keyong_jifen from xb_user where phone='" . $phone . "' and is_lock=1 order by id desc limit 1";
$list_userscore = $HyDb->get_row($sql_userscore);

//用户id
$userid = $list_userscore['id'];

if($type=='1'){//从APP里面进去
	
	if($list_userscore['id'] <= 0){
		
		$retarr = array(
				'code' => '2000',
				'msg' => '用户已下载app，未注册',
		);
		exit(json_encode($retarr));
		
	}else{
		$phone_jifen = $list_userscore['keyong_jifen'];
	}
	
	
	
}else{//从网页端判断
	
	if ($list_userscore['id'] <= 0) {
	
		$retarr = array(
				'code' => '200',
				'msg' => '用户不存在',
		);
		exit(json_encode($retarr));
	} else {
		$phone_jifen = $list_userscore['keyong_jifen'];
	}
	
}


//判断该用户是否符合积分条件
if ($phone_jifen < $m_score) {
    $retarr = array(
        'code' => '201',
        'msg' => '用户积分不足',
        'score'=>$phone_jifen
    );
    exit(json_encode($retarr));

}


if (time() > strtotime($m_kjtime) && $m_kjtime != '0000-00-00 00:00:00') {

    $retarr = array(
        'code' => '12',
        'msg' => '已经开奖，活动结束',
    );
    exit(json_encode($retarr));

}


if (time() < strtotime($m_start_datetime)) {
    $retarr = array(
        'code' => '10',
        'msg' => '活动未开始',
    );
    exit(json_encode($retarr));

}
if (time() > strtotime($m_end_datetime)) {
    $retarr = array(
        'code' => '11',
        'msg' => '活动已结束',
    );
    exit(json_encode($retarr));

}


//判断当前是否达到最大报名数
$sql_maxperson = "select count(*) as num from duobao_child where mid='" . $m_id . "' and create_datetime>='" . $m_start_datetime . "' and create_datetime<='" . $m_end_datetime . "' ";
$list_maxperson = $HyDb->get_row($sql_maxperson);

if ($list_maxperson['num'] > $m_maxperson) {
    $retarr = array(
        'code' => '10',
        'msg' => '本次活动的名额已满',
    );
    exit(json_encode($retarr));
}

//减去用户对应积分值


//判断该手机号是否已经报名过该项目
$sql_phonepan = "select * from duobao_child where mid='" . $m_id . "' and phone='" . $phone . "' order by id desc limit 1";
$list_phonepan = $HyDb->get_row($sql_phonepan);

if (count($list_phonepan) > 0) {

    $list_phonepan['phone'] = substr($list_phonepan['phone'], 0, 3) . '****' . substr($list_phonepan['phone'], -4);

    $retarr = array(
        'code' => '3',
        'msg' => '已报名成功，不可重复报名',
        'list' => $list_phonepan,
    );
    exit(json_encode($retarr));

}


//获取当前子表中的最大序号
$sql_childxuhao = "select max(xuhao) as max_xuhao from duobao_child where mid='" . $m_id . "'";
$list_childxuhao = $HyDb->get_one($sql_childxuhao);
if ($list_childxuhao == '') {
    $list_childxuhao = 0;
}
++$list_childxuhao;
if ($list_childxuhao >$m_maxperson) {
    $retarr = array(
        'code' => '10',
        'msg' => '报名失败，名额已满',
    );
    exit(json_encode($retarr));
}


$create_datetime = date('Y-m-d H:i:s');

$sql_insert = "insert into duobao_child (mid,xuhao,phone,create_datetime) 
				values('" . $m_id . "','" . $list_childxuhao . "','" . $phone . "','" . $create_datetime . "')";
$HyDb->execute($sql_insert);


//---------------------------------------------
//减少用户积分操作
$sql_jianshaoscore = "update xb_user set keyong_jifen=keyong_jifen-'" . $m_score . "' where phone='" . $phone . "'";
$HyDb->execute($sql_jianshaoscore);

//积分消耗记录
$getdescribe = '参加零元夺宝消耗'.$m_score.'馅饼';
$date=time();
$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe)
							values ('".$userid."','1','1','1','".$m_score."','".$date."','".$getdescribe."')";
$HyDb->execute($scoresql);


$showphone = substr($phone, 0, 3) . '****' . substr($phone, -4);

$baomingarr = array(
    'mid' => $m_id,
    'xuhao' => $list_childxuhao,
    'phone' => $showphone,
    'create_datetime' => $create_datetime,
);


$retarr = array(
    'code' => '1',
    'msg' => '报名成功',
    'list' => $baomingarr,
);
exit(json_encode($retarr));
