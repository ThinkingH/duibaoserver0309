<?php
/*
 * 礼包的领取
 */
class HyXb208 extends HyXb{
	
	
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
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		
		$starttime = date('Y-m-01 00:00:00');
		$endtime   = date('Y-m-31 23:59:59');
			
		$pdusersql  = "select id from libaocode where userid='".$this->userid."' and createtime>='".$starttime."' and createtime<='".$endtime." '";
		$pduserlist = parent::__get('HyDb')->get_row($pdusersql);
			
		if($pduserlist['id']>0){//说明该用户已领取过当月礼包，不可以在进行领取
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户当月已领取过礼包，请下月再领！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
		
			echo json_encode($echoarr);
			return false;
				
		}else{//礼包领取
			$addtime = date('Y-m-01');
		
			$libaosql  = "select id,type,duihuanma,maintype,siteid from libaocode where flag='9' and addtime='".$addtime."' limit 1  " ;
			$libaolist = parent::__get('HyDb')->get_all($libaosql);
				
			if($libaolist[0]['id']>0){//兑换码获取成功
				//更新flag为已使用 已经对应的id
				$createtime=date('Y-m-d h:i:s');
				$updateflagsql  = "update libaocode set flag=1,userid='".$this->userid."',createtime='".$createtime."' where id='".$libaolist[0]['id']."'";
				$updateflaglist = parent::__get('HyDb')->execute($updateflagsql);
				
				$insertdata_sql = "insert into shop_userbuy (userid,siteid,typeid,name,keystr,order_createtime)
    				values ('".$this->userid."','".$libaolist[0]['siteid']."','".$libaolist[0]['maintype']."','".$libaolist[0]['type']."','".$libaolist[0]['duihuanma']."','".$createtime."')";
    			$insertdata_list = $Model->execute($insertdata_sql);
		
				$temparr = array(
						'type'      => $libaolist[0]['type'],
						'duihuanma' => $libaolist[0]['duihuanma'],
						'userid'    => $this->userid,
						'createtime' => $createtime,
				);
		
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '礼包领取成功';
				$echoarr['dataarr']    = $temparr;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
		
		
			}else{//礼包领取完
		
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '当月礼包已抢光，请下次再来！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
					
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
	
		//操作类型的判断parent::__get('xb_usertype')
		if(parent::__get('xb_thetype')!='208'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
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
		
	
		//每月礼包获取
		$this->controller_getlibaocode();
	
		return true;
	
	}
	
	
}