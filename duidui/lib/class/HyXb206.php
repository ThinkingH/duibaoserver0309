<?php
/*
 * 新手礼包的领取
 */

class HyXb206 extends HyXb{
	
	private $libao;//礼包奖品
	private $userid;
	private $sharequan;//分享到朋友圈
	private $sharefriend;//分享给好友
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
				HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
		
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
		
		$this->sharequan   = isset($input_data['sharequan']) ? $input_data['sharequan']:'';  //分享到朋友圈
		$this->sharefriend = isset($input_data['sharefriend']) ? $input_data['sharefriend']:'';//分享到好友
		
		//领取的礼包
		$this->libao = '300';  //
	}
	
	
	//新手领取前的判断1-判断是否登录 2--判断是否是新手 b.是否是7天内的新用户
	public function controller_getnewuserlist(){
		
		//1.判断是否登录
		$panduloginsql  = "select id,phone,openid,jiguangid,create_datetime from xb_user where id='".$this->userid."'";
		$panduloginlist = parent::__get('HyDb')->get_row($panduloginsql);
		
		
		if($panduloginlist['id']<=0){//用户没登录
			
			//该用户未进行注册，请进行注册
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户未登录';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}else{
			//判断该用户是否存在新手表中
			$newusersql  = "select id from newusers where userid='".$this->userid."'";
			$newuserlist = parent::__get('HyDb')->get_row($newusersql);
			
			if(count($newuserlist)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该用户已领取过礼包';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				
				//用户创建时间的时间戳
				$createtime = strtotime($panduloginlist['create_datetime']);
				$phone  = $panduloginlist['phone'];
				$openid = $panduloginlist['openid'];
				$jiguangid = $panduloginlist['jiguangid'];
				
				//判断是否是7天内注册的
				if((time()-$createtime)>604800){//超过7天
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '您不是新用户,不可以领取礼包！';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				

				//朋友圈的分享
				if($this->sharequan!='666' && $this->sharefriend!='888'){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '分享后才可以领取馅饼哦！';
					$echoarr['dataarr']    = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
				
				//新手包的插入
				$date = date('Y-m-d H:i:s');
				$newuserinsertsql = "insert into newusers (userid,phone,openid,libao,createtime) values
							 ('".$this->userid."','".$phone."','".$openid."','".$this->libao."','".$date."')";
				$newuserinsertlist = parent::__get('HyDb')->execute($newuserinsertsql);
				
				
				if($newuserinsertlist){
					
					//用户领取礼包--获取300积分1.用户表积分的增加 2-新手表的插入3-积分详情的变动
					$jifencharusql  = "update xb_user set keyong_jifen=keyong_jifen+'".$this->libao."' where id='".$this->userid."'";
					$jifencharulist = parent::__get('HyDb')->execute($jifencharusql);
					
					//积分详情的记录
					$getdescribe = '领取新手礼包'.$this->libao.'馅饼';
					$date=time();
					$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe) 
							values ('".$this->userid."','1','1','1','".$this->libao."','".$date."','".$getdescribe."')";
							parent::__get('HyDb')->execute($scoresql);
							
					//极光推送
					$message = '恭喜你完成新手礼包的引导任务，现已将'.$this->libao.'馅饼发送到你的账户，请查看';
					
					parent::func_jgpush($jiguangid,$message);
					
					
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '礼包领取成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
					
				}else{
					
					//领取失败
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '礼包领取失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}
				
			}
		}
	}
	
	
	//操作入口--新手领取礼包
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='206'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//用户类型错误
		if(parent::__get('xb_usertype')!='1'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
		
		$this->controller_getnewuserlist();
	
		return true;
	}
	
}