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
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '数据查询' ,  '/Reportdata/index' , ),
								//array('f', '游戏数据分省查询' ,  '/Reportdata/report_province' , ),
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
				
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
								/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
						),
				)
			
				
				
		);
		
		
		//----------------------------------------------------------------------------------------------------
		$urlarr7 = array(
				
				array(
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '数据查询' ,  '/Reportdata/index' , ),
								//array('f', '游戏数据分省查询' ,  '/Reportdata/report_province' , ),
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
				
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
								/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
						),
				)
				
		);
		
		
		//----------------------------------------------------------------------------------------------------
		$urlarr5 = array(
				
				array(
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '数据查询' ,  '/Reportdata/index' , ),
								//array('f', '游戏数据分省查询' ,  '/Reportdata/report_province' , ),
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
				
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
								/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
						),
				)
		);
		//----------------------------------------------------------------------------------------------------
		$urlarr3 = array(
				
				array(
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '数据查询' ,  '/Reportdata/index' , ),
								//array('f', '游戏数据分省查询' ,  '/Reportdata/report_province' , ),
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
				
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
								/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
						),
				)
				
		);
		
		
		//----------------------------------------------------------------------------------------------------
		$urlarr1 = array(
				
				array(
						'murl_name' => '中铁数据查询',
						'curl_name' => array(
								array('f', '数据查询' ,  '/Reportdata/index' , ),
								//array('f', '游戏数据分省查询' ,  '/Reportdata/report_province' , ),
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
				
				array(
						'murl_name' => '系统管理',
						'curl_name' => array(
								array('f', '编辑操作用户' ,   '/Root/editoruser' , ),
								array('f', '管理员操作文档' ,  '/Root/roottext' , ),
								array('f', '管理公告修改' ,   '/Root/gonggao' , ),
								/* array('f', '数据临时设定' ,   '/Root/set_session_cpid_show' , ), */
						),
				)
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