<?php
/*
 * 首页商品搜索（查询抓取淘宝联盟的数据）
 */
class HyXb1028 extends HyXb{
	
	private $proname; //搜索查询字段
	private $page; 
	private $pagesize; 
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->proname = isset($input_data['proname'])?$input_data['proname']:'';
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	}
	
	
	public function controller_exec1(){
		$where = '';
		if($this->proname==''){
			$where = " flag=1 and new_datetime>='".date("Y-m-d H:i:s",strtotime("-3 month"))."' and new_datetime<='".date('Y-m-d H:i:s')."'   ";
		}else{
			$where = " flag=1 and title like '%".$this->proname."%'  ";
		}
		
		//分类数据的查询
		$typesql  = "select count(*) as num from z_quantaobaoke where $where   ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品数据列表的获取
		$shangpinsql  = "select id,new_datetime,title,picurl,spicurl,quanurl,yuanprice,nowprice,quanprice,maintype,childtype,type from z_quantaobaoke
						where $where
						order by new_datetime desc  ".$pagelimit;
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql);
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shangpinlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
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