<?php
/*
 * 任务列表获取--161
 */
class HyXb161 extends HyXb{
	
	
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
	
	}
	
	
	
	
	//任务列表获取操作
	protected function controller_gettasklist(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from xb_task ";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		if($usertype=='1'){
			$tablename = 'xb_user_task';
			
		}else if($usertype=='2'){
			
			$tablename = 'xb_temp_user_task';
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		$tasklist_sql = "select id,flag,type,over_inttime,score,downtimes,scoretimes,name,shuoming,mainimage 
						from xb_task order by id desc limit $firstpage,$pagesize";
		$tasklist    = parent::__get('HyDb')->get_all($tasklist_sql);
		
		foreach ($tasklist as $keys => $vals){
			
			$seltaskdata_sql  = "select status from $tablename where userid = '".parent::__get('xb_userid')."' and taskid = '".$tasklist[$keys]['id']."'";
			$seltaskdata_list = parent::__get('HyDb')->get_row($seltaskdata_sql);
			
			if($seltaskdata_list['status']>0){
				
				$statusdata = array(
						'status'=> $seltaskdata_list['status'],
				);
				
				array_push($tasklist,$statusdata);
			}
		}
		
		
		
		if(count($tasklist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '任务列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $tasklist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '任务列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false; 
		}
		
	}
	
	
	
	
	
	
	//操作入口--任务列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='161'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
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
		
	
		//任务列表的获取入口
		$this->controller_gettasklist();
	
		return true;
	}
	
	
	
}
