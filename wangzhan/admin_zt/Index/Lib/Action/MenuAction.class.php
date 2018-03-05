<?php


class MenuAction extends Action {
	//菜单模板模块
	
	
	//定义各模块锁定级别
	private $lock_index = 'nolock';
	
	
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		
		//-------------------------------------------
		//获取用户session标识，主要用于配合日志判断用户访问
		$userri  = session('username');
		$quanxian = session('rootflag');
		//-------------------------------------------
		
		
		
		
		//菜单链接数据存放数组
		//----------------------------------------------------------------------------------------------------
	if($quanxian==9||$quanxian==5||$quanxian==7){
		$urlarr = array(
				
// 				array(
// 						'murl_name' => '用户状态',
// 						'curl_name' => array(
// 								//array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
// 						),
// 				),
				array(
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '订单信息录入' ,  '/Reportdata/xiugaishow' , ),
								array('f', '订单信息审核' ,  '/Reportdata/index' , ),
								array('f', '完成订单查询' ,  '/Reportdata/senddata' , ),
								array('f', '作废订单查询' ,  '/Reportdata/zuofeichakan' , ),
						),
				),
				
				
				array(
						'murl_name' => '用户信息',
						'curl_name' => array(
								array('f', '用户信息' , '/Userdata/index' , ),
						),
				),
				
				
				array(
						'murl_name' => '用户操作',
						'curl_name' => array(
								array('f', '当前用户信息' , '/Main/index' , ),
								array('f', '用户密码修改' , '/Passwdrewrite/index' , ),
								array('t', '退出系统'    , '/Login/logout' , ),
						),
				),
				
		);
		
	}else{
		
		$urlarr = array(
		
		// 				array(
				// 						'murl_name' => '用户状态',
				// 						'curl_name' => array(
						// 								//array('f', '用户在线状态' ,    '/Taskrelease/userstate' , ),
						// 						),
				// 				),
		array(
				'murl_name' => '中铁数据查询',
				'curl_name' => array(
						array('f', '数据查询' ,  '/Reportdata/index' , ),
						/* array('f', '发布数据查询' ,  '/Reportdata/senddata' , ), */
				),
		),
		
		array(
				'murl_name' => '用户操作',
				'curl_name' => array(
						array('f', '当前用户信息' , '/Main/index' , ),
						array('f', '用户密码修改' , '/Passwdrewrite/index' , ),
						array('t', '退出系统'    , '/Login/logout' , ),
				),
		),
		
		);
	}
		if($quanxian==9) {
			$ttt = array(
					'murl_name' => '系统管理',
					'curl_name' => array(
							array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
							array('f', '管理员操作文档' ,  '/Root/roottext' , ),
							array('f', '管理公告修改' ,   '/Root/gonggao' , ),
							/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
					),
			);
			$urlarr[] = $ttt;
		}
		
		//----------------------------------------------------------------------------------------------------
		
		
		
		
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
				//为链接添加附加信息
				if(substr($urlarr[$keyu]['curl_name'][$keyc][2],0,7)=='http://') {
					//对外连接不增加任何参数
					
				}else {
					$urlarr[$keyu]['curl_name'][$keyc][2] = __APP__ . $urlarr[$keyu]['curl_name'][$keyc][2] . '?userxr='.$userri;
				}
				
				
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