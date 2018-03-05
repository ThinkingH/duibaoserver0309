<?php
/*
 * 任务列表展示
 */

class TasklistAction extends Action{
	
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	private $lock_updatetaskshow     = '975';
	private $lock_updatetaskshowdata  = '975';
	private $lock_upload              = '975';
	private $lock_uploadimg           = '975';
	private $lock_addtaskdata         = '975';
	private $lock_deletetaskdata      = '97';
	private $lock_apkupload           = '975';
	private $lock_apkuploaddata       = '975';
	private $lock_ajax_delimg         = '975';
	
	public function index(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$date_s      = strtotime($this->_get('date_s'));
		$date_e      = strtotime($this->_get('date_e'));
		$flag_s      = $this->_get('flag_s');
		$taskname    = $this->_get('taskname');
		$score       = $this->_get('score');
		
		
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
		
		$this->assign('optionflag',$optionflag);
		$this->assign('taskname',$taskname);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		$this->assign('score',$score);
		
		$Model = new Model();
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($taskname!='') {
			$sql_where .= " name like '%".$taskname."%' and ";
		}
		
		if($date_s!='') {
			$sql_where .= "over_inttime>='".$date_s."' and ";
		}
		
		if($date_e!='') {
			$sql_where .= "over_inttime<='".$date_e."' and ";
		}
		
		if($score!='') {
			$sql_where .= "score>='".$score."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_task')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_task')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		//echo $Model->getLastsql();
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
			
			if($list[$keyc]['type']=='1') {
				$list[$keyc]['type'] = '游戏下载';
			}else if($list[$keyc]['type']=='2') {
				$list[$keyc]['type'] = '网页下载';
			}else {
				$list[$keyc]['type'] = 'ERR';
			}
			
			$date = date('Y-m-d');
			$jiequtime = substr(date('Y-m-d H:i:s',$list[$keyc]['over_inttime']),0,10);
			if(strtotime($jiequtime)<=strtotime($date)){
				
				$list[$keyc]['over_inttime']='<font style="background:#E63127">'.date('Y-m-d H:i:s',$list[$keyc]['over_inttime']).'</font>';
			}else{
				$list[$keyc]['over_inttime'] = date('Y-m-d H:i:s',$list[$keyc]['over_inttime']);
			}
			
			$arr = unserialize(BUCKETSTR);//七牛云存储连接$arr['duibao-basic']
			
			//http://ot9nqx2pm.bkt.clouddn.com/596ef414cf8d2.jpg?imageView2/1/w/600/h/1200/q/75|imageslim
			if(substr($list[$keyc]['mainimage'],0,1)=='/'){
				$list[$keyc]['mainimage'] = XMAINURL.$list[$keyc]['mainimage'];
			}else{
				$list[$keyc]['mainimage'] = $arr['duibao-basic'].$list[$keyc]['mainimage'].'?imageView2/1/w/200/h/200/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['liucheng_1_img'],0,1)=='/'){
				$list[$keyc]['liucheng_1_img'] = XMAINURL.$list[$keyc]['liucheng_1_img'];
			}else{
				$list[$keyc]['liucheng_1_img']=$arr['duibao-basic'].$list[$keyc]['liucheng_1_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['liucheng_2_img'],0,1)=='/'){
				$list[$keyc]['liucheng_2_img'] = XMAINURL.$list[$keyc]['liucheng_2_img'];
			}else{
				$list[$keyc]['liucheng_2_img'] = $arr['duibao-basic'].$list[$keyc]['liucheng_2_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['liucheng_3_img'],0,1)=='/'){
				$list[$keyc]['liucheng_3_img'] = XMAINURL.$list[$keyc]['liucheng_3_img'];
			}else{
				$list[$keyc]['liucheng_3_img'] = $arr['duibao-basic'].$list[$keyc]['liucheng_3_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['liucheng_4_img'],0,1)=='/'){
				$list[$keyc]['liucheng_4_img'] = XMAINURL.$list[$keyc]['liucheng_4_img'];
			}else{
				$list[$keyc]['liucheng_4_img'] = $arr['duibao-basic'].$list[$keyc]['liucheng_4_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['liucheng_5_img'],0,1)=='/'){
				$list[$keyc]['liucheng_5_img'] = XMAINURL.$list[$keyc]['liucheng_5_img'];
			}else{
				$list[$keyc]['liucheng_5_img'] =$arr['duibao-basic'].$list[$keyc]['liucheng_5_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
			}
			
			/* $list[$keyc]['mainimage'] = XMAINURL.$list[$keyc]['mainimage'];
			$list[$keyc]['liucheng_1_img'] = XMAINURL.$list[$keyc]['liucheng_1_img'];
			$list[$keyc]['liucheng_2_img'] = XMAINURL.$list[$keyc]['liucheng_2_img'];
			$list[$keyc]['liucheng_3_img'] = XMAINURL.$list[$keyc]['liucheng_3_img'];
			$list[$keyc]['liucheng_4_img'] = XMAINURL.$list[$keyc]['liucheng_4_img'];
			$list[$keyc]['liucheng_5_img'] = XMAINURL.$list[$keyc]['liucheng_5_img']; */
			
			$list[$keyc]['create_datetime'] = date("Y-m-d H:i:s",$list[$keyc]['create_datetime']); 
		}
		
		
		$this -> assign('list',$list);
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//任务列表的修改--图片的上传
	public function updatetaskshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatetaskshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl); 
		
		//接收用户选择的查询参数
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit'); 
		
		
		if($update_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
		
		}else {
		
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			} 
			
			//数据库初始化
			$Model = new Model();
			
			//任务的查询
			$tasklist_sql  = "select * from xb_task where id='".$id."'";
			$list          = $Model->query($tasklist_sql);
			
			
			if(count($list)<=0) {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}else {
				
				$option_flag = '';
				$option_flag .= '<option value="1" ';
				if($list[0]['flag']==1) { $option_flag .= ' selected="selected" '; }
				$option_flag .= '>启用</option>';
				$option_flag .= '<option value="9" ';
				if($list[0]['flag']==9) { $option_flag .= ' selected="selected" '; }
				$option_flag .= '>关闭</option>';
				
				$option_type = '';
				$option_type .= '<option value="1" ';
				if($list[0]['type']==1) { $option_type .= ' selected="selected" '; }
				$option_type .= '>游戏下载</option>';
				$option_type .= '<option value="2" ';
				if($list[0]['flag']==2) { $option_type .= ' selected="selected" '; }
				$option_type .= '>网页浏览</option>';
				
				//$picurl = 'http://127.0.0.1:8002/dd_system';
				
				$list[0]['youxiaotime'] = $list[0]['over_inttime']-$list[0]['create_datetime'];
				//有效期限
				$list[0]['youxiaotime'] = ceil($list[0]['youxiaotime']/(60*60*24));
				
				$list[0]['create_datetime'] = date('Y-m-d H:i:s',$list[0]['create_datetime']);
				//到期时间
				$list[0]['over_inttime'] = substr(date('Y-m-d H:i:s',$list[0]['over_inttime']),0,10);
				$list[0]['over_inttime_his'] = substr(date('Y-m-d H:i:s',$list[0]['over_inttime']),11,8);
				
				
				$this-> assign('option_flag',$option_flag);
				$this-> assign('option_type',$option_type);
				$this -> assign('list',$list[0]);
				
			}
			
			// 输出模板
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
			
		}
		
	}
		
		//任务列表的修改--主操作
		public function updatetaskshowdata(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_updatetaskshowdata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			//接收用户选择的查询参数
			$id            = $this->_post('id');
			$update_submit = $this->_post('update_submit'); 
			
			
			$flag                 = $this->_post('flag');  //游戏的开关
			$over_inttime         = $this->_post('over_inttime');//到期时间
			$over_inttime_his     = $this->_post('over_inttime_his');//到期时间
			
			$jieshutime           = $over_inttime.' '.$over_inttime_his;
			
			$type                 = $this->_post('type');
			$downurl              = trim($this->_post('downurl'));
			$iosdownurl           = trim($this->_post('iosdownurl'));
			$showurl              = trim($this->_post('showurl'));
			
			$score                = $this->_post('score');
			
			$name                 = $this->_post('name');
			$shuoming             = $this->_post('shuoming');
			$huodonggaishu        = $this->_post('huodonggaishu');
			$huodongguize         = $this->_post('huodongguize');
				
			$liucheng_1_title         = $this->_post('liucheng_1_title');
			$liucheng_1_miaoshu       = $this->_post('liucheng_1_miaoshu');
				
			$liucheng_2_title         = $this->_post('liucheng_2_title');
			$liucheng_2_miaoshu       = $this->_post('liucheng_2_miaoshu');
				
			$liucheng_3_title         = $this->_post('liucheng_3_title');
			$liucheng_3_miaoshu       = $this->_post('liucheng_3_miaoshu');
				
			$liucheng_4_title         = $this->_post('liucheng_4_title');
			$liucheng_4_miaoshu       = $this->_post('liucheng_4_miaoshu');
				
			$liucheng_5_title         = $this->_post('liucheng_5_title');
			$liucheng_5_miaoshu       = $this->_post('liucheng_5_miaoshu');
				
			$data=array();
				
			$Model = new Model(); // 实例化User对象
			
			$data['over_inttime']  = strtotime($jieshutime);
			$data['flag']          = $flag;
			$data['name']          = $name;
			$data['shuoming']      = $shuoming;
			$data['huodonggaishu'] = $huodonggaishu;
			$data['huodongguize']  = $huodongguize;
			
			$data['type']     = $type;
			$data['downurl']  = $downurl;
			$data['iosdownurl']  = $iosdownurl;
			$data['showurl']  = $showurl;
			
			$data['score']  = $score;
				
			$data['liucheng_1_title']   = $liucheng_1_title;
			$data['liucheng_1_miaoshu'] = $liucheng_1_miaoshu;
				
			$data['liucheng_2_title']   = $liucheng_2_title;
			$data['liucheng_2_miaoshu'] = $liucheng_2_miaoshu;
				
			$data['liucheng_3_title']   = $liucheng_3_title;
			$data['liucheng_3_miaoshu'] = $liucheng_3_miaoshu;
				
			$data['liucheng_4_title']   = $liucheng_4_title;
			$data['liucheng_4_miaoshu'] = $liucheng_4_miaoshu;
				
			$data['liucheng_5_title']   = $liucheng_5_title;
			$data['liucheng_5_miaoshu'] = $liucheng_5_miaoshu;
				
				
				
			$imagedata_sql = $Model->table('xb_task')->where("id='".$id."'")->save($data);
				
			//echo $Model->getLastsql();
				
			if($imagedata_sql){
				
				echo "<script>alert('数据修改成功！'); history.go(-1);</script>";
				$this->error('数据修改成功！');
			}else{
				echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
				$this->error('数据修改失败！');
			}
			
			
		}
	
	
		//文件的上传
		Public function upload(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_upload);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			//接收该条订单的id
			$id = $this->_post('id');//任务编号
			
			import('ORG.UploadFile');
			
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt');//设置附件上传类型
			//$upload->savePath =  './Public/Uploads/task/'.date('Y-m').'/';// 设置附件上传目录
			$upload->savePath =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/';// 设置附件上传目录
				
			$upload->thumb = true;
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
			
			
			$Model = new Model(); // 实例化User对象
				
			//图片链接
			$chaxun_sql  = "select mainimage,liucheng_1_img,liucheng_2_img,liucheng_3_img,liucheng_4_img,liucheng_5_img from xb_task where id = '".$id."'";
			$chaxun_list = $Model->query($chaxun_sql);
				
				
			for($i=0;$i<=5;$i++){
				
				//图片的裁剪
				$this->hy_suofang_img($info[$i]['savepath'].$info[$i]['savename'],330);
			
				if($info[$i]['key']=='mainimage'){
					
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['mainimage']);
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['mainimage'];// 设置附件上传目录
					delfile($delurl);
						
					$mainimage=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$mainimage);
					$data['mainimage']  = $r;
					
				}else if($info[$i]['key']=='liucheng_1_img'){
						
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_1_img']);
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_1_img'];// 设置附件上传目录
					delfile($delurl);
						
					$liucheng_1_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_1_img);
					$data['liucheng_1_img']  = $r;
						
				}else if($info[$i]['key']=='liucheng_2_img'){
					
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_1_img']);
						
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_2_img'];// 设置附件上传目录
					delfile($delurl);
					
					$liucheng_2_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_2_img);
					$data['liucheng_2_img']  = $r;
					
				}else if($info[$i]['key']=='liucheng_3_img'){
						
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_3_img']);
						
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_3_img'];// 设置附件上传目录
					delfile($delurl);
					
					$liucheng_3_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_3_img);
					$data['liucheng_3_img']  = $r;
						
				}else if($info[$i]['key']=='liucheng_4_img'){
						
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_4_img']);
						
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_4_img'];// 设置附件上传目录
					delfile($delurl);
					
					$liucheng_4_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_4_img);
					$data['liucheng_4_img']  = $r;
						
				}else if($info[$i]['key']=='liucheng_5_img'){
						
					//七牛云存储上图片的删除
					delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_5_img']);
						
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_5_img'];// 设置附件上传目录
					delfile($delurl);
					
					$liucheng_5_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_5_img);
					$data['liucheng_5_img']  = $r;
				}
			
			}
			
			$imagedata_sql = $Model->table('xb_task')->where("id='".$id."'")->save($data);
			
			if($imagedata_sql){
				echo "<script>alert('上传图片修改成功！');window.location.href='".__APP__."/Tasklist/index".$yuurl."';</script>";
				$this ->success('上传图片修改成功!','__APP__/Tasklist/index'.$yuurl);
			}else{
				echo "<script>alert('上传图片修改失败！'); history.go(-1);</script>";
				$this->error('上传图片修改失败！');
			}
		
		}
		
		
		
		//图片上传
		public function uploadimg(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_uploadimg);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			//接收用户选择的查询参数
			$id            = $this->_post('id');
			$upimg_submit = $this->_post('upimg_submit');
			
			
			if($upimg_submit==''){
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			
			}else {
			
				if($id=='') {
					echo "<script>alert('非法操作！');history.go(-1);</script>";
					$this->error('非法操作！');
				}
					
				//数据库初始化
				$Model = new Model();
					
				//任务的查询
				$tasklist_sql  = "select * from xb_task where id='".$id."'";
				$list          = $Model->query($tasklist_sql);
					
					
				if(count($list)<=0) {
					echo "<script>alert('非法操作！');history.go(-1);</script>";
					$this->error('非法操作！');
				}else {
					
					if($list[0]['flag']==1){
						$list[0]['flag']='启用';
					}else if($list[0]['flag']==9){
						$list[0]['flag']='关闭';
					}
			
				//到期时间
				$list[0]['over_inttime'] = date('Y-m-d H:i:s',$list[0]['over_inttime']);
				$list[0]['create_datetime'] = date('Y-m-d H:i:s',$list[0]['create_datetime']);
				
				$list[0]['mainimage1']      = '.'.$list[0]['mainimage'];
				$list[0]['liucheng_11_img'] = '.'.$list[0]['liucheng_1_img'];
				$list[0]['liucheng_22_img'] = '.'.$list[0]['liucheng_2_img'];
				$list[0]['liucheng_33_img'] = '.'.$list[0]['liucheng_3_img'];
				$list[0]['liucheng_44_img'] = '.'.$list[0]['liucheng_4_img'];
				$list[0]['liucheng_55_img'] = '.'.$list[0]['liucheng_5_img'];
				
				$arr = unserialize(BUCKETSTR);//七牛云存储连接
				
				if(substr($list[0]['mainimage'],0,1)=='/'){
					$list[0]['mainimage'] = XMAINURL.$list[0]['mainimage'];
				}else{
					$list[0]['mainimage'] = $arr['duibao-basic'].$list[0]['mainimage'].'?imageView2/1/w/200/h/200/q/75|imageslim';
				}
				if(substr($list[0]['liucheng_1_img'],0,1)=='/'){
					$list[0]['liucheng_1_img'] = XMAINURL.$list[0]['liucheng_1_img'];
				}else{
					$list[0]['liucheng_1_img'] = $arr['duibao-basic'].$list[0]['liucheng_1_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
				}
				
				if(substr($list[0]['liucheng_2_img'],0,1)=='/'){
					$list[0]['liucheng_2_img'] = XMAINURL.$list[0]['liucheng_2_img'];
				}else{
					$list[0]['liucheng_2_img'] = $arr['duibao-basic'].$list[0]['liucheng_2_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
				}
				if(substr($list[0]['liucheng_3_img'],0,1)=='/'){
					$list[0]['liucheng_3_img'] = XMAINURL.$list[0]['liucheng_3_img'];
				}else{
					$list[0]['liucheng_3_img'] = $arr['duibao-basic'].$list[0]['liucheng_3_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
				}
				if(substr($list[0]['liucheng_4_img'],0,1)=='/'){
					$list[0]['liucheng_4_img'] = XMAINURL.$list[0]['liucheng_4_img'];
				}else{
					$list[0]['liucheng_4_img'] = $arr['duibao-basic'].$list[0]['liucheng_4_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
				}
				if(substr($list[0]['liucheng_5_img'],0,1)=='/'){
					$list[0]['liucheng_5_img'] = XMAINURL.$list[0]['liucheng_5_img'];
				}else{
					$list[0]['liucheng_5_img'] = $arr['duibao-basic'].$list[0]['liucheng_5_img'].'?imageView2/1/w/90/h/150/q/75|imageslim';
				}
				
				/* $list[0]['mainimage'] = XMAINURL.$list[0]['mainimage'];
				$list[0]['liucheng_1_img'] = XMAINURL.$list[0]['liucheng_1_img'];
				$list[0]['liucheng_2_img'] = XMAINURL.$list[0]['liucheng_2_img'];
				$list[0]['liucheng_3_img'] = XMAINURL.$list[0]['liucheng_3_img'];
				$list[0]['liucheng_4_img'] = XMAINURL.$list[0]['liucheng_4_img'];
				$list[0]['liucheng_5_img'] = XMAINURL.$list[0]['liucheng_5_img']; */
		
				$this-> assign('option_flag',$option_flag);
				$this -> assign('list',$list[0]);
			
				}
					
				// 输出模板
				$this->display();
					
				printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
					
			}
			
		}
		
		
		
		public function addtaskdata(){
			
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_addtaskdata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
				
				
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			
			//获取参数
			$flag                 = $this->_post('flag');  //游戏的开关
			$over_inttime         = $this->_post('over_inttime');//到期时间
			$over_inttime_his     = $this->_post('over_inttime_his');//到期时间
			$shijian              = $this->_post('over_inttime').' '.$this->_post('over_inttime_his');
			$name                 = $this->_post('name');
			$score                = $this->_post('score');
			
			$type                 = $this->_post('type');
			$downurl              = $this->_post('downurl');
			$iosdownurl           = $this->_post('iosdownurl');
			$showurl              = $this->_post('showurl');
			
			$shuoming             = $this->_post('shuoming');
			$huodonggaishu        = $this->_post('huodonggaishu');
			$huodongguize         = $this->_post('huodongguize');
			
			$liucheng_1_title         = $this->_post('liucheng_1_title');
			$liucheng_1_miaoshu       = $this->_post('liucheng_1_miaoshu');
			
			$liucheng_2_title         = $this->_post('liucheng_2_title');
			$liucheng_2_miaoshu       = $this->_post('liucheng_2_miaoshu');
			
			$liucheng_3_title         = $this->_post('liucheng_3_title');
			$liucheng_3_miaoshu       = $this->_post('liucheng_3_miaoshu');
			
			$liucheng_4_title         = $this->_post('liucheng_4_title');
			$liucheng_4_miaoshu       = $this->_post('liucheng_4_miaoshu');
			
			$liucheng_5_title         = $this->_post('liucheng_5_title');
			$liucheng_5_miaoshu       = $this->_post('liucheng_5_miaoshu');
			
			
			if($type=='1'){//游戏下载任务
				
				if($downurl==''&& $iosdownurl==''){
					
					$flag='9';//任务关闭
				}
				
			}else if($type=='2'){//网页浏览任务
				
				if($showurl==''){
					
					$flag='9';//任务关闭
				}
				
			}
			
			import('ORG.UploadFile');
				
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt');//设置附件上传类型
			//$upload->savePath =  './Public/Uploads/task/'.date('Y-m').'/';// 设置附件上传目录
			$upload->savePath =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/';// 设置附件上传目录
			
			$upload->thumb = true;
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
			
			$Model = new Model(); // 实例化User对象
			
			for($i=0;$i<=5;$i++){
				
				//图片的裁剪
				$this->hy_suofang_img($maxwidth=200,$info[$i]['savepath'].$info[$i]['savename']);
					
				if($info[$i]['key']=='mainimage'){
			
					$mainimage=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$mainimage);
					$data['mainimage']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
					
			
				}else if($info[$i]['key']=='liucheng_1_img'){
			
					$liucheng_1_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_1_img);
					$data['liucheng_1_img']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
					
				}else if($info[$i]['key']=='liucheng_2_img'){
			
			
					$liucheng_2_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_2_img);
					$data['liucheng_2_img']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
					
				}else if($info[$i]['key']=='liucheng_3_img'){
			
					$liucheng_3_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_3_img);
					$data['liucheng_3_img']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
			
				}else if($info[$i]['key']=='liucheng_4_img'){
			
					$liucheng_4_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_4_img);
					$data['liucheng_4_img']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
			
				}else if($info[$i]['key']=='liucheng_5_img'){
			
					$liucheng_5_img=$info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-basic',$pathname,$liucheng_5_img);
					$data['liucheng_5_img']  = $r;
					
					//本地文件的删除
					$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$pathname;// 设置附件上传目录
					delfile($delurl);
				}
					
			}
			
			$data['over_inttime']  = strtotime($shijian);
			$data['flag']          = $flag;
			$data['score']          = $score;
			$data['name']          = $name;
			$data['shuoming']      = $shuoming;
			$data['huodonggaishu'] = $huodonggaishu;
			$data['huodongguize']  = $huodongguize;
			
			$data['type']          = $type;
			$data['downurl']       = $downurl;
			$data['iosdownurl']    = $iosdownurl;
			$data['showurl']       = $showurl;
				
			$data['liucheng_1_title']   = $liucheng_1_title;
			$data['liucheng_1_miaoshu'] = $liucheng_1_miaoshu;
				
			$data['liucheng_2_title']   = $liucheng_2_title;
			$data['liucheng_2_miaoshu'] = $liucheng_2_miaoshu;
				
			$data['liucheng_3_title']   = $liucheng_3_title;
			$data['liucheng_3_miaoshu'] = $liucheng_3_miaoshu;
				
			$data['liucheng_4_title']   = $liucheng_4_title;
			$data['liucheng_4_miaoshu'] = $liucheng_4_miaoshu;
				
			$data['liucheng_5_title']   = $liucheng_5_title;
			$data['liucheng_5_miaoshu'] = $liucheng_5_miaoshu;
			
			$data['create_datetime'] = strtotime(date('Y-m-d H:i:s'));
			
				
			$imagedata_sql = $Model->table('xb_task')->add($data);
				
			/* echo $Model->getLastsql(); */
			
			 if($imagedata_sql){
				echo "<script>alert('任务添加成功！');window.location.href='".__APP__."/Tasklist/index".$yuurl."';</script>";
				$this ->success('任务添加成功!','__APP__/Tasklist/index'.$yuurl);
			}else{
				echo "<script>alert('任务添加失败！'); history.go(-1);</script>";
				$this->error('任务添加失败！');
			} 
		
		}
		
		
		//任务的删除
		public function deletetaskdata(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_deleteuserdata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			$id             = $this->_post('id');
			$delete_submit  = $this->_post('delete_submit');
			
			if($delete_submit!=''){
					
				if($id=='') {
					echo "<script>alert('非法操作！');history.go(-1);</script>";
					$this -> error('非法操作！');
				}
					
				//数据库初始化
				$Model = new Model();
					
				
				//上传图片的删除
				$chaxun_sql  = "select mainimage,liucheng_1_img,liucheng_2_img,liucheng_3_img,liucheng_4_img,liucheng_5_img from xb_task where id = '".$id."'";
				$chaxun_list = $Model->query($chaxun_sql);
				
				
				if($chaxun_list[0]['mainimage']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['mainimage']);
					
					if(substr($chaxun_list[0]['mainimage'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['mainimage'];
						delfile($delurl);
					}else{
						//本地文件的删除
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['mainimage'];// 设置附件上传目录
						delfile($delurl);
					}
				}
				
				if($chaxun_list[0]['liucheng_1_img']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_1_img']);
					
					if(substr($chaxun_list[0]['liucheng_1_img'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['liucheng_1_img'];
						delfile($delurl);
					}else{
						//本地文件的删除
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_1_img'];// 设置附件上传目录
						delfile($delurl);
					}
					
				}
				
				if($chaxun_list[0]['liucheng_2_img']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_2_img']);
					
					//本地文件的删除
					if(substr($chaxun_list[0]['liucheng_2_img'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['liucheng_2_img'];
						delfile($delurl);
					}else{
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_2_img'];// 设置附件上传目录
						delfile($delurl);
					}
				}
				
				if($chaxun_list[0]['liucheng_3_img']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_3_img']);
					
					//本地文件的删除
					if(substr($chaxun_list[0]['liucheng_3_img'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['liucheng_3_img'];
						delfile($delurl);
					}else{
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_3_img'];// 设置附件上传目录
						delfile($delurl);
					}
				}
				
				if($chaxun_list[0]['liucheng_4_img']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_4_img']);
					
					//本地文件的删除
					if(substr($chaxun_list[0]['liucheng_4_img'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['liucheng_4_img'];
						delfile($delurl);
					}else{
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_4_img'];// 设置附件上传目录
						delfile($delurl);
					}
				}
				
				if($chaxun_list[0]['liucheng_5_img']!=''){
					
					//七牛云存储上图片的删除
					$r=delqiuniu('duibao-basic',$chaxun_list[0]['liucheng_5_img']);
					
					//本地文件的删除
					if(substr($chaxun_list[0]['liucheng_5_img'],0,1)=='/'){
						$delurl=XMAINPATH.$chaxun_list[0]['liucheng_5_img'];
						delfile($delurl);
					}else{
						$delurl =  XMAINPATH.'Public/Uploads/task/'.date('Y-m').'/'.$chaxun_list[0]['liucheng_5_img'];// 设置附件上传目录
						delfile($delurl);
					}
				}
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('xb_task') -> where("id='".$id."'") -> delete();
				
				if($ret) {
					echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Tasklist/index".$yuurl."';</script>";
					$this -> success('数据删除成功!','__APP__/Tasklist/index'.$yuurl);
				}else {
					echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据删除失败，系统错误!');
				}
			
			}
		}
		
		
		public function apkupload(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_apkupload);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			$id = $this->_post('id');
			
			$this -> assign('id',$id);
			
			$this->display();
			
			
		}
		//安装包的上传
		public function apkuploaddata(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_apkuploaddata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
				
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			$id = $this->_post('id');
			
			import('ORG.UploadFile');
			
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			$upload->savePath =  './Public/Uploads/gameapk/'.date('Y-m-d').'/';// 设置附件上传目录
				
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
			/*Array ( [0] => Array ( [name] => 123.apk 
			 * [type] => application/octet-stream 
			 * [size] => 6 [key] => apkurl 
			 * [extension] => apk 
			 * [savepath] => ./Public/Uploads/gameapk/2017-02-18/ 
			 * [savename] => 58a80ecccef6e.apk 
			 * [hash] => 9cdae6e567d44466ad61c1c9f98bfd93 ) ) 
			 *  */
			$data=array();
			
			
			for ($i=0;$i<=1;$i++){
				
				if($info[$i]['key']=='apkurl'){
					$apkurl = $info[$i]['savepath'].$info[$i]['savename'];
					$data['downurl'] = URL_APK.str_replace('./','/',$apkurl);
				}
				
				if($info[$i]['key']=='iosurl'){
					$iosurl = $info[$i]['savepath'].$info[$i]['savename'];
					$data['iosdownurl'] = URL_APK.str_replace('./','/',$iosurl);
				}
				
				
			}
			
				
			$Model = new Model(); // 实例化User对象
			
			$ret = $Model -> table('xb_task') -> where("id='".$id."'") -> save($data);
			
			if($ret) {
					echo "<script>alert('apk上传成功！');window.location.href='".__APP__."/Tasklist/index".$yuurl."';</script>";
					$this -> success('apk上传成功!','__APP__/Tasklist/index'.$yuurl);
				}else {
					echo "<script>alert('apk上传失败!');history.go(-1);</script>";
					$this -> error('apk上传失败!');
				}
			
			
		}
		
		
		//图片的删除
		public function ajax_delimg(){
				
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_ajax_delimg);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//"id="+id+"&imagename="+imagename+"&picname"+picname,
			$id        = $this->_post('id');
			$imagename = $this->_post('imagename');
			$picname   = $this->_post('picname');
			
				
			if($id!=''&&$imagename!=''){
		
				$Model = new Model();
		
				$chaxun_sql  = "select mainimage,liucheng_1_img,liucheng_2_img,liucheng_3_img,liucheng_4_img,liucheng_5_img from xb_task where id = '".$id."'";
				$chaxun_list = $Model->query($chaxun_sql);
		
		
				if($picname=='mainimage'){
						
					$setdata = "mainimage=''";
					$filepath    = '.'.$chaxun_list[0]['mainimage'];
						
				}else if($picname=='liucheng_1_img'){
						
					$setdata = "liucheng_1_img=''";
					$filepath    = '.'.$chaxun_list[0]['liucheng_1_img'];
						
				}else if($picname=='liucheng_2_img'){
						
					$setdata = "liucheng_2_img=''";
					$filepath    = '.'.$chaxun_list[0]['liucheng_2_img'];
						
				}else if($picname=='liucheng_3_img'){
						
					$setdata = "liucheng_3_img=''";
					$filepath    = '.'.$chaxun_list[0]['liucheng_3_img'];
						
				}else if($picname=='liucheng_4_img'){
						
					$setdata = "liucheng_4_img=''";
					$filepath    = '.'.$chaxun_list[0]['liucheng_4_img'];
						
				}else if($picname=='liucheng_5_img'){
						
					$setdata = "liucheng_5_img=''";
					$filepath    = '.'.$chaxun_list[0]['liucheng_5_img'];
				}
		
				if(file_exists($filepath)){
					unlink($filepath);
				}
		
				//表中数据的清空
				//$imagedata_sql = $Model->table('xb_task')->where("id='".$id."'")->save($setdata);
				$imagedata_sql = "update xb_task set $setdata where id='".$id."'";
				$r = $Model->execute($imagedata_sql);
				if($imagedata_sql){
						
					echo 'delsuc';
						
				}else{
					echo 'delerr';
						
				}
		
		
		
			}
				
				
		}
		
		
		
		//图片的裁剪
		private function hy_suofang_img($picpathname,$maxwidth=330) {
		
			if(!file_exists($picpathname)) {
				return false;
			}
		
			$imginfo   = getimagesize($picpathname);
			$imgwidth  = isset($imginfo[0])?$imginfo[0]:330;
			$imgheight = isset($imginfo[1])?$imginfo[1]:600;
		
			$im = null;
		
			switch ($imginfo[2]) {
				case 1:
					$im = imagecreatefromgif($picpathname);
					break;
				case 2:
					$im = imagecreatefromjpeg($picpathname);
					break;
				case 3:
					$im = imagecreatefrompng($picpathname);
					break;
			}
		
			if($im===null) {
				return false;
		
			}
		
			if($imgwidth>$maxwidth) {
				//执行尺寸压缩
				//计算压缩后的宽和高
				$newwidth = $maxwidth; //新的宽度
				$newheight = intval($newwidth * $imgheight / $imgwidth);//等比缩放图片高度 变整型
		
		
			}else {
				//不执行尺寸压缩
				$newwidth = $imgwidth;
				$newheight = $imgheight;
		
		
			}
		
		
			$newim = imagecreatetruecolor($newwidth,$newheight);
			imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$imgwidth,$imgheight);
		
			//调整压缩比
			imagejpeg($newim,$picpathname,70);
			imagedestroy($newim);
		
			return true;
		
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