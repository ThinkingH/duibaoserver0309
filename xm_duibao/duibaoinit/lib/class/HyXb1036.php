<?php
/*
 * 首页商品数据展示
 */
class HyXb1036 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $type;
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		$this->type = isset($input_data['type'])?$input_data['type']:'';
		
		if(''==$this->imgwidth) {
			$this->imgwidth = '800';
		}
		if(''==$this->imgheight) {
			$this->imgheight = '800';
		}
	}
	
	//首页商品展示
	public function controller_exec1(){
		
		//获取商品的总条数
		$productnumsql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and feetype=1 ";
		$productnumlist = parent::__get('HyDb')->get_one($productnumsql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$productnumlist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品数据列表的获取
		$shangpinsql  = "select id,siteid,name,price,score,mainpic,buycount,pingjiacount,feetype 
						from shop_product 
						where flag=1 and status=1 and onsales=1 and feetype=1 
						order by orderbyid asc,id desc ".$pagelimit;
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql);
		
		foreach ($shangpinlist as $keys=>$vals){
			$shangpinlist[$keys]['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$shangpinlist[$keys]['mainpic'],$this->imgwidth,$this->imgheight);
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shangpinlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	//流量
	public function controller_exec2(){
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and typeid='11' and feetype='1' ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$shopproductsql  = "select id,mainpic,name,price,score,siteid  
							from shop_product 
							where flag=1 and status=1 and onsales=1 and typeid='11' and feetype='1' 
							order by orderbyid desc ".$pagelimit;
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['scoremoney'] = '¥'.number_format($shopproductlist[$keys]['price'] / 100, 2).'+'.$shopproductlist[$keys]['score'].'馅饼';
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
	
	
	//vip商品
	public function controller_exec3(){
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and feetype='4'";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品类型的输出
		$shopproductsql  = "select id,mainpic,name,price,score ,siteid   from shop_product where flag=1 and status=1 and onsales=1 and feetype='4' order by orderbyid asc,id desc  ".$pagelimit;
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['scoremoney'] = '免费';
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
	
	
	//抽奖免费区
	public function controller_exec4(){
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and feetype='5'";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品类型的输出
		$shopproductsql  = "select id,mainpic,name,price,score,siteid   from shop_product where flag=1 and status=1 and onsales=1 and feetype='5' order by orderbyid asc,id desc  ".$pagelimit;
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['scoremoney'] = '免费';
			$shopproductlist[$keys]['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopproductlist[$keys]['mainpic'],$this->imgwidth,$this->imgheight);
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shopproductlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$shopproductlist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	}
	
	
	//支付商品--超值优惠
	public function controller_exec5(){
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and feetype='2'";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品类型的输出
		$shopproductsql  = "select id,mainpic,name,price,score,siteid  from shop_product where flag=1 and status=1 and onsales=1 and feetype='2' order by orderbyid asc,id desc  ".$pagelimit;
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['scoremoney'] = '¥'.number_format($shopproductlist[$keys]['price'] / 100, 2);
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
		
		if($this->type=='all'){//首页商品展示
			$ret = $this->controller_exec1();
		}else if($this->type=='11'){//流量
			$ret = $this->controller_exec2();
		}else if($this->type=='17'){//vip商品
			$ret = $this->controller_exec3();
		}else if($this->type=='15'){//抽奖免费区
			$ret = $this->controller_exec4();
		}else if($this->type=='13'){//支付商品--超值优惠
			$ret = $this->controller_exec5();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		return $ret;
	}
	
}