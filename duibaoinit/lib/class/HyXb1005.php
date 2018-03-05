<?php
/*
 * 微信登录--与手机号平行
 */

class HyXb1005 extends HyXb{
	
	private $openid;
	private $sex;
	private $nickname;
	private $headimgurl;
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//用户opeind
		$this->openid = isset($input_data['openid']) ? $input_data['openid']:'';  //
		$this->sex = isset($input_data['sex']) ? $input_data['sex']:'';  //性别
		$this->nickname = isset($input_data['nickname']) ? $input_data['nickname']:'';  //昵称
		$this->headimgurl = isset($input_data['headimgurl']) ? $input_data['headimgurl']:'';  //头像
	}
	
	
	//正式用户微信登录操作
	protected function controller_exec1(){
		
		//判断该用户是否注册过
		$openidsql  = "select id,tokenkey,phone from xb_user where openid='".$this->openid."'";
		$openidlist = parent::__get('HyDb')->get_row($openidsql);
		if($openidlist['id']>0){
			if($openidlist['phone']==''){//微信登录成功后，是否跳转到手机绑定页
				$is_bangding = 'yes';
			}else{
				$is_bangding = 'no';
			}
			//用户信息更新
			$updateusersql = "update xb_user set sex='".$this->sex."',nickname='".$this->nickname."',touxiang='".$this->headimgurl."' where openid='".$this->openid."' ";
			parent::hy_log_str_add(HyItems::hy_trn2space($updateusersql)."\n");
			parent::__get('HyDb')->execute($updateusersql);
			
			$userarr = array(
					'userid'  => $openidlist['id'],
					'userkey' => $openidlist['tokenkey'],
					'is_bangding' => $is_bangding
			);
			$echojsonstr = HyItems::echo2clientjson('100','登录成功',$userarr);
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			//随机生成的userkey
			$userkey = parent::func_create_randkey();
			$userdatasql = "insert into xb_user (openid,tokenkey,sex,nickname,touxiang,create_datetime)
					values ('".$this->openid."','".$userkey."','".$this->sex."','".$this->nickname."','".$this->headimgurl."','".parent::__get('create_datetime')."')";
			parent::hy_log_str_add(HyItems::hy_trn2space($userdatasql)."\n");
			$userdatalist = parent::__get('HyDb')->execute($userdatasql);
			
			$useridsql = "select id,tokenkey,phone from xb_user where openid='".$this->openid."' and create_datetime>='".date('Y-m-d H:i:s',(time()-3*24*60*60))."'";
			$useridlist = parent::__get('HyDb')->get_row($useridsql);
			
			if($useridlist['phone']==''){//微信登录成功后，是否跳转到手机绑定页
				$is_bangding = 'yes';
			}else{
				$is_bangding = 'no';
			}
			
			//数据的同步
			if(parent::__get('usertype')=='2' && $useridlist['id']>0){//临时用户转正式用户的微信登录，进行数据同步
				
				//临时用户积分记录数据
				$scoreuser_sql  = "select * from xb_temp_user_score where userid = '".parent::__get('userid')."'";
				$scoreuser_list = $this->HyDb->get_all($scoreuser_sql);
				if(count($scoreuser_list)>0){
					foreach ($scoreuser_list as $keys=>$vals){
						parent::insert_userscore('xb_user_score',$useridlist['id'],'1',$vals['type'],$vals['score'],$vals['getdescribe'],$vals['remark']);
					}
				}
				//数据的删除
				$deluserscore_sql  = "delete from xb_temp_user_score where userid = '".parent::__get('userid')."' ";
				parent::hy_log_str_add(HyItems::hy_trn2space($deluserscore_sql)."\n");
				$this->HyDb->execute($deluserscore_sql);
				
				
				//推送数据转移删除
				$tuisongtempsong_sql  = "select * from xb_temp_user_tuisong where userid = '".parent::__get('userid')."' ";
				$tuisongtempsong_list = $this->HyDb->get_all($tuisongtempsong_sql);
				if(count($tuisongtempsong_list)>0){
					foreach ($tuisongtempsong_list as $keys=>$vals){
						parent::insert_usertuisong($tablename='xb_user_tuisong',$vals['type'],$vals['status'],$vals['taskid'],$vals['message']);
					}
				}
				//数据的删除
				$delusertuisong_sql  = "delete from xb_temp_user_tuisong where userid = '".parent::__get('userid')."' ";
				parent::hy_log_str_add(HyItems::hy_trn2space($delusertuisong_sql)."\n");
				$this->HyDb->execute($delusertuisong_sql);
				}
					
				//新手礼包领取记录
				$updatelibaosql = "update newusers set userid='".$useridlist['id']."' where userid='".parent::__get('userid')."'  ";
				parent::hy_log_str_add(HyItems::hy_trn2space($updatelibaosql)."\n");
				$this->HyDb->execute($updatelibaosql);
					
					
				//积分的删除
				$tempusersql  = "select id,keyong_jifen from xb_temp_user where id='".parent::__get('userid')."'  ";
				$tempuserlist =  $this->HyDb->get_row($tempusersql);
				if($tempuserlist['keyong_jifen']>0){
					//把临时表中的积分清空，极光id删除
					$linshiuser_sql = "update xb_temp_user set keyong_jifen = 0 and jiguangid='' where id='".$this->userid."' ";
					parent::hy_log_str_add(HyItems::hy_trn2space($linshiuser_sql)."\n");
					$this->HyDb->execute($linshiuser_sql);
				
					parent::update_userscore($tablename='xb_user',$tempuserlist['keyong_jifen'],$type='1',$useridlist['id']);
				
			}
			
			if(count($useridlist)>0){
				$userarr = array(
						'userid'  => $useridlist['id'],
						'userkey' => $useridlist['tokenkey'],
						'is_bangding' => $is_bangding
				);
			}
			$echojsonstr = HyItems::echo2clientjson('100','登录成功',$userarr);
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
			
		}
		
	}
	
	
	
	//操作入口--提交验证码
	public function controller_init(){
		
		//判断openid是否为空
		if($this->openid==''){
			$echojsonstr = HyItems::echo2clientjson('306','微信openid不能为空');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		$r = $this->controller_exec1();
		
		return $r;
	}
	
}