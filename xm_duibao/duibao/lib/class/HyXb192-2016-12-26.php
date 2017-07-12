<?php
/*
 * 任务积分领取
 */

class HyXb192 extends HyXb{
	
	private $taskid;//下载任务id
	private $normalusernum;//正常用户的下载次数
	private $normaluserscore; //正常用户可以下载的积分
	private $unnormalusernum;//非正常用户的下载次数
	private $unnormaluserscore;//非正常用户的下载积分
	
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
		$this->normalusernum = '30';        //正常用户每日的下载次数
		$this->normaluserscore = '1000';    //正常用户每日的下载最大积分数
		
		$this->unnormalusernum = '20';
		$this->unnormaluserscore = '800';
		
	
	}
	
	
	//积分的领取操作
	public function controller_usergetscore(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		//判断该用户是否下载过该游戏，下载过后就不可以载下载
		$paduanusersql = "select id from xb_list_taskscore where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' limit 1";
		$paduanuserlist = parent::__get('HyDb')->get_all($paduanusersql);
		
		if($paduanuserlist[0]['id']>0){//该游戏下载过，不可以在次领取积分
			
			//查询该游戏对应的积分
			$taskscoresql  = "select id,score,name,shuoming,mainimage from xb_task where id='".$this->taskid."'";
			$taskscorelist = parent::__get('HyDb')->get_row($taskscoresql);
			
			if($taskscorelist['id']<='0'){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该游戏不存在';
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
								'status'    => '2',
						)
				);
				
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '游戏下载完成';
				$echoarr['dataarr'] = $temparr;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}
			
			
		}else{
			//判断该用户在一天之内领取的次数，以及领取的积分数
			//今天凌晨的时间戳
			$nowtime = strtotime(date("Y-m-d",time()));
			//今天凌晨的时间
			$nowday  = date("Y-m-d",time());
			//今天24点时间戳
			$endtime = $nowtime+3600*24;
			//今天24点的时间
			$endday = date("Y-m-d H:i:s",$endtime); 
			
			$daynumsql = "select count(taskid) as taskidnum ,sum(score) as scorenum from xb_list_taskscore where 
					create_datetime>='".$nowday."' and create_datetime<='".$endday."' and userid='".parent::__get('xb_userid')."'";
			$daynumlist = parent::__get('HyDb')->get_all($daynumsql);
			
			if($usertype=='1'){
				
				if($daynumlist[0]['taskidnum']>$this->normalusernum){
					
					//下载游戏达到上限
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '今日下载游戏次数已达到上限';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}else{
					
					if($daynumlist[0]['scorenum']>$this->normaluserscore){
						
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '今日下载游戏领取的积分总额达到上限';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
						
					}else{
						
						//查询该游戏对应的积分
						$taskscoresql  = "select id,score,name,shuoming,mainimage from xb_task where id='".$this->taskid."'";
						$taskscorelist = parent::__get('HyDb')->get_row($taskscoresql);
						
						if($taskscorelist['id']<='0'){
							$echoarr = array();
							$echoarr['returncode'] = 'error';
							$echoarr['returnmsg']  = '该游戏不存在';
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
											'status'    => '3',
									)
							);
							
							//记录任务的领取情况入库
							$time = date("Y-m-d H:i:s",time());
							$insertscoretasksql = "insert into xb_list_taskscore (userid,taskid,score,create_datetime) 
									values ('".parent::__get('xb_userid')."','".$this->taskid."','".$taskscorelist['score']."','".$time."')";
							$insertscoretasklist = parent::__get('HyDb')->execute($insertscoretasksql);
							
							if($insertscoretasklist==false){
								$echoarr = array();
								$echoarr['returncode'] = 'error';
								$echoarr['returnmsg']  = '该游戏插入到积分记录列表失败';
								$echoarr['dataarr'] = array();
								$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
								parent::hy_log_str_add($logstr);
								echo json_encode($echoarr);
								return false;
								
							}else{
								//更新用户表中的数据
								$updateusertask = "update xb_user_task set status='3' where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' order by id desc limit 1";
								$updateusertasklist = parent::__get('HyDb')->execute($updateusertask);
								
								if($updateusertasklist==false){
									$echoarr = array();
									$echoarr['returncode'] = 'error';
									$echoarr['returnmsg']  = '任务积分领取失败';
									$echoarr['dataarr'] = array();
									$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
									parent::hy_log_str_add($logstr);
									echo json_encode($echoarr);
									return false;
									
								}else{//task任务表中积分领取次数的增加
									
									//更新任务表中的任务的下载次数
									$updatetasknum     = "update xb_task set downtimes = downtimes+1 where id='".$this->taskid."' ";
									$updatetasknumlist = parent::__get('HyDb')->execute($updatescorenum);
									
									//更新任务表中该积分领取的次数
									$updatescorenum     = "update xb_task set scoretimes = scoretimes+1 where id='".$this->taskid."' ";
									$updatescorenumlist = parent::__get('HyDb')->execute($updatescorenum);
									
									//更新用户表中的积分
									$updateuserscore_sql  = "update xb_user set keyong_jifen = keyong_jifen + '".$taskscorelist['score']."' where id='".parent::__get('xb_userid')."'";
									$updateuserscore_list = parent::__get('HyDb')->execute($updateuserscore_sql);
									
									if($updateuserscore_list==false){
										$echoarr = array();
										$echoarr['returncode'] = 'error';
										$echoarr['returnmsg']  = '用户表积分更新失败，积分领取失败';
										$echoarr['dataarr'] = array();
										$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
										parent::hy_log_str_add($logstr);
										echo json_encode($echoarr);
										return false;
										
									}else{
										//积分详情的插入
										$stime = time();
										$insertscore_sql = "insert into xb_user_score (userid,maintype,type,score,getdescribe,gettime) values 
												('".parent::__get('xb_userid')."','1','1','".$taskscorelist['score']."','下载获取的积分','".$stime."')";
										$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
										
										if($insertscore_list){
											
											$echoarr = array();
											$echoarr['returncode'] = 'success';
											$echoarr['returnmsg']  = '任务积分领取成功';
											$echoarr['dataarr'] = $temparr;
											$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
											parent::hy_log_str_add($logstr);
											echo json_encode($echoarr);
											return true;
											
											
										}else{
											$echoarr = array();
											$echoarr['returncode'] = 'error';
											$echoarr['returnmsg']  = '用户领取积分记录详情插入失败';
											$echoarr['dataarr'] = array();
											$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
											parent::hy_log_str_add($logstr);
											echo json_encode($echoarr);
											return false;
											
										}
										
									}
									
								}
								
							}
							
						}
						
						
					}
					
				}
				
				
				
				
			}else if($usertype=='2'){//非正常用户
				
				
				if($daynumlist[0]['taskidnum']>$this->unnormalusernum){
						
					//下载游戏达到上限
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '今日下载游戏次数已达到上限';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
						
				}else{
						
					if($daynumlist[0]['scorenum']>$this->unnormaluserscore){
				
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '今日下载游戏领取的积分总额达到上限';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
				
					}else{
				
						//查询该游戏对应的积分
						$taskscoresql  = "select id,score,name,shuoming,mainimage from xb_task where id='".$this->taskid."'";
						$taskscorelist = parent::__get('HyDb')->get_row($taskscoresql);
				
						if($taskscorelist['id']<='0'){
							$echoarr = array();
							$echoarr['returncode'] = 'error';
							$echoarr['returnmsg']  = '该游戏不存在';
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
											'status'    => '3',
									)
							);
								
							//记录任务的领取情况入库
							$time = date("Y-m-d H:i:s",time());
							$insertscoretasksql = "insert into xb_list_taskscore (userid,taskid,score,create_datetime)
									values ('".parent::__get('xb_userid')."','".$this->taskid."','".$taskscorelist['score']."','".$time."')";
							$insertscoretasklist = parent::__get('HyDb')->execute($insertscoretasksql);
								
							if($insertscoretasklist==false){
								$echoarr = array();
								$echoarr['returncode'] = 'error';
								$echoarr['returnmsg']  = '该游戏插入到积分记录列表失败';
								$echoarr['dataarr'] = array();
								$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
								parent::hy_log_str_add($logstr);
								echo json_encode($echoarr);
								return false;
				
							}else{
								//更新用户表中的数据
								$updateusertask = "update xb_temp_user_task set status='3' where userid='".parent::__get('xb_userid')."' and taskid='".$this->taskid."' order by id desc limit 1";
								$updateusertasklist = parent::__get('HyDb')->execute($updateusertask);
				
								if($updateusertasklist==false){
									$echoarr = array();
									$echoarr['returncode'] = 'error';
									$echoarr['returnmsg']  = '任务积分领取失败';
									$echoarr['dataarr'] = array();
									$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
									parent::hy_log_str_add($logstr);
									echo json_encode($echoarr);
									return false;
										
								}else{//task任务表中积分领取次数的增加
										
									//更新任务表中该积分领取的次数
									$updatescorenum     = "update xb_task set scoretimes = scoretimes+1 where id='".$this->taskid."' ";
									$updatescorenumlist = parent::__get('HyDb')->execute($updatescorenum);
										
									//更新用户表中的积分
									$updateuserscore_sql  = "update xb_temp_user set keyong_jifen = keyong_jifen + '".$taskscorelist['score']."' where id='".parent::__get('xb_userid')."'";
									$updateuserscore_list = parent::__get('HyDb')->execute($updateuserscore_sql);
										
									if($updateuserscore_list==false){
										$echoarr = array();
										$echoarr['returncode'] = 'error';
										$echoarr['returnmsg']  = '用户表积分更新失败，积分领取失败';
										$echoarr['dataarr'] = array();
										$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
										parent::hy_log_str_add($logstr);
										echo json_encode($echoarr);
										return false;
				
									}else{
										//积分详情的插入
										$stime = time();
										$insertscore_sql = "insert into xb_temp_user_score (userid,type,score,getdescribe,gettime) values
												('".parent::__get('xb_userid')."','1','".$taskscorelist['score']."','下载获取的积分','".$stime."')";
										$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
				
										if($insertscore_list){
												
											$echoarr = array();
											$echoarr['returncode'] = 'success';
											$echoarr['returnmsg']  = '任务积分领取成功';
											$echoarr['dataarr'] = $temparr;
											$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
											parent::hy_log_str_add($logstr);
											echo json_encode($echoarr);
											return true;
												
												
										}else{
											$echoarr = array();
											$echoarr['returncode'] = 'error';
											$echoarr['returnmsg']  = '用户领取积分记录详情插入失败';
											$echoarr['dataarr'] = array();
											$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
											parent::hy_log_str_add($logstr);
											echo json_encode($echoarr);
											return false;
												
										}
				
									}
										
								}
				
							}
								
						}
				
				
					}
						
				}
				
				
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
			
			
		}
		
		
		
	}
	
	
	//操作入口--任务积分领取
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
	
		//任务积分领取开始的入口
		$this->controller_usergetscore();
	
		return true;
	}
	
	
	
	
	
	
	
	
}