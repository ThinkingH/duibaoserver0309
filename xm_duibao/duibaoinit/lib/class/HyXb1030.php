<?php
/*
 * 饭票优惠券
 * 该接口进行数据的缓存
 */
class HyXb1030 extends HyXb{
	
	private $page; 
	private $pagesize; 
	private $imgwidth;
	private $imgheight;
	private $type; //1-热门饭票 2-饭票收藏列表
	private $proname; //搜索查询字段
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->proname = isset($input_data['proname'])?$input_data['proname']:'';
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //查询类型
	}
	
	
	public function controller_exec1(){
		
		//获取总条数
		$quansumsql  = "select count(*) as num from youhuiquan where flag=1 and youxiao='ok' and type='".$this->proname."' ";
		parent::hy_log_str_add($quansumsql."\n");
		//$quansumlist = parent::__get('HyDb')->get_one($quansumsql);
		$quansumlist = parent::func_runtime_sql_data($quansumsql);
		
		//查询该条件下的总条数
		$limitpagesum = isset($quansumlist[0]['num'])?$quansumlist[0]['num']:'0';
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$limitpagesum);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		
		//优惠券数据的查询
		$youhuiquansql  = "select * from youhuiquan where flag=1 and youxiao='ok' and type='".$this->proname."' order by id desc ".$pagelimit;
		parent::hy_log_str_add($youhuiquansql."\n");
		//$youhuiquanlist = parent::__get('HyDb')->get_all($youhuiquansql);
		$youhuiquanlist = parent::func_runtime_sql_data($youhuiquansql);
			
		//收藏数据的查询
		$collectsql  = "select quanid,userid from xb_collection where userid = '".parent::__get('userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		$checktaskarr = array();
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		foreach ($youhuiquanlist as $keys => $vals){
		
			$youhuiquanlist[$keys]['quanid'] = $youhuiquanlist[$keys]['id'];
		
			$temptaskid = $youhuiquanlist[$keys]['id'];
		
			if(isset($checktaskarr[$temptaskid])){
				$youhuiquanlist[$keys]['collect'] = '11';//已收藏
			}else{
				$youhuiquanlist[$keys]['collect'] = '22';//未收藏
			}
			
			
		
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $youhuiquanlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	public function controller_exec2(){
		
		//获取总条数
		$sqlcollect  = "select count(*) as num from xb_collection where flag=1 and userid='".parent::__get('userid')."' ";
		$listcollect = parent::__get('HyDb')->get_one($sqlcollect);
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$listcollect);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//查询收藏表
		$collectdatasql  = "select id,quanid from xb_collection where flag=1 and userid='".parent::__get('userid')."' ";
		parent::hy_log_str_add($collectdatasql."\n");
		$collectdatalist = parent::__get('HyDb')->get_all($collectdatasql);
		
		$collectidarr = array();
		foreach ($collectdatalist as $keys=>$vals){
			array_push($collectidarr,$vals['quanid']);
		}
		
		$collectidarr = array_unique($collectidarr);
		if(count($collectidarr)>0){
			$collectidarr = '('.implode(',',$collectidarr).')';
			$where = 'id in '.$collectidarr;
		}else{
			$where = 'id=0';
		}
		
		//查询优惠券表
		$youhuiquandatasql  = "select id,flag,tuijian,type,youxiao,youxiaoqi,jiage,imgurl,title,content,theurl from youhuiquan where ".$where . $pagelimit;
		parent::hy_log_str_add($youhuiquandatasql."\n");
		$youhuiquandatalist = parent::__get('HyDb')->get_all($youhuiquandatasql);
		foreach ($youhuiquandatalist as $key=>$val){
			$youhuiquandatalist[$key]['collect']  = '11';//已收藏
		}
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $youhuiquandatalist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	public function controller_init(){
		
		if($this->type=='1'){//展示全部数据
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){//饭票收藏列表
			$ret = $this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		return $ret;
	}
	
}