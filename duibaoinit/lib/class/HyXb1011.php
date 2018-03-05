<?php
/*
 * 极光推送用户关联id
 */

class HyXb1011 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $jiguangid;
	private $xb_tag1;
	private $xb_tag2;
	private $xb_tag3;
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		$this->jiguangid   = isset($input_data['jiguangid'])? $input_data['jiguangid']:'';  //极光id
		$this->xb_tag1     = isset($input_data['tag1'])? $input_data['tag1']:'';  //极光id
		$this->xb_tag2     = isset($input_data['tag2'])? $input_data['tag2']:'';  //极光id
		$this->xb_tag3     = isset($input_data['tag3'])? $input_data['tag3']:'';  //极光id
	
	}
	
	
	public function controller_exec1(){
		
		$jiguangid = trim($this->jiguangid);
		
		//获取用户登录的类型
		$usertype = parent::__get('usertype');
		if($usertype=='1'){
			$tablename = 'xb_user';
		}else if($usertype=='2'){
			$tablename='xb_temp_user';
		}
		
		if($this->jiguangid!='' || $this->xb_tag1!='' || $this->xb_tag2!='' || $this->xb_tag3!='' ){
				
			$sql_update = "update $tablename set ";
				
			if($this->jiguangid!='') {
				$sql_update .= " jiguangid='".$this->jiguangid."', ";
			}
				
			if($this->xb_tag1!=''){
		
				$sql_update .= " tag1='".$this->xb_tag1."', ";
			}
				
			if($this->xb_tag2!=''){
		
				$sql_update .= " tag2='".$this->xb_tag2."', ";
			}
				
			if($this->xb_tag3!=''){
		
				$sql_update .= " tag3='".$this->xb_tag3."', ";
			}
				
			$sql_update = rtrim($sql_update,', ');
				
			$sql_update .= "where id='".parent::__get('userid')."' ";
				
			$r = parent::__get('HyDb')->execute($sql_update);
			parent::hy_log_str_add(HyItems::hy_trn2space($sql_update)."\n");
		
			$echojsonstr = HyItems::echo2clientjson('100','极光推送关联成功');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('312','极光关联参数为空，操作失败');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	}
	
	
	public function controller_init(){
	
		//判断正式用户通讯校验参数
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$this->controller_exec1();
	
		return true;
	
	
	}
}