<?php


class MainAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_index = '97531';
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		$username = session(HYSESSQZ.'username');
		$xingming = session(HYSESSQZ.'xingming');
		
		
		//为变量赋值
		$this -> assign('username',$username);
		$this -> assign('xingming',$xingming);
		
		$Model = new Model();
		$list = $Model -> table('user_admin') -> field('lastLoginTime,lastLoginIp,qq') -> where("username='".$username."'") -> find();
		
		//每日带处理的订单
		$ordersql  = "select count(*) as num from shop_userbuy where order_createtime>='".date('Y-m-d 00:00:00')."' and order_createtime<='".date('Y-m-d 23:59:59')."' and keystr='' ";
		$orderlist = $Model->query($ordersql);
		
		$this -> assign('orderlist',$orderlist[0]);
		
		$addordersql = "select count(*) as num from shop_userbuy where order_createtime>='".date('Y-m-d 00:00:00')."' and order_createtime<='".date('Y-m-d 23:59:59')."' ";
		$addordersql = $Model->query($addordersql);
		
		$this -> assign('addordersql',$addordersql[0]);
		
		$addusersql  = "select count(*) as num from xb_user where create_datetime>='".date('Y-m-d 00:00:00')."' and create_datetime<='".date('Y-m-d 23:59:59')."' and is_lock='1' ";
		$adduserlist = $Model->query($addusersql);
		
		$this -> assign('adduserlist',$adduserlist[0]);
		
		$addusernumsql  = "select count(*) as num from xb_user where is_lock='1' ";
		$addusernumlist = $Model->query($addusernumsql);
		
		$this -> assign('addusernumlist',$addusernumlist[0]);
		
		
		
		//每日带处理的订单
		$order = $Model -> table('shop_userbuy') -> field('lastLoginTime,lastLoginIp,qq') -> where("username='".$username."'") -> find();
		
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