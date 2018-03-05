<?php
/*
 * 用户积分变动详情
 */

class HyXb1016 extends HyXb{
	
	private $now_page;
	private $pagesize;
	private $imgwidth;
	private $imgheight;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->now_page = isset($input_data['page'])?$input_data['page']:'1';
		$this->pagesize = isset($input_data['pagesize'])?$input_data['pagesize']:'10';
	}
	
	
	protected function controller_exec1(){
		
		//分用户进行判断
		if(parent::__get('usertype')=='1'){
			$tablename = ' xb_user ';
			$tablescorename = 'xb_user_score';
		}else if(parent::__get('usertype')=='2'){
			$tablename = 'xb_temp_user';
			$tablescorename = 'xb_temp_user_score';
		}
		
		//查询该用户的总条数
		$usernumscore_sql  = "select count(*) as num from $tablescorename where userid='".parent::__get('userid')."' ";
		$usernumscore_list = parent::__get('HyDb')->get_one($usernumscore_sql);
		$pagearr = HyItems::hy_pagepage($this->now_page,$this->pagesize,$usernumscore_list);
		
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$userscore_sql  = "select id,type,score,gettime,getdescribe from $tablescorename
						where userid='".parent::__get('userid')."'
						order by id desc ".$pagelimit;
		//echo $userscore_sql;
		$userscore_list = parent::__get('HyDb')->get_all($userscore_sql);
		foreach ($userscore_list as $keyu=>$valu){
			$userscore_list[$keyu]['gettime'] = date('Y-m-d H:i:s',$userscore_list[$keyu]['gettime']);
			if($userscore_list[$keyu]['type']=='1'){
				$userscore_list[$keyu]['score'] = '+'.$userscore_list[$keyu]['score'];
			}else if($userscore_list[$keyu]['type']=='9'){
				$userscore_list[$keyu]['score'] = '-'.$userscore_list[$keyu]['score'];
			}
			
		}
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $userscore_list,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','用户积分变动数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
		
	}
	
	
	//操作入口
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