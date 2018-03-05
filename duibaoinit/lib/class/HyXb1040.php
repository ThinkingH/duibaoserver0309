<?php
/*
 * 订单列表删除和实物的订单确认
 */
class HyXb1040 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $type;  //操作类型  type=1 删除订单 type=2 实物确认收货
	private $orderid;  //订单id
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		
		$this->type = isset($input_data['type'])?$input_data['type']:'';//操作类型  type=1 删除订单 type=2 实物确认收货
		$this->orderid = isset($input_data['orderid'])?$input_data['orderid']:'';//订单编号
		
	}
	
	
	//订单删除
	public function controller_exec1(){
		
		$shopproductsql = "update shop_userbuy set status='8', del_createtime='".date('Y-m-d H:i:s')."' where id='".$this->orderid."' ";
		$shopproductlist = parent::__get('HyDb')->execute($shopproductsql);
		
		if($shopproductlist){
			$echojsonstr = HyItems::echo2clientjson('100','删除成功');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('419','删除失败');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
	}
	
	
	//订单确认,实物商品进行商品的确认
	public function controller_exec2(){
	
		$shopproductsql = "update shop_userbuy set status='4', fh_shouhuotime='".date('Y-m-d H:i:s')."' where id='".$this->orderid."' and mtype='2' ";
		$shopproductlist = parent::__get('HyDb')->execute($shopproductsql);
		
		
		$echojsonstr = HyItems::echo2clientjson('100','确认收货成功');
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
		
		if( !is_numeric($this->orderid)){
			$echojsonstr = HyItems::echo2clientjson('420','订单id不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		if($this->type=='1'){
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){
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