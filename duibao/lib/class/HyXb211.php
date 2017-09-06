<?php
/*
 * 抽奖分享
 */
class HyXb211 extends HyXb{
	
	
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
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
	}
	
	//分享操作操作
	public function controller_share(){
		
		//判断用户的等级
		$userrange_sql = "select * from xb_user where id='".$this->userid."' limit 1 ";
		$userrange_list = parent::__get('HyDb')->get_all($userrange_sql);
		
		$phone = isset($userrange_list[0]['phone'])?$userrange_list[0]['phone']:'';
		//1-vip用户  10-普通用户
		$person = isset($userrange_list[0]['vipflag'])?$userrange_list[0]['vipflag']:'10';
		
		$where = '';
		if($person=='1'){//vip
			$where = 'vipnum = vipnum +1';
		}else{
			$where = 'normalnum = normalnum +1';
		}
		
		
		//判断该用户是否分享
		$user_sql = "select id  from newusers where type='5' and createtime>='".date('Y-m-d 00:00:00')."' 
					and createtime<='".date('Y-m-d 23:59:59')."' and userid='".$this->userid."' limit 1";
		$user_list = parent::__get('HyDb')->get_row($user_sql);
		
		$r='';
		if($user_list['id']<=0){
			//抽奖次数的增加
			$scoresql = "update db_prize_config set $where where  flag='1' ";
			$r = parent::__get('HyDb')->execute($scoresql);
			
			//分享记录的增加
			$shanre_sql = "insert into newusers (userid,type,phone,createtime) 
					values ('".$this->userid."','5','".$phone."','".date('Y-m-d H:i:s')."') ";
			$shanre_list = parent::__get('HyDb')->execute($shanre_sql);
		}
		
		if($r){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '分享次数增加成功';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '已分享成功，次数不在增加';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			
			echo json_encode($echoarr);
			return true;
		}
		
	}
	
	
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		$this->controller_share();
	
		return true;
	
	}
	
	
}