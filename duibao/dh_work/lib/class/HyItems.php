<?php

/** 
 * HyItems
 * 静态方法库
 * author:yu
 * 
 */

class HyItems {
	
	
	/**
	 * 从数组中取特定值
	 * 
	 * @param unknown_type $arr 数组名
	 * @param unknown_type $key 字段关键字
	 * @return unknown
	 */
	public static function arrayItem($arr, $key){
		if( ! is_array($arr) || ! array_key_exists($key, $arr) ){
			return FALSE;
		}else {
			return $arr[$key];
		}
	}
	
	
	
	/**
	 * 将xml字符串转换为xml对象
	 * @param string $xmlstr
	 * @return boolean|string
	 */
	public static function hy_xmldecode($xmlstr='') {
	
		$xmlstr = trim($xmlstr);
		$xml_obj = '';
	
		if($xmlstr=='') {
			return false;
		}else {
			$xml_obj = @simplexml_load_string($xmlstr);
			if(is_object($xml_obj)) {
				return $xml_obj;
			}else {
				return false;
			}
		}
	
	}
	
	
	
	/**
	 * 将数组中的字段拼接成url参数
	 * @param unknown $urlarr
	 * @return string
	 */
	public static function hy_urlcreate( $urlarr=array()) {
	
		$baseurl = '';
	
		if( is_array($urlarr) && count($urlarr)>0 ) {
				
			foreach($urlarr as $key => $val) {
				$baseurl .= $key.'='.urlencode($val).'&';
			}
				
			$baseurl = substr($baseurl,0,(strlen($baseurl)-1));
		}
	
		return $baseurl;
	
	}
	
	
	
	/**
	 * 模拟POST
	 * @param unknown $url
	 * @param unknown $data
	 * @param unknown $header
	 * @param number $timeout
	 * @return arr $retarr
	 */
	public static function vpost($url,$data,$header=array(),$timeout=5000 ){ // 模拟提交数据函数
		
		if( ! function_exists('curl_init') ){
			return FALSE;
		}
		
		$headerArr = array();
		foreach( $header as $n => $v ) {
			$headerArr[] = $n.':'.$v;
		}
		
		
		$curl = curl_init(); // 启动一个CURL会话
		
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		
		//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		
		curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
		curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
		
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
		if(count($headerArr)>0) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);//设置HTTP头
		}
		
		$content  = curl_exec($curl); //返回结果
		$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
		$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
		$errorno  = curl_errno($curl);
		
		//关闭curl
		curl_close($curl);
		
		
		//定义return数组变量
		$retarr = array();
		$retarr['content']  = $content;
		$retarr['httpcode'] = $httpcode;
		$retarr['run_time'] = $run_time;
		$retarr['errorno']  = $errorno;
		
		return $retarr;
		
	}
	
	
	
	
	/**
	 * 模拟POST  同时获取header头和body
	 * @param unknown $url
	 * @param unknown $data
	 * @param unknown $header
	 * @param number $timeout
	 * @return arr $retarr
	 * 
	 * 关于$header,请使用 $arr[key] = val; 的方式
	 * 
	 */
	public static function hvpost($url,$data,$header=array(),$timeout=5000 ){ // 模拟提交数据函数
		
		if( ! function_exists('curl_init') ){
			return FALSE;
		}
		
		$headerArr = array();
		foreach( $header as $n => $v ) {
			$headerArr[] = $n.':'.$v;
		}
		
		
		$curl = curl_init(); // 启动一个CURL会话
		
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_HEADER, 1); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		
		//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		
		curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
		curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
		
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
		if(count($headerArr)>0) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);//设置HTTP头
		}
		
		$content    = curl_exec($curl); //返回结果
		$httpcode   = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
		$run_time   = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$errorno    = curl_errno($curl);
		
		//关闭curl
		curl_close($curl);
		
		if($httpcode == '200') { //如果是正常返回，分割header和body
			$headerx = substr($content, 0, $headerSize);
			$bodyx   = substr($content, $headerSize);
		}
		
		//定义return数组变量
		$retarr = array();
		$retarr['headers']  = $headerx;
		$retarr['content']  = $bodyx;
		$retarr['httpcode'] = $httpcode;
		$retarr['run_time'] = $run_time;
		$retarr['errorno']  = $errorno;
		
		return $retarr;
		
	}
	
	
	
	
	
	/**
	 * get模拟
	 * @param unknown $url
	 * @param number $timeout
	 * @param unknown $header
	 * @param string $useragent
	 * @return boolean|multitype:number unknown
	 * 关于$header,请使用 $arr[key] = val; 的方式
	 * CLIENT-IP
	 * X-FORWARDED-FOR
	 */
	public static function vget( $url, $timeout=5000, $header=array(), $useragent='' ) {
	
		if( !function_exists('curl_init') ){
			return false;
		}
	
		if(substr($url,0,7)!='http://' && substr($url,0,8)!='https://') {
			return 'url_error';
		}
	
		//对传递的header数组进行整理
		$headerArr = array();
		foreach( $header as $n => $v ) {
			$headerArr[] = $n.':'.$v;
		}
	
	
		$curl = curl_init(); // 启动一个CURL会话
	
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	
		if(trim($useragent)!='') {
			//当传递useragent参数时，模拟用户使用的浏览器
			curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		}
	
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	
		curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
		curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
		if(count($headerArr)>0) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);//设置HTTP头
		}
		$content  = curl_exec($curl); //返回结果
		$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
		$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
		$errorno  = curl_errno($curl);
	
		//关闭curl
		curl_close($curl);
	
	
		//定义return数组变量
		$retarr = array();
		$retarr['content']  = $content;
		$retarr['httpcode'] = $httpcode;
		$retarr['run_time'] = $run_time;
		$retarr['errorno']  = $errorno;
	
		return $retarr;
	
	}
	
	
	
	
	/**
	 * 生成唯一数字单号,基于毫秒时间戳+随机数
	 * @param number $length
	 * @return unknown
	 */
	public static function hy_onlynumid($length=16) {
	
		$length  = intval($length);
	
		if($length <= 15) {
			$length = 15;
		}else if($length >= 24){
			$length = 24;
		}else {
			//$length长度不变
		}
	
		list($s1, $s2) = explode(' ', microtime());
	
	
		$k1 = substr($s2,-9,1);
		$k2 = substr($s2,-8,8);
		if($k1==0) {
			$k1 = 5;
		}else if($k1=1) {
			$k1 = 6;
		}else if($k1=2) {
			$k1 = 7;
		}else if($k1=3) {
			$k1 = 8;
		}else if($k1=4) {
			$k1 = 9;
		}
		$s2 = $k1.$k2;
	
		$millsec = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
	
		$echo_onlynum = $millsec;
	
		$addlen = $length - strlen($millsec);
	
		for($i=0;$i<$addlen;$i++) {
			$echo_onlynum .= mt_rand(0,9);
		}
	
		return $echo_onlynum;
	
	}
	
	
	/**
	 * 生成唯一单号
	 * @param number $length
	 * @return unknown
	 */
	public static function hy_onlystrid($length=24, $biaoshi='x') {
	
		$create_randstrs = '123456789abcdefghijklmnopqrstuvwxyz';
		$create_randarr  = str_split($create_randstrs, 1);
	
		$length  = intval($length);
		$biaoshi = trim($biaoshi);
		$biaoshi = strtolower($biaoshi);
	
		if($length <= 16) {
			$length = 16;
		}else if($length >= 32){
			$length = 32;
		}else {
			//$length长度不变
		}
	
		$ret_str = $biaoshi.uniqid();
		$addlen = $length - strlen($ret_str);
	
		for($i=0;$i<$addlen;$i++) {
			$ret_str .= $create_randarr[mt_rand(0,(count($create_randarr)-1))];
		}
	
		return $ret_str;
	
	}
	
	
	
	
	/**
	 * 获取访问者ip地址，防止用户伪造ip
	 * @return unknown|string
	 */
	public static function hy_get_client_ip() {
		$ip = '';
		if(isset($_SERVER['REMOTE_ADDR'])) {
			$ip  = $_SERVER['REMOTE_ADDR'];
		}else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip  = trim($arr[0]);
		}else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip  = $_SERVER['HTTP_CLIENT_IP'];
		}
		// IP地址合法验证
		$long = sprintf("%u",ip2long($ip));
		if($long) {
			return $ip;
		}else {
			return '0.0.0.0';
		}
	}
	
	
	
	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @return mixed
	 */
	public static function get_client_ip($type = 0) {
		$type       =  $type ? 1 : 0;
		static $ip  =   NULL;
		if ($ip !== NULL) return $ip[$type];
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos    =   array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip     =   trim($arr[0]);
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip     =   $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip     =   $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u",ip2long($ip));
		$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}
	
	
	
	
	/**
	 * 日志写入函数
	 * @param unknown $path
	 * @param unknown $name
	 * @param unknown $data
	 */
	public static function hy_writelog($path, $name, $data) {
		//判断该日志文件存放路径是否存在，不存在则进行创建
		
		if(!is_dir($path)) {
			//创建该目录
			mkdir($path, 0777, true);
		}
		
		//生成文件路径名称
		$filepathname = $path.$name;
		
		$fp = fopen($filepathname,'a'); //打开句柄
		fwrite($fp, $data);  //将文件内容写入字符串
		fclose($fp); //关闭句柄
		
		
	}
	
	
	
	//微信支付日志快速写入封装函数
	public static function hy_addtopaylogfile($data='') {
		
		if($data=='') {
			return false;
			
		}else {
			$path = HY_PAYLOGPATH.date('Y-m').'/';
			$name = 'pay_'.date('Y-m-d').'.log';
			
			HyItems::hy_writelog($path,$name,$data);
			
			return true;
			
		}
		
	}
	
	
	
	
	/**
	 * 将bigint数据转成大字符串
	 * @param unknown $long
	 * @return unknown
	 */
	public static function hytobigstr($long) {
		$long = sprintf("%u",$long);
		return $long;
	}
	
	
	/**
	 * 替换制表符、回车、换行为空格，主要用于对多行的sql语句转换为单行，便于写入日志及日志提取等
	 */
	public static function hy_trn2space($str) {
	
		$replace = array("\t", "\r", "\n",);
		return str_replace($replace, ' ', $str);
	
	}
	
	
	/**
	 * 替换制表符和回车为空格，主要用于日志写入前的处理---将逐步使用另一个，逐步替换
	 */
	public static function hy_tospace($str) {
		
		$replace = array("\t", "\r", "\n",);
		return str_replace($replace, ' ', $str);
		
	}
	
	
	/**
	 * 替换制表符和回车为空格，主要用于日志写入前的处理
	 */
	public static function hy_tosqlstr($str) {
		
		$replace = array("\t", "\r", "\n", "'", '"',);
		$string = str_replace($replace, ' ', $str);
		
		return mb_convert_encoding($string, "UTF-8", "auto");
		
	}
	
	
	
	//检测手机号对应运营商
	public static function hy_yunyingshangcheck($phone='',$type='num') {
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
	
	
	
	
	public static function hy_func_cintkey($num='') {
		if(''===$num || !is_numeric($num)) {
			return mt_rand(0,32);
		}else {
			$ccnum = 0;
			if($num>99) {
				$ccnum = substr($num,-2);
			}else {
				$ccnum = $num;
			}
	
			for($i=0;$i<8;$i++) {
				if($ccnum>32) {
					$ccnum = $ccnum-11;
				}else {
					break;
				}
			}
			
			return $ccnum;
			
		}
	}
	
	
	public static function hy_func_str32($keyid=0) {
		$keyid = intval($keyid);
		//32进制数组
		$yuarr = array(
				0  => '0',
				1  => '1',
				2  => '2',
				3  => '3',
				4  => '4',
				5  => '5',
				6  => '6',
				7  => '7',
				8  => '8',
				9  => '9',
				10 => 'a',
				11 => 'b',
				12 => 'c',
				13 => 'd',
				14 => 'e',
				15 => 'f',
				16 => 'g',
				17 => 'h',
				18 => 'j',
				19 => 'k',
				20 => 'm',
				21 => 'n',
				22 => 'p',
				23 => 'q',
				24 => 'r',
				25 => 's',
				26 => 't',
				27 => 'u',
				28 => 'v',
				29 => 'w',
				30 => 'x',
				31 => 'y',
				32 => 'z',
		);
	
		$r = isset($yuarr[$keyid])?$yuarr[$keyid]:'0';
		return $r;
	
	
	}
	
	
	
	
	/**
	 * 产生随机字串，可用来自动生成密码
	 * 默认长度6位 字母和数字混合 支持中文
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 * @param string $addChars 额外字符
	 * @return string
	 */
	static public function randString($len=6,$type='',$addChars='') {
		$str ='';
		switch($type) {
			case 0:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 1:
				$chars= str_repeat('0123456789',3);
				break;
			case 2:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
				break;
			case 3:
				$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 4:
				$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
				break;
			default :
				// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
				break;
		}
		if($len>10 ) {//位数过长重复字符串一定次数
			$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
		}
		if($type!=4) {
			$chars   =   str_shuffle($chars);
			$str     =   substr($chars,0,$len);
		}else{
			// 中文随机字
			for($i=0;$i<$len;$i++){
				$str.= HyItems::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1,'utf-8',false);
			}
		}
		return $str;
	}
	
	
	
	
	/**
	 * 字符串截取，支持中文和其他编码
	 * @static
	 * @access public
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	static public function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr"))
			$slice = mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.'...' : $slice;
	}
	
	
	
	
	public static function topstr_num2str($aa='') {
		$topstr = 'y';
		if($aa==2) {
			$topstr = 't';
		}else if($aa==3) {
			$topstr = 'd';
		}else {
			$topstr = 'y';
		}
		return $topstr;
		
	}
	public static function topstr_str2num($aa='') {
		$topstr = '';
		if($aa=='t') {
			$topstr = '2';
		}else if($aa=='d') {
			$topstr = '3';
		}else if($aa=='y') {
			$topstr = '1';
		}else {
			$topstr = '';
		}
		return $topstr;
		
	}
	
	

}
