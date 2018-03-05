<?php
/*
 * 每日领红包
 */

class HyXb1015 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $type; //type=1 签到查询 2-签到操作
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		//操作类型
		$this->type = isset($input_data['type']) ? $input_data['type']:'';  
	
	}
	
	
	//是否签到
	protected function controller_exec1(){
		
		$pdusersql  = "select id from newusers where userid='".parent::__get('userid')."' and type='3' and 
				createtime>='".date('Y-m-d 00:00:00')."' and createtime<='".date('Y-m-d 23:59:59')."' ";
		$pduserlist = parent::__get('HyDb')->get_row($pdusersql);
		if(count($pduserlist)>0){
			$userarr = array(
					'flag'=> '1',//已领取
			);
			$echojsonstr = HyItems::echo2clientjson('320','今日已领取',$userarr);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$userarr = array(
					'flag'=> '9',//今日为未领取
			);
			$echojsonstr = HyItems::echo2clientjson('321','今日未领取',$userarr);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
	}
	
	//签到操作
	protected function controller_exec2(){
		$pdusersql  = "select id from newusers where userid='".parent::__get('userid')."' and type='3' and
				createtime>='".date('Y-m-d 00:00:00')."' and createtime<='".date('Y-m-d 23:59:59')."' ";
		$pduserlist = parent::__get('HyDb')->get_row($pdusersql);
		if(count($pduserlist)>0){
			$echojsonstr = HyItems::echo2clientjson('320','今日已领取');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{
			//分用户进行判断
			if(parent::__get('usertype')=='1'){
				$tablename = 'xb_user';
				$tablescorename = 'xb_user_score';
				$tabletuisongname = 'xb_user_tuisong';
			}else if(parent::__get('usertype')=='2'){
				$tablename = 'xb_temp_user';
				$tablescorename = 'xb_temp_user_score';
				$tabletuisongname = 'xb_temp_user_tuisong';
			}
			
			//获取用户信息
			$userlistdata = parent::__get('userlistdata');
			
			$vipflag = isset($userlistdata['vipflag'])?$userlistdata['vipflag']:'';//vip会员标识
			$phone  = isset($userlistdata['phone'])?$userlistdata['phone']:'';
			$openid = isset($userlistdata['openid'])?$userlistdata['openid']:'';
			
			//用户积分增加量的判断
			if($vipflag=='1'){//会员积分加10
				$score = '20';
			}else if($vipflag=='10'){
				$score = '10';
			}else{
				$score = '5';
			}
			
			$getdescribe = '‘每日领’获取'.$score.'馅饼';
			//用户积分记录的增加
			parent::insert_userscore($tablescorename,parent::__get('userid'),'1','1',$score,$getdescribe,$getdescribe);
			
			//推送信息的插入
			parent::insert_usertuisong($tabletuisongname,parent::__get('userid'),'1','2','0',$getdescribe);
			
			//用户信息的增加
			parent::update_userscore($tablename,$score,parent::__get('userid'),parent::__get('userkey'));
			
			//新人记录表
			parent::insert_newusers('newusers',parent::__get('userid'),'3',$phone,$openid,$score);
			
			$userarr = array(
					'score' =>$score,
					'flag'  => '1',//今日为未领取
			);
			$echojsonstr = HyItems::echo2clientjson('100','今日领取成功',$userarr);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}
		
		
	}
	
	
	
	//操作入口
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='1'){//判断是否签到
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){//进行签到操作
			$ret = $this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			parent::hy_log_str_add($echojsonstr."\n");
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}