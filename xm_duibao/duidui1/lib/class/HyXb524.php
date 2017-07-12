<?php
/*
 * 商品的详细显示
 */
class HyXb524 extends HyXb{
	
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
	
		$this->shoptype      = isset($input_data['shoptype'])? $input_data['shoptype']:'';             //商品分类
		$this->shopchildtype = isset($input_data['shopchildtype'])? $input_data['shopchildtype']:'';    //商品的详细分类
		
		$this->count    = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page     = isset($input_data['page'])?$input_data['page']:'';     //页数
	}
	
	
	
	//查询的主要操作
	public function controller_getproductlist(){
		
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
		
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		
		
		if($this->shoptype=='10' || $this->shopchildtype=='100'){
			$wherestr = " where flag='1' and status='1' ";  //全部
		}else{
			$wherestr = " where flag='1' and status='1' and typeid='".$this->shoptype."' and typeidchild='".$this->shopchildtype."'";  //全部
		}
		
		$returnarr = array();
		
		//获取总条数
		$shopsumsql  = "select count(*) as num from shop_product $wherestr ";
		$shopsumlist = parent::__get('HyDb')->get_all($shopsumsql);
		
		if($shopsumlist[0]['num']>0){
			$returnarr['maxcon'] = $shopsumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		
		
		//数据的查询
		$sql_shop = "select id,name,price,score,mainpic,xiangqingurl,feetype,buycount,pingjiacount,kucun,daymax 
					from shop_product $wherestr 
					order by hottypeid asc ,orderbyid desc limit $firstpage,$pagesize ";
		$list_shop = parent::__get('HyDb')->get_all($sql_shop); 
		
		if(count($list_shop)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品详细分类列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $list_shop;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
				
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品详细分类列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	//操作入口--商品详细分类的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='524'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//商品详细类型不能为空
		if($this->shoptype==''){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品类型展示字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断商品的类型
		$shuzu = array('10','11','13','22');
		
		if(!in_array($this->shoptype,$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品类型参数错误！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		if($this->shopchildtype==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品详细类型展示字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断商品的类型
		$cshuzu = array('100','111','112','113','130','131');
		
		if(!in_array($this->shopchildtype,$cshuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品子类型参数错误！';
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
		$this->controller_getproductlist();
	
		return true;
	}
	
	
}