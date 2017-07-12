<?php

class HyCookie {
	
	
	private $path   = '';
	private $domain = '';
	
	
	
	
	public function __construct($path='', $domain='') {
		
		$this->path = $path;
		$this->domain = $domain;
		
	}
	
	
	
	//设置cookie值
	public function set($name, $value, $expire='') {
		
		if($name=='') {
			return 'error_the_name_is_null';
		}else {
			
			if($expire!=='') {
				$ttime = time()+intval($expire);
			}else {
				$ttime = null;
			}
			setcookie( $name, $value, $ttime, $this->path, $this->domain );
			
			return true;
			
		}
		
	}
	
	
	
	//获取指定cookie
	public function get($name='') {
		
		if($name=='') {
			return null;
		}else {
			
			if(isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}else {
				return null;
			}
		}
		
	}
	
	
	
	//删除指定cookie
	public function del($name) {
		
		if($name=='') {
			return 'error_the_name_is_null';
		}else {
			setcookie($name, '', time()-3600, $this->path, $this->domain );
			unset($_COOKIE[$name]);
			
			return true;
		}
		
	}
	
	
	//销毁cookie
	public function destroy() {
		
		if (empty($_COOKIE)) {
			return true;
		}else {
			
			foreach ($_COOKIE as $key => $val) {
				setcookie($key, '', time() - 3600, $this->path, $this->domain );
				unset($_COOKIE[$key]);
			}
			
			return true;
			
		}
		
	}
	
	
	
}



