<?php
/*
 * 用户信息管理
 */
class UserAction extends Action {
	
	
	//用户信息列表
	public function userinfo(){
		
		//数据库的初始化
		$Model = new Model();
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//用户信息的查询
		$userinfosql  = "select * from shop_site where id='".$siteid."' and flag=1 and pay='1' ";
		$userinfolist = $Model->query($userinfosql);
		
		if($userinfolist[0]['id']>0){
			
			if($userinfolist[0]['checkstatus']=='1'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#EDDC1D">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='2'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='3'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#FB3C16">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
			
		}
		
		$this->assign('list',$userinfolist);
		
		$this->display();
		
	}
	
	
	//用户信息的查看
	public function usershow(){
		
		//获取用户的编号
		$id = $this->_post('id');
		
		$Model = new Model();
		
		//获取用户的信息
		$userinfosql  = "select * from shop_site where id='".$id."'";
		$userinfolist = $Model->query($userinfosql);
		
		if($userinfolist[0]['id']>0){
			
			if($userinfolist[0]['checkstatus']=='1'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#EDDC1D">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='2'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='3'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#FB3C16">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
			
		}
		$userinfolist[0]['bussinelicence1'] = hy_qiniuimgurl('duibao-business',$userinfolist[0]['bussinelicence1'],'800','800',$canshu=true);

		$this->assign('list',$userinfolist[0]);
		
		$this->display();
		
	}
	
	
	//编辑用户信息
	public function useredit(){
		
		$id = $this->_post('id');
		
		//数据库的初始化
		$Model = new Model();
		
		//获取用户的信息
		$userinfosql  = "select * from shop_site where id='".$id."'";
		$userinfolist = $Model->query($userinfosql);
		
		if($userinfolist[0]['id']>0){
				
			if($userinfolist[0]['checkstatus']=='1'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#EDDC1D">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='2'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['checkstatus']=='3'){
				$userinfolist[0]['checkstatus']='<font style="background-color:#FB3C16">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
			$userinfolist[0]['bussinelicence1'] = hy_qiniuimgurl('duibao-business',$userinfolist[0]['bussinelicence1'],$width='200',$height='200');
		}
		
		$this->assign('list',$userinfolist[0]);
		
		$this->display();
		
	}
	
	
	//用户信息修改入口
	public function usereditdata(){
		
		//获取相应的参数
		$id = $this->_post('id');
		$lianxiren = $this->_post('lianxiren');
		$phone     = $this->_post('phone');
		$email     = $this->_post('email');
		$company   = $this->_post('company');
		$address   = $this->_post('address');
		$zitiaddress = $this->_post('zitiaddress');
		$uupdate_submit = $this->_post('uupdate_submit');
		
		$data=array();
		
		if($uupdate_submit!=''){
			
			if($lianxiren==''){
				echo "<script>alert('联系人不能为空！');history.go(-1);</script>";
				$this -> error('联系人不能为空！');
			}
			
			if($phone=='' || !is_numeric($phone)){
				echo "<script>alert('联系方式不能为空！');history.go(-1);</script>";
				$this -> error('联系方式不能为空！');
			}
			
			$patrn='/^([a-zA-Z0-9]+[_|_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|_|.|-]?)*[a-zA-Z0-9]+.[a-zA-Z]{2,3}$/';
			
			if(!preg_match($patrn,$email)){
				echo "<script>alert('邮箱格式不正确！');history.go(-1);</script>";
				$this -> error('邮箱格式不正确！');
			}
			
			if($company==''){
				echo "<script>alert('公司名称不能为空！');history.go(-1);</script>";
				$this -> error('公司名称不能为空！');
			}
			
			if($address==''){
				echo "<script>alert('公司地址不能为空！');history.go(-1);</script>";
				$this -> error('公司地址不能为空！');
			}
			
			
			//图片的上传
			import('ORG.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			$upload->savePath = XMAINPATH.'/Public/Uploads/bussiness/';// 设置附件上传目录
			
			$upload->thumb = false;
			$upload->thumbMaxHeight = '300';
			
			//判断该目录是否存在
			if(!is_dir($upload->savePath)){
				mkdir($upload->savePath,0777);
			}
			
			$infof  =   $upload->upload();
			
			$Model = new Model();
			
			if($infof===true){
				//七牛上修改之前图片的删除
				$del_sql = "select bussinelicence1 from shop_site where id='".$id."'";
				$del_list = $Model->query($del_sql);
				
				delete_qiniu('duibao-business',$del_list[0]['bussinelicence1']);
				
				$info =  $upload->getUploadFileInfo();
				$apkurl = $info[0]['savepath'].$info[0]['savename'];
				$business = upload_qiniu('duibao-business',$apkurl,$info[0]['savename'],'no');
				delfile($apkurl);//本地图片的删除
				$data['bussinelicence1']        = $business;
			}
			
			$data['lianxiren'] = $lianxiren;
			$data['phone']     = $phone;
			$data['email']     = $email;
			$data['company']   = $company;
			$data['address']     = trim($address);
			$data['zitiaddress']     = trim($zitiaddress);
			//$data['edit_datetime']   = date('Y-m-d h:i:s');
			$data['checkstatus']   = '1';
			
				
			$imagedata_sql = $Model->table('shop_site')->where ("id='".$id."'")->save($data);
			
			if($imagedata_sql){
				echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/User/userinfo".$yuurl."';</script>";
				$this ->success('数据修改成功!','__APP__/User/userinfo'.$yuurl);
			}else{
				echo "<script>alert('数据修改失败,数据未发生改变！'); history.go(-1);</script>";
				$this->error('数据修改失败,数据未发生改变！');
			}
			
		}
		
	}
	
	
	//店铺信息
	public function store(){
		
		//数据库的初始化
		$Model = new Model();
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//用户信息的查询
		$userinfosql  = "select * from shop_site where id='".$siteid."' and flag=1 and pay='1' ";
		$userinfolist = $Model->query($userinfosql);
		
		if($userinfolist[0]['id']>0){
				
			if($userinfolist[0]['storestatus']=='1'){
				$userinfolist[0]['storestatus']='<font style="background-color:#EDDC1D">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['storestatus']=='2'){
				$userinfolist[0]['storestatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['storestatus']=='3'){
				$userinfolist[0]['storestatus']='<font style="background-color:#FB3C16">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
		}
		
		$userinfolist[0]['touxiang'] = hy_qiniuimgurl('duibao-business',$userinfolist[0]['touxiang'],'200',200,$canshu=true);
		
		$this->assign('list',$userinfolist);
		
		$this->display();
		
	}
	
	
	//店铺信息的编辑
	public function storedit(){
		
		$id = $this->_post('id');
		
		//数据库的初始化
		$Model = new Model();
		
		//获取用户的信息
		$userinfosql  = "select * from shop_site where id='".$id."'";
		$userinfolist = $Model->query($userinfosql);
		
		if($userinfolist[0]['id']>0){
		
			if($userinfolist[0]['storestatus']=='1'){
				$userinfolist[0]['storestatus']='<font style="background-color:#EDDC1D">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['storestatus']=='2'){
				$userinfolist[0]['storestatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($userinfolist[0]['storestatus']=='3'){
				$userinfolist[0]['storestatus']='<font style="background-color:#FB3C16">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
			$userinfolist[0]['touxiang'] = hy_qiniuimgurl('duibao-business',$userinfolist[0]['touxiang'],'200',200,$canshu=true);
		}
		
		$this->assign('list',$userinfolist[0]);
		
		$this->display();
		
	}
	
	
	//店铺信息修改入库
	public function storeditdata(){
		
		//获取相应的参数
		$id = $this->_post('id');
		$storename = $this->_post('storename');
		$qq     = $this->_post('qq');
		$shangjiatype     = $this->_post('shangjiatype');
		$uupdate_submit = $this->_post('uupdate_submit');
		
		$data=array();
		
		if($uupdate_submit!=''){
				
			if($storename==''){
				echo "<script>alert('店铺名称不能为空！');history.go(-1);</script>";
				$this -> error('店铺名称不能为空！');
			}
				
			//图片的上传
			import('ORG.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			$upload->savePath = XMAINPATH.'/Public/Uploads/storelogo/';// 设置附件上传目录
				
			$upload->thumb = false;
			$upload->thumbMaxHeight = '300';
				
			//判断该目录是否存在
			if(!is_dir($upload->savePath)){
				mkdir($upload->savePath,0777);
			}
				
			$infof  =   $upload->upload();
			
			$Model = new Model();
			
			if($infof===true){
				
				//七牛上修改之前图片的删除
				$del_sql = "select touxiang from shop_site where id='".$id."'";
				$del_list = $Model->query($del_sql);
				
				delete_qiniu('duibao-business',$del_list[0]['touxiang']);
				
				$info =  $upload->getUploadFileInfo();
				$apkurl = $info[0]['savepath'].$info[0]['savename'];
				$touxiang = upload_qiniu('duibao-business',$apkurl,$info[0]['savename'],'no');
				delfile($apkurl);//本地图片的删除
				$data['touxiang']        = $touxiang;
				
			}
				
			$data['storename']     = $storename;
			$data['qq']            = $qq;
			$data['shangjiatype']  = $shangjiatype;
			$data['storestatus']   = '1';
				
				
			$imagedata_sql = $Model->table('shop_site')->where ("id='".$id."'")->save($data);
			
				
			if($imagedata_sql){
				echo "<script>alert('店铺信息修改成功！');window.location.href='".__APP__."/User/store".$yuurl."';</script>";
				$this ->success('店铺信息修改成功!','__APP__/User/store'.$yuurl);
			}else{
				echo "<script>alert('店铺信息修改失败！'); history.go(-1);</script>";
				$this->error('店铺信息修改失败！');
			}
				
		}
		
	}
	
	
	//商户第三方店铺链接
	public function shopstore(){
		
		//数据库的初始化
		$Model = new Model();
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//用户信息的查询
		$userinfosql  = "select * from shop_store where siteid='".$siteid."' limit 5";
		$userinfolist = $Model->query($userinfosql);
		foreach ($userinfolist as $keys=>$vals){
			
			$userinfolist[$keys]['shoplogo'] = hy_qiniuimgurl('duibao-business',$userinfolist[$keys]['shoplogo'],'200',200);
		}
		
		$this->assign('list',$userinfolist);
		
		$this->display();
		
	}
	
	
	//店铺的展示页面
	public function shopstoreshow(){
		
		
		$type = $this->_get('type');//页面添加
		$id =   $this->_get('id');//修改的id
		
		//数据库的初始化
		$Model = new Model();
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//用户信息的查询
		$userinfosql  = "select * from shop_store where id='".$id."' ";
		$userinfolist = $Model->query($userinfosql);
		
		
		$userinfolist[0]['shoplogo'] = hy_qiniuimgurl('duibao-business',$userinfolist[0]['shoplogo'],'100','100');
		
		
		
		$this->assign('list',$userinfolist[0]);
		
		$this->display();
	}
	
	
	//店铺修改入库
	public function shopstoredata(){
		
		
		$shopname = $this->_post('shopname');
		$shoptype = $this->_post('shoptype');
		$shopurl  = $this->_post('shopurl');
		$id       = $this->_post('id');
		$uupdate_submit = $this->_post('uupdate_submit');
		
		$siteid   = session(HYSESSQZ.'siteid');
		
		$data = array();
		if($uupdate_submit!=''){

			//判断该链接的数量
			$Model = new Model();

			$shopnum_sql = "select count(*) as num from shop_store where siteid='".$siteid."'";
			$shopnum_list = $Model->query($shopnum_sql);

			if($shopnum_list[0]['num']>=5){

				echo "<script>alert('操作失败,只可以填写5个店铺网址'); history.go(-1);</script>";
				$this->error('操作失败,只可以填写5个店铺网址！');

			}
			
			//图片的上传
			import('ORG.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 20*1024*1024;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
			$upload->savePath =  XMAINPATH.'Public/Uploads/storelogo/';// 设置附件上传目录
			
			
			$upload->thumb = false;
			$upload->thumbMaxHeight = '300';
			
			//判断该目录是否存在
			if(!is_dir($upload->savePath)){
				mkdir($upload->savePath,0777);
			}
			
			$infof  =   $upload->upload();
			
			
			if($infof===true){
				//七牛上修改之前图片的删除
				$del_sql = "select shoplogo from shop_store where id='".$id."'";
				$del_list = $Model->query($del_sql);
				
				delete_qiniu('duibao-business',$del_list[0]['shoplogo']);
				$info =  $upload->getUploadFileInfo();
				$apkurl = $info[0]['savepath'].$info[0]['savename'];
				$touxiang = upload_qiniu('duibao-business',$apkurl,$info[0]['savename'],'no');
				delfile($apkurl);//本地图片的删除
				$data['shoplogo']        = $touxiang;
				
			}else{
				$data['shoplogo']='';
			}
			
			
			
			if($id!=''){//信息修改
				
				$data['shopname']     = $shopname;
				$data['shoptype']     = $shoptype;
				$data['shopurl']     = $shopurl;
				//$data['create_datetime']   = date('Y-m-d H:i:s');
				
				$shopdata = $Model->table('shop_store')->where ("id='".$id."'")->save($data);
			}else{//数据添加
				
				$data['shopname']     = $shopname;
				$data['shoptype']     = $shoptype;
				$data['shopurl']     = $shopurl;
				$data['create_datetime']   = date('Y-m-d H:i:s');
				$data['siteid']  = $siteid;
				
				$shopdata = $Model->table('shop_store')->add($data);
			}
			
			if($shopdata){
				echo "<script>alert('操作成功！');window.location.href='".__APP__."/User/shopstore".$yuurl."';</script>";
				$this ->success('操作成功!','__APP__/User/shopstore'.$yuurl);
			}else{
				echo "<script>alert('操作失败！'); history.go(-1);</script>";
				$this->error('操作失败！');
			}
			
			
		}
		
	}
	
	
	//数据的删除
	public function delshopstore(){
		
		$id = $this->_get('id');
		
		$Model = new Model();
		
		$del_sql = "select shoplogo from shop_store where id='".$id."'";
		$del_list = $Model->query($del_sql);
		
		delete_qiniu('duibao-business',$del_list[0]['shoplogo']);
		
		$del_sql = "delete from shop_store where id='".$id."'";
		$del_list = $Model->execute($del_sql);
		
		if($del_list){
			
			echo "<script>alert('删除成功！');window.location.href='".__APP__."/User/shopstore".$yuurl."';</script>";
			$this ->success('删除成功!','__APP__/User/shopstore'.$yuurl);
			
		}else{
			
			echo "<script>alert('删除失败！'); history.go(-1);</script>";
			$this->error('删除失败！');
		}
		
		
		
	}
	
	
	
}