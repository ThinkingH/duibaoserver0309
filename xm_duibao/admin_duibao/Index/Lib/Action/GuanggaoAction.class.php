<?php
/*
 * 模块广告图
 */

class GuanggaoAction extends Action {
	
	private $lock_index                = '9751';
	private $lock_addlunbotushow       = '975';
	private $lock_addlunbotudata       = '975';
	private $lock_updatelunbotushow    = '975';
	private $lock_updatelunbotudata    = '975';
	private $lock_deletelunbodata      = '97';
	
	private $lock_updateshow   = '975';
	private $lock_addshow      = '975';

	
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
		$sql_where = " biaoshi=4";
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_lunbotu')
						-> where($sql_where)
						-> order($sql_order)
						-> select();
		
		/* $listsql = "select * from xb_lunbotu where biaoshi='4' and flag='1' ";
		$blist = $Model->query($listsql); */
		
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		//数据的读出
		foreach ($list as $keys=>$vals){
			
			
		if($list[$keys]['flag']=='1'){
				$list[$keys]['flag']='<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flag']='<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}
				
			if($list[$keys]['picname']=='1'){
				$list[$keys]['picname']='轮播图一';
			}else if($list[$keys]['picname']=='2'){
				$list[$keys]['picname']='轮播图二';
			}else if($list[$keys]['picname']=='3'){
				$list[$keys]['picname']='轮播图三';
			}else if($list[$keys]['picname']=='4'){
				$list[$keys]['picname']='轮播图四';
			}
				
			if($list[$keys]['action']=='1'){
				$list[$keys]['action']='页面';
			}else if($list[$keys]['action']=='2'){
				$list[$keys]['action']='活动';
			}else if($list[$keys]['action']=='3'){
				$list[$keys]['action']='商品';
			}
				
			if($list[$keys]['isused']=='1'){
				$list[$keys]['isused']='允许点击';
			}else if($list[$keys]['isused']=='2'){
				$list[$keys]['isused']='不允许点击';
			}
			
		}
		
		
		//执行SQL查询语句
		$blist  = $Model -> table('ad_advertisement')
						-> where(" maintype=1")
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		//数据的读出
		foreach ($blist as $keys=>$vals){
		
			if($blist[$keys]['maintype']=='1'){
				$blist[$keys]['maintype']='<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($blist[$keys]['maintype']=='99'){
				$blist[$keys]['maintype']='<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}
		
			if($blist[$keys]['gflag']=='1'){
				$blist[$keys]['gflag']='普通列表';
			}else if($blist[$keys]['gflag']=='2'){
				$blist[$keys]['gflag']='网页广告';
			}else if($blist[$keys]['gflag']=='3'){
				$blist[$keys]['gflag']='下载广告';
			}
		
			if($blist[$keys]['gtype']=='1'){
				$blist[$keys]['gtype']='任务';
			}else if($blist[$keys]['gtype']=='2'){
				$blist[$keys]['gtype']='广告';
			}
		
		}
					
		$this -> assign('list',$list);
		$this -> assign('blist',$blist);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//轮播图的添加页面
	public function addlunbotushow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addlunbotushow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
		
	}
	
	
	//轮播图的添加
	public function addlunbotudata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addlunbotudata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$flag      = $this->_post('flag');//关闭和开启
		$action    = $this->_post('tztype');//跳转类型
		$content   = $this->_post('content');//跳转页面的内容
		$value     = $this->_post('tzurl');  //跳转页面的链接
		$isused    = $this->_post('isused');   //是否可点击
		$picname   = $this->_post('picname'); 
		//把接收的数据写入到,上传的图片内容
		//$r = file_put_contents('../../Xheditor/content/2.txt', $content);
		
		
		//图片的上传
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		//$upload->savePath =  './Public/Uploads/lunbotu/'.date('Y-m').'/';// 设置附件上传目录
		$upload->savePath =  './Public/Uploads/lunbotu/';// 设置附件上传目录
		
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
		
		//把接收的数据以json的形式存入到数组中
		/* $temparr = array(
				'1' => array(
						'flag'   => $flag,
						'img'    => $picurl,
						'action' => $action,
						'value'  => $value,
						'isused' => $isused,
				),
		);  */
		
		//数据库的初始化
		$Model = new Model();
		
		$data = array();
		
		$data['flag'] = $flag;
		$data['img']  = $picurl;
		$data['action']  = $action;
		$data['value']  = $value;
		$data['isused'] = $isused;
		$data['content'] = $content;
		$data['picname'] = $picname;
		$data['createdatetime'] = date('Y-m-d h:i:s');
		
		
		//把接收的数据存入到文件中
		
		//$piccontent = file_put_contents('./Public/Uploads/picconfig/lunbo_1.txt', json_encode($temparr));
		//$piccontent = file_put_contents('./Public/Uploads/picconfig/lunbo_1.txt',$temparr);
		$imagedata_sql = $Model->table('xb_lunbotu')->add($data);
		
		if($imagedata_sql){//说明文件上传成功
			
			echo "<script>alert('轮播图添加成功！');window.location.href='".__APP__."/Guanggao/index".$yuurl."';</script>";
			$this ->success('轮播图添加成功!','__APP__/Guanggao/index'.$yuurl);
		}else{
			echo "<script>alert('任务添加失败！'); history.go(-1);</script>";
			$this ->success('轮播图添加失败!');
		}
		
	}
	
	
	//页面的修改
	public function updatelunbotushow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatelunbotushow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		
		
		if($update_submit!=''){
			
		
		$Model = new Model();
		
		$sqlpic = "select * from xb_lunbotu where id='".$id."'";
		$verdata_list = $Model->query($sqlpic);
		
		
		if(count($verdata_list)<=0){
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
				if($verdata_list[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			//跳转页面类型
			$tztype_arr = array(
					'1'=> '页面',
					'2'=> '活动',
					'3'=> '商品',
			);
			
			foreach($tztype_arr as $keyc => $valc) {
				$optiontype .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['action']==$keyc) { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>'.$valc.'</option>';
			}
			$this->assign('optiontype',$optiontype);
			
			//跳转页面类型
			$lunbo_arr = array(
					'1'=> '轮播图一',
					'2'=> '轮播图二',
					'3'=> '轮播图三',
					'4'=> '轮播图四',
			);
				
			foreach($lunbo_arr as $keyc => $valc) {
				$optionlunbo .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['picname']==$keyc) { $optionlunbo .= ' selected="selected" '; }
				$optionlunbo .= '>'.$valc.'</option>';
			}
			$this->assign('optionlunbo',$optionlunbo);
			
			//是否可点击
			$click_arr = array(
					'1' => '允许点击',
					'2' => '不允许点击',
			);
			foreach($click_arr as $keyc => $valc) {
				$optionclick .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['isused']==$keyc) { $optionclick .= ' selected="selected" '; }
				$optionclick .= '>'.$valc.'</option>';
			}
			$this->assign('optionclick',$optionclick);
			
			
			}
		
		}
		$this->assign('list',$verdata_list[0]);
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//页面的修改
	public function updatelunbotudata(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatelunbotudata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$id        = $this->_post('id'); 
		$flag      = $this->_post('flag');  //是否启用
		$tztype    = $this->_post('tztype');
		$content   = $this->_post('content');
		$tzurl     = $this->_post('tzurl');
		$isused    = $this->_post('isused');
		$shopname    = $this->_post('shopname');
		$update_submit = $this->_post('update_submit');
		
		$Model = new Model();
		$data=array();
		
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
		
		$infof  =   $upload->upload();
		
		if($infof===true){
			$info =  $upload->getUploadFileInfo();
			$apkurl = $info[0]['savepath'].$info[0]['savename'];
			$picurl = URL_APK.str_replace('./','/',$apkurl);
			$data['img']          = $picurl;
		}
		
		if($apkurl!=''){
				
			$data_sql = "select img from xb_versioninfo where id='".$id."'";
			$data_list = $Model->query($data_sql);
				
			$filepath=$data_list[0]['img'];
		
			if(file_exists($filepath)){
				unlink($filepath);
			}
		}
		
		
		$data['flag']        = $flag;
		$data['action']      = $tztype;
		$data['content']     = $content;
		$data['imgurl']       = $tzurl;
		$data['isused']      = $isused;
		$data['shopname']      = $shopname;
		$data['createdatetime']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('xb_lunbotu')->where ("id='".$id."'")->save($data);
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Guanggao/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Guanggao/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
	}
	
	
	//页面的删除
	//删除操作
	public function deletelunbodata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletelunbodata);
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
			$seldata_sql  = "select img from xb_lunbotu where id='".$id."'";
			$seldata_list = $Model->query($seldata_sql);
				
			$filepath=$seldata_list[0]['img'];
				
			if(file_exists($filepath)){
				unlink($filepath);
			}
				
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('xb_lunbotu') -> where("id='".$id."'") -> delete();
				
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Guanggao/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Guanggao/index'.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
			}
				
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
					'99' => '关闭',
					'1' => '启用',
	
			);
			foreach($flag_arr as $keyc => $valc) {
				$optionflag .= '<option value="'.$keyc.'" ';
				if($listdata[0]['maintype']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
				
				
			//广告跳转类型
			$tiaozhuantype = array(
					'1' => '普通列表',
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
	
	
		$data['maintype'] = $flag;
		$data['gflag']  = $gflag;
		$data['gtype']  = $type;
		$data['adtitle']  = $adtitle;
		$data['adcontent'] = $adcontent;
		$data['adurl'] = $tzurl;
		$data['taskid'] = $taskid;
		$data['createtime'] = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('ad_advertisement')->where ("id='".$id."'")->save($data);
	
	
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Guanggao/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Guanggao/index'.$yuurl);
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
