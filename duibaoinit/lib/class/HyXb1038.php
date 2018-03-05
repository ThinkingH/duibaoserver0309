<?php
/*
 * 商品评论列表的获取
 */
class HyXb1038 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $productid;
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->page     = isset($input_data['page'])?$input_data['page']:'';
		$this->pagesize = isset($input_data['pagesize'])?$input_data['pagesize']:'';
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		$this->productid = isset($input_data['productid'])?$input_data['productid']:'';//商品id
		
		if(''==$this->imgwidth) {
			$this->imgwidth = '100';
		}
		if(''==$this->imgheight) {
			$this->imgheight = '100';
		}
	}
	
	
	public function controller_exec1(){
		
		$typesql  = "select count(*) as num from shop_comment where productid='".$this->productid."' ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//用户信息的获取
		$sitearr = array();
		$sitepicarr = array();
		
		$sitesql = "select id,nickname,touxiang from xb_user where is_lock=1 ";
		$sitelist = parent::__get('HyDb')->get_all($sitesql); 
		
		foreach ($sitelist as $keys=>$vals){
			
			$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['nickname'];
			$sitepicarr[$sitelist[$keys]['id']] = $sitelist[$keys]['touxiang'];
			
		}
		
		$shopproductsql  = "select * from shop_comment where productid='".$this->productid."' order by id desc ".$pagelimit; 
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql); 
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['nickname'] = isset($sitearr[$shopproductlist[$keys]['userid']])?$sitearr[$shopproductlist[$keys]['userid']]:'';
			$shopproductlist[$keys]['touxiang'] = isset($sitepicarr[$shopproductlist[$keys]['userid']]) ?$sitepicarr[$shopproductlist[$keys]['userid']]:'';
			$shopproductlist[$keys]['touxiang']  = HyItems::hy_qiniuimgurl('duibao-basic',$shopproductlist[$keys]['touxiang'],$this->imgwidth,$this->imgheight);
		}
		
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shopproductlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	
	public function controller_init(){
		
		if( !is_numeric($this->productid)){
			$echojsonstr = HyItems::echo2clientjson('301','商品id不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}