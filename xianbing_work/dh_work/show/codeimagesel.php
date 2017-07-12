<?php

//数字图形验证码输出
//手机号兑换记录查询

//引入主文件
require_once("../lib/c.core.php");


$HyImage = new HyImage();

$HyImage->buildImageVerify(4,1,'dh_selimagecode','png',50,22);

// echo $vcode;
