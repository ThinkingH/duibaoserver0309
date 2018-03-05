<?php
/*
 * 商家信息的获取以及商品列表的获取
 */
class HyXb1037 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $typeid;
	private $type;
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->page = isset($input_data['page'])?$input_data['page']:'';
		$this->pagesize = isset($input_data['pagesize'])?$input_data['pagesize']:'';
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		$this->typeid = isset($input_data['typeid'])?$input_data['typeid']:'';
		$this->type = isset($input_data['type'])?$input_data['type']:'';
		
		if(''==$this->imgwidth) {
			$this->imgwidth = '800';
		}
		if(''==$this->imgheight) {
			$this->imgheight = '800';
		}
	}
	
	
	public function controller_exec1(){
		
		$shopproductsql  = "select * from shop_site where id='".$this->typeid."' ";
		$shopproductlist = parent::__get('HyDb')->get_row($shopproductsql);
		
		$shopproductlist['touxiang'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopproductlist['touxiang'],$this->imgwidth,$this->imgheight);
		
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$shopproductlist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	
	public function controller_exec2(){
		
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and siteid='".$this->typeid."'";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$shopproductsql  = "select id,mainpic,price,score,name,feetype from shop_product where flag=1 and status=1 and onsales=1 and siteid='".$this->typeid."'  
							order by orderbyid asc,id desc ".$pagelimit;
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		foreach ($shopproductlist as $keys=>$vals){
			
			$shopproductlist[$keys]['scoremoney']=parent::func_diffzhifutype($shopproductlist[$keys]['feetype'],$shopproductlist[$keys]['price'],$shopproductlist[$keys]['score']);
			$shopproductlist[$keys]['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopproductlist[$keys]['mainpic'],$this->imgwidth,$this->imgheight);
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
		
		if($this->type=='1'){//获取商户信息
			 $ret = $this->controller_exec1();
		}else if($this->type=='2'){//获取商品下的数据
			$ret = $this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}