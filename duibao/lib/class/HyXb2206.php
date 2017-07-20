<?php
/*
 * 新用户礼包领取
 */

class HyXb2206 extends HyXb{
	
	private $libao1;//礼包奖品
	private $libao2;
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
		
		//领取的礼包
		$this->libao1 = '100';  //
		$this->libao2 = '200';  //
		$this->picurlpath = URLPATH;//icon_novicebg.png
	}
	
	
	//新手领取前的判断1-判断是否登录 2--判断是否是新手 b.是否是7天内的新用户
	public function controller_getnewuserlist(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		//分用户进行判断
		if($usertype=='1'){
		
			$tablename = 'xb_user';
			$tablescorename = 'xb_user_score';
			$tabletuisongname = 'xb_user_tuisong';
		}else if($usertype=='2'){
		
			$tablename = 'xb_temp_user';
			$tablescorename = 'xb_temp_user_score';
			$tabletuisongname = 'xb_temp_user_tuisong';
		}
		
		$phone='';
		$openid='';
		$jiguangid='';
		
		if($usertype=='1'){
			
			$panduloginsql  = "select id,phone,openid,jiguangid,create_datetime from xb_user where id='".$this->userid."'";
			$panduloginlist = parent::__get('HyDb')->get_row($panduloginsql);
			
			$phone = $panduloginlist['phone'];
			$openid = $panduloginlist['openid'];
			$jiguangid = $panduloginlist['jiguangid'];
			
		}else if($usertype=='2'){
			
			$panduloginsql  = "select id,jiguangid,create_datetime from xb_temp_user where id='".$this->userid."'";
			$panduloginlist = parent::__get('HyDb')->get_row($panduloginsql);
			
			$jiguangid = $panduloginlist['jiguangid'];
		}
		
		
		//分2步 1--未进行分享，直接领取     2--进行分享
		if($this->sharequan=='888'){
			
			$newusersql  = "select id from newusers where userid='".$this->userid."'  and type in (1,2)";
			$newuserlist = parent::__get('HyDb')->get_row($newusersql);
			
			if($newuserlist['id']>0){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该用户已领取过礼包';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				
				
				//发放用户100积分，用户领取的记录
				$newuserinsertsql = "insert into newusers (userid,type,phone,openid,libao,createtime) values
							 ('".$this->userid."','1','".$phone."','".$openid."','".$this->libao1."','".date('Y-m-d H:i:s')."')";
				$newuserinsertlist = parent::__get('HyDb')->execute($newuserinsertsql);
				
				$temparr = array(
						array(
							'imgurl'   => $this->picurlpath.'icon_novicebg.png',
						),
				);
				
				if($newuserinsertlist){
						
					//用户领取礼包--获取300积分1.用户表积分的增加 2-新手表的插入3-积分详情的变动
					$jifencharusql  = "update $tablename set keyong_jifen=keyong_jifen+'".$this->libao1."' where id='".$this->userid."'";
					$jifencharulist = parent::__get('HyDb')->execute($jifencharusql);
						
					//积分详情的记录
					$getdescribe = '领取新手好礼'.$this->libao1.'馅饼';
					$date=time();
					$scoresql = "insert into $tablescorename (userid,goodstype,maintype,type,score,gettime,getdescribe)
							values ('".$this->userid."','1','1','1','".$this->libao1."','".$date."','".$getdescribe."')";
					parent::__get('HyDb')->execute($scoresql);
						
					//极光推送
					$message = '恭喜你获取领取新手礼包'.$this->libao1.'馅饼，请查看';
					
					//推送是我记录
					$time =time();
					$tuisongsql = "insert into $tabletuisongname (userid,type,status,message,create_inttime)
							values ('".$this->userid."','1','2','".$message."','".$time."')";
					$tuisonglist = parent::__get('HyDb')->execute($tuisongsql);
						
					parent::func_jgpush($jiguangid,$message);//parent::func_jgpush($jiguangid,$messagee);
						
						
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '新手好礼领取成功';
					$echoarr['dataarr'] = $temparr;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
						
				}else{
					//领取失败
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '新手好礼领取失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
						
				}
				
				
			}
			
			
		}else if($this->sharequan=='666'){//进行分享
			
			//分享的判断
			$newusersql  = "select id from newusers where userid='".$this->userid."' and type='2' ";
			$newuserlist = parent::__get('HyDb')->get_row($newusersql);
			
			if($newuserlist['id']>0){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该用户已分享过';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				//分享获取200积分
				$typesql = "insert into newusers (userid,type,phone,openid,libao,createtime) values
							 ('".$this->userid."','2','".$phone."','".$openid."','".$this->libao2."','".date('Y-m-d H:i:s')."')";
				$typelist = parent::__get('HyDb')->execute($typesql);
				
				
				
				//用户领取礼包--获取300积分1.用户表积分的增加 2-新手表的插入3-积分详情的变动
				$jifencharusql  = "update $tablename set keyong_jifen=keyong_jifen+'".$this->libao2."' where id='".$this->userid."'";
				$jifencharulist = parent::__get('HyDb')->execute($jifencharusql);
				
				/* //分享的状态更新
				$typesql  = "update newusers set type='2',libao=libao+'".$this->libao2."' where userid='".$this->userid."' and type='1' ";
				$typelist = parent::__get('HyDb')->execute($typesql);  */
				
				
				//积分详情的记录
				$getdescribe = '新手好礼分享获取'.$this->libao2.'馅饼';
				$date=time();
				$scoresql = "insert into $tablescorename (userid,goodstype,maintype,type,score,gettime,getdescribe)
							values ('".$this->userid."','1','1','1','".$this->libao2."','".$date."','".$getdescribe."')";
				parent::__get('HyDb')->execute($scoresql);
				
				
				//极光推送
				$message = '新手好礼分享获取'.$this->libao2.'馅饼，请查看';
				
				
				//推送是我记录
				$time =time();
				$tuisongsql = "insert into $tabletuisongname (userid,type,status,message,create_inttime)
				values ('".$this->userid."','1','2','".$message."','".$time."')";
				$tuisonglist = parent::__get('HyDb')->execute($tuisongsql);
				
				
				parent::func_jgpush($jiguangid,$message);
				
				
				if($typelist){
					
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '新手好礼领取成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
					
					
				}else{
					
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '新手好礼领取失败';
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
		if($this->sharequan==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型不能为空';
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