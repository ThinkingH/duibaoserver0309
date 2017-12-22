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
		
		$this->type = isset($input_data['type'])? $input_data['type']:'';  ////优惠券的来源1-聚合优惠券 2-发布优惠券 3-商家优惠券
		$this->kindtype = isset($input_data['kindtype'])? $input_data['kindtype']:'';  //美食类型
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	//抓取优惠券排序
	public function controller_quanlist(){
		
		//读取抓取数据的类型
		$z = '1';
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		//查询的条件
		$where = '';
		
		if($this->kindtype=='全部'){
			$where = "z='".$z."' and hyflag=1 and flag=1 and zflag=1 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
		}else{
			
			$where = "z='".$z."' and hyflag=1 and flag=1 and zflag=1 and maintype = '".$this->kindtype."' 
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
		}
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined'){
			$this->page=1;
		}
			
		if($this->count=='' || $this->count=='undefined' || $this->count=='0'){
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
		
		$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
					yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan
					from z_tuanmainlist where $where and faflag='9' order by bydiscount asc limit $firstpage,$pagesize ";
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		foreach ($listdata as $keys=>$vals){
			
			
			$listdata[$keys]['gflag'] = '1';
				
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'1';
			
			if($listdata[$keys]['yuanprice']==0){
				$listdata[$keys]['discount']='0';
			}else{
				$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			}
			
			
			//标题空格的去除
			$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['title']);
				
			
			$listdata[$keys]['distance'] = parent::getDistance($this->lat, $this->lng, $listdata[$keys]['lat'], $listdata[$keys]['lng'], $len_type = 2, $decimal = 2);
			
				
			if($listdata[$keys]['distance']<1){
			
				$listdata[$keys]['distance'] = ($listdata[$keys]['distance']*1000).'米';
			}else{
				$listdata[$keys]['distance'] = $listdata[$keys]['distance'].'公里';
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
		}
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据获取成功!';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $listdata;
			$logstr = 'test--test0'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据为空!';
			$echoarr['dataarr'] = array();
			$logstr = 'test--test0'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
	}
	
	
	
	//发布优惠券排序
	public function controller_fabuquanlist(){
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		//查询的条件
		$where = '';
		
		if($this->kindtype=='全部'){
			$where = " hyflag=1 and flag=1 and zflag=1
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
		}else{
				
			$where = " hyflag=1 and flag=1 and zflag=1 and maintype = '".$this->kindtype."'
					and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."'";
		}
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined'){
			$this->page=1;
		}
			
		if($this->count=='' || $this->count=='undefined' || $this->count=='0'){
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
		
		$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
					yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan
					from z_tuanmainlist where $where and faflag='1' and shstatus='11' order by bydiscount asc limit $firstpage,$pagesize ";
		//echo $sqldata;
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		//点赞数据的查询
		$collectsql  = "select quanid,userid from xb_collection where flag='4' and userid = '".parent::__get('xb_userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
		$checktaskarr = array();
		
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		foreach ($listdata as $keys=>$vals){
				
			$listdata[$keys]['gflag'] = '1';
		
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'1';
				
			if($listdata[$keys]['yuanprice']==0){
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
				
				
			//标题空格的去除
			$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['title']);
		
				
			$listdata[$keys]['distance'] = parent::getDistance($this->lat, $this->lng, $listdata[$keys]['lat'], $listdata[$keys]['lng'], $len_type = 2, $decimal = 2);
				
		
			if($listdata[$keys]['distance']<1){
					
				$listdata[$keys]['distance'] = ($listdata[$keys]['distance']*1000).'米';
			}else{
				$listdata[$keys]['distance'] = $listdata[$keys]['distance'].'公里';
			}
			
			if($listdata[$keys]['over_datetime']!=''){
				$listdata[$keys]['over_datetime'] = substr($listdata[$keys]['over_datetime'],0,10);
			}else{
				$listdata[$keys]['over_datetime']='';
			}
			
			$temptaskid = $listdata[$keys]['id'];
			
			if(isset($checktaskarr[$temptaskid])){
				$listdata[$keys]['dflag'] = '1';//已点赞
			}else{
				$listdata[$keys]['dflag'] = '2';//未点赞
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
		}
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据获取成功!';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $listdata;
			$logstr = 'test--test1'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据为空!';
			$echoarr['dataarr'] = array();
			$logstr = 'test--test1'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
		
		
	}
	
	
	//商家发布优惠券拍讯
	public function controller_shangjiaquanlist(){
		
		//获取4个顶点的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		//查询的条件
		$where = '';
		
		$shopaddress = array();//距离商铺的距离
		$addressarr = array();//商铺地址
		
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
			$addressarr[$shopaddress_list[$keys]['id']] = isset($shopaddress_list[$keys]['address'])?$shopaddress_list[$keys]['address']:'';
		
		}
		
		$inarr = array_unique($inarr);
		if(count($inarr)<=0){
				
			$where = 'and siteid=0';
		}else{
			$instr = ' ('.implode(',',$inarr).') ';
			$where = ' and siteid in '. $instr;
		}
		
		//附近与商品之间的关联表fujin_product
		if($this->kindtype=='全部'){
			$guanlian_sql = "select name,typeid from fujin_product  ";
			$guanlian_list = parent::__get('HyDb')->get_all($guanlian_sql);
		}else{
			$guanlian_sql = "select name,typeid from fujin_product where type='".$this->kindtype."' ";
			$guanlian_list = parent::__get('HyDb')->get_all($guanlian_sql);
		}
		
		
		$typearr = array();//关联商品的类型
		
		foreach ($guanlian_list as $keys=>$vals){
			array_push($typearr,$guanlian_list[$keys]['typeid']);
		}
		
		$typearr = array_unique($typearr);
		
		if(count($typearr)<=0){
			$where .= '';
		}else{
			$typearr = ' ('.implode(',',$typearr).') ';
			$where .= ' and typeid in '. $typearr;
		}
		
		if($this->kindtype=='全部'){//查询全部的附近商家的优惠券
			$where = "onsales=1 and status=1 and flag=1  $where  ";
		}else {//查询单个的 
			$where = " onsales=1 and status=1 and flag=1 $where  ";
		}
		
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined'){
			$this->page=1;
		}
			
		if($this->count=='' || $this->count=='undefined' || $this->count=='0'){
			$this->count=10;
		}
			
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
			
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where $where ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
			
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		$sqldata = "select id, xushi_type,name,price,yuanprice,score,mainpic,siteid,feetype   
					from shop_product where $where  order by create_datetime desc limit $firstpage,$pagesize ";
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		foreach ($listdata as $keys=>$vals){//yuanprice nowprice
			
		if($this->width==''){//753 * 292
				$this->width='800';
			}
				
			if($this->height==''){
				$this->height='800';
			}
			
			
			$replace = array("\t", "\r", "\n",);
				
			//图片展示
			$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			if($listdata[$keys]['mainpic']!=null){
				if(substr($listdata[$keys]['mainpic'],0,7)=='http://' ||substr($listdata[$keys]['mainpic'],0,8)=='https://' ){
					$listdata[$keys]['mainpic'] = str_replace($replace, '', $listdata[$keys]['mainpic']);
				}else{
					$listdata[$keys]['mainpic'] = $arr['duibao-shop'].$listdata[$keys]['mainpic'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$listdata[$keys]['mainpic'] = str_replace($replace, '', $listdata[$keys]['mainpic']);
				}
				
			}
			
			
			//区分标识
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'99';
			
			if($listdata[$keys]['yuanprice']==null){
				$listdata[$keys]['yuanprice']='0';
			}
			
			if($listdata[$keys]['price']==null){
				$listdata[$keys]['price']='0';
			}
			
			if($listdata[$keys]['score']==null){
				$listdata[$keys]['score']='0';
			}
			 
			//标题feetype
			$listdata[$keys]['title'] = isset($listdata[$keys]['name'])?$listdata[$keys]['name']:'';
			
			if($listdata[$keys]['feetype']=='1'){//积分
				
				$listdata[$keys]['scoremoney']   = '¥'.number_format($listdata[$keys]['price'] /100, 2)+$listdata[$keys]['score'].'馅饼';
				
			}else if($listdata[$keys]['feetype']=='2'){//金额
				
				$listdata[$keys]['scoremoney'] = '¥'.number_format($listdata[$keys]['price'] /100, 2);
				
			}else if($listdata[$keys]['feetype']=='3'){//混合
				
				
				
			}else if($listdata[$keys]['feetype']=='4'){//会员免费
				
				$listdata[$keys]['scoremoney']='免费';
				
			}else if($listdata[$keys]['feetype']=='5'){//抽奖免费
				
				$listdata[$keys]['scoremoney']='免费';
				
			}
			
			$listdata[$keys]['gflag'] = '1';//广告字段1-优惠券 2-广告
			//价格
			$listdata[$keys]['price'] = number_format($listdata[$keys]['price'] /100, 2);
			
			//地址$addressarrstr_replace(' ','',$listdata[$keys]['title']);
			$listdata[$keys]['address'] = str_replace(' ','',$addressarr[$listdata[$keys]['siteid']]);
			
			//距离
			$listdata[$keys]['distance']  = isset($shopaddress[$listdata[$keys]['siteid']])?$shopaddress[$listdata[$keys]['siteid']]:'';
			
			if($listdata[$keys]['distance']<1){
					
				$listdata[$keys]['distance'] = ($listdata[$keys]['distance']*1000).'米';
			}else{
				$listdata[$keys]['distance'] = $listdata[$keys]['distance'].'公里';
			}
			
		}
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据获取成功!';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $listdata;
			$logstr = 'test--test2'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '抓取数据为空!';
			$echoarr['dataarr'] = array();
			$logstr = 'test--test2'.$echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
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
	 
	
		if($this->type=='1'){//抓取优惠券
			
			$this->controller_quanlist();
			
		}else if($this->type=='2'){//发布优惠券
			
			$this->controller_fabuquanlist();
			
			
		}else if($this->type=='3'){//商家优惠券
			
			$this->controller_shangjiaquanlist();
			
		}else{
			$this->controller_alllist();
		}
	
		return true;
		
	}
	
	
	
}
