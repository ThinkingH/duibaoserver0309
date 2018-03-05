<?php

// 页面超时设置
set_time_limit(3600);


$locktime = file_get_contents('./moresendtemplocktime');

if(time()-$locktime<=720) {
	//两次触发时间间隔必须大于12分钟
	exit('too fast');
}else {
	file_put_contents('./moresendtemplocktime',time());
}





//新闻分类列表数据抓取，获取新闻地址及分类
$url = 'http://120.27.34.239:8005/hyinterface/zhua_newslist.php';
$a = vget($url,1440000);
sleep(1); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';


//新闻地址对应内容、标题、时间抓取
$url = 'http://120.27.34.239:8005/hyinterface/zhua_child.php';
$a = vget($url,1440000);
sleep(1); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';


//新闻内容过滤，去掉金银岛以及金银岛链接数据
$url = 'http://120.27.34.239:8005/hyinterface/content_change.php';
$a = vget($url,1440000);
sleep(1); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';


//煤炭种类数据的抓取
$url = 'http://120.27.34.239:8005/hyinterface/meitankindget.php';
$a = vget($url,1440000);
sleep(1); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';

//煤炭数据--现货行情的抓取
$url = 'http://120.27.34.239:8005/hyinterface/xianhuocoalget.php';
$a = vget($url,1440000);
sleep(1); //暂停一秒
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







