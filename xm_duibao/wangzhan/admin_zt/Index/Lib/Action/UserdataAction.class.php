<?php

//用户信息的展示
class UserdataAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_index         = 'nolock';
	
	//用户信息的查询
	public function index(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chexiaofabu);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-m-01');
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
		
		$Model = new Model();
		
		//查询的条件
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('users')
						-> count();     // 查询满足要求的总记录数
		$Page = new Page($count,20);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();         // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('users')
						-> order('id desc')
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		foreach ($list as $key=>$val){
			
			if($list[$key]['flag']=='1'){
				$list[$key]['flag']='1-金牌会员';
			}else if($list[$key]['flag']=='2'){
				$list[$key]['flag']='2-银牌会员';
			}else if($list[$key]['flag']=='3'){
				$list[$key]['flag']='3-铜牌会员';
			}else if($list[$key]['flag']=='4'){
				
				$list[$key]['flag']='4-普通会员';
			}
			
		}
		
		$this->assign('list',$list);
		
		
		
		$this->display();
		
	}
	
	
	//页面的修改展示页
	public function updateshow(){
		
		
		//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//接收参数
		$id = $this->_post('id');
		
		$Model = new Model();
		
		//账单类型
		$typearr = array(
				'1'=> '1-金牌',
				'2'=> '2-银牌',
				'3'=> '3-铜牌',
				'4'=> '4-普通',
		);
		
		$optiontype  = '<option value=""></option>';
		foreach($typearr as $valc) {
			$optiontype .= '<option value="'.$valc.'"';
				
				
			if($valc==$flag) {
		
				$optiontype .= ' selected="selected" ';
			}
			$optiontype .= '>'.$valc.'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		
		
		$seldata = "select * from users where id='".$id."'";
		$listdata = $Model->query($seldata);
		
		$this->assign('list',$listdata[0]);
		
		$this->display();
	}
	
	
	//数据的修改添加
	public function updatedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_fabudata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//接收参数
		$id    = $this->_post('id');
		$flag  = substr($this->_post('flag'),0,1);
		$year  = $this->_post('year');
		$zj_month = $this->_post('zj_month');
		$remark = $this->_post('remark');
		
		
		$update_submit  = $this->_post('update_submit');
		
		$Model = new Model();
		
		if($update_submit!=''){
			
			
			$buytime=strtotime(date('Y-m-d H:i:s',time()));//获取当前日期并转换成时间戳
			
			$datetime=$buytime+60*60*24*30*$year;
			
			if(abs($zj_month)>0){
				
				$overtime = $datetime+60*60*24*30*$zj_month;
				
			}else{
				
				$overtime=$datetime;
			}
		
			$data = array();
			$data['flag']     = $flag;
			$data['hymonth']  = $year;
			$data['remark']   = $remark;
			$data['buytime']  = date('Y-m-d H:i:s',time());
			$data['overtime'] = date('Y-m-d H:i:s',$datetime);
			
			$ret = $Model->table('users')->where("id='".$id."'")->save($data);
			
			//echo $Model->getLastsql();exit;
			if($ret) {
				echo "<script>alert('修改成功！');window.location.href='".__APP__."/Userdata/index".$yuurl."';</script>";
				$this ->success('修改成功！','__APP__/Userdata/index'.$yuurl);
			}else {
				echo "<script>alert('修改失败!');history.go(-1);</script>";
				$this -> error('修改失败!');
			}
		}
		
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