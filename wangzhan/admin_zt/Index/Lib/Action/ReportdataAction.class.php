<?php


class ReportdataAction extends Action {
	//统计回传数据
	
	
	//定义各模块锁定级别
	private $lock_index         = 'nolock';
	private $lock_datashow      = 'nolock';
	private $lock_chushendata   = 'nolock';
	private $lock_deldata       = 'nolock';
	private $lock_fushendata    = 'nolock';
	private $lock_fushenshow    = 'nolock';
	private $lock_fabudata      = 'nolock';
	private $lock_zuofeidata    = 'nolock';
	private $lock_lastshow      = 'nolock';
	private $lock_editshow      = 'nolock';
	private $lock_editdata      = 'nolock';
	private $lock_chushenshow   = 'nolock';
	private $lock_fabuclick     = 'nolock';
	private $lock_chexiaofabu   = 'nolock';
	private $lock_chakan        = 'nonlock';
	private $lock_senddata      = 'nonlock';
	private $lock_zuofeichakan  = 'nonlock';
	private $lock_xiugaichakan  = 'nonlock';
	private $lock_xiugaishow    = 'nonlock';
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		$username = session('username');
		$rootflag  = session('rootflag');
		
		if($username=='') {
			$username = 888;
		}
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//接收用户选择的查询参数
		$date_s  = $this->_get('date_s');
		$date_e  = $this->_get('date_e');
		$flag    = $this->_get('flag');
		$fl=substr($this->_get('flag'),0,2);
		
		//账单类型
		$typearr = array(
				'10'=> '10-等待初审',
				'20'=> '20-已初审',
				'30'=> '30-已复审',
				'40'=> '40-已终审',
				'50'=> '50-已发布',
				'99'=> '99-以作废',
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
		
		
		$Model = new Model();
		
		//---------------------------------------------------------
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-07-01');
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		
		$date_s = date('Y-m-d',strtotime($date_s));
		$date_e = date('Y-m-d',strtotime($date_e));
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
			
		//************************************************************************************
		//拼接生成where字符串
		 $sql_where = '';
		 if($rootflag!=''&&$rootflag=='9'){
		 	if($flag!='')         { $sql_where .= "flag='".$fl."' and "; }
		 	$sql_where .= "flag in (10,20,30,40) and tijiao_time >='".$date_s." 00:00:00' and tijiao_time<='".$date_e." 23:59:59 ' ";
		 	
		 }else if($rootflag!=''&&$rootflag=='7'){//用户的权限为7，可以进行数据的终审查询
		 	$sql_where .= "tijiao_time >='".$date_s." 00:00:00' 
		 			and tijiao_time<='".$date_e." 23:59:59 ' and flag not in (10,20,99)";
		 }else if($rootflag!=''&&$rootflag=='5'){//用户的权限为5，可以进行数据的复审和修改审查询
		 	$sql_where .= "flag='20' and tijiao_time >='".$date_s." 00:00:00' 
		 					and tijiao_time<='".$date_e." 23:59:59 ' ";
		 }else if($rootflag!=''&&$rootflag=='1'){//用户的权限为1，可以进行数据的初审和删除查询
		 	$sql_where .= "flag='10' and tijiao_time >='".$date_s." 00:00:00' 
		 			and tijiao_time<='".$date_e." 23:59:59 ' ";
		 }
		
		
		$sql_order = "id desc";
			
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,100);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
			
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
			
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		 foreach ($list as $key => $val){
			
			
		if($list[$key]['flag']=='10'){
			$list[$key]['flags'] = '未初审';
		}else if($list[$key]['flag']=='20'){
			$list[$key]['flags'] = '已初审';
		}else if($list[$key]['flag']=='30'){
			$list[$key]['flags'] = '已复审';
		}else if($list[$key]['flag']=='40'){
			$list[$key]['flags'] = '已终审';
		}else if($list[$key]['flag']=='99'){
			$list[$key]['flags'] = '已删除';
		}else if($list[$key]['flag']=='50'){
			$list[$key]['flags'] = '已发布';
		}
		} 
		$this -> assign('list',$list);
			
		//-----------------------------------------------------------------
		
		// 输出模板
		$this->display();
	}
	
	
	//修改数据的查看
	public function xiugaichakan(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_xiugaichakan);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id            = $this->_post('id');
		$chakan = $this->_post('chakan');
		
		$Model = new Model();
		
		if($chakan!=''){
		
			//更新flag状态标识
			$sql_update  = "select id,userphone,flag,shangjiatype,company,phone,name,shuliang,price,rezhi,liufen,meitype,address,sendtype,comment,tijiao_time,createtime from zt_dingdan where id ='".$id."'";
			$list_update = $Model->query($sql_update);
			
			if($list_update[0]['flag']=='10'){
				$list_update[0]['flag']='未初审';
			}
				
				
			$this->assign('list',$list_update[0]);
		}
		
		$this->display();
		
	}
	
	//数据的修改
	public function xiugaishow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_xiugaishow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		$username = session('username');
		$rootflag  = session('rootflag');
		
		if($username=='') {
			$username = 888;
		}
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//接收用户选择的查询参数
		$date_s  = $this->_get('date_s');
		$date_e  = $this->_get('date_e');
		
		
		$Model = new Model();
		
		//---------------------------------------------------------
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-07-01');
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		
		$date_s = date('Y-m-d',strtotime($date_s));
		$date_e = date('Y-m-d',strtotime($date_e));
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
		
		//拼接生成where字符串
		$sql_where = '';
		$sql_where .= "flag = '10' and tijiao_time >='".$date_s." 00:00:00' and tijiao_time<='".$date_e." 23:59:59 ' ";
		
		
		$sql_order = "id desc";
			
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,100);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
			
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
			
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		foreach ($list as $key => $val){
				
				
			if($list[$key]['flag']=='10'){
				$list[$key]['flags'] = '未初审';
			}
		}
		
		
		$this -> assign('list',$list);
			
		//-----------------------------------------------------------------
		
		// 输出模板
		$this->display();
		
	}
	
	
	
	//作废数据的查询
	public function zuofeichakan(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_zuofeichakan);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$date_s  = $this->_get('date_s');
		$date_e  = $this->_get('date_e');
		
		
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-07-01');
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		
		$date_s = date('Y-m-d',strtotime($date_s));
		$date_e = date('Y-m-d',strtotime($date_e));
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
		
		$Model = new Model();
		
		
		//拼接生成where字符串
		$sql_where = '';
		$sql_where .= "flag='99' and createtime >='".$date_s." 00:00:00' and createtime<='".$date_e." 23:59:59 ' ";
		
		
		$sql_order = "id desc";
			
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,100);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
			
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
			
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		
		
		$this -> assign('list',$list);
			
		//-----------------------------------------------------------------
		
		// 输出模板
		$this->display();
		
	}
	
	
	
	//撤销发布
	public function chexiaofabu(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chexiaofabu);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id      = $this->_post('id');
		$chexiao = $this->_post('chexiao');
		
		
		if($chexiao!=''){
				
			$Model = new Model();
			
			//更新flag状态标识
			$sql_update  = "update zt_dingdan set flag='10' where id='".$id."'";
			$list_update = $Model->execute($sql_update);
				
			if($list_update) {
				echo "<script>alert('撤销发布成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this -> success('撤销发布成功!','__APP__/Basicsite/index'.$yuurl);
			}else {
				echo "<script>alert('撤销发布失败，系统错误!');history.go(-1);</script>";
				$this -> error('撤销发布失败，系统错误!');
			}
				
		}
		
	}
	
	
	
	
	
	//查看页面
	public function datashow(){
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_datashow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		$id     = $this->_post('id');
		$chakan = $this->_post('chakan');
		
		$Model = new Model();
		$sql_showdata = "select * from zt_dingdan where id='".$id."'";
		$list_showdata = $Model->query($sql_showdata);
		
		$this->assign('list',$list_showdata[0]);
		
		
		
		$this->display();
	}
	
	
	//初审数据的展示
	public function chushenshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chushenshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		$id          = $this->_post('id');
		$first_check = $this->_post('first_check');
	
		
		$Model = new Model();
	
		if($first_check!=''){
				
			$Model = new Model();
			$sql_showdata = "select * from zt_dingdan where id='".$id."'";
			$list_showdata = $Model->query($sql_showdata);
			
			$this->assign('list',$list_showdata[0]);
				
		}
		$this->display();
	
	}
	
	
	
	//数据的初审
	public function chushendata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chushendata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		
		
		$Model = new Model();
		
		if($update_submit!=''){
			
			//更新flag状态标识
			$data = array();
			$data['flag']       = '20';
			/* $data['filecheck']  = '2';  //初审核 */
			
				
			
			$ret = $Model->table('zt_dingdan')->where("id='".$id."'")->save($data);
			
			if($ret) {
				echo "<script>alert('数据初审成功，请进行复审！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this ->success('数据初审成功，请进行复审!','__APP__/Reportdata/index'.$yuurl);
			}else {
				echo "<script>alert('数据初审失败!');history.go(-1);</script>";
				$this -> error('数据初审失败!');
			}
			
		}
	}
	
	
	
	//数据的删除
	public function deldata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deldata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id  = $this->_post('id');
		$del = $this->_post('del');
		
		if($del!=''){
			
			$Model = new Model();
			$ret = $Model -> table('zt_dingdan') -> where("id='".$id."'") -> delete();
			
				if($ret) {
						echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
						$this -> success('数据删除成功!','__APP__/Basicsite/index'.$yuurl);
					}else {
						echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
						$this -> error('数据删除失败，系统错误!');
					}
			
		}
		
	}
	
	
	
	//数据的复审
	public function fushenshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_fushenshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id  = $this->_post('id');
		//$path='http://127.0.0.1:8001/newzhongtie/Public/Uploads/';
		$path='http://www.chinaresc.com/Public/Uploads/';
		
		//缩略图的前缀
		//$qianzuni = 'thumb_';
		//$Http->download(MYUPLOAD.'/Public/Uploads/'.$url);// 实例文件下载
		//$path=MYUPLOAD.'Public/Uploads/';
		
		$Model = new Model();
		
		$sql_where = "id='".$id."'";
		
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,50);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		 foreach($list as $key=>$val){
		 	
			$list[$key]['businesslicence0']=$path.$list[$key]['businesslicence'];
			$list[$key]['danzi0']          =$path.$list[$key]['danzi'];
			$list[$key]['tihuodan0']          =$path.$list[$key]['tihuodan'];
			$list[$key]['buybill0']        =$path.$list[$key]['buybill'];
			$list[$key]['coalzhengming0']  =$path.$list[$key]['coalzhengming'];
			$list[$key]['qualityreporter0']=$path.$list[$key]['qualityreporter'];
			
		} 
		/* echo '<pre>';
		print_r($list);
		echo '</pre>'; */
		
		$this->assign('list',$list[0]);
		
		$this->display();
	}
	
	
	//复审确认
	public function fushendata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_fushendata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		$id          = $this->_post('id');
		$submit      = $this->_post('update_submit');
	
	
		$Model = new Model();
	
		if($submit!=''){
				
			$data = array();
			$data['flag']       = '30';
			/* $data['filecheck']  = '3'; */
			
			
				
			$ret = $Model->table('zt_dingdan')->where("id='".$id."'")->save($data);
				
			if($ret) {
				echo "<script>alert('复审成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this ->success('复审成功!','__APP__/Reportdata/index'.$yuurl);
			}else {
				echo "<script>alert('复审失败!');history.go(-1);</script>";
				$this -> error('复审失败!');
			}
				
		}
	}
	
	
	
	
	//数据的修改
	public function editshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		$id  = $this->_post('id');
	
		$Model = new Model();
	
		$sql_showdata = "select * from zt_dingdan where id='".$id."'";
		$list_showdata = $Model->query($sql_showdata);
	
		$this->assign('list',$list_showdata[0]);
	
	
		$this->display();
	}
	
	
	
	//复审数据的插入
	public function editdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id          = $this->_post('id');
		$submit      = $this->_post('update_submit');
		
		//接收的参数
		$shangjiatype = $this->_post('shangjiatype');
		$name         = $this->_post('name'); 
		$price        = $this->_post('price');
		$shuliang     = $this->_post('shuliang');
		$address      = $this->_post('address');
		$sendtype     = $this->_post('sendtype');
		$comment      = $this->_post('comment');
		$phone        = $this->_post('phone');
		$company      = $this->_post('company');
		$rezhi        = $this->_post('rezhi');
		$liufen       = $this->_post('liufen');
		$meitype       = $this->_post('meitype');
		
		$Model = new Model();
		
		if($submit!=''){
					
			$data = array();
			$data['shangjiatype']  = $shangjiatype;
			$data['name']      = $name;
			$data['price']      = $price;
			$data['shuliang']   = $shuliang;
			$data['address']    = $address;
			$data['sendtype']   = $sendtype;
			$data['comment']    = $comment;
			$data['phone']      = $phone;
			$data['company']    = $company;
			$data['rezhi']     = $rezhi;
			$data['liufen']     = $liufen;
			$data['meitype']    = $meitype;
			
			$ret = $Model->table('zt_dingdan')->where("id='".$id."'")->save($data);
			
		 	if($ret) {
				echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Reportdata/xiugaishow".$yuurl."';</script>";
				$this ->success('数据修改成功!','__APP__/Reportdata/index'.$yuurl);
			}else {
				echo "<script>alert('数据修改失败!');history.go(-1);</script>";
				$this -> error('数据修改失败!');
			} 
		
		}
	}
	
	
	
	 public function picfile_download() {
	 	
	 	$id=$this->_post('id');
	 	$url=$this->_post('lurl');
		
		import('ORG.Http');// 导入分页类
		
		$Http = new Http();
		
		//$Http->download(MYUPLOAD.'zhongtie/Public/Uploads/'.$url);// 实例文件下载
		$Http->download(MYUPLOAD.'Public/Uploads/'.$url);// 实例文件下载
		/* $path='http://www.chinaresc.com/Public/Uploads/';
		$Http->download($path.$url);// 实例文件下载 */
		
	}
	 
 
	
	
	
	
	//数据的发布展示页
	public function lastshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_lastshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id  = $this->_post('id');
		//$path='http://127.0.0.1:8001/newzhongtie/Public/Uploads/';
		$path='http://www.chinaresc.com/Public/Uploads/';
		
		//缩略图的前缀
		//$qianzuni = 'thumb_';
		//$Http->download(MYUPLOAD.'/Public/Uploads/'.$url);// 实例文件下载
		//$path=MYUPLOAD.'Public/Uploads/';
		
		$Model = new Model();
		
		$sql_where = "id='".$id."'";
		
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,50);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		foreach($list as $key=>$val){
		
			$list[$key]['businesslicence0']=$path.$list[$key]['businesslicence'];
			$list[$key]['danzi0']          =$path.$list[$key]['danzi'];
			$list[$key]['tihuodan0']          =$path.$list[$key]['tihuodan'];
			$list[$key]['buybill0']        =$path.$list[$key]['buybill'];
			$list[$key]['coalzhengming0']  =$path.$list[$key]['coalzhengming'];
			$list[$key]['qualityreporter0']=$path.$list[$key]['qualityreporter'];
				
		}
		
		$this->assign('list',$list[0]);
		
		/* $id  = $this->_post('id');
		//$path='http://127.0.0.1:8001/newzhongtie/Public/Uploads/';
		
		//前缀
		//$qianzhui = 'thumb_';
		//$path='http://127.0.0.1:8001/newzhongtie/Public/Uploads/';
		//$path=MYUPLOAD.'Public/Uploads/';
		$path='http://www.chinaresc.com/Public/Uploads/';
		
		$Model = new Model();
		
		$sql_where = "id='".$id."'";
		
		import('ORG.Page');                         // 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();             // 查询满足要求的总记录数
		$Page = new Page($count,50);          // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();                // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
	//print_r($list);
		
		foreach($list as $key=>$val){
		
			$list[$key]['businesslicence0']   =$path.$list[$key]['businesslicence'];
			$list[$key]['tihuodan0']          =$path.$list[$key]['tihuodan'];
			$list[$key]['danzi0']          =$path.$list[$key]['danzi'];
			$list[$key]['buybill0']        =$path.$list[$key]['buybill'];
			$list[$key]['coalzhengming0']  =$path.$list[$key]['coalzhengming'];
			$list[$key]['qualityreporter0']=$path.$list[$key]['qualityreporter'];
			$list[$key]['tihuodan0']       =$path.$list[$key]['tihuodan'];
			
			 if($list[$key]['flag']=='30'){
				$list[$key]['flag']='30-已复审';
			}
				
		}
		/* echo '<pre>';
		 print_r($list);
		 echo '</pre>'; */ 
		
		$this->assign('list',$list[0]);
		
		
		$this->display();
		
	}
	
	
	
	//数据的发布
	public function fabudata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_fabudata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id          = $this->_post('id');
		
		$update_submit      = $this->_post('update_submit');
		
		$Model = new Model();
		
		if($update_submit!=''){
				
			$data = array();
			$data['flag']      = '40';
			$data['createtime']   = date('Y-m-d H:i:s',time());
				
			$ret = $Model->table('zt_dingdan')->where("id='".$id."'")->save($data);
				
			if($ret) {
				echo "<script>alert('终审成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this ->success('终审成功！','__APP__/Reportdata/index'.$yuurl);
			}else {
				echo "<script>alert('终审失败!');history.go(-1);</script>";
				$this -> error('终审失败!');
			}
		}
		
	}
	
	
	//作废的数据
	public function zuofeidata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_zuofeidata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id           = $this->_post('id');
		$zuofei_check = $this->_post('zuofei');
		
		$Model = new Model();
		
		if($zuofei_check!=''){
				
			//更新flag状态标识
			$sql_update  = "update zt_dingdan set flag='99' where id='".$id."'";
			$list_update = $Model->execute($sql_update);
				
			if($list_update){
		
				echo "<script>alert('数据作废成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this -> success('数据作废成功！');
			}else{
				echo "<script>alert('数据作废失败！');history.go(-1);</script>";
				$this -> error('数据作废失败！');
			}
		}
	}
	
	
	//已发布数据的查询
	public function senddata() {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_senddata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//对时间参数进行处理
		if($date_s=='') {
			$date_s = date('Y-m-d');
		}
		if($date_e=='') {
			$date_e = date('Y-m-d');
		}
		
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
		$sql_order = "id desc";
		
		$sql_where = "flag='40' and createtime >='".$date_s." 00:00:00' and createtime<='".$date_e." 23:59:59' ";
		
		$Model = new Model();
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> count();     // 查询满足要求的总记录数
		$Page = new Page($count,20);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();         // 分页显示输出
		
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('zt_dingdan')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		foreach ($list as $key=>$val){
			
			if($list[$key]['flag']=='40'){
				$list[$key]['flag']='已发布';
			}
		}
		$this->assign('list',$list);
	
	
		// 输出模板
		$this->display();
	
	}
	
	
	//文件的发布
	public function fabuclick(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_fabuclick);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id    = $this->_post('id');
		$fabu  = $this->_post('fabu');
		
		$Model = new Model();
		
		if($fabu!=''){
			
			$data = array();
			$data['flag']      = '50';
			
			
			$ret = $Model->table('zt_dingdan')->where("id='".$id."'")->save($data);
			
			if($ret) {
				echo "<script>alert('发布成功！');window.location.href='".__APP__."/Reportdata/index".$yuurl."';</script>";
				$this ->success('发布成功！','__APP__/Reportdata/index'.$yuurl);
			}else {
				echo "<script>alert('发布失败!');history.go(-1);</script>";
				$this -> error('发布失败!');
			}
		}
	}
	
	
	
	
	
	//文件的查看
	public function chakan(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chakan);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id            = $this->_post('id');
		$chakan = $this->_post('chakan');
		
		$Model = new Model();
		
		if($chakan!=''){
		
			//更新flag状态标识
			$sql_update  = "select id,userphone,flag,shangjiatype,company,phone,name,shuliang,price,rezhi,liufen,meitype,address,sendtype,comment,tijiao_time,createtime from zt_dingdan where id ='".$id."'";
			$list_update = $Model->query($sql_update);
			
			foreach ($list_update as $key => $val){
				
				if($list_update[$key]['flag']=='10'){
					$list_update[$key]['flag']='未初审';
				}else if($list_update[$key]['flag']=='20'){
					$list_update[$key]['flag']='已初审';
				}else if($list_update[$key]['flag']=='30'){
					$list_update[$key]['flag']='已复审';
				}else if($list_update[$key]['flag']=='40'){
					$list_update[$key]['flag']='已终审';
				}
			}
			
			
			
			$this->assign('list',$list_update[0]);
		}
		
		$this->display();
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