<?php
/*
 * 微信与手机号的绑定
 */

class HyXb1006 extends HyXb{
	
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $phone;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//用户手机号
		$this->phone = isset($input_data['phone']) ? $input_data['phone']:'';  //
	}
	
	
	protected function controller_exec1(){//HyItems::hy_trn2space($linshiuser_sql)."\n";
		
		//判断该手机号是否是兑宝用户
		$phone_sql  = "select id,keyong_jifen,nickname,touxiang,openid,phone from xb_user where phone='".$this->phone."' and is_lock=1  limit 1";
		$phone_list = parent::__get('HyDb')->get_row($phone_sql); 
		if($phone_list['id']>0){//该用户为兑宝用户，判断是否已进行手机号的绑定
			if($phone_list['openid']!=''){
				$echojsonstr = HyItems::echo2clientjson('307','该手机号已进行过微信绑定');
				if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
			
			$jifen = $phone_list['keyong_jifen'];
			$nickname = $phone_list['nickname'];
			$touxiang = $phone_list['touxiang'];
			
			//以前的手机号账户关闭
			$updatephone_sql  = "update xb_user set is_lock='9',remark='手机与微信进行绑定，该手机号账户关闭' where phone='".$this->phone."' ";
			parent::hy_log_str_add(HyItems::hy_trn2space($updatephone_sql)."\n");
			$updatephone_list = parent::__get('HyDb')->execute($updatephone_sql);
				
			$updatesql = "update xb_user set phone='".$this->phone."',keyong_jifen=keyong_jifen+'".$jifen."' where id='".parent::__get('userid')."' ";
			$updatelist = parent::__get('HyDb')->execute($updatesql);
			parent::hy_log_str_add(HyItems::hy_trn2space($updatesql)."\n");
		}else{
			//微信和手机号进行关联绑定
			$updatesql  = "update xb_user set phone='".$this->phone."' where id='".parent::__get('userid')."' ";
			parent::hy_log_str_add(HyItems::hy_trn2space($updatesql)."\n");
			$updatelist = parent::__get('HyDb')->execute($updatesql);
		}
		
		if($updatelist){
			$echojsonstr = HyItems::echo2clientjson('100','手机号绑定成功');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('308','手机号绑定失败');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
	}
	
	
	
	//操作入口
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
	
		if( !is_numeric($this->phone) || strlen($this->phone)!='11'){
			$echojsonstr = HyItems::echo2clientjson('124','手机号码格式不正确');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		$r = $this->controller_exec1();
		
		return $r;
	}
	
}