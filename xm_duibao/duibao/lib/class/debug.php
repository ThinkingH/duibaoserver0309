<?php


//断开连接后继续执行，参数用法详见手册
ignore_user_abort(true);



$g_input = $_SERVER["REQUEST_URI"];
$p_input = file_get_contents("php://input");

$log_str = "\n".'REPBASE_BEGIN-----------------------------------------------------------'."\n".
		date('Y-m-d H:i:s').'    '.$mname."\n".
		'get:    '.$g_input."\n".
		'post:    '.$p_input."\n";

$filepath = './logs/'.date('Y-m').'/';
$filename = date('Y-m-d_H').'.log';



$getcanshuarr = explode('debug.php',$g_input);
$getcanshustr = isset($getcanshuarr[1])?$getcanshuarr[1]:'';


$xbiniturl = 'http://114.215.222.75:8001/duidui/interface/xbinit.php'.$getcanshustr;


$res = hy_vpost($xbiniturl,$p_input,5000);


$httpcode = isset($res['httpcode'])?$res['httpcode']:'';
$run_time = isset($res['run_time'])?$res['run_time']:'';
$errorno  = isset($res['errorno'])?$res['errorno']:'';
$content  = isset($res['content'])?$res['content']:'';



$log_str .= $httpcode.'    '.$run_time.'    '.$errorno."\n".$content."\n";


hy_writelog($filepath,$filename,$log_str);

echo $content;








function hy_vpost($url,$data,$timeout=5000 ){ // 模拟提交数据函数

	if( ! function_exists('curl_init') ){
		return FALSE;
	}

	$curl = curl_init(); // 启动一个CURL会话

	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器

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


	//定义return数组变量
	$retarr = array();
	$retarr['content']  = $content;
	$retarr['httpcode'] = $httpcode;
	$retarr['run_time'] = $run_time;
	$retarr['errorno']  = $errorno;

	return $retarr;

}


function hy_writelog($path, $name, $data) {
	//判断该日志文件存放路径是否存在，不存在则进行创建

	if(!is_dir($path)) {
		//创建该目录
		mkdir($path, 0777, true);
	}

	//生成文件路径名称
	$filepathname = $path.$name;

	$fp = fopen($filepathname,'a'); //打开句柄
	fwrite($fp, $data);  //将文件内容写入字符串
	fclose($fp); //关闭句柄


}



