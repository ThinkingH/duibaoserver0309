<?php
/*
 * 新手领取的判断
 */

class HyXb205 extends HyXb{
	
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
	
	}
	
	
	//新手领取前的判断1-判断是否登录 2--判断是否是新手 a.是否收藏过饭票 b.是否是7天内的新用户
	public function controller_newuserlist(){
		
		
		//1.判断是否登录
		$panduloginsql  = "select id,create_datetime from xb_user where id='".$this->userid."'";
		$panduloginlist = parent::__get('HyDb')->get_row($panduloginsql);
		
		if($panduloginlist['id']>0){//已经登录
			
			
			//用户创建时间的时间戳
			$createtime = strtotime($panduloginlist['create_datetime']);
			
			//判断是否是7天之内的新手
			if((time()-$createtime)>604800){//超过7天
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该用户不是新用户';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				
				//判断是否收藏过东西
				$usercollectsql = "select id from xb_collection where userid='".$this->userid."'";
				$usercollectlist = parent::__get('HyDb')->get_row($usercollectsql);
				
				
				if($usercollectlist['id']>0){//该用户满足收藏过商品
					
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
						
						$echoarr = array();
						$echoarr['returncode'] = 'success';
						$echoarr['returnmsg']  = '该用户满足领取礼包的条件';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return true;
						
					}
					
				}else{//未进行商品收藏，不满足新手条件
					
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '该用户未做任务，不满足领取礼包的要求';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
					
			} 
			
			
		}else{
			//该用户未进行注册，请进行注册
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户未登录';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//操作入口--新手领取前的判断
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='205'){
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
	
		
		$this->controller_newuserlist();
	
		return true;
	}
	
}