<?php


class HySession {
	
	
	public function __construct() {
		session_start();
	}
	
	
	
	//设置session
	public function set($name='', $value='') {
		
		if($name=='') {
			return false;
		}else {
			$_SESSION[$name] = $value;
			return true;
		}
		
	}
	
	
	
	//获取指定session；
	public function get($name) {

		if($name=='') {
			return null ;
		}else {
			if(isset($_SESSION[$name])) {
				return $_SESSION[$name];
			}else {
				return null;
			}
		}
		
	}
	
	
	
	//删除指定session
	public function del($name) {
		
		if(isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
		
		return true;
		
	}
	
	
	
	//销毁session
	public function destroy() {
		
		$_SESSION = array();
		session_destroy();
		
		return true;
		
	}
	
	
	
}
