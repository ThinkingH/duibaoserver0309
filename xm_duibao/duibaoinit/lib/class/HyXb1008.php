<?php
/**
 * 用户的信息获取
 */

class HyXb1008 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
	
	}
	
	
	protected function controller_exec1(){
		
		$userlistdata = parent::__get('userlistdata');
		//select id,vipflag,vip_endtime_one,vip_endtime_two,phone,keyong_jifen,dongjie_jifen,sex,birthday,userlevel,nickname,touxiang,create_datetime
		$retarr = array(
				'id',
				'phone',
				'sex',
				'birthday',
				'nickname',
				'touxiang',
				'keyong_jifen',
				'create_datetime',
				'vipflag',
				'vip_endtime_one',
				'vip_endtime_two'
		);
		$newuserlist = array();
		foreach($userlistdata as $keyu => $valu) {
			//echo $keyu.'--'.$valu.'||';
			if(in_array($keyu, $retarr)) {
				$newuserlist[$keyu] = (string)$valu;
			}
		}
		
		if($newuserlist['touxiang']!='') {
			if(substr($newuserlist['touxiang'],0,4)!='http') {
				//拼接七牛云头像链接
				$newuserlist['touxiang'] = HyItems::hy_qiniuimgurl('duibao-basic',$newuserlist['touxiang'],100,100,true);
			}else {
				//链接为微信的，不做处理
			}
		}
		
		//判断vip是否到期
		if($newuserlist['vip_endtime_one']<time()){
			$updateflag_sql = "update xb_user set vipflag='10' where id='".parent::__get('userid')."' ";
			parent::__get('HyDb')->execute($updateflag_sql);
				
			$newuserlist['vipflag'] = '10'; //会员标识 1-会员  10-普通会员
			$newuserlist['day']    = '0';//剩余天数
			
		}else{
			$newuserlist['day'] = ceil(((strtotime($newuserlist['vip_endtime_one']) - time() )/86400)).'天';
		}
		
		if($newuserlist['nickname']=='' ){
			$newuserlist['nickname']=substr($newuserlist['phone'],0,3).'****'.substr($newuserlist['phone'],-4);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','正式用户信息获取成功',$newuserlist);
		parent::hy_log_str_add($echojsonstr."\n");
		echo $echojsonstr;
		return true;
		
		
	}
	
	//临时用户信息
	protected function controller_exec2(){
	
		$userinfo_sql = "select id,keyong_jifen,dongjie_jifen,create_datetime from xb_temp_user
					where id='".parent::__get('userid')."' and tokenkey='".parent::__get('userkey')."'";
		$userinfo_list = parent::__get('HyDb')->get_row($userinfo_sql);
	
		$echojsonstr = HyItems::echo2clientjson('100','临时用户信息获取成功',$userinfo_list);
		parent::hy_log_str_add($echojsonstr."\n");
		echo $echojsonstr;
		return true;
	
	
	}
	
	
	
	
	//用户信息--操作入口
	public function controller_init(){
		
		//判断正式用户通讯校验参数
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if(parent::__get('usertype')=='1'){//正式用户信息
			$this->controller_exec1();
		}else if(parent::__get('usertype')=='2'){//临时用户信息
			$this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','用户类型错误');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
	
		return true;
	
	
	}
	
	
	
	
}