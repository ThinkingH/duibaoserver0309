<?php
/*
 * 1-版本升级 2-公司信息
 * 采用数据的缓存
 */
class HyXb1002 extends HyXb{
	
	private $type;
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		//接口类型区分1-版本升级 2-公司信息
		$this->type = isset($input_data['type'])?$input_data['type']:'1';
		
	}
	
	//版本升级
	protected function controller_exec1(){
		
		//数据的查询
		$version_sql = "select * from xb_versioninfo where systemtype='".parent::__get('system')."' and flag='1'  ";
		$version_list = parent::__get('HyDb')->get_row($version_sql);
		//$version_list = parent::func_runtime_sql_data($version_sql);
		
		$echojsonstr = HyItems::echo2clientjson('100','版本信息获取成功',$version_list);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	//公司信息
	protected function controller_exec2(){
		
		//公共信息的缓存
		$config_sql = "select qq,version,content from xb_config";
		//$config_list = parent::func_runtime_sql_data($config_sql);
		$config_list = parent::func_runtime_sql_data($config_sql);
		
		$echojsonstr = HyItems::echo2clientjson('100','公司信息获取成功',$config_list);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	}
	
	
	
	
	//操作入口
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='1' || $this->type==''){//版本升级提示
			$this->controller_exec1();
		}else if($this->type=='2'){//公司信息
			$this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return true;
	
	}
}

