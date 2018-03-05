<?php
/* 
 * 首页信息模块
 */

class IndexAction extends Action {
	
	
	public function index(){
		
		$Model = new Model();
		
		$comment = $this->_post('comment');
		$company = $this->_post('company');
		$phone   = $this->_post('phone');
		$sub     = $this->_post('submit');
		
		if($sub!=''){
			
			if($company==''){
				echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
				$this->error('请填写公司名称！');
			}
			
			if($phone==''){
				echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
				$this->error('请填写联系电话！');
			}
			
			if($comment==''){
				echo "<script>alert('请填写备注信息！'); history.go(-1);</script>";
    			$this->error('请填写备注信息！');
			}
			
			$data = array(
				'comment' => $comment,
				'company' => $company,
				'phone'   => $phone,
				'addtime' => time(),
			);
			
			$id = $Model->table('zhaomeizhushou')->add($data);
			
			if($id){
				echo "<script>alert('发布成功！'); history.go(-1);</script>";
				//$this->success('发布成功！');
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this->success('发布失败！');
			}
		}
		
		/*找煤助手的浏览器验证*/
		$zhushoutable = 'no';
		if($this->is_mobile_request()) {
			$zhushoutable = 'yes';
		}
		$this->assign('zhushoutable', $zhushoutable);
		
		
		/* 动力煤 */
		$sql_sel_dongli   = "select * from xianhuoziyuan 
							where m_name='动力煤' 
							order by id desc limit 8";
							
		$list_dongli = $Model->query($sql_sel_dongli);
		/* echo '<pre>';
		print_r($info);
		echo '</pre>'; */
		$this->assign('donglimei',$list_dongli);
		
		
		/* 左侧栏    采购信息 -动力煤*/
		$sql_zuolan_donglimei  = "select m_type,m_diweirezhi,m_shuliang 
								from caigouxinxi 
								where m_type='动力煤' 
								order by id desc limit 6";
		
		$dong_c = $Model->query($sql_zuolan_donglimei);
		$this->assign('donglimei_c',$dong_c);
		
		
		
		
		/*  无烟煤*/
		$sql_sel_wuyanmei="select * from xianhuoziyuan where m_type='无烟煤' order by id desc limit 8";
		$wuyanmei=$Model->query($sql_sel_wuyanmei);
		$this->assign('wuyanmei',$wuyanmei);
		
		
		
		
		
		/*  左侧栏  采购信息 -无烟煤*/
		$sql_zuolan_wuyanmei =  "select  m_type,m_shuliang ,m_diweirezhi 
								from caigouxinxi 
								where m_type='无烟煤' 
								order by id desc limit 6";
		$wuyanmei_c=$Model->query($sql_zuolan_wuyanmei);
		$this->assign('wuyanmei_c',$wuyanmei_c);
		
		
		
		
		/*炼焦煤*/
		$sql_sel_lianjiaomei = "select * from xianhuoziyuan 
								where m_type='炼焦煤' 
								order by id desc limit 8";
		$jiaotan=$Model->query($sql_sel_lianjiaomei);
		$this->assign('jiaotan',$jiaotan);
		
		
		/*  左侧栏   采购信息 -炼焦煤*/
		$sql_zuolan_lianjiaomei = "select m_type,m_shuliang ,m_diweirezhi
									from caigouxinxi 
									where m_type='炼焦煤' 
									order by id desc limit 6";
		$jiaomei_c=$Model->query($sql_zuolan_lianjiaomei);
		$this->assign('jiaomei_c',$jiaomei_c);
		
		
		
		
		/* 其他 */
		$sql_sel_qita="select * from xianhuoziyuan  order by id desc limit 8";
		$qita=$Model->query($sql_sel_qita);
		$this->assign('qita',$qita);
		
		
		
		
		/* 订单动态 */
		$arr = array(
					array(
							'company'   => '北京远兴**公司',
							'shuxing'   => '动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai' => '正在洽谈',
					),
					array(
							'company'=>'浙商控股**公司',
							'shuxing'=>'动力煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'北京神火**公司',
							'shuxing'=>'动力煤 低位热值:5000kcal,硫份:0.5% 订购:14吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
					),
					array(
							'company'=>'蒙泰**公司',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
					),
					array(
							'company'=>'北京满世**集团',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'正在洽谈',
							
					),
					array(
							'company'=>'北京山煤**公司',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
					),
					array(
							'company'=>'北京泰***公司',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'中国华**集团',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'北***有限公司',
							'shuxing'=>'动力煤 低位热值:6500kcal,硫份:0.5% 订购:1600吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'北京矿***公司',
							'shuxing'=>'动力煤 低位热值:5000kcal,硫份:0.6% 订购:100吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
					),
					array(
							'company'=>'国电燃***公司',
							'shuxing'=>'无烟煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
					),
					array(
							'company'=>'泰德煤**公司',
							'shuxing'=>'动力煤 低位热值:6000kcal,硫份:0.6% 订购:1000吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'北京尹***公司',
							'shuxing'=>'动力煤 低位热值:4000kcal,硫份:0.6% 订购:20吨',
							'zhuangtai'=>'正在洽谈',
					),
					array(
							'company'=>'广东新***公司',
							'shuxing'=>'动力煤 低位热值:3000kcal,硫份:0.6% 订购:40吨',
							'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
					),
					array(
							'company'=>'神华销售**公司',
							'shuxing'=>'精 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
							'zhuangtai'=>'正在洽谈',
							
					),
				array(
						'company'=>'北京龙煤**公司',
						'shuxing'=>'一三块 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'力量能***公司',
						'shuxing'=>'石炭4-4500 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'山西世德**公司',
						'shuxing'=>'米粒无烟煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
				),
				array(
						'company'=>'浙江丰太**公司',
						'shuxing'=>'炼焦煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'神华国**公司',
						'shuxing'=>'炼焦煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'北京阳**有限公司',
						'shuxing'=>'动力煤 低位热值:5000kcal,硫份:0.5% 订购:100吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'大同煤**公司',
						'shuxing'=>'炼焦煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
				),
				array(
						'company'=>'北京阳**有限公司',
						'shuxing'=>'动力煤 低位热值:5000kcal,硫份:0.5% 订购:100吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'大连**公司',
						'shuxing'=>'无烟煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
				),
				array(
						'company'=>'北京矿***公司',
						'shuxing'=>'无烟煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'北京阳**有限公司',
						'shuxing'=>'石炭4-4500 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
				),
				array(
						'company'=>'山西世德**公司',
						'shuxing'=>'动力煤 低位热值:5000kcal,硫份:0.5% 订购:100吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'浙商控股**公司',
						'shuxing'=>' 炼焦煤 低位热值:6300kcal,硫份:0.5% 订购:300吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'泰德煤***公司',
						'shuxing'=>'无烟煤 低位热值:6300kcal,硫份:0.5% 订购:2000吨',
						'zhuangtai'=>'正在洽谈',
							
				),
				array(
						'company'=>'国电燃**公司',
						'shuxing'=>'无烟煤  低位热值:6000kcal,硫份:0.6% 订购:1000吨',
						'zhuangtai'=>'<font style="color: #229f24 !important">交易成功</font>',
							
				),
		);
		
		$this->assign('arr',$arr);
		
		
		
		//实现点击导航栏首页时被选中
		$this->assign('d_shouye','active');
		
		$this->display();
	}
	
	
	
	
	public function is_mobile_request() {
		
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		$mobile_browser = '0';
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
			$mobile_browser++;
		if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_PROFILE']))
			$mobile_browser++;
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
		$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda','xda-'
		);
		if(in_array($mobile_ua, $mobile_agents))
			$mobile_browser++;
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
			$mobile_browser++;
		// Pre-final check to reset everything if the user is on Windows
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
			$mobile_browser=0;
		// But WP7 is also Windows, with a slightly different characteristic
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
			$mobile_browser++;
		if($mobile_browser>0)
			return true;
		else
			return false;
	}
	
	
	
	

}