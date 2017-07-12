<?php
/*
 * 兑换规则的描述
 */
class HyXb703 extends HyXb{
	
	
	
	
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
	
	}
	
	
	public function controller_miaoshucontent(){
		
		$maoshu = '   1.根据页面右上角的“馅饼”数量领取相应兑换码。
				   2.请选择与您手机号码相对应的运营商名称。
				   3.获取兑换码后请立即前往兑换流量。
				   4.流量兑换成功后会有推送消息提示。 
				   5.兑宝在法律允许的范围内对此活动进行解释。';
		
		$data = array(
				'maoshu'  => $maoshu,
		);
		
		$tt = str_replace("\t","",$data);
		
		if($data!=''){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '规则描述获取成功！';
			$echoarr['dataarr'] = $tt;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '规则描述获取为空！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		} 
		
		
		
	}
	
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
	
		$this->controller_miaoshucontent();
	
		return true;
	}
	
	
	
	
	
	
	
}
