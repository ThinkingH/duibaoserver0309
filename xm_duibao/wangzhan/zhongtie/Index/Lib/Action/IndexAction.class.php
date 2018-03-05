<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	
	//首页
	public function index(){
		
		
		
		$Model = new Model();
		
		$comment = $this->_post('comment');
		$company = $this->_post('company');
		$lphone  = $this->_post('phone');
		$sub     = $this->_post('submit');
		
		
		if($sub!=''){
		
			if($company==''){
				echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
				$this->error('请填写公司名称！');
			}
		
			if($lphone==''){
				echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
				$this->error('请填写联系电话！');
			}
		
			if($comment==''){
				echo "<script>alert('请填写备注信息！'); history.go(-1);</script>";
				$this->error('请填写备注信息！');
			}
		
			$data = array(
					'comment' => $comment,
					'company' => $company,
					'phone'   => $lphone,
					'tijiao_time' =>date('Y-m-d:H:i:s',time()),
					'flag'       =>'10',                 //订单的状态
					'userphone'  =>session('user'),
					'filecheck'  =>'9',                 //文件审核的状态,当为9时表示还没上传文件
						
			);
		
			$id = $Model->table('zt_dingdan')->add($data);
				
		
			if($id){
				echo "<script>alert('发布成功！');window.location.href='".__APP__."/Index/index".$yuurl."';</script>";
				$this->success('发布成功！');
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this->error('发布失败！');
			}
		}
		
		
		/* $Model=new Model(); */
		//宏观
		$sql="SELECT id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 4,4";
		$list=$Model->query($sql);
		
		$this->assign('list',$list);
		
		//石油
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 5,4";
		$list=$Model->query($sql);
		
		$this->assign('oil',$list);
		
		//新能源
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 9,4";
		$list=$Model->query($sql);
		
		$this->assign('nengyuan',$list);
		
		//钢铁
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 14,4";
		$list=$Model->query($sql);
		
		$this->assign('steel',$list);
		
		//化工
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 18,5";
		$list=$Model->query($sql);
		
		$this->assign('chem',$list);
		
		//公司
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 22,5";
		$list=$Model->query($sql);
		$this->assign('com',$list);
		
		
		//煤炭列表
		//$sql="select * from caigouxinxi limit 0,7";
		//$sql = "select * from zt_dingdan where flag='40' order by id desc limit 0,7";
		$sql = "select * from zt_coaldata order by id desc limit 0,7";
		$list1=$Model->query($sql);
		
		$this->assign('clist',$list1);
		
		//新闻列表
		$sql="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 36,13";
		$list2=$Model->query($sql);
		
		$this->assign('nlist',$list2);
		
		
		$sql4="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 45,3";
		$list4=$Model->query($sql4);
		
		$this->assign('zoilq',$list4);
		
		$sql5="select id,title from zt_wx_news where typeid='2' or typeid='3' order by id desc limit 50,3";
		$list5=$Model->query($sql5);
		
		$this->assign('zoils',$list5);
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		
		//电脑版和手机版之间的切换
		$kk = $this->_get('type');
		if($kk!='kk') {
			if($this->isMobile()) {
				header("Location:/m_chinaresc");
			}
		}
		
		$this->display();
	}
	
	
	//煤炭详情
	public function coalxiangqing(){
		
		$Model=new Model();
		
		$id=$this->_get('id');
		
		
		$sql="select * from zt_coaldata where id='".$id."'";
		$list1=$Model->query($sql);
		
		$this->assign('xiangqing',$list1[0]);
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	//详情页
	public function xiangqing(){
		
		$Model=new Model();
		
		$id=$this->_get('id');
		
		
		$sql="select * from zt_coaldata where id='".$id."'";
		$list1=$Model->query($sql);
		
		$this->assign('xiangqing',$list1[0]);
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
	}
	
	//大宗财经
	public function blockfinance(){
		
		$Model=new Model();
		
		$sql="select id,name from xinwenzixun limit 20";
		$list=$Model->query($sql);
		
		$this->assign('list',$list);
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
	}
	
	
	
	//需求发布
	public function cocalcommerce(){
		
		$Model = new Model();
		$sqldata = "select phone,company from users where phone='".session('user')."'";
		$listdata = $Model->query($sqldata);
		
		$this->assign('list',$listdata[0]);
		
		
		//煤炭列表
		$sql="select * from zt_coaldata order by id desc limit 0,7";
		$list1=$Model->query($sql);
		
		$this->assign('clist',$list1);
		
		//现货行情
		$xianhuo = "select * from zt_xianhuo order by id desc limit 0,8";
		$xianhuosql = $Model->query($xianhuo);
		
		$this->assign('xlist',$xianhuosql);
		
		
		
		//行业动态
		$sql="select id,ttitle from zt_news limit 0,5";
		$listr=$Model->query($sql);
		
		$this->assign('rlist',$listr);
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));

		$this->display();
	}
	
	
	
	
	//需求发布的数据添加
	public function cocalcommercedata(){
		
		$Model = new Model();
		
		$comment = $this->_post('comment');
		$company = $this->_post('company');
		$lphone   = $this->_post('phone');
		$sub     = $this->_post('submit');
		
		
		if($sub!=''){
		
			 if($company==''){
				echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
				$this->error('请填写公司名称！');
			} 
		
			if($lphone==''){
				echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
				$this->error('请填写联系电话！');
			} 
		
			if($comment==''){
				echo "<script>alert('请填写备注信息！'); history.go(-1);</script>";
				$this->error('请填写备注信息！');
			}
		
			$data = array(
					'comment' => $comment,
					'company' => $company,
					'phone'   => $lphone,
					'tijiao_time' =>date('Y-m-d:H:i:s',time()),
					'flag'       =>'10',                 //订单的状态
					'userphone'  =>session('user'),
					'filecheck'  =>'0',                 //文件审核的状态,当为0时表示还没上传文件
					
			);
		
			$id = $Model->table('zt_dingdan')->add($data);
			
		
			if($id){
				echo "<script>alert('发布成功！');window.location.href='".__APP__."/Index/index".$yuurl."';</script>";
				$this->success('发布成功！');
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this->error('发布失败！');
			}
		}
		
		
		
		$this->display();
	}
	
	
	
	
	//产业链金融
	public function chainfinance(){
		
		
		//用户账户
		$this->assign('username',session('user'));
		$this->assign('fflag',session('hyflag'));
		
		$this->display();
		
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