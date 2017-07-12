<?php
/*
 * 发布商品的种类
 */
class HyXb806 extends HyXb{
	
	
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
		
		$this->kindtype = isset($input_data['kindtype'])? $input_data['kindtype']:'';  
	
	}
	
	
	//发布商品种类的获取
	public function controller_fabushopkindtype(){
		
		if($this->kindtype=='1'){
			
			$kindtypesql = "select kindname from z_kindtype where flag='1' ";
			$kindtypelist = parent::__get('HyDb')->get_all($kindtypesql);
			
			if(count($kindtypelist)>0){
					
				$echoarr = array();
				$echoarr['returncode']='success';
				$echoarr['returnmsg']='分类数据获取成功！';
				$echoarr['dataarr'] = $kindtypelist;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n";//日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
					
			}else{
				$echoarr = array();
				$echoarr['returncode']='success';
				$echoarr['returnmsg']='分类数据获取失败！';
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n";//日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
		}else if($this->kindtype=='2'){
			
			$maoshu = '   1.优惠券信息填写完整
				   2.如果无法识别地理位置，可以在地图上搜索
				   3.填写原价，现价与折扣三选二即可';
			
			$data = array(
					'maoshu'  => $maoshu,
			);
			
			$tt = str_replace("\t","",$data);
			
			if($data!=''){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '特别提示获取成功！';
				$echoarr['dataarr'] = $tt;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '特别提示获取为空！';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
		}
		
		
		
	}
	
	
	//发现分类获取操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//发布商品种类的获取入口
		$this->controller_fabushopkindtype();
	
		return true;
	}
	
	
	
}