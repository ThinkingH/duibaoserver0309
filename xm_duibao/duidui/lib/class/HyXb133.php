<?php
/*
 * 头像的上传
 */

class HyXb133 extends HyXb{
	
	
	private $imgpath;
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
				HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
	
		//头像的存放位置
		$this->imgpath = IMAGEPATH;
		
		$this->houzhui          = isset($input_data['houzhui']) ? $input_data['houzhui'] : '' ;
		$this->imgdata          = isset($input_data['imgdata']) ? $input_data['imgdata'] : '' ;
	}
	
	
	public function controller_edituserimage(){
		
		
		//图片保存的位置$this->imgpath
		$filepath = $this->imgpath.date('Ymd').'/';
		
		if(!file_exists($filepath)) {
			//mkdir($filepath,0777);
			mkdir( $filepath, 0777, true );
		}
		//图片文件名
		$filename = date('YmdHis').rand(1000,9999).'.'.$this->houzhui;
			
		//文件的路径
		$filepathname = $filepath.$filename;
			
		//把图片的编码解码为图片，存到对应的路径中
		file_put_contents($filepathname,base64_decode($this->imgdata));
		
		
		$sql_touxiang  = "update xb_user set touxiang = '".$filepathname."' where id='".parent::__get('xb_userid')."' and tokenkey='".parent::__get('xb_userkey')."' ";
		$list_touxiang = parent::__get('HyDb')->execute($sql_touxiang);
		
		if($list_touxiang){
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '头像上传成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '头像上传失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	
	//操作入口--头像的上传
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		//判断是否为正常用户
		if(parent::__get('xb_usertype')!='1'){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//头像的上传
		$this->controller_edituserimage();
	
		return true;
	
	
	}
	
}