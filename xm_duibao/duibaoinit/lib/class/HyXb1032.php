<?php
/*
 * 抽奖分享
 */
class HyXb1032 extends HyXb{
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
	}
	
	//获取抽奖数据
	public function controller_exec1(){
		
		//判断用户的等级
		$userrange_sql = "select * from xb_user where id='".parent::__get('userid')."' ";
		parent::hy_log_str_add($userrange_sql."\n");
		$userrange_list = parent::__get('HyDb')->get_row($userrange_sql);
		
		$phone = isset($userrange_list['phone'])?$userrange_list['phone']:'';
		//1-vip用户  10-普通用户
		$person = isset($userrange_list['vipflag'])?$userrange_list['vipflag']:'10';
		
		$where = '';
		if($person=='1'){//vip
			$where = ' vipnum = vipnum +1';
		}else{
			$where = ' normalnum = normalnum +1';
		}
		
		//判断该用户是否分享
		$user_sql = "select id  from newusers where type='5' and createtime>='".date('Y-m-d 00:00:00')."'
					and createtime<='".date('Y-m-d 23:59:59')."' and userid='".parent::__get('userid')."' limit 1";
		parent::hy_log_str_add($user_sql."\n");
		$user_list = parent::__get('HyDb')->get_row($user_sql);
		
		if($user_list['id']>0){
			$echojsonstr = HyItems::echo2clientjson('414','今日已分享');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{
// 			//抽奖次数的增加
// 			$scoresql = "update db_prize_config set $where where  flag='1' ";
// 			$r = parent::__get('HyDb')->execute($scoresql);
				
			//分享记录的增加
			$shanre_sql = "insert into newusers (userid,type,phone,createtime)
					values ('".parent::__get('userid')."','5','".$phone."','".date('Y-m-d H:i:s')."') ";
			parent::hy_log_str_add(HyItems::hy_trn2space($shanre_sql)."\n");
			$shanre_list = parent::__get('HyDb')->execute($shanre_sql);
			
			$echojsonstr = HyItems::echo2clientjson('100','操作成功');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
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