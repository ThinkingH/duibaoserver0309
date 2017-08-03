<?php
/*
 * 饭票的分类
 */

class QuantypeAction extends Action {
	
	private $lock_index                = '9751';
	private $lock_addlunbotushow       = '975';
	private $lock_updatetypeshow       = '975';
	private $lock_updatetypedata      = '975';
	private $lock_addtypedata         = '97';
	private $lock_deletetypedata      = '97';
	
	
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
		$selsql = "select * from xb_kind where biaoshi='2' ";
		$sellist = $Model->query($selsql);
		
		foreach ($sellist as $keys => $vals){
			
			if($sellist[$keys]['flag']=='1'){
				$sellist[$keys]['flag']='<font style="background-color:#00EA00">&nbsp;&nbsp;启用&nbsp;&nbsp;</font>';
			}else if($sellist[$keys]['flag']=='9'){
				$sellist[$keys]['flag']='<font style="background-color:#FF1700">&nbsp;&nbsp;关闭&nbsp;&nbsp;</font>';
			}
			
			$arr = unserialize(BUCKETSTR);//七牛云存储连接$arr['duibao-basic']
				
			if(substr($sellist[$keys]['smallpic'],0,7)=='http://' || substr($sellist[$keys]['smallpic'],0,8)=='https://'){
			
			}else{
				$sellist[$keys]['smallpic'] = $arr['duibao-basic'].$sellist[$keys]['smallpic'].'?imageView2/1/w/200/h/160/q/75|imageslim';
			}
		}
					
		$this -> assign('list',$sellist);
		
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
		
		$kindtype = $this->_post('kindtype');
		
		$Model = new Model();
		
		$quantypesql  = "select * from xb_kind where kindtype='".$kindtype."'";
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
		$upload->savePath =  XMAINPATH.'Public/Uploads/hottype/'.date('Y-m-d').'/';// 设置附件上传目录
		
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
			$savename = $info[0]['savename'];//图片名称
			
			$r=upload_qiniu('duibao-basic',$apkurl,$savename);
			
			$data['smallpic']=$r;
			
			//本地图片的删除
			delfile($apkurl);
			
			
			$data_sql = "select smallpic from xb_kind where kindtype='".$kindtype."'";
			$data_list = $Model->query($data_sql);
			
			$bucket= 'duibao-basic';//http://osv2nvwyw.bkt.clouddn.com/596c7fd36d942.png
			$filename = $data_list[0]['smallpic'];
			
			delqiuniu($bucket,$filename);
			
		}
		
		
		$data['flag']          = $flag;
		$data['kindname']      = $kindname;
		$data['createtime']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('xb_kind')->where ("kindtype='".$kindtype."'")->save($data);
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Quantype/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Quantype/index'.$yuurl);
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
		$flag      = $this->_post('flag');//关闭和开启
		$kindtype    = $this->_post('kindtype');
		$kindname   = $this->_post('kindname');
		
		
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
			mkdir($upload->savePath,0777);
		}
		
		if(!$upload->upload()) {                             // 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{                                              // 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		
		$pathurl  = $info[0]['savepath'].$info[0]['savename'];
		$savename = $info[0]['savename'];//图片名称
		
		//图片上传
		$r=upload_qiniu('duibao-basic',$pathurl,$savename);
		
		$data = array();
		
		if($r){
			$data['smallpic']  = $r;
		}else{
			$data['smallpic']  = '';
		}
		
		//本地文件的删除
		delfile($pathurl);
		
		
		//数据库的初始化
		$Model = new Model();
		
		$data['flag'] = $flag;
		$data['kindtype']  = $kindtype;
		$data['kindname']  = $kindname;
		$data['createtime'] = date('Y-m-d h:i:s');
		
		
		//把接收的数据存入到文件中
		$imagedata_sql = $Model->table('xb_kind')->add($data);
		
		if($imagedata_sql){//说明文件上传成功
			
			echo "<script>alert('类型添加成功！');window.location.href='".__APP__."/Quantype/index".$yuurl."';</script>";
			$this ->success('类型添加成功!','__APP__/Quantype'.$yuurl);
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
			
		
			//对应安装包的删除
			$seldata_sql  = "select smallpic from xb_kind where kindtype='".$kindtype."'";
			$seldata_list = $Model->query($seldata_sql);
		
			$filepath=$seldata_list[0]['smallpic'];
			
			$bucket= 'duibao-basic';//http://osv2nvwyw.bkt.clouddn.com/596c7fd36d942.png
			
			$deldata = delqiuniu($bucket,$filepath);
		
		
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('xb_kind') -> where("kindtype='".$kindtype."'") -> delete();
		
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Quantype/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Quantype/index'.$yuurl);
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
