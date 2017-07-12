<?php
/*
 * 优惠券分享获积分
 */
class HyXb705 extends HyXb{
	
	private $jifen;
	
	
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
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  
		//分享领取的积分
		$this->jifen = '50';  //
	}
	
	
	//优惠券分享
	public function controller_sharequan(){
		
		
		//判断用户当天分享的次数
		$panduansql  = "select count(*) as num from newusers where type='2' and createtime>='".date('Y-m-d 00:00:00')."' and createtime<='".date('Y-m-d 23:59:59')."' and userid='".$this->userid."' ";
		$panduanlist = parent::__get('HyDb')->get_all($panduansql);
		
		if($panduanlist[0]['num']>=5){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '今日分享达到上限，请明日在分享！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}else {
			
			//记录用户的分享次数
			$date = date('Y-m-d H:i:s');
			$newuserinsertsql = "insert into newusers (userid,type,libao,createtime) values
							 ('".$this->userid."','2','".$this->jifen."','".date('Y-m-d H:i:s')."')";
			$newuserinsertlist = parent::__get('HyDb')->execute($newuserinsertsql);
			
			
			if($newuserinsertlist){
				
				//用户分享优惠券--获取50积分
				$jifencharusql  = "update xb_user set keyong_jifen=keyong_jifen+'".$this->jifen."' where id='".$this->userid."'";
				$jifencharulist = parent::__get('HyDb')->execute($jifencharusql);
					
				//积分详情的记录
				$getdescribe = '分享优惠券获取'.$this->jifen.'馅饼';
				$date=time();
				$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe)
							values ('".$this->userid."','1','1','1','".$this->jifen."','".$date."','".$getdescribe."')";
				parent::__get('HyDb')->execute($scoresql);
				
				$sqldata  = "select jiguangid,is_lock from xb_user where id='".$this->userid."' ";
				$listdata = parent::__get('HyDb')->get_row($sqldata); 
					
				//极光推送
				$message = '分享优惠券获取'.$this->jifen.'馅饼，请查看';
					
				parent::func_jgpush($listdata['jiguangid'],$message);
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '分享成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '分享失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
		
	}
	
	
	
	
	
	
	
	//操作入口--新手领取礼包
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//用户类型错误
		if(parent::__get('xb_usertype')!='1'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
	
		$this->controller_sharequan();
	
		return true;
	}
	
	
	
	
}