<?php


class LoginAction extends Action {
	
	public function index() {

		$username = session(HYSESSQZ.'username');
		$password = session(HYSESSQZ.'password');
		$siteid   = session(HYSESSQZ.'siteid');
		
		if($username!='' && $password!='') {
			
			//提示用户不能二次登陆
			echo "<script>alert('您已经登录，不能再次进行登陆!');top.location.href='".__APP__."/Index' </script>";
			$this -> error('您已经登录，不能再次进行登陆!','__APP__/?userxr='.$username);
		}
		
		
		// 输出模板
		$this->display();
		
		
	}
	
	
	
	
	//判断用户提交的登录数据
	public function login_x() {
		
		if($this->_post('submit')!=null) {
			$username = '';
			$passwd   = '';
			
			if($this->_post('username')!=null) {
				$username = strtolower(trim($this -> _post('username')));
			}else {
				$this -> error('用户名不能为空！','__APP__/Login/index');
			}
			
			if($this->_post('passwd')!=null) {
				$passwd = $this -> _post('passwd');
			}else {
				$this -> error('密码不能为空！','__APP__/Login/index');
			}
			
			//连接数据库，查询用户名所对应的数据
			$Model = new Model();
			
			$list = $Model -> table('shop_site') -> where("phone='".$username."' and pay='1' and flag='1' ")->find();
			
			
			
			$passwd_md5 = md5($passwd);
			if(!empty($list)) {
				if($passwd_md5==$list['password']) {
					//设置session
					session(HYSESSQZ.'username',$username);
					session(HYSESSQZ.'password',$passwd_md5);
					session(HYSESSQZ.'siteid',$list['id']); //商户编号
					
					//登陆成功，直接跳转到主页面
					$this->redirect("__APP__/Index/index?userxr=".$username);
					
				}else {
					$this -> error('密码错误！','__APP__/Login/index');
				}
				
			}else {
				$this -> error('用户名不存在！','__APP__/Login/index');
			}
			
		}
		
	}
	
	
	
	//判断用户提交的退出数据
	public function logout() {
		
		$username = session(HYSESSQZ.'username');
		$password = session(HYSESSQZ.'password');
		
		if($username!='' && $password!='') {
			//说明用户已经登陆了，session中保存有用户的信息
			//清空当前session
			session(HYSESSQZ.'username',null);
			session(HYSESSQZ.'password',null);
			session(HYSESSQZ.'siteid',null); //用户姓名
			
			echo "<script>alert('退出成功！');window.location.href='".__APP__."/Login/index';</script>";
			$this -> success('退出成功','__APP__/Login/index');
			
		}else {
			echo "<script>alert('您尚未登录，无法退出!');window.location.href='".__APP__."/Login/index';</script>";
			$this -> error('您尚未登录，无法退出!','__APP__/Login/index');
		}
		
		
	}
	
	
	
}

