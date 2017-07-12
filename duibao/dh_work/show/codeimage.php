<?php

//数字图形验证码输出
//流量兑换

//引入主文件
require_once("../lib/c.core.php");

//启用session
// $HySession = new HySession();

//生成验证码并存储到session中
// $HySession->set('dh_imagecode',$vcode);


$HyImage = new HyImage();

$HyImage->buildImageVerify(4,1,'dh_imagecode','png',50,22);

// echo $vcode;
