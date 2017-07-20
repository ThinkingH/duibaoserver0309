<?php
/*
 * 微信登录
 */
class HyXb114 extends HyXb{
	
	private $sex;
	private $openid;
	private $nickname;
	private $headimgurl;
	private $userid;
	private $userkey;
	
	
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
	
	
	
		//接收openid
		$this->openid = isset($input_data['openid']) ? $input_data['openid']:'';  //
	
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
	
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
		
		$this->sex = isset($input_data['sex']) ? $input_data['sex']:'';  //性别
		$this->nickname = isset($input_data['nickname']) ? $input_data['nickname']:'';  //昵称
		$this->headimgurl = isset($input_data['headimgurl']) ? $input_data['headimgurl']:'';  //头像
		//$this->headimgurl='77777hfjkd';
	}
	
	
	//openid主操作的插入
	public function controller_checkopenid(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		//随机生成的userkey
		$userkey = parent::func_create_randkey();
		
		if($usertype=='1'){//用户的正常登录
			
			//判断该用户是否注册过
			$openidsql  = "select id,tokenkey from xb_user where openid='".$this->openid."'";
			$openidlist = parent::__get('HyDb')->get_row($openidsql); 
			
			if($openidlist['id']>0){
				
				//用户名更新
				$updateusersql = "update xb_user set sex='".$this->sex."',nickname='".$this->nickname."',touxiang='".$this->headimgurl."' where openid='".$this->openid."' ";
								parent::__get('HyDb')->execute($updateusersql);
				
				//该用户注册过，直接进行登录
				$temparr = array(
						array(
								'userid' => $openidlist['id'],
								'userkey'=> $openidlist['tokenkey'],
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
				
				$userdatasql = "insert into xb_user (openid,tokenkey,sex,nickname,touxiang,create_datetime)
					values ('".$this->openid."','".$userkey."','".$this->sex."','".$this->nickname."','".$this->headimgurl."','".parent::__get('create_datetime')."')";
				$userdatalist = parent::__get('HyDb')->execute($userdatasql);
				
				$useridsql = "select id,tokenkey from xb_user where openid='".$this->openid."' and create_datetime>='".date('Y-m-d H:i:s',(time()-3*24*60*60))."'";
				$useridlist = parent::__get('HyDb')->get_row($useridsql);
					
					
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
			
			
		}else if($usertype=='2'){//临时用户转为正常用户
			
			
			//判断该用户是否注册过
			$useropenidsql  = "select id,tokenkey from xb_user where openid='".$this->openid."' ";
			$useropenidlist = parent::__get('HyDb')->get_row($useropenidsql); 
			
			if($useropenidlist['id']>0){//说明该用户注册过，直接登录
				
				//用户名更新
				$updateusersql = "update xb_user set sex='".$this->sex."',nickname='".$this->nickname."',touxiang='".$this->headimgurl."' where openid='".$this->openid."' ";
				parent::__get('HyDb')->execute($updateusersql);
				
				$trueuserid  = $useropenidlist['id'];
				$trueuserkey = $useropenidlist['tokenkey'];
				
				$temparr = array(
						array(
								'userid' => $trueuserid,
								'userkey'=> $trueuserkey,
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
				
			}else{//该用户没有注册过,临时用户积分更新到正式用户中
				
				$userdatasql = "insert into xb_user (openid,tokenkey,sex,nickname,touxiang,create_datetime) 
						values ('".$this->openid."','".$userkey."','".$this->sex."','".$this->nickname."','".$this->headimgurl."','".parent::__get('create_datetime')."')";
				$userdatalist = parent::__get('HyDb')->execute($userdatasql);
				
				
				if($userdatalist){
				
					//读取转为正式用户的id
					$zhengshiuser_sql  = "select id,tokenkey from xb_user where openid='".$this->openid."' order by id desc limit 1";
					$zhengshiuser_list = parent::__get('HyDb')->get_row($zhengshiuser_sql);
				
					$trueuserid  = $zhengshiuser_list['id'];
					$trueuserkey = $zhengshiuser_list['tokenkey'];
				
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
				$taskuser_sql  = "select * from xb_temp_user_task where userid='".$this->userid."'";
				$taskuser_list = parent::__get('HyDb')->get_all($taskuser_sql);
				
				if(count($taskuser_list)>0){
						
					foreach ($taskuser_list as $key => $val){
						$insertuserscore_sql = "insert into xb_user_task (userid,status,taskid,flag,remark,create_datetime)
								values ('".$trueuserid."','".$taskuser_list[$key]['status']."','".$taskuser_list[$key]['taskid']."',
										'".$taskuser_list[$key]['flag']."','".$taskuser_list[$key]['remark']."','".$taskuser_list[$key]['create_datetime']."')";
				
						$insertuserscore_list = parent::__get('HyDb')->execute($insertuserscore_sql);
				
					}
				
					//删除临时表中该用户的记录
					$delusertask_sql  = "delete from xb_temp_user_task where userid='".$this->userid."'";
					$delusertask_list = parent::__get('HyDb')->execute($delusertask_list);
						
				}
				
				
				//2.临时积分数据的插入 userid,type,gettime,score,	getdescribe,remark
				$scoreuser_sql  = "select * from xb_temp_user_score where userid = '".$this->userid."'";
				$scoreuser_list = parent::__get('HyDb')->get_all($scoreuser_sql);
				
				if(count($scoreuser_list)>0){
						
					$insertscore_sql = "insert into xb_user_score (userid,maintype,type,score,getdescribe,gettime,remark)
								values ('".$trueuserid."','1','".$scoreuser_list[$key]['type']."','".$scoreuser_list[$key]['score']."',
										'".$scoreuser_list[$key]['getdescribe']."','".$scoreuser_list[$key]['gettime']."','".$scoreuser_list[$key]['remark']."')";
					$insertscore_list = parent::__get('HyDb')->execute($insertscore_sql);
						
					//删除临时表中该用户的记录
					$deluserscore_sql  = "delete from xb_temp_user_score where userid = '".$this->userid."' ";
					$deluserscore_list = parent::__get('HyDb')->execute($deluserscore_sql);
						
				}
				
				
				//3.临时推送数据的添加
				$temptuisong_sql = "select * from xb_temp_user_tuisong where userid = '".$this->userid."'";
				$temptuisong_list = parent::__get('HyDb')->get_all($temptuisong_sql);
					
				if(count($temptuisong_list)>0){
				
					foreach ($temptuisong_list as $keys=>$vals){
							
						$tuisonginsert_sql= "insert into xb_user_tuisong (userid,type,status,taskid,message,create_inttime)
									values('".$trueuserid."','".$temptuisong_list[$keys]['type']."',
											'".$temptuisong_list[$keys]['status']."','".$temptuisong_list[$keys]['taskid']."','".$temptuisong_list[$keys]['message']."','".$temptuisong_list[$keys]['create_inttime']."')";
						parent::__get('HyDb')->execute($tuisonginsert_sql);
					}
				
					//删除临时表中该用户的记录
					$delusertuisong_sql  = "delete from xb_temp_user_tuisong where userid = '".$this->userid."' ";
					$delusertuisong_list = parent::__get('HyDb')->execute($delusertuisong_sql);
				
				}
				
				
				//4.读取临时用户表中用户的积分数
				$tempusersql  = "select id,keyong_jifen from xb_temp_user where id='".$this->userid."' and tokenkey='".$this->userkey."'";
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
					$linshiuser_sql = "update xb_temp_user set keyong_jifen = 0 and jiguangid='' where id='".$this->userid."' and tokenkey='".$this->userkey."'";
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
					return false;
				}
				
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
		
		
		//判断openid是否为空
		if($this->openid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = 'openid不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
		//openid数据的插入
		$this->controller_checkopenid();
	
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}