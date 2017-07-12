<?php
/*
 * 用户任务下载的开始
 */

class HyXb191 extends HyXb{
	
	private $taskid;//下载任务id
	private $normalusernum;
	private $normaluserscore;
	
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
	
		$this->taskid = isset($input_data['taskid'])? $input_data['taskid']:'';  //下载任务id
		
	}
	
	//任务下载开始--插入xb_user_task status=1
	public function controller_downloadscore(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		$temparr = array(
				array(
						'status' => '1'
				)
		);
		
		
		//限量数据的查询
		$download_sql = "select normalusernum,normaluserscore,unnormalusernum,unnormaluserscore from xb_config";
		$download_list = parent::__get('HyDb')->get_row($download_sql);
		
		
		//判断该用户在一天之内领取的次数，以及领取的积分数
		$nowtime = strtotime(date("Y-m-d",time()));//今天凌晨的时间戳
		$nowday  = date("Y-m-d",time());//今天凌晨的时间
		$endtime = $nowtime+3600*24;   //今天24点时间戳
		$endday = date("Y-m-d H:i:s",$endtime);//今天24点的时间
		
		//判断该用户当天的下载次数
		$daynumsql = "select count(taskid) as taskidnum ,sum(score) as scorenum from xb_list_taskscore where
					create_datetime>='".$nowday."' and create_datetime<='".$endday."' and userid='".parent::__get('xb_userid')."'";
		$daynumlist = parent::__get('HyDb')->get_row($daynumsql);
		
		
		if($usertype=='1'){
			
			$this->normalusernum   = $download_list['normalusernum']; 
			$this->normaluserscore = $download_list['normaluserscore'];
			$tablename = 'xb_user_task';
			
			if($daynumlist['taskidnum']>$this->normalusernum){
					
				//下载游戏达到上限
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '今日下载游戏次数已达到上限，请明日在来';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			if($daynumlist['scorenum']>$this->normaluserscore){
					
				//下载游戏达到上限
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '今日下载游戏领取的积分总额达到上限，请明日在来';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
		}else if($usertype=='2'){
			
			$this->normalusernum   = $download_list['unnormalusernum'];
			$this->normaluserscore = $download_list['unnormaluserscore'];
			$tablename = 'xb_temp_user_task';
			
			if($daynumlist['taskidnum']>$this->normalusernum){
					
				//下载游戏达到上限
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '今日下载游戏次数已达到上限，请进行登录';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
				
			if($daynumlist['scorenum']>$this->normaluserscore){
					
				//下载游戏达到上限
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '今日下载游戏领取的积分总额达到上限，请进行登录';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户任务下载失败！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		//判断该用户是否下载过，下载过不可以在重复插入
		$paduanusersql = "select id from xb_list_taskscore where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' limit 1";
		$paduanuserlist = parent::__get('HyDb')->get_row($paduanusersql);
		
		
		if($paduanuserlist['id']>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '游戏下载开始';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			
			//记录表的插入
			$scoretime = date('Y-m-d H:i:s');
			$userscoresql = "insert into xb_list_taskscore (userid,taskid,create_datetime) values ('".parent::__get('xb_userid')."','".$this->taskid."','".$scoretime."')";
			$userscorelist = parent::__get('HyDb')->execute($userscoresql);
			
			
			//数据插入表中
			$time = date('Y-m-d H:i:s');
			$usertasksql = "insert into $tablename (userid,status,taskid,flag,remark,create_datetime) values ('".parent::__get('xb_userid')."','1','".$this->taskid."','1','游戏下载','".$time."')";
			$usertasklist = parent::__get('HyDb')->execute($usertasksql);
			
			if($usertasklist){
						
						
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '游戏下载开始';
				$echoarr['dataarr'] = $temparr;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
							
			}else{
						
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '游戏下载失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
								
			}
		}
		
	}
	
	
	
	
	//操作入口--下载任务的开始
	public function controller_init(){
		
		
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='191'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		$shuzu = array('1','2');
		
		if(!in_array(parent::__get('xb_usertype'),$shuzu)){
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户任务下载失败';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//任务taskid的判断
		if($this->taskid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '传递的任务id为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//下载任务的开始的入口
		$this->controller_downloadscore();
	
		return true;
	}
	
}