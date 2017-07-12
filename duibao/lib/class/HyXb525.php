<?php
/*
 * 店铺信息的获取
 */

class HyXb525 extends HyXb{
	
	
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
		
		$this->typeid = isset($input_data['typeid'])? $input_data['typeid']:'';  //商品的类型id
	
	}
	
	
	//店铺信息的展示
	public function controller_getproducttypelist(){
		
		if($this->typeid!=''){
			
			$shopproductsql  = "select * from shop_site where id='".$this->typeid."' ";
			$shopproductlist = parent::__get('HyDb')->get_row($shopproductsql);
			
			if(count($shopproductlist)>0){
					
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '商铺列表获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $shopproductlist;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
					
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '商户为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else if($this->typeid==''){
			
			$maoshu = '商品兑换流程请仔细参照商品详情页的“兑换流程“，“注意事项”与“使用时间”。除商品本身不能兑换外，商品一经兑换，一律不退换馅饼。';
				
			$data = array(
					'maoshu'  => $maoshu,
			);
				
			$tt = str_replace("\t","",$data);
				
			if($data!=''){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '重要说明获取成功！';
				$echoarr['dataarr'] = $tt;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '重要说明获取为空！';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
		
		
	}
	
	
	
	//操作入口--分类商户列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='525'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		
	
		$this->controller_getproducttypelist();
	
		return true;
	}
	
}