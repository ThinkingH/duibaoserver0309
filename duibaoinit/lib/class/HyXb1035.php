<?php
/*
 * 商城的分类
 */
class HyXb1035 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		
		if(''==$this->imgwidth) {
			$this->imgwidth = '60';
		}
		if(''==$this->imgheight) {
			$this->imgheight = '60';
		}
	}
	
	
	public function controller_exec1(){
		
		//轮播图查询
		$lunbotu_sql = "select * from shop_config where flag=1 and type='".parent::__get('system')."' order by id asc";
		$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
		
		foreach ($lunbotu_list as $keys=>$vals){
			$lunbotu_list[$keys]['picurl'] = HyItems::hy_qiniuimgurl('duibao-shop',$lunbotu_list[$keys]['picurl'],$this->imgwidth,$this->imgheight);
			
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$lunbotu_list);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	
	public function controller_init(){
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}