<?php
/*
 * 商品订单评价
 */
class HyXb1039 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $productid;
	private $yijian;
	private $orderid;
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		
		$this->productid = isset($input_data['productid'])?$input_data['productid']:'';//商品id
		$this->orderid = isset($input_data['orderid'])?$input_data['orderid']:'';//订单id
		$this->yijian     = isset($input_data['yijian'])?$input_data['yijian']:'';//评论内容
		
	}
	
	
	public function controller_exec1(){
		
		//数据库入库
		$yijian_sql  = "insert into shop_comment (userid,productid,content,create_datetime) values
				      ('".parent::__get('userid')."','".$this->productid."','".$this->yijian."','".parent::__get('create_datetime')."')";
		parent::hy_log_str_add(HyItems::hy_trn2space($yijian_sql)."\n");
		$yijian_list = parent::__get('HyDb')->execute($yijian_sql);
		
		
		//商品评价次数增加
		$productsql = "update shop_product set pingjiacount=pingjiacount+1 where id='".$this->productid."'";
		parent::hy_log_str_add(HyItems::hy_trn2space($productsql)."\n");
		$productlist = parent::__get('HyDb')->execute($productsql);
		
		
		//评价状态的更新
		$pingjiasql = "update shop_userbuy set status=7,pingjia_createtime='".date('Y-m-d H:i:s')."' where  id='".$this->orderid."' ";
		parent::hy_log_str_add(HyItems::hy_trn2space($pingjiasql)."\n");
		$pingjialist = parent::__get('HyDb')->execute($pingjiasql);
		
		
		$echojsonstr = HyItems::echo2clientjson('100','评价成功');
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
		
		if( !is_numeric($this->productid)){
			$echojsonstr = HyItems::echo2clientjson('418','商品id不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		if( $this->yijian==''){
			$echojsonstr = HyItems::echo2clientjson('417','评论内容不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		if( !is_numeric($this->orderid)){
			$echojsonstr = HyItems::echo2clientjson('416','评价的订单编号不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}