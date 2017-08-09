<?php
/*
 *附近数据的展示
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
		$z = '1';
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		//查询的条件
		$where = '';
		
		$shopaddress = array();//距离商铺的距离
		
		if($this->type=='3'){
			
			//地理位置转换为经纬度
			$shopaddress_sql = "select id,address,lat,lng from shop_site where flag='1' and checkstatus='2' and storestatus='2' 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' ";
			$shopaddress_list = parent::__get('HyDb')->get_all($shopaddress_sql);
			
			
			$inarr = array();
			
			foreach ($shopaddress_list as $keys => $vals){
				
				//获取商户的id
				if(is_numeric($shopaddress_list[$keys]['id'])) {
					array_push($inarr,$shopaddress_list[$keys]['id']);
				}
				
				//距离的展示
				$shopaddress[$shopaddress_list[$keys]['id']] = parent::getDistance($this->lat, $this->lng, $shopaddress_list[$keys]['lat'], $shopaddress_list[$keys]['lng'], $len_type = 2, $decimal = 2);
				
			}
			
			$inarr = array_unique($inarr);
			if(count($inarr)<=0){
					
				$where = 'siteid=0';
			}else{
				$instr = ' ('.implode(',',$inarr).') ';
				$where = ' siteid in '. $instr;
			}
			
			
			if($this->kindtype=='全部'){//查询全部的附近商家的优惠券
					
				$where = "onsales=1 and status=1 and flag=1  and $where and typeid not in (11,13,14,15) ";
					
			}else {//查询单个的
					
				$where = " onsales=1 and status=1 and flag=1  
							and $where and attribute = '".$this->kindtype."' and typeid not in (11,13,14,15) ";
			}
			
			
		}else{
			
			if($this->kindtype=='全部'){//查询全部的
					
				$where = "z='".$z."' and shstatus=11 and hyflag=1  and zflag=1
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' and new_datetime>='".date("Y-m-d",strtotime("-11 month"))."' ";
					
			}else{//查询单个的
					
				$where = "z='".$z."' and shstatus=11 and hyflag=1 and zflag=1 and maintype = '".$this->kindtype."'
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' and new_datetime>='".date("Y-m-d",strtotime("-11 month"))."' ";
			}
		}
		//$where = ' 1=1 ';
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined'){
			$this->page=1;
		}
			
		if($this->count=='' || $this->count=='undefined' || $this->count=='0'){
			$this->count=10;
		}
			
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->count;
			
		$returnarr = array();
			
		//分类数据的查询
		if($this->type=='3'){//查询商家的优惠券
			$typesql  = "select count(*) as num from shop_product where $where ";
			$typelist = parent::__get('HyDb')->get_all($typesql);
			
		}else{
			$typesql  = "select count(*) as num from z_tuanmainlist where $where ";
			$typelist = parent::__get('HyDb')->get_all($typesql);
		}
		
			
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
			
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//1-抓取优惠券
		if($this->type=='1'){
			
			$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
					yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan   
				 	from z_tuanmainlist where $where and faflag='9' order by bydiscount asc limit $firstpage,$pagesize ";
			
		//	echo $sqldata;
			
		}else if($this->type=='2'){//评价最高     //发布优惠券
			
			$sqldata = "select id,userid,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
					yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan  
				 	from z_tuanmainlist where $where and faflag='1' order by bydiscount asc limit $firstpage,$pagesize ";
			
			
		}else if($this->type=='3'){//最新发布      //商家优惠券
			
			$sqldata = "select id,name,price,yuanprice,score,mainpic,showpic1,showpic2,showpic3,showpic4,showpic5,
						xiangqingurl,round(price/yuanprice,2) as bydiscount  
				 	from shop_product where $where  order by bydiscount asc limit $firstpage,$pagesize ";
			
		}else if($this->type==''){
			
			$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
						yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
						phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan
						from z_tuanmainlist where $where and faflag='9' order by bydiscount asc limit $firstpage,$pagesize ";
		}
		
		
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		
		//点赞数据的查询
		$collectsql  = "select quanid,userid from xb_collection where flag='4' and userid = '".parent::__get('xb_userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
		$checktaskarr = array();
		
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		
		
		$con = 0;
		$conn = 0;
		foreach ($listdata as $keys=>$vals){
			
			
			
			if($listdata[$keys]['mainpic']){
				$listdata[$keys]['picurl'] = $listdata[$keys]['mainpic'];
			}
			
			if($listdata[$keys]['name']){
				
				$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['name']);//商品的名称
			}
			
			if($listdata[$keys]['price']=='0' || $listdata[$keys]['price']!=''){
				$listdata[$keys]['nowprice'] = isset($listdata[$keys]['price'])?$listdata[$keys]['price']:'0';//商品的名称
			}
			
			//跳转详情页tiaozhuanurl
			if($listdata[$keys]['xiangqingurl']!=''){
				
				$listdata[$keys]['tiaozhuanurl'] = $listdata[$keys]['xiangqingurl'];
			}
			
			
			$listdata[$keys]['gflag'] = '1';
			
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'1';
			
			++$con;
			
			if($con==1 && $this->page==1){
					
				$lunbotu_sql = "select * from ad_advertisement where maintype='1'";
				$lunbotu_list = parent::__get('HyDb')->get_row($lunbotu_sql);
				
					
				if(count($lunbotu_list)>0){//有广告存在可以进行插入
			
					$listdata[$keys]['gflag'] = '2';//广告类型1-优惠券 2-网页下载 3-下载广告
					if($lunbotu_list['gtype']=='1'){
						$lunbotu_list['gtype']='任务';
					}else if($lunbotu_list['gtype']=='2'){
						$lunbotu_list['gtype']='广告';
					}
					$listdata[$keys]['gtype'] = $lunbotu_list['gtype'];//1-任务 2-广告
					$listdata[$keys]['url'] = $lunbotu_list['picurl'];//图片链接
					$listdata[$keys]['adurl'] = $lunbotu_list['adurl'];//广告跳转链接
					$listdata[$keys]['taskid'] = $lunbotu_list['taskid'];//任务下载编号
					$listdata[$keys]['adtitle'] = $lunbotu_list['adtitle'];//广告标题
					$listdata[$keys]['adcontent'] = $lunbotu_list['adcontent'];//广告描述
					$listdata[$keys]['childtype'] = '广告';//广告
			
				}
			}
			
			if($listdata[$keys]['yuanprice']=='0'){
				$listdata[$keys]['discount']='0';
			}else{
				$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			}
			
			
			if(strlen($listdata[$keys]['discount'])=='1'){
				
				$listdata[$keys]['discount'] = $listdata[$keys]['discount'].'.0';
			}
			
			if($listdata[$keys]['discount']=='0'){
				
				if(strlen($listdata[$keys]['reamrk'])=='1'){
					$listdata[$keys]['discount'] = $listdata[$keys]['reamrk'].'.0';
				}
			}
			
			//$listdata[$keys]['over_datetime'] = date('Y年m月d日',$listdata[$keys]['over_datetime']/1000);
			if($listdata[$keys]['over_datetime']!=''){
				$listdata[$keys]['over_datetime'] = substr($listdata[$keys]['over_datetime'],0,10);
			}else{
				$listdata[$keys]['over_datetime']='';
			}
			
			
			//标题空格的去除
			$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['title']);
			
			if($listdata[$keys]['lat']=='' || $listdata[$keys]['lng']==''){
				
				$listdata[$keys]['distance'] = $shopaddress[$listdata[$keys]['id']];
				
			}else{
				$listdata[$keys]['distance'] = parent::getDistance($this->lat, $this->lng, $listdata[$keys]['lat'], $listdata[$keys]['lng'], $len_type = 2, $decimal = 2);
			}
			
			if($listdata[$keys]['distance']<1){
				
				$listdata[$keys]['distance'] = $listdata[$keys]['distance']*1000;
			}
			
			
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
			
			
			$temptaskid = $listdata[$keys]['id'];
				
			if(isset($checktaskarr[$temptaskid])){
				$listdata[$keys]['dflag'] = '1';//已点赞
			}else{
				$listdata[$keys]['dflag'] = '2';//未点赞
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
			$echoarr['returnmsg']  = '经度字段不能为空';
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
