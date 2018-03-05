<?php
/*
 * 用户登录--提交验证码
 */

class HyXb1004 extends HyXb{
	
	private $phone;
	private $vcode;
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//接受验证码的手机号
		$this->phone = isset($input_data['phone']) ? $input_data['phone']:'';  //
	
		//接受验证码
		$this->vcode = isset($input_data['vcode']) ? $input_data['vcode']:'';  //
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';
		
		
	}
	
	
	
	public function controller_exec1(){
		//验证码校验函数
		$r = parent::func_vcode_check($type='1',$this->phone,$this->vcode);
		if($r===true) {
			//判断该用户是否注册过
			$userregistersql  = "select id,tokenkey from xb_user where phone='".$this->phone."'";
			$userregisterlist = parent::__get('HyDb')->get_row($userregistersql);
			
			if(count($userregisterlist)>0){
				$userarr = array(
						'userid' => $userregisterlist['id'],
						'userkey'=> $userregisterlist['tokenkey'],
				);
				$echojsonstr = HyItems::echo2clientjson('100','登录成功',$userarr);
				parent::hy_log_str_add($echojsonstr."\n");
				echo $echojsonstr;
				return true;
			}else{//该用户首次登录，数据插入到用户表中
				
				//随机生成的userkey
				$userkey = parent::func_create_randkey();
				
				//数据的插入
				$userdatasql = "insert into xb_user (phone,tokenkey,create_datetime)
									values ('".$this->phone."','".$userkey."','".date('Y-m-d H:i:s')."')";
				$userdatalist = parent::__get('HyDb')->execute($userdatasql);
				parent::hy_log_str_add(HyItems::hy_trn2space($userdatasql)."\n");
				
				
				$useridsql = "select id,tokenkey from xb_user where phone='".$this->phone."' order by id desc limit 1";
				$useridlist = parent::__get('HyDb')->get_row($useridsql);
				
				
				if(count($useridlist)>0){
					
					//数据的同步,仅限临时用户
					if('2'==parent::__get('usertype')){
						
						//临时用户积分记录数据
						$scoreuser_sql  = "select * from xb_temp_user_score where userid = '".$this->userid."'";
						$scoreuser_list = parent::__get('HyDb')->get_all($scoreuser_sql);
						if(count($scoreuser_list)>0){
							foreach ($scoreuser_list as $keys=>$vals){
								parent::insert_userscore('xb_user_score',$useridlist['id'],'1',$vals['type'],$vals['score'],$vals['getdescribe'],$vals['remark']);
							}
						}
						//数据的删除
						$deluserscore_sql  = "delete from xb_temp_user_score where userid = '".$this->userid."' ";
						parent::hy_log_str_add(HyItems::hy_trn2space($deluserscore_sql)."\n");
						parent::__get('HyDb')->execute($deluserscore_sql);
						
						
						//推送数据转移删除
						$tuisongtempsong_sql  = "select * from xb_temp_user_tuisong where userid = '".$this->userid."' ";
						$tuisongtempsong_list = parent::__get('HyDb')->get_all($tuisongtempsong_sql);
						if(count($tuisongtempsong_list)>0){
							foreach ($tuisongtempsong_list as $keys=>$vals){
								parent::insert_usertuisong($tablename='xb_user_tuisong',$vals['type'],$vals['status'],$vals['taskid'],$vals['message']);
							}
						}
						//数据的删除
						$delusertuisong_sql  = "delete from xb_temp_user_tuisong where userid = '".$this->userid."' ";
						parent::hy_log_str_add(HyItems::hy_trn2space($delusertuisong_sql)."\n");
						parent::__get('HyDb')->execute($delusertuisong_sql);
					}
					
					//新手礼包领取记录
					$updatelibaosql = "update newusers set userid='".$useridlist['id']."' where userid='".$this->userid."'  ";
					parent::hy_log_str_add(HyItems::hy_trn2space($updatelibaosql)."\n");
					parent::__get('HyDb')->execute($updatelibaosql);
					
					
					//积分的删除
					$tempusersql  = "select id,keyong_jifen from xb_temp_user where id='".$this->userid."'  ";
					parent::hy_log_str_add(HyItems::hy_trn2space($tempusersql)."\n");
					$tempuserlist =  parent::__get('HyDb')->get_row($tempusersql);
					
					parent::hy_log_str_add(HyItems::hy_trn2space($tempuserlist['keyong_jifen'])."\n");
					if($tempuserlist['keyong_jifen']>0){
						
						//把临时表中的积分清空，极光id删除
						$linshiuser_sql = "update xb_temp_user set keyong_jifen = 0 and jiguangid='' where id='".$this->userid."' ";
						parent::hy_log_str_add(HyItems::hy_trn2space($linshiuser_sql)."\n");
						$this->HyDb->execute($linshiuser_sql);
						
						parent::update_userscore($tablename='xb_user',$tempuserlist['keyong_jifen'],$type='1',$useridlist['id']);
					}
					
					$userarr = array(
							'userid' => $useridlist['id'],
							'userkey'=> $useridlist['tokenkey'],
					);
					$echojsonstr = HyItems::echo2clientjson('100','登录成功',$userarr);
					parent::hy_log_str_add($echojsonstr."\n");
					echo $echojsonstr;
					return true;
						
				}else{
					$echojsonstr = HyItems::echo2clientjson('304','登录失败，系统错误');
					parent::hy_log_str_add($echojsonstr."\n");
					echo $echojsonstr;
					return false;
						
				}
			}
			
		}
	}
	
	
	
	//操作入口--提交验证码
	public function controller_init(){
		
		//echo $this->earth_radius;
		if( !is_numeric($this->phone) || strlen($this->phone)!='11'){
			$echojsonstr = HyItems::echo2clientjson('310','手机号码格式不正确');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		if( !is_numeric($this->vcode) || strlen($this->vcode)<4){
			$echojsonstr = HyItems::echo2clientjson('303','验证码格式不正确');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		
		$r = $this->controller_exec1();
		
		return $r;
	}
	
}