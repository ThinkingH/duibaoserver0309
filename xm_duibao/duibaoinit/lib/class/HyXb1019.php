<?php
/*
 * 推送和留言信息
 */

class HyXb1019 extends HyXb{
	
	private $pagesize; //
	private $page;
	private $type;
	private $dtype;
	private $nowid;
	 
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->type       = isset($input_data['type'])?$input_data['type']:''; //1-获取留言和通知，各20条,3为更新为已读
		$this->dtype      = isset($input_data['dtype']) ? $input_data['dtype']:'c'; //m或c m--评论 c-回复
		$this->nowid      = isset($input_data['nowid']) ? $input_data['nowid']:'0'; //回复的id或评论的id
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	}
	
	//推送消息
	protected function controller_exec1(){
		
		$usertype = parent::__get('usertype');
		
		if($usertype=='1'){
			$tablename = 'xb_user_tuisong';
		}else if($usertype=='2'){
			$tablename = 'xb_temp_user_tuisong';
		}
		
		//分页的实现
		$tuisongsumsql  = "select count(*) as num from $tablename where userid='".parent::__get('userid')."' ";
		$tuisongsumsqlist = parent::__get('HyDb')->get_one($tuisongsumsql);
		
		$selectpage = HyItems::hy_pagepage($this->page,$this->pagesize,$tuisongsumsqlist);
		
		$pagemsg   = $selectpage['pagemsg'];
		$pagelimit = $selectpage['pagelimit'];
		
		$temptuisongsql  = "select id,type,status,taskid,message,create_inttime
							from $tablename where userid='".parent::__get('userid')."'
							order by create_inttime desc".$pagelimit;
		$temltuisonglist = parent::__get('HyDb')->get_all($temptuisongsql);
			
		foreach ($temltuisonglist as $keys => $vals){
			$temltuisonglist[$keys]['create_inttime'] = date('Y-m-d H:i:s',$temltuisonglist[$keys]['create_inttime']);
		}
		
		$retarr = array(
				'pagemsg' => $pagemsg,
				'list' => $temltuisonglist,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$temltuisonglist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	//留言消息
	protected function controller_exec2(){
		
		//留言数据---只取20条
		$sql_maindata  = "select * from xb_comment where touserid='".parent::__get('userid')."' order by createtime desc limit 20";
		$list_maindata = parent::__get('HyDb')->get_all($sql_maindata);
		
		//回复数据---只取20条
		$sql_childdata = "select * from xb_subcomment where touserid='".parent::__get('userid')."' order by createtime desc limit 20";
		$list_childdata = parent::__get('HyDb')->get_all($sql_childdata);
		
		$userarr = array(); //用户id
		$shoparr = array(); //发布优惠券id
		
		foreach ($list_maindata as $keys => $vals){
			/* if($vals['fromuserid']!='') {
				array_push($userarr,$vals['fromuserid']);
			} */
			if($vals['touserid']!='') {
				array_push($userarr,$vals['touserid']);
			}
			if($vals['quanid']!='') {
				array_push($shoparr,$vals['quanid']);
			}
		}
		
		foreach ($list_childdata as $keys=>$vals){
			if($vals['fromuserid']!=''){
				array_push($userarr,$vals['fromuserid']);
			}
			if($vals['touserid']!=''){
				array_push($userarr,$vals['touserid']);
			}
			if($vals['quanid']!=''){
				array_push($shoparr,$vals['quanid']);
			}
		}
		
		
// 		$userarr = array_unique($userarr);
// 		if(count($userarr)<=0){
// 			$where_user =  ' id=0 ';
// 		}else{
// 			$where_user = 'id in ('.implode(',',$userarr).')';
// 		}
		
		$shoparr = array_unique($shoparr);
		if(count($shoparr)<=0){
			$where_shop = 'id=0';
		}else{
			$where_shop = 'id in ('.implode(',',$shoparr).')';
		}
		
		$user_namearr = parent::func_retsqluserdata($userarr,$imgwidth=50,$imgheight=50) ;
		
		//商品信息的获取
		$shopinfo_sql  = "select id,picurl from z_tuanmainlist where $where_shop ";
		$shopinfo_list = parent::__get('HyDb')->get_all($shopinfo_sql);
		
		foreach ($shopinfo_list as $keys => $vals){
			$shop_dataarr[$vals['id']] = $vals['picurl'];
		}
		
		
		$temltuisonglist = array();
		
		foreach ($list_maindata as $keym => $valm){
			$list_maindata[$keym]['dtype'] = 'm';//评论
			$list_maindata[$keym]['fromuserid'] = isset($user_namearr[$valm['userid']])?$user_namearr[$valm['userid']]:'';//留言者
			$list_maindata[$keym]['picurl']     = isset($shop_dataarr[$valm['quanid']])?$shop_dataarr[$valm['quanid']]:'';//
				
			array_push($temltuisonglist,$list_maindata[$keym]);
		}
		
		
		foreach ($list_childdata as $keym => $valm){
			$list_childdata[$keym]['dtype'] = 'c';//回复
			$list_childdata[$keym]['fromuserid'] = isset($user_namearr[$valm['userid']])?$user_namearr[$valm['userid']]:'';//留言者
			$list_childdata[$keym]['picurl']     = isset($shop_dataarr[$valm['quanid']])?$shop_dataarr[$valm['quanid']]:'';//
				
			array_push($temltuisonglist,$list_childdata[$keym]);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$temltuisonglist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	//留言信息读取状态
	protected function controller_exec3(){
		
		if(''==$this->nowid) {
			$echojsonstr = HyItems::echo2clientjson('334','未传递标识id');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		$newidarr = array();
		$nowidarr = explode(',',$this->nowid);
		foreach($nowidarr as $valn) {
			$valn = trim($valn);
			if(is_numeric($valn) && $valn>0) {
				array_push($newidarr,$valn);
			}
		}
		if(count($newidarr)<=0) {
			$echojsonstr = HyItems::echo2clientjson('334','未传递标识id');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}else {
			$instring = ' ('.implode(',',$newidarr).') ';
			
		}
		
		
		if('m'==$this->dtype) {
			$sql_update = "update xb_comment set readflag='1' where touserid='".parent::__get('userid')."' and id in ".$instring;
		}else {
			$sql_update = "update xb_subcomment set readflag='1' where touserid='".parent::__get('userid')."' and id in ".$instring;
		}
		parent::hy_log_str_add(HyItems::hy_trn2space($sql_update)."\n");
		//更新状态标为已读
		parent::__get('HyDb')->execute($sql_update);
		
		$echojsonstr = HyItems::echo2clientjson('100','状态更新成功');
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	//留言数量
	protected function controller_exec4(){
		
		//留言数据---只取20条
		$sql_maindata  = "select id,readflag from xb_comment where touserid='".parent::__get('userid')."' order by createtime desc limit 20";
		$list_maindata = parent::__get('HyDb')->get_all($sql_maindata);
		
		//回复数据---只取20条
		$sql_childdata = "select id,readflag from xb_subcomment where touserid='".parent::__get('userid')."' order by createtime desc limit 20";
		$list_childdata = parent::__get('HyDb')->get_all($sql_childdata);
		
		$noread_con  = 0;
		$isread_con  = 0;
		$allread_con = 0;
		
		foreach($list_maindata as $valm) {
			++$allread_con;
			if($valm['readflag']=='1') {
				++$isread_con;
			}else {
				++$noread_con;
			}
		}
		foreach($list_childdata as $valm) {
			++$allread_con;
			if($valm['readflag']=='1') {
				++$isread_con;
			}else {
				++$noread_con;
			}
		}
		
		$temltuisonglist = array(
				'allread_con' => $allread_con,
				'isread_con'  => $isread_con,
				'noread_con'  => $noread_con,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','留言数量获取成功',$temltuisonglist);
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
		
		if($this->type=='1'){//推送信息
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){//留言信息
			$ret = $this->controller_exec2();
		}else if($this->type=='3'){//留言回复标为已读
			$ret = $this->controller_exec3();
		}else if($this->type=='4'){//留言是否读取的数量
			$ret = $this->controller_exec4();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}