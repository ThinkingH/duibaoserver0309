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

$main_day_max = '2'; //每日最大抽奖次数
$main_xiaohaoscore = '20'; //每次抽奖消耗积分


$HySession = new HySession();
$userkey = $HySession->get('session_userid'); //userid
$checkkey = $HySession->get('session_checkkey'); //md5(userid+userkey+$ckey)
$ckey = '527aa50704b8e9e2529e1a03e6ccd912';

//-------------------------------------------------------------
//用户id和key校验

//数据库初始化
$HyDb = new HyDb();

if(strlen($userkey)>='14' &&  is_numeric($userkey)){//临时用户
	$tablename = 'xb_temp_user';
	$scorename = 'xb_temp_user_score';
	
	$useridsql  = "select tokenkey,keyong_jifen from $tablename where id='".$userkey."'";
	$useridlist = $HyDb->get_row($useridsql);
	
}else{//正式用户
	$tablename = 'xb_user';
	$scorename = 'xb_user_score';
	
	$useridsql  = "select tokenkey,keyong_jifen,phone,openid from $tablename where id='".$userkey."'";
	$useridlist = $HyDb->get_row($useridsql);
}



if(count($useridlist)<=0){
	
	echo '0';
	$HySession->set('f_angle','谢谢参与'); //该用户不存在
	
}else{
	
	$tokenkey  = isset($useridlist['tokenkey'])?$useridlist['tokenkey']:'';
	$userjifen = isset($useridlist['keyong_jifen'])?$useridlist['keyong_jifen']:'';
	$phone     = isset($useridlist['phone'])?$useridlist['phone']:'';
	$openid    = isset($useridlist['openid'])?$useridlist['openid']:'';
	
	//md5加密
	$md5key = md5($userkey.$tokenkey.$ckey);
	
	//key值校验
	if($md5key!=$checkkey){
		
		echo '0';
		$HySession->set('f_angle','谢谢参与');//key值校验不通过
		
	}else{//校验通过
		
		//判断该用户仅分享未进行抽奖
		$starttime=date('Y-m-d 00:00:00');
		$endtime  = date('Y-m-d 23:59:59');
		$sharesql  = "select * from choujiang where userid='".$userkey."' and biaoshi='9' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' ";
		$sharelist = $HyDb->get_all($sharesql);
		
		if(count($sharelist)>0){
			$main_day_max = '2';
		}else{
			$selectdatasql  = "select * from choujiang where userid='".$userkey."' and flag='1' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' limit 1";
			$selectdatalist = $HyDb->get_all($selectdatasql);
			
			if(count($selectdatalist)>0){//已进行过分享，可以进行一次抽奖
				$main_day_max = '2';
			}else{
				$main_day_max = '1';
			}
			echo $main_day_max;
			
			 //判断用户积分是否满足可以抽取
			 if($userjifen<$main_xiaohaoscore){//用户积分小于抽奖消耗的积分
			 	
				 echo '0';
				 $xiaohao = '您的积分低于'.$main_xiaohaoscore.'不符合抽奖要求';
				 $HySession->set('f_angle',$xiaohao);
			 	
			 }else{
			 	
				 //2017-03-07 00:00:00   ---  2017-03-07 23:59:59
				 $starttime = date('Y-m-d 00:00:00');
				 $endtime   = date('Y-m-d 23:59:59');
				 //判断用户抽奖次数是否大于3次
				 $prizesql  = "select count(id) as num from choujiang where choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' and userid='".$userkey."' ";
				 $prizelist = $HyDb->get_row($prizesql);
				 
				 if($prizelist['num']>=$main_day_max){
				 	
					 echo '0';
					 $HySession->set('f_angle','抽奖次数达到上限！');
			 	
			 	}else{
			
				 //积分的消耗
				 $updatescoresql  = "update ".$tablename." set keyong_jifen=keyong_jifen-".$main_xiaohaoscore." where id='".$userkey."'";
				 $updatescorelist = $HyDb->execute($updatescoresql);
				
				 //积分消耗记录
				 $gettime= time();
				 $getdescribe = '抽奖消耗'.$main_xiaohaoscore.'馅饼';
				 $scorejilusql = "insert into ".$scorename." (userid,maintype,type,score,gettime,getdescribe) 
				 		values('".$userkey."','2','9','".$main_xiaohaoscore."','".$gettime."','".$getdescribe."')";
				 $scorejillist = $HyDb->execute($scorejilusql);
				
				
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
				 $f_picurl = isset($main_choujiang_jiangpin_arr[$xuanzhong]['picurl'])?$main_choujiang_jiangpin_arr[$xuanzhong]['picurl']:''; //奖品描述
				
				
				
				 //将记录写入session中
				 $HySession->set('f_type',$f_type);//userid
				 $HySession->set('f_score',$f_score);//md5(userid+userkey)
				 $HySession->set('f_angle',$f_angle);//md5(userid+userkey)
			
				 //-------------------------------------------------------------
				 if($f_type=='1'){//商品不是实物
				
					 if($f_score>'0'){ 
					
					 //把中奖积分增加上去
					 $scoresql  = "update ".$tablename." set keyong_jifen=keyong_jifen+".$f_score." where id='".$userkey."'";
					 $scorelist = $HyDb->execute($scoresql);
					
					  } 
				
				 }
			
				//判断是插入还是更新
				 $starttime=date('Y-m-d 00:00:00');
				 $endtime  = date('Y-m-d 23:59:59');
				 $shijian = date('Y-m-d H:i:s');
				 $prize = '抽中'.$f_angle;
				$biaoshisql  = "select * from choujiang where userid='".$userkey."' and biaoshi='9' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."'  ";
				$bioashilist = $HyDb->get_all($biaoshisql);
				if(count($bioashilist)>0){
					//进行更先操作
					$updatesql = "update choujiang set biaoshi='1',phone='".$phone."',openid='".$openid."',
							currentscore='".$userjifen."',score='".$f_score."',prize='".$prize."',choujiangtime='".$shijian."' ,picurl='".$f_picurl."' 
							where userid='".$userkey."' and baioshi='9' and choujiangtime>='".$starttime."' and choujiangtime<='".$endtime."' ";
					$HyDb->execute($updatesql);
					
				}else{
					$choujiangsql = "insert into choujiang (flag,biaoshi,userid,phone,openid,currentscore,score,prize,choujiangtime,picurl) 
							values ('9','1','".$userkey."','".$phone."','".$openid."','".$userjifen."','".$f_score."','".$prize."','".$shijian."','".$f_picurl."')";
					
					$HyDb->execute($choujiangsql);
					
				}
			
			
				 if($f_score>'0'){
				 	
				 //积分详情的记录
				 $gettime= time();
				 $getdescribe = '抽奖获取'.$f_score.'馅饼';
				 $scorecharusql = "insert into ".$scorename." (userid,maintype,type,score,gettime,getdescribe) values ('".$userkey."','2','1','".$f_score."','".$gettime."','".$getdescribe."')";
				 	
				 $HyDb->execute($scorecharusql);
				 	
				 }
				
				 }
				 	
				 } 
			
		}
		
	}
	
}


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







