<?php
/*
 * 意见反馈
 */
class SuggestAction extends Action{
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	
	
	
	
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
		$type        = $this->_get('type');
	
	
	
		//是否启用
		$flag_arr = array(
				'1' => '正常用户',
				'2' => '临时用户',
		);
		$optiontype = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optiontype .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optiontype .= ' selected="selected" '; }
			$optiontype .= '>'.$valc.'</option>';
		}
	
		$this->assign('optiontype',$optiontype);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		
	
		$Model = new Model();
	
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
	
		if($date_s!='') {
			$sql_where .= "create_datetime>='".$date_s."' and ";
		}
	
		if($date_e!='') {
			$sql_where .= "create_datetime<='".$date_e."' and ";
		}
	
		if($type!='') {
			$sql_where .= "type='".$type."' and ";
		}
	
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
	
	
		$sql_data = 'xb_yijian.*,xb_user.phone';
			
			
		//生成排序字符串数据
		$sql_order = " xb_yijian.id desc ";
	
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_yijian')
						-> join('xb_user on xb_user.id = xb_yijian.userid')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('xb_yijian')
						-> join('xb_user on xb_user.id = xb_yijian.userid')
						-> field($sql_data)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
	
	
		foreach($list as $keyc => $valc) {
	
			if($list[$keyc]['type']=='1') {
				$list[$keyc]['type'] = '正常用户';
			}else if($list[$keyc]['type']=='2') {
				$list[$keyc]['type'] = '临时用户';
			}
	
		}
			$this -> assign('list',$list);
	
	
			// 输出模板
			$this->display();
	
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	
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