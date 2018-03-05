<?php

//煤炭专区
class CocalAction extends Action {
	
	public function cocalstore(){
		
		$Model = new Model();
		
		$sql="select * from xianhuoziyuan limit 10";
		 
		$list=$Model->query($sql);
		
		
		$this->assign('list',$list);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
	
	public function cocalfinance(){
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
	
	public function cocaljiaogeku(){
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
}