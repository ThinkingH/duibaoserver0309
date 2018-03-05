<?php
/*
 * 通知信息的删除
 */

class HyXb1020 extends HyXb{
	
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	}
	
	
	protected function controller_exec1(){
		
		//获取用户登录的类型
		$usertype = parent::__get('usertype');
		if($usertype=='1'){
			$tablename = 'xb_user_tuisong';
		}else if($usertype=='2'){
			$tablename = 'xb_temp_user_tuisong';
		}
		
		$tuisongsql  = "delete from $tablename where userid='".parent::__get('userid')."' ";
		parent::hy_log_str_add(HyItems::hy_trn2space($tuisongsql)."\n");
		$tuisonglist = parent::__get('HyDb')->execute($tuisongsql);
		
		if($tuisonglist){
			$echojsonstr = HyItems::echo2clientjson('100','信息清空成功');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('332','信息清空失败');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$ret = $this->controller_exec1();
		return $ret;
	}
	
}