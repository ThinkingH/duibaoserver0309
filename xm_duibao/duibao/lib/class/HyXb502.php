<?php
/*
 * 分类商品的展示
 */

class HyXb502 extends HyXb{
	
	
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
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	//商品的分类展示
	public function controller_getproducttypelist(){
		
		if($this->typeid=='11' || $this->typeid=='13' || $this->typeid=='14'){
			
			$this->function_typelist();
		}else if($this->typeid=='17'){
			
			$this->function_viptypelist();
		}
		
	}
	
	
	//非vip数据的展示
	public function function_typelist(){
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->count;
		
		$returnarr = array();
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and typeid='".$this->typeid."'";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		//商品类型的输出
		$shopproductsql  = "select * from shop_product where flag=1 and status=1 and onsales=1 and typeid='".$this->typeid."' and feetype in (1,2,3) order by orderbyid desc limit $firstpage,$pagesize";
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		
		foreach ($shopproductlist as $keys=>$vals){
			
			$shopproductlist[$keys]['scoremoney'] = '¥'.$shopproductlist[$keys]['price'].'+'.$shopproductlist[$keys]['score'].'馅饼';
			$shopproductlist[$keys]['downloadnum'] = '568'+ $shopproductlist[$keys]['buycount'];
			
			if($shopproductlist[$keys]['showpic2'] ===null){
				$shopproductlist[$keys]['showpic2']='';
			}
			
			if($shopproductlist[$keys]['showpic3'] ===null){
				$shopproductlist[$keys]['showpic3']='';
			}
			
			if($shopproductlist[$keys]['showpic4'] ===null){
				$shopproductlist[$keys]['showpic4']='';
			}
			
			if($shopproductlist[$keys]['showpic5'] ===null){
				$shopproductlist[$keys]['showpic5']='';
			}
			
			if($shopproductlist[$keys]['miaoshu'] ===null){
				$shopproductlist[$keys]['miaoshu']='';
			}
		
		
		}
		
		
		if(count($shopproductlist)>0){
					
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品分类列表获取成功';
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
			$echoarr['returnmsg']  = '商品分类列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
			
	}
	
	
	
	//vip数据的展示
	public function function_viptypelist(){
		
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->count;
		
		$returnarr = array();
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where flag=1 and status=1 and onsales=1 and feetype='4'";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//顶部图片的输出
		$lunbo_sql = "select img from xb_lunbotu where biaoshi='8' limit 1";
		$lunbo_list = parent::__get('HyDb')->get_one($lunbo_sql);
		
		
		//商品类型的输出
		$shopproductsql  = "select * from shop_product where flag=1 and status=1 and onsales=1 and feetype='4' order by orderbyid desc limit $firstpage,$pagesize";
		$shopproductlist = parent::__get('HyDb')->get_all($shopproductsql);
		
		$i = 0;
		foreach ($shopproductlist as $keys=>$vals){
			$i++;
			if($i==1){
				if($lunbo_list==''){
					$shopproductlist[$keys]['img'] = '';
				}else{
					$shopproductlist[$keys]['img'] = $lunbo_list;
				}
			}
			
			
			$shopproductlist[$keys]['scoremoney'] = '免费';
			$shopproductlist[$keys]['downloadnum'] = '568'+ $shopproductlist[$keys]['buycount'];
			
			if($shopproductlist[$keys]['showpic2'] ===null){
				$shopproductlist[$keys]['showpic2']='';
			}
				
			if($shopproductlist[$keys]['showpic3'] ===null){
				$shopproductlist[$keys]['showpic3']='';
			}
				
			if($shopproductlist[$keys]['showpic4'] ===null){
				$shopproductlist[$keys]['showpic4']='';
			}
				
			if($shopproductlist[$keys]['showpic5'] ===null){
				$shopproductlist[$keys]['showpic5']='';
			}
				
			if($shopproductlist[$keys]['miaoshu'] ===null){
				$shopproductlist[$keys]['miaoshu']='';
			}
		}
		
		
		if(count($shopproductlist)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品分类列表获取成功';
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
			$echoarr['returnmsg']  = '商品分类列表为空';
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
		if(parent::__get('xb_thetype')!='502'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//商品类型id的判断
		if($this->typeid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品类型id不能为空';
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
	
	
		//商品列表的获取入口
		$this->controller_getproducttypelist();
	
		return true;
	}
	
}