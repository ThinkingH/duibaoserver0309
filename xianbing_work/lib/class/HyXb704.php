<?php
/*
 * 流量兑换
 */
class HyXb704 extends HyXb{
	
	private $phone;
	private $keystr;
	
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
	
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
				HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
		
		$this->phone = isset($input_data['phone'])? $input_data['phone']:'';  
		$this->keystr  = isset($input_data['keystr'])?trim($input_data['keystr']):'';    
	
	}
	
	
	//用户兑换操作
	public function controller_duihuan(){
		
		
		//校验密钥校验位是否正确
		$miyao_s_16 = substr($this->keystr,0,16);
		$miyao_e_2 = substr($this->keystr,-2);
		
		
		$checkmd5 = md5($miyao_s_16);
		$jiaoyam_2_str = '';
		$jiaoyam_2_str .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($checkmd5,8,1))+hexdec(substr($checkmd5,16,1))));
		$jiaoyam_2_str .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($checkmd5,12,1))+hexdec(substr($checkmd5,24,1))));
		
		
		
		
		if($miyao_e_2!=$jiaoyam_2_str) {
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '密钥格式校验没有通过！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断该手机号对应运营商和省份
		$yunyingshang = HyItems::hy_yunyingshangcheck($this->phone); //移动1，联通2，电信3
		
		$miyao_yunying = HyItems::topstr_str2num(substr($this->keystr,0,1));
		
		if($miyao_yunying!=$yunyingshang) {
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '密钥对应运营商与手机号归属运营商不一致！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//查询该密钥在数据表中是否存在
		$sql_getmiyao = "select id,gateway,mbps,ttype,keystr 
				from dh_orderlist
				where keystr='".$this->keystr."' 
				and key_over_datetime>='".date('Y-m-d H:i:s')."'
				and flag='9' 
				and gateway='".$yunyingshang."'
				order by id desc limit 1";
		$list_getmiyao = parent::__get('HyDb')->get_row($sql_getmiyao);
		
		
		
		if(count($list_getmiyao)<=0) {
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '密钥不存在或不在使用规则内！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		
		}else {
			//密钥查询到后，根据密钥中的参数查询产品列表，获取到产品列表中对应的产品参数，执行流量充值操作
		
			$dht_id       = $list_getmiyao['id'];
			$dht_gateway  = $list_getmiyao['gateway'];
			$dht_mbps     = $list_getmiyao['mbps'];
			$dht_ttype    = $list_getmiyao['ttype'];  //流量的使用范围，1全国，2省内
			$dht_keystr   = $list_getmiyao['keystr'];
		
			//为了防止出错，再次校验keystr,防止sql注入
			if($dht_keystr!=$this->keystr) {
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '密钥不存在或不在使用规则内！';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}else {
		
				//判断该手机号对应的省份地市
				$top7_phone = substr($this->phone,0,7);
		
				$h_province = '未知';
				$sql_provcity = "select mobile,province,city from phone where mobile='".$top7_phone."'";
				$list_provcity = parent::__get('HyDb')->get_row($sql_provcity);
				if(count($list_provcity)>0) {
					$h_province = $list_provcity['province'];
					$h_city = $list_provcity['city'];
				}
				//对于未知省份号段，直接不予通过
				if($h_province=='未知') {
					
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '该手机号不符合充值手机号规则，暂时无法充值！';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
						
				}else {
						
					$sql_getcodelist = "select id,gateway,mbps,ttype,productid,province,now_price 
								from dh_codelist 
								where flag='1' 
								and gateway='".$dht_gateway."' 
								and mbps='".$dht_mbps."' 
								and ttype='".$dht_ttype."' 
								and ( (ttype='1' and province='') 
										or (ttype='1' and province='".$h_province."') 
										or ( ttype='2' and province='".$h_province."'))";
					$list_getcodelist = parent::__get('HyDb')->get_all($sql_getcodelist);
						
					if(count($list_getcodelist)<=0) {
						
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '暂无可用产品以供充值，请稍后再次尝试！';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
		
					}else {
						//选取利润最高的产品算法
						$lr_now_price = '9999999';
						$lr_productid = '';
		
						//先去除掉单省充值和改手机号省份不符合的数据
						foreach($list_getcodelist as $keyg => $valg) {
							if($valg['province']!='' && $h_province!=$valg['province']) {
								unset($list_getcodelist[$keyg]);
							}
						}
						if(count($list_getcodelist)<=0) {
							$echoarr = array();
							$echoarr['returncode'] = 'error';
							$echoarr['returnmsg']  = '暂无可用产品以供充值，请稍后再次尝试_！';
							$echoarr['dataarr'] = array();
							$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
							parent::hy_log_str_add($logstr);
							echo json_encode($echoarr);
							return false;
						}
						//便利第二遍获取金额最小的数据对应的产品id
						foreach($list_getcodelist as $keyg => $valg) {
							if($valg['now_price']<$lr_now_price) {
								$lr_productid = $valg['productid'];
								$lr_now_price = $valg['now_price'];
							}else {
								//跳过
		
							}
								
						}
		
						if(''==$lr_productid) {
							
							$echoarr = array();
							$echoarr['returncode'] = 'error';
							$echoarr['returnmsg']  = '暂无可用产品以供充值，请稍后再次尝试__！';
							$echoarr['dataarr'] = array();
							$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
							parent::hy_log_str_add($logstr);
							echo json_encode($echoarr);
							return false;
						}
						//--------------------------------------------------
						//至此我们获取到了产品id
		
						//调用流量充值接口完成流量充值操作
		
						//锁定该密钥对应的状态值，修改为5，使用中
						$sql_update_flag = "update dh_orderlist set flag='5' 
									where id='".$dht_id."' 
									and keystr='".$dht_keystr."' 
									and flag='9'"; 
						$r_update = parent::__get('HyDb')->execute($sql_update_flag);
						if(!$r_update) {
							$echoarr = array();
							$echoarr['returncode'] = 'error';
							$echoarr['returnmsg']  = '该密钥暂时不符合使用条件，请稍后重试！';
							$echoarr['dataarr'] = array();
							$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
							parent::hy_log_str_add($logstr);
							echo json_encode($echoarr);
							return false;
								
						}else {
							//拼接对应参数，请求流量订单至上家
							//生成唯一标识单号
							$only_orderid = 'dh'.date('YmdHis').mt_rand(100,999).mt_rand(100,999);
								
							$urlarrt = array();
							$urlarrt['siteid']    = '180002'; //上家分配渠道编号
							$urlarrt['productid'] = $lr_productid; //上家分配产品编号
							$urlarrt['phone']     = $this->phone;   //手机号
							$urlarrt['orderid']   = $only_orderid;   //我们生成的唯一标识订单号
							$urlarrt['sitetime']  = time();  //当前时间戳
							$urlarrt['md5key']    = md5($urlarrt['siteid'].$urlarrt['productid'].$urlarrt['phone'].$urlarrt['orderid'].$urlarrt['sitetime'].'6278687b361dd936165128313a3fdb95');   //md5(渠道编号+产品编号+手机号+渠道订单号+时间戳+渠道秘钥)
								
								
							//$gurl = 'http://114.215.87.192/dc_work/interface/dcinit.php?'.HyItems::hy_urlcreate($urlarrt);
							$gurl = 'http://121.42.228.34/dc_work/interface/dcinit.php?'.HyItems::hy_urlcreate($urlarrt);
								
							//数据转发
							$res = HyItems::vget($gurl,10000);
								
							$content  = isset($res['content'])  ? $res['content'] : '';
							$httpcode = isset($res['httpcode']) ? $res['httpcode'] : '';
							$run_time = isset($res['run_time']) ? $res['run_time'] : '';
							$errorno  = isset($res['errorno'])  ? $res['errorno'] : '';
								
								
							if($content=='') {
								//没有得到响应，可能请求中间出现了错误，还原flag为9
								//锁定该密钥对应的状态值，修改为5，使用中
								$sql_update_flag = "update dh_orderlist set flag='9' 
									where id='".$dht_id."' 
									and keystr='".$dht_keystr."' 
									and flag='5'";
								$r_update = parent::__get('HyDb')->execute($sql_update_flag);
								
								$echoarr = array();
								$echoarr['returncode'] = 'error';
								$echoarr['returnmsg']  = '流量充值接口未正常响应，请稍后重试';
								$echoarr['dataarr'] = array();
								$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
								parent::hy_log_str_add($logstr);
								echo json_encode($echoarr);
								return false;
		
		
							}else {
								//得到了短流平台的响应
								$strarr = explode('|',$content);
								if($strarr[0]=='ok' || $strarr[1]=='201') {
									$temp_sj_orderid = $strarr[2];
									$temp_sj_errcode = $strarr[1];
									//充值请求成功送达短流平台
									//更新订单数据状态值
									$sql_update_flag = "update dh_orderlist set sj_productid='".$lr_productid."',
														phone='".$this->phone."',
														only_orderid='".$only_orderid."',
														sj_orderid='".$temp_sj_orderid."',
														sj_errcode='".$temp_sj_errcode."',
														create_datetime='".date('Y-m-d H:i:s')."' 
														where id='".$dht_id."' 
														and keystr='".$dht_keystr."' 
														and flag='5'";
									$r_update = parent::__get('HyDb')->execute($sql_update_flag);
									
									$echoarr = array();
									$echoarr['returncode'] = 'success';
									$echoarr['returnmsg']  = '流量订单提交成功，请耐心等待流量充值完成';
									$echoarr['dataarr'] = array();
									$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
									parent::hy_log_str_add($logstr);
									echo json_encode($echoarr);
									return true;
									
										
								}else {
									$temp_sj_errcode = $strarr[1];
									$sql_update_flag = "update dh_orderlist set flag='9',
														sj_productid='".$lr_productid."',
														phone='".$this->phone."',
														only_orderid='".$only_orderid."',
														sj_errcode='".$temp_sj_errcode."',
														create_datetime='".date('Y-m-d H:i:s')."'
														where id='".$dht_id."'
														and keystr='".$dht_keystr."'
														and flag='5'";
									$r_update = parent::__get('HyDb')->execute($sql_update_flag);
									
									$echoarr = array();
									$echoarr['returncode'] = 'error';
									$echoarr['returnmsg']  = '流量充值失败，请稍后重新尝试！';
									$echoarr['dataarr'] = array();
									$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
									parent::hy_log_str_add($logstr);
									echo json_encode($echoarr);
									return false;
										
										
								}
		
		
							}
								
								
						}
		
		
					}
						
						
				}
		
		
			}
		
		
		}
		
		
	}
	
	//操作入口
	public function controller_init(){
		
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		//判断这三个参数是否齐全
		if(!is_numeric($this->phone) || strlen($this->phone)!=11) {
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '手机号格式不符合规范！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		if(strlen($this->keystr)<15) {
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '密钥格式不符合规范！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		$this->controller_duihuan();
	
		return true;
	}
	
	
	
	
}