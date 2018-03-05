<?php
/*
 * 商品管理
 */
class ShopAction extends Action {
	
	
	//商品的展示列表
	public function shoplist(){
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		 
		//接收相应的参数
		$mtype = $this->_get('mtype');//商品的类型
		$childtype = $this->_get('childtype'); //商品 的分类
		$checkstatus = $this->_get('checkstatus');//审核状态
		$is_on_sale  = $this->_get('is_on_sale');//是否上架
		$good_name   = $this->_get('good_name'); //商品名称
		 
		 
		$this -> assign('good_name',$good_name);
		 
		$Model = new Model();
		 
		//商品的主类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		 
		 
		//虚拟类型
		$xuniarr = '( 0,';
		//实物类型
		$shiwuarr = '( 0,';
		 
		foreach ($list_type as $keyc=>$valc){
	
			$typearr[$list_type[$keyc]['typeid']] = $list_type[$keyc]['typeid'].'--'.$list_type[$keyc]['name'];
	
			//虚拟
			if(substr($list_type[$keyc]['typeid'],0,1)=='1'){
				//虚拟和实物的区分
				$xuniarr .= $list_type[$keyc]['typeid'].',';
			}
	
			//实物
			if(substr($list_type[$keyc]['typeid'],0,1)=='2'){
				//虚拟和实物的区分
				$shiwuarr .= $list_type[$keyc]['typeid'].',';
			}
			 
		}
		$xuniarr .= ' 0 )';
		$shiwuarr .= ' 0 )';
		 
		 
		$optiontype = '<option value="">商品类型</option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		 
		 
		//渠道编号
		$sitearr = array();
		$sitesql = "select id,name,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		foreach ($sitelist as $keys=>$vals){
			$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['id'].'-'.$sitelist[$keys]['name'];
				
		}
		
		
		//商品详细分类
		$typechildarr = array();
		$sql_childtype = "select * from shop_type where flag=1 order by id asc";
		$list_childtype = $Model->query($sql_childtype);
		 
		foreach ($list_childtype as $keyc=>$valc){
			$typechildarr[$list_childtype[$keyc]['childtype']] = $list_childtype[$keyc]['childtype'].'--'.$list_childtype[$keyc]['name'];
		}
		 
		$optionchildtype = '<option value="">商品分类</option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['childtype'].'"';
			if($val['childtype']==$childtype) {
			 $optionchildtype .= ' selected="selected" ';
			 } 
			$optionchildtype .= '>'.$val['childtype'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		
		
		//商品类型
		$shoptype_arr = array(
				
				'1' => '虚拟',
				'2' => '实物',
		);
		
		$optionxuni = '<option value="">商品类型</option>';
		foreach($shoptype_arr as $keys => $vals ) {
			$optionxuni .= '<option value="'.$keys.'"';
			if($keys==$mtype) {
				$optionxuni .= ' selected="selected" ';
			}
			$optionxuni .= '>'.$vals.'</option>';
		}
		$this -> assign('optionxuni',$optionxuni);
		
		//是否审核
		$check_arr = array(
				
				'1' => '审核成功',
				'2' => '审核失败',
				'3' => '未审核',
		);
		
		$optioncheckarr = '<option value="">是否审核</option>';
		foreach($check_arr as $keys => $vals ) {
			$optioncheckarr .= '<option value="'.$keys.'"';
			if($keys==$checkstatus) {
				$optioncheckarr .= ' selected="selected" ';
			}
			$optioncheckarr .= '>'.$vals.'</option>';
		}
		$this -> assign('optioncheckarr',$optioncheckarr);
		
		
		//是否上架
		$onsalearr = array(
				
				'1' => '上架',
				'2' => '下架',
				'3' => '违规下架',
		);
		
		$optiononsalearr = '<option value="">是否上架</option>';
		foreach($onsalearr as $keys => $vals ) {
			$optiononsalearr .= '<option value="'.$keys.'"';
			if($keys==$is_on_sale) {
				$optiononsalearr .= ' selected="selected" ';
			}
			$optiononsalearr .= '>'.$vals.'</option>';
		}
		$this -> assign('optiononsalearr',$optiononsalearr);
		 
		 
		//-----------------------------------------------------------
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//生成where条件判断字符串
		$sql_where = "flag=1  and siteid='".$siteid."' and " ;
		 
		if($mtype!='') {
			if($mtype=='1'){//虚拟商品
				$sql_where .= " typeid in $xuniarr and ";
			}else if($mtype=='2'){//实物
				$sql_where .= " typeid in $shiwuarr and ";
			}
		}
		 
		if($checkstatus!='') {
			$sql_where .= " status='".$checkstatus."' and ";
		}
		 
		if($childtype!='') {
			$sql_where .= " typeidchild='".$childtype."' and ";
		}
		 
		if($is_on_sale!='') {
			$sql_where .= " onsales='".$is_on_sale."' and ";
		}
		 
		if($good_name!='') {
			$sql_where .= " name like'%".$good_name."%' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		 
		//生成排序字符串数据
		$sql_order = " id desc ";
		 
		 
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_product')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		 
		//执行SQL查询语句
		$list  = $Model -> table('shop_product')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//echo $Model->getLastsql();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		 
		foreach($list as $keyc => $valc) {
			
			$list[$keyc]['siteid'] = isset($sitearr[$list[$keyc]['siteid']])?$sitearr[$list[$keyc]['siteid']]:$list[$keyc]['siteid'];
	
			if($list[$keyc]['status']=='1'){//审核通过
				$list[$keyc]['status'] = '<font style="background-color:#00EA00;padding:2px">审核成功</font>';
			}else if($list[$keyc]['status']=='2'){//审核不通过
				$list[$keyc]['status'] = '<font style="background-color:#FB3C16;padding:2px">审核失败</font>';
			}else if($list[$keyc]['status']=='3'){//待审核
				$list[$keyc]['status'] = '<font style="background-color:#EDDC1D;padding:2px">待审核</font>';
			}
			
			$list[$keyc]['mainpic'] = hy_qiniuimgurl('duibao-shop',$list[$keyc]['mainpic'],'800',800,$canshu=true);
			$list[$keyc]['showpic1'] = hy_qiniuimgurl('duibao-shop',$list[$keyc]['mainpic'],'800',800,$canshu=true);
	
			if($list[$keyc]['onsales']=='1'){
				$list[$keyc]['onsaless'] = '已上架';
			}else if($list[$keyc]['onsales']=='2'){
				$list[$keyc]['onsaless'] = '未上架';
			}else if($list[$keyc]['onsales']=='3'){
				$list[$keyc]['onsaless'] = '违规下架';
			}
			
			if($list[$keyc]['feetype']=='1'){
				$list[$keyc]['feetype'] = '积分';
			}else if($list[$keyc]['feetype']=='2'){
				$list[$keyc]['feetype'] = '金额';
			}else if($list[$keyc]['feetype']=='3'){
				$list[$keyc]['feetype'] = '积分金额混合支付';
			}
			
			if($list[$keyc]['pickup']=='1'){
				$list[$keyc]['pickup'] = '自提';
			}else if($list[$keyc]['pickup']=='2'){
				$list[$keyc]['pickup'] = '包邮';
			}else if($list[$keyc]['pickup']=='3'){
				$list[$keyc]['pickup'] = '不包邮';
			}
			
			$list[$keyc]['price'] = number_format($list[$keyc]['price']/100, 2) ;
	
			//商品类型
			$list[$keyc]['typeidchild'] = isset($typechildarr[$list[$keyc]['typeidchild']])?$typechildarr[$list[$keyc]['typeidchild']]:$list[$keyc]['typeidchild'];
	
		}
		 
		$this -> assign('list',$list);
		 
		 
		$this->display();
	}
	
	
	//商品的添加
	public function shopadd(){
		
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		
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
		$sitesql = "select id,name,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		
		$optionsiteid = '<option value=""></option>';
		foreach($sitelist as $keyc => $valc) {
			$optionsiteid .= '<option value="'.$valc['id'].'" ';
			/* if($sitelist[0]['flag']==$keyc) { $optionsiteid .= ' selected="selected" '; } */
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
		
		
		//支付类型
		$zhifu_arr = array(
				'1' => '积分',
				'2' => '金额',
				'3' => '混合使用',
				'4' => 'vip免费',
				'5' => '抽奖免费',
		);
		//$optionfeetype = '<option value="">商品支付方式</option>';
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
		//$optionpickup = '<option value="">商品领取方式</option>';
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
			
		);
		$optionmiyaotype = '<option value=""></option>';
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
		$optionfafang = '<option value=""></option>';
		foreach($fafang_arr as $keyc => $valc) {
			$optionfafang .= '<option value="'.$keyc.'" ';
			/* if($status==$keyc) { $optionstatus .= ' selected="selected" '; } */
			$optionfafang .= '>'.$valc.'</option>';
		}
		$this->assign('optionfafang',$optionfafang);
		
		
		// 输出模板
		$this->display();
		
	}
	
	//流量商品展示页
	public function shopadd_xuni(){
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$Model = new Model();
		
		//渠道编号
		$sitesql = "select id,name,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		
		$optionsiteid = '<option value=""></option>';
		foreach($sitelist as $keyc => $valc) {
			$optionsiteid .= '<option value="'.$valc['id'].'" ';
			/* if($sitelist[0]['flag']==$keyc) { $optionsiteid .= ' selected="selected" '; } */
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
			$optionxushitype .= '>'.$valc.'</option>';
		}
		$this->assign('optionxushitype',$optionxushitype);
		
		
		//商品编号--主类型
		$typearr = array();
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
		$sql_childtype = "select * from db_goods_type  where flag=1 and level=2 order by id desc";
		$list_childtype = $Model->query($sql_childtype);
		
		$optionchildtype = '<option value="">请选择商品子分类</option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['id'].'"';
			$optionchildtype .= '>'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		
		
		//支付类型
		$zhifu_arr = array(
				'1' => '积分',
				'2' => '金额',
				'3' => '混合使用',
				'4' => 'vip免费',
				'5' => '抽奖免费',
		);
		
		$optionfeetype = '<option value="">商品支付方式</option>';
		foreach($zhifu_arr as $keyc => $valc) {
			$optionfeetype .= '<option value="'.$keyc.'" ';
			$optionfeetype .= '>'.$valc.'</option>';
		}
		$this->assign('optionfeetype',$optionfeetype);
		
		// 输出模板
		$this->display('Shop:shopadd_xuni');
		
	}
	//实物商品
	public function shopadd_shiwu(){
		
		//商户编号id
		$siteid   = session(HYSESSQZ.'siteid');
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$Model = new Model();
		
		//渠道编号
		$sitesql = "select id,name,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		
		$optionsiteid = '<option value=""></option>';
		foreach($sitelist as $keyc => $valc) {
			$optionsiteid .= '<option value="'.$valc['id'].'" ';
			/* if($sitelist[0]['flag']==$keyc) { $optionsiteid .= ' selected="selected" '; } */
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
			$optionxushitype .= '>'.$valc.'</option>';
		}
		$this->assign('optionxushitype',$optionxushitype);
		
		
		//商品编号--主类型
		$typearr = array();
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
		$sql_childtype = "select * from db_goods_type  where flag=1 and level=2 order by id desc";
		$list_childtype = $Model->query($sql_childtype);
		
		$optionchildtype = '<option value="">请选择商品子分类</option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['id'].'"';
			$optionchildtype .= '>'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		
		
		//支付类型
		$zhifu_arr = array(
				'1' => '积分',
				'2' => '金额',
				'3' => '混合使用',
				'4' => 'vip免费',
				'5' => '抽奖免费',
		);
		
		$optionfeetype = '<option value="">商品支付方式</option>';
		foreach($zhifu_arr as $keyc => $valc) {
			$optionfeetype .= '<option value="'.$keyc.'" ';
			$optionfeetype .= '>'.$valc.'</option>';
		}
		$this->assign('optionfeetype',$optionfeetype);
		
		
		// 输出模板
		$this->display();
		
	}
	
	
	
	
	//商品的添加入库
	public function  shopaddata(){
		
		//商户编号
		$siteid   = session(HYSESSQZ.'siteid');
		
		$Model = new Model();
		
		//判断该商品是否通过审核
		$checksql = "select checkstatus,storestatus from shop_site where id='".$siteid."'";
		$checklist = $Model->query($checksql);
		
		if(count($checklist)<=0){
			echo "<script>alert('非法操作！'); history.go(-1);</script>";
			$this->error('非法操作！');
			
		}else{
			
			if($checklist[0]['checkstatus']!='2' || $checklist[0]['storestatus']!='2'){
				echo "<script>alert('该商户没通过审核，不可以发布商品！'); history.go(-1);</script>";
				$this->error('该商户没通过审核，不可以发布商品！');
			}else{
				
				//获取相应的参数
				$name   = trim($this->_post('name'));
				$goods_sn   = $this->_post('goods_sn');
				$mtype   = $this->_post('mtype');
				$type    = $this->_post('type');
				$typeid  = $this->_post('typeid');
				$price    = ($this->_post('price'))*100;
				$yuanprice    = ($this->_post('yuanprice'))*100;
				$score    = $this->_post('score');
				$miaoshu  = $this->_post('miaoshu');
				$feetype  = $this->_post('feetype');
				$miyao_type  = $this->_post('miyao_type');
				$fafang_type  = $this->_post('fafang_type');
				$pickup   = $this->_post('pickup');
				$kucun        = $this->_post('kucun');
				$daymax       = $this->_post('daymax');
				$userdaymax   = $this->_post('userdaymax');
				$usermonthmax = $this->_post('usermonthmax');
				$userallmax   = $this->_post('userallmax');
				$orderbyid    = $this->_post('orderbyid');
				$remark    = $this->_post('remark');
				$create_datetime    = date('Y-m-d H:i:s');
				$youxiaoqi    = $this->_post('youxiaoqi');
				//流量参数
				$gateway    = $this->_post('gateway');
				$mbps    = $this->_post('mbps');
				$ttype    = $this->_post('ttype');
				$video_url    = $this->_post('video_url');
				$prize_url    = $this->_post('prize_url');
				
				//不同类型页面参数
				$stype = $this->_post('stype');
				
				//商品描述
				$goods_content  = $this->_post('goods_content');
				//详情页链接
				$xiangqingurl     = 'http://xbapp.xinyouxingkong.com//web/xiangqing.php?name='.$name;
				//$xiangqingurl     = 'http://120.27.34.239:8018/web/xiangqing.php?name='.$name;
				
				$update_submit = $this->_post('uupdate_submit');
				
				if($update_submit==''){
					echo "<script>alert('非法操作！'); history.go(-1);</script>";
					$this->error('非法操作！');
						
				}else{
					 if($siteid==''){
						echo "<script>alert('非法操作！'); history.go(-1);</script>";
						$this->error('非法操作！');
					}
					
					if($name==''){
						echo "<script>alert('商品名称不能为空！'); history.go(-1);</script>";
						$this->error('商品名称不能为空！');
					}
					
					/* if($mtype==''){
						echo "<script>alert('商品类型不能为空！'); history.go(-1);</script>";
						$this->error('商品类型不能为空！');
					}
					
					if($type==''){
						echo "<script>alert('商品分类不能为空！'); history.go(-1);</script>";
						$this->error('商品分类不能为空！');
					}
					
					if($typeid==''){
						echo "<script>alert('商品子分类不能为空！'); history.go(-1);</script>";
						$this->error('商品子分类不能为空！');
					} */
					
					
					
					if($kucun==''|| !is_numeric($kucun) || $kucun<10){
						echo "<script>alert('商品库存不能为空且必须为数字大于10！'); history.go(-1);</script>";
						$this->error('商品库存不能为空且必须为数字大于10！');
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
						
						
					//判断该数据是否已经存在
					$repeatdatasql = "select id from shop_product where name='".$name."' and siteid='".$siteid."' and  typeid='".$type."' and typeidchild='".$typeid."' ";
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
						$upload->savePath =  XMAINPATH.'/Public/Uploads/shoppic/';// 设置附件上传目录
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
						
						//print_r($info);exit;
						$data = array();
				
						for ($i=0;$i<=3;$i++){//./Public/Uploads/shoppic/59ffdcd2987f0.png  $filename=md5(uniqid()).'.'.$ext;
				
							if($info[$i]['key']=='mainpic'){
								$mainpic = $info[$i]['savepath'].$info[$i]['savename'];
								$r = upload_qiniu('duibao-shop',$mainpic,$info[$i]['savename'],'no');
								delfile($mainpic);
								$data['mainpic'] = $r;
							}
								
							if($info[$i]['key']=='showpic1'){
								$showpic1 = $info[$i]['savepath'].$info[$i]['savename'];
								$r = upload_qiniu('duibao-shop',$showpic1,$info[$i]['savename'],'no');
								delfile($showpic1);
								$data['showpic1'] = $r;
							}
							if($info[$i]['key']=='showpic2'){
								$showpic2 = $info[$i]['savepath'].$info[$i]['savename'];
								$r = upload_qiniu('duibao-shop',$showpic2,$info[$i]['savename'],'no');
								delfile($showpic2);
								$data['showpic2'] = $r;
							}
							if($info[$i]['key']=='showpic3'){
								$showpic3 = $info[$i]['savepath'].$info[$i]['savename'];
								$r = upload_qiniu('duibao-shop',$showpic3,$info[$i]['savename'],'no');
								delfile($showpic3);
								$data['showpic3'] = $r;
							}
						}
						
						if($stype=='liuliang'){
							$data['score']      =$score;
							$data['miyao_type'] ='1';
							$data['fafang_type'] ='1';
							$data['xushi_type'] = '1';
							$data['pickup'] = '2';
							$data['gateway'] = $gateway;
							$data['mbps'] = $mbps;
							$data['ttype'] = $ttype;
							$data['typeidchild'] = $typeid;
							$data['typeid'] = '11';
							$data['feetype'] = $feetype;
							$data['tpl'] = '1';
							
						}else if($stype=='xuni'){
							
							$data['xushi_type']  = $mtype;
							$data['typeidchild'] = $typeid;
							$data['typeid']      = $type;
							$data['miyao_type']  =$miyao_type;
							$data['pickup']      =$pickup;
							$data['fafang_type'] =$fafang_type;
							$data['price']        =$price;
							$data['score']        =$score;
							$data['video_url']    =$video_url;
							//$data['prize_url']    =$prize_url;
							$data['youxiaoqi']    = $youxiaoqi;
							$data['feetype'] = $feetype;
							$data['tpl'] = '2';
							
						}else if($stype=='shiwu'){
							$data['xushi_type']    = '2';
							$data['miyao_type']    ='4';
							$data['fafang_type']   ='2';
							$data['feetype']   ='2';
							$data['price']         =$price;
							$data['yuanprice']     =$yuanprice;
							$data['typeidchild'] = $typeid;
							$data['typeid']      = $type;
							$data['tpl'] = '3';
						}
						
						$data['name']       = $name;
						$data['goods_sn']   = $goods_sn;
						$data['siteid']     = $siteid;
						$data['miaoshu'] = $miaoshu;
						$data['kucun'] = $kucun;
						$data['daymax']= $daymax;
						$data['userdaymax'] = $userdaymax;
						$data['usermonthmax'] = $usermonthmax;
						$data['userallmax'] = $userallmax;
						$data['remark'] = $remark;
						$data['orderbyid'] = '100'; //排序字段
						$data['hottypeid'] = '100';
						$data['onsales'] = '2';
						
						$data['flag'] = '1';//待审核
						$data['create_datetime'] = $create_datetime;
						$data['xiangqingurl'] = $xiangqingurl;
						$data['goods_content'] = $goods_content;
						
						
						if($siteid=='1000'){
							$data['status'] = '1';//1000自己内部渠道
						}else{
							$data['status'] = '3';//待审核
						}
						
						//说明此数据没有关联数据，可以删除
						$ret = $Model -> table('shop_product') -> add($data);
						//echo $Model->getLastsql();exit;
						
						if($ret) {
							echo "<script>alert('商品添加成功，等待审核！');window.location.href='".__APP__."/Shop/shoplist".$yuurl."';</script>";
							$this -> success('商品添加成功，等待审核！','__APP__/Shop/shoplist'.$yuurl);
						}else {
							echo "<script>alert('商品添加失败！');history.go(-1);</script>";
							$this -> error('商品添加失败！');
						}
					}
				}
			}
	
	}
}
	
	
	//流量商品编辑页面
	public function shopedit_liuliang(){
		
		$id = $this->_post('id');
		
		$Model = new Model();
		
		$productsql  = "select * from shop_product where id='".$id."'";
		$productlist = $Model->query($productsql);
		
		if($productlist[0]['id']<=0){
			
			echo "<script>alert('非法操作！'); history.go(-1);</script>";
			$this->error('非法操作！');
			
		}else{
			
			//审核展示
			if($productlist[0]['status']=='1'){//审核通过
				$productlist[0]['status'] = '<font style="background-color:#00EA00;pading:2px;">审核成功</font>';
			}else if($productlist[0]['status']=='2'){//审核不通过
				$productlist[0]['status'] = '<font style="background-color:#FB3C16;pading:2px">审核失败</font>';
			}else if($productlist[0]['status']=='3'){//待审核
				$productlist[0]['status'] = '<font style="background-color:#EDDC1D;pading:2px">待审核</font>';
			}
			
			//流量商品类型
			$typearr = array(
					'32' =>'联通流量',
					'33' =>'移动流量',
					'34' =>'电信流量',
			);
			foreach($typearr as $key=>$val) {
				$optiontype .= '<option value="'.$key.'"';
				if($productlist[0]['typeidchild']==$key) {$optiontype .= ' selected="selected" ';}
				$optiontype .= '>'.$val.'</option>';
			}
			$this -> assign('optiontype',$optiontype);
			
			//运营商
			$gatearr = array(
					'1' =>'移动',
					'2' =>'联通',
					'3' =>'电信',
			);
			foreach($gatearr as $key=>$val) {
				$optiongate .= '<option value="'.$key.'"';
				if($productlist[0]['gateway']==$key) {$optiongate .= ' selected="selected" ';}
				$optiongate .= '>'.$val.'</option>';
			}
			$this -> assign('optiongate',$optiongate);
			
			//流量使用范围
			$ttypearr = array(
					'1' =>'全国',
					'2' =>'省内',
			);
			foreach($ttypearr as $key=>$val) {
				$optionttype .= '<option value="'.$key.'"';
				if($productlist[0]['ttype']==$key) {$optionttype .= ' selected="selected" ';}
				$optionttype .= '>'.$val.'</option>';
			}
			$this -> assign('optionttype',$optionttype);
			
			
			//支付类型
			$zhifu_arr = array(
					'1' => '积分',
					'2' => '金额',
					'3' => '积分金额混合',
					'4' => '免费（vip）',
					'5' => '免费（抽奖）',
			);
			$optionfeetype = '<option value=""></option>';
			foreach($zhifu_arr as $keyc => $valc) {
				$optionfeetype .= '<option value="'.$keyc.'" ';
				if($productlist[0]['feetype']==$keyc) { $optionfeetype .= ' selected="selected" '; }
				$optionfeetype .= '>'.$valc.'</option>';
			}
			$this->assign('optionfeetype',$optionfeetype);
			
			
			//商品价格的展示
			if($productlist[0]['price'] ==''){
				$productlist[0]['price']='0';
			}else{
				$productlist[0]['price'] = number_format($productlist[0]['price'] / 100, 2);
			}
			
			$this->assign('list',$productlist[0]);
		}
		
		// 输出模板
		$this->display();
	}
	
	
	//商品编辑页面
	public function shopedit(){
		
		$id = $this->_post('id');
		
		$Model = new Model();
		
		$productsql  = "select * from shop_product where id='".$id."'";
		$productlist = $Model->query($productsql);
		
		//echo $productlist[0]['feetype'];
		
		if($productlist[0]['id']<=0){
			
			echo "<script>alert('非法操作！'); history.go(-1);</script>";
			$this->error('非法操作！');
			
		}else{
			
			//审核展示
			if($productlist[0]['status']=='1'){//审核通过
				$productlist[0]['status'] = '<font style="background-color:#00EA00;pading:2px;">审核成功</font>';
			}else if($productlist[0]['status']=='2'){//审核不通过
				$productlist[0]['status'] = '<font style="background-color:#FB3C16;pading:2px">审核失败</font>';
			}else if($productlist[0]['status']=='3'){//待审核
				$productlist[0]['status'] = '<font style="background-color:#EDDC1D;pading:2px">待审核</font>';
			}
			
			//商品虚实
			$xushitype = array(
					'1' => '虚拟',
					'2' => '实物',
			);
			
			$optionxushitype = '<option value="">请选择商品类型</option>';
			foreach($xushitype as $keyc => $valc) {
				$optionxushitype .= '<option value="'.$keyc.'" ';
				if($productlist[0]['xushi_type']==$keyc) {$optionxushitype .= ' selected="selected" ';}
				$optionxushitype .= '>'.$valc.'</option>';
			}
			$this->assign('optionxushitype',$optionxushitype);
			
			
			//商品编号--主类型
			 $typearr = array();
			//$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
			$sql_type = "select * from db_goods_type where flag=1 and level=1  order by  id desc ";
			$list_type = $Model->query($sql_type);
			
			$optiontype = '<option value=""></option>';
			foreach($list_type as $val) {
				$optiontype .= '<option value="'.$val['id'].'"';
				if($productlist[0]['typeid']==$val['id']) {$optiontype .= ' selected="selected" ';}
				$optiontype .= '>'.$val['name'].'</option>';
			}
			$this -> assign('optiontype',$optiontype); 
			
			
			//商品编号--子类型
			$typearr = array();
			//$sql_childtype = "select type,childtype,xushi,name from shop_type order by id asc";
			$sql_childtype = "select * from db_goods_type  where flag=1 and level=2 order by id desc";
			$list_childtype = $Model->query($sql_childtype);
			
			$optionchildtype = '<option value=""></option>';
			foreach($list_childtype as $val) {
				$optionchildtype .= '<option value="'.$val['id'].'"';
				if($productlist[0]['typeidchild']==$val['id']) { $optionchildtype .= ' selected="selected" '; }
				$optionchildtype .= '>'.$val['name'].'</option>';
			}
			$this -> assign('optionchildtype',$optionchildtype);
			
			
			//支付类型
			$zhifu_arr = array(
					'1' => '积分',
					'2' => '金额',
					'3' => '积分金额混合',
					'4' => '免费（vip）',
					'5' => '免费（抽奖）',
			);
			$optionfeetype = '<option value=""></option>';
			foreach($zhifu_arr as $keyc => $valc) {
				$optionfeetype .= '<option value="'.$keyc.'" ';
				if($productlist[0]['feetype']==$keyc) { $optionfeetype .= ' selected="selected" '; }
				$optionfeetype .= '>'.$valc.'</option>';
			}
			$this->assign('optionfeetype',$optionfeetype);
			
			
			//商品的领取方式
			$pickup_arr = array(
					'1' => '自提',
					'2' => '包邮',
			);
			foreach($pickup_arr as $keyc => $valc) {
				if($productlist[0]['pickup']==$keyc) { 
					$optionpickup = ' checked="checked" '; 
				}else{
					$optionpickup='';
				} 
			}
			$this->assign('optionpickup',$optionpickup);
			
			
			
			//秘钥形式
			$miyao_arr = array(
					'1' => '单卡密',
					'2' => '多卡密',
					'3' => '二维码',
					'4' => '实物'
						
			);
			$optionmiyaotype = '<option value=""></option>';
			foreach($miyao_arr as $keyc => $valc) {
				$optionmiyaotype .= '<option value="'.$keyc.'" ';
				 if($productlist[0]['miyao_type']==$keyc) { $optionmiyaotype .= ' selected="selected" '; } 
				$optionmiyaotype .= '>'.$valc.'</option>';
			}
			$this->assign('optionmiyaotype',$optionmiyaotype);
			
			
			//卡密发放方式
			$fafang_arr = array(
					'1' => '即时发放',
					'2' => '后台发放',
			);
			$optionfafang = '';
			foreach($fafang_arr as $keyc => $valc) {
				 if($productlist[0]['fafang_type']==$keyc) { $optionfafang .= ' checked="checked" '; } 
			}
			$this->assign('optionfafang',$optionfafang);
			
			//商品价格的展示
			if($productlist[0]['price'] ==''){
				$productlist[0]['price']='0';
			}else{
				$productlist[0]['price'] = number_format($productlist[0]['price'] / 100, 2);
			}
			
			$productlist[0]['yuanprice'] = number_format($productlist[0]['yuanprice'] / 100, 2);
			
			$this->assign('list',$productlist[0]);
		}
		
		// 输出模板
		$this->display();
	}
	
	
	
	
	//商品修改数据的入库
	public function shopeditdata(){
		
		$tpl  = $this->_post('tpl');//模板参数
		
		$id = $this->_post('id');
		$name = trim($this->_post('name'));
		$goods_sn = $this->_post('goods_sn');
		$mtype = $this->_post('mtype');
		$type = $this->_post('type');
		$typeid = $this->_post('typeid');
		$price = $this->_post('price')*100;
		$yuanprice = $this->_post('yuanprice')*100;
		$score = $this->_post('score');
		$miaoshu = $this->_post('miaoshu');
		$feetype = $this->_post('feetype');
		$pickup = $this->_post('pickup');
		$kucun = $this->_post('kucun');
		$daymax = $this->_post('daymax');
		$userdaymax = $this->_post('userdaymax');
		$usermonthmax = $this->_post('usermonthmax');
		$userallmax = $this->_post('userallmax');
		//$orderbyid = $this->_post('orderbyid');
		$orderbyid = '100';
		$youxiaoqi    = $this->_post('youxiaoqi');
		$remark    = $this->_post('remark');
		$miyao_type  = $this->_post('miyao_type');
		$fafang_type  = $this->_post('fafang_type');
		$gateway = $this->_post('gateway');
		$mbps  = $this->_post('mbps');
		$ttype  = $this->_post('ttype');
		$tpl  = $this->_post('tpl');//模板参数
		$video_url  = $this->_post('video_url');//模板参数
		
		if($tpl=='1'){
			$pickup='2';
			$miyao_type='1';
			$fafang_type='1';
			$youxiaoqi='0';
			$type='11';
			$video_url='';
		}else if($tpl=='2'){//虚拟商品
			$yuanprice=0;
			$mbps=0;
			$ttype=0;
			$gateway=0;
			
		}else if($tpl=='3'){
			$feetype='2';
			$miyao_type='4';
			$fafang_type='2';
			$video_url='';
			$youxiaoqi=0;
			$score=0;
			$mbps=0;
			$ttype=0;
			$gateway=0;
		}
		
		
		//商品详情描述
		$goods_content = $this->_post('goods_content');
		//商品详情页链接
		//$xiangqingurl = 'http://xbapp.xinyouxingkong.com//web/xiangqing.php?name='.$name;
		$xiangqingurl     = 'http://120.27.34.239:8018/web/xiangqing.php?name='.$name;
		
		//图片的上传
		import('ORG.UploadFile');
			
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','rar','pdf','txt','apk');//设置附件上传类型
		$upload->savePath = XMAINPATH.'/Public/Uploads/shoppic/'.date('Y-m').'/';// 设置附件上传目录
		
		$upload->thumb = true;
		$upload->thumbMaxHeight = '300';
		
		//判断该目录是否存在
		if(!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777,true);
		}
		
		//数据库初始化
		$Model = new Model();
		
		$infodata = $upload->upload();
		
		if($infodata===true){
			
			$info =  $upload->getUploadFileInfo();
			
			//图片的删除
			$datasql = "select mainpic,showpic1,showpic2,showpic3 from shop_product where id='".$id."'";
			$datalist = $Model->query($datasql);
		}
		
		$data = array();
		
		for ($i=0;$i<=3;$i++){
		
			if($info[$i]['key']=='mainpic'){
				//删除以前上传的图片
				delete_qiniu('duibao-shop',$datalist[0]['mainpic']);
				$mainpic = $info[$i]['savepath'].$info[$i]['savename'];
				$r = upload_qiniu('duibao-shop',$mainpic,$info[$i]['savename'],'no');
				delfile($mainpic);
				$data['mainpic'] = $r;
			}

			if($info[$i]['key']=='showpic1'){
				//删除以前上传的图片
				delete_qiniu('duibao-shop',$datalist[0]['showpic1']);
				$showpic1 = $info[$i]['savepath'].$info[$i]['savename'];
				$r = upload_qiniu('duibao-shop',$showpic1,$info[$i]['savename'],'no');
				delfile($showpic1);//删除本地图片
				$data['showpic1'] = $r;
			}
			
			if($info[$i]['key']=='showpic2'){
				//删除以前上传的图片
				delete_qiniu('duibao-shop',$datalist[0]['showpic2']);
				$showpic2 = $info[$i]['savepath'].$info[$i]['savename'];
				$r = upload_qiniu('duibao-shop',$showpic2,$info[$i]['savename'],'no');
				delfile($showpic2);
				$data['showpic2'] = $r;
			}
			
			if($info[$i]['key']=='showpic3'){
				//删除以前上传的图片
				delete_qiniu('duibao-shop',$datalist[0]['showpic3']);
				$showpic3 = $info[$i]['savepath'].$info[$i]['savename'];
				$r = upload_qiniu('duibao-shop',$showpic3,$info[$i]['savename'],'no');
				delfile($showpic3);
				$data['showpic3'] = $r;
			}
		}
		
		$data['name']       = $name;
		$data['goods_sn']   = $goods_sn;
		$data['typeid']     = $type;
		$data['typeidchild'] = $typeid;
		$data['miaoshu']     = $miaoshu;
		$data['price']       = $price;
		$data['score']       = $score;
		$data['feetype']     = $feetype;
		$data['pickup']      = $pickup;
		$data['kucun']       = $kucun;
		$data['daymax']      = $daymax;
		$data['userdaymax']   = $userdaymax;
		$data['usermonthmax'] = $usermonthmax;
		$data['userallmax']   = $userallmax;
		$data['remark']       = $remark;
		$data['orderbyid']     = $orderbyid;
		$data['hottypeid'] = '100';
		$data['onsales'] = '2';
		$data['status'] = '3';//待审核
		$data['flag'] = '1';//待审核
		$data['goods_content'] = $goods_content;
		$data['xiangqingurl'] = $xiangqingurl;
		$data['youxiaoqi'] = $youxiaoqi;
		$data['gateway'] = $gateway;
		$data['ttype'] = $ttype;
		$data['mbps'] = $mbps;
		$data['yuanprice'] = $yuanprice;
		
		$data['miyao_type'] = $miyao_type;
		$data['fafang_type'] = $fafang_type;
		$data['video_url'] = $video_url;
		
		
		//说明此数据没有关联数据，可以删除
		$ret = $Model -> table('shop_product') ->where("id='".$id."'") ->save($data);
		
		if($ret) {
			echo "<script>alert('商品修改成功，等待审核！');window.location.href='".__APP__."/Shop/shoplist".$yuurl."';</script>";
			$this -> success('商品修改成功，等待审核！','__APP__/Shop/shoplist'.$yuurl);
		}else {
			echo "<script>alert('商品修改失败！');history.go(-1);</script>";
			$this -> error('商品修改失败！');
		}
	}
	
	
		
		//数据的删除
		public function deletedata(){
		
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
		
			$id = $this->_post('id');
		
			$Model = new Model();
		
			$datasql = "select mainpic,showpic1,showpic2,showpic3 from shop_product where id='".$id."'";
			$datalist = $Model->query($datasql);
			
			delete_qiniu('duibao-shop',$datalist[0]['mainpic']);
			delete_qiniu('duibao-shop',$datalist[0]['showpic1']);
			delete_qiniu('duibao-shop',$datalist[0]['showpic2']);
			delete_qiniu('duibao-shop',$datalist[0]['showpic3']);
		
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('shop_product') -> where("id='".$id."'") -> delete();
		
			if($ret) {
				echo "<script>alert('商品删除成功！');window.location.href='".__APP__."/Shop/shoplist".$yuurl."';</script>";
				$this -> success('商品删除成功!','__APP__/Shop/shoplist'.$yuurl);
			}else {
				echo "<script>alert('商品删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('商品删除失败，系统错误!');
			}
		}
		
		
		//商品发布
		public function ajax_fabu(){
			
			$type = $this->_get('type');
			
			//对应的商户编号
			$siteid   = session(HYSESSQZ.'siteid');
			
			$Model = new Model();
			
			$usersql  = "select checkstatus,storestatus from shop_site where id='".$siteid."'";
			$userlist = $Model->query($usersql); 
			
			if($userlist[0]['checkstatus']!='2' || $userlist[0]['storestatus']!='2'){//2-审核成功
				echo 'usuccess';
			}
		}
		
		
		
		//ajax---商品上架 和 下架
		/**
		 * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
		 * table,id_name,id_value,field,value   changeTableVal('goods','goods_id','27','is_on_sale',this)
		 */
		 public function changeTableVal(){
		 	
		 	//"id_value="+id_value+"&value="+value,
		 	$id_value = $this->_get('id_value');//获取商品id
		 	$value    = $this->_get('value');//状态
		 	
		 	$Model = new Model();
		 	
		 	//判断该商品是否审核通过
		 	$checkstatus_sql = "select status from shop_product where id='".$id_value."'  ";
		 	$checkstatus_list = $Model->query($checkstatus_sql);
		 	
		 	if($checkstatus_list[0]['status']!='1'){//不可以上架
		 		echo '3';
		 	}else{
		 		
		 		if($value=='1'){
		 			$updatesql  = "update shop_product set onsales='".$value."',start_datetime='".date('Y-m-d H:i：s')."' where id='".$id_value."'";
		 		}else if($value=='2'){
		 			$updatesql  = "update shop_product set onsales='".$value."',stop_datetime='".date('Y-m-d H:i：s')."' where id='".$id_value."'";
		 		}
		 		
		 		$updatelist = $Model->execute($updatesql);
		 		
		 		if($updatelist){
		 			if($value=='1'){//上架
		 				echo '1';
		 			}else if($value=='2'){//下架
		 				echo '2';
		 			}
		 		}else{
		 			echo 'error';
		 		}
		 		
		 	}
		 	
		}
		
		//联动菜单
		public function ajax_munu(){
			
			//echo 'iii';exit;
			$Model = new Model();
			$pid = $this->_post('pid');
			
			$sql_childtype = "select id,name,pid from db_goods_type  where pid='".$pid."' and flag=1  ";
			$list_childtype = $Model->query($sql_childtype);
			
			$pusharray = 
			array( 'id' => '',
                  'name' =>'请选择商品子分类',
                  'pid' => ''
			);
			array_push($list_childtype,$pusharray);
			sort($list_childtype);
			
			$data = json_encode($list_childtype);
			echo $data;
			
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
}