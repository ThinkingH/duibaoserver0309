<?php

//大宗财经
class NewsAction extends Action {

	
	public function oillist(){
	
		$id = $this->_get('id');
	
		$Model = new Model();
	
	
		$sql="select * from zt_wx_news where id='".$id."'";
		$list=$Model->query($sql);
	
	
		$this->assign('list',$list[0]);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	
	}

	
	//煤炭价格的展示页
	public function pricecoal(){
	
		$id = $this->_get('id');
	
		$Model = new Model();
	
	
		$sql="select * from zt_wx_news where id='".$id."'";
		$list=$Model->query($sql);
	
	
		$this->assign('list',$list[0]);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
	
		$this->display();
	
	}

	
	public function coallist(){
	
		$id = $this->_get('id');
	
		$Model = new Model();
	
	
		$sql="select * from zt_coal where id='".$id."'";
		$list=$Model->query($sql);
	
	
		$this->assign('list',$list[0]);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	
	}
	
	//大宗产业-石油
	public function dazongmacrooil(){
		
		/*  */
		$Model = new Model();
		
		$sql="select id,title,content,contenttime from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 30";
		$list=$Model->query($sql);
		
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['content'],0,50,'utf-8');
		} 
		
		$this->assign('list',$list);
		
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	
	
	//大宗产业
	public function dazongchanye(){
	
		$Model = new Model();
	
		$sql="select id,title,content,contenttime from zt_wx_news where typeid='2' order by id desc limit 31,30";
		$list=$Model->query($sql);
	
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['content'],0,50,'utf-8');
		}
	
		$this->assign('list',$list);
	
		//生成随机数
		$num=rand(1, 50);
		$this->assign('num',$num);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	//煤炭
	public function dazongmacrocoal(){
	
		$Model = new Model();
	
		$sql="select id,ttitle,tcontent,createtime from zt_news where ttype='meitan' limit 30";
		$list=$Model->query($sql);
	
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['tcontent'],0,20,'utf-8');
		}
	
		$this->assign('list',$list);
	
		//生成随机数
		$num=rand(1, 50);
		$this->assign('num',$num);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	
	}
	
	
	public function dazongcoal(){
	
		$Model = new Model();
	
		$sql="select id,ttitle,tcontent,createtime from zt_news where ttype='meitan' limit 30";
		$list=$Model->query($sql);
	
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['tcontent'],0,20,'utf-8');
		}
	
		$this->assign('list',$list);
	
		//生成随机数
		$num=rand(1, 50);
		$this->assign('num',$num);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	
	}
	
	
	
	
	
	
	
	public function dazongchem(){
		
		$Model = new Model();
		
		$sql="select id,ttitle,tcontent,createtime from zt_news where ttype='huagong' limit 30";
		$list=$Model->query($sql);
		
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['tcontent'],0,50,'utf-8');
		}
		
		$this->assign('list',$list);
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
	//行业动态
	public function dazongchinanews(){
		
		$Model = new Model();
		
		$sql="select id,title,content,contenttime from zt_wx_news where typeid='2' limit 60,30";
		$list=$Model->query($sql);
		
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['content'],0,60,'utf-8');
		}
		
		$this->assign('list',$list);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	//国际新闻
	public function dazonginternews(){
	
		$Model = new Model();
	
		$sql="select id,ttitle,tcontent,createtime from zt_news where ttype='internastional' limit 30";
		$list=$Model->query($sql);
	
		foreach ($list as $key => $val){
			$list[$key]['tcontent'] = mb_substr($list[$key]['tcontent'],0,50,'utf-8');
		}
	
		$this->assign('list',$list);
	
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}

}