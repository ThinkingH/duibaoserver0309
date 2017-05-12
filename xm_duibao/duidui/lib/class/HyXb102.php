<?php
/*
 * 广告位循环图片数组获取--102
 * 
 */
class HyXb102 extends HyXb{
	
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
	
	
		//图片存放位置
		$this->picurlpath = URLPATH;
	
	}
	//dataarr:[{img:url,act:1,value:link},....]
	/* {"returncode":"success","returnmsg":"\u83b7\u53d6\u6210\u529f",
	 * "dataarr":["http:\/\/127.0.0.1\/img\/gaunggao1.jpg","http:\/\/127.0.0.1\/img\/gaunggao2.jpg",
	 * "http:\/\/127.0.0.1\/img\/gaunggao3.jpg","http:\/\/127.0.0.1\/img\/gaunggao4.jpg"]} 
	 * thetype=102&nowtime=1463384184&md5key=527aa50704b8e9e2529e1a03e6ccd912&usertype=3&userid=&userkey=
	 * */
	
	//进行操作
	protected function controller_getpicurl(){
		
		/* 
		 	img：  轮播图1
			action: 跳转类型
			value:  跳转的链接地址
			click：1-允许点击 2-不允许
		 */
		
		//轮播图查询
		$lunbotu_sql = "select id,picname,biaoshi,flag,shopid,shopname,img,
				imgurl,action,type,value,isused,createdatetime
				 from xb_lunbotu where flag='1' and biaoshi='9' order by id asc";
		$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql); 
		
		/* //获取图片的地址
		$picpath1 = $this->picurlpath.'lunbo_1.jpg';
		$picpath2 = $this->picurlpath.'lunbo_2.png';
		
		//定义临时数组
		$temparr= array();
		$temparr[0] = $picpath1;
		$temparr[1] = $picpath2; */
		/* $temparr[2] = 'http://xbapp.xinyouxingkong.com/Xheditor/tiaozhuanurl_1_xinshoubidu.php'; */
		
		/* $temparr = array(
					array(
						'img'   => $this->picurlpath.'lunbo_1.jpg',
						'action'   => '1',
						'value' => 'http://xbapp.xinyouxingkong.com/Xheditor/tiaozhuanurl_1_xinshoubidu.php',
						'isused' => '1',
						),
					/* array(
						'img'   => $this->picurlpath.'lunbo_2.png',
						'action'   => '1',
						'value' => 'http://xbapp.xinyouxingkong.com/Xheditor/tiaozhuanurl_1_xinshoubidu.php',
						'isused' => '2',
					), 
					array(
						'img'   => $this->picurlpath.'lunbo_3.png',
						'action'   => '1',
						'value' => 'http://xbapp.xinyouxingkong.com/Xheditor/tiaozhuanurl_1_xinshoubidu.php',
						'isused' => '2',
					),
		
		); */
		
		
		
		
		if(count($lunbotu_list)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '广告位循环图片获取成功';
			$echoarr['dataarr'] = $lunbotu_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
			
		}else{
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '广告位循环图片获取失败';
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
	
	
		//进行启动页图片url地址获取
		$this->controller_getpicurl();
	
		return true;
	
	
	}
}