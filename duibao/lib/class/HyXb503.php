<?php
/*
 * 商品类型的获取
 */

class HyXb503 extends HyXb{
	
	
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
	
	}
	
	//商品类型的获取
	public function controller_getproducttypelist(){
		
		
		//商城类型id与名称的匹配
		$typenamearr = array();
		
		$shoptypesql  = "select * from shop_config where flag=1 order by id asc ";
		$shoptypelist = parent::__get('HyDb')->get_all($shoptypesql);
		
		foreach ($shoptypelist as $keys=>$vals){
			
			$replace = array("\t", "\r", "\n",);
			$shoptypelist[$keys]['picurl'] =  str_replace($replace, '', $shoptypelist[$keys]['picurl']);
		}
		
		if(count($shoptypelist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品类型获取成功';
			$echoarr['dataarr'] = $shoptypelist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品类型列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	}
	
	
	
	
	//操作入口--分类商品列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='503'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//商品类型的获取入口
		$this->controller_getproducttypelist();
	
		return true;
	}
	
	
}