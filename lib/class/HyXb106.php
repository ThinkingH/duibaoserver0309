<?php
/*
 * 零碎广告--2017-07-05
 * 
 */
class HyXb106 extends HyXb{
	
	private $kindtype;
	
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
	
		$this->kindtype  = isset($input_data['kindtype'])?$input_data['kindtype']:'';     //获取广告的类型
	}

	
	//进行操作
	protected function controller_getpicurl(){
		
		if($this->kindtype=='1'){//获取首页的广告
			
			$lunbotu_sql = "select id,picname,biaoshi,flag,shopid,shopname,img,
				imgurl,action,type,value,isused,createdatetime
				 from xb_lunbotu where flag='1' and biaoshi='4' ";
			$lunbotu_list = parent::__get('HyDb')->get_row($lunbotu_sql); 
			
			$lunbotu_list['adtitle'] = '广告';
			
			
			
		}else if($this->kindtype=='2'){//获取发现的广告
			
			//查询广告表中的广告
			$lunbotu_sql = "select id,gflag,gtype,picurl,adurl,taskid,adtitle,adcontent,createtime from ad_advertisement where flag='1' and maintype='1'";
			$lunbotu_list = parent::__get('HyDb')->get_row($lunbotu_sql);
			
		}
		
		
		if(count($lunbotu_list)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '广告获取成功';
			$echoarr['dataarr'] = $lunbotu_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
			
		}else{
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '广告获取失败';
			$echoarr['dataarr']    = array();
			
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
	
	
		$this->controller_getpicurl();
	
		return true;
	
	
	}
}