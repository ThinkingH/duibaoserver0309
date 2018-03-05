<?php
/*
 * push推送
 */

/* 1.极光推送跳转的字段

//3--跳商品详情页
$txt = array(
    'shopid' => '',
);
//4--商品分类
$txt22 = array(
    'name' => '流量',
    'typeid' => '11',
);

//2-热门饭票
$txt = array(
        'num' => '',//0--kdj 1-hbb 3-mdl 4-zgf

);

//action 1--跳通知

//附近优惠券--点赞通知    详情页


//actin=11     name  url 条外链  */

class PushAction extends Action{
	
	//定义各模块锁定级别
	private $lock_index              = '975';
	
	
	
	public function index(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$flag = $this->_post('flag');
		$tuisongname = $this->_post('tuisongname');
		$tuisongid   = $this->_post('tuisongid');
		$tuiurl      = $this->_post('tuiurl');
		$tuisongcontent = $this->_post('tuisongcontent');
		$update_submit  = $this->_post('update_submit');
		
		if($update_submit!=''){
			
			$txt = array();
			
			if($flag=='2'){//热门饭票
					
				$txt = array(
						'num' => $tuisongid,//0--kdj 1-hbb 3-mdl 4-zgf
							
				);
			}else if($flag=='3'){
					
				$txt = array(
						'shopid' => $tuisongid,
			
				);
					
			}else if($flag=='4'){
					
				$txt = array(
							
						'name'   => $tuisongname,
						'typeid' => $tuisongid,
				);
					
			}else if($flag=='11'){
					
				$txt = array(
						'name' => $tuisongname,
						'url'   => $tuiurl,
				);
			}
			
			
			$m_type=$flag;
			$m_txt = $txt;
			
			//极光发送
			$r = $this ->func_jgpush($receiver='all',$tuisongcontent,$m_type,$m_txt);
			
			
			if($r=='ok'){
				echo "<script>alert('推送成功！');</script>";
				$this -> success('推送成功！');
			}else{
				echo "<script>alert('推送失败！');</script>";
				$this -> error('推送失败！');
			}
		}
		
		$this->display();
		
	}
	
	
	
	
	
	
	
	
	
	//极光推送($receiver='all',$content='',$m_type='',$m_txt='',$m_time='86400')
	private function func_jgpush($receiver='all',$messagee,$m_type,$m_txt){
	
		import('ORG.JiPush');
		$push = new JiPush();// 实例化上传类
			
		//极光推送的设置
		//$m_type = '';//推送附加字段的类型
		//$m_txt = '';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
		$m_time = '86400';//离线保留时间
		//$receive = array('alias'=>array($jiguangid));//别名
		$content = $messagee;
		$result = $push->push($receiver,$content,$m_type,$m_txt,$m_time);
	
		if($result){
			$res_arr = json_decode($result, true);
	
			if(isset($res_arr['error'])){                       //如果返回了error则证明失败
				echo $res_arr['error']['message'];          //错误信息
				$error_code=$res_arr['error']['code'];             //错误码
				switch ($error_code) {
					case 200:
						$message= '发送成功！';
						break;
					case 1000:
						$message= '失败(系统内部错误)';
						break;
					case 1001:
						$message = '失败(只支持 HTTP Post 方法，不支持 Get 方法)';
						break;
					case 1002:
						$message= '失败(缺少了必须的参数)';
						break;
					case 1003:
						$message= '失败(参数值不合法)';
						break;
					case 1004:
						$message= '失败(验证失败)';
						break;
					case 1005:
						$message= '失败(消息体太大)';
						break;
					case 1008:
						$message= '失败(appkey参数非法)';
						break;
					case 1020:
						$message= '失败(只支持 HTTPS 请求)';
						break;
					case 1030:
						$message= '失败(内部服务超时)';
						break;
					default:
						$message= '失败(返回其他状态，目前不清楚额，请联系开发人员！)';
						break;
				}
			}else{
				$message="ok";
			}
		}else{//接口调用失败或无响应
			$message='接口调用失败或无响应';
		}
	
		return $message;
	}
	
	
	
	//生成url拼接参数
	private function createurl($get) {
	
		$yuurl = '?';
		foreach($get as $keyg => $valg) {
			if(substr($keyg,0,6)!='submit' && $keyg!='_URL_') {
				if(is_array($valg)) {
					foreach($valg as $valcc) {
						$yuurl .= $keyg.'[]='.urlencode($valcc).'&';
					}
	
				}else {
					$yuurl .= $keyg.'='.urlencode($valg).'&';
				}
			}
		}
		$yuurl = rtrim($yuurl,'&');
	
		if(strlen($yuurl)>1) {
			return $yuurl;
		}else {
			return '';
		}
	
	
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