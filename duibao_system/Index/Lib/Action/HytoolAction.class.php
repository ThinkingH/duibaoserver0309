<?php

class HytoolAction extends Action {
	//hy工具集
	
	//定义各模块锁定级别
	private $lock_provincehebingsum = '9751';
	
	
	
	
	public function hy_vpost($url, $data, $timeout=5000 ){ // 模拟提交数据函数
		
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
		
		return $content;
		
// 		//定义return数组变量
// 		$retarr = array();
// 		$retarr['content']  = $content;
// 		$retarr['httpcode'] = $httpcode;
// 		$retarr['run_time'] = $run_time;
// 		$retarr['errorno']  = $errorno;
		
// 		return $retarr;
		
	}
	
	
	
	
	
	
	//判断用户是否登陆的前台展现封装模块
	private function loginjudgeshow($lock_key) {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$lockarr = loginjudge($lock_key);
		if($lockarr['grade']=='C') {
			//通过
		}else if($lockarr['grade']=='B') {
			exit($lockarr['exitmsg']);
		}else if($lockarr['grade']=='A') {
			echo $lockarr['alertmsg'];
			$this -> error($lockarr['errormsg'],'__APP__/Login/index');
		}else {
			exit('系统错误，为确保系统安全，禁止登入系统');
		}
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
	}
	
	
	
	
}