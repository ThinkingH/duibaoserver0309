<?php
/*
 * 首页类型的分类
 */
class MaintypeAction extends Action{
	
	
	private $lock_index                = '9751';
	private $lock_addlunbotushow       = '975';
	private $lock_updatetypeshow       = '975';
	private $lock_updatetypedata      = '975';
	private $lock_addtypedata         = '97';
	private $lock_deletetypedata      = '97';
	private $lock_chupdatetypedata    = '97';
	private $lock_chupdatetypeshow    = '975';
	
	
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
		
		//首页logo图标
		$selsql = "select * from maintype order by create_date desc ";
		$sellist = $Model->query($selsql);
	
		foreach ($sellist as $keys => $vals){
			
			$sellist[$keys]['smallpic'] = hy_qiniuimgurl('duibao-basic',$sellist[$keys]['smallpic'],'50','50');
			
			if($sellist[$keys]['flag']=='1'){
				$sellist[$keys]['flags']='<font style="background-color:#00EA00">&nbsp;&nbsp;启用&nbsp;&nbsp;</font>';
			}else if($sellist[$keys]['flag']=='9'){
				$sellist[$keys]['flags']='<font style="background-color:#FF1700">&nbsp;&nbsp;关闭&nbsp;&nbsp;</font>';
			}
				
		}
		
		
		//首页类型的子分类
		$sql = "select * from shouye_config";
		$shouyelist = $Model->query($sql);
		
		foreach ($shouyelist as $keys => $vals){
			
			$shouyelist[$keys]['smallpic'] = hy_qiniuimgurl('duibao-basic',$shouyelist[$keys]['smallpic'],'50','50');
			
			if($shouyelist[$keys]['flag']=='1'){
				$shouyelist[$keys]['flags']='<font style="background-color:#00EA00">&nbsp;&nbsp;启用&nbsp;&nbsp;</font>';
			}else if($shouyelist[$keys]['flag']=='9'){
				$shouyelist[$keys]['flags']='<font style="background-color:#FF1700">&nbsp;&nbsp;关闭&nbsp;&nbsp;</font>';
			}
		
		}
		
			
		$this -> assign('list',$sellist);
		$this -> assign('lists',$shouyelist);
	
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
	
	
	//页面的修改
	public function updatetypeshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatetypeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$Model = new Model();
	
		$kindtype = $this->_post('kindtype');
		
		
		if(is_numeric($kindtype)){
			
			$quantypesql  = "select * from shouye_config where id='".$kindtype."'";
		}else{
			
			$quantypesql  = "select * from maintype where kindtype='".$kindtype."'";
		}
		
		$quantypelist = $Model->query($quantypesql);
	
		if(count($quantypelist)<=0){
				
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
				if($quantypelist[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			$quantypelist[0]['smallpic'] = hy_qiniuimgurl('duibao-basic',$quantypelist[0]['smallpic'],'50','50');
		}
	
		$this->assign('list',$quantypelist[0]);
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	
	//页面的修改
	public function chupdatetypeshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chupdatetypeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$Model = new Model();
	
		$kindtype = $this->_post('kindtype');
		
		$quantypesql  = "select * from shouye_config where id='".$kindtype."'";
		
		
		$quantypelist = $Model->query($quantypesql);
	
		if(count($quantypelist)<=0){
				
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
				if($quantypelist[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			$quantypelist[0]['smallpic'] = hy_qiniuimgurl('duibao-basic',$quantypelist[0]['smallpic'],'50','50');
		}
	
		$this->assign('list',$quantypelist[0]);
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	//修改添加
	public function updatetypedata(){
	
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatetypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取相应的参数
		$flag      = $this->_post('flag');  //是否启用
		$kindtype    = $this->_post('kindtype');
		$kindname   = $this->_post('kindname');
		$update_submit = $this->_post('update_submit');
	
		$Model = new Model();
	
		$data=array();
	
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath = XMAINPATH.'Public/Uploads/hottype/'.date('Y-m-d').'/';// 设置附件上传目录
	
		$upload->thumb = false;
		$upload->thumbMaxHeight = '300';
	
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777,true);
		}
	
		$infof  =   $upload->upload();
		
		if($infof===true){
			
			$info =  $upload->getUploadFileInfo();
			
			//云存储上传
			$apkurl   = $info[0]['savepath'].$info[0]['savename'];
			$savename = $info[0]['savename'];//图片名称
			
			$r=upload_qiniu('duibao-basic',$apkurl,$savename);
			
			if($r){
				$data['smallpic']          = $r;
			}
			
			//本地图片的删除
			delfile($apkurl);
				
			//对应云存储的删除
			$seldata_sql  = "select smallpic from maintype where kindtype='".$kindtype."'";
			$seldata_list = $Model->query($seldata_sql);
				
			
			$bucket= 'duibao-basic';//http://osv2nvwyw.bkt.clouddn.com/596c7fd36d942.png
			$filename = $seldata_list[0]['smallpic'];
			
			//七牛云上图片删除
			delete_qiniu('duibao-basic',$seldata_list[0]['img']);
			
		}
		
		$data['flag']        = $flag;
		$data['kindname']     = $kindname;
		$data['create_date']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('maintype')->where ("kindtype='".$kindtype."'")->save($data);
		
	
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Maintype/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Maintype/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
	
	}
	
	
	
	//子分类的修改添加
	public function chupdatetypedata(){
	
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chupdatetypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取相应的参数
		$flag      = $this->_post('flag');  //是否启用
		$kindtype    = $this->_post('kindtype');
		$kindname   = $this->_post('kindname');
		$update_submit = $this->_post('update_submit');
	
		$Model = new Model();
	
		$data=array();
	
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath = XMAINPATH.'Public/Uploads/hottype/'.date('Y-m-d').'/';// 设置附件上传目录
	
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
			$r=upload_qiniu('duibao-basic',$apkurl,$info[0]['savename']);
			
			if($r){
				$data['smallpic'] = $r;
			}else{
				$data['smallpic'] = '';
			}
			
			//本地图片的删除
			delfile($apkurl);
			
			$data_sql = "select smallpic from shouye_config where id='".$kindtype."'";
			$data_list = $Model->query($data_sql);
			
			//七牛云上图片删除
			delete_qiniu('duibao-basic',$data_list[0]['smallpic']);
		}
	
	
		$data['flag']        = $flag;
		$data['childtype']     = $kindname;
		$data['createtime']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('shouye_config')->where ("id='".$kindtype."'")->save($data);
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Maintype/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Maintype/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
	
	}
	
	//类型的添加
	public function addtypedata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addtypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取相应的参数
		$flag        = $this->_post('flag');//关闭和开启
		$kindtype    = $this->_post('kindtype');
		$kindname    = $this->_post('kindname');
		
		$data = array();
	
		//图片的上传
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath =  XMAINPATH.'Public/Uploads/hottype/'.date('Y-m-d').'/';// 设置附件上传目录
	
		$upload->thumb = false;
		$upload->thumbMaxHeight = '300';
	
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777,true);
		}
		
		$infof  =   $upload->upload();
		
		if($infof===true){
			$info =  $upload->getUploadFileInfo();
			
			//七牛图片上传
			$apkurl = $info[0]['savepath'].$info[0]['savename'];
			$r=upload_qiniu('duibao-basic',$apkurl,$info[0]['savename']);
			if($r){
				$data['smallpic'] = $r;
			}else{
				$data['smallpic']='';
			}
			//本地图片删除
			delfile($apkurl);
		}
	
		//数据库的初始化
		$Model = new Model();
	
		$data['flag'] = $flag;
		$data['type']  = $kindtype;
		$data['childtype']  = $kindname;
		$data['createtime'] = date('Y-m-d h:i:s');
	
		//把接收的数据存入到文件中
		$imagedata_sql = $Model->table('shouye_config')->add($data);
		
		if($imagedata_sql){//说明文件上传成功
			echo "<script>alert('类型添加成功！');window.location.href='".__APP__."/Maintype/index".$yuurl."';</script>";
			$this ->success('类型添加成功!','__APP__/Maintype'.$yuurl);
		}else{
			echo "<script>alert('类型添加失败！'); history.go(-1);</script>";
			$this ->success('类型添加失败!');
		}
	
	}
	
	
	//数据的删除
	public function deletetypedata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletetypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取参数
		$kindtype = $this->_post('kindtype');
		$delete_submit = $this->_post('delete_submit');
	
		if($delete_submit!=''){
	
			if($kindtype==''){
				echo "<script>alert('非法操作！'); history.go(-1);</script>";
				$this->error('非法操作！');
			}
	
	
			//数据库初始化
			$Model = new Model();
			
			if(is_numeric($kindtype)){
				
				$ret_zhuangtai = M("shouye_config")->where("id='".$kindtype."'")->find();
				if($ret_zhuangtai['flag']=='1'){
					echo "<script>alert('开启状态的数据只有关闭时，才可以进行删除！');history.go(-1);</script>";
					$this ->error('开启状态的数据只有关闭时，才可以进行删除!');
				}
				
				$seldata_sql  = "select smallpic from shouye_config where id='".$kindtype."'";
				$seldata_list = $Model->query($seldata_sql);
				
				//七牛云上图片删除
				delete_qiniu('duibao-basic',$seldata_list[0]['smallpic']);
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shouye_config') -> where("id='".$kindtype."'") -> delete();
				
			}else{
				
				$ret_zhuangtai = M("maintype")->where("kindtype='".$kindtype."'")->find();
				if($ret_zhuangtai['flag']=='1'){
					echo "<script>alert('开启状态的数据只有关闭时，才可以进行删除！');history.go(-1);</script>";
					$this ->error('开启状态的数据只有关闭时，才可以进行删除!');
				}
				
				$seldata_sql  = "select smallpic from maintype where kindtype='".$kindtype."'";
				$seldata_list = $Model->query($seldata_sql);
				
				//七牛云上图片删除
				delete_qiniu('duibao-basic',$seldata_list[0]['smallpic']);
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('maintype') -> where("kindtype='".$kindtype."'") -> delete();
			}
	
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Maintype/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Maintype/index'.$yuurl);
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