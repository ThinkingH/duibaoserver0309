<?php
/*
 * 推送信息获取
 */

class HyXb171 extends HyXb{
	
	private $count;
	private $page;
	
	
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
		
		$this->type  = isset($input_data['type'])?$input_data['type']:''; //1-获取留言和通知，各20条,3为更新为已读
		
		$this->dtype      = isset($input_data['dtype']) ? $input_data['dtype']:'c'; //m或c m--评论 c-回复
		$this->nowid      = isset($input_data['nowid']) ? $input_data['nowid']:'0'; //回复的id或评论的id
		
		
	}
	
	
	//信息的推送
	protected function controller_tuisongmessage(){
		
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
		
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		
		if($this->type==''){
			
			$usertype = parent::__get('xb_usertype');
			
			if($usertype=='1'){
					
				$tablename = 'xb_user_tuisong';
					
			}else if($usertype=='2'){
					
				$tablename = 'xb_temp_user_tuisong';
					
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该用户的用户类型参数错误，信息推送失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			$returnarr = array();
			
			//分页的实现
			$tuisongsumsql  = "select count(*) as num from $tablename where userid='".parent::__get('xb_userid')."' ";
			$tuisongsumsqlist = parent::__get('HyDb')->get_all($tuisongsumsql);
			
			if($tuisongsumsqlist[0]['num']>0){
				$returnarr['maxcon'] = $tuisongsumsqlist[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;//总条数
			}
			
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			
			$temptuisongsql  = "select id,type,status,taskid,message,create_inttime from $tablename where userid='".parent::__get('xb_userid')."'
				order by create_inttime desc";
			$temltuisonglist = parent::__get('HyDb')->get_all($temptuisongsql);
			
			foreach ($temltuisonglist as $keys => $vals){
				
				$temltuisonglist[$keys]['create_inttime'] = date('Y-m-d H:i:s',$temltuisonglist[$keys]['create_inttime']);
				
			}
			
			
			
		}else if($this->type=='1'){//留言数据的获取
			
			
			
			
			//留言数据---只取20条
			$sql_maindata  = "select * from xb_comment where touserid='".parent::__get('xb_userid')."' order by createtime desc limit 20";
			$list_maindata = parent::__get('HyDb')->get_all($sql_maindata);
			
			
			//回复数据---只取20条
			$sql_childdata = "select * from xb_subcomment where touserid='".parent::__get('xb_userid')."' order by createtime desc limit 20";
			$list_childdata = parent::__get('HyDb')->get_all($sql_childdata);
			
			
			$userarr = array(); //用户id
			$shoparr = array(); //券id
			
			foreach ($list_maindata as $keys => $vals){
				if($vals['fromuserid']!='') {
					array_push($userarr,$vals['fromuserid']);
				}
				if($vals['touserid']!='') {
					array_push($userarr,$vals['touserid']);
				}
				if($vals['quanid']!='') {
					array_push($shoparr,$vals['quanid']);
				}
				
				
			}
			foreach ($list_childdata as $keys => $vals){
				if($vals['fromuserid']!='') {
					array_push($userarr,$vals['fromuserid']);
				}
				if($vals['touserid']!='') {
					array_push($userarr,$vals['touserid']);
				}
				if($vals['quanid']!='') {
					array_push($shoparr,$vals['quanid']);
				}
				
				
			}
			
			
			//用户id
			$userarr = array_unique($userarr);
			if(count($userarr)<=0){
				$where_user = ' id=0 ';
			}else{
				$where_user = ' id in ('.implode(',',$userarr).') ';
			}
			//券
			$shoparr = array_unique($shoparr);
			if(count($shoparr)<=0){
				$where_shop = ' id=0 ';
			}else{
				$where_shop = ' id in ('.implode(',',$shoparr).') ';
			}
			
			
			$user_touxiangarr = array();
			$user_namearr     = array();
			$shop_dataarr     = array();
			
			
			//获取用户列表
			$usersql  = "select id,nickname,touxiang from xb_user where $where_user ";
			//echo $usersql;
			
			$userlist = parent::__get('HyDb')->get_all($usersql);
			
			foreach($userlist as $keys => $vals){
				$user_touxiangarr[$vals['id']]  = $vals['touxiang'];
				$user_namearr[$vals['id']]      = $vals['nickname'];
					
			}
			
			//商品信息的获取
			$shopinfo_sql  = "select id,picurl from z_tuanmainlist where $where_shop ";
			$shopinfo_list = parent::__get('HyDb')->get_all($shopinfo_sql);
			
			foreach ($shopinfo_list as $keys => $vals){
				$shop_dataarr[$vals['id']] = $vals['picurl'];
			}
			
			
			
			$temltuisonglist = array();
			
			foreach ($list_maindata as $keym => $valm){
				$list_maindata[$keym]['dtype'] = 'm';
				$list_maindata[$keym]['fromuserid'] = isset($user_namearr[$valm['userid']])?$user_namearr[$valm['userid']]:'';//留言者
				$list_maindata[$keym]['picurl']     = isset($shop_dataarr[$valm['quanid']])?$shop_dataarr[$valm['quanid']]:'';//
				
				array_push($temltuisonglist,$list_maindata[$keym]);
			}
			foreach ($list_childdata as $keym => $valm){
				$list_childdata[$keym]['dtype'] = 'c';
				$list_childdata[$keym]['fromuserid'] = isset($user_namearr[$valm['userid']])?$user_namearr[$valm['userid']]:'';//留言者
				$list_childdata[$keym]['picurl']     = isset($shop_dataarr[$valm['quanid']])?$shop_dataarr[$valm['quanid']]:'';//
				
				array_push($temltuisonglist,$list_childdata[$keym]);
				
			}
			
			
			
		}
		
		
		if(count($temltuisonglist)>0){
		
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $temltuisonglist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '获取为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	
	
	
	
	protected function func_messagestatusup(){
		
		
		if(''==$this->nowid) {
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '未传递数据标识id';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
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
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '未传递数据标识id';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}else {
			$instring = ' ('.implode(',',$newidarr).') ';
			
		}
		
		
		if('m'==$this->dtype) {
			$sql_update = "update xb_comment set readflag='1' where touserid='".parent::__get('xb_userid')."' and id in ".$instring;
			
		}else {
			$sql_update = "update xb_subcomment set readflag='1' where touserid='".parent::__get('xb_userid')."' and id in ".$instring;
			
			
		}
		
		
		//更新状态标为已读
		parent::__get('HyDb')->execute($sql_update);
		
		
		$echoarr = array();
		$echoarr['returncode'] = 'success';
		$echoarr['returnmsg']  = '状态更新成功';
		$echoarr['dataarr'] = array();
		$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
		parent::hy_log_str_add($logstr);
		echo json_encode($echoarr);
		return true;
		
		
		
		
	}
	
	
	
	
	
	//操作入口--推送信息获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		$shuzu = array('1','2');
		
		if(!in_array(parent::__get('xb_usertype'),$shuzu)){
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户积分记录列表获取失败';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//操作类型的判断
		if(parent::__get('xb_thetype')!='171'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
		if($this->type=='3') {
			//留言回复标为已读
			$this->func_messagestatusup();
			
			
		}else {
			//推送信息入口
			$this->controller_tuisongmessage();
			
			
		}
		
	
		
		return true;
	}
	
	
}