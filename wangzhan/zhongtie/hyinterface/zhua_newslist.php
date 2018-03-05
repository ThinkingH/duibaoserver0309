<?php

require_once('./HyDb.php');

// 页面超时设置
set_time_limit(3600);


header('Content-Type:text/html;charset=utf8');

$baseurl = 'http://news.315.com.cn/';

$canshuarr = array(
		'macro', //宏观
		'company', //公司
		'futures', //期货
		'zzs', //行情
		'ztb', //专题
		'business', //商道
		'global', //视野
		'oil', //石油
		'chemical', //化工
		'coal', //煤炭
		'metallurgy', //冶金
		'steels', //钢铁
		'other', //综合
		'power', //电力
		'equip', //设备技术
		'xny', //新能源
		'commodity', //黄金
		'opinion', //农副
		'renwu', //焦点人物
);


$HyDb = new HyDb();

foreach($canshuarr as $valcan) {
	
	$mainarr = array();
	
	$ttype = $valcan;
	
	$urltest = $baseurl.$ttype;
	
	$res = hy_strtonull(vget($urltest));
	
	
	//获取最大页数
	$res_maxpage1 = explode('(function(){hxPage.maxPage =',$res);
	$res_maxpage2 = explode(';',isset($res_maxpage1[1])?$res_maxpage1[1]:'');
	$res_maxpage = $res_maxpage2[0];
	
	
	$pat_t = '/<a href=\'.{30,60}\' target=\'_blank\'>/';
	preg_match_all($pat_t,$res,$iparr_t1);
	
	if(count($iparr_t1)>0) {
		foreach($iparr_t1[0] as $vali) {
			$tempurl = substr($vali,9,strlen($vali)-27);
			array_push($mainarr,$tempurl);
		}
	}
	
	for($i=0;$i<3;$i++) {
		--$res_maxpage;
		$tempurl = $urltest.'/index-'.$res_maxpage.'.html';
		
		$res = hy_strtonull(vget($tempurl));
		
		$pat_t = '/<a href=\'.{30,60}\' target=\'_blank\'>/';
		preg_match_all($pat_t,$res,$iparr_t1);
		
		if(count($iparr_t1)>0) {
			foreach($iparr_t1[0] as $vali) {
				$tempurl = substr($vali,9,strlen($vali)-27);
				array_push($mainarr,$tempurl);
			}
		}
		sleep(1);
		
	}
	
	
	foreach($mainarr as $valm) {
		
		$insert_sql = "insert ignore into zhua_news (turl,ttype) values ('".$valm."','".$ttype."')";
		$HyDb->execute($insert_sql);
		
	}
	
	echo $ttype.'<br>';
	
	
	sleep(1);
	
}




echo 'ok';




function hy_strtonull($str='') {
	$replace = array("\t", "\r", "\n",);
	$content = str_replace($replace, '', $str);
	return $content;
}


function vget($url,$timeout=6000,$header=array(),$useragent=''){ // 模拟提交数据函数

	if( ! function_exists('curl_init') ){
		return FALSE;
	}

	$headerArr = array();
	foreach( $header as $n => $v ) {
		$headerArr[] = $n.':'.$v;
	}
	$useragent = 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)';

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
	//$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
	//$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
	//$errorno  = curl_errno($curl);
	//关闭curl
	curl_close($curl);


// 	//定义return数组变量
// 	$retarr = array();
// 	$retarr['content']  = $content;
// 	$retarr['httpcode'] = $httpcode;
// 	$retarr['run_time'] = $run_time;
// 	$retarr['errorno']  = $errorno;

	return $content;

}








