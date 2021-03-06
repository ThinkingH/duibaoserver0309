<?php


class MenuAction extends Action {
	//菜单模板模块
	
	
	//定义各模块锁定级别
	private $lock_index = '97531';
	
	
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		
		//-------------------------------------------
		//获取用户session标识，主要用于配合日志判断用户访问
		$userri   = session(HYSESSQZ.'username');
		$rootflag = session(HYSESSQZ.'rootflag'); //权限标识
		//-------------------------------------------
		
		
		
		
		//菜单链接数据存放数组
		//----------------------------------------------------------------------------------------------------
		$urlarr9 = array(
				
				array(
						'murl_name' => '用户状态',
						'curl_name' => array(
								array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
						),
				),
				
				
				array(
						'murl_name' => '配置信息',
						'curl_name' => array(
								array('f', '版本信息管理' ,       '/Versionlist/index' , ),
								array('f', '首页类型管理' ,        '/Lunbotu/index' , ),
								array('f', '首页分类管理' ,        '/Maintype/index', ),
								array('f', '引导图管理' ,        '/Kaipingtu/index' , ),
								array('f', '特供好货' ,        '/Quantype/index', ),
								array('f', '兑换码信息管理' ,        '/Duihuanma/index', ),
								array('f', '每月礼包管理' ,        '/Monthprize/index', ),
								array('f', 'Push推送' ,        '/Push/index', ),
						),
				),
				array(
						'murl_name' => '用户管理',
						'curl_name' => array(
								
								array('f', '正式用户信息' ,        '/Userlist/normaluser' , ),
								array('f', '临时用户信息' ,        '/Userlist/tempuser' , ),
						),
				),
				
				array(
						'murl_name' => '附近管理',
						'curl_name' => array(
								array('f', '附近审核管理' ,        '/Fabulist/index', ),
								
						),
				),
				
				
				
				array(
						'murl_name' => '优惠信息管理',
						'curl_name' => array(
								
								array('f', '热门饭票管理' ,        '/Youhuiquan/index', ),
						),
				),
				
				array(
						'murl_name' => '商户信息管理',
						'curl_name' => array(
								
								array('f', '商户开户管理' ,    '/Shanghu/index' , ),
							/* 	array('f', '商户店铺管理' ,    '/Shanghu/store' , ), */
						),
				),
				
				array(
						'murl_name' => '商城配置信息',
						'curl_name' => array(
								array('f', '商城轮播图' ,    '/Shoplunbotu/index' , ),
								array('f', '商城类型' ,    '/Shoptype/maintype' , ),
								array('f', '商品类型' ,    '/Goodscategory/goodstype' , ),
								array('f', '商品分类' ,    '/Goodscategory/addcategory' , ),//categorylist
								array('f', '商品属性' ,    '/Goodscategory/attributelist' , ),
								array('f', '商品列表' ,    '/Shopadd/index' , ),
								
								
				
						),
				),
				
				array(
						'murl_name' => '商城订单查询',
						'curl_name' => array(
								array('f', '订单数据查询' ,    '/Orderlist/index' , ),
								array('f', '商品评价查询' ,    '/Orderlist/pingjia' , ),
								array('f', '虚拟订单查询' ,    '/Orderlist/dealorder' , ),
								array('f', '实物订单查询' ,    '/Orderlist/shiwuorder' , ),
								
				
						),
						
				),
				
				
				array(
						'murl_name' => '数据查询',
						'curl_name' => array(
								array('f', '积分变动记录' ,          '/Scoredata/scorechang' , ),
								array('f', '正常用户信息推送' ,   '/Scoredata/usertuisong' , ),
								array('f', '临时用户信息推送' ,   '/Scoredata/tempusertuisong' , ),
								array('f', '用户意见反馈' ,        '/Suggest/index' , ),
								array('f', '验证码发送' ,            '/Scoredata/codedata' , ),
								
						),
				),
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '用户操作日志记录' , '/Root/caozuo_log' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
						),
				),
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' ,  '/Main/index' , ),
								array('f', '用户密码修改' ,  '/Passwdrewrite/index' , ),
								array('t', '退出系统'    ,  '/Login/logout' , ),
						),
				),
				
				array(
						'murl_name' => '不在使用模块',
						'curl_name' => array(
								array('f', '任务列表信息' ,        '/Tasklist/index' , ),
								array('f', '广告上传管理' ,        '/Adverlist/index', ),
								array('f', '模块广告管理' ,        '/Guanggao/index', ),
								array('f', '产品信息管理' ,    '/Codelist/index' , ),
								array('f', '首页数据管理' ,        '/Firsttype/index', ),
								array('f', '下载任务查询' ,   '/Productlist/downloadtask' , ),
								array('f', '用户兑换记录' ,          '/Scoredata/duihuan' , ),
						),
				),
		);
		
		//----------------------------------------------------------------------------------------------------
		$urlarr7 = array(
				
				array(
						'murl_name' => '用户状态',
						'curl_name' => array(
								array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
						),
				),
				
				
				array(
						'murl_name' => '配置信息',
						'curl_name' => array(
								array('f', '版本信息管理' ,       '/Versionlist/index' , ),
								array('f', '首页类型管理' ,        '/Lunbotu/index' , ),
								array('f', '首页分类管理' ,        '/Maintype/index', ),
								array('f', '引导图管理' ,        '/Kaipingtu/index' , ),
								array('f', '特供好货' ,        '/Quantype/index', ),
								array('f', '兑换码信息管理' ,        '/Duihuanma/index', ),
								array('f', '每月礼包管理' ,        '/Monthprize/index', ),
								array('f', 'Push推送' ,        '/Push/index', ),
						),
				),
				array(
						'murl_name' => '用户管理',
						'curl_name' => array(
								
								array('f', '正式用户信息' ,        '/Userlist/normaluser' , ),
								array('f', '临时用户信息' ,        '/Userlist/tempuser' , ),
						),
				),
				
				array(
						'murl_name' => '附近管理',
						'curl_name' => array(
								array('f', '附近审核管理' ,        '/Fabulist/index', ),
								
						),
				),
				
				
				
				array(
						'murl_name' => '优惠信息管理',
						'curl_name' => array(
								
								array('f', '热门饭票管理' ,        '/Youhuiquan/index', ),
						),
				),
				
				array(
						'murl_name' => '商户信息管理',
						'curl_name' => array(
								
								array('f', '商户开户管理' ,    '/Shanghu/index' , ),
							/* 	array('f', '商户店铺管理' ,    '/Shanghu/store' , ), */
						),
				),
				
				array(
						'murl_name' => '商城配置信息',
						'curl_name' => array(
								array('f', '商城轮播图' ,    '/Shoplunbotu/index' , ),
								array('f', '商城类型' ,    '/Shoptype/maintype' , ),
								array('f', '商品类型' ,    '/Goodscategory/goodstype' , ),
								array('f', '商品分类' ,    '/Goodscategory/addcategory' , ),//categorylist
								array('f', '商品属性' ,    '/Goodscategory/attributelist' , ),
								array('f', '商品列表' ,    '/Shopadd/index' , ),
								
								
				
						),
				),
				
				array(
						'murl_name' => '商城订单查询',
						'curl_name' => array(
								array('f', '订单数据查询' ,    '/Orderlist/index' , ),
								array('f', '商品评价查询' ,    '/Orderlist/pingjia' , ),
								array('f', '虚拟订单查询' ,    '/Orderlist/dealorder' , ),
								array('f', '实物订单查询' ,    '/Orderlist/shiwuorder' , ),
								
				
						),
						
				),
				
				
				array(
						'murl_name' => '数据查询',
						'curl_name' => array(
								array('f', '积分变动记录' ,          '/Scoredata/scorechang' , ),
								array('f', '正常用户信息推送' ,   '/Scoredata/usertuisong' , ),
								array('f', '临时用户信息推送' ,   '/Scoredata/tempusertuisong' , ),
								array('f', '用户意见反馈' ,        '/Suggest/index' , ),
								array('f', '验证码发送' ,            '/Scoredata/codedata' , ),
								
						),
				),
				
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' ,  '/Main/index' , ),
								array('f', '用户密码修改' ,  '/Passwdrewrite/index' , ),
								array('t', '退出系统'    ,  '/Login/logout' , ),
						),
				),
				
				array(
						'murl_name' => '不在使用模块',
						'curl_name' => array(
								array('f', '任务列表信息' ,        '/Tasklist/index' , ),
								array('f', '广告上传管理' ,        '/Adverlist/index', ),
								array('f', '模块广告管理' ,        '/Guanggao/index', ),
								array('f', '产品信息管理' ,    '/Codelist/index' , ),
								array('f', '首页数据管理' ,        '/Firsttype/index', ),
								array('f', '下载任务查询' ,   '/Productlist/downloadtask' , ),
								array('f', '用户兑换记录' ,          '/Scoredata/duihuan' , ),
						),
				),
		);
		
		
		//----------------------------------------------------------------------------------------------------
		$urlarr5 = array(
				
				array(
						'murl_name' => '用户状态',
						'curl_name' => array(
								array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
						),
				),
				
				
				array(
						'murl_name' => '配置信息',
						'curl_name' => array(
								array('f', '版本信息管理' ,       '/Versionlist/index' , ),
								array('f', '首页类型管理' ,        '/Lunbotu/index' , ),
								array('f', '首页分类管理' ,        '/Maintype/index', ),
								array('f', '引导图管理' ,        '/Kaipingtu/index' , ),
								array('f', '特供好货' ,        '/Quantype/index', ),
								array('f', '每月礼包说明' ,        '/Monthprize/index', ),
						),
				),
				array(
						'murl_name' => '用户管理',
						'curl_name' => array(
								
								array('f', '正式用户信息' ,        '/Userlist/normaluser' , ),
								array('f', '临时用户信息' ,        '/Userlist/tempuser' , ),
						),
				),
				
				array(
						'murl_name' => '附近管理',
						'curl_name' => array(
								array('f', '发布审核管理' ,        '/Fabulist/index', ),
								
						),
				),
				
				
				
				array(
						'murl_name' => '优惠信息管理',
						'curl_name' => array(
								
								array('f', '热门饭票管理' ,        '/Youhuiquan/index', ),
						),
				),
				
				array(
						'murl_name' => '商户信息管理',
						'curl_name' => array(
								
								array('f', '商户开户管理' ,    '/Shanghu/index' , ),
								array('f', '商户店铺管理' ,    '/Shanghu/store' , ),
						),
				),
				
				array(
						'murl_name' => '商城配置信息',
						'curl_name' => array(
								array('f', '商城轮播图' ,    '/Shoplunbotu/index' , ),
								array('f', '商城类型' ,    '/Shoptype/maintype' , ),
								array('f', '商品类型' ,    '/Goodscategory/goodstype' , ),
								array('f', '商品分类' ,    '/Goodscategory/addcategory' , ),//categorylist
								array('f', '商品属性' ,    '/Goodscategory/attributelist' , ),
								array('f', '商品列表' ,    '/Shopadd/index' , ),
								
								
				
						),
				),
				
				array(
						'murl_name' => '商城订单查询',
						'curl_name' => array(
								array('f', '订单数据查询' ,    '/Orderlist/index' , ),
								array('f', '商品评价查询' ,    '/Orderlist/pingjia' , ),
								array('f', '兑换订单查询' ,    '/Orderlist/dealorder' , ),
								array('f', '实物订单发货' ,    '/Orderlist/shiwuorder' , ),
								
				
						),
				),
				
				
				array(
						'murl_name' => '数据查询',
						'curl_name' => array(
								array('f', '积分变动记录' ,          '/Scoredata/scorechang' , ),
								array('f', '正常用户信息推送' ,   '/Scoredata/usertuisong' , ),
								array('f', '临时用户信息推送' ,   '/Scoredata/tempusertuisong' , ),
								array('f', '用户意见反馈' ,        '/Suggest/index' , ),
								array('f', '验证码发送' ,            '/Scoredata/codedata' , ),
								
						),
				),
				
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' ,  '/Main/index' , ),
								array('f', '用户密码修改' ,  '/Passwdrewrite/index' , ),
								array('t', '退出系统'    ,  '/Login/logout' , ),
						),
				),
				
		);
		//----------------------------------------------------------------------------------------------------
		$urlarr3 = array(

				array(
						'murl_name' => '数据查询',
						'curl_name' => array(
								array('f', '用户信息' ,  '/Singer/userlist' , ),
								array('f', '界面编辑' ,  '/Singer/listdata' , ),
							/* 	array('f', '界面编辑2' ,  '/Singer/editlist' , ), */
						),
				),
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' ,  '/Main/index' , ),
								array('f', '用户密码修改' ,  '/Passwdrewrite/index' , ),
								array('t', '退出系统'    ,  '/Login/logout' , ),
						),
				),
		);
		
		
		//----------------------------------------------------------------------------------------------------
		$urlarr1 = array(
				
				array(
						'murl_name' => '用户状态',
						'curl_name' => array(
								array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
						),
				),
				
				array(
						'murl_name' => '用户管理',
						'curl_name' => array(
								
								array('f', '正式用户信息' ,        '/Userlist/normaluser' , ),
								array('f', '临时用户信息' ,        '/Userlist/tempuser' , ),
						),
				),
				
				
				array(
						'murl_name' => '优惠信息管理',
						'curl_name' => array(
								
								array('f', '热门饭票管理' ,        '/Youhuiquan/index', ),
						),
				),
				
				
				array(
						'murl_name' => '商城配置信息',
						'curl_name' => array(
								array('f', '商城轮播图' ,    '/Shoplunbotu/index' , ),
								array('f', '商城类型' ,    '/Shoptype/maintype' , ),
								array('f', '商品列表' ,    '/Shopadd/index' , ),
								
								
				
						),
				),
				
				array(
						'murl_name' => '商城订单查询',
						'curl_name' => array(
								array('f', '订单数据查询' ,    '/Orderlist/index' , ),
								array('f', '商品评价查询' ,    '/Orderlist/pingjia' , ),
								array('f', '兑换订单查询' ,    '/Orderlist/dealorder' , ),
								array('f', '实物订单发货' ,    '/Orderlist/shiwuorder' , ),
								
				
						),
				),
				
				
				array(
						'murl_name' => '数据查询',
						'curl_name' => array(
								array('f', '积分变动记录' ,          '/Scoredata/scorechang' , ),
								array('f', '正常用户信息推送' ,   '/Scoredata/usertuisong' , ),
								array('f', '临时用户信息推送' ,   '/Scoredata/tempusertuisong' , ),
								array('f', '用户意见反馈' ,        '/Suggest/index' , ),
								array('f', '验证码发送' ,            '/Scoredata/codedata' , ),
								
						),
				),
				
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' ,  '/Main/index' , ),
								array('f', '用户密码修改' ,  '/Passwdrewrite/index' , ),
								array('t', '退出系统'    ,  '/Login/logout' , ),
						),
				),
				
		);
		
		
		//----------------------------------------------------------------------------------------------------
		
		$urlarr = array();
		if($rootflag==9) {
			$urlarr = $urlarr9;
		}else if($rootflag==7) {
			$urlarr = $urlarr7;
		}else if($rootflag==5) {
			$urlarr = $urlarr5;
		}else if($rootflag==3) {
			$urlarr = $urlarr3;
		}else if($rootflag==1) {
			$urlarr = $urlarr1;
		}else {
			
		}
		
		//----------------------------------------------------------------------------------
		//循环遍历导航数据数组，对数据进行进一步处理操作
		foreach($urlarr as $keyu => $valu) {
			//判断链接字符长度，并对较短的链接字符补充空格到指定长度
			$len = strlen($urlarr[$keyu]['murl_name']);
			if($len<=15) {
				$urlarr[$keyu]['murl_name'] = $urlarr[$keyu]['murl_name'].str_repeat('&nbsp;', (15-$len));
			}
			
			//遍历所有子url链接，为所有链接添加对应用户标识，补充较短的链接长度
			foreach($urlarr[$keyu]['curl_name'] as $keyc => $valc) {
				
				$lencc = strlen($urlarr[$keyu]['curl_name'][$keyc][1]);
				if($lencc<=15) {
					$urlarr[$keyu]['curl_name'][$keyc][1] = $urlarr[$keyu]['curl_name'][$keyc][1].str_repeat('&nbsp;', (15-$len));
				}
				
				$urlarr[$keyu]['curl_name'][$keyc][2] = __APP__ . $urlarr[$keyu]['curl_name'][$keyc][2] . '?userxr='.$userri;
				
				
			}
			
		}
		//----------------------------------------------------------------------------------
		
		
		
		
		$this -> assign('urlarr',$urlarr);
		
		
		
		
		// 输出模板
		$this->display();
		
		
		
		
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