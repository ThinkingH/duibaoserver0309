<?php
/*
 * 基础处理数据的父类文件
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
	
	
	protected $HyDb;         //数据库初始化变量
	protected $JiPush;         //极光推送
	
	protected $set_md5key = '527aa50704b8e9e2529e1a03e6ccd912';  //校验设置的密钥
	
	//短信下发传递的校验参数
	protected $md5key='e0f8978c0677a01aeac12cc90eed0949';
	
	//公共校验变量
	protected $xb_thetype;  //操作类型编号，如101,102,103
	protected $xb_nowtime;  //时间戳，预留字段，用于后期校验增加安全性使用
	protected $xb_md5key;   //校验数据是否是从app端过来的数据
	protected $xb_usertype; //用户类型，1为正常用户，2为匿名用户，其他的也归为匿名用户 初始化访问时填3匿名用户
	protected $xb_userid;   //用户在平台的标识编号，平台全部以用户的标识编号作为用户的区分初始化访问时置空
	protected $xb_userkey;  //用户通讯的校验密钥，初始化访问时置空
	
	
	private $send_sms_ua = 'XBSC';  //验证码发送账户名称
	private $send_sms_pw = '012534';  //验证码发送账户密码
	//private $send_sms_url = 'http://121.42.205.244:18002/send.do';  //验证码发送接收地址
	private $send_sms_url = 'http://121.42.228.34/duanxinfasong/interface/smssend.php';  //验证码发送接收地址
	private $send_sms_max_time  = '120'; //验证码发送时间间隔描述，单个类型
	private $send_sms_max_count = '10';  //验证码当日发送最大次数，单个类型
	private $send_sms_vcode_minutes = '15';  //验证码有效分钟数
	
	//define(EARTH_RADIUS, 6371);//地球半径，平均半径为6371km
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
	
	private $wangzhantype; //网站的类型
	private $fwangzhantype; //发现网站的类型
	
	function __construct($input_data){
	
		//初始化数据库
		$this->HyDb = new HyDb();
		
		//极光推送的引入
		$this->JiPush = new JiPush();
	
		$this->server_time     = $_SERVER['REQUEST_TIME'];
		$this->create_date     = date('Y-m-d', $this->server_time);
		$this->create_datetime = date('Y-m-d H:i:s', $this->server_time);
		
		
		$this->xb_thetype      = isset($input_data['thetype']) ? $input_data['thetype']:'000';  //
		$this->xb_nowtime      = isset($input_data['nowtime']) ? $input_data['nowtime']:'';  //
		$this->xb_md5key       = isset($input_data['md5key'])  ? $input_data['md5key']:'';  //
		$this->xb_usertype     = isset($input_data['usertype'])? $input_data['usertype']:'';  //
		$this->xb_userid       = isset($input_data['userid'])  ? $input_data['userid']:'';  //
		$this->xb_userkey      = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
		
		$this->userdaymax   = 0;
		$this->usermonthmax = 0;
		$this->userallmax   = 0;
		$this->daymax       = 0; //该商品的当日兑换次数
		$this->price        = 0; //商品的金额价格
		$this->score        = 0;  //商品的积分价格
		$this->kucun        = 0;//商品库存
		
		
		
		$this->productid  = isset($input_data['productid'])?$input_data['productid']:'';
		
		$this->log_filepath    = LOGPATH;
		//$this->log_filename    = $this->xb_thetype.'_'.date('Y-m-d');
		$this->log_filename    = date('Y-m-d').'_'.$this->xb_thetype;
		$this->log_str         = '';
		
		
		//首页读取网站的类型
		/* $filepathname1 = URLUPDATE.'Public/Uploads/wangzhan.txt';
		$this->wangzhantype = file_get_contents($filepathname1);
		
		//发现数据读取
		$filepathname2 = URLUPDATE.'Public/Uploads/fwangzhan.txt';
		$this->fwangzhantype = file_get_contents($filepathname2); */
		
		
		
		unset($input_data);
		
		
	}
	
	
	function __destruct() {
		//调用日志写入函数，将日志数据写入对应日志文件
		if($this->log_str!='') {
			$this->write_file_log();
		}
	}
	
	
	function __get($property_name){
		return isset($this->$property_name) ? $this->$property_name : false;
	}
	
	
	function __set($property_name, $value){
		$this->$property_name = $value;
	}
	
	
	
	
	//基本的参数校验
	protected function func_base_check(){
		
		//检测是否是从app过来的数据
		if(''==$this->xb_md5key || $this->xb_md5key!=$this->set_md5key) {
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '客户端秘钥校验错误';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
			
		}
		
		if(!is_numeric($this->xb_nowtime)) {
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '时间戳不能为空且只能为数字';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}
		
		//判断用户的请求类型
		//对于非初始化的访问，全部进行用户校验判断
		if('1'==$this->xb_usertype) {
			//走手机号用户判断流程
			if(!is_numeric($this->xb_userid)) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户id格式不正确';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
			}
			
			if(strlen($this->xb_userkey)!=32) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户id对应校验密钥格式不正确';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
			}
			
			
			//判断用户id和校验key是否正确
			$r = $this->func_check_phoneuser();
			
			if(false===$r) {
				return false;
			}
			
			
		}else if('2'==$this->xb_usertype) {
			//走匿名用户判断流程
			
			if(!is_numeric($this->xb_userid)) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '临时用户id格式不正确';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
			}
			if(strlen($this->xb_userkey)!=32) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '临时用户id对应校验密钥格式不正确';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
			}
			
			//判断临时用户id和校验key是否正确
			$r = $this->func_check_randuser();
			if(false===$r) {
				return false;
			}
			
			
			
			
		}else {
			//归类为初始化访问
			//初始化访问不做判断
			
			
		}
		
		
		
		
		//返回true通过判断
		return true;
		
		
		
		
	}
	
	
	
	
	//手机用户访问参数校验
	protected function func_check_phoneuser() {
		//查询用户表，看该手机号用户是否存在
		$sql_getuserdata = "select id,is_lock,tokenkey
							from xb_user
							where id='".$this->xb_userid."'
							order by id limit 1";
		
		$list_getuserdata = $this->HyDb->get_row($sql_getuserdata);
		if(count($list_getuserdata)<=0) {
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '用户id不存在';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}else {
			if($list_getuserdata['is_lock']=='9') {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户被禁用，无法执行登录';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
			}else if($list_getuserdata['tokenkey']!=$this->xb_userkey) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户id对应密钥校验不通过';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
				
			}else {
				//登录判断通过
				return true;
				
				
			}
		}
		
		
	}
	
	
	
	
	//匿名用户访问参数校验
	protected function func_check_randuser() {
		//查询用户表，看改手机号用户是否存在
		$sql_getuserdata = "select id,tokenkey
							from xb_temp_user
							where id='".$this->xb_userid."'
							order by id limit 1";
		$list_getuserdata = $this->HyDb->get_row($sql_getuserdata);
		
		if(count($list_getuserdata)<=0) {
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '用户id不存在';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}else {
			if($list_getuserdata['tokenkey']!=$this->xb_userkey) {
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户id对应密钥校验不通过';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
				
			}else {
				//登录判断通过
				return true;
				
				
			}
		}
		
		
	}
	
	
	
	//随机key值返回函数
	protected function func_create_randkey() {
		return md5(time().mt_rand(10000,99999).mt_rand(10000,99999));
	}
	
	
	
	//匿名用户唯一标识id返回函数
	protected function func_create_randid() {
		return time().mt_rand(1000,9999);
	}
	
	
	//6位数字验证码生成函数
	protected function func_create_tempvcode() {
		return mt_rand(100,999).mt_rand(100,999);
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
	
	
	
	//md5key参数校验
	protected function func_md5key_check(){
		
		//判断客户端传递的校验值是否正确
		if($this->xb_md5key!=$this->keymd5){
			
			$echoarr = array();
			$echoarr['returncode']='error';
			$echoarr['returnmsg'] = '客户端秘钥校验错误';
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
			
		}else{
			return true;
		}
		
	}
	
	
	
	//校验用户提交的验证码是否和发送的验证码相等
	//馅饼流量app目前登录即为注册，所以只有短信验证码直接登录方式，需要特别注意，其他方式均为预留
	protected function func_vcode_check($type='1',$phone='',$vcode='') {
		
		$phone   = trim($phone);
		$vcode   = trim($vcode);
		
		if($phone=='' || $vcode=='') {
			
			$this->log_str .= 'func_vcode_check---手机号或者验证码或者短信内容为空'."\n"; //日志写入
			return false;
			
		}else {
			
			//苹果测试账号
			if($phone=='13800138008' && $vcode=='123456') {
				return true;
			}
			
			
			
			
			
			//查询数据库获取改手机号最近的一个验证码
			$sql_getlast_vcode = "select vcode
								from xb_vcode_send
								where type='".$type."'
								and phone='".$phone."'
								and sendtime>='".(time()-($this->send_sms_vcode_minutes*60))."'
								order by id desc limit 1";
			
			$list_getlast_vcode = $this->HyDb->get_one($sql_getlast_vcode);
			
			
			if($list_getlast_vcode=='' || strlen($list_getlast_vcode)<4) {
				
				$echoarr = array();
				$echoarr['returncode']='error';
				$echoarr['returnmsg'] = '验证码过期';
				$echoarr['dataarr'] = array();
				$this->log_str .= 'func_vcode_check---没有查询到验证码，或验证码不规范，或验证码过期---'.$list_getlast_vcode."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
				
			}else {
				//判断查询到的验证码是否和提交过来的验证码相等
				if($list_getlast_vcode!=$vcode) {
					
					$echoarr = array();
					$echoarr['returncode']='error';
					$echoarr['returnmsg'] = '验证码不正确';
					$echoarr['dataarr'] = array();
					$this->log_str .= 'func_vcode_check---验证码不正确---yuan:'.$list_getlast_vcode.'---user:'.$vcode."\n"; //日志写入
					echo json_encode($echoarr);
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
			$this->log_str .= 'func_send_sms---手机号或者验证码或者短信内容为空'."\n"; //日志写入
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
				$echoarr = array();
				$echoarr['returncode']='error';
				$echoarr['returnmsg'] = '短信发送已达当日上限';
				$echoarr['dataarr'] = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
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
					$echoarr = array();
					$echoarr['returncode']='error';
					$echoarr['returnmsg'] = '验证码发送太频繁';
					$echoarr['dataarr'] = array();
					$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----second:'.$fasong_jiangetime."\n"; //日志写入
					echo json_encode($echoarr);
					return false;
				}else {
					//调用验证码发送函数
					
					//调用下发验证码的链接
					/* $tm = date('Y-m-d H:i:s',(time()-100));
					$sendcodearr = array(
							'ua' => $this->send_sms_ua,
							'pw' => md5($this->send_sms_pw.$tm),
							'mb' => $phone,
							'ms' => $message,
							'tm' => $tm,
					); 
					
					$vcode_sendurl = $this->send_sms_url.'?'.HyItems::hy_urlcreate($sendcodearr);*/
					
					$time= time();
					$url = 'http://121.42.228.34/duanxinfasong/interface/smssend.php?md5key=e0f8978c0677a01aeac12cc90eed0949&nowtime='.$time.'&phone='.$phone.'&message='.urlencode($message);
					
					//$vcode_sendurl = $url.'?'.$this->md5key;
					
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
						return false;
						
						
					}else {
						
						if($content!='') {
							$sql_insert_vcode = "insert into xb_vcode_send (type,sendtime,phone,vcode,content) values(
												'".$type."','".time()."','".$phone."','".$vcode."','".$message."')";
							$this->log_str .= HyItems::hy_trn2space($sql_insert_vcode)."\n";
							$this->HyDb->execute($sql_insert_vcode);
							
							return true;
							
						}else {
							return false;
							
						}
						
						
					}
					
					
				}
				
				
			}
			
			
		}
		
		
	}
	
	
	//极光推送
	public function func_jgpush($jiguangid,$messagee){
		
		
		//极光推送的设置
		$m_type = '';//推送附加字段的类型
		$m_txt = '';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
		$m_time = '86400';//离线保留时间
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
	
	
	
	//检测手机号对应运营商
	protected function hy_yunyingshangcheck($phone='',$type='num') {
		
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
	protected function returnSquarePoint($lng, $lat,$distance = 300){
	
	$dlng =  2 * asin(sin($distance / (2 * $this->earth_radius)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);
	
	$dlat = $distance/$this->earth_radius;
	$dlat = rad2deg($dlat);
	
	
	return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
			);
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
			$this->typeidchild    = $productidlist['typeidchild'];//商品下架时间
			
			return $productidlist;
			
		
		}else{
			$this->userdaymax   = 0;
			$this->usermonthmax = 0;
			$this->userallmax   = 0;
			$this->daymax       = 0; //该商品的当日兑换次数
			$this->price        = 0; //商品的金额价格
			$this->score        = 0;  //商品的积分价格
			$this->kucun     = 0;//商品库存
			$this->zhifuway  = '1';//用户的支付方式
			$this->typeid    = '';//判断商品的类型11001--流量  21001--实物  12001--卡密   13001--卡券
			$this->goodsname = '';//商品名称
			$this->stop_datetime  = '';//商品下架时间
			$this->siteid    = '';//商品下架时间
			
			return array();
		}
		
	}
	
	//兑换参数的判断
	protected function check_duihuan_canshu(){
		
		
		//判断商品是否为空
		if($this->productid==''){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '商品id不能为空！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
			
		}else{
			return true;
		}
		//判断1.该商品的库存是否大于0  2.是否上架 3.用户每日 每月 每年的最大兑换次数
		if($this->kucun<=0){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '该商品的库存为零！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
			
		}else{
			return true;
		}
		
		if($this->stop_datetime<date('Y-m-d H:i:s')){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '此商品已下架！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		
		}else{
			return true;
		}
		
	}
	
	
	//判断该商品每日兑换的数量
	protected function check_duihuan_max_day(){
		
		
		//判断该商品每日兑换的数量
		$starttime = date('Y-m-d 00:00:00');
		$endtime   = date('Y-m-d 23:59:59');
		$maxduihuansql  = "select count(id) as maxnum from shop_userbuy where order_createtime>='".$starttime."' and order_createtime<='".$endtime."' and productid='".$this->productid."'";
		$maxduihuanlist = $this->HyDb->get_row($maxduihuansql);
		
		if($maxduihuanlist['maxnum']>$this->daymax){
				
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '超过该商品的每日最大兑换数量！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
				
		}else{
			return true;
		}
	}
	
	
	//1.判断该用户每日该商品的兑换数量
	protected function check_duihuan_user_day(){
		
		$starttime = date('Y-m-d 00:00:00');
		$endtime   = date('Y-m-d 23:59:59');
		$userbuysql  = "select count(id) as num from shop_userbuy where order_createtime>='".$starttime."'
				and order_createtime<='".$endtime."' and userid='".$this->xb_userid."' ";
		$userbuylist = $this->HyDb->get_row($userbuysql);
		
		if($userbuylist['num']>$this->userdaymax){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '超过用户每日兑换该商品的最大次数！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
				
		}else{
			return true;
		}
		
	}
	
	
	//2.商品每月的最大兑换次数
	protected function check_duihuan_user_month(){
		
		$startmonth = date('Y-m-01 00:00:00');
		$endmonth   = date('Y-m-31 23:59:59');
			
		$monthbuysql  = "select count(id) as monthnum from shop_userbuy where order_createtime>='".$startmonth."'
				and order_createtime<='".$endmonth."' and userid='".$this->xb_userid."'  ";
		$monthbuylist = $this->HyDb->get_row($monthbuysql);
		
		if($monthbuylist['monthnum']>$this->usermonthmax){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '超过用户每月兑换该商品的最大次数！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}else{
			return true;
		}
	}
	
	//判断该商品该用户每年的最大兑换次数
	protected function check_duihuan_user_year(){
		
		$startyear = date('Y-1-01 00:00:00');
		$endyear   = date('Y-12-31 23:59:59');
		
		$yearbuysql  = "select count(id) as yearnum from shop_userbuy where order_createtime>='".$startyear."'
				and order_createtime<='".$endyear."' and userid='".$this->xb_userid."'  ";
		$yearbuylist = $this->HyDb->get_row($yearbuysql);
		
		if($yearbuylist['yearnum']>$this->userallmax){
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '超过用户每年兑换该商品的最大次数！';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}else{
			return true;
		}
		
		
	}
	
	//用户积分的判断
	protected function check_scoremoney_user(){
		
		//查询用户的可用积分和金额
		$userscoresql  = "select keyong_jifen,keyong_money from xb_user where id='".$this->xb_userid."' ";
		$userscorelist = $this->HyDb->get_row($userscoresql);
		
		if(count($userscorelist)>0){
			
			$this->keyongjifen = $userscorelist['keyong_jifen'];
			$this->keyongmoney = $userscorelist['keyong_money'];
			
			/* //支付方式的判断
			$r = $this->check_zhifuway_user();
			
			return $r; */
			return $userscorelist;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] ='error';
			$echoarr['returnmsg']  = '用户id不存在';
			$echoarr['dataarr']    = array();
			$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
	}
	
	//支付方式的判断
	protected function check_zhifuway_user(){
		
		
		if($this->zhifuway=='1'){//积分支付
			
			if($this->keyongjifen<$this->score){
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户账户积分不足';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
				
			}else{
				return true;
			}
			
		}else if($this->zhifuway=='2'){//金额支付
			
			if($this->keyongmoney<$this->price){
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户账户金额不足';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
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
				$echoarr = array();
				$echoarr['returncode'] ='error';
				$echoarr['returnmsg']  = '用户账户总额不足';
				$echoarr['dataarr']    = array();
				$this->log_str .= $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				echo json_encode($echoarr);
				return false;
				
			}else{
				return true;
			}
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
	
		$insertuserbuysql = "insert into shop_userbuy(userid,siteid,typeid,mtype,name,price,score,productid,status,orderno,productnum,keystr,passwd,order_createtime,fh_phone,fh_address,fh_fahuotime,fh_shouhuotime,address_id,fh_shouhuoren) 
							values ('".$this->xb_userid."','".$this->siteid."','".$this->typeidchild."','".$mytype."','".$this->goodsname."','".$this->price."','".$this->score."','".$this->productid."','".$status."',
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
	
	
	//商品信息的更新
	protected function updateproductdata(){
		$updateproduct = "update shop_product set buycount=buycount+1,kucun=kucun-1,daymax=daymax+1 where id='".$this->productid."'";
		$updateproductsql = $this->HyDb->execute($updateproduct);
		
		return true;
	}
	
	//用户积分变化的更新
	protected function updateuserscore($xiaohaojifen,$xiaohaomoney){
		$userscoresql  = "update xb_user set keyong_jifen=keyong_jifen-'".$xiaohaojifen."',keyong_money= keyong_money-'".$xiaohaomoney."' where id='".$this->xb_userid."' ";
		$userscorelist = $this->HyDb->execute($userscoresql);
		
		return true;
		
	}
	
	//积分变动的插入
	protected function insertscore($goodsname,$xiaohaojifen,$xiaohaomoney){
		$getdescribe = '购买'.$goodsname.'消耗'.$xiaohaojifen;
		$gettime = time();
		$insertsql = "insert into xb_user_score (userid,goodstype,maintype,type,score,getdescribe,gettime) values
				 ('".$this->xb_userid."','1','2','9','".$xiaohaojifen."','".$xiaohaomoney."','".$getdescribe."','".$gettime."')";
		
		$this->HyDb->execute($insertsql);
		
		return true;
	}
	/*  
	 * //商品信息的更新
		$updateproduct = "update shop_product set buycount=buycount+1,kucun=kucun-1,daymax=daymax+1 where id='".$this->productid."'";
		$updateproductsql = parent::__get('HyDb')->execute($updateproduct);
		
		//用户积分的变化
		$userscoresql  = "update xb_user set keyong_jifen=keyong_jifen-'".$xiaohaojifen."',keyong_money= keyong_money-'".$xiaohaomoney."' where id='".parent::__get('xb_userid')."' ";
		$userscorelist = parent::__get('HyDb')->execute($userscoresql);
		
		//积分变动的插入
		$getdescribe = '购买'.$this->goodsname.'消耗'.$xiaohaojifen;
		$gettime = time();
		$insertsql = "insert into xb_user_score (userid,goodstype,maintype,type,score,getdescribe,gettime) values
				 ('".parent::__get('xb_userid')."','1','2','9','".$xiaohaojifen."','".$xiaohaomoney."','".$getdescribe."','".$gettime."')";
		
		parent::__get('HyDb')->execute($insertsql);*/
	
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
	
	
	//七牛图片上传--------2
	function upload_qiniu($bucket,$filepath,$savename){
	
	
		$qiniurl = 'http://127.0.0.1:8001/hyqiniu/init/hy_upload.php';
	
		$data = array(
				'bucket'   => $bucket,
				'filepath' => $filepath,
				'savename' => $savename,
	
		);
	
		//模拟数据访问
		$r=vpost($qiniurl,$data,$header=array(),$timeout=5000 );
	
		if(substr($r['content'],0,1)!='#' && $r['httpcode']=='200'){
	
			$truepath = json_decode($r['content'], true);
			//$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			$trueurl= $truepath['key'];//http://osv2nvwyw.bkt.clouddn.com/596c7fd36d942.png
			return $trueurl;
		}else{
			return false;
		}
	
	}
	
	
	//敏感字的过滤
	public function sensitive1($str){
		
		if(is_file('./sensitive.txt')){
			
			$filter_word = file('./sensitive.txt');//把整个文件读入一个数组中
			
			for($i=0;$i<count($filter_word);$i++){
				
				if(preg_match('/'.trim($filter_word[$i]).'/i',$str)){
					return true;//存在敏感词
				}else{
					return false;
				}
				
			}
			
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
