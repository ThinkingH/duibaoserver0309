<?php
/*
 * 商城首页商品列表的获取
 */

class HyXb501 extends HyXb{
	
	
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
		
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //商品类型 10--全部 11--流量 13--电子卡 14--优惠卡 22--实物
		
	
	}
	
	
	//商品列表的获取
	public function controller_getproductlist(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		$arr=array('10','11','13','14','22');  
		if(!in_array($this->type,$arr)){
			$this->type='10';//全部
		} 
		
		if($this->type=='10'){
			$wherestr = " where flag=1 and status=1 ";  //全部
		}else if($this->type=='11'){//流量
			$wherestr = "where flag=1 and status=1 and typeid='11' "; //流量
		}else if($this->type=='13'){
			$wherestr = "where flag=1 and status=1 and typeid='13' ";//电子卡
		}else if($this->type=='14'){
			$wherestr = "where flag=1 and status=1 and typeid='14' ";//优惠卡
		}else if($this->type=='22'){
			$wherestr = "where flag=1 and status=1 and typeid='22' ";//实物
		}
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//获取商品的总条数
		$productnumsql  = "select count(*) as num from shop_product $wherestr ";
		
		$productnumlist = parent::__get('HyDb')->get_all($productnumsql); 
		
		if($productnumlist[0]['num']>0){
			$returnarr['maxcon'] = $productnumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon']= 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//商品数据列表的获取
		$shangpinsql  = "select id,siteid,typeid,name,price,
						score,mainpic,xiangqingurl,buycount,pingjiacount 
						from shop_product 
						 $wherestr order by hottypeid asc,orderbyid asc,id desc limit $firstpage,$pagesize ";
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql); 
		
		if(count($shangpinlist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $shangpinlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//操作入口--商品列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='501'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//类型不能为空
		if($this->type==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品类型展示字段不能为空';
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