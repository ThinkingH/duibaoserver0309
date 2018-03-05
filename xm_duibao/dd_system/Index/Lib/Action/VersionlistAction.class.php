<?php
/*
 * 版本信息的更新
 */

class VersionlistAction extends Action{
	
	
	private $lock_index              = '9751';
	private $lock_addversioninfo     = '975';
	private $lock_addversiondata     = '975';
	private $lock_deleteverdata      = '97';
	private $lock_updatevershow      = '975';
	private $lock_updateverdata      = '975';
	private $lock_updateconfigshow      = '975';
	private $lock_configshow            = '975';
	private $lock_updateconfigshowdata  = '975';
	
	
	
	//版本更新的展示页面
	public function index(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		
		//是否启用
		$flag_arr = array(
				'1' => '启用',
				'9' => '关闭',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		
		
		$phonetype_arr = array(
				'1' => '安卓',
				'2' => 'ios',
		);
		$optionphonetype = '<option value=""></option>';
		foreach($phonetype_arr as $keyc => $valc) {
			$optionphonetype .= '<option value="'.$keyc.'" ';
			if($phonetype==$keyc) { $optionphonetype .= ' selected="selected" '; }
			$optionphonetype .= '>'.$valc.'</option>';
		}
		
		$this->assign('optionflag',$optionflag);
		$this->assign('optionphonetype',$optionphonetype);
		
		
		$Model = new Model();
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_versioninfo')
						-> count();// 查询满足要求的总记录数
		
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_versioninfo')
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		foreach($list as $keyc => $valc) {
			
			if($list[$keyc]['flag']=='1'){
				$list[$keyc]['flag']='启用';
			}else if($list[$keyc]['flag']=='9'){
				$list[$keyc]['flag']='关闭';
			}
			
			if($list[$keyc]['systemtype']=='1'){
				$list[$keyc]['systemtype']='安卓';
			}else if($list[$keyc]['systemtype']=='2'){
				$list[$keyc]['systemtype']='ios';
			}
			
			
			if($list[$keyc]['uptype']=='1'){
				$list[$keyc]['uptype']='强制升级';
			}else if($list[$keyc]['uptype']=='2'){
				$list[$keyc]['uptype']='非强制升级';
			}
			
			/* $list[$keyc]['apk_url'] = str_replace('./','/',$list[$keyc]['apk_url']);
			$list[$keyc]['apk_url']   = URL_APK.$list[$keyc]['apk_url']; */
			
		}
		
		//参数设置
		$clist  = $Model -> table('xb_config')-> where('flag=1')-> select();
		
		$this -> assign('list',$list);
		
		$this -> assign('clist',$clist);
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//版本的条件
	public function addversioninfo(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addversioninfo);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	//版本信息的添加
	public function addversiondata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addversiondata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//获取相应的参数
		 $add_submit  = $this->_post('add_submit');
		 $flag        = $this->_post('flag');
		 $systemtype  = $this->_post('systemtype');
		 $versioncode = $this->_post('versioncode');
		 $updescription = $this->_post('updescription');
		 $uptype        = $this->_post('uptype');
		 
		 
		
		 if($add_submit!=''){
		 	
		 	
			 if($versioncode==''){
				 echo "<script>alert('版本号不能为空');history.go(-1);</script>";
				 $this->error('版本号不能为空！');
			 }else if(!is_numeric($versioncode)){
				 echo "<script>alert('版本号必须为数字！');history.go(-1);</script>";
				 $this->error('版本号必须为数字！');
			
			 }
			
		 
			 import('ORG.UploadFile');
			 $upload = new UploadFile();// 实例化上传类
			 $upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			 $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			 $upload->savePath =  './Public/Uploads/apk/'.date('Y-m').'/';// 设置附件上传目录
			 
			 $upload->thumb = false;
			 $upload->thumbMaxHeight = '300';
			 
			 //判断该目录是否存在
			 if(!is_dir($upload->savePath)){
			 	mkdir($upload->savePath,0777,true);
			 }
			 
			 if(!$upload->upload()) {                             // 上传错误提示错误信息
			 	$this->error($upload->getErrorMsg());
			 }else{                                              // 上传成功 获取上传文件信息
			 	$info =  $upload->getUploadFileInfo();
			 }
			 
			// print_r($info);exit;
			 $url =$info[0]['savepath'].$info[0]['savename'];
		 	 $apkurl = URL_APK.str_replace('./','/',$url);
		 
			 $Model = new Model();
			 	
			 $data=array();
			 	
			 $data['flag']            = $flag;
			 $data['systemtype']      = $systemtype;
			 $data['versioncode']     = $versioncode;
			 $data['apk_url']          = $apkurl;
			 $data['updescription']   = $updescription;
			 $data['uptype']          = $uptype;
			 $data['up_createtime']   = date('Y-m-d');
			 	
			 	
			 $imagedata_sql = $Model->table('xb_versioninfo')->add($data);
			 
			 /* echo $Model->getLastsql();
			 	exit; */
			 if($imagedata_sql){
				 echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Versionlist/index".$yuurl."';</script>";
				 $this ->success('数据添加成功!','__APP__/Versionlist/index'.$yuurl);
			 }else{
				 echo "<script>alert('数据添加失败！'); history.go(-1);</script>";
				 $this->error('数据添加失败！');
			 }
		 }
		
		
	}
	
	
	//删除操作
	public function deleteverdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deleteverdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取参数
		$id = $this->_post('id');
		$delete_submit = $this->_post('delete_submit');
		
		if($delete_submit!=''){
			
			if($id==''){
				
				echo "<script>alert('非法操作！'); history.go(-1);</script>";
				$this->error('非法操作！');
			}
			
			
			//数据库初始化
			$Model = new Model();
			
			//对应安装包的删除
			$seldata_sql  = "select apk_url from xb_versioninfo where id='".$id."'";
			$seldata_list = $Model->query($seldata_sql);
			
			$filepath=$seldata_list[0]['apk_url'];
			
			if(file_exists($filepath)){
				unlink($filepath);
			}
			
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('xb_versioninfo') -> where("id='".$id."'") -> delete();
			
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Versionlist/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Versionlist/index'.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
			}
			
		}
		
	}
	
	
	//版本信息的修改
	public function updatevershow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatevershow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		
		$Model = new Model();
		
		$verdata_sql = "select * from xb_versioninfo where id='".$id."'";
		$verdata_list= $Model->query($verdata_sql);
		
		if(count($verdata_list)<=0){
			
			echo "<script>alert('非法操作');history.go(-1);</script>";
			$this -> error('非法操作');
		}else{
			
			//是否启用
			$flag_arr = array(
					'1' => '启用',
					'9' => '关闭',
			);
			/* $optionflag = '<option value=""></option>'; */
			foreach($flag_arr as $keyc => $valc) {
				$optionflag .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			//系统的设置
			$systemtype_arr = array(
					'1' => '安卓',
					'2' => 'ios',
			);
			/* $optionflag = '<option value=""></option>'; */
			foreach($systemtype_arr as $keyc => $valc) {
				$optionsystemtype .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['systemtype']==$keyc) { $optionsystemtype .= ' selected="selected" '; }
				$optionsystemtype .= '>'.$valc.'</option>';
			}
			$this->assign('optionsystemtype',$optionsystemtype);
			
			//是否强制升级
			$uptype_arr = array(
					'1' => '强制升级',
					'2' => '非强制升级',
			);
			/* $optionflag = '<option value=""></option>'; */
			foreach($uptype_arr as $keyc => $valc) {
				$optionuptype .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['uptype']==$keyc) { $optionuptype .= ' selected="selected" '; }
				$optionuptype .= '>'.$valc.'</option>';
			}
			$this->assign('optionuptype',$optionuptype);
			
			
			if($verdata_list[0]['flag']=='1'){
				$verdata_list[0]['flag']='启用';
			}else if($verdata_list[0]['flag']=='9'){
				$verdata_list[0]['flag']='关闭';
			}
			
			if($verdata_list[0]['systemtype']=='1'){
				$verdata_list[0]['systemtype']='安卓';
			}else if($verdata_list[0]['systemtype']=='2'){
				$verdata_list[0]['systemtype']='ios';
			}
			
			if($verdata_list[0]['uptype']=='1'){
				$verdata_list[0]['uptype']='强制升级';
			}else if($verdata_list[0]['uptype']=='2'){
				$verdata_list[0]['uptype']='非强制升级';
			}
			
		}
		
		
		
		$this->assign('list',$verdata_list[0]);
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	//数据的修改添加
	public function updateverdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateverdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$id    = $this->_post('id');
		$flag  = $this->_post('flag');
		$systemtype    = $this->_post('systemtype');
		$versioncode   = $this->_post('versioncode');
		$updescription = $this->_post('updescription');
		$uptype        = $this->_post('uptype');
		$apk_url        = $this->_post('apk_url');
		
		
		$Model = new Model();
		$data=array();
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath =  './Public/Uploads/apk/'.date('Y-m').'/';// 设置附件上传目录
		
		$upload->thumb = false;
		$upload->thumbMaxHeight = '300';
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777,true);
		}
		
		$infof  =   $upload->upload();
		
		if($infof===true){
			
			$info =  $upload->getUploadFileInfo();
			$apkurl = $info[0]['savepath'].$info[0]['savename'];
			$data['apk_url']          = $apkurl;
			
			$data_sql = "select apk_url from xb_versioninfo where id='".$id."'";
			$data_list = $Model->query($data_sql);
				
			$filepath=$data_list[0]['apk_url'];
			
			if(file_exists($filepath)){
				unlink($filepath);
			}
		}else{
			$data['apk_url']         = $apk_url;
		}
		
		 $data['flag']            = $flag;
		 $data['systemtype']      = $systemtype;
		 $data['versioncode']     = $versioncode;
		 $data['updescription']   = $updescription;
		 $data['uptype']          = $uptype;
		 $data['up_createtime']   = date('Y-m-d');
			 	
		
		 $imagedata_sql = $Model->table('xb_versioninfo')->where ("id='".$id."'")->save($data);
		 	
		 if($imagedata_sql){
			 echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Versionlist/index".$yuurl."';</script>";
			 $this ->success('数据修改成功!','__APP__/Versionlist/index'.$yuurl);
		 }else{
			 echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			 $this->error('数据修改失败！');
		 }
	}
	
	
	//参数管理
	//后台配置数据的修改
	public function updateconfigshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateconfigshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		$id = $this->_post('id');
		$update_submit = $this->_post('update_submit');
	
	
		if($update_submit!=''){
	
	
			$Model = new Model();
	
			$sqlpic       = "select * from xb_config where flag='1' and id='".$id."'";
			$verdata_list = $Model->query($sqlpic);
	
	
			if(count($verdata_list)<=0){
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}
	
		}
		$this->assign('list',$verdata_list[0]);
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//后台配置文件展示
	public function configshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_configshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//数据库的初始化
		$Model = new Model();
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
		//执行SQL查询语句
		$list  = $Model -> table('xb_config')-> where('flag=1')-> select();
	
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//参数配置页面的添加
	public function updateconfigshowdata(){
	
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateconfigshowdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取相应的参数
		$id            = $this->_post('id');
		$qq            = $this->_post('qq');  //是否启用
		$version       = $this->_post('version');
		$companyinfo   = $this->_post('companyinfo');
		$normalusernum     = $this->_post('normalusernum');
		$normaluserscore    = $this->_post('normaluserscore');
		$unnormalusernum     = $this->_post('unnormalusernum');
		$unnormaluserscore    = $this->_post('unnormaluserscore');
	
		$update_submit = $this->_post('update_submit');
	
		$Model = new Model();
	
		$data=array();
		$data['qq']           = $qq;
		$data['version']      = $version;
		$data['content']  = $companyinfo;
		$data['normalusernum']      = $normalusernum;
		$data['normaluserscore']    = $normaluserscore;
		$data['unnormalusernum']    = $unnormalusernum;
		$data['unnormaluserscore']  = $unnormaluserscore;
		$data['	createtime']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('xb_config')->where ("flag=1 and id='".$id."'")->save($data);
	
	
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Versionlist/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Versionlist/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
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