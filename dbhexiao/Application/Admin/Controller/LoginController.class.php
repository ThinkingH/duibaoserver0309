<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller{
	
	public function index(){
		
	}
	
	
	//用户登录
	public function login(){
		
		//获取用户名和密码
		$username = $_POST['username'];
		$password  = $_POST['password'];
		
// 		$username = '13126874659';
// 		$password = '123456';
		if(!trim($username)){
			return show('0','用户名不能为空');
		}
		
		//判断是否是手机号
		if(!is_numeric($username) || strlen($username)!=11){
			return show('0','用户名不正确');
		}
		
		if(!trim($password)){
			return show('0','密码不能为空');
		}
		
		//判断该用户是否存在
		$userlist = M('shop_site')->where('phone="'.$username.'"')->find();
		
		if(!$userlist || $userlist['flag']=='9'){
			return show('0','用户不存在');
		}
		
		if($userlist['checkstatus']!='2' || $userlist['storestatus']!='2'){
			return show('0','该用户未通过审核');
		}
		
		//判断密码是否相同
		$md5password = md5($password);
		
		if($md5password!=$userlist['password']){
			return show('0','密码错误');
		}
		//更新最后登录时间
		M('user_admin')->where('username="'.$username.'"')->save(array('lastLoginTime'=>date('Y-m-d H:i:s')));
		
		//用户名写入session
		session('adminUser',$userlist);
		
		return show('1','用户登录成功');
		
	}
	
	//退出登录
	public function logout(){
		session('adminUser',null);
		$value = session('adminUser');
		if($value==''){
			return show('1','退出成功');
		}else{
			return show('0','退出失败');
		}
		//$this->redirect('/admin.php?c=login');
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}