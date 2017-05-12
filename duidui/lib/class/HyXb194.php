<?php

/*
 * 积分的领取
 * 1.状态字段的更新--status=4
 * 2.积分的增加
 * 3.积分详情记录的插入
 */
class HyXb194 extends HyXb{
	
	
	private $taskid;//下载任务id
	private $push;
	
	
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
		
		//极光发送的初始化
		$this->push = new JiPush();
	
		$this->taskid = isset($input_data['taskid'])? $input_data['taskid']:'';  //下载任务id
	}
	
	
	
	public function controller_useraddscore(){
		
		
		//用户登录类型,分为正常用户--1和匿名用户--2两种
		$usertype = parent::__get('xb_usertype');
		
		//查询该游戏对应的积分
		$taskscoresql  = "select id,score,name,shuoming,mainimage from xb_task where id='".$this->taskid."'";
		$taskscorelist = parent::__get('HyDb')->get_row($taskscoresql);
		
		if($taskscorelist['id']<='0'){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该游戏已不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}else{
			
			//获取该任务的详细信息
			$temparr = array(
					array(
						'score'     => $taskscorelist['score'],
						'name'      => $taskscorelist['name'],
						'shuoming'  => $taskscorelist['shuoming'],
						'mainimage' => $taskscorelist['mainimage'],
						'status'    => '44',   //代表积分已领取
					)
			);
		}
		
		
		
		if($usertype=='1'){
			
			$tablename = 'xb_user';
			$tablescorename = 'xb_user_score';
			$taskgettablename = 'xb_user_task';
			$tabletuisongname = 'xb_user_tuisong';
			
		}else if($usertype=='2'){
			
			$tablename = 'xb_temp_user';
			$tablescorename = 'xb_temp_user_score';
			$taskgettablename = 'xb_temp_user_task';
			$tabletuisongname = 'xb_temp_user_tuisong';
			
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，积分领取失败！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//更新任务表中的status字段为4，代表积分的领取，前端返回为44--代表积分已领取
		$updateusertaskscore = "update $taskgettablename set status='4' where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' order by id desc limit 1";
		$updateusertasklist = parent::__get('HyDb')->execute($updateusertaskscore);
		
		
		//更新用户表中的积分
		$updateuserscore_sql  = "update $tablename set keyong_jifen = keyong_jifen + '".$taskscorelist['score']."' where id='".parent::__get('xb_userid')."'";
		$updateuserscore_list = parent::__get('HyDb')->execute($updateuserscore_sql);
		
		
		
		//只有正常用户才会进行积分的增加
		if($usertype=='1'){
				
			//更新tpshop用户表中的积分
			$selectphone_sql  = "select id,phone,jiguangid from xb_user where id='".parent::__get('xb_userid')."' ";
			$selectphone_list = parent::__get('HyDb')->get_row($selectphone_sql);
				
			if($selectphone_list['id']>0){
				
				//极光推送id
				$jiguangid = $selectphone_list['jiguangid'];
		
				//更新商城用户表中的积分
				$updatetpshop_sql = "update duibaoshop.tp_users set pay_points = pay_points + '".$taskscorelist['score']."' where mobile = '".$selectphone_list['phone']."'";
				$updatetpshop_list = parent::__get('HyDb')->execute($updatetpshop_sql);
			}
		//临时用户的推送		
		}else if($usertype=='2'){
			//读取临时表中的激光id
			$tempuser_sql = "select id,jiguangid,tokenkey from xb_temp_user where id='".parent::__get('xb_userid')."'";
			$tempuser_list = parent::__get('HyDb')->get_row($tempuser_sql);
			
			if(count($tempuser_list)>0){
				
				//极光推送id
				$jiguangid = $tempuser_list['jiguangid'];
			}
			
		}
		
		
		$messagee = '《'.$taskscorelist['name'].'》任务奖金已经到账，快去看看吧！';
		
		$retsend = parent::func_jgpush($jiguangid,$messagee);
		
		//推送信息的插入
		$stime = time();
		$tuisongdata_sql = "insert into $tabletuisongname (userid,type,status,taskid,message,create_inttime,remark)
		values ('".parent::__get('xb_userid')."','2','1','".$this->taskid."','".$messagee."','".$stime."','".$retsend."')";
				$tuisongdata_list = parent::__get('HyDb')->execute($tuisongdata_sql);
		
		
		if($updateuserscore_list===false){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '积分领取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}else{
			
			//积分详情的插入
			$stime = time();
			$getdescribe = '下载《'.$taskscorelist['name'].'》获取'.$taskscorelist['score'].'积分';
			$insertscore_sql = "insert into $tablescorename (userid,maintype,type,score,getdescribe,gettime) values
												('".parent::__get('xb_userid')."','1','1','".$taskscorelist['score']."','".$getdescribe."','".$stime."')";
			$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
			
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '积分领取成功';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
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
		if(parent::__get('xb_thetype')!='194'){
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
	
	
		$this->controller_useraddscore();
	
		return true;
	}
	
	
}