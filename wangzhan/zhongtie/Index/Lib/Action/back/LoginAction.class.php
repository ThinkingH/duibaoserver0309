<?php
class LoginAction extends Action {

	
	//用户登录
	public function login(){
		
		
		$account  = $this->_post('Account');
		$password = md5($this->_post('Password'));
		$submit   = $this->_post('submit');
		
		if($submit!=''){
			
			if($account==''){
				echo "<script>alert('账号不能为空！'); history.go(-1);</script>";
				$this->success('账号不能为空！');
			}
			
			if($password==''){
				echo "<script>alert('密码不能为空！'); history.go(-1);</script>";
				$this->success('密码不能为空！');
			}
			
			$Model = new Model();
			
			//判断该账户是否存在
			$sqlaccount  = "select phone,flag from users where phone='".$account."' and password='".$password."' ";
			$listaccount = $Model->query($sqlaccount); 
			 
			if(count($listaccount)>0){
				
				if($listaccount[0]['flag']=='1'){
					$listaccount[0]['flag']='金牌会员';
				}else if($listaccount[0]['flag']=='2'){
					$listaccount[0]['flag']='银牌会员';
				}else if($listaccount[0]['flag']=='3'){
					$listaccount[0]['flag']='铜牌会员';
				}else if($listaccount[0]['flag']=='4'){
					$listaccount[0]['flag']='普通会员';
				}
				//把账户名存入session
				session('user',$listaccount[0]['phone']);
				//会员等级
				session('hyflag',$listaccount[0]['flag']);
				
 				echo "<script>alert('用户登录成功！');window.location.href='".__APP__."/Index/index';</script>";
 				$this->success('用户登录成功！');
			}else{
				
				echo "<script>alert('账号或密码错误！'); history.go(-1);</script>";
				$this->error('账号或密码错误！');
			}
			
		}
		
		$this->display();
	}
	
	
	//忘记密码页面
	public function forgetpassword(){
		
		//手机号 图片验证码 短信验证码
		$mobile = $this->_post('mobile');
		$code   = $this->_post('code');
		$Mobilecode = $this->_post('MobileCode');
		$submit = $this->_post('submit');
		
		 $Model=new Model();
	 	 if($submit!=''){
			if($mobile==''){
				echo "<script>alert('请输入手机号！'); history.go(-1);</script>";
				$this->error('请输入手机号！');
			}else{
				//判断该手机是否注册
				$sqlphone  = "select id from users where phone='".$mobile."' ";
				$listphone = $Model->query($sqlphone);
				
				if(count($listphone)<=0){
					echo "<script>alert('该手机号没有注册！'); history.go(-1);</script>";
					$this->error('该手机号没有注册！');
				}
			}
			
			if($code==''){
				echo "<script>alert('请输入验证码！'); history.go(-1);</script>";
				$this->error('请输入验证码！');
			}else{
				if(session('verify') != md5($code)) {
					echo "<script>alert('验证码错误！'); history.go(-1);</script>";
					$this->error('验证码错误！');
				}
			}
			
			if($Mobilecode==''){
				echo "<script>alert('请输入短信验证码！'); history.go(-1);</script>";
				$this->error('请输入短信验证码！');
			}else{
				if(session('code')!=$Mobilecode){
					echo "<script>alert('短信验证码错误！'); history.go(-1);</script>";
					$this->error('短信验证码错误！');
				}
			}
		} 
		$this->display();
	}
	
	
	//输入新的密码
	public function editpassword(){
		
		
		//print_r($_SESSION);
		$mobile = $this->_post('mobile');
		$password = $this->_post('password');
		$repassword = $this->_post('repassword');
		$submit     = $this->_post('btnSubmit');
		
		//echo session('username');
		$rephone=session('userphone'); 
		
		$Model=new Model();
		
		if($submit!=''){
			if($password==''){
				echo "<script>alert('新密码不能为空！'); history.go(-1);</script>";
				$this->error('新密码不能为空！');
			}
			
			if($repassword==''){
				echo "<script>alert('确认新密码不能为空！'); history.go(-1);</script>";
				$this->error('确认新密码不能为空！');
			}
			
			if($password!=$repassword){
				echo "<script>alert('两次密码输入的不一致！'); history.go(-1);</script>";
				$this->error('两次密码输入的不一致！');
			}else{
				$sqlpass = "update users set password='".$password."' where phone='".$rephone."'"; 
				$listpass = $Model->execute($sqlpass);
				
				if($listpass){
					echo "<script>alert('密码修改成功！');window.location.href='".__APP__."/Login/login';</script>";
					$this->success('密码修改成功！');
					
				}else{
					echo "<script>alert('密码修改失败！'); history.go(-1);</script>";
					$this->error('密码修改失败！');
				}
			}
		}	
		$this->display();
	}
	
	//ajax登录判断
	public function ajax_login(){
		
		/* data: "account="+account+"&password="+password, */
		$account  = $this->_post('account');
		$password = md5($this->_post('password'));
		
		$Model = new Model();
			
		//判断该账户是否存在
		$sqlaccount  = "select id from users where phone='".$account."' and password='".$password."' ";
		$listaccount = $Model->query($sqlaccount);
		
		if(count($listaccount)<=0){
			echo 'error';
		}
	
	}
	
	//ajax--忘记密码的短信验证码的判断
	public function ajax_duanxincode(){
		
		
		$mobliecode = $this->_get('MobileCode');
		
		if(session('code')!=$mobliecode){
			echo 'dunxinfail';
		}
	}
	
	
	//用户的注册
	public function register(){
		
		$phone      = $this->_post('phone');
		$password   = $this->_post('password');
		$repassword = $this->_post('repassword');
		$code       = $this->_post('code');
		$yanzhengma = $this->_post('yanzhengma');
		$submit     = $this->_post('btnSubmit');
		
		if($submit!=''){
		
		if($phone==''){
			echo "<script>alert('请输入手机号！'); history.go(-1);</script>";
			$this->error('请输入手机号！');
		}else if(strlen($phone)!=11 || !is_numeric($phone)){
			echo "<script>alert('请输入正确的手机号！'); history.go(-1);</script>";
			$this->error('请输入正确的手机号！');
		}
		
		if($password==''){
			echo "<script>alert('请输入密码！'); history.go(-1);</script>";
			$this->error('请输入密码！');
		}else if($password<6){
			echo "<script>alert('密码长度要6位以上！'); history.go(-1);</script>";
			$this->error('密码长度要6位以上！');
		}
		
		if($repassword==''){
			echo "<script>alert('请输入密码！'); history.go(-1);</script>";
			$this->error('请输入密码！');
		}else if($repassword<6){
			echo "<script>alert('密码长度要6位以上！'); history.go(-1);</script>";
			$this->error('密码长度要6位以上！');
		}
		
		if($password!=$repassword){
			echo "<script>alert('密码和确认密码不一致，请重新输入密码！'); history.go(-1);</script>";
			$this->error('密码和确认密码不一致，请重新输入密码！');
		}
		
		if($code==''){
			echo "<script>alert('请输入短信验证码！'); history.go(-1);</script>";
			$this->error('请输入短信验证码！');
		}
		
		if($yanzhengma==''){
			
			echo "<script>alert('请输入验证码!！'); history.go(-1);</script>";
			$this->error('请输入验证码!！');
		}
		
		$Model = new Model();
		//判断该手机是否注册过
		$sql_phone = "select phone from users where phone='".$phone."' "; 
		$list_phone = $Model->query($sql_phone);
		
		if(count($list_phone)>0){
			echo "<script>alert('该手机已经注册过，请直接登录！'); history.go(-1);</script>";
			$this->error('该手机已经注册过，请直接登录！');
		}
		
		//判断验证码是否正确
		if(session('verify') != md5($yanzhengma)){
			echo "<script>alert('验证码输入错误！'); history.go(-1);</script>";
			$this->error('验证码输入错误！');
		}
		
		//判断输入的验证码是否正确
		if(session('code')!= $code){
			
			echo "<script>alert('短信验证码输入错误！'); history.go(-1);</script>";
			$this->error('短信验证码输入错误！');
		}
			
		//数据入库操作
		$date= date('y-m-d h:i:s',time());
		$mdpasswd = md5($password);
		
		//注册的信息存入数据库
		$sqlphonedata = "insert into users (phone,password,createtime) values ('".$phone."','".$mdpasswd."','".$date."')";
		$listsql      = $Model->execute($sqlphonedata);
		
		if($listsql){
		
		echo "<script>alert('用户注册成功！');window.location.href='".__APP__."/Login/registersuccess".$yuurl."';</script>";
			$this->success('用户注册成功！');
		
		}else{
			echo "<script>alert('用户注册失败！'); history.go(-1);</script>";
			$this->error('用户注册失败！');
		}
		
	}
		
		$this->display();
}

	//信息完善页面
	public function message(){
		
		
		$company  = $this->_post('company');
		$name     = $this->_post('name');
		$phone    = $this->_post('phone');
		$qq       = $this->_post('QQ');
		$email    = $this->_post('email');
		$adcompany = $this->_post('adcompany');
		$submit  = $this->_post('submit');
		
		if($submit!=''){
			
			if($company==''){
				echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
				$this->error('请填写公司名称！');
			}
			
			if($name==''){
			echo "<script>alert('请填写联系人！'); history.go(-1);</script>";
			$this->error('请填写联系人！');
			
			}
			
			if($phone==''){
				echo "<script>alert('请填写手机号！'); history.go(-1);</script>";
				$this->error('请填写手机号！');
				
			}
			
			if(strlen($qq)>11 || !is_numeric($qq)){
				echo "<script>alert('请填写正确的qq号！'); history.go(-1);</script>";
				$this->error('请填写正确的qq号！');
			}
			
			if($email==''){
				echo "<script>alert('请填写邮箱！！'); history.go(-1);</script>";
				$this->error('请填写邮箱！');
			}
			
			if($adcompany==''){
				echo "<script>alert('请填写公司地址！'); history.go(-1);</script>";
				$this->error('请填写公司地址！');
			}
			
			$Model = new Model();
			
			//数据插入到数据表中
			$sqluser = "select id from users order by id desc limit 1";
			$listuser = $Model->query($sqluser);
			if($listuser[0]['id']>0){
			//echo $listuser[0]['id'];
				$sqlmess= "update users set company='".$company."',lianxiren='".$name."',
						zuojiphone='".$phone."',email='".$email."',qq='".$qq."',address='".$adcompany."'
								where id='".$listuser[0]['id']."' ";
				$listmess= $Model->execute($sqlmess);
				
				if($listmess){
					
					echo "<script>alert('信息完成成功！');window.location.href='".__APP__."/Index/index';</script>";
					$this->success('信息完成成功！');
				}else{
					echo "<script>alert('信息完善失败！'); history.go(-1);</script>";
					$this->error('信息完善失败！');
				}
			}
		}
		
		$this->display();
	}

	
	
	//图形验证码
	Public function verify(){
		import('ORG.Util.Image1');
		Image::buildImageVerify();
	}
	
	//验证码的判断
	public function ajax_yanzhengma(){
		
		$yanzhengma = $this->_get('yanzhengma');
		
		if(session('verify') != md5($yanzhengma)) {
			echo 'error_yzm'; 
		}
	}
	
	//忘记密码--手机号的验证
	public function ajax_forphone(){
	
		$phone = $this->_get('phone');
		session('userphone',$phone);
	
		$Model = new Model();
	
		$sql_phone = "select id from users where phone='".$phone."' ";
		$list_phone = $Model->query($sql_phone);
	
		//说明该手机号没有注册过
		if(count($list_phone)<=0){
			echo 'phoneerror';
		}
	}
	
	
	//手机号的验证
	public function ajax_phone(){
		
		$phone = $this->_get('phone');
		
		$Model = new Model();
		
		$sql_phone = "select id from users where phone='".$phone."' ";
		$list_phone = $Model->query($sql_phone);
		
		//说明该手机号没有注册过
		if(count($list_phone)>0){
			echo 'phoneerror';
		}
	}
	
	//手机发送验证码
	public function ajax_code(){
		
			//获取手机号
			$phone = $this->_get('phone');
				
			//随机生成的六位验证码
			$yanzhengma = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
			
			//下发短信的内容
			$xiafaneirong = '【中铁国恒】本次验证码为：'.$yanzhengma.'，有效时间为30分钟';
			
			//随机生成的验证码，存入session中，进行校验
			session('code',$yanzhengma);
			
			/*  echo session('code'); 
			 exit; */
			
			 //用户名
			$ua='XBSC';
			//密码
			$pw='012534';
			//下发验证码的链接
			$codeurl='http://121.42.205.244:18002/send.do';
			
			//调用下发验证码的链接
			$tm = date('Y-m-d H:i:s',(time()-120));
			//md5加密的验证码
			$pw_md5=md5($pw.$tm);
			
			//短信下发的链接
			$url = $codeurl.'?ua='.$ua.'&pw='.$pw_md5.'&mb='.$phone.'&ms='.$xiafaneirong.'&tm='.$tm;
			
			$res = $this->vget($url,10000);
			
			
			$content  = isset($res['content'])  ? trim($res['content']) : '';
			$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
			$run_time = isset($res['run_time']) ? $res['run_time'] : '';
			
			
			if($httpcode == 200){
			
				if($content!=''&& $content >0){
					
					echo 'success';
					
				}else{
					echo 'fail';
				}
			
			} 
		
}

	//用户退出
	public function ajax_loginout(){
		
		$type=$this->_get('type');
		if($type=='1'){
			
			if(session('user')!=''){
				session('user',null);
			}
			//用户退出成功
			if(session('user')==null){
					
				echo 'success';
			}
		}
	}
	
	public function loginout() {
			
			$username = session('user');
			
			
			if($username!='') {
				session('user',null);
				echo "<script>alert('退出成功！');window.location.href='".__APP__."/Index/index';</script>";
				$this -> success('退出成功','__APP__/Index/index');
				
			}else {
				echo "<script>alert('您尚未登录，无法退出!');window.location.href='".__APP__."/Index/index';</script>";
				$this -> error('您尚未登录，无法退出!','__APP__/Index/index');
			}
		}
	
	
	

	
	//订单中心
	public function order(){
		
		
		$Model=new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where("userphone='".session('user')."'")
						-> count();     // 查询满足要求的总记录数
		
		$Page = new Page($count,10);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();         // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$listorder  = $Model -> table('zt_dingdan')
							-> where("userphone='".session('user')."'")
							-> order('id desc')
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
		
		//print_r($listorder);
		
		/* $sqlorder = "select id,company,phone,flag,comment,tijiao_time,filecheck from zt_dingdan where userphone='".session('user')."' order by id desc";
		$listorder = $Model->query($sqlorder); */
		
		//10--刚发布，等待初审    20--通过初审，等待复审  30---通过复审，等待终审  40---通过终审，可以进行发布
		foreach ($listorder as $key=>$val){
			
			if($listorder[$key]['flag']=='10'){
				$listorder[$key]['flags']='等待初核';
			}
			
			if($listorder[$key]['flag']=='20'){
				$listorder[$key]['flags']='等待复审';
			}
			
			if($listorder[$key]['flag']=='30'){
				$listorder[$key]['flags']='等待终审';
			}
			
			if($listorder[$key]['flag']=='40'){
					$listorder[$key]['flags']='订单成功';
			}
			
			if($listorder[$key]['flag']=='99'){
				$listorder[$key]['flags']='订单失败';
			}
		}
		
		
		$this->assign('list',$listorder);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	
	
	public function ajax_userlogin(){
		
		 if(session('user')==''){
			session('user',null);
		}
		
		
		 if(session('user')==null){
			echo 'error';
		} 
		
		
	}
	
	
	//用户的个人信息
	public function info(){
		
		
		$id=$this->_get('id');
		
		$this->assign('id',$id);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	//会员用户信息
	public function userinfo(){
		
		$Model=new Model();
		
		$seldata = "select * from users where phone='".session('user')."'";
		$listsql = $Model->query($seldata);
		
		if($listsql[0]['flag']=='1'){
			$listsql[0]['flag']='金牌会员';
		}else if($listsql[0]['flag']=='2'){
			$listsql[0]['flag']='银牌会员';
		}else if($listsql[0]['flag']=='3'){
			$listsql[0]['flag']='铜牌会员';
		}else if($listsql[0]['flag']=='4'){
			$listsql[0]['flag']='普通会员';
		}
		
		$listsql[0]['overtime'] = substr($listsql[0]['overtime'],0,10);
		$listsql[0]['buytime'] = substr($listsql[0]['buytime'],0,10);
		
		$this->assign('list',$listsql[0]);
		
		
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	//文件的上传
	Public function upload(){
	
		
		//接收该条订单的id
		$id=$this->_post('id');
		
		
		import('ORG.Net.UploadFile');
	
	
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt');// 设置附件上传类型
		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
	
		$upload->thumb = true;
		$upload->thumbMaxWidth  = '350';
		$upload->thumbMaxHeight = '350';
	
		if(!$upload->upload()) {                             // 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{                                              // 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		
		/* echo '<pre>';
		print_r($_POST);
		print_r($info);
		echo '</pre>'; */
		
		//保存表单数据 包括附件数据
		$Model = new Model(); // 实例化User对象
		$data=array();
		
	      
		for($i=0;$i<=5;$i++){
				
			if($info[$i]['key']=='file1'){//营业执照
				$lujing1=$info[$i]['savename'];
				$data['businesslicence'] = str_replace('./','/',$lujing1);
			}else if($info[$i]['key']=='file2'){//库存证明
				$lujing2=$info[$i]['savename'];
				$data['tihuodan'] =str_replace('./','/',$lujing2);
			}else if($info[$i]['key']=='file3'){//购货发票
				$lujing3=$info[$i]['savename'];
				$data['buybill'] = str_replace('./','/',$lujing3);
			}else if($info[$i]['key']=='file4'){//煤炭产地
				$lujing4=$info[$i]['savename'];
				$data['coalzhengming'] = str_replace('./','/',$lujing4);
			}else if($info[$i]['key']=='file5'){//质检报告
				$lujing5=$info[$i]['savename'];
				$data['qualityreporter'] = str_replace('./','/',$lujing5);
			}else if($info[$i]['key']=='file6'){//关单
				$lujing6=$info[$i]['savename'];
				$data['danzi'] = str_replace('./','/',$lujing6);
			}
			
		}
		$data['filecheck']='1'; //代表上传过文件
		
		$sql_where = '';
		if($id==''){
			$sql_where.="userphone='".session('user')."'";
		}else{
			$sql_where.="userphone='".session('user')."' and id='".$id."'";
		}
			
		//$list = $Model->table('zt_dingdan')->where("userphone='".session('user')."' and id='".$id."'")->save($data);
		$list = $Model->table('zt_dingdan')->where($sql_where)->save($data);
		
		//echo $Model->getLastsql();
		if($list){
			 echo "<script>alert('图片上传成功！'); window.location.href='".__APP__."/Login/order".$yuurl."';</script>";
			 $this->error('图片上传成功！');
		}else{
			echo "<script>alert('图片上传失败！'); history.go(-1);</script>";
			$this->error('图片上传失败！');
		}
	
	
	}
	
	
	//vget模拟发送函数
	public function vget( $url, $timeout=5000, $header=array(), $useragent='' ) {
	
		if( !function_exists('curl_init') ){
			return false;
		}
	
		if(substr($url,0,7)!='http://') {
			return 'url_error';
		}
	
		//对传递的header数组进行整理
		$headerArr = array();
		foreach( $header as $n => $v ) {
			$headerArr[] = $n.':'.$v;
		}
	
	
		$curl = curl_init(); // 启动一个CURL会话
	
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_NOBODY, 0); // 显示返回的body区域内容
	
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
	
		if(trim($useragent)!='') {
			//当传递useragent参数时，模拟用户使用的浏览器
			curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		}
	
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	
		curl_setopt($curl, CURLOPT_NOSIGNAL,1); //注意，毫秒超时一定要设置这个
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS,$timeout); //设置连接等待毫秒数
		curl_setopt($curl, CURLOPT_TIMEOUT_MS,$timeout); //设置超时毫秒数
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
		if(count($headerArr)>0) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);//设置HTTP头
		}
		$content  = curl_exec($curl); //返回结果
		$httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //页面状态码
		$run_time = (curl_getinfo($curl,CURLINFO_TOTAL_TIME)*1000); //所用毫秒数
		$errorno  = curl_errno($curl);
	
		//关闭curl
		curl_close($curl);
	
	
		//定义return数组变量
		$retarr = array();
		$retarr['content']  = $content;
		$retarr['httpcode'] = $httpcode;
		$retarr['run_time'] = $run_time;
		$retarr['errorno']  = $errorno;
	
		return $retarr;
	
	}
	
	
	
	public function isMobile()
	{
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		{
			return true;
		}
		// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA']))
		{
			// 找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT']))
		{
			$clientkeywords = array ('nokia',
					'sony',
					'ericsson',
					'mot',
					'samsung',
					'htc',
					'sgh',
					'lg',
					'sharp',
					'sie-',
					'philips',
					'panasonic',
					'alcatel',
					'lenovo',
					'iphone',
					'ipod',
					'blackberry',
					'meizu',
					'android',
					'netfront',
					'symbian',
					'ucweb',
					'windowsce',
					'palm',
					'operamini',
					'operamobi',
					'openwave',
					'nexusone',
					'cldc',
					'midp',
					'wap',
					'mobile'
			);
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				return true;
			}
		}
		// 协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT']))
		{
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
			{
				return true;
			}
		}
		return false;
	}
	
	
	
}