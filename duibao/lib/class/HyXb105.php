<?php
/*
 * 开屏引导图和新用户注册首页好礼弹框
 * 
 */
class HyXb105 extends HyXb{
	
	private $picurlpath;
	
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
	
		
		$this->type = isset($input_data['type']) ? $input_data['type']:'';  //
	}
	
	
	//进行操作
	protected function controller_getpicurl(){
		
		
		if($this->type=='1'){//开屏引导
			
			
			$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='2' order by id asc";
			$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
			
			foreach ($lunbotu_list as $keys=>$vals){
				
				$lunbotu_list[$keys]['adtitle'] = '广告';
				
			}
			
			if(count($lunbotu_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '开屏引导图获取成功';
				$echoarr['dataarr'] = $lunbotu_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo str_replace("\/", "/",  json_encode($echoarr));
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '开屏引导图获取失败';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return false;
			}
			
			
		}else if($this->type=='2'){
			
			$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='3' order by id asc";
			$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
				
			if(count($lunbotu_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '好友弹窗图获取成功';
				$echoarr['dataarr'] = $lunbotu_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo str_replace("\/", "/",  json_encode($echoarr));
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '好友弹窗图获取失败';
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
		
		//类型不能为空
		if($this->type==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
	
		//进行启动页图片url地址获取
		$this->controller_getpicurl();
	
		return true;
	
	
	}
}