<?php
/*
 * 兑宝特供优惠
 */

class HyXb205 extends HyXb{
	
	private $width;
	private $height;
	
	
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
		
		$this->width  = isset($input_data['width'])?$input_data['width']:'';
		$this->height = isset($input_data['height'])?$input_data['height']:'';
	
	}
	
	
	//兑宝特供优惠
	public function controller_collectiontypelist(){
		
		if($this->width==''){//753 * 292
			$this->width='750';
		}
		
		if($this->height==''){
			$this->height='290';
		}
		
		$youhuiquanconfsql  = "select id,img from xb_lunbotu where flag='1' and biaoshi='6' order by picname desc limit 4";
		$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql); 
		
		foreach ($youhuiquanconflist as $keys => $vals){
			
			//图片展示
			$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			if(substr($youhuiquanconflist[$keys]['img'],0,7)=='http://' ||substr($youhuiquanconflist[$keys]['img'],0,8)=='https://' ){
				//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
			}else{
				$youhuiquanconflist[$keys]['img'] = $arr['duibao-basic'].$youhuiquanconflist[$keys]['img'].'imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					
			}
			
		}
		
		
		if(count($youhuiquanconflist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '特供优惠获取成功';
			$echoarr['dataarr'] = $youhuiquanconflist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '特供优惠获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	
	//操作入口--兑宝特供优惠
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='205'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//兑宝特供优惠
		$this->controller_collectiontypelist();
	
		return true;
	}
	
	
	
	
}