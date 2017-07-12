<?php
//流量密钥兑换执行函数

//引入主文件
require_once("../lib/c.core.php");

//接收参数



$phone = HyItems::arrayItem ( $_REQUEST, 'sel_phone' );
$vcode = HyItems::arrayItem ( $_REQUEST, 'sel_vcode' ); //图形验证码
$phone = trim($phone);
$vcode = trim($vcode);
//判断这三个参数是否齐全
if(!is_numeric($phone) || strlen($phone)!=11) {
	exit('error,手机号格式不符合规范');
}
if(!is_numeric($vcode) || strlen($vcode)<4) {
	exit('error,验证码格式不符合规范');
}

//校验验证码是否和session中存储的一致
$HySession = new HySession();
$imageval_md5 = $HySession->get('dh_selimagecode');

if($imageval_md5!='' && $imageval_md5==md5($vcode)) {
	//图形验证码判断通过
	//图片存储session数据删除
	$HySession->del('dh_selimagecode');
	
}else {
	exit('error,图形验证码错误');
}



//数据库初始化
$HyDb = new HyDb();


//查询改手机号在兑换表中是否存在对应数据
$sql_getmiyao = "select id,flag,gateway,mbps,ttype,orderno,userid,name
				from dh_orderlist
				where phone='".$phone."'
				order by id desc limit 30";
$list_getmiyao = $HyDb->get_all($sql_getmiyao);

if(count($list_getmiyao)<=0) {
	exit('没有查到该手机号对应的充值记录');
	
}else {
	echo '<ul class="ui-list ui-border-tb">';
	foreach($list_getmiyao as $valg) {
		
		echo '<li class="ui-border-t">';
		echo '<div class="ui-avatar">';
		if($valg['flag']=='1') {
			echo '<span style="background-image:url(../public/img/success.png);"></span>';
		}else if($valg['flag']=='5') {
			echo '<span style="background-image:url(../public/img/waiting.png);"></span>';
		}else {
			echo '<span style="background-image:url(../public/img/error.png);"></span>';
		}
		echo '</div>';
		echo '<div class="ui-list-info">';
		echo '<h4 class="ui-nowrap">'.$valg['name'].'</h4>';
		echo '<p class="ui-nowrap">'.$valg['orderno'].'</p>';
		echo '</div>';
		
		echo '</li>';
		
		
	}
	echo '</ul>';
	
	
	
	
}



