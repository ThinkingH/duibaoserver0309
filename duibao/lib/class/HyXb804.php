<?php
/*
 *坐标的计算
 */
class HyXb803 extends HyXb{
	
	private $lat;
	private $lng;
	private $type;
	private $kindtype;
	
	
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
	
		$this->lat = isset($input_data['lat'])? $input_data['lat']:'';  //纬度
		$this->lng = isset($input_data['lng'])? $input_data['lng']:'';  //经度
		
		$this->type = isset($input_data['type'])? $input_data['type']:'';  //类型
		$this->kindtype = isset($input_data['kindtype'])? $input_data['kindtype']:'';  //美食类型
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	
	public function controller_zuobiaolist(){
		
		//发现获取网站数据读取的类型
		$z = '2';
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		//查询的条件
		$where = '';
		
		if($this->kindtype==''){//查询全部的
			
			$where = "z='".$z."' and hyflag=1 and flag=1 and zflag=1 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."' 
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
			
			
		}else{//查询单个的
			
			$where = "z='".$z."' and hyflag=1 and flag=1 and zflag=1 and childtype = '".$this->kindtype."' 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
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
			
		//分类数据的查询
		$typesql  = "select count(*) as num from z_tuanmainlist where $where ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
			
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
			
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//1-人气最高
		if($this->type=='1'){
			
			$sqldata = "select id,new_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen 
				 	from z_tuanmainlist where $where order by bydiscount desc limit $firstpage,$pagesize ";
			
		}else if($this->type=='2'){//评价最高
			
			$sqldata = "select id,new_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen 
				 	from z_tuanmainlist where $where order by pingfen desc limit $firstpage,$pagesize ";
			
			
		}else if($this->type=='3'){//最新发布
			$sqldata = "select id,new_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen 
				 	from z_tuanmainlist where $where  order by new_datetime desc limit $firstpage,$pagesize ";
		}
		
		
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		foreach ($listdata as $keys=>$vals){
			$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			$listdata[$keys]['distance'] = parent::getDistance($this->lat, $this->lng, $listdata[$keys]['lat'], $listdata[$keys]['lng'], $len_type = 2, $decimal = 2);
			
			if($listdata[$keys]['pingfen']>'0' && $listdata[$keys]['pingfen']<='1.5'){
				
				$listdata[$keys]['geshu']= '1';
			}else if($listdata[$keys]['pingfen']>'1.5' && $listdata[$keys]['pingfen']<='2.5'){
				
				$listdata[$keys]['geshu']= '2';
			}else if($listdata[$keys]['pingfen']>'2.5' && $listdata[$keys]['pingfen']<='3.5'){
				$listdata[$keys]['geshu']= '3';
			}else if($listdata[$keys]['pingfen']>'3.5' && $listdata[$keys]['pingfen']<='4.5'){
				$listdata[$keys]['geshu']= '4';
			}else if($listdata[$keys]['pingfen']>'4.5' && $listdata[$keys]['pingfen']<='5.0'){
				$listdata[$keys]['geshu']= '5';
			}
			
			
			
			
		}
		
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '列表数据获取成功!';
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
			$echoarr['returnmsg']  = '列表数据为空!';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
		
		
		
	}
	
	
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//类型不能为空
		if($this->type==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型字段不能为空';
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
	 
	
	
		//坐标列表的获取入口
		$this->controller_zuobiaolist();
	
		return true;
	}
	
	
	
}