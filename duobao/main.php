<?php

//0-活动未开始   1-夺宝中 2-未开奖 3-已开奖

//引入主文件
require_once("./lib/c.core.php");

header("Content-Type:text/html;charset=utf-8");

$mid = HyItems::arrayItem($_REQUEST, 'mid');
$phone = HyItems::arrayItem($_REQUEST, 'phone');
$type = HyItems::arrayItem($_REQUEST, 'type');


$mid='1';
//$phone = '18601941578';


if (!is_numeric($mid)) {
    $retarr = array(
        'code' => '0',
        'msg' => '非法操作',
    );
    exit(json_encode($retarr));
}


$HyDb = new HyDb();


$sql_getmain = "select * from duobao_main where id='" . $mid . "' order by id desc limit 1";

$list_getmain = $HyDb->get_row($sql_getmain);

if (count($list_getmain) <= 0) {
    $retarr = array(
        'code' => '0',
        'msg' => '活动不存在',
    );
    exit(json_encode($retarr));
}


$m_id = $list_getmain['id'];
$m_imgurl = $list_getmain['imgurl'];
$m_name = $list_getmain['name'];
$m_maxperson = $list_getmain['maxperson'];//活动参与人数
$m_miaoshu = $list_getmain['miaoshu'];//描述
$m_score = $list_getmain['score'];//活动所需积分
$m_start_datetime = $list_getmain['start_datetime'];//活动开始时间
$m_end_datetime = $list_getmain['end_datetime'];//活动结束时间
$m_zjxuhao = $list_getmain['zjxuhao'];//中奖序号
$m_kjtime = $list_getmain['kjtime'];//开奖时间
$m_zjphone = $list_getmain['zjphone'];//开奖手机号
$m_create_datetime = $list_getmain['create_datetime'];


if (time() < strtotime($m_start_datetime)) {
    $retarr = array(
        'code' => '0',
        'msg' => '活动未开始',
    );
    exit(json_encode($retarr));
}


$sql_childxuhao = "select * from duobao_child where mid='" . $m_id . "' order by xuhao desc,id desc limit 5";
$list_childxuhao = $HyDb->get_all($sql_childxuhao);


$now_people = '0';
$max_people = '0';
$list = array();

if (count($list_childxuhao) <= 0) {
    $now_people = '0';
    $max_people = $m_maxperson;


} else {
    $now_people = $list_childxuhao[0]['xuhao'];
    $max_people = $m_maxperson;


    foreach ($list_childxuhao as $keyp => $valp) {
        $list_childxuhao[$keyp]['phone'] = substr($list_childxuhao[$keyp]['phone'], 0, 3) . '****' . substr($list_childxuhao[$keyp]['phone'], -4);
    }
    $list = $list_childxuhao;

}

//用户信息
$userinfro = "select * from duobao_child where phone='" . $phone . "'";
$userinfolist = $HyDb->get_row($userinfro);

if (count($userinfolist) > 0) {
    $userinfolist['phone'] = substr($userinfolist['phone'], 0, 3) . '****' . substr($userinfolist['phone'], -4);
}


$status = '';

//活动是否开始
if (time() < strtotime($m_start_datetime)) {//
    $status = '0';
} else if (time() >= strtotime($m_start_datetime) && time() <= strtotime($m_end_datetime)) {
    if ($now_people >= $max_people) {
        $status = '4';//人数已满
    } else {
        $status = '1';//夺宝中
    }
} else if (time() > strtotime($m_end_datetime) && time() < strtotime($m_kjtime)) {//等待开奖中
    $status = '2';
} else if (time() > strtotime($m_kjtime) && $m_kjtime != '0000-00-00 00:00:00') {//已开奖
    $status = '3';
}


if (time() > strtotime($m_kjtime) && $m_kjtime != '0000-00-00 00:00:00' && $phone == '') {

    $retarr = array(
        'code' => '1',
        'msg' => '已经开奖',
        'kjtime' => $m_kjtime,
        'zjxuhao' => $m_zjxuhao,
        'nowcount' => $now_people,
        'maxcount' => $max_people,
        'status' => $status,
        'score' => $m_score,
        'list' => $list,
        'zjphone' => substr($m_zjphone, 0, 3) . '****' . substr($m_zjphone, -4),


    );
    exit(json_encode($retarr));

} else if (time() > strtotime($m_kjtime) && $m_kjtime != '0000-00-00 00:00:00' && $phone != '') {

    $retarr = array(
        'code' => '1',
        'msg' => '已经开奖',
        'kjtime' => $m_kjtime,
        'zjxuhao' => $m_zjxuhao,
        'nowcount' => $now_people,
        'maxcount' => $max_people,
        'status' => $status,
        'score' => $m_score,
        'list' => $list,
        'zjphone' => substr($m_zjphone, 0, 3) . '****' . substr($m_zjphone, -4),
        'userinfo' => $userinfolist,

    );
    exit(json_encode($retarr));
}


if (time() > strtotime($m_end_datetime) && $phone == '') {
    $retarr = array(
        'code' => '1',
        'msg' => '等待开奖中',
        'score' => $m_score,
        'nowcount' => $now_people,
        'maxcount' => $max_people,
        'status' => $status,
        'list' => $list,
    );
    exit(json_encode($retarr));

} else if (time() > strtotime($m_end_datetime) && $phone != '') {

    $retarr = array(
        'code' => '1',
        'msg' => '等待开奖中',
        'score' => $m_score,
        'nowcount' => $now_people,
        'maxcount' => $max_people,
        'status' => $status,
        'list' => $list,
        'userinfo' => $userinfolist,
    );
    exit(json_encode($retarr));
}


if ($phone != '') {

    $retarr = array(
        'code' => '1',
        'msg' => '获取成功',
        'score' => $m_score,
        'nowcount' => $now_people,
        'maxcount' => $max_people,
        'status' => $status,
        'list' => $list,
        'userinfo' => $userinfolist,
    );
    exit(json_encode($retarr));
}

$retarr = array(
    'code' => '1',
    'msg' => '获取成功',
    'score' => $m_score,
    'nowcount' => $now_people,
    'maxcount' => $max_people,
    'status' => $status,
    'list' => $list,
);
exit(json_encode($retarr));




