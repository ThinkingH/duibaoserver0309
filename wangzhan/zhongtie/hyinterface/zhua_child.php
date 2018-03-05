<?php

require_once('./HyDb.php');

// 页面超时设置
set_time_limit(3600);


header('Content-Type:text/html;charset=utf8');

$HyDb = new HyDb();
$sql = "select id,turl from zhua_news where createtime='0000-00-00 00:00:00' limit 2000";
$list = $HyDb->get_all($sql);
//echo $sql.'<br>';

if(count($list)>0) {
	echo $list[0]['id'].'<br>';
	
	foreach($list as $vall) {
		
		$res = hy_strtonull(vget($vall['turl']));
		
		$res_a1 = explode('<h2 class="tit_01" id="cont_title">',$res);
		$res_a2 = explode('</h2>',isset($res_a1[1])?$res_a1[1]:'');
		$res_a3 = $res_a2[0];
		
		$res_aa1 = explode('<div class="time_lyfl fl c666" id="cont_riqi">',$res);
		$res_aa2 = explode(' 来源：',isset($res_aa1[1])?$res_aa1[1]:'');
		$res_aa3 = $res_aa2[0];
		
		$res_aaa1 = explode('<div id="frameContent" class="txt_0422">',$res);
		$res_aaa2 = explode('</div>',isset($res_aaa1[1])?$res_aaa1[1]:'');
		$res_aaa3 = explode('相关阅读：<BR><FONT',$res_aaa2[0]);
		$res_aaa6 = explode('<p align="right" >（责任编辑:',$res_aaa3[0]);
		$res_aaa4 = $res_aaa6[0];
		
		
		if($res_a3!='' && $res_aaa3!='') {
			$sql_update = "update zhua_news set ttitle='".$res_a3."',tcontent='".hy_yin2space($res_aaa4)."',createtime='".$res_aa3."' where id='".$vall['id']."'";
			//echo $sql_update.'<br>';
			$HyDb->execute($sql_update);
			echo '-';
		}else {
			//$sql_update = "update zhua_news set createtime='0000-00-01 00:00:00' where id='".$vall['id']."'";
			//$HyDb->execute($sql_update);
			//echo '='.$vall['id'].'<br>';
			echo '=';
		}
		
		//暂停300毫秒
		usleep(300000);
		
	}
	
	
	
	
}else {
	echo 'no';
	
}




echo 'ok';




function hy_strtonull($str='') {
	$replace = array("\t", "\r", "\n",);
	$content = str_replace($replace, '', $str);
	return $content;
}

function hy_yin2space($str='') {
	$replace = array("'",);
	$content = str_replace($replace, ' ', $str);
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








