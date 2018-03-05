<?php

// 页面超时设置
set_time_limit(1440);


$locktime = file_get_contents('./moresendtemplocktime');

if(time()-$locktime<=600) {
	//两次触发时间间隔必须大于10分钟
	exit('too fast');
}else {
	file_put_contents('./moresendtemplocktime',time());
}



for($i=1;$i<=3;$i++) {
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_xinwen.php?ntype=1&p='.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_xinwen.php?ntype=2&p='.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_xinwen.php?ntype=3&p='.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_xinwen.php?ntype=4&p='.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_caigou.php?p='.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_ziyuan.php?p=1'.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	$url = 'http://120.27.34.239:8001/hyinterface/meitanget_wuliu.php?p=1'.$i;
	$a = vget($url);
	sleep(1); //暂停一秒
	echo $i.'------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';
	
	
	
	
	
}




//新闻详细数据更新
$url = 'http://120.27.34.239:8001/hyinterface/meitanget_xinwen_c.php';
$a = vget($url);
sleep(1); //暂停一秒
echo 'news------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';


//现货资源详细数据更新
$url = 'http://120.27.34.239:8001/hyinterface/meitanget_ziyuan_c.php';
$a = vget($url);
sleep(1); //暂停一秒
echo 'ziyuan------'.$a['httpcode'].'---'.$a['run_time'].'---'.$a['errorno'].'---'.$a['content'].'<br/>';








echo 'over_ok';









function vget($url,$timeout=5000){ // 模拟提交数据函数

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







