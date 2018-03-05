<?php
/*
 * 附近数据的分类
 */

class HyXb1021 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $type;  //分类的获取 type=all 主分类 type=美食  子分类
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->type = isset($input_data['type'])?$input_data['type']:'';
	}
	
	//一级分类
	protected function controller_exec1(){
		
		if($this->type=='all'){
			$maintypesql  = "select maintype,count(*) as num from z_tuanmainlist where flag='1' and shstatus='11' group by maintype ";
			$maintypelist = parent::__get('HyDb')->get_all($maintypesql);
		}else{
			$maintypesql  = "select childtype,count(*) as num from z_tuanmainlist  where maintype='".$this->type."' and flag='1' and shstatus='11' group by childtype ";
			$maintypelist = parent::__get('HyDb')->get_all($maintypesql);
		}
		
		if(count($maintypelist)>0){
			$echojsonstr = HyItems::echo2clientjson('100','分类获取成功',$maintypelist);
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('331','分类获取失败');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		
	}
	
	
	public function controller_init(){
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}