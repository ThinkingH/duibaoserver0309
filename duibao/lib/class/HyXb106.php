<?php
/*
 * 零碎广告--2017-07-05
 * 
 */
class HyXb106 extends HyXb{
	
	private $kindtype;
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
	
		$this->kindtype  = isset($input_data['kindtype'])?$input_data['kindtype']:'';     //获取广告的类型
		$this->width   = isset($input_data['width'])? $input_data['width']:'';  //图片宽
		$this->height  = isset($input_data['height'])?$input_data['height']:'';     //图片高
	}

	
	//进行操作
	protected function controller_getpicurl(){
		
		$arr = unserialize(BUCKETSTR);//获取七牛访问链接
		
		if($this->kindtype=='1'){//获取首页的广告
			
			if($this->width==''){
				$this->width='350';
			}
				
			if($this->height==''){
				$this->height='178';
			}
			
			$lunbotu_sql = "select id,picname,biaoshi,flag,shopid,shopname,img,
				imgurl,action,type,value,isused,createdatetime
				 from xb_lunbotu where flag='1' and biaoshi='4' ";
			$lunbotu_list = parent::__get('HyDb')->get_row($lunbotu_sql); 
			
			$lunbotu_list['adtitle'] = '广告';
			
			$replace = array("\t", "\r", "\n",);
			
			if(substr($lunbotu_list['img'],0,7)=='http://' || substr($lunbotu_list['img'],0,8)=='https://'){
				$lunbotu_list['img'] = str_replace($replace, '',$lunbotu_list['img']);
			}else{
				$lunbotu_list['img'] = str_replace($replace, '',$arr['duibao-basic'].$lunbotu_list['img']).'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
			}
			
			
			
		}else if($this->kindtype=='2'){//获取发现的广告
			
			if($this->width==''){
				$this->width='180';
			}
			
			if($this->height==''){
				$this->height='180';
			}
			
			//查询广告表中的广告
			$lunbotu_sql = "select id,gflag,gtype,picurl,adurl,taskid,adtitle,adcontent,createtime from ad_advertisement where flag='1' and maintype='1'";
			$lunbotu_list = parent::__get('HyDb')->get_row($lunbotu_sql);
			
			
			$replace = array("\t", "\r", "\n",);
			
			if(substr($lunbotu_list['picurl'],0,7)=='http://' || substr($lunbotu_list['picurl'],0,8)=='https://'){
				$lunbotu_list['picurl'] = str_replace($replace, '',$lunbotu_list['picurl']);
			}else{
				$lunbotu_list['picurl'] = str_replace($replace, '',$arr['duibao-basic'].$lunbotu_list['picurl']).'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
			}
			
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