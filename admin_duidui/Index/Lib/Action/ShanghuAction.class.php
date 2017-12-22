<?php
/*
 * 商户开户管理
 */
class ShanghuAction extends Action{
	
	
	private $lock_index                = '9751';
	private $lock_chakandata           = '975';
	private $lock_chakanshow           = '975';
	
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
		$bianhao      = $this->_get('bianhao');
		$lianxiren      = $this->_get('lianxiren');
		$status      = $this->_get('status');
	
		//是否启用
		$flag_arr = array(
				'1' => '开启',
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
				'3' => '审核驳回',
				'2' => '审核成功',
				'1' => '等待审核',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
	
	
		$this->assign('optionflag',$optionflag);
		$this->assign('optionstatus',$optionstatus);
		$this->assign('bianhao',$bianhao);
		$this->assign('lianxiren',$lianxiren);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
	
		//数据库的初始化
		$Model = new Model();
	
		//生成where条件判断字符串
		$sql_where = " ";
	
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
			$sql_where .= "checkstatus='".$status."' and ";
		}
		if($bianhao!='') {
			$sql_where .= "id='".$bianhao."' and ";
		}
		if($lianxiren!='') {
			$sql_where .= "lianxiren like '%".$lianxiren."%' and ";
		}
	
		$sql_where = rtrim($sql_where,'and ');
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_site')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_site')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
	
		//数据的读出
		foreach ($list as $keys=>$vals){
				
			if($list[$keys]['flag']=='1'){
				$list[$keys]['flags']='<font style="background-color:#00EA00">&nbsp;&nbsp;开启&nbsp;&nbsp;</font>';
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flags']='<font style="background-color:#FF1700">&nbsp;&nbsp;关闭&nbsp;&nbsp;</font>';
			}
				
			if($list[$keys]['checkstatus']=='1'){
				$list[$keys]['checkstatus']='<font style="background-color:#FFA8B8">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
			}else if($list[$keys]['checkstatus']=='2'){
				$list[$keys]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($list[$keys]['checkstatus']=='3'){
				$list[$keys]['checkstatus']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核驳回&nbsp;&nbsp;</font>';
			}
				
		}
			
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	
	}
	
	
	//数据的查看
	public function chakanshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chakanshow);
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
		
				$sqldata = "select * from shop_site where id='".$id."'";
				$listdata = $Model->query($sqldata);
		
		
				//是否启用
				$flag_arr = array(
						'1' => '开启',
						'9' => '关闭',
		
				);
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($listdata[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
		
		
					
				$tiaozhuantype = array(
						'3' => '审核驳回',
						'2' => '审核成功',
						'1' => '等待审核',
				);
					
				foreach($tiaozhuantype as $keyc => $valc) {
					$optioncheck .= '<option value="'.$keyc.'" ';
					if($listdata[0]['checkstatus']==$keyc) { $optioncheck .= ' selected="selected" '; }
					$optioncheck .= '>'.$valc.'</option>';
				}
				$this->assign('optioncheck',$optioncheck);
		
				
				if($listdata[0]['checkstatus']=='1'){
					$listdata[0]['checkstatuss']='<font style="background-color:#FFA8B8">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='2'){
					$listdata[0]['checkstatuss']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='3'){
					$listdata[0]['checkstatuss']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核驳回&nbsp;&nbsp;</font>';
				}
				
				if($listdata[0]['flag']=='1'){
					$listdata[0]['flag']='<font style="background-color:#00EA00">&nbsp;&nbsp;开启&nbsp;&nbsp;</font>';
				}else if($listdata[0]['flag']=='9'){
					$listdata[0]['flag']='<font style="background-color:#FF1700">&nbsp;&nbsp;关闭&nbsp;&nbsp;</font>';
				}
				
				
			}
		
			$this->assign('list',$listdata[0]);
		}
		
		$this->display();
		
		
		
	}
	
	
	//审核，驳回
	public function chakandata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chakandata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$tijiao_submit = $this->_post('tijiao_submit');
		$id            = $this->_post('id');
		$checkstatus   = $this->_post('checkstatus');
		$flag          = $this->_post('flag');
		$remark        = $this->_post('remark');
		
		$Model = new Model();
		
		if($tijiao_submit!=''){
			
			$updatesql = "update shop_site set checkstatus='".$checkstatus."',flag='".$flag."',remark='".$remark."' where id='".$id."'";
			$updatelist = $Model->execute($updatesql);
			
			if($updatelist){
				
				$sqldata = "select * from shop_site where id='".$id."' ";
				$sqllist = $Model->query($sqldata);
				
				if($sqllist[0]['checkstatus']=='2'){
					
					$mailto = $sqllist[0]['email'];
					$body = '亲爱的'.$sqllist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现已通过审核，请使用账号'.$sqllist[0]['lianxiren'].' 初始密码123456进行登录查看！';
					
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
					
					echo "<script>alert('审核成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('审核成功！','__APP__/Shanghu/index'.$yuurl);
					
				}else if($sqllist[0]['checkstatus']=='3'){
					
					$mailto = $sqllist[0]['email'];
					$body = '亲爱的'.$sqllist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现未通过审核，请登录查看完善资料！';
					
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
					
					echo "<script>alert('驳回成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('驳回成功！','__APP__/Shanghu/index'.$yuurl);
				}else{
					echo "<script>alert('操作成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('操作成功！','__APP__/Shanghu/index'.$yuurl);
				}
			}else{
				echo "<script>alert('操作失败！'); history.go(-1);</script>";
				$this ->success('操作失败！');
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