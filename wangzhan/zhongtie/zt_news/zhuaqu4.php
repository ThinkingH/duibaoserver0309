<?php

//联合金属网
//www.umetal.com
//账号  lizy
//密码  123456


// 页面超时设置
set_time_limit(5000);

//设置时区
date_default_timezone_set('Asia/Shanghai');



require_once('./HyItems.php');
require_once('./HyDb.php');


$cookie_path = dirname(__FILE__).'/tmp/zhuaqu4_cookie_temp';
define('HY_TEMP_COOKIE_PATH',$cookie_path);
$dailiiparrpath = dirname(__FILE__).'/tmp/zhuqu_dailiip_arr';
define('HY_DAL_LI_IP_ARR_PATH',$dailiiparrpath);



//=======================================================================
$dailiiparr = array();


//=======================================================================
$getdailiipurl = 'http://api.xicidaili.com/free2016.txt';
$res = HyItems::vget($getdailiipurl);
$content = $res['content'];
$hang = explode("\n",$content);
foreach($hang as $valh) {
	$lie = explode(':',$valh);
	$tempip = isset($lie[0])?trim($lie[0]):'';
	$tempport = isset($lie[1])?trim($lie[1]):'';
	if($tempip!='' && $tempport!='') {
		$tempccarr = array(
				'ip' => trim($tempip),
				'port' => trim($tempport),
		);
		array_push($dailiiparr,$tempccarr);
	}
	
}


//=======================================================================
$getdailiipurl = 'http://www.ip181.com/';
$res = HyItems::vget($getdailiipurl);
$content = $res['content'];
$content = hy_tonull($content);
$temparr1 = explode('<tr',$content);
foreach($temparr1 as $valt1) {
	$temparr2 = explode('<td>',$valt1);
	if(count($temparr2)>0) {
		$temp3_ip = isset($temparr2[1])?$temparr2[1]:'';
		$temp4_ip = explode('</td>',$temp3_ip);
		$temp5_ip = trim($temp4_ip[0]);
			
		$temp3_port = isset($temparr2[2])?$temparr2[2]:'';
		$temp4_port = explode('</td>',$temp3_port);
		$temp5_port = trim($temp4_port[0]);
			
		$temp3_type = isset($temparr2[4])?strtolower($temparr2[4]):'';
		$temp4_type = explode('</td>',$temp3_type);
		$temp5_type = trim($temp4_type[0]);
			
		$temp3_second = isset($temparr2[5])?strtolower($temparr2[5]):'';
		$temp4_second = explode('.',$temp3_second);
		$temp5_second = $temp4_second[0];
		$temp5_second = trim($temp5_second);
			
		//echo $temp5_ip.'--'.$temp5_port.'--'.$temp5_type.'--'.$temp5_second.'<br>';
		if($temp5_ip!='' && $temp5_port!='' && $temp5_type=='http' && $temp5_second<=3) {
			$tempccarr = array(
					'ip' => trim($temp5_ip),
					'port' => trim($temp5_port),
			);
			array_push($dailiiparr,$tempccarr);
			
		}
	}
}




/*
$getdailiipurl = 'http://www.xicidaili.com/nn/';
$content = hy_tonull(hy_vget($getdailiipurl));
$temparr1 = explode('alt="Cn" /></td>      <td>',$content);
foreach($temparr1 as $valt1) {
	if(is_numeric(substr($valt1,0,1))) {
		$tta1 = explode('</td>      <td>',$valt1);
		$tempip = $tta1[0];
		$tta2 = isset($tta1[1])?$tta1[1]:'';
		$tta3 = explode('</td>',$tta2);
		$tempport = $tta3[0];
		if($tempip!='' && $tempip!='') {
			$tempccarr = array(
					'ip' => trim($tempip),
					'port' => trim($tempport),
			);
			array_push($dailiiparr,$tempccarr);
		}
	}
}
*/



if(count($dailiiparr)<=0) {
	exit('daili_ip_get_error');
}
define('HYDAILIIPARR',serialize($dailiiparr));
file_put_contents(HY_DAL_LI_IP_ARR_PATH,serialize($dailiiparr));

//随机获取一个代理
function hy_randipport() {
	$dailiarr = unserialize(HYDAILIIPARR);
	$randipport = $dailiarr[mt_rand(0,(count($dailiarr)-1))];
	return $randipport;
}
//随机获取一个代理
function hy_randipportfile() {
	$aa = file_get_contents(HY_DAL_LI_IP_ARR_PATH);
	//echo HY_DAL_LI_IP_ARR_PATH;
	$dailiarr = unserialize($aa);
	$randipport = $dailiarr[mt_rand(0,(count($dailiarr)-1))];
	return $randipport;
}




//先模拟登录
// hy_crw_vget
$dl_url = 'http://www.umetal.com/sec/dgserverlet?classname=login.LoginCtrlChina&method=loginInUiHomeByXmlHttp';
$dl_data = 'username=lizy&password=123456';
hy_crw_vpost($dl_url,HY_TEMP_COOKIE_PATH,$dl_data);



//存放对应的数据链接
$urlarr = array();
$quchongurlarr = array();


//---------------------------------------------------------------------------------------
//煤炭频道汇总
$liebiaourl = 'http://www.umetal.com/luliao/coal.html';
$tempurlarr = func_zixunurlchuli($liebiaourl);

foreach($tempurlarr as $valt) {
	$quchongstr = substr($valt,-14);
	if(!in_array($quchongstr,$quchongurlarr)) {
		array_push($quchongurlarr,$quchongstr);
		array_push($urlarr,$valt);
	}
	
}


//---------------------------------------------------------------------------------------
//价格行情
$liebiaourl = 'http://www.umetal.com/luliao/coalPrice.html';
$tempurlarr = func_zixunurlchuli($liebiaourl);

foreach($tempurlarr as $valt) {
	$quchongstr = substr($valt,-14);
	if(!in_array($quchongstr,$quchongurlarr)) {
		array_push($quchongurlarr,$quchongstr);
		array_push($urlarr,$valt);
	}
	
}


//---------------------------------------------------------------------------------------
//国际市场
$liebiaourl = 'http://www.umetal.com/luliao/coalIntel.html';
$tempurlarr = func_zixunurlchuli($liebiaourl);

foreach($tempurlarr as $valt) {
	$quchongstr = substr($valt,-14);
	if(!in_array($quchongstr,$quchongurlarr)) {
		array_push($quchongurlarr,$quchongstr);
		array_push($urlarr,$valt);
	}
	
}


//---------------------------------------------------------------------------------------
//库存
$liebiaourl = 'http://www.umetal.com/luliao/coalKucun.do';
$tempurlarr = func_zixunurlchuli($liebiaourl);

foreach($tempurlarr as $valt) {
	$quchongstr = substr($valt,-14);
	if(!in_array($quchongstr,$quchongurlarr)) {
		array_push($quchongurlarr,$quchongstr);
		array_push($urlarr,$valt);
	}
	
}

// print_r($urlarr);

if(count($urlarr)>0) {
	//数据库初始化
	$HyDb = new HyDb();
	
	
}



foreach($urlarr as $valurl) {
	
	$valu_url = $valurl;
	
	//查询数据库看该链接是否已经存在
	$sql_pan = "select id from zt_wx_news where y_url='".$valu_url."' and create_datetime>='".date('Y-m-d H:i:s',(time()-10*24*60*60))."'";
	$list_pan = $HyDb->get_one($sql_pan);
	
	if($list_pan<=0) {
		
		//执行数据抓取
		$res = hy_crw_vget($valu_url,HY_TEMP_COOKIE_PATH);
		$content = HyItems::hy_tonull($res);
		
		if($content!='') {
			//获取时间
			$temp_shijian_arr1 = explode('<div align="center">',$content);
			$temp_shijian_arr2 = explode('</div>',(isset($temp_shijian_arr1[1])?$temp_shijian_arr1[1]:''));
			$temp_shijian_a = rtrim($temp_shijian_arr2[0]);
			$temp_shijian_b = substr($temp_shijian_a,-21);
			$temp_shijian = substr($temp_shijian_b,0,19);
			
			
			//如果当前文章的时间超过当前时间24小时，直接跳过
			$thecontentcreatetime_int = strtotime($temp_shijian);
			$time_plus_24hours_int = strtotime(date('Y-m-d 00:00:00'));
			if($thecontentcreatetime_int>=$time_plus_24hours_int) {
				//如果此文章发布时间距离抓取时间的24小时内，则继续执行数据入库，否则跳过数据执行操作
				
	
				//获取标题
				$temp_biaoti_arr1 = explode('<h1>',$content);
				$temp_biaoti_arr2 = explode('</h1>',$temp_biaoti_arr1[1]);;
				$temp_biaoti = trim($temp_biaoti_arr2[0]);
				
				
				//获取内容
				$temp_neirong_arr1 = explode('<div id="content">',$content);
				$temp_neirong_arr2 = explode('<div class="clear0">',$temp_neirong_arr1[1]);;
				$temp_neirong_arr3 = explode('</div>',$temp_neirong_arr2[0]);;
				$temp_neirong = trim($temp_neirong_arr3[0]);
				
				
				$biaoti  = $temp_biaoti;
				$biaoti = iconv('GBK', 'UTF-8', $biaoti); //编码转换
				$riqi    = $temp_shijian;
				$neirong = preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$temp_neirong); //去除a标签
				$neirong = iconv('GBK', 'UTF-8', $neirong); //编码转换
				if(strlen($neirong)>50) {
					$neirong .= '  （联合金属网）';
				}else {
					$neirong = '';
				}
				if(strlen($biaoti)<9) {
					$biaoti = '';
				}
				if($biaoti!='' && $riqi!='' && $neirong!='') {
				
					$jiaoyanstring = $biaoti.$riqi.$neirong;
					$md5crc32 = md5($jiaoyanstring).crc32($jiaoyanstring);
					//$intorder = strtotime($riqi).mt_rand(1000,9999);
					$intorder = time().mt_rand(1000,9999);
				
					$sql_pan = "select id from zq_tmp_umetal where md5crc32='".$md5crc32."' and typeid='2' ";
					$list_pan = $HyDb->get_one($sql_pan);
				
					if($list_pan<=0) {
							
						//typeid 1为纯文本推送，2为链接,3
						$sql_insert = "insert into zq_tmp_umetal (typeid,intorder,contenttime,title,content,
								y_url,create_datetime,md5crc32) values
								('3','".$intorder."','".$riqi."','".$biaoti."','".$neirong."',
								'".$valu_url."','".date('Y-m-d H:i:s')."','".$md5crc32."')";
						$HyDb->execute($sql_insert);
						//echo $sql_insert;
						echo '-';
						
						//插入到微信推送信息表
						$sql_insert = "insert into zt_wx_news (typeid,intorder,contenttime,title,content,
								y_url,create_datetime,md5crc32) values
								('3','".$intorder."','".$riqi."','".$biaoti."','".$neirong."',
								'".$valu_url."','".date('Y-m-d H:i:s')."','".$md5crc32."')";
						$HyDb->execute($sql_insert);
						
						
						
					}else {
						echo '|';
					}
				
				}else {
					echo '#';
				}
				
				
			}else {
				echo 'T';
			}
			
			
		}else {
			echo 'N';
		}
		
		
	}else {
		echo 'J';
	}
	
	
	ob_flush();
	flush();
	
	sleep(1);
	
	
}






echo 'ok_over';



function func_zixunurlchuli($liebiaourl='') {
	if($liebiaourl=='') {
		return array();
	}
	
	
	$urlarr = array();
	//echo $liebiaourl;
	$res = hy_crw_vget($liebiaourl,HY_TEMP_COOKIE_PATH);
	$content = $res;
// 	echo '<textarea>';
// 	print_r($res);
// 	echo '</textarea>';
	$temp1arr = explode('<a href="',$content);
	
	
	foreach($temp1arr as $valt) {
		if(substr($valt,0,13)=='viewDetail.do') {
			//链接
			$temp_url_arr1 = explode('"',$valt);
			$temp_url = trim($temp_url_arr1[0]);
			
			if($temp_url!='') {
				$trmpurl_ff = 'http://www.umetal.com/luliao/'.$temp_url;
				array_push($urlarr,$trmpurl_ff);
			}
			
		}
	
	}
	
	
	return $urlarr;
	
	
}














function hy_crw_vget($url,$cookiepathname='', $timeout=30000) {
	if( !function_exists('curl_init') ){
		return false;
	}
	if(substr($url,0,7)!='http://' && substr($url,0,8)!='https://' ) {
		return 'url_error';
	}
	
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	
	$randiparrt = hy_randipport();
	$proxy = isset($randiparrt['ip'])?$randiparrt['ip']:'';
	$port = isset($randiparrt['port'])?$randiparrt['port']:'';
	
	if($proxy!='') {
		curl_setopt($curl, CURLOPT_PROXY, $proxy); //代理服务器地址
		curl_setopt($curl, CURLOPT_PROXYPORT, $port); //代理服务器端口
	}
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiepathname); //调用对应cookie存储文件
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiepathname); //将返回的cookie写入对应文件
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'); // 模拟用户使用的浏览器
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
	//echo $proxy,'---',$port,'---',$httpcode,'-----',$errorno,'-----',$url,'-----','<br><br>';
	return $content;
}


function hy_crw_vpost($url,$cookiepathname='',$data='',$timeout=30000 ){
	if( ! function_exists('curl_init') ){
		return FALSE;
	}
	if(substr($url,0,7)!='http://' && substr($url,0,8)!='https://' ) {
		return 'url_error';
	}
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	
	$randiparrt = hy_randipport();
	$proxy = isset($randiparrt['ip'])?$randiparrt['ip']:'';
	$port = isset($randiparrt['port'])?$randiparrt['port']:'';
	if($proxy!='') {
		curl_setopt($curl, CURLOPT_PROXY, $proxy); //代理服务器地址
		curl_setopt($curl, CURLOPT_PROXYPORT, $port); //代理服务器端口
	}
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiepathname); //调用对应cookie存储文件
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiepathname); //将返回的cookie写入对应文件
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'); // 模拟用户使用的浏览器
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
	curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
	curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
	$content  = curl_exec($curl); //返回结果
	$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
	$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
	$errorno  = curl_errno($curl);
	//关闭curl
	curl_close($curl);
	//echo $httpcode,'-----',$errorno,'-----',$url,'-----',$data,'<br><br>';
	return $content;
}



//===========================================================================================================



//字符替换
function hy_tonull($str) {
	$replace = array("\t", "\r", "\n",);
	return str_replace($replace, '', $str);

}

function hy_vget($url, $timeout=30000) {
	if( !function_exists('curl_init') ){
		return false;
	}
	if(substr($url,0,7)!='http://' && substr($url,0,8)!='https://' ) {
		return 'url_error';
	}

	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址

	$randiparrt = hy_randipportfile();
	$proxy = isset($randiparrt['ip'])?$randiparrt['ip']:'';
	$port = isset($randiparrt['port'])?$randiparrt['port']:'';
	echo $proxy.':'.$port;
	if($proxy!='') {
		curl_setopt($curl, CURLOPT_PROXY, $proxy); //代理服务器地址
		curl_setopt($curl, CURLOPT_PROXYPORT, $port); //代理服务器端口
	}
	
	
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	// 	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiepathname); //调用对应cookie存储文件
	// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiepathname); //将返回的cookie写入对应文件
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'); // 模拟用户使用的浏览器
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
	//echo $httpcode,'-----',$errorno,'-----',$url,'-----',$data,'<br><br>';
	return $content;
}


function hy_vpost($url,$data='',$timeout=30000 ){
	if( ! function_exists('curl_init') ){
		return FALSE;
	}
	if(substr($url,0,7)!='http://' && substr($url,0,8)!='https://' ) {
		return 'url_error';
	}
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	// 	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiepathname); //调用对应cookie存储文件
	// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiepathname); //将返回的cookie写入对应文件
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'); // 模拟用户使用的浏览器
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
	curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
	curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
	$content  = curl_exec($curl); //返回结果
	$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
	$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
	$errorno  = curl_errno($curl);
	//关闭curl
	curl_close($curl);
	//echo $httpcode,'-----',$errorno,'-----',$url,'-----',$data,'<br><br>';
	return $content;
}


















