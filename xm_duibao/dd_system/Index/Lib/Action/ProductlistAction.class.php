<?php
/*
 * 商品的下载
 */
class ProductlistAction extends Action{
	
	
	//定义各模块锁定级别
	private $lock_downloadtask           = '9751';
	private $lock_shopgoods              = '9751';
	
	//app下载任务的浏览
	public function downloadtask(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_downloadtask);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$date_s      = strtotime($this->_get('date_s'));
		$date_e      = strtotime($this->_get('date_e'));
		$flag_s      = $this->_get('flag_s');
		$taskname    = $this->_get('taskname');
		$score       = $this->_get('score');
		
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
		
		$this->assign('optionflag',$optionflag);
		$this->assign('taskname',$taskname);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		$this->assign('score',$score);
		
		$Model = new Model();
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($taskname!='') {
			$sql_where .= " name like '%".$taskname."%' and ";
		}
		
		if($date_s!='') {
			$sql_where .= "over_inttime>='".$date_s."' and ";
		}
		
		if($date_e!='') {
			$sql_where .= "over_inttime<='".$date_e."' and ";
		}
		
		if($score!='') {
			$sql_where .= "score>='".$score."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		$sql_data  = "id,flag,type,over_inttime,score,downtimes,scoretimes,name,downurl,iosdownurl,showurl,create_datetime,shuoming";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_task')
						-> where($sql_where)
						->field($sql_data)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_task')
						-> where($sql_where)
						->field($sql_data)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
				
			if($list[$keyc]['type']=='1') {
				$list[$keyc]['type'] = '游戏下载';
			}else if($list[$keyc]['type']=='2') {
				$list[$keyc]['type'] = '网页下载';
			}else {
				$list[$keyc]['type'] = 'ERR';
			}
				
			$list[$keyc]['over_inttime'] = date('Y-m-d H:i:s',$list[$keyc]['over_inttime']);
				
			$list[$keyc]['create_datetime'] = date("Y-m-d H:i:s",$list[$keyc]['create_datetime']);
		}
		
		
		$this -> assign('list',$list);
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//商城商品表的查询
	public function shopgoods(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_shopgoods);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$flag_s      = $this->_get('flag_s');
		$taskname    = $this->_get('goods_name');
		
		//是否启用
		$flag_arr = array(
				'1' => '流量',
				'2' => '实物',
				'3' => '虚拟点卡',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		
		$this->assign('optionflag',$optionflag);
		$this->assign('goods_name',$taskname);
		
		$Model = new Model();
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " shop_type='".$flag_s."' and ";
		}
		if($taskname!='') {
			$sql_where .= " goods_name like '%".$taskname."%' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		//生成排序字符串数据
		$sql_order = " goods_id desc ";
		$sql_data  = "goods_id,shop_type,goods_sn,goods_name,store_count,market_price,shop_price,goods_remark,is_on_sale,on_time,is_recommend,is_hot,exchange_integral";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('duibaoshop.tp_goods')
						-> where($sql_where)
						->field($sql_data)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('duibaoshop.tp_goods')
						-> where($sql_where)
						->field($sql_data)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['shop_type']=='1') {
				$list[$keyc]['shop_type'] = '流量';
			}else if($list[$keyc]['shop_type']=='2') {
				$list[$keyc]['shop_type'] = '实物';
			}else if($list[$keyc]['shop_type']=='3'){
				$list[$keyc]['shop_type'] = '虚拟点卡';
			}
				
			if($list[$keyc]['is_on_sale']=='1') {
				$list[$keyc]['is_on_sale'] = '已上架';
			}else {
				$list[$keyc]['is_on_sale'] = '未上架';
			}
			
			if($list[$keyc]['is_recommend']=='1') {
				$list[$keyc]['is_recommend'] = '已推荐';
			}else {
				$list[$keyc]['is_recommend'] = '未推荐';
			}
			
			if($list[$keyc]['is_hot']=='1') {
				$list[$keyc]['is_hot'] = '热卖';
			}else {
				$list[$keyc]['is_hot'] = '非热卖';
			}
				
			$list[$keyc]['on_time'] = date('Y-m-d H:i:s',$list[$keyc]['on_time']);
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