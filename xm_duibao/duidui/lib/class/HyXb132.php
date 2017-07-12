<?php
/*
 * 用户信息修改，正常用户功能--132
 */

class HyXb132 extends HyXb{
	
	private $sex;
	private $birthday;
	private $nickname;
	
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
		
		$this->sex      = isset($input_data['sex'])? $input_data['sex']:'';  
		$this->birthday = isset($input_data['birthday'])?$input_data['birthday']:'';
		$this->nickname = isset($input_data['nickname'])?$input_data['nickname']:'';
	
	
	}
	
	
	protected function controller_edituserinfo(){
		
		
		if($this->sex!='' || $this->birthday!='' || $this->nickname!=''){
			
			$useredit_sql = "update xb_user set ";
			
			if($this->sex!=''){
				$useredit_sql .= " sex='".$this->sex."', ";
			}
			
			if($this->birthday!=''){
				$useredit_sql .= " birthday='".$this->birthday."', ";
			}
			
			if($this->nickname!=''){
				$useredit_sql .= " nickname='".$this->nickname."', ";
			}
			
			$useredit_sql = rtrim($useredit_sql,', ');
			$useredit_sql .= "where id='".parent::__get('xb_userid')."' and tokenkey='".parent::__get('xb_userkey')."' ";
			
			$useredit_list = parent::__get('HyDb')->execute($useredit_sql);
			
			
			if($useredit_list===true){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户信息修改成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '用户信息修改失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '参数传递为空，用户信息修改失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	
	
	//操作入口--用户信息修改，正常用户功能
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}

		//判断是否为正常用户
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
		
		
		//用户信息修改入口
		$this->controller_edituserinfo();
	
		return true;
	
	
	}
	
	
	
	
	
}