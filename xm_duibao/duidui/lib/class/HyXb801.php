<?php
/*
 * 发现搜索模块的实现
 */
class HyXb801 extends HyXb{
	
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
	
		$this->proname = isset($input_data['proname'])? $input_data['proname']:'';  //商品模糊搜索
		$this->lat = isset($input_data['lat'])? $input_data['lat']:'';  //纬度
		$this->lng = isset($input_data['lng'])? $input_data['lng']:'';  //经度
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	//发现的搜索
	public function controller_searchproductlist(){
		
		//发现获取网站数据读取的类型
		$z = parent::__get('fwangzhantype');
		
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,100);
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined' ){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined' || $this->count=='0' ){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//分类数据的查询
		$typesql  = "select count(*) as num from z_tuanmainlist 
					where z='".$z."' and hyflag=1 and flag=1 and zflag=1 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."' 
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' and title like '%".$this->proname."%' ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//商品数据列表的获取
		$shangpinsql = "select id,new_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen 
				 	from z_tuanmainlist where z='".$z."' and hyflag=1 and flag=1 and zflag=1 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."' 
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' and title like '%".$this->proname."%' 
							order by bydiscount asc limit $firstpage,$pagesize ";
		
		$listdata = parent::__get('HyDb')->get_all($shangpinsql); 
		
		foreach ($listdata as $keys=>$vals){
			
			//折扣的计算
			$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			
			if(strlen($listdata[$keys]['discount'])=='1'){
				
				$listdata[$keys]['discount'] = $listdata[$keys]['discount'].'.0';
			}
			
			//标题空格的去除
			$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['title']); 
			
			//两坐标之间距离的计算
			$listdata[$keys]['distance'] = parent::getDistance($this->lat, $this->lng, $listdata[$keys]['lat'], $listdata[$keys]['lng'], $len_type = 2, $decimal = 2);
			
			
		}
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '搜索列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $listdata;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
				
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '搜索为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
		
	}
	
	
	
	//操作入口--商品搜索列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//商品名称
		if($this->proname==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品查询模糊字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
				
		}
		
		//经度不能为空
		if($this->lat==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '纬度字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		
		}
		
		//纬度不能为空
		if($this->lng==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = 'jingdu字段不能为空';
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
		$this->controller_searchproductlist();
	
		return true;
	}
	
	
}