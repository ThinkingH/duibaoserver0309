<?php
/*
 * 商品详情信息的展示
 */
class HyXb511 extends HyXb{
	
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
				HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
	
		$this->productid = isset($input_data['productid'])? $input_data['productid']:'';  //商品的类型id
	
	}
	
	
	//详情信息的获取
	public function controller_getproductdetail(){
		
		
		$productdetailsql = "select id,siteid,typeid,name,miaoshu,price,score,mainpic,showpic1,
				showpic2,showpic3,showpic4,showpic5,feetype,xiangqingurl,buycount,pingjiacount,
				create_datetime,stop_datetime,start_datetime,kucun,daymax 
				from shop_product 
				where flag=1 and status=1 and id='".$this->productid."'";
		
		$productdetaillist = parent::__get('HyDb')->get_row($productdetailsql);
		
		
		
		
		if($productdetaillist['id']>0){
			
			if($productdetaillist['stop_datetime']<=date('Y-m-d H:i:s')){
				$productdetaillist['button'] = '9';//商品下架
			}else{
				$productdetaillist['button'] = '1';//商品上架
			}
			
			if($productdetaillist['kucun']<=0){
				$productdetaillist['productnum'] = '9';//库存不足
			}else{
				$productdetaillist['productnum'] = '1';//库存充足
			}
			
			$productdetaillist['miaoshu'] = htmlspecialchars_decode($productdetaillist['miaoshu']);
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品详情获取成功';
			$echoarr['dataarr'] = $productdetaillist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该商品不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	//操作入口--商品详情信息的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='511'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//商品id的判断
		if($this->productid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品id不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
				
		}
	
		//判断每页的条数，数值介于1到20之间
		if($this->count<0 || $this->count>20){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '每页展示的条数超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
	
		//商品详情信息的入口
		$this->controller_getproductdetail();
	
		return true;
	}
	
	
}