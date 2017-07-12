<?php
/*
 * 用户任务记录列表的获取--153
 */
class HyXb153 extends HyXb{
	
	private $count;//每页的条数，数值介于1到20之间
	private $page;//数据请求对应的页数
	
	
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
	
	
	//主要操作--用户任务记录列表的获取
	protected function controller_getusertask(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');

		if($this->page==''||$this->page=='0' || $this->page== 'undefined'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			
			$this->count = 10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$taskidarr       = array();
		$taskarr         = array();
		$taskimgarr      = array();
		$taskshuomingarr = array();
		$taskscorearr    = array();
		$returnarr       = array();
		$downtimesarr       = array();//下载次数
		$typearr = array();
		$flagarr = array();
		
		//当用户为正常用户
		if($usertype=='1'){
			
			
			//任务列表
			$tasklistsql = "select id,name,mainimage,shuoming,score,downtimes,type,flag,over_inttime from xb_task ";
			$tasklist    = parent::__get('HyDb')->get_all($tasklistsql);
			
			//***************************************************************************************8
			foreach ($tasklist as $val){
				
				$taskidarr[$val['id']]       = $val['id'];
				$taskarr[$val['id']]         = $val['name'];
				$taskimgarr[$val['id']]      = XMAINURL.$val['mainimage'];
				$taskshuomingarr[$val['id']] = $val['shuoming'];
				$taskscorearr[$val['id']]    = $val['score'];
				$downtimesarr[$val['id']]    = $val['downtimes'];
				$typearr[$val['id']]         = $val['type'];  
				$flagarr[$val['id']]         = $val['flag'];
				
				if(time()>$val['over_inttime']) {
					$flagarr[$val['id']]         = '9';
				}
			}
			
			//查询用户的总条数
			$usertasksql  = "select count(*) as num from xb_user_task where userid='".parent::__get('xb_userid')."' ";
			$usertasklist = parent::__get('HyDb')->get_all($usertasksql);
			
			if($usertasklist[0]['num']>0){
				$returnarr['maxcon'] = $usertasklist[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;
			}
			
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			
			//数据xb_user_task表的查询
			$usertask_sql = "select id,status,taskid from xb_user_task 
						where userid='".parent::__get('xb_userid')."' order by id desc limit $firstpage,$pagesize";
			$usertask_list = parent::__get('HyDb')->get_all($usertask_sql);
			
			
			foreach ($usertask_list as $key=>$val){
				
				$usertask_list[$key]['id']       = $taskidarr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['taskid']   = $taskidarr[$usertask_list[$key]['taskid']];
				//$usertask_list[$key]['status']   = $usertask_list[$key]['status'];
				
				$usertask_list[$key]['taskname'] = $taskarr[$usertask_list[$key]['taskid']];
				
				$usertask_list[$key]['mainimage']  = $taskimgarr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['shuoming']   = $taskshuomingarr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['score']      = $taskscorearr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['downtimes']      = $downtimesarr[$usertask_list[$key]['taskid']];
				
				$usertask_list[$key]['type']      = $typearr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['flag']      = $flagarr[$usertask_list[$key]['taskid']];
				
				if($usertask_list[$key]['status']=='1'){
					$usertask_list[$key]['status']= '11';//任务下载中或任务下载失败的状态--任务已领取
				}else if($usertask_list[$key]['status']=='2'){
					$usertask_list[$key]['status']= '22';//任务审核中
				}else if($usertask_list[$key]['status']=='4'){
					$usertask_list[$key]['status']= '44';//奖金已到账
				}else{
					$usertask_list[$key]['status']= '11'; //任务已领取
				}
				
			}
			
			if(count($usertask_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户任务记录列表的获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $usertask_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户任务记录列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else if($usertype=='2'){
			
			//任务列表
			$tasklistsql = "select id,name,mainimage,shuoming,score,downtimes,type,flag,over_inttime from xb_task ";
			$tasklist    = parent::__get('HyDb')->get_all($tasklistsql);
				
			foreach ($tasklist as $val){
				$taskarr[$val['id']] = $val['name'];
				
				$taskimgarr[$val['id']]      = XMAINURL.$val['mainimage'];
				$taskshuomingarr[$val['id']] = $val['shuoming'];
				$taskscorearr[$val['id']]    = $val['score'];
				$downtimesarr[$val['id']]    = $val['downtimes'];
				$typearr[$val['id']]         = $val['type'];
				$flagarr[$val['id']]         = $val['flag'];
				
				if(time()>$val['over_inttime']) {
					$flagarr[$val['id']]         = '9';
				}
				
				
				
			}
			
			//查询用户的总条数
			$usertasksql  = "select count(*) as num from xb_temp_user_task where userid='".parent::__get('xb_userid')."' ";
			$usertasklist = parent::__get('HyDb')->get_all($usertasksql);
				
			if($usertasklist[0]['num']>0){
				$returnarr['maxcon'] = $usertasklist[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;
			}
				
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
				
				
			//数据xb_user_task表的查询
			$usertask_sql = "select id,status,taskid from xb_temp_user_task
						where userid='".parent::__get('xb_userid')."' order by id desc limit $firstpage,$pagesize";
			$usertask_list = parent::__get('HyDb')->get_all($usertask_sql);
				
				
			foreach ($usertask_list as $key=>$val){
			
				$usertask_list[$key]['id']       = $usertask_list[$key]['id'];
				$usertask_list[$key]['taskid']   = $usertask_list[$key]['taskid'];
				//$usertask_list[$key]['status']   = $usertask_list[$key]['status'];
				$usertask_list[$key]['taskname'] = $taskarr[$usertask_list[$key]['taskid']];
				
				$usertask_list[$key]['mainimage']  = $taskimgarr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['shuoming']   = $taskshuomingarr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['score']      = $taskscorearr[$usertask_list[$key]['taskid']];
				
				$usertask_list[$key]['downtimes']      = $downtimesarr[$usertask_list[$key]['taskid']];
				
				$usertask_list[$key]['type']      = $typearr[$usertask_list[$key]['taskid']];
				$usertask_list[$key]['flag']      = $flagarr[$usertask_list[$key]['taskid']];
				
				if($usertask_list[$key]['status']=='1'){
					$usertask_list[$key]['status']= '11';//任务下载中或任务下载失败的状态--任务已领取
				}else if($usertask_list[$key]['status']=='2'){
					$usertask_list[$key]['status']= '22';//任务审核中
				}else if($usertask_list[$key]['status']=='4'){
					$usertask_list[$key]['status']= '44';//奖金已到账
				}else{
					$usertask_list[$key]['status']= '11'; //任务已领取
				}
				
				
			
			}
				
			if(count($usertask_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户任务记录列表的获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $usertask_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户任务记录列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，任务记录列表获取失败！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}
		
	}
	
	
	
	
	//操作入口--用户任务记录列表的获取
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户任务记录列表获取失败';
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
	
		//用户任务记录列表的获取入口
		$this->controller_getusertask();
	
		return true;
	}
	
	
	
	
	
	
	
	
}