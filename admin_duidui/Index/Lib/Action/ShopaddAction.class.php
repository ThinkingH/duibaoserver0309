<?php
/*
 * 商品的添加
 */
class ShopaddAction extends Action{
	
	private $lock_index              = '9751';
	private $lock_adddata            = '975';
	private $lock_addshow            = '975';
	private $lock_deletedata         = '97';
	private $lock_updatedata         = '975';
	private $lock_updateshow         = '975';
	
	
	
	public function index(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		//接收用户选择的查询参数
		$date_s    = $this->_get('date_s');
		$date_e    = $this->_get('date_e');
		$flag      = $this->_get('flag');
		$status    = $this->_get('status');
		$name      = $this->_get('name');
		$onsales   = $this->_get('onsales');
		$siteid   = $this->_get('siteid');
		$tuijian   = $this->_get('tuijian');
		
	
	
		//是否启用
		$flag_arr = array(
				'1' => '启用',
				'9' => '关闭',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		//审核状态
		$status_arr = array(
				'1' => '审核通过',
				'2' => '审核不通过',
				'3' => '待审核',
		);
		
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
		$this->assign('optionstatus',$optionstatus);
		
		
		//是否上线
		$onsales_arr = array(
				'1' => '上架',
				'2' => '不上架',
				'3' => '违规下架',
		);
		$optiononsales = '<option value=""></option>';
		foreach($onsales_arr as $keyc => $valc) {
			$optiononsales .= '<option value="'.$keyc.'" ';
			if($onsales==$keyc) { $optiononsales .= ' selected="selected" '; }
			$optiononsales .= '>'.$valc.'</option>';
		}
		$this->assign('optiononsales',$optiononsales);
		
		
		
		//是否推荐
		$tuijian_arr = array(
		
				'101' => '推荐',
				'100' => '不推荐',
		
		);
		$optionhottypeid = '<option value=""></option>';
		foreach($tuijian_arr as $keyc => $valc) {
			$optionhottypeid .= '<option value="'.$keyc.'" ';
			if($tuijian==$keyc) { $optionhottypeid .= ' selected="selected" '; }
			$optionhottypeid .= '>'.$valc.'</option>';
		}
		$this->assign('optionhottypeid',$optionhottypeid);
		
		/* //支付类型
		$zhifu_arr = array(
				'1' => '积分',
				'2' => '金额',
				'3' => '积分金额混合使用',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
		$this->assign('optionstatus',$optionstatus); */
		
		$this->assign('name',$name);
		$this->assign('siteid',$siteid);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
	
		$Model = new Model();
	
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
	
		if($flag!='') {
			$sql_where .= " flag='".$flag."' and ";
		}
		
		if($status!='') {
			$sql_where .= " status='".$status."' and ";
		}
		
		if($onsales!='') {
			$sql_where .= " onsales='".$onsales."' and ";
		}
		
		if($name!='') {
			$sql_where .= " name like '%".$name."%' and ";
		}
	
		if($date_s!='') {
			$sql_where .= "start_datetime>='".$date_s."' and ";
		}
	
		if($date_e!='') {
			$sql_where .= "start_datetime<='".$date_e."' and ";
		}
		
		if($siteid!=''){
			$sql_where .= "siteid='".$siteid."' and ";
		}
		
		if($tuijian!=''){
			$sql_where .= "hottypeid='".$tuijian."' and ";
		}
	
	
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		//渠道编号
		$sitearr = array();
		$sitesql = "select id,lianxiren,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		foreach ($sitelist as $keys=>$vals){
			$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['id'].'-'.$sitelist[$keys]['name'];
			
		}
		
		$typearr = array();
		$typechildarr = array();
		$sql_childtype = "select * from shop_config where flag='1' order by typeid asc";
		$list_childtype = $Model->query($sql_childtype);
		
		
		foreach ($list_childtype as $keys=>$vals){
			$typearr[$list_childtype[$keys]['typeid']] = $list_childtype[$keys]['name'];
			
		}
		
		$sql_type = "select * from shop_type ";
		$list_type = $Model->query($sql_type);
		
		foreach ($list_type as $keys=>$vals){
			$typechildarr[$list_type[$keys]['childtype']] = $list_type[$keys]['name'];
		}
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_product')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_product')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
	
	
		foreach($list as $keyc => $valc) {
			
	
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
				
			if($list[$keyc]['status']=='3') {
				$list[$keyc]['status'] = '<font style="background-color:#FBFB7F">&nbsp;&nbsp;待审核&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['status']=='1') {
				$list[$keyc]['status'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;审核通过&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['status']=='2') {
				$list[$keyc]['status'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;审核不通过&nbsp;&nbsp;</font>';
			}
			
			if($list[$keyc]['feetype']=='1'){
				$list[$keyc]['feetype'] = '积分支付';
				
			}else if($list[$keyc]['feetype']=='2'){
				$list[$keyc]['feetype'] = '金额支付';
			}else if($list[$keyc]['feetype']=='3'){
				$list[$keyc]['feetype'] = '积分金额混合支付';
			}else if($list[$keyc]['feetype']=='4'){
				$list[$keyc]['feetype'] = 'VIP免费专区';
			}else if($list[$keyc]['feetype']=='5'){
				$list[$keyc]['feetype'] = '免费专区';
			}
			
			if($list[$keyc]['onsales']=='1'){
				$list[$keyc]['onsales'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已上架&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['onsales']=='2'){
				$list[$keyc]['onsales'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;已下架&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['onsales']=='3'){
				$list[$keyc]['onsales'] = '<font style="background-color:#FBFB7F">&nbsp;&nbsp;违规下架&nbsp;&nbsp;</font>';
			}
			
			if($list[$keyc]['hottypeid']=='100'){
				$list[$keyc]['hottypeid'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;未推荐&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['hottypeid']=='101'){
				$list[$keyc]['hottypeid'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已推荐&nbsp;&nbsp;</font>';
			}
			
			
			$date = date('Y-m-d Y-m-d H:i:s');
			$jiequtime = substr(date('Y-m-d H:i:s',$list[$keyc]['stop_datetime']),0,10);
			if(strtotime($jiequtime)<=strtotime($date)){
	
				$list[$keyc]['shangjia']='<font style="background-color:#FF1700">&nbsp;&nbsp;已下架&nbsp;&nbsp;</font>';
			}else{
				$list[$keyc]['shangjia'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已上架&nbsp;&nbsp;</font>';
			}
			
			$list[$keyc]['siteid'] =  isset($sitearr[$list[$keyc]['siteid']])? $sitearr[$list[$keyc]['siteid']]:$list[$keyc]['siteid'];
			
			$list[$keyc]['typeid'] =  isset($typearr[$list[$keyc]['typeid']])? $typearr[$list[$keyc]['typeid']]:$list[$keyc]['typeid'];
			
			//商品子类型
			$list[$keyc]['typeidchild'] =  isset($typechildarr[$list[$keyc]['typeidchild']])? $typechildarr[$list[$keyc]['typeidchild']]:$list[$keyc]['typeidchild'];
		
			$list[$keyc]['create_datetime'] = date("Y-m-d H:i:s",$list[$keyc]['create_datetime']);
			
			//图片展示路径
			$arr = unserialize(BUCKETSTR);//七牛云存储连接$arr['duibao-basic']
				
			//图片展示
			if(substr($list[$keyc]['mainpic'],0,7)!='http://'){
				$list[$keyc]['mainpic']=$arr['duibao-shop'].$list[$keyc]['mainpic'].'?imageView2/1/w/200/h/200/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['showpic1'],0,7)!='http://'){
				$list[$keyc]['showpic1']=$arr['duibao-shop'].$list[$keyc]['showpic1'].'?imageView2/1/w/200/h/200/q/75|imageslim';
			}
			
			if(substr($list[$keyc]['showpic2'],0,7)!='http://'){
				$list[$keyc]['showpic2']=$arr['duibao-shop'].$list[$keyc]['showpic2'].'?imageView2/1/w/200/h/200/q/75|imageslim';
			}
			if(substr($list[$keyc]['showpic3'],0,7)!='http://'){
				$list[$keyc]['showpic3']=$arr['duibao-shop'].$list[$keyc]['showpic3'].'?imageView2/1/w/200/h/200/q/75|imageslim';
			}
			
			//价格展示number_format($selet_list['price'] / 100, 2);
			/* if($list[$keyc]['price'] =='0'){
				
			}else{
				$list[$keyc]['price'] = number_format($list[$keyc]['price'] / 100, 2);
			}
			 */
			
			//$list[$keyc]['price'] = $list[$keyc]['price'] / 100;
			
			$list[$keyc]['price'] = number_format($list[$keyc]['price'] / 100, 2);
			
			
		}
	
		$this -> assign('list',$list);
	
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	
	}
	
	
	//商品的添加
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
				'1' => '审核通过',
				'2' => '审核不通过',
				'3' => '待审核',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionstatus .= '>'.$valc.'</option>';
		}
		$this->assign('optionstatus',$optionstatus);
		
		
		//是否推荐
		$tuijian_arr = array(
				
				'100' => '不推荐',
				'101' => '推荐',
				
		);
		foreach($tuijian_arr as $keyc => $valc) {
			$optionhottypeid .= '<option value="'.$keyc.'" ';
			/* if($shoplist[0]['hottypeid']==$keyc) { $optionhottypeid .= ' selected="selected" '; } */
			$optionhottypeid .= '>'.$valc.'</option>';
		}
		$this->assign('optionhottypeid',$optionhottypeid);
		
		
		//是否上架
		$onsales_arr = array(
				'1' => '上架',
				'2' => '下架',
				'3' => '违规下架',
		);
		$optiononsales = '<option value=""></option>';
		foreach($onsales_arr as $keyc => $valc) {
			$optiononsales .= '<option value="'.$keyc.'" ';
			/* if($shoplist[0]['flag']==$keyc) { $optiononsales .= ' selected="selected" '; } */
			$optiononsales .= '>'.$valc.'</option>';
		}
		$this->assign('optiononsales',$optiononsales);
		
		$Model = new Model();
		
		//渠道编号
		$sitesql = "select id,lianxiren,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		
		$optionsiteid = '<option value=""></option>';
		foreach($sitelist as $keyc => $valc) {
			$optionsiteid .= '<option value="'.$valc['id'].'" ';
			/* if($sitelist[0]['flag']==$keyc) { $optionsiteid .= ' selected="selected" '; } */
			$optionsiteid .= '>'.$valc['id'].'--'.$valc['lianxiren'].'</option>';
		}
		$this -> assign('optionsiteid',$optionsiteid);
		
		
		//商品编号--主类型
		$typearr = array();
		/* $sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc"; */
		$sql_type = "select * from db_goods_type where flag=1 and level=1  order by  id desc ";
		$list_type = $Model->query($sql_type);
		
		$optiontype = '<option value="">请选择商品分类</option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['id'].'"';
			$optiontype .= '>'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		
		
		//商品编号--子类型
		$typearr = array();
		//$sql_childtype = "select childtype,xushi,name from shop_type order by id asc";
		$sql_childtype = "select * from db_goods_type  where flag=1 and level=2 order by id desc";
		$list_childtype = $Model->query($sql_childtype);
		
		$optionchildtype = '<option value="">请选择商品子分类</option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['id'].'"';
			/* if($val['typeid']==$type) {
			 $optiontype .= ' selected="selected" ';
			 } */
			$optionchildtype .= '>'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		
		
		
		
		/* //商品编号--主类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		$optiontype = '<option value=""></option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		
		
		//商品编号--子类型
		$typearr = array();
		$sql_childtype = "select childtype,xushi,name from shop_type order by id asc";
		$list_childtype = $Model->query($sql_childtype);
		
		$optionchildtype = '<option value=""></option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['childtype'].'"';
			$optionchildtype .= '>'.$val['childtype'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype); */
		
		
		//支付类型
		$zhifu_arr = array(
				'1' => '积分',
				'2' => '金额',
				'3' => '混合使用',
				'4' => 'VIP免费专区',
				'5' => '免费专区',
		);
		$optionfeetype = '<option value=""></option>';
		foreach($zhifu_arr as $keyc => $valc) {
			$optionfeetype .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionfeetype .= '>'.$valc.'</option>';
		}
		$this->assign('optionfeetype',$optionfeetype);
		
		
		//商品领取方式
		$pickup_arr = array(
				'1' => '自提',
				'2' => '包邮',
				'3' => '发货物流',
		);
		$optionpickup = '<option value="">商品领取方式</option>';
		foreach($pickup_arr as $keyc => $valc) {
			$optionpickup .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionpickup .= '>'.$valc.'</option>';
		}
		$this->assign('optionpickup',$optionpickup);
		
		//秘钥形式
		$miyao_arr = array(
				'1' => '单卡密',
				'2' => '多卡密',
				'3' => '二维码',
				'4' => '实物',
					
		);
		$optionmiyaotype = '<option value="4">秘钥形式</option>';
		foreach($miyao_arr as $keyc => $valc) {
			$optionmiyaotype .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionmiyaotype .= '>'.$valc.'</option>';
		}
		$this->assign('optionmiyaotype',$optionmiyaotype);
		
		//卡密发放方式
		$fafang_arr = array(
				'1' => '立即发放',
				'2' => '隔天发放',
		);
		$optionfafang = '<option value="">商品发放时间</option>';
		foreach($fafang_arr as $keyc => $valc) {
			$optionfafang .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionfafang .= '>'.$valc.'</option>';
		}
		$this->assign('optionfafang',$optionfafang);
		
		//商品虚实
		$xushitype = array(
				'1' => '虚拟',
				'2' => '实物',
		);
		
		$optionxushitype = '<option value="">请选择商品类型</option>';
		foreach($xushitype as $keyc => $valc) {
			$optionxushitype .= '<option value="'.$keyc.'" ';
			$optionxushitype .= '>'.$valc.'</option>';
		}
		$this->assign('optionxushitype',$optionxushitype);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	
	
	//商品的添加
	public function adddata(){
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_adddata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$flag   = $this->_post('flag');
		$status = $this->_post('status');
		$start_datetime = $this->_post('start_datetime');
		$stop_datetime = $this->_post('stop_datetime');
		$siteid = $this->_post('siteid');
		$type   = $this->_post('type');
		$typeid = $this->_post('typeid');
		$name   = trim($this->_post('name'));
		$gateway = $this->_post('gateway');
		$mbps    = $this->_post('mbps');
		$ttype   = $this->_post('ttype');
		$miaoshu  = $this->_post('miaoshu');
		$price    = $this->_post('price');
		$score    = $this->_post('score');
		$feetype  = $this->_post('feetype');
		$kucun        = $this->_post('kucun');
		$daymax       = $this->_post('daymax');
		$userdaymax   = $this->_post('userdaymax');
		$usermonthmax = $this->_post('usermonthmax');
		$userallmax   = $this->_post('userallmax');
		$remark       = $this->_post('remark');
		$statusmsg    = $this->_post('statusmsg');
		$orderbyid    = $this->_post('orderbyid');
		$hottypeid    = $this->_post('hottypeid');
		$onsales    = $this->_post('onsales');
		$youxiaoqi    = $this->_post('youxiaoqi');
		$update_submit = $this->_post('update_submit');
		
		$video_url = $this->_post('video_url');
		$xushitype = $this->_post('xushitype');//商品虚实
		$miyao_type = $this->_post('miyao_type');//商品虚实
		$fafang_type = $this->_post('fafang_type');//商品虚实
		$pickup = $this->_post('pickup');//商品虚实
		
		$goods_content    = $this->_post('goods_content');//商品详情页
		//商品详情页链接
		$xiangqingurl = 'http://xbapp.xinyouxingkong.com/web/xiangqing.php?name='.$name;
		
		if($update_submit==''){
			echo "<script>alert('非法操作！'); history.go(-1);</script>";
			$this->error('非法操作！');
			
		}else{
			if($siteid==''){
				echo "<script>alert('渠道不能为空！'); history.go(-1);</script>";
				$this->error('渠道不能为空！');
			}
			if($kucun==''|| !is_numeric($kucun)){
				echo "<script>alert('商品库存不能为空且必须为数字！'); history.go(-1);</script>";
				$this->error('商品库存不能为空且必须为数字！');
			}
			
			if($daymax==''|| !is_numeric($daymax)){
				echo "<script>alert('每日允许最大库存不能为空且必须为数字！'); history.go(-1);</script>";
				$this->error('每日允许最大库存不能为空且必须为数字！');
			}
			
			if($userdaymax==''|| !is_numeric($userdaymax)){
				echo "<script>alert('允许用户每日兑换最大次数不能为空且必须为数字！'); history.go(-1);</script>";
				$this->error('允许用户每日兑换最大次数不能为空且必须为数字！');
			}
			
			if($usermonthmax==''|| !is_numeric($usermonthmax)){
				echo "<script>alert('允许用户每月兑换最大次数不能为空且必须为数字！'); history.go(-1);</script>";
				$this->error('允许用户每月兑换最大次数不能为空且必须为数字！');
			}
			
			if($userallmax==''|| !is_numeric($userallmax)){
				echo "<script>alert('允许用户终身兑换最大次数不能为空且必须为数字！'); history.go(-1);</script>";
				$this->error('允许用户终身兑换最大次数不能为空且必须为数字！');
			}
			
			$Model = new Model();
			
			//判断该数据是否已经存在
			$repeatdatasql = "select id from shop_product where name='".$name."' and siteid='".$siteid."' and  typeid='".$typeid."' ";
			$repeatdatalist = $Model->query($repeatdatasql);
			
			if(count($repeatdatalist)>0){//该数据存在，不可以重复添加
				
				echo "<script>alert('该商品已存在不可以重复添加！'); history.go(-1);</script>";
				$this->error('该商品已存在不可以重复添加！');
			}else{
				//图片的上传
				import('ORG.UploadFile');
					
				$upload = new UploadFile();// 实例化上传类
				$upload->maxSize  = 3145728 ;// 设置附件上传大小
				$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
				//$upload->savePath =  './Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
				$upload->savePath =  XMAINPATH.'/Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
				
				$upload->thumb = true;
				$upload->thumbMaxHeight = '300';
				
				//判断该目录是否存在
				if(!is_dir($upload->savePath)){
					mkdir($upload->savePath,0777,true);
				}
				
				if(!$upload->upload()) {                             // 上传错误提示错误信息
					$this->error($upload->getErrorMsg());
				}else{                                              // 上传成功 获取上传文件信息
					$info =  $upload->getUploadFileInfo();
				}
				
				
				$data = array();
				
				for ($i=0;$i<=3;$i++){
				
					if($info[$i]['key']=='mainpic'){
						
						$mainpic=$info[$i]['savename'];
						$pathname = $info[$i]['savepath'].$info[$i]['savename'];
						$r=upload_qiniu('duibao-shop',$pathname,$mainpic);
						$data['mainpic']  = $r;
							
						//本地文件的删除
						delfile($pathname);
					}
					
					if($info[$i]['key']=='showpic1'){
						$showpic1 = $info[$i]['savename'];
						$pathname = $info[$i]['savepath'].$info[$i]['savename'];
						$r=upload_qiniu('duibao-shop',$pathname,$showpic1);
						$data['showpic1'] = $showpic1;
						
						//本地文件的删除
						delfile($pathname);
					}
					if($info[$i]['key']=='showpic2'){
						$showpic2 = $info[$i]['savename'];
						$pathname = $info[$i]['savepath'].$info[$i]['savename'];
						$r=upload_qiniu('duibao-shop',$pathname,$showpic2);
						$data['showpic2'] = $r;
						
						//本地文件的删除
						delfile($pathname);
					}
					if($info[$i]['key']=='showpic3'){
						$showpic3 = $info[$i]['savename'];
						$pathname = $info[$i]['savepath'].$info[$i]['savename'];
						$r=upload_qiniu('duibao-shop',$pathname,$showpic3);
						$data['showpic3'] = $r;
						
						//本地文件的删除
						delfile($pathname);
					}
				}
				
				$data['flag']= $flag;
				$data['status'] = $status;
				$data['siteid'] = $siteid;
				$data['typeid'] = $type;
				$data['typeidchild'] = $typeid;
				$data['name']   = $name;
				$data['gateway'] = $gateway;
				$data['mbps']    = $mbps;
				$data['ttype']   = $ttype;
				$data['miaoshu'] = $miaoshu;
				$data['price']   = $price*100;
				$data['score']   = $score;
				$data['feetype'] = $feetype;
				$data['xiangqingurl'] =$xiangqingurl;
				$data['kucun'] = $kucun;
				$data['daymax']= $daymax;
				$data['userdaymax'] = $userdaymax;
				$data['usermonthmax'] = $usermonthmax;
				$data['userallmax'] = $userallmax;
				$data['remark'] = $remark;
				$data['statusmsg'] = $statusmsg;
				$data['orderbyid'] = $orderbyid;
				$data['hottypeid'] = $hottypeid;
				$data['onsales'] = $onsales;
				$data['goods_content'] = $goods_content;
				$data['stop_datetime'] = $stop_datetime;
				$data['youxiaoqi'] = $youxiaoqi;
				$data['video_url'] = $video_url;
				$data['miyao_type'] = $miyao_type;
				$data['xushi_type'] = $xushitype;
				$data['fafang_type'] = $fafang_type;
				$data['pickup'] = $pickup;
				
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('shop_product')  -> add($data);
			
			
				
			if($ret) {
				echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Shopadd/index".$yuurl."';</script>";
				$this -> success('数据添加成功!','__APP__/Shopadd/index'.$yuurl);
			}else {
				echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据添加失败，系统错误!');
			}
			}
		}
		
	}
	
	
	//页面的修改
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
		
		if($update_submit==''){
			echo "<script>alert('非法操作！');history.go(-1)</script>";
			$this->error('非法操作！');
			
		}else{
			if($id==''){
				echo "<script>alert('非法操作！');history.go(-1)</script>";
				$this->error('非法操作！');
				
			}else{
				
				$Model = new Model();
				
				$shopsql  = "select * from shop_product where id='".$id."'";
				$shoplist = $Model->query($shopsql); 
				
				
				$shoplist[0]['price'] = number_format($shoplist[0]['price'] / 100, 2);//单价
				//时间的截取
				/* $shoplist[0]['stop_datetime_his'] = substr($shoplist[0]['stop_datetime'],11,8); 
				$shoplist[0]['stop_datetime'] = substr($shoplist[0]['stop_datetime'],0,10);//下架时间
				
				$shoplist[0]['start_datetime_his'] = substr($shoplist[0]['start_datetime'],11,8);//上架时间
				$shoplist[0]['start_datetime']   = substr($shoplist[0]['start_datetime'],0,10);*/
				
				//是否启用
				$flag_arr = array(
						'1' => '启用',
						'9' => '关闭',
				);
				$optionflag = '<option value=""></option>';
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
				
				//是否上架
				$onsales_arr = array(
						'1' => '上架',
						'2' => '下架',
						'3' => '违规下架',
				); 
				
				$optiononsales = '<option value=""></option>';
				foreach($onsales_arr as $keyc => $valc) {
					$optiononsales .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['onsales']==$keyc) { $optiononsales .= ' selected="selected" '; }
					$optiononsales .= '>'.$valc.'</option>';
				}
				$this->assign('optiononsales',$optiononsales);
				
				/* $optiononsales = '<option value=""></option>';
				foreach($onsales_arr as $keyc => $valc) {
					$optiononsales .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['onsales']==$keyc) { $optiononsales .= ' selected="selected" '; }
					$optiononsales .= '>'.$valc.'</option>';
				}
				$this->assign('optiononsales',$optiononsales); */
				
				
				
				//是否推荐
				$tuijian_arr = array(
						'100' => '不推荐',
						'101' => '推荐',
				);
				foreach($tuijian_arr as $keyc => $valc) {
					$optionhottypeid .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['hottypeid']==$keyc) { $optionhottypeid .= ' selected="selected" '; }
					$optionhottypeid .= '>'.$valc.'</option>';
				}
				$this->assign('optionhottypeid',$optionhottypeid);
				
				
				
				
				//审核状态
				$status_arr = array(
						'1' => '审核通过',
						'2' => '审核不通过',
						'3' => '待审核',
				);
				$optionstatus = '<option value=""></option>';
				foreach($status_arr as $keyc => $valc) {
					$optionstatus .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['status']==$keyc) { $optionstatus .= ' selected="selected" '; } 
					$optionstatus .= '>'.$valc.'</option>';
				}
				$this->assign('optionstatus',$optionstatus);
				
				
				//渠道编号
				$sitesql = "select id,lianxiren,flag from shop_site where flag='1'";
				$sitelist = $Model->query($sitesql);
				
				$optionsiteid = '<option value=""></option>';
				foreach($sitelist as $keyc => $valc) {
					$optionsiteid .= '<option value="'.$valc['id'].'" ';
					if($shoplist[0]['siteid']==$valc['id']) { $optionsiteid .= ' selected="selected" '; } 
					$optionsiteid .= '>'.$valc['id'].'--'.$valc['name'].'</option>';
				}
				$this -> assign('optionsiteid',$optionsiteid);
				
				//商品虚实
				$xushitype = array(
						'1' => '虚拟',
						'2' => '实物',
				);
				
				$optionxushitype = '<option value="">请选择商品类型</option>';
				foreach($xushitype as $keyc => $valc) {
					$optionxushitype .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['xushi_type']==$keyc) {
						$optionxushitype .= ' selected="selected" ';
					}
					$optionxushitype .= '>'.$valc.'</option>';
				}
				$this->assign('optionxushitype',$optionxushitype);
				
				
				
				//商品编号--主类型
				$typearr = array();
				/* $sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc"; */
				$sql_type = "select * from db_goods_type where flag=1 and level=1  order by  id desc ";
				$list_type = $Model->query($sql_type);
				
				$optiontype = '<option value="">请选择商品分类</option>';
				foreach($list_type as $val) {
					$optiontype .= '<option value="'.$val['id'].'"';
					
					
				 if($shoplist[0]['typeid']==$val['id']) { $optiontype .= ' selected="selected" '; } 
				 
					$optiontype .= '>'.$val['name'].'</option>';
				}
				
				
				$this -> assign('optiontype',$optiontype);
				
				
				//商品编号--子类型
				$typearr = array();
				//$sql_childtype = "select childtype,xushi,name from shop_type order by id asc";
				$sql_childtype = "select * from db_goods_type  where flag=1 and level=2 order by id desc";
				$list_childtype = $Model->query($sql_childtype);
				
				$optionchildtype = '<option value="">请选择商品子分类</option>';
				foreach($list_childtype as $val) {
					$optionchildtype .= '<option value="'.$val['id'].'"';
				 if($shoplist[0]['typeidchild']==$val['id']) { $optionchildtype .= ' selected="selected" '; } 
					$optionchildtype .= '>'.$val['name'].'</option>';
				}
				$this -> assign('optionchildtype',$optionchildtype);
				/* //商品编号--主类型
				$typearr = array();
				$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
				$list_type = $Model->query($sql_type);
				
				$optiontype = '<option value=""></option>';
				foreach($list_type as $val) {
					$optiontype .= '<option value="'.$val['typeid'].'"';
					if($shoplist[0]['typeid']==$val['typeid']) {$optiontype .= ' selected="selected" ';}
					$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
				}
				$this -> assign('optiontype',$optiontype);
				
				
				//商品编号--子类型
				$typearr = array();
				$sql_childtype = "select type,childtype,xushi,name from shop_type where flag=1 order by id asc";
				$list_childtype = $Model->query($sql_childtype);
				
				$optionchildtype = '<option value=""></option>';
				foreach($list_childtype as $val) {
					$optionchildtype .= '<option value="'.$val['childtype'].'"';
					if($shoplist[0]['typeidchild']==$val['childtype']) { $optionchildtype .= ' selected="selected" '; } 
					$optionchildtype .= '>'.$val['childtype'].'--'.$val['name'].'</option>';
				}
				$this -> assign('optionchildtype',$optionchildtype); */
				
				
				//支付类型
				$zhifu_arr = array(
						'1' => '积分',
						'2' => '金额',
						'3' => '混合使用',
						'4' => 'VIP免费专区',
						'5' => '免费专区',
				);
				$optionfeetype = '<option value=""></option>';
				foreach($zhifu_arr as $keyc => $valc) {
					$optionfeetype .= '<option value="'.$keyc.'" ';
					 if($shoplist[0]['feetype']==$keyc) { $optionfeetype .= ' selected="selected" '; } 
					$optionfeetype .= '>'.$valc.'</option>';
				}
				$this->assign('optionfeetype',$optionfeetype);
				
				
				//商品领取方式
				$pickup_arr = array(
						'1' => '自提',
						'2' => '包邮',
						'3' => '发货物流',
				);
				$optionpickup = '<option value="">商品领取方式</option>';
				foreach($pickup_arr as $keyc => $valc) {
					$optionpickup .= '<option value="'.$keyc.'" ';
					 if($shoplist[0]['pickup']==$keyc) { $optionpickup .= ' selected="selected" '; } 
					$optionpickup .= '>'.$valc.'</option>';
				}
				$this->assign('optionpickup',$optionpickup);
				
				//秘钥形式
				$miyao_arr = array(
						'1' => '单卡密',
						'2' => '多卡密',
						'3' => '二维码',
						'4' => '实物',
							
				);
				$optionmiyaotype = '<option value="4">秘钥形式</option>';
				foreach($miyao_arr as $keyc => $valc) {
					$optionmiyaotype .= '<option value="'.$keyc.'" ';
					 if($shoplist[0]['miyao_type']==$keyc) { $optionmiyaotype .= ' selected="selected" '; } 
					$optionmiyaotype .= '>'.$valc.'</option>';
				}
				$this->assign('optionmiyaotype',$optionmiyaotype);
				
				//卡密发放方式
				$fafang_arr = array(
						'1' => '立即发放',
						'2' => '隔天发放',
				);
				$optionfafang = '<option value="">商品发放时间</option>';
				foreach($fafang_arr as $keyc => $valc) {
					$optionfafang .= '<option value="'.$keyc.'" ';
					if($shoplist[0]['fafang_type']==$keyc) { $optionfafang .= ' selected="selected" '; } 
					$optionfafang .= '>'.$valc.'</option>';
				}
				$this->assign('optionfafang',$optionfafang);
			//	$shoplist[0]['price'] = number_format($shoplist[0]['price'] / 100, 2);//单价
				
				
				
				$this->assign('list',$shoplist[0]);
				
				
				// 输出模板
				$this->display();
				
				printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
				
				}
				
		}
		
	}
	

	//上架 
	public function onsalesdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_onsalesdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		$sales_submit = $this->_post('sales_submit');
		
		$Model = new Model();
		
		if($sales_submit!=''){
			
			//判断该商品是否审核通过
			$updatesql = "select id from shop_product where id='".$id."' and status=2 and flag=1 ";
			$updatelist = $Model->query($updatesql);
			
			
			if($updatelist[0]['id']>0){//该数据通过审核
				
				$updatesalessql = "update shop_product set onsales=1,start_datetime='".date('Y-m-d H:i:s')."' where id='".$id."'";
				$updatesaleslist = $Model->execute($updatesalessql);
				
				if($updatesaleslist){
					echo "<script>alert('商品上架成功！');window.location.href='".__APP__."/Shopadd/index".$yuurl."';</script>";
					$this -> success('商品上架成功!','__APP__/Shopadd/index'.$yuurl);
				}else{
					echo "<script>alert('商品上架失败，系统错误!');history.go(-1);</script>";
					$this -> error('商品上架失败，系统错误!');
				}
				
				
			}else{
				echo "<script>alert('该数据未审核，不可以上架!');history.go(-1);</script>";
				$this -> error('该数据未审核，不可以上架!');
			}
		}
		
		
	}
	
	
	//商品推荐
	public function tuijianshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_tuijianshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		$tuijian_submit = $this->_post('tuijian_submit');
		
		$Model = new Model();
		
		if($tuijian_submit!=''){
				
			//判断该商品是否审核通过
			$updatesql = "select id from shop_product where hottypeid='101' and status=2 and flag=1 ";
			$updatelist = $Model->query($updatesql);
				
				
			if($updatelist[0]['id']<=0){//该数据通过审核
		
				$updatesalessql = "update shop_product set hottypeid=101 where id='".$id."'";
				$updatesaleslist = $Model->execute($updatesalessql);
		
				if($updatesaleslist){
					echo "<script>alert('商品推荐成功！');window.location.href='".__APP__."/Shopadd/index".$yuurl."';</script>";
					$this -> success('商品推荐成功!','__APP__/Shopadd/index'.$yuurl);
				}else{
					echo "<script>alert('商品推荐失败，系统错误!');history.go(-1);</script>";
					$this -> error('商品推荐失败，系统错误!');
				}
		
		
			}else{
				echo "<script>alert('该数据已推荐!');history.go(-1);</script>";
				$this -> error('该数据已推荐!');
			}
		}
		
	}
	
	
	
	//数据的添加
	public function updatedata(){
			
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		
		//获取相应的参数
		$flag        = $this->_post('flag');
		$status      = $this->_post('status');
		$start_datetime     = $this->_post('start_datetime');
		$stop_datetime     = $this->_post('stop_datetime');
		$siteid = $this->_post('siteid');
		$type   = $this->_post('type');
		$typeid = $this->_post('typeid');
		$name   = trim($this->_post('name'));
		$gateway = $this->_post('gateway');
		$mbps    = $this->_post('mbps');
		$ttype   = $this->_post('ttype');
		$miaoshu  = $this->_post('miaoshu');
		$price    = $this->_post('price')*100;
		$score    = $this->_post('score');
		$feetype  = $this->_post('feetype');
		/* $xiangqingurl = $this->_post('xiangqingurl'); */
		$kucun        = $this->_post('kucun');
		$daymax       = $this->_post('daymax');
		$userdaymax   = $this->_post('userdaymax');
		$usermonthmax = $this->_post('usermonthmax');
		$userallmax   = $this->_post('userallmax');
		$remark       = $this->_post('remark');
		$statusmsg    = $this->_post('statusmsg');
		$orderbyid    = $this->_post('orderbyid');
		$hottypeid    = $this->_post('hottypeid');
		$status    = $this->_post('status');
		$youxiaoqi    = $this->_post('youxiaoqi');
		$onsales    = $this->_post('onsales');
		$hottype    = $this->_post('hottype');
		$video_url    = $this->_post('video_url');
		
		/*  $xushitype = $this->_post('xushitype');//商品虚实
		$miyao_type = $this->_post('miyao_type');//商品虚实
		$fafang_type = $this->_post('fafang_type');//商品虚实
		$pickup = $this->_post('pickup');//商品虚实*/
		$xushitype    = $this->_post('xushitype');
		$miyao_type    = $this->_post('miyao_type');
		$fafang_type    = $this->_post('fafang_type');
		$pickup    = $this->_post('pickup');

		$goods_content    = $this->_post('goods_content');//商品详情页
		//商品详情页链接
		$xiangqingurl = 'http://xbapp.xinyouxingkong.com/web/xiangqing.php?name='.$name;
		
		$update_submit = $this->_post('update_submit');
		
		
		$data = array();
		
		//图片的修改
		import('ORG.UploadFile');
			
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		//$upload->savePath =  './Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
		$upload->savePath =  XMAINPATH.'/Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
		
		$upload->thumb = true;
		$upload->thumbMaxHeight = '300';
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
		mkdir($upload->savePath,0777,true);
		}
		
		$r = $upload->upload();
		
		$Model = new Model();
		
		//七牛上图片的删除
		$delsql  = "select mainpic,showpic1,showpic2,showpic3 from shop_product where id='".$id."'";
		$dellist = $Model->query($delsql);
		
		if($r===true){//有图片上传
			
			$info =  $upload->getUploadFileInfo();
			
			for ($i=0;$i<=3;$i++){
				
				if($info[$i]['key']=='mainpic'){
					
					$mainpic = $info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-shop',$pathname,$mainpic);
					$data['mainpic'] = $r;
					
					if(substr($dellist[0]['mainpic'],0,7)!='http://'){
						//七牛云存储上图片的删除
						delqiuniu('duibao-shop',$dellist[0]['mainpic']);
					}
				
				}
				
				if($info[$i]['key']=='showpic1'){
					
					$showpic1 = $info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-shop',$pathname,$showpic1);
					$data['showpic1'] = $r;
					
					if(substr($dellist[0]['showpic1'],0,7)!='http://'){
					
						//七牛云存储上图片的删除
						delqiuniu('duibao-shop',$dellist[0]['showpic1']);
					}
					
					
				}
				if($info[$i]['key']=='showpic2'){
					$showpic2 = $info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-shop',$pathname,$showpic2);
					$data['showpic2'] = $r;
					
					if(substr($dellist[0]['showpic2'],0,7)!='http://'){
					
						//七牛云存储上图片的删除
						delqiuniu('duibao-shop',$dellist[0]['showpic2']);
					}
					
				}
				
				if($info[$i]['key']=='showpic3'){
					
					$showpic3 = $info[$i]['savename'];
					$pathname = $info[$i]['savepath'].$info[$i]['savename'];
					$r=upload_qiniu('duibao-shop',$pathname,$showpic2);
					$data['showpic3'] = $r;
					
					if(substr($dellist[0]['showpic3'],0,7)!='http://'){
					
						//七牛云存储上图片的删除
						delqiuniu('duibao-shop',$dellist[0]['showpic3']);
					}
					
				}
			}
			
		}
		
	
		$data['flag']= $flag;
		$data['status'] = $status;
		$data['start_datetime'] = $start_datetime;
		$data['stop_datetime']  = $stop_datetime;
		$data['siteid'] = $siteid;
		$data['typeid'] = $type;
		$data['typeidchild'] = $typeid;
		$data['name']   = $name;
		$data['gateway'] = $gateway;
		$data['mbps']    = $mbps;
		$data['ttype']   = $ttype;
		$data['miaoshu'] = $miaoshu;
		$data['price']   = $price;
		$data['score']   = $score;
		$data['feetype'] = $feetype;
		$data['xiangqingurl'] =$xiangqingurl;
		$data['kucun'] = $kucun;
		$data['daymax']= $daymax;
		$data['userdaymax'] = $userdaymax;
		$data['usermonthmax'] = $usermonthmax;
		$data['userallmax'] = $userallmax;
		$data['remark'] = $remark;
		$data['statusmsg'] = $statusmsg;
		$data['orderbyid'] = $orderbyid;
		$data['hottypeid'] = $hottype;
		$data['status'] = $status;
		$data['goods_content'] = $goods_content;
		$data['youxiaoqi'] = $youxiaoqi;
		$data['onsales'] = $onsales;
		$data['video_url'] = $video_url;
		
		$data['xushi_type'] = $xushitype;
		$data['miyao_type'] = $miyao_type;
		$data['fafang_type'] = $fafang_type;
		$data['pickup'] = $pickup;
		
		//echo $data['hottypeid'];exit;
		
		//说明此数据没有关联数据，可以删除
		$ret = $Model -> table('shop_product')->where("id='".$id."'") -> save($data);
		//echo $Model->getLastsql();exit;
		
		
		if($ret) {
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Shopadd/index".$yuurl."';</script>";
			$this -> success('数据修改成功!','__APP__/Shopadd/index'.$yuurl);
		}else {
			echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
			$this -> error('数据修改失败，系统错误!');
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
		
		$id = $this->_post('id');
		
		$Model = new Model();
		
		$datasql = "select mainpic,showpic1,showpic2,showpic3 from shop_product where id='".$id."'";
		$dellist = $Model->query($datasql);
		
		if(substr($dellist[0]['mainpic'],0,7)!='http://'){
		
			//七牛云存储上图片的删除
			delqiuniu('duibao-shop',$dellist[0]['mainpic']);
		}
		if(substr($dellist[0]['showpic1'],0,7)!='http://'){
		
			//七牛云存储上图片的删除
			delqiuniu('duibao-shop',$dellist[0]['showpic1']);
		}
		if(substr($dellist[0]['showpic2'],0,7)!='http://'){
		
			//七牛云存储上图片的删除
			delqiuniu('duibao-shop',$dellist[0]['showpic2']);
		}
		if(substr($dellist[0]['showpic3'],0,7)!='http://'){
		
			//七牛云存储上图片的删除
			delqiuniu('duibao-shop',$dellist[0]['showpic3']);
		}
		
		
		//说明此数据没有关联数据，可以删除
		$ret = $Model -> table('shop_product') -> where("id='".$id."'") -> delete();
		
		if($ret) {
			echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Shopadd/index".$yuurl."';</script>";
			$this -> success('数据删除成功!','__APP__/Shopadd/index'.$yuurl);
		}else {
			echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
			$this -> error('数据删除失败，系统错误!');
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