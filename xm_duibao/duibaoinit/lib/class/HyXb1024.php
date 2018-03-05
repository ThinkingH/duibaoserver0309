<?php
/*
 * 我的发布优惠
 */
class HyXb1024 extends HyXb{
	
	private $pagesize;
	private $page;
	private $imgwidth;
	private $imgheight;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		
		if(''==$this->imgwidth) {
			$this->imgwidth = 600;
		}
		if(''==$this->imgheight) {
			$this->imgheight = 600;
		}
	}
	
	
	public function controller_exec1(){
		
		//分类数据的查询
		$typesql  = "select count(*) as num from z_fabulist where faflag=1 and userid='".parent::__get('userid')."' ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$shoptypesql  = "select * from z_fabulist where faflag=1  and userid='".parent::__get('userid')."'  order by id desc ".$pagelimit;
		$shoptypelist = parent::__get('HyDb')->get_all($shoptypesql);
		
		foreach ($shoptypelist as $keys=>$vals){
			$shoptypelist[$keys]['picurl'] = HyItems::hy_qiniuimgurl('duibao-find',$shoptypelist[$keys]['picurl'],$this->imgwidth,$this->imgheight);
			if($shoptypelist[$keys]['shstatus']=='11'){
				$shoptypelist[$keys]['shstatus']='已成功';
			}else if($shoptypelist[$keys]['shstatus']=='99'){
				$shoptypelist[$keys]['shstatus']='审核中';
			}else if($shoptypelist[$keys]['shstatus']=='9'){
				$shoptypelist[$keys]['shstatus']='未通过';
			}
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shoptypelist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
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