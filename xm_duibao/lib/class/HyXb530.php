<?php
/*
 评价列表的获取
 */

class HyXb530 extends HyXb{
	
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
		
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:''; 
		$this->productid = isset($input_data['productid']) ? $input_data['productid']:'';
	
	}
	
	
	
	public function controller_getproducttypelist(){
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  =  $this->count;
		
		$returnarr = array();
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_comment  ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		//商户信息的获取
		$sitearr = array();
		$sitepicarr = array();
		
		$sitesql = "select id,nickname,touxiang from xb_user where is_lock=1 ";
		$sitelist = parent::__get('HyDb')->get_all($sitesql); 
		
		foreach ($sitelist as $keys=>$vals){
			
			$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['nickname'];
			$sitepicarr[$sitelist[$keys]['id']] = $sitelist[$keys]['touxiang'];
			
		}
		
		$shopproductsql  = "select * from shop_comment where productid='".$this->productid."' order by id desc limit $firstpage,$pagesize  "; 
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql); 
		
		foreach ($shopproductlist as $keys=>$vals){
			$shopproductlist[$keys]['storename'] = $sitearr[$shopproductlist[$keys]['userid']];
			$shopproductlist[$keys]['touxiang']  = $sitepicarr[$shopproductlist[$keys]['userid']];
		}
		
		
		if(count($shopproductlist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '评价列表获取成功';
			$echoarr['pflag']  = 1;
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
			$echoarr['returnmsg']  = '评价列表为空';
			$echoarr['pflag']  = 2;
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		if($this->productid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品id不能为空！';
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