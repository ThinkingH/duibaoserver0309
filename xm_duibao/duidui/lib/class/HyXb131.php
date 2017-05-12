<?php
/**
 * 用户的信息获取--操作类型是131
 */

class HyXb131 extends HyXb{
	
	private $usertype;
	
	
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
	
	
	}
	
	
	protected function controller_getuserinfo(){
		
		
		//用户登录类型,分为正常用户--1和匿名用户--2两种
		$usertype = parent::__get('xb_usertype');
		
		
		//当usertype=1时，正常用户
		if($usertype=='1'){
			$userinfo_sql = "select id,phone,keyong_jifen,dongjie_jifen,sex,birthday,userlevel,nickname,create_datetime 
							from xb_user where id='".parent::__get('xb_userid')."'and tokenkey='".parent::__get('xb_userkey')."'";
			$userinfo_list = parent::__get('HyDb')->get_all($userinfo_sql);
			
			if($userinfo_list[0]['nickname']=='' || $userinfo_list[0]['nickname']=='undefined'){
				
				
				$userinfo_list[0]['nickname']=substr($userinfo_list[0]['phone'],0,3).'****'.substr($userinfo_list[0]['phone'],-4);
				
			}
			
			if(count($userinfo_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户信息查询成功';
				$echoarr['dataarr'] = $userinfo_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '用户信息查询失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
		}else if($usertype=='2'){
			
			$userinfo_sql = "select id,keyong_jifen,dongjie_jifen,create_datetime from xb_temp_user 
					where id='".parent::__get('xb_userid')."' and tokenkey='".parent::__get('xb_userkey')."'";
			$userinfo_list = parent::__get('HyDb')->get_all($userinfo_sql);
			
			
			if(count($userinfo_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户信息查询成功';
				$echoarr['dataarr'] = $userinfo_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '用户信息查询失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型错误！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}
		
		
		
	}
	
	
	//用户信息--操作入口
	public function controller_init(){
		
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		//用户信息获取入口
		$this->controller_getuserinfo();
	
		return true;
	
	
	}
	
	
	
	
}