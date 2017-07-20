<?php

/** 
 * HyItems
 * 静态方法库
 * author:yu
 * 
 */


//鉴权ip数组
$iparr = array(
		'127.0.0.1',
);
$iparrstr = serialize($iparr);
define('IPARRSTR',$iparrstr);




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
	
		if(substr($url,0,7)!='http://') {
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
	 * 替换制表符和回车为空格，主要用于日志写入前的处理
	 */
	public static function hy_tosqlstr($str) {
		
		$replace = array("\t", "\r", "\n", "'", '"',);
		$string = str_replace($replace, ' ', $str);
		
		return mb_convert_encoding($string, "UTF-8", "auto");
		
	}
	
	
	
	/**
	 * 数组转换为字符串，用来替代serialize函数
	 */
	public static function hy_arr2str($arr=array()) {
	
		if(is_array($arr) && count($arr)>0) {
				
			$string = '|';
				
			foreach($arr as $key=>$val) {
				$string .= $key.'=>'.$val.'|';
			}
				
			return $string;
				
		}else {
			return false;
		}
	
	}
	
	
	

	/**
	 * xml转换成数组
	 * 主要用于soap数据的解析
	 * @param type $contents
	 * @param type $get_attributes
	 * @param type $priority
	 * @return type
	 */
	public static function hy_xml2array($contents, $get_attributes=1, $priority = 'tag') {
	
		if(!$contents) {
			return array();
		}
	
		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}
	
		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
	
		if(!$xml_values) {
			return; //Hmm
		}
	
		//Initializations
		$xml_array   = array();
		$parents     = array();
		$opened_tags = array();
		$arr         = array();
	
		$current = &$xml_array; //Refference
		//Go through the tags.
		$repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
		foreach($xml_values as $data) {
			unset($attributes, $value); //Remove existing values, or there will be trouble
			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data); //We could use the array by itself, but this cooler.
	
			$result = array();
			$attributes_data = array();
	
			if(isset($value)) {
				if ($priority == 'tag') {
					$result = $value;
				}else {
					$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}
			}
	
			//Set the attributes too.
			if(isset($attributes) and $get_attributes) {
				foreach ($attributes as $attr => $val) {
					if($priority == 'tag') {
						$attributes_data[$attr] = $val;
					}else {
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
			}
	
			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level - 1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					if ($attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
					$repeated_tag_index[$tag . '_' . $level] = 1;
	
					$current = &$current[$tag];
				}else { //There was another element with the same tag name
					if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					}else {//This section will make the value an array if multiple tags with the same name appear together
						$current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
						$repeated_tag_index[$tag . '_' . $level] = 2;
	
						if(isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = &$current[$tag][$last_item_index];
				}
			}else if($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if($priority == 'tag' and $attributes_data) {
						$current[$tag . '_attr'] = $attributes_data;
					}
				}else { //If taken, put all things inside a list(array)
					if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array
						// push the new element into that array.
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
	
						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}else { //If it is not an array
						$current[$tag] = array($current[$tag], $result); //Make it an array using using the existing value and the new value
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset($current[$tag . '_attr']);
							}
	
							if ($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}else if ($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level - 1];
			}
	
		}
	
		return($xml_array);
	
	}
	
	
	
	public static function hy_object2array($array=array()) {
		if(is_object($array)) {
			$array = (array)$array;
		} if(is_array($array)) {
			foreach($array as $key=>$value) {
				$array[$key] = HyItems::hy_object2array($value);
			}
		}
		return $array;
		
	}
	
	

}
