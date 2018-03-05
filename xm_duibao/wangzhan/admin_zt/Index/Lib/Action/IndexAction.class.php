<?php

class IndexAction extends Action {
	//html模板主入口文件
	
	public function index() {

		//说明：此文件为html主入口文件，此文件主要包括三部分，header，menu，main
		
		//判断用户是否登录
		$username  = session('username');
		$password  = session('password');
		$rootflag  = session('rootflag');
		$lockflag  = session('lockflag');
		
		if($username!='' && $password!='') {
			//说明用户已经登陆了，session中保存有用户的信息
			
		}else {
			//说明用户没有登录
			//清空session
			session(null);
			//直接强制跳转到登录页面
			$this->redirect('__APP__/Login/index');
			exit('<h1>警告：您正在试图攻击系统！</h1>');
		}
		
		$this->display(); // 输出模板
		
	}
	
	
}