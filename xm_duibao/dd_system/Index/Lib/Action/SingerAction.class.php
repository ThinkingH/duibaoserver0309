<?php

/*
 * 用户信息的查看
 */

class SingerAction extends Action{
	
	//定义各模块锁定级别
	private $lock_userlist              = '3';
	private $lock_editdata              = '3';
	private $lock_editlist              = '3';
	private $lock_listdata              = '3';
	
	//用户信息的展示
	public function userlist(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_userlist);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$phone           = $this->_get('phone');
		$nickname        = $this->_get('nickname');
		$date_s         = $this->_get('date_s');
		$date_e         = $this->_get('date_e');
		
		
		$this->assign('phone',$phone);
		$this->assign('nickname',$nickname);
		
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		
		$Model = new Model();
		
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($phone!='') {
			$sql_where .= " phone='".$phone."' and ";
		}
		
		if($nickname!='') {
			$sql_where .= " username like '%".$nickname."%' and ";
		}
		
		if($date_s!='') {
			$sql_where .= " createtime>='".$date_s."' and ";
		}
		
		if($date_e!='') {
			$sql_where .= " createtime<='".$date_e."' and ";
		}
		
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xx_users')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xx_users')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['type']=='0'){
				$list[$keyc]['type']='原创';
			}else if($list[$keyc]['type']=='1'){
				$list[$keyc]['type']='竞唱';
			}
			
			
		}
		
		$this -> assign('list',$list);
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	//文件的下载
	public function download(){
		
		
		$id   = $this->_post('id');
		$type = $this->_post('type');
		
		$Model = new Model();
		
		//download ($filename, $showname='',$content='',$expire=180)
		
		$selectsql = "select yinpin,yuanyinpin,shipin,yuanshipin from xx_users where id='".$id."'";
		$selectlist = $Model->query($selectsql);
		
		
		import('ORG.HyDownload');// 导入文件下载类
		
		//实例化下载类
		$HyDownload = new HyDownload();
		
		
		if($type=='1'){//音频下载/updata/audio/2017072410185512186.mp3
			
			$yinpin = $selectlist[0]['yinpin']; //音频路径
			$yuanyinpin = explode('.',$selectlist[0]['yuanyinpin']); //音频原名称
			$showname = $yuanyinpin[0];//不带文件格式后缀的文件名
			$filename = substr($yinpin,14); //音频名称
			$pathname = PICPATH.'signer'.substr($yinpin,0,14);//文件的绝对路径
			
			$HyDownload->set_init( $pathname, $filename, $showname);
			
			$HyDownload->download(); //一次性输出
			
		}else if($type=='2'){//视频下载
			
			$shipin = $selectlist[0]['shipin'];//视频路径
			$yuanshipin = explode('.',$selectlist[0]['yuanshipin']); //视频原名称
			$showname = $yuanshipin[0];//不带文件格式后缀的文件名
			$filename = substr($shipin,14);
			
			$pathname = PICPATH.'signer'.substr($shipin,0,14);//文件的绝对路径
			
				
			$HyDownload->set_init( $pathname, $filename, $showname);
				
			$HyDownload->download(); //一次性输出
			
		}
	}
	
	
	public function editlist(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		
		$Model = new Model();
		
		$selsql = "select * from xx_edit  where id='".$id."'";
		$sellist = $Model->query($selsql);
		
		$this->assign('list',$sellist[0]);
		$this->display();
		
	}
	
	
	
	public function editdata(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_editdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		
		$title1 = $this->_post('title1');
		$content1 = $this->_post('content1');
		$title2 = $this->_post('title2');
		$content2 = $this->_post('content2');
		
		
		$Model = new Model();
		
		$data = array();
		
		//图片的上传
		import('ORG.UploadFile');
			
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		//$upload->savePath =  './Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
		$upload->savePath = './Public/Uploads/shoppic/'.date('Y-01').'/';// 设置附件上传目录
		
		$upload->thumb = true;
		$upload->thumbMaxHeight = '300';
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777,true);
		}
		
		$r = $upload->upload();
		
		
		
		if($r){
			$info =  $upload->getUploadFileInfo();
			
			for ($i=0;$i<=3;$i++){
			
			
				if($info[$i]['key']=='picurl1'){
					$showpic1 = $info[$i]['savepath'].$info[$i]['savename'];
					$data['showpic1'] = $showpic1;
			
				}
				if($info[$i]['key']=='picurl2'){
					$showpic2 = $info[$i]['savepath'].$info[$i]['savename'];
					$data['showpic2'] = $showpic2;
			
				}
				if($info[$i]['key']=='picurl3'){
					$showpic3 = $info[$i]['savepath'].$info[$i]['savename'];
					$data['showpic3'] = $showpic3;
			
				}
			}
		}
		
		
		$data['title1'] = $title1;
		$data['content1'] = $content1;
		$data['title2'] = $title2;
		$data['content2'] = $content2;
		$data['createtime']   = date('Y-m-d H:i:s');
		
		
		//说明此数据没有关联数据，可以删除
		$ret = $Model -> table('xx_edit')  -> where("id='".$id."'")->save($data);
			
		//echo $Model->getLastsql();exit;
		
		if($ret) {
			echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Singer/listdata".$yuurl."';</script>";
			$this -> success('数据添加成功!','__APP__/Singer/listdata'.$yuurl);
		}else {
			echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
			$this -> error('数据添加失败，系统错误!');
		}
		
	}
	
	
	public function listdata(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_listdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$Model = new Model();
		
		$selsql = "select * from xx_edit ";
		$sellist = $Model->query($selsql);
		
		$this->assign('list',$sellist);
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