<?php

$autoloadpath = dirname(dirname(__FILE__)).'/autoload.php';
require_once($autoloadpath);


// 需要填写你的 Access Key 和 Secret Key
/* $accessKey = 'Sky0MkiPGmGhwVKBh6wMYywT11OVDxC15SM8TQEg';
$secretKey = 'D51wLiksNuq7KilbiCCvAY2UZG4p4OUL02Ui5qb6'; */

$accessKey = 'YrNV5vl8DIKjCnGkArKkeI_ouaAizAnchgFPKOKo';
$secretKey = 'gax-kJS5d377c1HCOHgKG1eoFMFs0d7h8C1YNdjC';

//定义常量
define('ACCESSKEY',$accessKey);
define('SECRETKEY',$secretKey);


// 引入鉴权类
use Qiniu\Auth;


// 构建鉴权对象
$hy_auth = new Auth(ACCESSKEY, SECRETKEY);



