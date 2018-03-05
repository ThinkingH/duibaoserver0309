<?php
//尾部
class WeibuAction extends Action {
	
	public function about(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	public function advice(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
	public function friends(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	public function huiyuan(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	public function legal(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	public function protect(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	public function zhaopin(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}




}