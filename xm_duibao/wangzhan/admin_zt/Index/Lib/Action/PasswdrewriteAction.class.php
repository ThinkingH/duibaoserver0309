<?php


class PasswdrewriteAction extends Action {
	//密码修改模块
	
	
	//定义各模块锁定级别
	private $lock_index         = 'nolock';
	private $lock_rewritepasswd = 'nolock';
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$username  = session('username');
		$xingming  = session('xingming');
			
		$this -> assign('username',$username);
		$this -> assign('xingming',$xingming);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	public function rewritepasswd() {

		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_rewritepasswd);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		if($this->_post('submit')!=null) {
			
			$Model = new Model();
			
			//定义全局变量
			$username  = session('username');
			$password  = '';
			$repasswd  = '';
			$oldpasswd = '';
			
			
			//判断提交的数据是否有空值
			if($this->_post('oldpasswd')!=null) {
				$oldpasswd = $this -> _post('oldpasswd');
			}else {
				echo "<script>alert('原密码不能为空！');history.go(-1);</script>";
				$this -> error('原密码不能为空！');
			}
			
			if($this->_post('passwd')!=null || $this->_post('repasswd')!=null) {
				$passwd = $this -> _post('passwd');
				$repasswd = $this -> _post('repasswd');
			}else {
				echo "<script>alert('新密码不能为空！');history.go(-1);</script>";
				$this -> error('新密码不能为空！');
			}
			
			//判断两次输入密码是否一致
			if($passwd!=$repasswd) {
				echo "<script>alert('两次密码不一致！');history.go(-1);</script>";
				$this -> error('两次密码不一致！');
			}
			
			//限制密码最小长度
			if(strlen($passwd)<6) {
				echo "<script>alert('新密码长度不能小于6位');history.go(-1);</script>";
				$this -> error('新密码长度不能小于6位');
			}
			
			//----------------------------------------
			//检测密码的强度
			$qiangdu = yu_passwdStrength($passwd);
			
			if($qiangdu<4) {
				echo "<script>alert('很抱歉，您的密码强度得分为".$qiangdu."分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');history.go(-1);</script>";
				$this -> error('很抱歉，您的密码强度得分为'.$qiangdu.'分，满分10分，本系统要求最低得分为4分，请设置更加复杂的密码');
			}else {
				echo '<font size="+2"><b>密码强度得分：'.$qiangdu.'分，满分10分</b></font>';
			}
			//----------------------------------------
			
			
			//查询原密码是否输入正确
			$list  = $Model -> table('user_admin') -> where("username='".$username."'")->find();
			
			//对原密码进行MD5
			$oldpasswd_md5 = md5($oldpasswd);
			
			//对新密码进行MD5
			$passwd_md5 = md5($passwd);
			
			if(!empty($list)) {
				if($oldpasswd_md5==$list['passwd']) {
					//修改密码
					
					//将新密码写入数组变量
					$pas['passwd']    = $passwd_md5;
					$pas['resetflag'] = 9999;
					//$pas['qq']        = $qq;
					
					$ret   = $Model -> table('cp_user') -> where("username='".$username."'")->save($pas);
					if($ret) {
						//重新设置session
						session('username',$username);
						session('password',$passwd_md5);
						session('resetflag',9999);
						
						echo "<script>alert('密码修改成功!');window.location.href='".__APP__."/Passwdrewrite/index';</script>";
						$this ->success('密码修改成功!','__APP__/Passwdrewrite/index');
						
					}else {
						echo "<script>alert('密码修改失败，系统错误!');history.go(-1);</script>";
						$this -> error('密码修改失败，系统错误!');
					}
					
				}else {
					echo "<script>alert('原密码错误！');history.go(-1);</script>";
					$this -> error('原密码错误！');
				}
				
			}else {
				echo "<script>alert('用户名不存在，系统错误！');history.go(-1);</script>";
				$this -> error('用户名不存在，系统错误！');
			}
		
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