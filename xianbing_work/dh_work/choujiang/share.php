<?php
/*
 * 用户分享
 */

//引入主文件
require_once("../lib/c.core.php");


//接收用户id和用户密钥进行校验
$userkey  = HyItems::arrayItem ( $_REQUEST, 'userid' ); //userid
$checkkey = HyItems::arrayItem ( $_REQUEST, 'checkkey' ); //md5(userid+userkey)

$ckey = '527aa50704b8e9e2529e1a03e6ccd912';//校验的key值

//数据库初始化
$HyDb = new HyDb();

if(strlen($userkey)>='14' &&  is_numeric($userkey)){//临时用户
	$tablename = 'xb_temp_user';
	$useridsql  = "select tokenkey,keyong_jifen from $tablename where id='".$userkey."'";
	$useridlist = $HyDb->get_row($useridsql);

}else{//正式用户
	$tablename = 'xb_user';
	$useridsql  = "select tokenkey,keyong_jifen,phone,openid from $tablename where id='".$userkey."'";
	$useridlist = $HyDb->get_row($useridsql);
}



if(count($useridlist)<=0){

	$echoarr = array();
	$echoarr['returncode'] = 'error';
	$echoarr['returnmsg']  = '该用户不存在';
	$echoarr['dataarr'] = array();
	echo json_encode($echoarr);
	return false;

}else{

	$tokenkey  = isset($useridlist['tokenkey'])?$useridlist['tokenkey']:'';
	$userjifen = isset($useridlist['keyong_jifen'])?$useridlist['keyong_jifen']:'';
	$phone     = isset($useridlist['phone'])?$useridlist['phone']:'';
	$openid    = isset($useridlist['openid'])?$useridlist['openid']:'';

	//md5加密
	$md5key = md5($userkey.$tokenkey.$ckey);
	
	//key值校验
	if($md5key!=$checkkey){
		$echoarr = array();
		$echoarr['returncode'] = 'error';
		$echoarr['returnmsg']  = '校验失败';
		$echoarr['dataarr'] = array();
		echo json_encode($echoarr);
		return false;
		
	}else{
		//校验通过，可以进行分享，分两步进行
		//1.未抽奖就进行分享，用户可以抽两次
		//2.抽过一次奖后进行分享，用户可以在抽一次
		$starttime=date('Y-m-d 00:00:00');
		$endtime  = date('Y-m-d 23:59:59');
		$selectdatasql  = "select * from choujiang where userid='".$userkey."' and flag='9' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' limit 1";
		$selectdatalist = $HyDb->get_all($selectdatasql);
		
		if(count($selectdatalist)>0){//
			
			//更新用户是否分享的状态值
			$updateflagsql  = "update choujiang set flag='1' where userid='".$userkey."' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' limit 1 ";
			$updateflaglist = $HyDb->execute($updateflagsql);
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '数据获取成功';
			$echoarr['dataarr'] = $selectdatalist;
			echo json_encode($echoarr);
			return true;
			
		}else{
			
			//记录该用户数据
			$shijian = date('Y-m-d H:i:s');
			$choujiangsql = "insert into choujiang (userid,phone,openid,currentscore,choujiangtime,flag,biaoshi) 
							values ('".$userkey."','".$phone."','".$openid."','".$userjifen."','".$shijian."','1','9')";
			$HyDb->execute($choujiangsql);
			
			//记录分享
			$temparr = array(
					array(
						'prize' => '缤纷流量大抽奖',
						//'picurl'=> 'http://xbapp.xinyouxingkong.com/dh_work/choujiang/images/choujiang.png',
						'picurl'=> 'http://xbapp.xinyouxingkong.com/dh_work/choujiang/images/8.jpg',
					)
					
			);
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '数据获取成功';
			$echoarr['dataarr'] = $temparr;
			echo json_encode($echoarr);
			return true;
			
			
		}
		
	}

}
