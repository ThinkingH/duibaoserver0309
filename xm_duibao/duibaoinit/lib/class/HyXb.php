<?php
/*
 * 兑宝接口文件的父类接口
 */
class HyXb{
	
	
	/*******************************************************************
	 * 初始化
	 *******************************************************************/
	protected static $server_time;
	protected static $create_datetime;
	protected static $create_date;
	protected static $log_filepath;    //日志文件对应目录
	protected static $log_filename;    //日志文件对应民名称
	
	
	private  $Redis;         //redis链接
	private  $HyDb;         //数据库初始化变量
	protected $JiPush;         //极光推送
	protected $qiniubucketarr;
	
//	protected $set_md5key = '527aa50704b8e9e2529e1a03e6ccd912';  //校验设置的密钥
	
	//短信下发传递的校验参数
	protected $md5key=MSGSENDKEY;
	
	//公共校验变量
	protected $version;   //接口版本,后台接口版本，与app无关,每个version版本均对应一个版本秘钥,100~999
	protected $system;    //操作系统，IOS/ANDROID/PC，非IOS、PC全部默认为ANDROID
	protected $sysversion;  ////APP系统版本，例100
	protected $thetype;  //操作类型编号，如101,102,103
	protected $nowtime;  //时间戳，预留字段，用于后期校验增加安全性使用
	protected $usertype; //用户类型，1为正常用户，2为匿名用户，其他的也归为匿名用户 初始化访问时填3匿名用户
	protected $userid;   //用户在平台的标识编号，平台全部以用户的标识编号作为用户的区分初始化访问时置空
	protected $userkey;  //用户通讯的校验密钥，初始化访问时置空
	
// 	protected $lat;
// 	protected $lng;
// 	protected $type;
	
	protected $userlistdata; //用户信息表
	
	private $send_sms_ua = 'XBSC';  //验证码发送账户名称
	private $send_sms_pw = '012534';  //验证码发送账户密码
	private $send_sms_url = 'http://121.42.228.34/duanxinfasong/interface/smssend.php';  //验证码发送接收地址
	private $send_sms_max_time  = '120'; //验证码发送时间间隔描述，单个类型
	private $send_sms_max_count = '10';  //验证码当日发送最大次数，单个类型
	private $send_sms_vcode_minutes = '15';  //验证码有效分钟数
	
	//地球半径，平均半径为6371km
	private $earth_radius = '6371';
	
	
	//商城对应的参数
	private $userdaymax;//用户每日最大兑换次数
	private $usermonthmax;//用户每月最大兑换次数
	private $userallmax;//用户终身的最大兑换次数
	private $daymax;//每日最大允许库存
	private $price;//商品的金额
	private $score;//商品的积分
	private $zhifuway;//用户的支付方式
	private $typeid;//商品的类型
	private $typeidchild;
	private $goodsname;//商品名称
	private $siteid;//渠道编号
	private $productid;//产品编号
	private $kucun;//商品库存
	private $stop_datetime;//商品下架时间
	private $keyongjifen;//用户可用积分
	private $keyongmoney;//可用金额
	
	
	
	//父类构造函数
	public function __construct($input_data){
		
		//初始化数据库
		$this->HyDb = new HyDb();
		
		//redis初始化
		//$this->Redis = new MyRedis();
		//$this->Redis = new MyRedis();
		
		//print_r($this->HyDb);
		
		//极光推送的引入
		$this->JiPush = new JiPush();
		
		$this->server_time     = $_SERVER['REQUEST_TIME'];
		$this->create_date     = date('Y-m-d', $this->server_time);
		$this->create_datetime = date('Y-m-d H:i:s', $this->server_time);
		
		$this->version = isset($input_data['version'])?$input_data['version']:'';
		$this->system  = isset($input_data['system'])?$input_data['system']:'';
		$this->sysversion = isset($input_data['sysversion'])?$input_data['sysversion']:'';
		$this->thetype    = isset($input_data['thetype'])?$input_data['thetype']:'';
		$this->nowtime    = isset($input_data['nowtime'])?$input_data['nowtime']:'';
		$this->usertype   = isset($input_data['usertype'])?$input_data['usertype']:'';
		$this->userid     = isset($input_data['userid'])?$input_data['userid']:'';
		$this->userkey    = isset($input_data['userkey'])?$input_data['userkey']:'';
		
// 		$this->lat = isset($input_data['lat'])?$input_data['lat']:'';
// 		$this->lng = isset($input_data['lng'])?$input_data['lng']:'';
// 		$this->type = isset($input_data['type'])?$input_data['type']:'';
		
		//商城数据的初始化
		$this->userdaymax    = 0;
		$this->usermoneymax = 0;
		$this->userallmax   = 0;
		$this->daymax       = 0;
		$this->price        = 0;
		$this->score        = 0;
		$this->keyongjifen  = 0;
		$this->keyongmoney  = 0;
		$this->kucun        = 0;
		
		$this->productid  = isset($input_data['productid'])?$input_data['productid']:'';//商品id
		
		$this->log_filepath   = LOGPATH.date('Y-m').'/'.date('Y-m-d').'/';
		$this->log_filename   = date('Y-m-d').'_'.$this->version.'_'.$this->system.'_'.$this->sysversion.'_'.$this->thetype;
		$this->log_str        = '';
		
		$this->qiniubucketarr = json_decode(QINIUBUCKETSTR,true);
		
		
		$input_data['imgdata'] = '';//图片内容置空
		//日志数据开始写入
		$this->log_str   = "\n".'BEGIN-------------------DUIBAO---------------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    '.$_SERVER["REQUEST_URI"]."\n".
				json_encode($input_data)."\n";
		
		unset($input_data);
		
	}
	
	
	public function __destruct() {
		//调用日志写入函数，将日志数据写入对应日志文件
		if($this->log_str!='') {
			$this->write_file_log();
		}
	}
	
	
	public function __get($property_name){
		return isset($this->$property_name) ? $this->$property_name : false;
	}
	
	
	public function __set($property_name, $value){
		$this->$property_name = $value;
	}
	
	
	//用户参数判断,分1-正式用户和2-临时用户
	public function func_usercheck(){
		if('1'==$this->usertype){//正常用户
			//查询用户表，判断该用户是否存在
			$sql_getuserdata = "select * from xb_user where id='".$this->userid."' order by id desc limit 1";
			$this->userlistdata = $this->HyDb->get_row($sql_getuserdata);
			if(count($this->userlistdata)<=0) {
				$echojsonstr = HyItems::echo2clientjson('109','用户id对应信息不存在');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
			}else{
				$ser_tokenkey = $this->userlistdata['tokenkey'];
				$ser_islock   = $this->userlistdata['is_lock'];
				//判断该用户是否被禁止
				if($ser_islock=='9'){
					$echojsonstr = HyItems::echo2clientjson('110','用户被禁用，无法执行登录');
					$this->log_str .= $echojsonstr."\n";
					echo $echojsonstr;
					return false;
				}else if(''==$this->userkey || $ser_tokenkey!=$this->userkey){
					$echojsonstr = HyItems::echo2clientjson('111','用户登录秘钥校验失败');
					$this->log_str .= $echojsonstr."\n";
					echo $echojsonstr;
					return false;
				}else{
					return true;
				}
			}
		}else if('2'==$this->usertype ){//匿名用户
			//查询用户表，判断该用户是否存在
			$sql_getuserdata = "select * 
							from xb_temp_user
							where id='".$this->userid."'
							order by id limit 1";
			$this->userlistdata = $this->HyDb->get_row($sql_getuserdata);
			
			if(count($this->userlistdata)<=0){
				$echojsonstr = HyItems::echo2clientjson('112','临时用户id不存在');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
			}else{
				if($this->userlistdata['tokenkey']!=$this->userkey){
					$echojsonstr = HyItems::echo2clientjson('113','临时用户登录秘钥校验失败');
					$this->log_str .= $echojsonstr."\n";
					echo $echojsonstr;
					return false;
				}else{
					return true;
				}
			}
			
		}else{
			//初始化不做判断，通过
			return false;
		}
	}
	
	
	//随机userkey值返回函数
	protected function func_create_randkey(){
		return md5(time().mt_rand(10000,99999).mt_rand(10000,99999));
	}
	
	//匿名用户唯一标识id返回函数
	protected function func_create_randid(){
		return time().mt_rand(1000,9999);
	}
	
	
	//6位数字验证码生成函数
	protected function func_create_tempvcode(){
		return mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
	}
	
	
	//type---1登陆，2注册，3重置
	//馅饼流量app目前登录即为注册，所以只有短信验证码直接登录方式，需要特别注意，其他方式均为预留
	protected function func_create_vcode_message($type='1',$vcode='') {
		$vcode = trim($vcode);
		if($vcode=='' || strlen($vcode)<4) {
			return false;
		}else {
			//返回的短信内容
			$sendmessage = '';
				
			if($type=='1') {
				//登录
				$sendmessage = '【兑宝流量】本次验证码为：'.$vcode.'，用于登录兑宝app,'.$this->send_sms_vcode_minutes.'分钟内有效';
	
			}else if($type=='2') {
				//注册
				$sendmessage = '【馅饼流量】本次验证码为：'.$vcode.'，用于注册馅饼app账号,'.$this->send_sms_vcode_minutes.'分钟内有效';
	
			}else if($type=='3') {
				//重置
				$sendmessage = '【馅饼流量】本次验证码为：'.$vcode.'，用于重置馅饼app密码,'.$this->send_sms_vcode_minutes.'分钟内有效';
	
			}else {
				return false;
			}
			
			return $sendmessage;
		}
	
	}
	
	
	//校验用户提交的验证码是否和发送的验证码相等
	//馅饼流量app目前登录即为注册，所以只有短信验证码直接登录方式，需要特别注意，其他方式均为预留
	protected function func_vcode_check($type='1',$phone='',$vcode='') {
	
		$phone   = trim($phone);
		$vcode   = trim($vcode);
	
		if($phone=='' || $vcode=='') {
			$echojsonstr = HyItems::echo2clientjson('114','手机号或验证码不能为空');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
				
		}else {
				
			//苹果测试账号
			if(($phone=='13800138008' || $phone=='13800138009') && $vcode=='123456') {
				return true;
			}
				
			//查询数据库获取改手机号最近的一个验证码
			$sql_getlast_vcode = "select vcode
								from xb_vcode_send
								where type='".$type."'
								and phone='".$phone."'
								and sendtime>='".(time()-($this->send_sms_vcode_minutes*60))."'
								order by id desc limit 1";
			//$this->log_str .= $sql_getlast_vcode."\n";
			$list_getlast_vcode = $this->HyDb->get_one($sql_getlast_vcode);
				
				
			if($list_getlast_vcode=='' || strlen($list_getlast_vcode)<4) {
				$echojsonstr = HyItems::echo2clientjson('114','验证码超过有效期');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
	
			}else {
				//判断查询到的验证码是否和提交过来的验证码相等
				if($list_getlast_vcode!=$vcode) {
					$echojsonstr = HyItems::echo2clientjson('114','验证码错误');
					$this->log_str .= $echojsonstr."\n";
					echo $echojsonstr;
					return false;
				}else {
					return true;
						
				}
	
			}
				
		}
	
	}
	
	
	
	//短信验证码发送函数
	//type---1登陆，2注册，3重置
	//馅饼流量app目前登录即为注册，所以只有短信验证码直接登录方式，需要特别注意，其他方式均为预留
	protected function func_send_sms($type='1',$phone='',$vcode='',$message=''){
	
		$phone   = trim($phone);
		$vcode   = trim($vcode);
		$message = trim($message);
	
		if($phone=='' || $vcode=='' || $message=='') {
			$echojsonstr = HyItems::echo2clientjson('115','手机号验证码短信内容不能为空');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
				
		}else {
			//记录发送出去的内容
			$this->log_str .= 'func_send_sms---'.$type.'---'.$phone.'---'.$vcode.'---'.$message."\n"; //日志写入
				
			//查询数据库判断当天发送的最大次数
			$sql_getnow_count = "select count(id) as con
								from xb_vcode_send
								where type='".$type."'
								and sendtime>='".strtotime(date('Y-m-d'))."'
								and phone='".$phone."'";
			$list_getnow_count = $this->HyDb->get_one($sql_getnow_count);
			if($list_getnow_count>=$this->send_sms_max_count) {
				$echojsonstr = HyItems::echo2clientjson('114','短信验证码获取次数已达当日上限');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
	
			}else {
				//判断此次发送与上次发送的时间间隔
				//查询数据库判断当天发送的最大次数
				$sql_getlast_time = "select sendtime
									from xb_vcode_send
									where type='".$type."'
									and phone='".$phone."'
									order by id desc limit 1";
				$list_getlast_time = $this->HyDb->get_one($sql_getlast_time);
				//间隔时间计算
				$fasong_jiangetime = time()-$list_getlast_time;
				if($fasong_jiangetime<=$this->send_sms_max_time) {
					$echojsonstr = HyItems::echo2clientjson('114','短信验证码获取频繁');
					$this->log_str .= $echojsonstr."\n";
					echo $echojsonstr;
					return false;
				}else {
					//调用验证码发送函数
					$url = 'http://121.42.228.34/duanxinfasong/interface/smssend.php?md5key=e0f8978c0677a01aeac12cc90eed0949&nowtime='.time().'&phone='.$phone.'&message='.urlencode($message);
					$res = HyItems::vget($url,3000);
						
					$content  = isset($res['content'])  ? trim($res['content']) : '';
					$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
					$run_time = isset($res['run_time']) ? $res['run_time'] : '';
					$errorno  = isset($res['errorno'])  ? $res['errorno'] : '';
						
						
					//将curl数据日志写入数据库
					$this->log_str   .= $httpcode.'  -  '.
							$run_time.'  -  '.
							$errorno.'  -  '.
							$url.'  -  '.
							HyItems::hy_trn2space($content)."\n";
					if($httpcode!=200) {
						$echojsonstr = HyItems::echo2clientjson('114','验证码发送失败，系统错误');
						$this->log_str .= $echojsonstr."\n";
						echo $echojsonstr;
						return false;
					}else {
	
						if(trim($content)!='ok') {
							$echojsonstr = HyItems::echo2clientjson('114','验证码发送失败，系统错误');
							$this->log_str .= $echojsonstr."\n";
							echo $echojsonstr;
							return false;
								
						}else {//type,sendtime,phone,vcode,content
							$sql_insert_vcode = "insert into xb_vcode_send (type,sendtime,phone,vcode,content) values(
												'".$type."','".time()."','".$phone."','".$vcode."','".$message."')";
							$this->log_str .= HyItems::hy_trn2space($sql_insert_vcode)."\n";
							$this->HyDb->execute($sql_insert_vcode);
								
							return true;
						}
					}
				}
			}
		}
	}
	
	
	//极光推送
	public function func_jgpush($jiguangid,$messagee,$m_type='',$m_txt='',$m_time='86400'){
	
		//极光推送的设置
		/* $m_type = '';//推送附加字段的类型
			$m_txt = '';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
			$m_time = '86400';//离线保留时间 */
		$receive = array('alias'=>array($jiguangid));//别名
		//$receive = array('alias'=>array('073dc8672c25d8d023328d06dbbd1230'));//别名
		$content = $messagee;
		//$message="";//存储推送状态
		$result = $this->JiPush->push($receive,$content,$m_type,$m_txt,$m_time);
	
		if($result){
			$res_arr = json_decode($result, true);
	
			if(isset($res_arr['error'])){                       //如果返回了error则证明失败
				echo $res_arr['error']['message'];          //错误信息
				$error_code=$res_arr['error']['code'];             //错误码
				switch ($error_code) {
					case 200:
						$message= '发送成功！';
						break;
					case 1000:
						$message= '失败(系统内部错误)';
						break;
					case 1001:
						$message = '失败(只支持 HTTP Post 方法，不支持 Get 方法)';
						break;
					case 1002:
						$message= '失败(缺少了必须的参数)';
						break;
					case 1003:
						$message= '失败(参数值不合法)';
						break;
					case 1004:
						$message= '失败(验证失败)';
						break;
					case 1005:
						$message= '失败(消息体太大)';
						break;
					case 1008:
						$message= '失败(appkey参数非法)';
						break;
					case 1020:
						$message= '失败(只支持 HTTPS 请求)';
						break;
					case 1030:
						$message= '失败(内部服务超时)';
						break;
					default:
						$message= '失败(返回其他状态，目前不清楚额，请联系开发人员！)';
						break;
				}
			}else{
				$message="ok";
			}
		}else{//接口调用失败或无响应
			$message='接口调用失败或无响应';
		}
	
		//return $message;
	}
	
	
	//返回用户列表信息
	protected function func_retsqluserdata($useridarr=array(),$imgwidth=50,$imgheight=50) {
		$returnarr = array();
		$uuarr = array();
		foreach($useridarr as $valu) {
			if(is_numeric($valu)) {
				array_push($uuarr,$valu);
			}
		}
		if(!is_array($uuarr) || count($uuarr)<=0) {
			return $returnarr;
		}else {
			$instr = '('.implode(',',$uuarr).')';
			$sql_userlist = "select id,nickname,touxiang,phone from xb_user where id in ".$instr;
			$list_userlist = $this->HyDb->get_all($sql_userlist);
			foreach($list_userlist as $valus) {
				$tmparr = array();
				$tmparr['nickname'] = $valus['nickname'];
				$tmparr['phone'] = $valus['phone'];
				if(''==$valus['touxiang']) {
					$valus['touxiang'] = 'default_user.png';
				}
				$tmparr['touxiang'] = HyItems::hy_qiniuimgurl('duibao-basic',$valus['touxiang'],$imgwidth,$imgheight,true);
				$returnarr[$valus['id']] = $tmparr;
			}
			unset($list_userlist,$uuarr,$useridarr,$tmparr,$sql_userlist,$instr);
			return $returnarr;
				
		}
	}
	
	
	protected function func_userid_datatiqu($usdataarr=array(),$userid=0,$ziduan='') {
		$list = isset($usdataarr[$userid])?$usdataarr[$userid]:array();
		$retstr = isset($list[$ziduan])?$list[$ziduan]:'';
		unset($usdataarr,$userid,$list);
		return $retstr;
	}
	
	public function func_isImage($filename){
		$types = '.gif|.jpeg|.png|.bmp|.jpg';//定义检查的图片类型
		if(file_exists($filename)){
			$info = getimagesize($filename);
			$ext = image_type_to_extension($info['2']);
			return stripos($types,$ext);
		}else{
			return false;
		}
	}
	
	
	//检测手机号对应运营商
	protected function yunyingshangcheck($phone='',$type='num') {
	
		$phone = trim($phone);
	
		if($phone=='') {
			return false;
		}
		//截取手机号吗前三位
		$top3_phone = substr($phone,0,3);
	
		//运营商号段定义
		$topphonearr = array(
				'133' => '中国电信', '153' => '中国电信', '180' => '中国电信', '181' => '中国电信', '189' => '中国电信', '177' => '中国电信', '173' => '中国电信',
				'130' => '中国联通', '131' => '中国联通', '132' => '中国联通', '155' => '中国联通', '156' => '中国联通', '145' => '中国联通', '185' => '中国联通',
				'186' => '中国联通', '176' => '中国联通', '185' => '中国联通',
				'134' => '中国移动', '135' => '中国移动', '136' => '中国移动', '137' => '中国移动', '138' => '中国移动', '139' => '中国移动', '150' => '中国移动',
				'151' => '中国移动', '152' => '中国移动', '158' => '中国移动', '159' => '中国移动', '182' => '中国移动', '183' => '中国移动', '184' => '中国移动',
				'157' => '中国移动', '187' => '中国移动', '188' => '中国移动', '147' => '中国移动', '178' => '中国移动', '184' => '中国移动',
		);
	
		if(isset($topphonearr[$top3_phone])) {
	
			if($type=='num') {
				$y_yunying = false;
				if($topphonearr[$top3_phone]=='中国移动') {
					$y_yunying = 1;
				}else if($topphonearr[$top3_phone]=='中国联通') {
					$y_yunying = 2;
				}else if($topphonearr[$top3_phone]=='中国电信') {
					$y_yunying = 3;
				}
				return $y_yunying;
	
			}else {
				return $topphonearr[$top3_phone];
			}
	
		}else {
			return false;
		}
	}
	
	//经纬坐标的计算
	/**
	 *计算某个经纬度的周围某段距离的正方形的四个点
	 *
	 *@param lng float 经度
	 *@param lat float 纬度
	 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
	 *@return array 正方形的四个点的经纬度坐标
	 */
	protected function returnSquarePoint($lng=0, $lat=0,$distance = 300){
		if(0===$lng && 0===$lat) {
			return '';
		}
		if(!is_float($lng) && !is_numeric($lng)){
			return '';
		}
		if(!is_float($lat) && !is_numeric($lat)){
			return '';
		}
		
		$dlng =  2 * asin(sin($distance / (2 * $this->earth_radius)) / cos(deg2rad($lat)));
		$dlng = rad2deg($dlng);
	
		$dlat = $distance/$this->earth_radius;
		$dlat = rad2deg($dlat);
		
		$squares = array(
					'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
					'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
					'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
					'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
		);
		
		$where_sql = " and lat<>0 and lat>'".$squares['right-bottom']['lat']."' and lat<'".$squares['left-top']['lat']."'
					and lng>'".$squares['left-top']['lng']."' and lng<'".$squares['right-bottom']['lng']."' ";
		
		return $where_sql;
	}
	
	
	/**
	 * 计算两组经纬度坐标 之间的距离
	 * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
	 * return m or km
	 */
	protected function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
		//$EARTH_RADIUS=6378.137;//6371
		$EARTH_RADIUS=6371;
		$PI=3.1415926;
		$radLat1 = $lat1 * $PI / 180.0;
		$radLat2 = $lat2 * $PI / 180.0;
		$a = $radLat1 - $radLat2;
		$b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
		$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
		$s = $s * $EARTH_RADIUS;
		$s = round($s * 1000);
		if ($len_type > 1)
		{
			$s /= 1000;
		}
		return round($s,$decimal);
	}
	
	
	//地理位置转换
	protected function getlnglat($address){
	
		$url = 'http://api.map.baidu.com/geocoder?address=urlencode('.$address.')&output=json&key=WPzUoVnSMWZXrUuSR5Vs22Cd17yhCZeD';
	
		$data = vget($url);
	
		$truepath = json_decode($data['content'], true);
	
	
		if($truepath['status']=='OK'){//请求成功
				
			return $truepath['result']['location'];
				
		}else{
			return false;
		}
	
	}
	
	
	
	
	
	//6位字母+数字的组合
	function getRandomString($len, $chars=null)
	{
		if (is_null($chars)) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		}
		mt_srand(10000000*(double)microtime());
		for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
			$str .= $chars[mt_rand(0, $lc)];
		}
		return $str;
	}
	
	
	//积分记录的插入
	protected function insert_userscore($tablename='xb_user_score',$userid='',$maintype='1',$type='1',$score='0',$getdescribe='',$remark=''){
		if($userid==''){
			$userid=$this->userid;
		}
		$insertuserscore_sql = "insert into $tablename (userid,maintype,type,score,getdescribe,gettime,remark) 
								values ('".$userid."','".$maintype."','".$type."','".$score."','".$getdescribe."','".time()."','".$remark."')"; 
				$this->log_str .= HyItems::hy_trn2space($insertuserscore_sql)."\n";
				$this->HyDb->execute($insertuserscore_sql);
	}
	
	//推送信息的插入
	protected function insert_usertuisong($tablename='xb_user_tuisong',$userid='',$type='1',$status='1',$taskid='0',$message=''){
		if($userid==''){
			$userid=$this->userid;
		}
		$tuisonginsert_sql = "insert into $tablename (userid,type,status,taskid,message,create_inttime) values
									('".$userid."','".$type."','".$status."','".$taskid."','".$message."','".$this->server_time."')";
		$this->log_str .= HyItems::hy_trn2space($tuisonginsert_sql)."\n";
		$this->HyDb->execute($tuisonginsert_sql);
	}
	
	//用户信息的变动
	protected function update_userscore($tablename='xb_user',$changscore='0',$type='1',$userid='' ){
		if($userid==''){
			$userid=$this->userid;
		}
		if($type=='1'){//积分的增加
			$updatescore_sql = "update $tablename set keyong_jifen = keyong_jifen + '".$changscore."'
							where id='".$userid."'  ";
		}else if($type=='2'){//积分的减少
			$updatescore_sql = "update $tablename set keyong_jifen = keyong_jifen - '".$changscore."'
							where id='".$userid."'  ";
		}
		$this->log_str .= HyItems::hy_trn2space($updatescore_sql)."\n";
		$this->HyDb->execute($updatescore_sql);
	}
	
	
	//新人记录表
	protected function insert_newusers($tablename='newusers',$type='',$phone='',$openid='',$score='0'){
		$libaosql = "insert into $tablename (userid,type,phone,openid,libao,createtime)
					values ('".$this->userid."','".$type."','".$phone."','".$openid."','".$score."','".$this->create_datetime."')";
		$libaolist = $this->HyDb->execute($libaosql);
	}
	
	
	 //附近查询字段的判断
	protected function func_latandlngcheck(){
		
		//经度不能为空
		if(!is_float($this->lat) && !is_numeric($this->lat)){
			$echojsonstr = HyItems::echo2clientjson('116','经度参数格式错误');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		
		}
		
		//纬度不能为空
		if(!is_float($this->lng) && !is_numeric($this->lng)){
			$echojsonstr = HyItems::echo2clientjson('116','纬度参数格式错误');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
		
	}
	
	
	//不同支付类型下，金额的展示
	public function func_diffzhifutype($feetype,$changmoney='0',$changscore='0'){
		if(!is_numeric($changmoney)) {
			$changmoney = 0;
		}
		if(!is_numeric($changscore)) {
			$changscore = 0;
		}
		$totalnum = '';
		if($feetype=='1'){//积分
			$totalnum = '¥'.number_format($changmoney, 2)+$changscore.'馅饼';
		}else if($feetype=='2'){//金额
			$totalnum = '¥'.number_format($changmoney, 2);
		}else if($feetype=='3'){//混合=积分+支付
			
		}else if($feetype=='4' || $feetype=='5'){
			$totalnum='免费';
		}
		
		return $totalnum;
	}
	
	
	//金额的换算
	public function formatmoney($cmoney='0'){
		
		return number_format($cmoney,2);
	}
	
	//七牛图片上传
	public function upload_qiniu($bucket,$filepath,$savename,$rewrite='no'){
		$qiniurl = QINIUURL.'hy_upload.php';
		//$this->log_str .= $qiniurl."\n";
		$dataarr = array(
				'bucket'   => $bucket,
				'filepath' => $filepath,
				'savename' => $savename,
				'rewrite' => $rewrite,
		);
		$datastr = HyItems::hy_urlcreate($dataarr);
		//模拟数据访问
		$r = HyItems::vpost($qiniurl,$datastr,$header=array(),$timeout=5000 );
		$this->log_str .= var_export($dataarr,1)."\n";
		$this->log_str .= var_export($r,1)."\n";
		if(substr($r['content'],0,1)!='#' && $r['httpcode']=='200'){
			$truepath = json_decode($r['content'], true);
			//$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			$filename= $truepath['key'];
			return $filename;
		}else{
			return false;
		}
	}
	
	
	//七牛图片删除
	public function delete_qiniu($bucket,$delname){
		$qiniurl = QINIUURL.'hy_delete.php';
		$dataarr = array(
				'delbucket'   => $bucket,
				'delname' => $delname,
		);
		$datastr = HyItems::hy_urlcreate($dataarr);
		//模拟数据访问
		$r = HyItems::vpost($qiniurl,$datastr,$header=array(),$timeout=5000 );
		//$this->log_str .= var_export($dataarr,1)."\n";
		//$this->log_str .= var_export($r,1)."\n";
		if(substr($r['content'],0,1)!='#' && $r['httpcode']=='200'){
			return true;
		}else{
			return false;
		}
	}
	
	
	/*
	 * 广告的读取
	 */
	public function func_advertisement($m='1',$type='2'){
	
		if($type=='2'){//网页下载
			$adsql  = "select * from ad_advertisement where flag=1 and gflag=2 order by id desc limit $m,1";
		}else if($type=='3'){//广告下载
			$adsql  = "select * from ad_advertisement where flag=1 and gflag=3 order by id desc limit $m,1";
		}
	
		$adlist = $this->HyDb->get_row($adsql);
		return $adlist;
	}
	
	
	//商城的参数判断
	public function shopduihuan_check(){
	
		//判断是否审核通过，是否启用
		$productidsql  = "select * from shop_product where flag='1' and status='1' and id='".$this->productid."'";
		$productidlist = $this->HyDb->get_row($productidsql);
	
		if(count($productidlist)>0){//该商品存在
				
			//该商品每次下载的次数
			$this->userdaymax   = $productidlist['userdaymax'];
			$this->usermonthmax = $productidlist['usermonthmax'];
			$this->userallmax   = $productidlist['userallmax'];
			$this->daymax       = $productidlist['daymax']; //该商品的当日兑换次数
			$this->price        = $productidlist['price']; //商品的金额价格
			$this->score        = $productidlist['score'];  //商品的积分价格
			$this->kucun     = $productidlist['kucun'];//商品库存
			$this->zhifuway  = $productidlist['feetype'];//用户的支付方式
			$this->typeid    = $productidlist['typeid'];//判断商品的类型
			$this->goodsname = $productidlist['name'];//商品名称
			$this->stop_datetime  = $productidlist['stop_datetime'];//商品下架时间
			$this->siteid    = $productidlist['siteid'];//商品下架时间
			$this->typeidchild    = $productidlist['typeidchild'];//商品子类型
				
			return $productidlist;
				
	
		}else{
			$this->userdaymax   = 0;
			$this->usermonthmax = 0;
			$this->userallmax   = 0;
			$this->daymax       = 0; //该商品的当日兑换次数
			$this->price        = 0; //商品的金额价格
			$this->score        = 0;  //商品的积分价格
			$this->kucun     = 0;//商品库存
			$this->zhifuway  = '1';//用户的支付方式1-积分支付
			$this->typeid    = '';//判断商品的类型
			$this->goodsname = '';//商品名称
			$this->stop_datetime  = '';//商品下架时间
			$this->siteid    = '';//商品下架时间
				
			return array();
		}
	
	}
	
	
	//用户积分的判断
	protected function check_scoremoney_user(){
	
		//查询用户的可用积分和金额
		$userscoresql  = "select keyong_jifen,keyong_money from xb_user where id='".$this->userid."' ";
		$userscorelist = $this->HyDb->get_row($userscoresql);
	
		if(count($userscorelist)>0){
				
			$this->keyongjifen = $userscorelist['keyong_jifen'];
			$this->keyongmoney = $userscorelist['keyong_money'];
				
			return $userscorelist;
		}else{
			$echojsonstr = HyItems::echo2clientjson('201','用户id不存在');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
	
	}
	
	
	//账户余额
	protected function check_zhifuway_user($tid=1){
	
		if($this->zhifuway=='1'){//积分支付
			//需要总积分
			if($this->keyongjifen<$this->score*$tid){
				$echojsonstr = HyItems::echo2clientjson('202','您的馅饼不足，无法兑换该商品');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
			}else{
				return true;
			}
		}else if($this->zhifuway=='2'){//金额支付
			if($this->keyongmoney<$this->price*$tid){
				$echojsonstr = HyItems::echo2clientjson('202','您的账户金额不足，无法兑换该商品');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
			}else{
				return true;
			}
		}else if($this->zhifuway=='3'){//积分+金额
			//账户的积分
			$this->keyongmoney = $this->keyongmoney*DISCOUNT;
			$this->keyongjifen = $this->keyongjifen+$this->keyongmoney;
			//商品的积分
			$this->price = $this->price*DISCOUNT;
			$this->score = $this->price+$this->score;
				
			if($this->keyongjifen<$this->score){
				$echojsonstr = HyItems::echo2clientjson('202','您的账户金额不足，无法兑换该商品');
				$this->log_str .= $echojsonstr."\n";
				echo $echojsonstr;
				return false;
			}else{
				return true;
			}
		}else{
			//不做判断
		}
	
	}
	
	
	//兑换参数的判断
	protected function check_duihuan_canshu(){
	
		//判断商品是否为空
		if($this->productid==''){
			$echojsonstr = HyItems::echo2clientjson('203','商品id不能为空');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
		
		//判断1.该商品的库存是否大于0  2.是否上架 3.用户每日 每月 每年的最大兑换次数
		if($this->kucun<=0){
			$echojsonstr = HyItems::echo2clientjson('204','商品库存不足');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
		
		if($this->stop_datetime<date('Y-m-d H:i:s')){
			$echojsonstr = HyItems::echo2clientjson('205','商品已下架');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
		
		return true;
	
	}
	
	
	//判断该商品每日兑换的数量
	protected function check_duihuan_max_day(){
	
		//判断该商品每日兑换的数量
		$maxduihuansql  = "select count(id) as maxnum from shop_userbuy  
							where order_createtime>='".date('Y-m-d 00:00:00')."' and order_createtime<='".date('Y-m-d 23:59:59')."' and productid='".$this->productid."'";
		$maxduihuanlist = $this->HyDb->get_row($maxduihuansql);
	
		if($maxduihuanlist['maxnum']>$this->daymax){
			
			$echojsonstr = HyItems::echo2clientjson('206','超过该商品的每日最大兑换数量');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}else{
			return true;
		}
	}
	
	
	//判断该用户每日该商品的兑换数量
	protected function check_duihuan_user_day(){
	
		$userbuysql  = "select count(id) as num from shop_userbuy where order_createtime>='".date('Y-m-d 00:00:00')."'
				and order_createtime<='".date('Y-m-d 23:59:59')."' and userid='".$this->userid."' ";
		$userbuylist = $this->HyDb->get_row($userbuysql);
	
		if($userbuylist['num']>$this->userdaymax){
			$echojsonstr = HyItems::echo2clientjson('207','超过每日兑换最大次数');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}else{
			return true;
		}
	}
	
	
	//2.商品每月的最大兑换次数
	protected function check_duihuan_user_month(){
	
		$monthbuysql  = "select count(id) as monthnum from shop_userbuy where order_createtime>='".date('Y-m-01 00:00:00')."'
				and order_createtime<='".date('Y-m-31 23:59:59')."' and userid='".$this->userid."'  ";
		$monthbuylist = $this->HyDb->get_row($monthbuysql);
	
		if($monthbuylist['monthnum']>$this->usermonthmax){
			$echojsonstr = HyItems::echo2clientjson('208','超过用户每月兑换该商品的最大次数');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}else{
			return true;
		}
	}
	
	//判断该商品该用户每年的最大兑换次数
	protected function check_duihuan_user_year(){
	
		$yearbuysql  = "select count(id) as yearnum from shop_userbuy where order_createtime>='".date('Y-1-01 00:00:00')."' 
				and order_createtime<='".date('Y-12-31 23:59:59')."' and userid='".$this->userid."'  ";
		$yearbuylist = $this->HyDb->get_row($yearbuysql);
	
		if($yearbuylist['yearnum']>$this->userallmax){
			$echojsonstr = HyItems::echo2clientjson('209','超过用户每年兑换该商品的最大次数');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}else{
			return true;
		}
	
	}
	
	
	//购物车的插入
	protected function userbuy_insert_data($orderno='',$tid='',$code='',$passwd='',$fh_phone='',$fh_address='',$fh_fahuotime='',$fh_shouhuotime='',$address_id='',$shouhuoren='',$typeid='1',$mytype='1') {
	
		if($typeid=='1'){
			$status = '4';//虚拟商品，状态为4
			$fh_shouhuotime = date('Y-m-d H:i:s');
		}else if($typeid=='2'){
			$status='3'; //实物状态
		}
	
		$insertuserbuysql = "insert into shop_userbuy(userid,siteid,typeid,childtypeid,mtype,name,price,score,productid,status,orderno,productnum,keystr,passwd,order_createtime,fh_phone,fh_address,fh_fahuotime,fh_shouhuotime,address_id,fh_shouhuoren)
							values ('".$this->userid."','".$this->siteid."','".$this->typeid."','".$this->typeidchild."','".$mytype."','".$this->goodsname."','".$this->price."','".$this->score."','".$this->productid."','".$status."',
			 				'".$orderno."','".$tid."','".$code."','".$passwd."','".$this->create_datetime."','".$fh_phone."','".$fh_address."','".$fh_fahuotime."','".$fh_shouhuotime."','".$address_id."','".$shouhuoren."')";
		//echo $insertuserbuysql;
		$insertuserbuylist = $this->HyDb->execute($insertuserbuysql);
	
		$this->log_str .= HyItems::hy_tospace($insertuserbuysql)."\n";
	
		return true;
	
	}
	
	
	//购物订单的判断
	protected function repeat_userbuy_orderno($orderno=''){
		$buyproductsql  = "select id,keystr,orderno,order_createtime,name,price,score,typeid,productid from shop_userbuy where orderno='".$orderno."' ";
		$buyproductlist = $this->HyDb->get_all($buyproductsql);
	
		if(count($buyproductlist)>0){
			return $buyproductlist;
		}else{
			return false;
		}
	
	}
	
	
	function sensitive($str){
	
		$lujing = SEURL.'\sensitive.txt';
	
		if (is_file($lujing)){//判断给定文件名是否为一个正常的文件
				
			$filter_word = file($lujing);//把整个文件读入一个数组中
	
			for($i=0;$i<count($filter_word);$i++){//应用For循环语句对敏感词进行判断
				if(preg_match("/".trim($filter_word[$i])."/i",$str)){//应用正则表达式，判断传递的留言信息中是否含有敏感词
						
					return true;
				}
			}
		}
		return false;
	}
	
	
	//sql语句查询缓存输出
	protected function func_runtime_sql_data($selectsql='',$tmptime='') {
		
		if($tmptime==''){//$tmptime!='' && 
			$tmptime=TMPSQLTIME;
		}else if(!is_numeric($tmptime)){
			$echojsonstr = HyItems::echo2clientjson('204','缓存时间不合法');
			$this->log_str .= $echojsonstr."\n";
			echo $echojsonstr;
			return false;
		}
		
		$list = array();
		$selectsql = trim($selectsql);
		$tmp_sql_md5str = md5($selectsql);
		$tmpsqlfilepathname = TMPSQLPATH.$tmp_sql_md5str;
		if(file_exists($tmpsqlfilepathname)) {
			//获取文件上次修改更新时间
			$lastuptime = filemtime($tmpsqlfilepathname);
			if((time()-$lastuptime)<$tmptime) {//设置5分钟缓存一次
				//直接使用缓存数据
				$list = json_decode(file_get_contents($tmpsqlfilepathname),true);
			}else {
				$list  = $this->HyDb->get_all($selectsql);
				if(is_array($list)) {
					file_put_contents($tmpsqlfilepathname, json_encode($list));
				}
			}
		}else {
			$list = $this->HyDb->get_all($selectsql);
			if(is_array($list)) {
				file_put_contents($tmpsqlfilepathname, json_encode($list));
			}
		}
		return $list;
	}
	
	
	/**
	 * 日志变量数据追加，即将子类的日志变量数据追加到父类的日志变量数据中
	 */
	protected function hy_log_str_add($addlog) {
		$this->log_str .= $addlog;
	}
	
	
	/**
	 * 日志写入封装函数
	 */
	protected function write_file_log() {
	
		$path = $this->log_filepath;
		$name = $this->log_filename.'.log';
		$data = $this->log_str;
	
		//将数据写入日志文件
		HyItems::hy_writelog($path, $name, $data);
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}