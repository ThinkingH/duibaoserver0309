<?php
/*
 * 106，102，105接口整合为一个105接口
 *  type传不同的值，获取不同的数据
	type=1 开屏引导页获取  type=2 新手领礼包弹框  type=3  首页轮播图获取 
	type=4 首页零碎广告
 * 
 */
class HyXb105 extends HyXb{
	
	private $picurlpath;
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
	
		
		$this->type = isset($input_data['type']) ? $input_data['type']:'';  //
		$this->width   = isset($input_data['width'])? $input_data['width']:'';  //图片宽
		$this->height  = isset($input_data['height'])?$input_data['height']:'';     //图片高
		
	}
	
	
	//进行操作
	protected function controller_getpicurl(){
		
		
	//	echo $arr['duibao-basic'];
		
		if($this->type=='1'){//开屏引导
			
			if($this->width==''){
				$this->width='1080';
			}
			
			if($this->height==''){
				$this->height='1920';
			}
			
			
			$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='2' order by id asc";
			$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
				
			foreach ($lunbotu_list as $keys=>$vals){
				
				$lunbotu_list[$keys]['adtitle'] = '广告';
				
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接https://
				
				$replace = array("\t", "\r", "\n",);
				
				if(substr($lunbotu_list[$keys]['img'],0,7)=='http://' || substr($lunbotu_list[$keys]['img'],0,8)=='https://'){
					$lunbotu_list[$keys]['img'] = str_replace($replace, '',$lunbotu_list[$keys]['img']);
				}else{
					$lunbotu_list[$keys]['img'] = str_replace($replace, '',$arr['duibao-basic'].$lunbotu_list[$keys]['img']).'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
				}
				
			
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
			
			
		}else if($this->type=='2'){//新手礼包弹框
			
			if($this->width==''){
				$this->width='750';
			}
				
			if($this->height==''){
				$this->height='600';
			}
			
			$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='3' order by id asc";
			$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
			
			foreach ($lunbotu_list as $keys=>$vals){
				
				
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接https://
				
				if(substr($lunbotu_list[$keys]['img'],0,7)=='http://' || substr($lunbotu_list[$keys]['img'],0,8)=='https://'){
						
				}else{
					$lunbotu_list[$keys]['img'] = $arr['duibao-basic'].$lunbotu_list[$keys]['img'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
				}
			}
				
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
			
		}else if($this->type=='3'){
			
			
			//开屏引导
			$lunbotu_sql1 = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='2' order by id asc";
			$kaiping_list = parent::__get('HyDb')->get_all($lunbotu_sql1);
			
			foreach ($kaiping_list as $keys=>$vals){
				
				$kaiping_list[$keys]['adtitle'] = '广告';
				
			}
			
			//新手礼包
			$lunbotu_sql2 = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='3' order by id asc";
			$libao_list = parent::__get('HyDb')->get_all($lunbotu_sql2);
			
			
			//轮播图查询
			$lunbotu_sql3 = "select id,picname,biaoshi,flag,shopid,shopname,img,
				imgurl,action,type,value,isused,createdatetime
				 from xb_lunbotu where flag='1' and biaoshi='9' order by id asc";
			$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql3);
				
			foreach ($lunbotu_list as $keys=>$vals){
					
				$lunbotu_list[$keys]['adtitle'] = '广告';
					
			}
			
			
			//首页零碎广告
			$lunbotu_sql4 = "select id,picname,biaoshi,flag,shopid,shopname,img,
				imgurl,action,type,value,isused,createdatetime
				 from xb_lunbotu where flag='1' and biaoshi='4' ";
			$adver_list = parent::__get('HyDb')->get_all($lunbotu_sql4);
			
			foreach ($adver_list as $keys=>$vals){
					
				$adver_list[$keys]['adtitle'] = '广告';
					
			}
				
			
			$data = array(
					
					'1' => $kaiping_list,
					'2' => $libao_list,
					'3' => $lunbotu_list,
					'4' => $adver_list,
			);
			
			
			if(count($lunbotu_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '数据获取成功';
				$echoarr['dataarr'] = $data;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo str_replace("\/", "/",  json_encode($echoarr));
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '数据获取失败';
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