<?php

class RootAction extends Action {
	//渠道信息模块
	
	
	//定义各模块锁定级别
	private $lock_editoruser    = '9';
	private $lock_editoruser_x  = '9';
	private $lock_editoruser_xx = '9';
	private $lock_deluser_x     = '9';
	private $lock_adduser       = '9';
	private $lock_adduser_x     = '9';
	private $lock_caozuo_log    = '9';
	private $lock_roottext      = '9';
	private $lock_gonggao       = '9';
	private $lock_gonggao_x     = '9';
	
	
	public function editoruser() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editoruser);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$Model = new Model();
		$list = $Model -> table('user_admin') -> field('username,xingming,lastLoginTime,lastLoginIp,loginflag,lockflag,rootflag,checkflag,null as state') -> order('lockflag desc,rootflag desc,username') -> select();
		
		foreach($list as $key => $val) {
			if($list[$key]['rootflag']==9) {
				$list[$key]['rootflag'] = '<b><font color="purple">9-超级管理员用户</font></b>';
			}else if($list[$key]['rootflag']==7) {
				$list[$key]['rootflag'] = '<font color="red">7-高级用户</font>';
			}else if($list[$key]['rootflag']==5) {
				$list[$key]['rootflag'] = '<font color="orange">5-普通用户</font>';
			}else if($list[$key]['rootflag']==3) {
				$list[$key]['rootflag'] = '<font color="blue">3-客服用户</font>';
			}else if($list[$key]['rootflag']==1) {
				$list[$key]['rootflag'] = '1-浏览查询用户';
			}else {
				$list[$key]['rootflag'] = 'error';
			}
			
			if($list[$key]['lockflag']==1) {
				$list[$key]['lockflag'] = '<font color="green">未禁用</font>';
			}else if($list[$key]['lockflag']==-1) {
				$list[$key]['lockflag'] = '<font color="red">已禁用</font>';
			}else if($list[$key]['lockflag']==1) {
				$list[$key]['lockflag'] = 'error';
			}
			
			$list[$key]['lastLoginTime'] = substr($list[$key]['lastLoginTime'],0,19);
			
			if($list[$key]['loginflag']==1) {
				$list[$key]['state'] = '<font color="red">已经下线</font>';
			}else {
				$time = time();
				$lasttime = strtotime($list[$key]['lastLoginTime']);
				$spantime = $time - $lasttime;
				if($spantime>660) {
					$list[$key]['state'] = '<font color="red">可能已经下线</font>';
				}else {
					$list[$key]['state'] = '<font color="blue">可能在线</font>';
				}
			}
			
			
		}
		
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	public function editoruser_x() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editoruser_x);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$username_edit = $this->_post('edit_username_val');
		if(empty($username_edit)) {
			echo "<script>alert('非法进入此页面!');history.go(-1);</script>";
			$this -> error('非法进入此页面!');
		}else {
			if(trim($username_edit)=='root') {
				echo "<script>alert('超级管理员不可编辑，root用户受代码保护！');history.go(-1);</script>";
				$this -> error('超级管理员不可编辑，root用户受代码保护！');
			}
		}
		
		
		$Model = new Model();
		
		$list = $Model -> table('user_admin') -> field('username,xingming,lockflag,rootflag,checkflag') -> where("username='".$username_edit."'") -> find();
		
		
		//start--------------------------------------------------------------
		//动态生成权限下拉选项
		$rootarr = array(
				'1' => '1-浏览用户',
				'3' => '3-客服用户',
				'5' => '5-普通用户',
				'7' => '7-高级用户',
				//'9' => '9-超级管理员',
		);
		
		$rootflag_show = '';
		foreach($rootarr as $keyr => $valr) {
			$rootflag_show .= '<option value="'.$keyr.'" ';
			if($keyr==$list['rootflag']) {
				$rootflag_show .= ' selected="selected"';
			}
			$rootflag_show .= '>'.$valr.'</option>';
			
		}
		$this -> assign('rootflag_show',$rootflag_show);
		//end--------------------------------------------------------------
		
		
		
		//start--------------------------------------------------------------
		//动态生成权限下拉选项
		$lockflag_show = '';
		
		$lockflag_show .= '<option value="1" ';
		if($list['lockflag'] == 1) {
			$lockflag_show .= ' selected="selected"';
		}
		$lockflag_show .= '>未禁用</option>';
		
		$lockflag_show .= '<option value="-1" ';
		if($list['lockflag'] == -1) {
			$lockflag_show .= ' selected="selected"';
		}
		$lockflag_show .= '>已禁用</option>';
		
		$this -> assign('lockflag_show',$lockflag_show);
		//end--------------------------------------------------------------
		
		
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	public function editoruser_xx() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editoruser_xx);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$Model = new Model();
		
		//判断用户提交数据
		$username      = $this->_post('username');
		$xingming      = $this->_post('xingming');
		$resetpasswd   = $this->_post('resetpasswd');
		$resetrepasswd = $this->_post('resetrepasswd');
		$rootflag      = $this->_post('rootflag');
		$lockflag      = $this->_post('lockflag');
		$checkflag     = $this->_post('checkflag'); //审核权限字符串
		$checkflag = strtoupper(trim($checkflag)); //全部转换为大写
		
		
		if(trim($xingming)=='') {
			echo "<script>alert('姓名不允许为空，请填写姓名后再次提交');history.go(-1);</script>";
			$this -> error('姓名不允许为空，请填写姓名后再次提交');
		}
		
		$sql_xingming = "select count(*) as con from user_admin where xingming='".$xingming."' and username<>'".$username."'";
		$list_xingming = $Model -> query($sql_xingming);
		if($list_xingming[0]['con']>0) {
			echo "<script>alert('姓名存在重复，请改为其他的姓名');history.go(-1);</script>";
			$this -> error('姓名存在重复，请改为其他的姓名');
		}
		
		if(empty($username)) {
			echo "<script>alert('非法进入此页面!');history.go(-1);</script>";
			$this -> error('非法进入此页面!');
		}else {
			if(trim($username)=='root') {
				echo "<script>alert('超级管理员不可编辑，root用户受代码保护！');history.go(-1);</script>";
				$this -> error('超级管理员不可编辑，root用户受代码保护！');
			}
		}
		
		
		$list = $Model -> table('user_admin') -> field('username') -> where("username='".$username."'") -> find();
		if(empty($list['username'])) {
			echo "<script>alert('非法操作，该用户不存在!');history.go(-1);</script>";
			$this -> error('非法操作，该用户不存在!','__APP__/Root/editoruser');
		}
		
		$setpasswd = '';
		if(!empty($resetpasswd)&&!empty($resetrepasswd)) {
			//说明用户设置了重置密码
			//判断两次密码是否一致
			if($resetpasswd==$resetrepasswd) {
				//判断密码长度
				if(strlen($resetpasswd)<6) {
					echo '<br/><font style="color:red;font-size:36px"><b>密码重置失败，密码长度不能小于6位！</b></font>';
				}else {
					//----------------------------------------
					//检测密码的强度
					$qiangdu = yu_passwdStrength($resetpasswd);
						
					if($qiangdu<4) {
						echo "<script>alert('很抱歉，您的密码强度得分为".$qiangdu."分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');history.go(-1);</script>";
						$this -> error('很抱歉，您的密码强度得分为'.$qiangdu.'分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');
					}else {
						echo '<font size="+2"><b>密码强度得分：'.$qiangdu.'分</b></font>';
					}
					//----------------------------------------
					
					$setpasswd = $resetpasswd;
				}
				
			}else {
				echo '<br/><font style="color:red;font-size:36px"><b>密码重置失败，两次密码不一致！</b></font>';
			}
			
		}
		
		if(!empty($setpasswd)) {
			$password_change = md5($setpasswd);
			$sql_change = "update user_admin set passwd='".$password_change."' where username='".$username."'";
			
			$a = $Model -> execute($sql_change);
			if($a) {
				echo '<br/><font style="color:blue;font-size:36px"><b>用户密码重置成功</b></font>';
			}else {
				echo '<br/><font style="color:red;font-size:36px"><b>用户密码重置失败</b></font>';
			}
		}
		
		//将新密码写入数组变量
		$datauser = array();
		$datauser['xingming'] = $xingming;
		$datauser['rootflag'] = $rootflag;
		$datauser['lockflag'] = $lockflag;
		$datauser['checkflag'] = $checkflag;
		
		$ret = $Model -> table('user_admin') -> where("username='".$username."'")->save($datauser);
		
		/* $templogs = $Model->getlastsql();
		hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__); */
		
		
		echo "<script>alert('用户信息修改操作执行完成!');window.location.href='".__APP__."/Root/editoruser';</script>";
		$this ->success('用户信息修改操作执行完成!','__APP__/Root/editoruser');
		
		
	}
	
	
	
	
	public function deluser_x() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deluser_x);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		if($this->_post('submitdel')!=null) {
			if($this->_post('username')!=null) {
				$username = trim(strtolower($this->_post('username')));
				//查询用户名是否存在
				$Model = new Model();
				$list = $Model -> table('user_admin') -> where("username='".$username."'") -> find();
				if(empty($list)) {
					//说明此用户不存在
					echo "<script>alert('删除失败，非法操作，您要删除的用户不存在');history.go(-1);</script>";
					$this -> error('删除失败，非法操作，您要删除的用户不存在');
				}else {
							
					//判断该用户名是否为root，不允许删除root用户
					if(trim($username)=='root') {
						echo "<script>alert('非常抱歉，此操作不能执行，root账户受系统保护，不能被删除，如果必须要删除root用户，请到后台修改源代码后再次进行删除操作');history.go(-1);</script>";
						$this -> error('非常抱歉，此操作不能执行，root账户受系统保护，不能被删除，如果必须要删除root用户，请到后台修改源代码后再次进行删除操作');
					}
					
					//判断该用户名是否为该用户本身，不允许自己删除自己的操作
					if(trim($username)==session(HYSESSQZ.'username')) {
						echo "<script>alert('非常抱歉，此操作不能执行，本系统不允许自己删除自己的操作');history.go(-1);</script>";
						$this -> error('非常抱歉，此操作不能执行，本系统不允许自己删除自己的操作');
					}
					
					//删除该用户
					$Model = new Model();
					$ret = $Model -> table('user_admin') -> where("username='".$username."'") -> delete();
					
					
					
					
					if($ret) {
						echo "<script>alert('数据删除成功!');window.location.href='".__APP__."/Root/editoruser';</script>";
						$this -> success('数据删除成功!','__APP__/Root/editoruser');
					}else {
						echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
						$this -> error('数据删除失败，系统错误!');
					}
					
					
				}
				
			}else {
				echo "<script>alert('非法操作，请正常执行删除操作');history.go(-1);</script>";
				$this -> error('非法操作，请正常执行删除操作');
			}
		}
		
		
		
	}
	
	
	
	public function adduser() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_adduser);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	public function adduser_x() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_adduser_x);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		if($this->_post('submit')!=null) {
			
			$username = '';
			$passwd   = '';
			$xingming = '';
			
			if($this->_post('username')!=null) {
				$username = trim(strtolower($this->_post('username')));
				
				$pattern = '/^[a-z0-9_-]{4,20}$/';
				preg_match($pattern, $username, $matches);
				if(empty($matches)) {
					echo "<script>alert('用户名不能出现中文，同时不能出现除下划线和中划线之外的所有特殊符号，且长度必须在4到20位之间，大写字母会被系统自动转为小写');history.go(-1);</script>";
					$this -> error('用户名不能出现中文，同时不能出现除下划线和中划线之外的所有特殊符号，且长度必须在4到20位之间，大写字母会被系统自动转为小写');
				}
				
			}else {
				echo "<script>alert('用户名不能为空');history.go(-1);</script>";
				$this -> error('用户名不能为空');
			}
			
			if($this->_post('username')!=null) {
				$xingming = trim($this->_post('xingming'));
			}else {
				echo "<script>alert('姓名不能为空');history.go(-1);</script>";
				$this -> error('姓名不能为空');
			}
			
			if($this->_post('passwd')!='' && $this->_post('repasswd')!='') {
				$pass1 = $this->_post('passwd');
				$pass2 = $this->_post('repasswd');
				if($pass1===$pass2) {
					$passwd = $pass1;
				}else {
					echo "<script>alert('两次密码不一致');history.go(-1);</script>";
					$this -> error('两次密码不一致');
				}
				
				if(strlen($passwd)<6) {
					echo "<script>alert('密码长度不能小于6位');history.go(-1);</script>";
					$this -> error('密码长度不能小于6位');
				}else {
					//----------------------------------------
					//检测密码的强度
					$qiangdu = yu_passwdStrength($passwd);
					
					if($qiangdu<4) {
						echo "<script>alert('很抱歉，您的密码强度得分为".$qiangdu."分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');history.go(-1);</script>";
						$this -> error('很抱歉，您的密码强度得分为'.$qiangdu.'分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');
					}else {
						echo '<font size="+2"><b>密码强度得分：'.$qiangdu.'分</b></font>';
					}
					//----------------------------------------
				}
				
			}else {
				echo "<script>alert('密码不能为空');history.go(-1);</script>";
				$this -> error('密码不能为空');
			}
			
			$Model = new Model();
			
			//判断该用户名是否存在
			$sql_username = "select count(*) as con from user_admin where username='".$username."'";
			$list_username = $Model -> query($sql_username);
			if($list_username[0]['con']<=0) {
				$sql_xingming = "select count(*) as con from user_admin where xingming='".$xingming."'";
				$list_xingming = $Model -> query($sql_xingming);
				if($list_xingming[0]['con']>0) {
					echo "<script>alert('姓名存在重复，请改为其他的姓名');history.go(-1);</script>";
					$this -> error('姓名存在重复，请改为其他的姓名');
				}
				
				//添加用户
				$data['username'] = $username;
				$data['passwd']   = md5($passwd);
				$data['xingming']   = $this -> _post('xingming');
					
				$ret = $Model -> table('user_admin') -> add($data);
				
				/* $templogs = $Model->getlastsql();
				hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__); */
				
				
				if($ret) {
					echo "<script>alert('数据添加成功!');window.location.href='".__APP__."/Root/editoruser';</script>";
					$this -> success('数据添加成功!','__APP__/Root/editoruser');
				}else {
					echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据添加失败，系统错误!');
				}
				
			}else {
				echo "<script>alert('该用户名已经存在，无法使用该用户名进行新用户的创建！');history.go(-1);</script>";
				$this -> error('该用户名已经存在，无法使用该用户名进行新用户的创建！');
			}
			
			
			
		}
		
		
	}
	
	
	
	 //操作日志记录
	public function caozuo_log() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_caozuo_log);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$date_s = $this->_get('date_s'); //开始时间
		$s_time = $this->_get('s_time');
		$date_e = $this->_get('date_e'); //结束时间
		$e_time = $this->_get('e_time');
		
		$who    = trim($this->_get('who'));
		$ttype  = trim($this->_get('ttype'));
		$this -> assign('who',$who);
		$this -> assign('ttype',$ttype);
		
		
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-m-d');
		}
		if($s_time=='') {
			$s_time = '00:00:00';
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		if($e_time=='') {
			$e_time = '23:59:59';
		}
		
		
		$date_s = date('Y-m-d',strtotime($date_s));
		$s_time = date('H:i:s',strtotime($date_s.' '.$s_time));
		$date_e = date('Y-m-d',strtotime($date_e));
		$e_time = date('H:i:s',strtotime($date_s.' '.$e_time));
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		$this -> assign('s_time',$s_time);
		$this -> assign('e_time',$e_time);
		
		//-----------------------------------------------------------------
		if($this->_get('submit_select')!=null) {
			//用户提交了查询
				
			$Model = new Model();
			
			$sstart_time = $date_s.' '.$s_time;
			$send_time = $date_e.' '.$e_time;
			
			$sql_where = " create_datetime>='".$sstart_time."' and create_datetime<='".$send_time."' ";
			if($who!='') { $sql_where .= " and who='".$who."' "; }
			if($ttype!='') { $sql_where .= " and ttype like '%".$ttype."%' "; }
			
			
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('caozuo_log')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
		
			//执行SQL查询语句
			$list  = $Model -> table('caozuo_log')
							-> where($sql_where)
							-> order('id desc')
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
			
			
			if(count($list)>0) {
				foreach($list as $keyc =>$valc) {
					$list[$keyc]['content'] = nl2br(base64_decode($list[$keyc]['content']));
				}
			}
			
			
			$this -> assign('list',$list);
			
			
		}
		//-----------------------------------------------------------------
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
		
		
		
		
		
		
	}
	
	
	
	
	//管理员操作文档模块
	public function roottext() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_roottext);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//文件信息存放路径
		$filename = './Public/mailto/roottext';
		$rootstr = '';
		
		if($this->_post('submit_t')!=null && $this->_post('flag_t')=='flag_t') {
			//将修改加密后写入文件
			$textaa = $this->_post('textaa');
			$jmstr = '';
			if($textaa !='') {
				$jmstr = $textaa^str_repeat('_',strlen($textaa));
			}
			file_put_contents($filename, $jmstr);
			$rootstr = $textaa;
			
		}else {
			$jmstr = file_get_contents($filename);
			if(!empty($jmstr)) {
				$rootstr = $jmstr^str_repeat('_',strlen($jmstr));
			}
		}
		
		$this -> assign('rootdocument',$rootstr);
		
		
		// 输出模板
		$this->display();
		
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	} 
	
	
	
	
	public function gonggao() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_gonggao);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//获取公告内容
		$gonggao = file_get_contents('./Public/mailto/gonggao_m');
		
		$this -> assign('gonggao',$gonggao);
		
		//获取公告浏览者用户名
		$userstr = file_get_contents('./Public/mailto/gonggao_p');
		$userstr = '浏览过该公告的用户名称：'.$userstr;
		
		$this -> assign('userstr',$userstr);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	
	public function gonggao_x() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_gonggao_x);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$gonggao = $this -> _post('gonggao');
		//修改公告内容记录文件
		file_put_contents('./Public/mailto/gonggao_m', $gonggao);
		
		$time = date('Y-m-d H:i');
		if(empty($gonggao)) {
			$time = '无';
		}
		//修改公告发布时间记录文件
		file_put_contents('./Public/mailto/gonggao_t',$time);
		//清空用户浏览记录文件
		file_put_contents('./Public/mailto/gonggao_p','');
		
		echo "<script>alert('公告更新成功');window.location.href='".__APP__."/Root/gonggao';</script>";
		$this -> success('公告更新成功','__APP__/Root/gonggao');
		
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