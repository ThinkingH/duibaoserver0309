<?php
/*
 * 附近数据的获取
 */

class HyXb1022 extends HyXb{
	
// 	private $lat;
// 	private $lng;
// 	private $type;
	private $typename;
	private $pagesize;
	private $page;
	private $imgwidth;
	private $imgheight;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->lat = isset($input_data['lat'])? $input_data['lat']:'';  //纬度
		$this->lng = isset($input_data['lng'])? $input_data['lng']:'';  //经度
		
		$this->type = isset($input_data['type'])? $input_data['type']:'';  ////优惠券的来源1-聚合优惠券 2-发布优惠券 3-商家优惠券
		$this->typename = isset($input_data['typename'])? $input_data['typename']:'';  //美食类型
		
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	}
	
	
	//抓取优惠券排序
	public function controller_quanlist(){
		
		//查询的条件
		$where = '';
		
		//判断是抓取数据还是发布数据
		if($this->type=='1'){//优惠数据
			$where .='faflag=9 and ';
			$tablename = 'z_tuanmainlist';
		}else if($this->type=='2'){//发布数据
			$where .='faflag=1 and ';
			$tablename = 'z_fabulist';
		}
		
		//获取方圆3公里范围内的坐标点
		$squares = parent::returnSquarePoint($this->lng, $this->lat,3);
		
		if($this->typename=='全部'){
			$where .= "z='1' and hyflag=1 and flag=1  ".$squares;
		}else{
			$where .= "z='1' and hyflag=1 and flag=1  and maintype = '".$this->typename."' ".$squares;
		}
		
		//分类数据的查询
		$typesql  = "select count(*) as num from $tablename where $where ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist);
		
		$pagemsg   = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,
					yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan 
					from $tablename 
					where $where order by bydiscount asc ".$pagelimit;
		parent::hy_log_str_add($sqldata."\n");
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		foreach ($listdata as $keys=>$vals){
			
			$listdata[$keys]['scoremoney'] = parent::func_diffzhifutype('2',$listdata[$keys]['nowprice']);//积分的展示
			
			if($listdata[$keys]['yuanprice']==null){
				$listdata[$keys]['yuanprice']='0';
			}
			if($listdata[$keys]['bydiscount']==null){
				$listdata[$keys]['bydiscount']='0';
			}
				
			if($listdata[$keys]['nowprice']==null){
				$listdata[$keys]['nowprice']='0';
			}
				
			$listdata[$keys]['gflag'] = '1';//数据是优惠券还是广告 1-优惠券 2-广告
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'1';
			
			$listdata[$keys]['nowprice']     = parent::formatmoney($listdata[$keys]['nowprice']);//$listdata[$keys]['price']
			$listdata[$keys]['yuanprice'] = parent::formatmoney($listdata[$keys]['yuanprice']);
			
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
			
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $listdata,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
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
		$shopaddress_sql = "select id,address,lat,lng from shop_site where flag='1' and checkstatus='2' ".$squares;
		parent::hy_log_str_add($shopaddress_sql."\n");
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
			$where = ' and siteid=0 ';
		}else{
			$instr = ' ('.implode(',',$inarr).') ';
			$where = ' and siteid in '. $instr;
		}
		
		//附近与商品之间的关联表fujin_product
		if($this->typename=='全部'){
			$guanlian_sql = "select name,typeid from fujin_product  ";
			parent::hy_log_str_add($guanlian_sql."\n");
			$guanlian_list = parent::__get('HyDb')->get_all($guanlian_sql);
		}else{
			$guanlian_sql = "select name,typeid from fujin_product where type='".$this->typename."' ";
			parent::hy_log_str_add($guanlian_sql."\n");
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
		
		if($this->typename=='全部'){//查询全部的附近商家的优惠券
			$where = "onsales=1 and status=1 and flag=1  $where  ";
		}else {//查询单个的
			$where = " onsales=1 and status=1 and flag=1 $where  ";
		}
		
		//分类数据的查询
		$typesql  = "select count(*) as num from shop_product where $where ";
		parent::hy_log_str_add($typesql."\n");
		$typelist = parent::__get('HyDb')->get_one($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->now_page,$this->pagesize,$typelist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$sqldata = "select id, xushi_type,name,price,yuanprice,score,mainpic,siteid,feetype
					from shop_product where $where  order by create_datetime desc ".$pagelimit;
		parent::hy_log_str_add($sqldata."\n");
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		foreach ($listdata as $keys=>$vals){
			//区分标识
			$listdata[$keys]['faflag'] = isset($listdata[$keys]['faflag'])?$listdata[$keys]['faflag']:'99';
			$listdata[$keys]['scoremoney'] = parent::func_diffzhifutype($listdata[$keys]['feetype'],$listdata[$keys]['price'],$listdata[$keys]['score']);//积分的展示
			
			//距离
			$listdata[$keys]['distance']  = isset($shopaddress[$listdata[$keys]['siteid']])?$shopaddress[$listdata[$keys]['siteid']]:'';
			if($listdata[$keys]['distance']<1){
				$listdata[$keys]['distance'] = ($listdata[$keys]['distance']*1000).'米';
			}else{
				$listdata[$keys]['distance'] = $listdata[$keys]['distance'].'公里';
			}
			
			$listdata[$keys]['gflag'] = '1';//广告字段1-优惠券 2-广告
			//价格
			$listdata[$keys]['price']     = parent::formatmoney($listdata[$keys]['price']);//$listdata[$keys]['price']
			$listdata[$keys]['yuanprice'] = parent::formatmoney($listdata[$keys]['yuanprice']);
			$listdata[$keys]['address'] = str_replace(' ','',$addressarr[$listdata[$keys]['siteid']]);
			//图片展示
			$listdata[$keys]['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$listdata[$keys]['mainpic'],$this->imgwidth,$this->imgheight);
			if($listdata[$keys]['yuanprice']==0){
				$listdata[$keys]['discount']='0';
			}else{
				$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			}
			
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $listdata,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		
		//参数判断
 		$c = parent::func_latandlngcheck();
		if($c===false){
 			return false;
 		}
		
 		
 		//优惠券的来源1-聚合优惠券 2-发布优惠券 3-商家优惠券
		if($this->type=='1' || $this->type=='2' ){//抓取优惠券
			$ret = $this->controller_quanlist();
		}else if($this->type=='3'){//商家优惠券
			$ret = $this->controller_shangjiaquanlist();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型不能为空');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}