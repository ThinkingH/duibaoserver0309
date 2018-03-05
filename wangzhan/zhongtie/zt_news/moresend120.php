<?php

//每两小时触发一次

// 页面超时设置
set_time_limit(7200);

//设置时区
date_default_timezone_set('Asia/Shanghai');

sleep(5);


//金银岛
$url = 'http://120.27.34.239:8005/zt_news/zhuaqu1.php';
$a = vget($url,600000);
sleep(5); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';



//秦皇岛煤炭网
$url = 'http://120.27.34.239:8005/zt_news/zhuaqu3.php';
$a = vget($url,600000);
sleep(5); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';


//联合金属网
$url = 'http://120.27.34.239:8005/zt_news/zhuaqu4.php';
$a = vget($url,7200000);
sleep(5); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';






echo 'over_ok';






function vget($url,$timeout=10000){ // 模拟提交数据函数

	if( ! function_exists('curl_init') ){
		return FALSE;
	}

	$curl = curl_init(); // 启动一个CURL会话

	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在


	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer

	curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
	curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回

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







