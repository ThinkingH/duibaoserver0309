<?php

// 页面超时设置
set_time_limit(1440);
/* <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> */
header('Content-Type:text/html;charset=utf8');

$id = isset($_GET['id'])?$_GET['id']:'';

// if($id=='') {
// 	exit('error');
// }

$url='http://wxservice.zhaomei.com/selfmall/detail?id='.$id;

$useragent = randuseragent();
$header = array();

$t = vget($url,$timeout=10000,$header,$useragent);

if($t['httpcode']!=200) {
	echo 'http_code'.$t['httpcode'];
}

$content = $t['content'];
$replace = array('<a href="/selfmall/moreindex?id=57">更多指标<i class="iconfont icon-next"></i></a>',);
$content1 = str_replace($replace, '', $content); 

$replace = array('更多',);
$content2 = str_replace($replace, '', $content1);

$replace = array('<span>备注：</span><span>坑口价格近期波动较大，实际价格以市场价为标准</span>',);
$content3 = str_replace($replace, '', $content2);

$replace = array('<span>优惠：</span><span>此价格为一票含税价（量大可议价</span>',);
$content = str_replace($replace, '', $content3);




$fenge = explode('<article>',$content);
$fenge1 = explode('</article>',$fenge[1]);

$fenge2 = explode('<div class="fixed-menu">',$fenge1[0]);
$fenge3 = $fenge2[0].$fenge2[1];


echo $fenge2[0];
/* echo '<textarea>';
 print_r($fenge2);  */  
/* print_r($iparr_t1);
 print_r($iparr_t2);
 print_r($iparr_t3);
 print_r($iparr_t4);
 print_r($iparr_t5);
 print_r($iparr_t6); */
 //echo '</textarea>';  






function vget($url,$timeout=10000,$header=array(),$useragent){ // 模拟提交数据函数

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

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在

	curl_setopt($curl, CURLOPT_USERAGENT, $useragent); // 模拟用户使用的浏览器

	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer

	curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
	curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);//设置HTTP头
	//curl_setopt($curl, CURLOPT_REFERER, $referer);//构造来路

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

function randuseragent() {

	$arr = array(
			'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Win64; x64; Trident/4.0)',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)',
			'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070309 Firefox/2.0.0.3',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070803 Firefox/1.5.0.12',
			'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13',
			'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
			'Opera/9.27 (Windows NT 5.2; U; zh-cn)',
			'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)',
			'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)',

	);

	//return $arr[rand(0,(count($arr)-1))];
	return $arr[0];

}
?>