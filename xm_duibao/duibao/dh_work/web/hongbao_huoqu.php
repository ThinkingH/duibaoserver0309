<?php

//红包获取


//引入主文件
require_once("../lib/c.core.php");


//获取用户openid，并将获取到的openid写入session，防止多次访问造成的获取不到openid
$HySession = new HySession();
$sess_user_openid = $HySession->get('user_openid');
if($sess_user_openid=='') {
	//获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();
	if($openId!='') {
		$HySession->set('user_openid',$openId);
	}
}else {
	$openId = $sess_user_openid;
}




if($openId=='') {
	echo '<h3>系统错误，请稍后重试</h3>';
	
}else {
	
	//判断此次数据应该匹配查询哪个typeid
	$hytypeidarr = array(
			'5d15af0172251a8cafa06001efab922e' => '1',
			
			
	);
	
	
	$postkey     = isset($_POST['key'])?$_POST['key']:'';
	$finaltypeid = '1';
	
	if(isset($hytypeidarr[$postkey])) {
		$finaltypeid = $hytypeidarr[$postkey];
	}else {
		exit('</h2>错误，请从正常入口获取红包</h2>');
	}
	
	
	
	
	//数据库初始化
	$HyDb = new HyDb();
	
	
	$sql_userhashongbao  = "select id,ttype,flag,tval
							from hongbaolist
							where wxname='".$openId."'
							and create_datetime>='".date('Y-m-01 00:00:00')."'
							and ttype='".$finaltypeid."'";
	$list_userhashongbao = $HyDb->get_all($sql_userhashongbao);
	
	if(count($list_userhashongbao)>=1) {
		$showdatastr  = '<h1>很遗憾！</h1>';
		$showdatastr .= '<h3>每个用户每月只能领取一个红包，您已经领取过</h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/user_hongbaolist.php">查看红包记录</a></h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/liuliang_buy.php">立即购买流量</a></h3>';
		
		echo $showdatastr;
		
	}else {
		//生成红包并写入数据表
		
		$youhuiprice = 0;
		
		if(rand(1,100)<=1) {
			$youhuiprice = rand(200,288);
		}else if(rand(1,100)<=5) {
			$youhuiprice = rand(150,200);
		}else if(rand(1,100)<=15) {
			$youhuiprice = rand(88,150);
		}else if(rand(1,100)<=35) {
			$youhuiprice = rand(36,88);
		}else {
			$youhuiprice = rand(1,36);
		}
		
		
		//将数据插入红包数据表
		$sql_hongbaoinsert   = "insert into hongbaolist (wxname,ttype,flag,tval,create_datetime)
								values ('".$openId."','".$finaltypeid."','9','".$youhuiprice."','".date('Y-m-d H:i:s')."')";
		$HyDb->execute($sql_hongbaoinsert);
		
		
		$showdatastr  = '<h1>恭喜您！</h1>';
		$showdatastr .= '<h3>您抽中了一个<font color="orange">'.($youhuiprice/100).'元</font>的红包</h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/user_hongbaolist.php">查看红包记录</a></h3>';
		$showdatastr .= '<h3><a href="http://wx.51faba.cn/wx_work/web/liuliang_buy.php">立即购买流量</a></h3>';
		
		
		echo $showdatastr;
		
		
		
	}
	
	
	
	
}


