<?php
/*
 * 每月礼包
 */
class MonthprizeAction extends Action {
	
	private $lock_index                = '9751';
	private $lock_updateprizeshow      = '975';
	private $lock_updateprizeshowdata  = '975';
	private $lock_addduihuanma         = '975';
	private $lock_addduihuanmadata     = '975';
	
	public function index(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//换取相应的参数
		$flag_s = $this->_get('flag_s');//兑换码的使用状态
		$type_s = $this->_get('type_s');//兑换码的类型
		$addtime = $this->_get('addtime');//兑换码添加时间
		
		//是否启用
		$flag_arr = array(
				'1' => '已使用',
				'9' => '未使用',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		$this->assign('type_s',$type_s);
		
		//数据库的初始化
		$Model = new Model();
	
		//生成排序字符串数据
		$sql_field = " flag,title1, shengming,title2,shuoming,title3,guize,title4,fangfa,picurl ";
	
		import('ORG.Page');// 导入分页类
	
		//执行SQL查询语句
		$list1  = $Model -> table('xb_config')
						-> where('flag=2')
						-> field($sql_field)
						-> select();
	
		$list1[0]['picurl'] = hy_qiniuimgurl('duibao-basic',$list1[0]['picurl'],'150','50');
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		//****************************************************************************************************8
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($type_s!='') {
			$sql_where .= " type='".$type_s."' and ";
		}
		if($addtime!='') {
			$sql_where .= " addtime='".$addtime."' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		//生成排序字符串数据
		$sql_order = " id asc ";
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('libaocode')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('libaocode')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已使用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;未使用&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
		
		}
		
		
		$this -> assign('list',$list1);
		$this -> assign('list1',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	//修改页面
	public function updateprizeshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateprizeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$flag = $this->_post('flag');
		$update_submit = $this->_post('update_submit');
		
		
		if($update_submit!=''){
				
		
			$Model = new Model();
		
			$sqlpic = "select * from xb_config where flag='".$flag."'";
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
	
	
	//修改
	public function updateprizeshowdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateprizeshowdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$flag        = $this->_post('flag');
		$title1      = $this->_post('title1');
		$shengming   = $this->_post('shengming');
		$title2     = $this->_post('title2');
		$shuoming    = $this->_post('shuoming');
		$title3     = $this->_post('title3');
		$guize    = $this->_post('guize');
		$title4     = $this->_post('title4');
		$fangfa    = $this->_post('fangfa');
		
		$update_submit = $this->_post('update_submit');
		
		$data=array();
		
		$Model = new Model();
		
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath =  XMAINPATH.'Public/Uploads/libao/'.date('Y-m').'/';// 设置附件上传目录
		
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
			
			$data['picurl']      = $r;
		}
		
		if($apkurl!=''){
		
			$data_sql = "select picurl from xb_config where flag='".$flag."'";
			$data_list = $Model->query($data_sql);
			
			//七牛云上图片删除
			delete_qiniu('duibao-basic',$data_list[0]['picurl']);
		}
		
		$data['title1']     = $title1;
		$data['shengming']  = $shengming;
		$data['title2']     = $title2;
		$data['shuoming']    = $shuoming;
		$data['title3']      = $title3;
		$data['guize']       = $guize;
		$data['title4']      = $title4;
		$data['fangfa']      = $fangfa;
		$data['createtime']   = date('Y-m-d h:i:s');
			
			
		$imagedata_sql = $Model->table('xb_config')->where ("flag='".$flag."'")->save($data);
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Monthprize/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Monthprize/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
		
	}
	
	
//兑换码的添加
	public function addduihuanma(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addduihuanma);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//是否启用
		$flag_arr = array(
				'9' => '未使用',
				'1' => '已使用',
		);
		/* $optionflag = '<option value=""></option>'; */
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//兑换码的添加
	public function addduihuanmadata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addduihuanmadata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//获取相应测参数
		$flag = $this->_post('flag');
		$type = $this->_post('type');
		$addtime   = $this->_post('addtime');
		$duihuanma = $_POST['duihuanma'];
		
		if($type==''){
			echo "<script>alert('兑换码类型不能为空！');history.go(-1);</script>";
			$this -> error('兑换码类型不能为空！');
		}
		
		if($duihuanma==''){
			
			echo "<script>alert('兑换码不能为空！');history.go(-1);</script>";
			$this -> error('兑换码不能为空！');
		}
		
		$uniquedh = array();
		
		//兑换码字符串的分割
		$duihuanma_arr = explode("\n",$duihuanma);
		
		foreach($duihuanma_arr as $vald) {
			$vald = trim($vald);
			if($vald!='') {
				$uniquedh[$vald] = $vald;
			}
		}
		
		//重复兑换码个数
		$repeatcode = 0;
		$norepeatcode=0;
		
		//数据库的初始化
		$Model = new Model();
		
		foreach ($uniquedh as $vals){
			$dm = trim($vals);
			
			$sel = "select id from libaocode where duihuanma='".$dm."' and type='".$type."'";
			$sel_list = $Model->query($sel);
			
			if(count($sel_list)>0){
				
				$repeatcode++;
				
			}else{
				$insert_sql = "insert into libaocode(type,flag,duihuanma,addtime)
					values('".$type."','".$flag."','".$dm."','".$addtime."');";
				$Model->execute($insert_sql);
				$norepeatcode++;
			}
			
		}
		//兑换码的总个数
		$num=$repeatcode+$norepeatcode;
		
		if($num>0) {
			echo "<script>alert('数据添加成功,兑换码总数".$num."\n重复兑换码有".$repeatcode."\n非重复的兑换码正常插入的验证码有".$norepeatcode."');window.location.href='".__APP__."/Monthprize/index".$yuurl."';</script>";
			$this ->success('数据添加成功,兑换码总数'.$num.',重复兑换码有'.$repeatcode.',非重复的兑换码正常插入的兑换码有'.$norepeatcode,'__APP__/Monthprize/index'.$yuurl);
				
		}else {
			echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
			$this -> error('数据添加失败，系统错误!');
		}
		
	}
	
	
	//兑换码的删除
	public function deletedhdata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletedhdata);
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
				
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('libaocode') -> where("id='".$id."'") -> delete();
				
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Monthprize/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Monthprize/index'.$yuurl);
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