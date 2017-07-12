<?php
/*
 * 用户登录--提交验证码
 * 临时用户转为正常用户
 */

class HyXb112 extends HyXb{
	
	
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
	
	
		//接受验证码的手机号
		$this->phone = isset($input_data['phone']) ? $input_data['phone']:'';  //
	
		//接受验证码
		$this->vcode = isset($input_data['vcode']) ? $input_data['vcode']:'';  //
	
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
	
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
	}
	
	
	//校验验证码操作
	protected function controller_checkcode(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		//随机生成的userkey
		$userkey = parent::func_create_randkey();
		
		//验证码校验函数
		$r = parent::func_vcode_check($type='1',$this->phone,$this->vcode);
		
		
		if($usertype=='3'){//正常用户的注册
			
			if($r===true){
				
				//判断该用户是否注册过
				$userregistersql  = "select id,tokenkey from xb_user where phone='".$this->phone."'";
				$userregisterlist = parent::__get('HyDb')->get_all($userregistersql);
				
				if(count($userregisterlist)>0){
						
					$temparr = array(
							array(
									'userid' => $userregisterlist[0]['id'],
									'userkey'=> $userregisterlist[0]['tokenkey'],
							),
								
					);
					
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '用户登录成功';
					$echoarr['dataarr'] = $temparr;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
					
				}else{//该用户首次登录，数据插入到用户表中
					
					//数据的插入
					$userdatasql = "insert into xb_user (phone,tokenkey,create_datetime,keyong_jifen)
					values ('".$this->phone."','".$userkey."','".parent::__get('create_datetime')."','0')";
					$userdatalist = parent::__get('HyDb')->execute($userdatasql);
						
					/* $useridsql = "select id,tokenkey,jiguangid from xb_user where phone='".$this->phone."' and create_datetime>='".date('Y-m-d H:i:s',(time()-3*24*60*60))."'";
					$useridlist = parent::__get('HyDb')->get_row($useridsql);
					
					$jiguangid = $useridlist['jiguangid'];
					$userid    = $useridlist['id'];
					
					//极光推送
					$message = '恭喜注册成功获取300馅饼，请注意查看';
					
					//推送是我记录
					$time =time();
					$tuisongsql = "insert into xb_user_tuisong (userid,type,status,message,create_inttime)
							values ('".$userid."','1','2','".$message."','".$time."')";
					$tuisonglist = parent::__get('HyDb')->execute($tuisongsql); */
						
					//parent::func_jgpush($jiguangid,$message);
					
					
					if(count($useridlist)>0){
							
						$temparr = array(
								array(
										'userid' => $useridlist['id'],
										'userkey'=> $useridlist['tokenkey'],
								),
						);
							
						$echoarr = array();
						$echoarr['returncode'] = 'success';
						$echoarr['returnmsg']  = '用户登录成功';
						$echoarr['dataarr'] = $temparr;
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return true;
							
					}else{
							
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '用户登录失败';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
					}
					
				}
				
			}else{
				
			}
			
			
		}else if($usertype=='2'){//临时用户转为正常用户
			
			
			if($r===true){
				
				//判断该手机号是否注册过
				$userphonesql  = "select id,tokenkey from xb_user where phone='".$this->phone."' limit 1";
				$userphonelist = parent::__get('HyDb')->get_row($userphonesql);
				
				if($userphonelist['id']>0){//该手机号注册过，不更新临时用户的数据到正式用户表中
					
					$trueuserid  = $userphonelist['id'];
					$trueuserkey = $userphonelist['tokenkey'];
					
					$temparr = array(
							array(
									'userid'  => $trueuserid,
									'userkey' => $trueuserkey,
							),
					);
					
					
				}else{
					//没有注册，数据从新插入到用户表中，并同时把临时用户获取的积分增加上去
					$userdatasql = "insert into xb_user (phone,tokenkey,create_datetime,keyong_jifen) values ('".$this->phone."','".$userkey."','".parent::__get('create_datetime')."','300')";
					$userdatalist = parent::__get('HyDb')->execute($userdatasql);
					
					if($userdatalist){
						
						//读取转为正式用户的id
						$zhengshiuser_sql  = "select id,tokenkey,jiguangid from xb_user where phone='".$this->phone."' order by id desc limit 1";
						$zhengshiuser_list = parent::__get('HyDb')->get_row($zhengshiuser_sql);
						
						$trueuserid  = $zhengshiuser_list['id'];
						$trueuserkey = $zhengshiuser_list['tokenkey'];
						$jiguangid   = $zhengshiuser_list['jiguangid'];
						
					}else{
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '用户登录失败';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
					}
					
					
					
					//1.临时任务表中的数据更新 id userid status taskid flag remark create_datetime
					$taskuser_sql  = "select * from xb_temp_user_task where userid='".parent::__get('xb_userid')."'";
					$taskuser_list = parent::__get('HyDb')->get_all($taskuser_sql);
						
					if(count($taskuser_list)>0){
					
						foreach ($taskuser_list as $key => $val){
							$insertuserscore_sql = "insert into xb_user_task (userid,status,taskid,flag,remark,create_datetime)
								values ('".$trueuserid."','".$taskuser_list[$key]['status']."','".$taskuser_list[$key]['taskid']."',
										'".$taskuser_list[$key]['flag']."','".$taskuser_list[$key]['remark']."','".$taskuser_list[$key]['create_datetime']."')";
								
							$insertuserscore_list = parent::__get('HyDb')->execute($insertuserscore_sql);
								
						}
						
						//删除临时表中该用户的记录
						$delusertask_sql  = "delete from xb_temp_user_task where userid='".parent::__get('xb_userid')."'";
						$delusertask_list = parent::__get('HyDb')->execute($delusertask_list);
					
					}
					
					
					//2.临时积分数据的插入 userid,type,gettime,score,	getdescribe,remark
					$scoreuser_sql  = "select * from xb_temp_user_score where userid = '".parent::__get('xb_userid')."'";
					$scoreuser_list = parent::__get('HyDb')->get_all($scoreuser_sql);
						
					if(count($scoreuser_list)>0){
					
						$insertscore_sql = "insert into xb_user_score (userid,maintype,type,score,getdescribe,gettime,remark)
								values ('".$trueuserid."','1','".$scoreuser_list[$key]['type']."','".$scoreuser_list[$key]['score']."',
										'".$scoreuser_list[$key]['getdescribe']."','".$scoreuser_list[$key]['gettime']."','".$scoreuser_list[$key]['remark']."')";
						$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
					
						//删除临时表中该用户的记录
						$deluserscore_sql  = "delete from xb_temp_user_score where userid = '".parent::__get('xb_userid')."' ";
						$deluserscore_list = parent::__get('HyDb')->execute($deluserscore_sql);
					
					}
					
					
					//3.临时推送数据的添加
					$temptuisong_sql = "select * from xb_temp_user_tuisong where userid = '".parent::__get('xb_userid')."'";
					$temptuisong_list = parent::__get('HyDb')->get_all($temptuisong_sql);
					
					if(count($temptuisong_list)>0){
						
						foreach ($temptuisong_list as $keys=>$vals){
							
							$tuisonginsert_sql= "insert into xb_user_tuisong (userid,type,status,taskid,message,create_inttime) 
									values('".$trueuserid."','".$temptuisong_list[$keys]['type']."',
											'".$temptuisong_list[$keys]['status']."','".$temptuisong_list[$keys]['taskid']."','".$temptuisong_list[$keys]['message']."','".$temptuisong_list[$keys]['create_inttime']."')";
							parent::__get('HyDb')->execute($tuisonginsert_sql);
						}
						
						//删除临时表中该用户的记录
						$delusertuisong_sql  = "delete from xb_temp_user_tuisong where userid = '".parent::__get('xb_userid')."' ";
						$delusertuisong_list = parent::__get('HyDb')->execute($delusertuisong_sql);
						
					}
					
					
					
					
					//4.读取临时用户表中用户的积分数
					$tempusersql  = "select id,keyong_jifen from xb_temp_user where id='".parent::__get('xb_userid')."' and tokenkey='".parent::__get('xb_userkey')."'";
					$tempuserlist =  parent::__get('HyDb')->get_row($tempusersql);
					
					if($tempuserlist['id']<='0'){
						
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '该用户在临时表中不存在';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
						
					}else{
						//把临时表中的积分清空，极光id删除
						$linshiuser_sql = "update xb_temp_user set keyong_jifen = 0 and jiguangid='' where id='".parent::__get('xb_userid')."' and tokenkey='".parent::__get('xb_userkey')."'";
						$linshiuser_list = parent::__get('HyDb')->execute($linshiuser_sql);
						
						//更新积分
						$updatescore_sql = "update xb_user set keyong_jifen = keyong_jifen + '".$tempuserlist['keyong_jifen']."'
							where id='".$trueuserid."' and tokenkey = '".$trueuserkey."' ";
						$updatescore_list = parent::__get('HyDb')->execute($updatescore_sql);
						
						
						if($updatescore_list){
						
							$temparr = array(
									array(
											'userid'  => $trueuserid,
											'userkey' => $trueuserkey,
									),
							);
						}
						
						
					}
					
					
					
					/* //读取转为正式用户的id
					$zhengshiuser_sql  = "select id,tokenkey,jiguangid from xb_user where phone='".$this->phone."' order by id desc limit 1";
					$zhengshiuser_list = parent::__get('HyDb')->get_row($zhengshiuser_sql);
					
					$trueuseridd  = $zhengshiuser_list['id'];
					$jiguangidd   = $zhengshiuser_list['jiguangid'];
					
					//极光推送
					$message = '恭喜注册成功获取300馅饼，请注意查看';
						
					//推送是我记录
					$time =time();
					$tuisongsql = "insert into xb_user_tuisong (userid,type,status,message,create_inttime)
							values ('".$trueuseridd."','1','2','".$message."','".$time."')";
					$tuisonglist = parent::__get('HyDb')->execute($tuisongsql); */
					
					
					
					
					//parent::func_jgpush($jiguangidd,$message);
					
					
				}
				
				
				
				
				if(!empty($temparr)){
					
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '用户登录成功';
					$echoarr['dataarr'] = $temparr;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
					
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '用户登录失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}
				
				
				
				
			}else{
				//在父类中返回
				/* $echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '验证码校验错误';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false; */
			}
			
			
			
			
			
			
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型错误';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
			
		
		
	}
	
	
	
	
	//操作入口--提交验证码
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//用户类型的判断
		if(parent::__get('xb_usertype')==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//判断手机号是否为空
		if($this->phone==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '手机号不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//判断验证码是否为空
		if($this->vcode==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '验证码不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//校验验证码
		$this->controller_checkcode();
	
		return true;
	}
	
}