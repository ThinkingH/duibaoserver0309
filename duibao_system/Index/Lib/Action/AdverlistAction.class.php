<?php
/*
 * 广告的上传
 */
class AdverlistAction extends Action {
	
	
	private $lock_index                = '9751';
	private $lock_adddata              = '97';
	private $lock_addshow              = '975';
	private $lock_updateshow           = '975';
	private $lock_updatedata           = '97';
	private $lock_deletedata           = '97';
	
	//广告的展示
	public function index(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//数据库的初始化
		$Model = new Model();
	
		//生成排序字符串数据
		$sql_order = " id desc ";
		$sql_where = "flag=1 ";
	
		import('ORG.Page');// 导入分页类
	
		//执行SQL查询语句
		$list  = $Model -> table('ad_advertisement')
						-> where($sql_where)
						-> order($sql_order)
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
	
		//数据的读出
		foreach ($list as $keys=>$vals){
				
			if($list[$keys]['flag']=='1'){
				$list[$keys]['flag']='启用';
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flag']='关闭';
			}
				
			if($list[$keys]['gflag']=='1'){
				$list[$keys]['gflag']='优惠券列表';
			}else if($list[$keys]['gflag']=='2'){
				$list[$keys]['gflag']='网页广告';
			}else if($list[$keys]['gflag']=='3'){
				$list[$keys]['gflag']='下载广告';
			}
				
			if($list[$keys]['type']=='1'){
				$list[$keys]['type']='任务';
			}else if($list[$keys]['type']=='2'){
				$list[$keys]['type']='广告';
			}
	
		}
			
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//广告的添加
	public function addshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//是否启用
		$flag_arr = array(
				'9' => '关闭',
				'1' => '启用',
					
		);
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		
		//广告跳转类型
		$tiaozhuantype = array(
				'1' => '优惠券列表',
				'2' => '网页广告',
				'3' => '下载广告',
		);
		
		foreach($tiaozhuantype as $keyc => $valc) {
			$optiontiaozhuan .= '<option value="'.$keyc.'" ';
			$optiontiaozhuan .= '>'.$valc.'</option>';
		}
		$this->assign('optiontiaozhuan',$optiontiaozhuan);
		
		
		//广告展示类型
		$showtype = array(
				'2' => '广告',
				'1' => '任务',
		);
		
		foreach($showtype as $keyc => $valc) {
			$optionshowtype .= '<option value="'.$keyc.'" ';
			$optionshowtype .= '>'.$valc.'</option>';
		}
		$this->assign('optionshowtype',$optionshowtype);
		
		
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//广告的添加
	public function adddata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_adddata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//获取相应的参数
		$flag  = $this->_post('flag');
		$gflag = $this->_post('gflag');
		$type  = $this->_post('type');
		$adtitle = $this->_post('adtitle');
		$adcontent = $this->_post('adcontent');
		$tzurl     = $this->_post('tzurl');
		$taskid    = $this->_post('taskid');
		$add_submit = $this->_post('add_submit');
		
		//图片的上传
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath =  './Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
		
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
		
		//数据库的初始化
		$Model = new Model();
		
		$data = array();
		
		$data['flag'] = $flag;
		$data['gflag']  = $gflag;
		$data['type']  = $type;
		$data['adtitle']  = $adtitle;
		$data['adcontent'] = $adcontent;
		$data['picurl'] = $picurl;
		$data['adurl'] = $tzurl;
		$data['taskid'] = $taskid;
		$data['createtime'] = date('Y-m-d h:i:s');
		
		
		//把接收的数据存入到文件中
		$imagedata_sql = $Model->table('ad_advertisement')->add($data);
		
		if($imagedata_sql){//说明文件上传成功
				
			echo "<script>alert('广告添加成功！');window.location.href='".__APP__."/Adverlist/index".$yuurl."';</script>";
			$this ->success('广告添加成功!','__APP__/Adverlist/index'.$yuurl);
		}else{
			echo "<script>alert('广告添加失败！'); history.go(-1);</script>";
			$this ->success('广告添加失败!');
		}
		
		
	}
	
	
	//广告的修改
	public function updateshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		
		$Model = new Model();
		
		$sqldata = "select * from ad_advertisement where id='".$id."'";
		$listdata = $Model->query($sqldata);
		
		if(count($listdata)<=0){
			echo "<script>alert('非法操作');history.go(-1);</script>";
			$this -> error('非法操作');
		}else{
			
			//是否启用
			$flag_arr = array(
					'9' => '关闭',
					'1' => '启用',
						
			);
			foreach($flag_arr as $keyc => $valc) {
				$optionflag .= '<option value="'.$keyc.'" ';
				if($listdata[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			
			//广告跳转类型
			$tiaozhuantype = array(
					'1' => '优惠券列表',
					'2' => '网页广告',
					'3' => '下载广告',
			);
			
			foreach($tiaozhuantype as $keyc => $valc) {
				$optiontiaozhuan .= '<option value="'.$keyc.'" ';
				if($listdata[0]['gflag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optiontiaozhuan .= '>'.$valc.'</option>';
			}
			$this->assign('optiontiaozhuan',$optiontiaozhuan);
			
			
			//广告展示类型
			$showtype = array(
					'2' => '广告',
					'1' => '任务',
			);
			
			foreach($showtype as $keyc => $valc) {
				$optionshowtype .= '<option value="'.$keyc.'" ';
				if($listdata[0]['type']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionshowtype .= '>'.$valc.'</option>';
			}
			$this->assign('optionshowtype',$optionshowtype);
			
		}
			
		$this->assign('list',$listdata[0]);
	
		
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//修改的添加
	public function updatedata(){
		
		
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$id    = $this->_post('id');
		$flag  = $this->_post('flag');
		$gflag = $this->_post('gflag');
		$type  = $this->_post('type');
		$adtitle = $this->_post('adtitle');
		$adcontent = $this->_post('adcontent');
		$tzurl     = $this->_post('tzurl');
		$taskid    = $this->_post('taskid');
		$add_submit = $this->_post('add_submit');
		
		
		
		$Model = new Model();
		$data=array();
		
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath =  './Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
		
		$upload->thumb = false;
		$upload->thumbMaxHeight = '300';
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777);
		}
		
		$infof  =   $upload->upload();
		
		if($infof===true){
			$info =  $upload->getUploadFileInfo();
			$apkurl = $info[0]['savepath'].$info[0]['savename'];
			$picurl = URL_APK.str_replace('./','/',$apkurl);
			$data['picurl']          = $picurl;
		}
		
		if($apkurl!=''){
				
			$data_sql = "select picurl from ad_advertisement where id='".$id."'";
			$data_list = $Model->query($data_sql);
				
			$filepath=$data_list[0]['picurl'];
		
			if(file_exists($filepath)){
				unlink($filepath);
			}
		}
		
		
		$data['flag'] = $flag;
		$data['gflag']  = $gflag;
		$data['type']  = $type;
		$data['adtitle']  = $adtitle;
		$data['adcontent'] = $adcontent;
		$data['adurl'] = $tzurl;
		$data['taskid'] = $taskid;
		$data['createtime'] = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('ad_advertisement')->where ("id='".$id."'")->save($data);
		
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Adverlist/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Adverlist/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
		
		
	}
	
	
	//数据的删除
	public function deletedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletedata);
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
			$seldata_sql  = "select picurl from ad_advertisement where id='".$id."'";
			$seldata_list = $Model->query($seldata_sql);
		
			$filepath=$seldata_list[0]['ad_advertisement'];
		
			if(file_exists($filepath)){
				unlink($filepath);
			}
		
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('ad_advertisement') -> where("id='".$id."'") -> delete();
		
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Adverlist/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Adverlist/index'.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
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