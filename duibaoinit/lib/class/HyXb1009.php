<?php
/*
 * 用户信息修改
 */

class HyXb1009 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $sex;
	private $birthday;
	private $nickname;
	private $describes;
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		$this->sex      = isset($input_data['sex'])? $input_data['sex']:'';  
		$this->birthday = isset($input_data['birthday'])?$input_data['birthday']:'';
		$this->nickname = isset($input_data['nickname'])?$input_data['nickname']:'';
	
	}
	
	
	protected function controller_exec1(){
		
		if($this->sex!='' || $this->birthday!='' || $this->nickname!='' ){
			
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
			$useredit_sql .= " where id='".parent::__get('userid')."' and tokenkey='".parent::__get('userkey')."' ";
			
			$useredit_list = parent::__get('HyDb')->execute($useredit_sql);
			parent::hy_log_str_add($useredit_sql."\n");
			
			$echojsonstr = HyItems::echo2clientjson('100','信息修改成功');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return true;
			
			
		}else{
			$echojsonstr = HyItems::echo2clientjson('310','修改参数为空，无法执行修改');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return true;
			
		}
		
	}
	
	
	
	
	//操作入口--用户信息修改，正常用户功能
	public function controller_init(){
		
		//判断正式用户通讯校验参数
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		//用户信息修改入口
		$this->controller_exec1();
	
		return true;
	
	
	}
	
	
	
	
}