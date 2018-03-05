<?php

//手机发送验证码

header("Content-type: text/html; charset=utf-8");
require_once('./interface/HyDb.php');
date_default_timezone_set('Asia/Shanghai');
//开启session
session_start();


//获取手机号
$phone = $_POST['mobile'];

//判断该手机号是否注册过
$HyDb = new HyDb();
$sql = "select * from users where phone = '".$phone."'";
$list = $HyDb->get_all($sql);

if(count($list)<=0){
	//说明该用户注册过
	echo "nosign";
	exit;
}else{

	//随机生成的六位验证码
	$yanzhengma = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	//下发短信的内容
	$xiafaneirong = '【中铁国恒】本次验证码为：'.$yanzhengma.'，有效时间为30分钟';

	//随机生成的验证码，存入session中，进行校验
	//session('code',$yanzhengma);
	$_SESSION['code']=$yanzhengma;

	//echo $_SESSION['code'];

	//用户名
	$ua='XBSC';
	//密码
	$pw='012534';
	//下发验证码的链接
	$codeurl='http://121.42.205.244:18002/send.do';

	//调用下发验证码的链接
	$tm = date('Y-m-d H:i:s',(time()-120));
	//md5加密的验证码
	$pw_md5=md5($pw.$tm);

	//短信下发的链接
	$url = $codeurl.'?ua='.$ua.'&pw='.$pw_md5.'&mb='.$phone.'&ms='.$xiafaneirong.'&tm='.$tm;

	$res = vget($url,10000);


	$content  = isset($res['content'])  ? trim($res['content']) : '';
	$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
	$run_time = isset($res['run_time']) ? $res['run_time'] : '';


	if($httpcode == 200){
			
		if($content!=''&& $content >0){

			echo 'success';

		}else{
			echo 'fail';
		}
			
	}

}



//vget模拟发送函数
function vget( $url, $timeout=5000, $header=array(), $useragent='' ) {

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