<?php
/*
 * 发布数据的审核
 */
class FabulistAction extends Action {
	
	private $lock_index                = '9751';
	private $lock_updateshow           = '975';
	private $lock_updatedata           = '975';
	private $lock_adddata           = '975';
	private $lock_addshow           = '975';
	
	
	//广告的展示
	public function index(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//接收用户选择的查询参数
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$flag_s      = $this->_get('flag_s');
		$status    = $this->_get('status');
		
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
		
		//审核状态
		$status_arr = array(
				'11' => '已审核发布',
				'99' => '等待审核',
				'9'  => '审核驳回',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
		
		
		$this->assign('optionflag',$optionflag);
		$this->assign('optionstatus',$optionstatus);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		
		//数据库的初始化
		$Model = new Model();
		
		//生成where条件判断字符串
		$sql_where = " faflag=1 and ";
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		
		if($date_s!='') {
			$sql_where .= "create_datetime>='".$date_s." 00:00:00 ' and ";
		}
		
		if($date_e!='') {
			$sql_where .= "create_datetime<='".$date_e." 23:59:59' and ";
		}
		
		if($status!='') {
			$sql_where .= "shstatus='".$status."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('z_fabulist')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('z_fabulist')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		//数据的读出
		foreach ($list as $keys=>$vals){
			
			if($list[$keys]['flag']=='1'){
				
				$list[$keys]['flag']='<font style="background-color:#00EA00">&nbsp;&nbsp;开&nbsp;&nbsp;启&nbsp;&nbsp;</font>';
				
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flag']='<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}
			
			if($list[$keys]['shstatus']=='99'){
				$list[$keys]['shstatuss']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
			}else if($list[$keys]['shstatus']=='11'){
				$list[$keys]['shstatuss']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($list[$keys]['shstatus']=='9'){
				$list[$keys]['shstatuss']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
			
			$list[$keys]['over_datetime'] = substr($list[$keys]['over_datetime'],0,10);
			
			$list[$keys]['picurl'] = hy_qiniuimgurl('duibao-find',$list[$keys]['picurl'],'100','100');
			
		}
			
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//数据的修改
	public function updateshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$id = $this->_post('id');//发布商品的id
		$update_submit = $this->_post('update_submit');
		
		if($update_submit!=''){
			
			if($id==''){
				echo "<script>alert('非法操作！');history(-1);</script>";
				$this ->error('非法操作！');
			}else{
				//数据库的初始化
				$Model = new Model();
				
				$sqldata = "select * from z_fabulist where id='".$id."'";
				$listdata = $Model->query($sqldata);
				
				
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
				
				//是否启用
				$maintype_arr = array(
						'1' => '休闲娱乐',
						'2' => '生活服务',
						'3' => '美食',
				
				);
				
				$optionmaintype .= '<option value=""></option> ';
				foreach($maintype_arr as $keyc => $valc) {
					$optionmaintype .= '<option value="'.$valc.'" ';
					if($listdata[0]['maintype']==$valc) { $optionmaintype .= ' selected="selected" '; }
					$optionmaintype .= '>'.$valc.'</option>';
				}
				$this->assign('optionmaintype',$optionmaintype);
				
				
			
				$tiaozhuantype = array(
						'11' => '审核成功',
						'9' => '失败驳回',
						'99' => '等待审核',
				);
					
				foreach($tiaozhuantype as $keyc => $valc) {
					$optioncheck .= '<option value="'.$keyc.'" ';
					if($listdata[0]['shstatus']==$keyc) { $optioncheck .= ' selected="selected" '; }
					$optioncheck .= '>'.$valc.'</option>';
				}
				$this->assign('optioncheck',$optioncheck);
				
			//	echo substr($listdata[0]['picurl'],20);
				//$listdata[0]['picurl'] = 'http://xbapp.xinyouxingkong.com'.substr($listdata[0]['picurl'],20);
				
				if($listdata[0]['shstatus']=='99'){
					$listdata[0]['shstatus']=='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
				}else if($listdata[0]['shstatus']=='9'){
					$listdata[0]['shstatus']=='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
				}else if($listdata[0]['shstatus']=='11'){
					$listdata[0]['shstatus']=='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
				}
				
				$listdata[0]['picurl'] = hy_qiniuimgurl('duibao-find',$listdata[0]['picurl'],'100','100');
			}
			
			$this->assign('list',$listdata[0]);
		}
		
		$this->display();
		
	}
	
	
	//修改数据的添加
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
		$shstatus = $this->_post('shstatus');
		$type  = $this->_post('type');
		$maintype = $this->_post('maintype');
		$childtype = $this->_post('childtype');
		$title     = $this->_post('title');
		$yuanprice    = $this->_post('yuanprice');
		$nowprice    = $this->_post('nowprice');
		$yilingcon    = $this->_post('yilingcon');
		$address    = $this->_post('address');
		$phone = $this->_post('phone');
		$shopname = $this->_post('shopname');
		$discount = $this->_post('discount');
		$uupdate_submit = $this->_post('uupdate_submit');
		
		
		$Model = new Model();
		
		
		$data_sql = "select picurl,userid from z_fabulist where id='".$id."'";
		$data_list = $Model->query($data_sql);
		
		$fauserid = $data_list[0]['userid'];
		
		$data=array();
		
		import('ORG.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		//$upload->savePath =  './Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
		$upload->savePath =  XMAINPATH.'Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
		
		$upload->thumb = false;
		$upload->thumbMaxHeight = '300';
		
		
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777);
		}
		
		$infof  =   $upload->upload();
		
		if($infof===true){
			$info =  $upload->getUploadFileInfo();
			$pathname = $info[0]['savepath'].$info[0]['savename'];
			$savename = $info[0]['savename'];
			
			//云存储图片上传
			$picurl=upload_qiniu('duibao-find',$pathname,$savename);
			
			$data['picurl']          = $picurl;
			
			//本地文件的删除
			delfile($pathname);
			//七牛云上图片删除
			delete_qiniu('duibao-find',$data_list[0]['picurl']);
			
		}
		
		$data['flag'] = $flag;
		$data['shstatus']  = $shstatus;
		$data['type']  = $type;
		$data['maintype']  = $maintype;
		$data['childtype'] = $childtype;
		$data['title'] = $title;
		$data['yuanprice'] = $yuanprice;
		$data['nowprice'] = $nowprice;
		$data['yilingcon'] = $yilingcon;
		$data['address'] = $address;
		$data['phone'] = $phone;
		$data['discount'] = $discount;
		$data['shopname'] = $shopname;
		
		if($shstatus=='99'){//等待审核
			
		}else{
			
			if($shstatus=='9'){//驳回
				$message1 = '您发布的优惠券未通过审核，请重新发送。';
				
				//推送是我记录
				$tuisongsql = "insert into xb_user_tuisong (userid,type,status,message,create_inttime)
							values ('".$fauserid."','1','2','".$message2."','".time()."')";
				$tuisonglist = $Model->execute($tuisongsql);
					
			}else if($shstatus=='11'){//审核通过
				
				$message1 = '您的优惠券通过审核，已发布在‘附近’中。根据‘优惠信息发布规则’奖励您'.$xianbing.'馅饼‘，您可以在我的馅饼中查看。';
				
				if($yuanprice>0 && $nowprice>0){
					$discount = round($nowprice/$yuanprice,2);//新的折扣,保留两位小数
				}else if($discount>0){
					$discount = $discount/10;
				}
				
				if($discount==0){//折扣为零时，商品免费
					$xianbing = '300';
				}else{
					$xianbing = round(50-50*$discount);
				}
				
				//是否推送判断--每个用户前三次会发布积分
				$usernumsql = "select count(*) as num from z_fabulist where userid='".$fauserid."' and faflag='1' and shstatus='11'
									and create_datetime>='".date('Y-m-d 00:00:00')."' and create_datetime<='".date('Y-m-d 23:59:59')."' ";
					
				$usernumlist = $Model->query($usernumsql);
					
				if($usernumlist[0]['num']<='3'){
				
					//用户积分的增加
					$updatescoresql = "update xb_user set keyong_jifen=keyong_jifen+'".$xianbing."' where id='".$fauserid."'";
					$updatescorelist = $Model->execute($updatescoresql);
				
					//积分记录
					$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,getdescribe,gettime) values
							('".$fauserid."','3','1','1','".$xianbing."','".$message1."','".time()."')";
					$scorelist = $Model->execute($scoresql);
				
					//推送是我记录
					$tuisongsql = "insert into xb_user_tuisong (userid,type,status,message,create_inttime)
							values ('".$fauserid."','1','2','".$message1."','".time()."')";
					$tuisonglist = $Model->execute($tuisongsql);
				}
			}
			
			//获取用户极光id
			$jiguangsql  = "select jiguangid from xb_user where id='".$fauserid."'";
			$jiguanglist = $Model->query($jiguangsql); 
			
			$this ->func_jgpush($jiguanglist[0]['jiguangid'],$message1);
		}
		
		$imagedata_sql = $Model->table('z_fabulist')->where ("id='".$id."'")->save($data);
		
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Fabulist/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Fabulist/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
		
		
	}
	
	
		
		//发布
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
			
			
			
			$tiaozhuantype = array(
					'11' => '审核成功',
					'9' => '失败驳回',
					'99' => '等待审核',
			);
				
			foreach($tiaozhuantype as $keyc => $valc) {
				$optioncheck .= '<option value="'.$keyc.'" ';
				$optioncheck .= '>'.$valc.'</option>';
			}
			$this->assign('optioncheck',$optioncheck);
			
			
			$this->display();
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
		}
		
		
		//数据的添加
		public function adddata(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_adddata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			$flag = $this->_post('flag');
			$status = $this->_post('status');
			$date_s = $this->_post('date_s');
			$type   = $this->_post('type');
			$maintype = $this->_post('maintype');
			$childtype = $this->_post('childtype');
			$title     = $this->_post('title');
			$content   = $this->_post('content');
			$yuanprice = $this->_post('yuanprice');
			$nowprice  = $this->_post('nowprice');
			$address   = $this->_post('address');
			$lat   = $this->_post('lat');
			$lng   = $this->_post('lng');
			$add_submit = $this->_post('add_submit');
			
			
			//图片的上传
			import('ORG.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			//$upload->savePath =  './Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
			$upload->savePath =  XMAINPATH.'/Public/Uploads/advertisement/'.date('Y-m').'/';// 设置附件上传目录
			
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
			
			$savename=$info[0]['savename'];
			$savepath = $info[0]['savepath'].$info[0]['savename'];
			$picurl=upload_qiniu('duibao-find',$savepath,$savename);
				
			//本地文件的删除
			delfile($savepath);
			
			//数据库的初始化
			$Model = new Model();
			
			$data = array();
			$data['faflag'] = '1';
			$data['flag'] = $flag;
			$data['shstatus']  = $status;
			$data['type']  = $type;
			$data['over_datetime']  = $date_s;
			$data['maintype'] = $maintype;
			$data['childtype'] = $childtype;
			$data['title'] = $title;
			$data['content'] = $content;
			$data['yuanprice'] = $yuanprice;
			$data['nowprice'] = $nowprice;
			$data['address'] = $address;
			$data['picurl'] = $picurl;
			$data['lat'] = $lat;
			$data['lng'] = $lng;
			$data['flag']='1';
			$data['hyflag']='1';
			$data['create_datetime'] = date('Y-m-d h:i:s');
			
			//把接收的数据存入到文件中
			$imagedata_sql = $Model->table('z_fabulist')->add($data);
			//echo $Model->getLastsql();exit;
			
			
			if($imagedata_sql){//说明文件上传成功
			
				echo "<script>alert('发布成功！');window.location.href='".__APP__."/Fabulist/index".$yuurl."';</script>";
				$this ->success('发布成功!','__APP__/Fabulist/index'.$yuurl);
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this ->success('发布失败!');
			}
			
		}
		
		
		//极光推送
		private function func_jgpush($jiguangid,$messagee){
		
			import('ORG.JiPush');
			$push = new JiPush();// 实例化上传类
			
			//极光推送的设置
			$m_type = '';//推送附加字段的类型
			$m_txt = '';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
			$m_time = '86400';//离线保留时间
			$receive = array('alias'=>array($jiguangid));//别名
			$content = $messagee;
			$result = $push->push($receive,$content,$m_type,$m_txt,$m_time);
		
			if($result){
				$res_arr = json_decode($result, true);
		
				if(isset($res_arr['error'])){                       //如果返回了error则证明失败
					echo $res_arr['error']['message'];          //错误信息
					$error_code=$res_arr['error']['code'];             //错误码
					switch ($error_code) {
						case 200:
							$message= '发送成功！';
							break;
						case 1000:
							$message= '失败(系统内部错误)';
							break;
						case 1001:
							$message = '失败(只支持 HTTP Post 方法，不支持 Get 方法)';
							break;
						case 1002:
							$message= '失败(缺少了必须的参数)';
							break;
						case 1003:
							$message= '失败(参数值不合法)';
							break;
						case 1004:
							$message= '失败(验证失败)';
							break;
						case 1005:
							$message= '失败(消息体太大)';
							break;
						case 1008:
							$message= '失败(appkey参数非法)';
							break;
						case 1020:
							$message= '失败(只支持 HTTPS 请求)';
							break;
						case 1030:
							$message= '失败(内部服务超时)';
							break;
						default:
							$message= '失败(返回其他状态，目前不清楚额，请联系开发人员！)';
							break;
					}
				}else{
					$message="ok";
				}
			}else{//接口调用失败或无响应
				$message='接口调用失败或无响应';
			}
		
			//return $message;
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