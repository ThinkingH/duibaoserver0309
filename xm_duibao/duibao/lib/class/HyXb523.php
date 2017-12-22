<?php
/*
 * 商品的轮播图
 */
class HyXb523 extends HyXb{
	
	
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
	
	
		//图片存放位置
		//$this->picurlpath = URLPATH;
	
	}
	
	
	//进行操作
	protected function controller_getpicurl(){
	
		/*
		 img：  轮播图1
		 action: 跳转类型
		 value:  跳转的链接地址
		 click：1-允许点击 2-不允许
		 */
	
		//轮播图查询
		$lunbotu_sql = "select img,shopid,shopname,isused,type,picname,action,value from xb_lunbotu where biaoshi='1' and flag='1' order by id asc";
		$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
		
		foreach($lunbotu_list as $keys=>$vals){
			$replace = array("\t", "\r", "\n",);
			//图片展示
			$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			if(substr($lunbotu_list[$keys]['img'],0,7)=='http://' ||substr($lunbotu_list[$keys]['img'],0,8)=='https://' ){
				//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
				$lunbotu_list[$keys]['img'] = str_replace($replace, '', $lunbotu_list[$keys]['img']);
			}else{
				$lunbotu_list[$keys]['img'] = $arr['duibao-shop'].$lunbotu_list[$keys]['img'].'?imageView2/1/w/750/h/250/q/75|imageslim';
				$lunbotu_list[$keys]['img'] = str_replace($replace, '', $lunbotu_list[$keys]['img']);
					
			}		
				
		}
	
		if(count($lunbotu_list)>0){
			
	
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商城轮播图获取成功';
			$echoarr['dataarr'] = $lunbotu_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
				
		}else{
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商城轮播图获取成功';
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
	
	
		//进行图片url地址获取
		$this->controller_getpicurl();
	
		return true;
	
	
	}
	
}