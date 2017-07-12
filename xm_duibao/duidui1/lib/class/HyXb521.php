<?php
/*
 * 商品详情页的判断
 */
class HyXb521 extends HyXb{
	
	private $productid;
	
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
	
	
	//兑换详情页
	public function controller_duihuanproductdetail(){
		
		$productdetailsql  = "select id,name,miaoshu,price,score,mainpic,feetype,xiangqingurl from shop_product where flag='1' and status='1' and id='".$this->productid."'";
		$productdetaillist = parent::__get('HyDb')->get_row($productdetailsql);
		
		if(count($productdetaillist)>0){
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
			$echoarr['returnmsg']  = '商品详情获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	//操作入口--商品兑换的详情展示
	public function controller_init(){
	
		
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		//商城的参数判断
		$r = parent::shopduihuan_check();
		if($r===false){
			return false;
		}
		
		$r = parent::check_duihuan_canshu();
		if($r===false){
			return false;
		}
		
		//判断该商品每日兑换的数量
		$r = parent::check_duihuan_max_day();
		if($r===false){
			return false;
		}
		
		
		//1.判断该用户每日该商品的兑换数量
		$r = parent::check_duihuan_user_day();
		if($r===false){
			return false;
		}
		
		//2.商品每月的最大兑换次数
		$r = parent::check_duihuan_user_month();
		if($r===false){
			return false;
		}
		
		//判断该商品该用户每年的最大兑换次数
		$r = parent::check_duihuan_user_year();
		if($r===false){
			return false;
		}
		
		//支付方式的判断
		$r = parent::check_scoremoney_user();
		if($r===false){
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
	
	
		//商品的兑换
		$this->controller_duihuanproductdetail();
	
		return true;
	}
	
}