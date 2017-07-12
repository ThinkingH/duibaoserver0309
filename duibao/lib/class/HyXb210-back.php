<?php
/*
 * 每日领---2017-6-29
 */
class HyXb210 extends HyXb{
	
	
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
	
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
		
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
	}
	
	//领取礼包操作
	public function controller_getlibaocode(){
		
		
		$starttime = date('Y-m-d 00:00:00');
		$endtime   = date('Y-m-d 23:59:59');
			
		$pdusersql  = "select id from newusers where userid='".$this->userid."' and createtime>='".$starttime."' and createtime<='".$endtime."' ";
		$pduserlist = parent::__get('HyDb')->get_row($pdusersql);
			
		if($pduserlist['id']>0){//说明该用户已领取过当日礼包，不可以在进行领取
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '今日用户已领，请明日再来！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
		
			echo json_encode($echoarr);
			return false;
				
		}else{//礼包领取
			
			$userregistersql  = "select id,jiguangid,phone,openid from xb_user where id='".$this->userid."'";
			$userregisterlist = parent::__get('HyDb')->get_row($userregistersql);
			
			$libaosql = "insert into newusers (userid,type,phone,openid,libao,createtime) 
					values ('".$this->userid."','3','".$userregisterlist['phone']."','".$userregisterlist['openid']."','5','".date('Y-m-d H:i:s')."')";
			$libaolist = parent::__get('HyDb')->execute($libaosql);
			
			//用户积分的增加
			$scoresql = "update xb_user set keyong_jifen=keyong_jifen+5 where id='".$this->userid."'";
						parent::__get('HyDb')->execute($scoresql);
						
			//积分增加记录
			//积分详情的记录
			$getdescribe = '‘每日领’获取5馅饼';
			$date=time();
			$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe)
						values ('".$this->userid."','1','1','1','5','".$date."','".$getdescribe."')";
					parent::__get('HyDb')->execute($scoresql);
					
				$temparr = array(
							array(
									'xianbing'   => '5',
							),
					);
			
			if($libaolist){
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '领取成功';
				$echoarr['dataarr']    = $temparr;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
				
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '领取失败！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return false;
			}
			
			
		}
		
		
	}
	
	
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
	
		//每日领
		$this->controller_getlibaocode();
	
		return true;
	
	}
	
	
}