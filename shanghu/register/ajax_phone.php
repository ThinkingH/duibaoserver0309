<?php
header('Content-Type:text/html;charset=utf-8');
//文件的引入
require_once("../lib/c.core.php");

//数据库的初始化
$HyDb = new HyDb();

$phone = $_GET['phone'];
$type  = $_GET['type'];

//echo $phone;
if($type=='1'){
	
	//判断该手机号是否注册过
	$selephonesql  = "select id from shop_site where phone='".$phone."' and pay='1'";
	$selephonelist = $HyDb->get_row($selephonesql);
	
	if($selephonelist['id']>0){
		echo 'phoneerror';
	}
	
}else if($type=='2'){
	
	//随机生成的六位验证码
	$yanzhengma = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	
	//下发短信的内容
	$message = '【兑宝流量】本次验证码为：'.$yanzhengma.'，用于登录兑宝app,30分钟内有效';
	
	$time= time();
	$url = 'http://121.42.228.34/duanxinfasong/interface/smssend.php?md5key=e0f8978c0677a01aeac12cc90eed0949&nowtime='.$time.'&phone='.$phone.'&message='.urlencode($message);
	$filename = 'file.txt';
	file_put_contents($filename, $url);
	
	//session的初始化
	$HySession = new HySession();
	$HySession->set('code',$yanzhengma);//随机生成的验证码，存入session中，进行校验
	
	$res = vget($url,10000);
	
	$content  = isset($res['content'])  ? trim($res['content']) : '';
	$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
	$run_time = isset($res['run_time']) ? $res['run_time'] : '';
	
	
	if($httpcode == 200){
		
		if($content!=''){
			echo 'success';
		}else{
			echo 'fail';
		}
		
	}
	
	
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
  function vget( $url, $timeout=5000, $header=array(), $useragent='' ) {

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

