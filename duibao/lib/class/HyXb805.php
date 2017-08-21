<?php
/*
 * 发现的发布数据的提交
 */
class HyXb805 extends HyXb{
	
	private $imgpath;
	private $over_datetime;
	
	
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
	
		$this->lat = isset($input_data['lat'])? $input_data['lat']:'';  //纬度
		$this->lng = isset($input_data['lng'])? $input_data['lng']:'';  //经度
		
		$this->imgdata = isset($input_data['imgdata'])? $input_data['imgdata']:'';  //图片进行base64编码后处理的字符串，传递时请按规范先urlencode
		$this->houzhui = isset($input_data['houzhui'])? $input_data['houzhui']:'';  //图片的后缀名
	
		$this->type = isset($input_data['type'])? $input_data['type']:'';  //商品类型
		$this->discount = isset($input_data['discount'])? $input_data['discount']:'';  //折扣价格
	
		$this->nowprice   = isset($input_data['nowprice'])? $input_data['nowprice']:'';        //现价
		$this->yuanprice  = isset($input_data['yuanprice'])?$input_data['yuanprice']:'';     //原价
		
		$this->address  = isset($input_data['address'])?$input_data['address']:'';     //地址
		$this->proname  = isset($input_data['proname'])?$input_data['proname']:'';     //店铺名称
		
		$this->nowtime  = isset($input_data['nowtime'])?$input_data['nowtime']:'';     //到期时间
		
		$this->phone  = isset($input_data['phone'])?$input_data['phone']:'';  
		   
		$this->over_datetime  = isset($input_data['over_datetime'])?$input_data['over_datetime']:'';     
		
		//图片的存放位置
		//$this->imgpath = 'http://xbapp.xinyouxingkong.com/dd_system/Public/Uploads/fabupicture/';
		//$this->imgpath = 'http://127.0.0.1:8002/dd_system/Public/Uploads/fabupicture/';
		$this->imgpath = ADIMAGEPATH;
		//$this->imgpath = 'http://xbapp.xinyouxingkong.com/duidui/advertisement/';
	
	}
	
	
	//信息发布的实现
	public function controller_fabuinfo(){
		
		if($this->discount=='请选择折扣'){
			$this->discount='';
		}
		
		
		//判断该用户在一天之内的发布次数（一天只限制发布3次）
		$panduansql = "select count(*) as num from z_tuanmainlist where userid='".parent::__get('xb_userid')."' and faflag='1' 
				and create_datetime>='".date('Y-m-d 00:00:00')."' and create_datetime<='".date('Y-m-d 23:59:59')."'"; 
		parent::hy_log_str_add($panduansql);
		$panduanlist = parent::__get('HyDb')->get_row($panduansql);
		//parent::hy_log_str_add($panduanlist['num']);
		
			//图片的保存
			$filepath = $this->imgpath.date('Ym').'/';
			
			if(!file_exists($filepath)){
				mkdir($filepath,0777,true);
			}
			
			//图片文件名
			$filename = date('YmdHis').rand(1000,9999).'.'.$this->houzhui;
			
			//图片的存储路径--图片的绝对路径
			$filepathname = $filepath.$filename;
			
			
			//把解码转化为图片，然后存放路径中
			file_put_contents($filepathname,base64_decode($this->imgdata));
			
			//$filepathname ='http://xbapp.xinyouxingkong.com'.substr($filepathname,20);
			//七牛文件的上传parent::hy_yunyingshangcheck($this->phone);
			$filenameurl=parent::upload_qiniu('duibao-find',$filepathname,$filename);
			
			
			if($filenameurl){//图片上传成功
				//本地文件删除
				if(file_exists($filepathname)){
					unlink($filepathname);
				}
			}
			
			
			$theurl = 'http://127.0.0.1/'.date('YmdHis').mt_rand(1000,9999);
			
			//发布数据的入库处理
			$insertsql = "insert into z_tuanmainlist (hyflag,shstatus,userid,faflag,theurl,
						create_datetime,maintype,title,picurl,
						yuanprice,nowprice,reamrk,address,
						lat,lng,over_datetime,zflag,phone) values 
						('1','99','".parent::__get('xb_userid')."','1','".$theurl."',
						'".date('Y-m-d H:i:s')."','".$this->type."','".$this->proname."','".$filenameurl."', 
							'".$this->yuanprice."','".$this->nowprice."','".$this->discount."','".$this->address."',
							'".$this->lat."','".$this->lng."','".$this->over_datetime."','1','".$this->phone."')";
			
			$insertlist = parent::__get('HyDb')->execute($insertsql);
			
			if($insertlist===true){
				
				//判断该用户在一天之内的发布次数（一天只限制发布3次）
				$panduansql = "select count(*) as num from z_tuanmainlist where userid='".parent::__get('xb_userid')."' and faflag='1' 
				and create_datetime>='".date('Y-m-d 00:00:00')."' and create_datetime<='".date('Y-m-d 23:59:59')."'";
				parent::hy_log_str_add($panduansql);
				$panduanlist = parent::__get('HyDb')->get_row($panduansql);
				
				
				if($panduanlist['num']>=4){
					
				}else{
					
					//用户的积分增加--用户发布成功，增加10积分
					$updatescoresql = "update xb_user set keyong_jifen=keyong_jifen+10 where id='".parent::__get('xb_userid')."'";
					$updatescorelist = parent::__get('HyDb')->execute($updatescoresql);
					
					//积分详情的记录
					$getdescribe = '发布优惠信息获取10馅饼';
					$date=time();
					$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe)
							values ('".parent::__get('xb_userid')."','1','1','1','10','".$date."','".$getdescribe."')";
					parent::__get('HyDb')->execute($scoresql);
					
					$usersql = "select id,jiguangid from xb_user where id='".parent::__get('xb_userid')."' ";
					$userlist = parent::__get('HyDb')->get_row($usersql);
					
						
					$jiguangid = $userlist['jiguangid'];
					
					/* echo $jiguangid; */
					//极光推送
					$message = '恭喜你发布优惠信息获取10馅饼，请注意查看';
					
					//推送是我记录
					$time =time();
					$tuisongsql = "insert into xb_user_tuisong (userid,type,status,message,create_inttime)
							values ('".parent::__get('xb_userid')."','1','2','".$message."','".$time."')";
					$tuisonglist = parent::__get('HyDb')->execute($tuisongsql);
						
					parent::func_jgpush($jiguangid,$message,'1',$m_txt='',$m_time='86400');
					
				}
				
				
				$echoarr = array();
				$echoarr['returncode']='success';
				$echoarr['returnmsg']='发布成功！';
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n";//日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				$echoarr = array();
				$echoarr['returncode']='error';
				$echoarr['returnmsg']='发布失败！';
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n";//日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
		}
		
		
	/* } */
	
	
	
	//发现的操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		
		//店铺名称不能为空
		if($this->proname=='' || $this->proname=='优惠信息名称'){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠信息名称不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		
		}
		
		
	/* 	if($this->imgdata==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '图片不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		} */
		
		if($this->houzhui==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '图片后缀不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	
		//经度不能为空
		if($this->lat==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '纬度字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
		//纬度不能为空
		if($this->lng==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '经度字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
		
	
		if($this->address==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '地址不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}
		
		
		
		
		//当折扣为空时，原价现价不能为空
		if($this->discount=='' || $this->discount=='请选择折扣' ){
			
			if($this->nowprice==''){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '现价不能为空！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			if($this->yuanprice==''){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '原价不能为空！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
		//类型不能为空
		if($this->type==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '分类不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		
		}
		
	
	
		//信息发布入口
		$this->controller_fabuinfo();
	
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}