<?php
/*
 * 商品类型
 */

class ShoptypeAction extends Action{
	
	private $lock_index         = '9751';
	private $lock_updateshow    = '975';
	private $lock_addshow       = '975';
	private $lock_adddata       = '975';
	private $lock_deletedata    = '97';
	private $lock_madddata      = '975';
	private $lock_maintype      = '975';
	
	public function index() {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);

		//接收用户选择的查询参数
		$xushi   = $this->_get('xushi');
		$type    = $this->_get('type');
		$name    = $this->_get('name');
	
		$this-> assign('name',$name);
	
		$optionxushi = '<option value=""></option>';
		$optionxushi .= '<option value="1" ';
		if($xushi==1) { $optionxushi .= ' selected="selected" '; }
		$optionxushi .= '>1-虚拟商品</option>';
		$optionxushi .= '<option value="2" ';
		if($xushi==9) { $optionxushi .= ' selected="selected" '; }
		$optionxushi .= '>2-实物商品</option>';
		$this -> assign('optionxushi',$optionxushi);
		
		
		$Model = new Model();
		
		//商品的类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		foreach ($list_type as $keyc=>$valc){
			
			$typearr[$list_type[$keyc]['typeid']] = $list_type[$keyc]['typeid'].'--'.$list_type[$keyc]['name'];
			
		}
		
		
		$optiontype = '<option value=""></option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			if($val['typeid']==$type) {
				$optiontype .= ' selected="selected" ';
			}
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
	
	
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		if($type!='') {
			$sql_where .= " type='".$type."' and ";
		}
		
		if($xushi!='') {
			$sql_where .= " xushi='".$xushi."' and ";
		}
		if($name!='') {
			$sql_where .= " name like'".$name."%' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
	
		//生成排序字符串数据
		$sql_order = " id asc ";
	
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_type')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_type')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
	
		foreach($list as $keyc => $valc) {
			
			$list[$keyc]['type'] = $typearr[$list[$keyc]['type']];//商品类型
				
				
			if($list[$keyc]['xushi']=='1') {
				$list[$keyc]['xushi'] = '虚拟';
			}else if($list[$keyc]['xushi']=='2') {
				$list[$keyc]['xushi'] = '实物';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
			
		}
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	public function maintype() {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_maintype);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);

		//接收用户选择的查询参数
		$xushi   = $this->_get('xushi');
		$type    = $this->_get('type');
		$name    = $this->_get('name');
	
		$this-> assign('name',$name);
	
		$optionxushi = '<option value=""></option>';
		$optionxushi .= '<option value="1" ';
		if($xushi==1) { $optionxushi .= ' selected="selected" '; }
		$optionxushi .= '>1-虚拟商品</option>';
		$optionxushi .= '<option value="2" ';
		if($xushi==9) { $optionxushi .= ' selected="selected" '; }
		$optionxushi .= '>2-实物商品</option>';
		$this -> assign('optionxushi',$optionxushi);
		
		
		$Model = new Model();
		
		//商品的类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		foreach ($list_type as $keyc=>$valc){
			
			$typearr[$list_type[$keyc]['typeid']] = $list_type[$keyc]['typeid'].'--'.$list_type[$keyc]['name'];
			
		}
		
		
		$optiontype = '<option value=""></option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			if($val['typeid']==$type) {
				$optiontype .= ' selected="selected" ';
			}
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
	
	
		//-----------------------------------------------------------
	
		//生成排序字符串数据
		$sql_order = " typeid asc ";
	
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_config')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_config')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		foreach ($list as $keys=>$vals){
			
			if($list[$keys]['flag']=='1'){
				$list[$keys]['flag']='开启';
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flag']='关闭';
			}
			
		}
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//数据的添加
	public function madddata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_madddata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的采纳数
		$flag      = $this->_post('flag');
		$typeid    = $this->_post('typeid');
		$name      = $this->_post('name');
		$picurl    = $this->_post('picurl');
		$add_submit = $this->_post('add_submit');
		
		if($add_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
				
		}else{
				
			if($typeid==''){
				echo "<script>alert('商品类型编号不能为空！');history.go(-1);</script>";
				$this -> error('商品类型编号不能为空');
		
			}
				
			if($name==''){
				echo "<script>alert('类型名称不能为空！');history.go(-1);</script>";
				$this -> error('类型名称不能为空！');
		
			}
				
			//图片的上传
			import('ORG.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			$upload->savePath =  './Public/Uploads/lunbotu/'.date('Y-m').'/';// 设置附件上传目录
			
			$upload->thumb = false;
			$upload->thumbMaxHeight = '300';
			
			//判断该目录是否存在
			if(!is_dir($upload->savePath)){
				mkdir($upload->savePath,0777);
			}
			
			if(!$upload->upload()) {                             // 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{                                              // 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			}
			//轮播图的链接
			$picurl = URL_APK.str_replace('./','/',$info[0]['savepath'].$info[0]['savename']);
			
			
			$data=array();
			$data['flag']      =$flag;
			$data['typeid']    = $typeid;
			$data['name']      = $name;
			$data['picurl']    = $picurl;
				
			$Model = new Model();
				
			//判断该类型是否重复
			$repeatsql = "select typeid from shop_config where typeid='".$typeid."' and name='".$name."'";
			$repeatlist = $Model->query($repeatsql);
				
			if(count($repeatlist)>0){
				echo "<script>alert('该类型已存在，请重新添加！');history.go(-1);</script>";
				$this -> error('该类型已存在，请重新添加！');
		
			}else{
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shop_config') -> add($data);
					
				if($ret) {
					echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Shoptype/maintype".$yuurl."';</script>";
					$this -> success('数据添加成功!','__APP__/Shoptype/maintype'.$yuurl);
				}else {
					echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据添加失败，系统错误!');
				}
		
			}
				
				
		}
		
	}
	
	
	
	
	
	
	
	
	
	//修改页面
	public function updateshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取参数
		$id = $this->_post('id');
		$update = $this->_post('update_submit');
		
		$Model = new Model();
		
		if($update==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
			
		}else {
			if($id==''){
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
				
			}
			
			$shoptypesql  = "select * from shop_type where id='".$id."'";
			$shoptypelist = $Model->query($shoptypesql);
			
			
			$this -> assign('list',$shoptypelist);
			
			// 输出模板
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
			
		}
		
	}
	
	

	//数据的删除呢
	public function deletedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
			
		$id       = $this->_post('id');
		$type     = $this->_post('type');
		$delete_submit  = $this->_post('delete_submit');
			
		if($delete_submit!=''){
				
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}
			
			
			//数据库初始化
			$Model = new Model();
			
			if($type=='1'){
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shop_type') -> where("id='".$id."'") -> delete();
				
				if($ret) {
					echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Shoptype/index".$yuurl."';</script>";
					$this -> success('数据删除成功!','__APP__/Shoptype/index'.$yuurl);
				}else {
					echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据删除失败，系统错误!');
				}
				
			}else if($type=='2'){
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shop_config') -> where("typeid='".$id."'") -> delete();
				
				if($ret) {
					echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Shoptype/maintype".$yuurl."';</script>";
					$this -> success('数据删除成功!','__APP__/Shoptype/maintype'.$yuurl);
				}else {
					echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据删除失败，系统错误!');
				}
			}
			
		}
	}
	
	
	//数据的添加
	public function addshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$Model = new Model();
		
		//商品的类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		$optiontype = '<option value=""></option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			if($val['typeid']==$type) {
				$optiontype .= ' selected="selected" ';
			}
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		
		// 输出模板
		$this->display();
			
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//商品类型的添加
	public function adddata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_adddata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的采纳数
		$xushi = $this->_post('xushi');
		$type  = $this->_post('type');
		$childtype = $this->_post('childtype');
		$name      = $this->_post('name');
		$remark    = $this->_post('remark');
		$add_submit = $this->_post('add_submit');
		
		if($add_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
			
		}else{
			if($xushi==''){
				echo "<script>alert('商品分类不能为空！');history.go(-1);</script>";
				$this -> error('商品分类不能为空！');
				
			}
			
			if($type==''){
				echo "<script>alert('商品类型不能为空！');history.go(-1);</script>";
				$this -> error('商品类型不能为空');
				
			}
			
			if($childtype==''){
				echo "<script>alert('商品子类型不能为空！');history.go(-1);</script>";
				$this -> error('商品子类型不能为空！');
				
			}
			
			if($name==''){
				echo "<script>alert('商品名称不能为空！');history.go(-1);</script>";
				$this -> error('商品名称不能为空！');
				
			}
			
			$data=array();
			$data['xushi']    =$xushi;
			$data['type']     = $type;
			$data['childtype'] = $childtype;
			$data['name']      = $name;
			$data['remark']    = $remark;
			
			$Model = new Model();
			
			//判断该类型是否重复
			$repeatsql = "select id from shop_type where childtype='".$childtype."'";
			$repeatlist = $Model->query($repeatsql);
			
			if(count($repeatlist)>0){
				echo "<script>alert('商品子类型已存在，请重新添加！');history.go(-1);</script>";
				$this -> error('商品子类型已存在，请重新添加！');
				
			}else{
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shop_type') -> add($data);
					
				if($ret) {
					echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Shoptype/index".$yuurl."';</script>";
					$this -> success('数据添加成功!','__APP__/Shoptype/index'.$yuurl);
				}else {
					echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据添加失败，系统错误!');
				}
				
			}
			
			
		}
		
		
		
		
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