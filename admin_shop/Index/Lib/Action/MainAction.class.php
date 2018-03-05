<?php


class MainAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_index = '97531';
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		/* $this->loginjudgeshow($this->lock_index); */
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$username = session(HYSESSQZ.'username');
		$xingming = session(HYSESSQZ.'xingming');
		
		
		//为变量赋值
		$this -> assign('username',$username);
		$this -> assign('xingming',$xingming);
		
		$Model = new Model();
		$list = $Model -> table('user_admin') -> field('lastLoginTime,lastLoginIp,qq') -> where("username='".$username."'") -> find();
		
		$lastLoginTime = substr($list['lastLoginTime'],0,19);
		$lastLoginIp   = $list['lastLoginIp'];
		$qq            = $list['qq'];
		
		$this -> assign('lastLoginTime',$lastLoginTime);
		$this -> assign('lastLoginIp',$lastLoginIp);
		$this -> assign('qq',$qq);
		
		
		// 输出模板
		$this->display();
		
	}
	
	
	
	
	//用户qq信息ajax触发修改
	public function qqchange() {
		
		$Model = new Model();
		
		$qq = '';
		$username = session(HYSESSQZ.'username');
		
		//判断用户提交的qq是否符合规范
		if($this->_post('qq')!=null) {
			$qq = $this -> _post('qq');
		}
		if($qq != '') {
			if(strlen($qq)<4 || strlen($qq)>13) {
				echo '更新失败，qq号长度不符合规范';
				exit;
				//$this -> error('qq号长度不符合规范');
			}else if(!is_numeric($qq)) {
				echo '更新失败，qq号只能由数字组成';
				exit;
				//$this -> error('qq号只能由数字组成');
			}
		}else {
			//echo '您未填写qq数据';
		}
		
		
		$data['qq'] = $qq;
		
		$ret = $Model -> table('user_admin') -> where("username='".$username."'")->save($data);
		
		if($ret) {
			echo '更新成功';
		}else {
			echo '更新失败，系统错误';
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