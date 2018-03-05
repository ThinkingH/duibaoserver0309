<?php
/*
 * 首页数据查询（查询抓取淘宝联盟的数据）
 */
class HyXb1029 extends HyXb{
	
	private $proname; //搜索查询字段
	private $page; 
	private $pagesize; 
	private $imgwidth;
	private $imgheight;
	private $type; //查询数据类型 1-父类 2-子类
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->proname = isset($input_data['proname'])?$input_data['proname']:'';
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //查询类型
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
	}
	
	
	public function controller_exec1(){
		
		if($this->imgwidth==''){
			$this->imgwidth='600';
		}
		if($this->imgheight==''){
			$this->imgheight='200';
		}
		
		$sql_where = " new_datetime>='".date("Y-m-d H:i:s",strtotime("-12 month"))."' and new_datetime<='".date('Y-m-d H:i:s')."' and ";
		
		if($this->type=='1'){//主类查询
			$sql_where .= " flag=1 and maintype='".$this->proname."' ";
		}else if($this->type=='2'){//子类查询
			$sql_where .= " flag=1 and childtype='".$this->proname."' ";
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		//分类数据的查询
		$typesql  = "select count(*) as num from z_quantaobaoke where $sql_where ";
		//$typelist = parent::__get('HyDb')->get_one($typesql);
		$typelist = parent::func_runtime_sql_data($typesql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$typelist[0]['num']);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		//商品数据列表的获取
		$shangpinsql  = "select id,hyflag,new_datetime,type,maintype,childtype,title,picurl,spicurl,quanurl,quanprice,
						yuanprice,nowprice from z_quantaobaoke
						where $sql_where
						order by new_datetime desc,id desc  ".$pagelimit;
		//echo $shangpinsql;
		parent::hy_log_str_add(HyItems::hy_trn2space($shangpinsql)."\n");
		//$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql);
		$shangpinlist = parent::func_runtime_sql_data($shangpinsql);
		
		//print_r($shnagpinlist);
		
		$con=0;//条数的判断
		foreach ($shangpinlist as $keys=>$vals){
			
			$shangpinlist[$keys]['gflag'] = '1';
			
			++$con;//条数的增加
			if($con=='10' ||$con=='20' ){
				if($con=='10'){
					$type='2';
				}else{
					$type='3';
				}
				$page = $this->page-1;
				$dataarr = parent::func_advertisement($page,$type);
			
				if($dataarr['id']>0){//有广告存在可以进行插入
			
					$shangpinlist[$keys]['gflag'] = $dataarr['gflag'];//广告类型1-优惠券 2-网页下载 3-下载广告
						
					if($dataarr['gtype']=='1'){
						$dataarr['gtype']='任务';
					}else if($dataarr['gtype']=='2'){
						$dataarr['gtype']='广告';
					}
						
					$shangpinlist[$keys]['gtype'] = $dataarr['gtype'];//1-任务 2-广告
					$shangpinlist[$keys]['url'] = HyItems::hy_qiniuimgurl('duibao-basic',$dataarr['picurl'],$this->imgwidth,$this->imgheight);;//图片链接
					$shangpinlist[$keys]['adurl'] = $dataarr['adurl'];//广告跳转链接
					$shangpinlist[$keys]['taskid'] = $dataarr['taskid'];//任务下载编号
					$shangpinlist[$keys]['adtitle'] = $dataarr['adtitle'];//广告标题
					$shangpinlist[$keys]['adcontent'] = $dataarr['adcontent'];//广告描述
					$shangpinlist[$keys]['childtype'] = '广告';//广告导航栏
			
				}
			
			}
			
		}
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $shangpinlist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}