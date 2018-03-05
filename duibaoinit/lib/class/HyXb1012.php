<?php
/*
 * 收货地址的获取
 */

class HyXb1012 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
	}
	
	
	
	protected function controller_exec1(){
		
		//用户id
		$userid = parent::__get('userid');
		
		$getaddress_sql  = "select id,is_default,mobile,shouhuoren,province,city,address,zipcode from xb_user_address where userid='".$userid."'";
		$getaddress_list = parent::__get('HyDb')->get_all($getaddress_sql);
		
		if(count($getaddress_list)>0){
			$echojsonstr = HyItems::echo2clientjson('100','收货地址获取成功',$getaddress_list);
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('313','收货地址为空');
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
		
		//判断是否为正常用户
		if(parent::__get('usertype')!='1'){
			$echojsonstr = HyItems::echo2clientjson('314','用户类型错误');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	
		$r = $this->controller_exec1();
		
		return $r;
	}
	
}