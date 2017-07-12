<?php

/*
 * 用户下载完成调用
 * 1.判断该用户是否下载该游戏，下载过直接返回
 * 2.判断该用户当天下载的此时
 * 3.判断该用户当天下载获取的积分总和的最大值
 * 4.以上判断通过，调用193 194 进行积分的增加
 * 5.193--预留审核
 * 6.194--积分的增加
 */

class HyXb192 extends HyXb{
	
	private $taskid;//下载任务id
	
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
	
	
	//领取积分前的判断
	public function controller_usergetscore(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		//查询该游戏对应的积分
		$taskscoresql  = "select id,score,name,shuoming,mainimage from xb_task where id='".$this->taskid."'";
		$taskscorelist = parent::__get('HyDb')->get_row($taskscoresql);
		if($taskscorelist['id']<=0){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该游戏已不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}else{
			//判断该用户是否下载过该游戏，下载过后就不可以载下载，直接返回
			$paduanusersql = "select id from xb_list_taskscore where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' and flag='4' limit 1";
			$paduanuserlist = parent::__get('HyDb')->get_all($paduanusersql);
			if(count($paduanuserlist)>0){//说明该用户下载过该游戏，不可以重复下载
				//更新任务表中的任务的下载次数
				$downloadnum     = "update xb_task set downtimes = downtimes+1 where id='".$this->taskid."' ";
				$downloadnumlist = parent::__get('HyDb')->execute($downloadnum);
					
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '游戏下载成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{//该用户未下载过该游戏,进行游戏的下载
				
				//分用户进行判断
				if($usertype=='1'){
				
					$insertscoretablename = 'xb_list_taskscore'; //任务记录表
					$taskgettablename = 'xb_user_task';
					$tasktablename    = 'xb_task';
					$tablename = 'xb_user';
					$tablescorename = 'xb_user_score';
					$tabletuisongname = 'xb_user_tuisong';
				}else if($usertype=='2'){
				
					$insertscoretablename = 'xb_list_taskscore'; //任务记录表
					$taskgettablename = 'xb_temp_user_task';
					$tasktablename    = 'xb_task';
					$tablename = 'xb_temp_user';
					$tablescorename = 'xb_temp_user_score';
					$tabletuisongname = 'xb_temp_user_tuisong';
				}
				
				
				//记录任务的领取情况入库//更新task_list_score表中status的状态
				$time = date("Y-m-d H:i:s",time());
				$insertscoretasksql = "update $insertscoretablename set flag=4,score='".$taskscorelist['score']."' where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."'";
				$insertscoretasklist = parent::__get('HyDb')->execute($insertscoretasksql);
				
				//更新任务表中的任务的下载次数
				$updatetasknum     = "update $tasktablename set downtimes = downtimes+1,scoretimes = scoretimes+1 where id='".$this->taskid."' ";
				$updatetasknumlist = parent::__get('HyDb')->execute($updatetasknum);
				
				
				//更新任务表中的status字段为4，代表积分的领取，前端返回为44--代表积分已领取
				$updateusertaskscore = "update $taskgettablename set status='4' where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' order by id desc limit 1";
				$updateusertasklist = parent::__get('HyDb')->execute($updateusertaskscore);
				
				
				//更新用户表中的积分
				$updateuserscore_sql  = "update $tablename set keyong_jifen = keyong_jifen + '".$taskscorelist['score']."' where id='".parent::__get('xb_userid')."'";
				$updateuserscore_list = parent::__get('HyDb')->execute($updateuserscore_sql);
				
				//获取用户的jiguangid
				$selectphone_sql  = "select id,jiguangid from $tablename where id='".parent::__get('xb_userid')."' ";
				$selectphone_list = parent::__get('HyDb')->get_row($selectphone_sql);
				
				if($selectphone_list['id']>0){
					//极光推送id
					$jiguangid = $selectphone_list['jiguangid'];
				}
				
				$messagee = '《'.$taskscorelist['name'].'》任务奖金已经到账，快去看看吧！';
		
				$retsend = parent::func_jgpush($jiguangid,$messagee);
		
				//推送信息的插入
				$stime = time();
				$tuisongdata_sql = "insert into $tabletuisongname (userid,type,status,taskid,message,create_inttime,remark)
									values ('".parent::__get('xb_userid')."','2','1','".$this->taskid."','".$messagee."','".$stime."','".$retsend."')";
				$tuisongdata_list = parent::__get('HyDb')->execute($tuisongdata_sql);
				
				
				//积分详情的插入
				$stime = time();
				$getdescribe = '下载《'.$taskscorelist['name'].'》获取'.$taskscorelist['score'].'馅饼';
				$insertscore_sql = "insert into $tablescorename (userid,maintype,type,score,getdescribe,gettime) values 
									('".parent::__get('xb_userid')."','1','1','".$taskscorelist['score']."','".$getdescribe."','".$stime."')";
				$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
				
				if($updateuserscore_list){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '积分领取成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '积分领取失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
				
			}
			
		}
		
	}
	
	
	
	
	
	
	//操作入口--任务下载完成调用入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='192'){
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，任务积分领取失败';
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
	
		
		$this->controller_usergetscore();
	
		return true;
	}
	
	
	
}