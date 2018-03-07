<?php 

class LoginAction extends Action {
	
	public function index(){
		
		$this->display();
	}
	
	
	//登录校验
	public function login(){
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if(!trim($username)) {
			echo show(1,'用户名不能为空');
			exit;
		}
		
		if(!trim($password)) {
			echo show(1,'密码不能为空');
			exit;
		}
		
		//$Model = new \Think\Model();// 实例化一个model对象 没有对应任何数据表
		$Model = new Model();
		//用户是否注册
		$loginuser_sql  = "select * from user_admin where username='".$username."' ";
		$loginuser_list = $Model->query($loginuser_sql);
		
		if(count($loginuser_list)<=0){
				
			echo  show(1,'该用户不存在');
			exit;
		}
		
		
		$md5_password = $loginuser_list[0]['passwd'];
		
		if($md5_password!=md5($password)){
			echo show(1,'密码错误');
			exit;
		}
		
		session('adminUser', $loginuser_list);
		echo show(0,'登录成功');
		exit;
	}
	
	
	
	//用户推出
 	public function loginout() {
        session('adminUser', null);
        $this->redirect('./index.php/Login/index');
    }
}



















?>