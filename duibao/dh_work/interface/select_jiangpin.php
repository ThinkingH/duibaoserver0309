<?php

/**
 * 九宫格转盘抽奖
 */

//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);


//引入主文件
require_once("../lib/c.core.php");
require_once("./jiangpin_array.php"); //奖品概率数组




$HySession = new HySession();


usleep(800000);

$f_type = $HySession->get('f_type');
$f_score = $HySession->get('f_score');
$f_angle = $HySession->get('f_angle');


echo $f_angle;




