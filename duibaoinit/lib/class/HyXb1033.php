<?php
/*
 * 新用户礼包领取
 */
class HyXb1033 extends HyXb{
	
	private $sharequan;
	private $picurlpath;
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->sharequan = isset($input_data['sharequan'])?$input_data['sharequan']:'';//是否分享
		$this->picurlpath = URLPATH;//icon_novicebg.png
	}
	
	//获取抽奖数据
	public function controller_exec1(){
		$usertype = parent::__get('usertype');
		if($usertype=='1'){
			$tablename = 'xb_user';
			$tablescorename = 'xb_user_score';
			$tabletuisongname = 'xb_user_tuisong';
		}else if($usertype=='2'){
			$tablename = 'xb_temp_user';
			$tablescorename = 'xb_temp_user_score';
			$tabletuisongname = 'xb_temp_user_tuisong';
		}
		
		$userinfo = parent::__get('userlistdata');
		
		$phone = isset($userinfo['phone'])?$userinfo['phone']:'';
		$openid = isset($userinfo['openid'])?$userinfo['openid']:'';
		$jiguangid = isset($userinfo['jiguangid'])?$userinfo['jiguangid']:'';//极光id
		
		//分2步 1--未进行分享，直接领取     2--进行分享
		$where = '';
		if($this->sharequan=='888'){
			$where = "userid='".parent::__get('userid')."'  and type in (1,2)" ;
			$this->libao = '100';
			$type='1';
		}else if($this->sharequan=='666'){
			$where = "userid='".parent::__get('userid')."'  and type = 2 " ;
			$this->libao = '200';  //
			$type='2';
		}
		
		$newusersql  = "select id from newusers where $where ";
		$newuserlist = parent::__get('HyDb')->get_row($newusersql);
		
		if($newuserlist['id']>0){
			$echojsonstr = HyItems::echo2clientjson('414','礼包已领取');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}else{
			//发放用户100积分，用户领取的记录
			$newuserinsertsql = "insert into newusers (userid,type,phone,openid,libao,createtime) values
							 ('".parent::__get('userid')."','".$type."','".$phone."','".$openid."','".$this->libao."','".date('Y-m-d H:i:s')."')";
			parent::hy_log_str_add(HyItems::hy_trn2space($newuserinsertsql)."\n");
			$newuserinsertlist = parent::__get('HyDb')->execute($newuserinsertsql);
			
			$temparr = array(
					'imgurl'   => $this->picurlpath.'icon_novicebg.png',
			);
			
			$getdescribe = '领取新手好礼'.$this->libao1.'馅饼';
			$message = '恭喜你获取领取新手礼包'.$this->libao1.'馅饼，请查看';
			//用户信息的增加
			parent::update_userscore($tablename,$this->libao,'1',parent::__get('userid'));
			//积分记录的插入
			parent::insert_userscore($tablescorename,parent::__get('userid'),'1','1',$this->libao,$getdescribe);
			//推送信息的插入
			parent::insert_usertuisong($tabletuisongname,parent::__get('userid'),'1','1',$taskid='0',$message);
			
			$userlistdata = parent::__get('userlistdata');
			//极光推送
			parent::func_jgpush($jiguangid,$message,'1',$m_txt='',$m_time='86400');
			
			
			if($newuserinsertlist){
				$echojsonstr = HyItems::echo2clientjson('100','领取成功',$temparr);
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('415','操作失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
			
		}
		
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}