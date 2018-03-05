<?php

require_once('./HyDb.php');

// 页面超时设置
set_time_limit(1440);


header('Content-Type:text/html;charset=utf8');


$p = isset($_GET['p'])?$_GET['p']:1;


$url = 'http://56.zhaomei.com/transportcapacity/search?page_no='.$p;


$useragent = randuseragent();
$header = array();
$t = vget($url,$timeout=10000,$header,$useragent);
if($t['httpcode']!=200) {
	echo 'http_code'.$t['httpcode'];
}

$content = $t['content']; 
$replace = array("\t", "\r", "\n",);
$content = str_replace($replace, '', $content);



//物流运力抓取的字段
//船名
$pat_t = '/<div class="t-1"><span><i class="F_icon i_haiyun"><\/i>(.{3,20})<\/span><i class=""><\/i><\/div>/';
preg_match_all($pat_t,$content,$iparr_t1);

//船类型
$pat_t = '/<div class="t-2"><span>(.{3,15})<\/span><\/div>/';
preg_match_all($pat_t,$content,$iparr_t2);

//空船港
$pat_t = '/<div class="t-3"><span><i class="F_icon i_gangkou"><\/i>(.{3,20})<\/span><\/div>/';
preg_match_all($pat_t,$content,$iparr_t3);

//日期
//$pat_t = '/<div class="t-4" data-loaddate="\d{4}-\d{1,2}-\d{1,2}"><span>/';
// preg_match_all($pat_t,$content,$iparr_t4);

//print_r($iparr_t4);

//待卸货物
$pat_t = '/<div class="t-5"><span>(.{3,30})<\/span><\/div>/';
preg_match_all($pat_t,$content,$iparr_t5);

//吨位
$pat_t = '/<div class="t-6"><span>(.{3,30})<\/span><\/div>/';
preg_match_all($pat_t,$content,$iparr_t6);


//获取cid的值
$pat_t = '/" data-ship="(.{30,600})">                        <div class="t-1">/';
preg_match_all($pat_t,$content,$iparr_t7);



$HyDb = new HyDb();


foreach($iparr_t1[1] as $keyt => $valt) {
	
	/*
	echo '<tr>';
	echo '<td>'.(isset($iparr_t1[1][$keyt])?$iparr_t1[1][$keyt]:'').'</td>';
	echo '<td>'.(isset($iparr_t2[1][$keyt])?$iparr_t2[1][$keyt]:'').'</td>';
	echo '<td>'.(isset($iparr_t3[1][$keyt])?$iparr_t3[1][$keyt]:'').'</td>';
	echo '<td>'.(isset($iparr_t4[1][$keyt])?$iparr_t4[1][$keyt]:'').'</td>';
	echo '<td>'.(isset($iparr_t5[1][$keyt])?$iparr_t5[1][$keyt]:'').'</td>';
	echo '</tr>'; 
	*/
	
	$vali = $iparr_t7[1][$keyt];
	$vali = str_replace('&quot;','"',$vali);
	if($vali!=''){
		$tarr = json_decode($vali,true);
		 
		$cid    = isset($tarr['cid'])?$tarr['cid']:'';
	
	
	}
	
	$chuanming          = isset($iparr_t1[1][$keyt])?$iparr_t1[1][$keyt]:'';
	$type               = isset($iparr_t2[1][$keyt])?$iparr_t2[1][$keyt]:'';
	$kongchuangang      = isset($iparr_t3[1][$keyt])?$iparr_t3[1][$keyt]:'';
	$daixiehuowu        = isset($iparr_t5[1][$keyt])?$iparr_t5[1][$keyt]:'';
	$dunwei             = isset($iparr_t6[1][$keyt])?$iparr_t6[1][$keyt]:'';
	
	
	$sql_insert = "insert ignore into wuliuyunli (cid,w_chuanming,w_type,w_kongchuangang,w_daixiehuowu,w_dunwei) values(
				'".$cid."','".$chuanming."','".$type."','".$kongchuangang."','".$daixiehuowu."','".$dunwei."')";
	//echo $sql_insert;
	
	$HyDb->execute($sql_insert);
	
	
}


echo 'ok';




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


function randip() {

	$ip_long = array(
			array('607649792', '608174079'), //36.56.0.0-36.63.255.255
			array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
			array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
			array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
			array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
			array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
			array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
			array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
			array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
			array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	);

	$rand_key = rand(0, 9);

	$ip = long2ip(rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));


	return $ip;

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




