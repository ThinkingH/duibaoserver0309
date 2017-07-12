<?php
/*
 *商品兑换
 */
class HyXb522 extends HyXb{
	
	private $type;
	private $passwd;
	private $keystr;
	private $productid;
	private $nowprice;
	private $tid;
	private $keyongjifen;
	private $keyong_money;
	private $price;
	private $typeid;
	private $gateway;
	private $mbps;
	private $ttype;
	private $goodsname;
	private $typeidchild;
	private $xiafaurl;
	private $mtype;
	private $fh_address;
	private $tflag;
	private $typeid1;
	
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
	
		$this->productid = isset($input_data['productid'])? $input_data['productid']:'';  //商品的类型id
		$this->nowprice = isset($input_data['nowprice'])? $input_data['nowprice']:'';     //商品的总价
		$this->tid = isset($input_data['tid'])? $input_data['tid']:'';                   //商品数量
		
		
		$shopdata = parent::shopduihuan_check();//商品属性的获取
		$userinfo = parent::check_scoremoney_user();//用户账户积分的获取
	
		$this->keyongjifen  = isset($userinfo['keyong_jifen']) ? $userinfo['keyong_jifen'] : '0'; //用户可用积分
		$this->keyong_money  = isset($userinfo['keyong_money']) ? $userinfo['keyong_money'] : '0';//用户可用金额
	
		$this->zhifuway  = isset($shopdata['feetype']) ? $shopdata['feetype'] : '';//用户的支付方式
		$this->score     = isset($shopdata['score']) ? $shopdata['score'] : '0'; //商品兑换所需要的积分
		$this->price     = isset($shopdata['price']) ? $shopdata['price'] : '0'; //商品兑换所需要的金额
		$this->typeid    = isset($shopdata['typeid']) ? $shopdata['typeid'] : ''; //商品的类型
		$this->gateway   = isset($shopdata['gateway']) ? $shopdata['gateway'] : ''; //流量运营商
		$this->mbps      = isset($shopdata['mbps']) ? $shopdata['mbps'] : '';       //流量大小
		$this->ttype     = isset($shopdata['ttype']) ? $shopdata['ttype'] : '';    //流量使用的范围
		$this->goodsname = isset($shopdata['name']) ? $shopdata['name'] : '';     //商品名称
		$this->typeidchild = isset($shopdata['typeidchild']) ? $shopdata['typeidchild'] : '';  //商品的子类型
		$this->fh_address = isset($shopdata['fh_address']) ? $shopdata['fh_address'] : '';  //商品的子类型
		
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
	
	}
	
	
	public function controller_userduihuan(){
		
		//echo $this->tid;
		if($this->tid==''){
			$this->tid='1';//商品数量
		}
		
		$this->score = $this->score*$this->tid;
		
		//积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录
		//订单编号date('YmdHis').mt_rand(1000,9999);
		//商品类型的判断
		$this->mtype  = substr($this->typeidchild,0,3);
		//$this->typeid = substr($this->typeid,0,2);//$typeid 
		$this->typeid  = substr($this->typeidchild,0,4);//截取类型的前三位判断  是自采还是商户发放
		$this->typeid1 = substr($this->typeidchild,0,3);//截取类型的前三位判断 流量1111  卡密1311自采多秘钥--状态改变（待领取） 1321商户发放--状态改变（已领取）
		
		
		$this->type = substr($this->typeid,0,1);//$typeid 判断商品的虚实
		
		
		//商品订单
		$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
		
		//单卡密 多卡密的判断
		if($this->typeid=='1112' ||$this->typeid=='1312' || $this->typeid=='1322' ){//11流量 1311 自采多卡密 1312 自采单卡密    1321商户发放多卡密      1322 商户发放单卡密
			$this->tflag='1';//单卡密
		}else if($this->typeid=='1311' || $this->typeid=='1321'){
			$this->tflag='3';//多卡密
		}else if($this->typeid=='22'){
			$this->tflag='2';
		}else{
			$this->tflag='1';
		}
		
		if($this->typeid1=='111'){//走流量接口
			
			if($this->tid>1){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '流量兑换数量不能大于2个';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				if($this->score > $this->keyongjifen){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}else{
					//订单去重判断
					$r = parent::repeat_userbuy_orderno($orderno);
					
					if($r){
						$echoarr = array();
						$echoarr['returncode'] = 'success';
						$echoarr['returnmsg']  = '订单提交成功！';
						$echoarr['dataarr'] = $r;
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return true;
					}else{
						//模拟用户操作发放兑换码--运营商1，2，3--
						$url =$this->xiafaurl.'?gateway='.$this->gateway.'&mbps='.$this->mbps.'&ttype='.$this->ttype.'&orderno='.$orderno.'&userid='.parent::__get('xb_userid').'&youxiaoday=30&name='.urlencode($this->goodsname).'&describe='.urlencode($this->goodsname);
						$duihuancode = HyItems::vget( $url, 10000 );
						
						if($duihuancode['httpcode']=='200'){
							$this->keystr = $duihuancode['content'];//生成兑换码
							
							if(strlen($this->keystr)!='18'){//秘钥生成错误
								$echoarr = array();
								$echoarr['returncode'] = 'error';
								$echoarr['returnmsg']  = '兑换码生成失败,系统维修中';
								$echoarr['dataarr'] = array();
								$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
								parent::hy_log_str_add($logstr);
								echo json_encode($echoarr);
								return false;
									
							}else{//兑换码生成成功，数据的插入
									
								parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,'','',$this->fh_address,'','','','','2','1');
							}
						}
					}
				}
			}
			
		}else if($this->typeid1=='131' || $this->typeid1=='132'){//该商品为卡密类的商品，走卡密接口, 1311 自采多卡密 1312 自采单卡密    1321商户发放多卡密      1322 商户发放单卡密    131 自采   132 商户发放   
			
			if($this->score>$this->keyongjifen){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}else{
				//订单去重判断
				$r = parent::repeat_userbuy_orderno($orderno);
				
				if($r){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '订单提交成功！';
					$echoarr['dataarr'] = $r;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
					
				}else{
					
					if($this->typeid1=='131'){//自采电子卡商品
						
						$this->keystr='';
						$this->passwd='';
						
						parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','2','1');
						
						
						//单卡密 多卡密的判断
						if( $this->typeid=='1312' ){//11流量 1311 自采多卡密 1312 自采单卡密    1321商户发放多卡密      1322 商户发放单卡密
							
							if($this->keystr==''){
								$this->keystr='派送中';
							}
							
							
						}else if($this->typeid=='1311'){
							
							if($this->keystr==''){
								$this->keystr='派送中';
							}
							
							if($this->passwd==''){
								$this->passwd='派送中';
							}
						}
						
						
					}else if($this->typeid1=='132'){//商户发放电子卡
						
						//生成秘钥
						$duihuanmasql  = "select id,duihuanma,passwd from xb_duihuanma where flag=9 and type='".$this->typeidchild."' order by id asc limit 1 ";
						parent::hy_log_str_add($duihuanmasql);
						$duihuanmalist = parent::__get('HyDb')->get_all($duihuanmasql);
						
						if($duihuanmalist[0]['id']<=0){
							
							
							$this->keystr='';
							$this->passwd='';
							
							//状态在待领取中
							parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','1','1');
							
							//单卡密 多卡密的判断
							if( $this->typeid=='1322' ){//11流量 1311 自采多卡密 1312 自采单卡密    1321商户发放多卡密      1322 商户发放单卡密
								
								if($this->keystr==''){
									$this->keystr='派送中';
								}
								
							}else if($this->typeid=='1321'){
								
								if($this->keystr==''){
									$this->keystr='派送中';
								}
								
								if($this->passwd==''){
									$this->passwd='派送中';
								}
							}
							
					
						}else{
							
							$this->keystr = $duihuanmalist[0]['duihuanma'];
							$this->passwd = $duihuanmalist[0]['passwd'];
							
							//更新兑换码的状态
							parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','1','1');
						}
					}
				}
				
			}
			
		}else if($this->typeid=='22'){
			
			if($this->score>$this->keyongjifen){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}else{
				//随机生成的兑换码
				$this->keystr = parent::getRandomString(6);
				
				if(strlen($this->keystr)==6){
				
					//秘钥数据的入库
					$miyaosql = "insert into shop_duihuanma(userid,goods_id,goods_name,type,maintype,flag,duihuanma,createtime) values
							('".parent::__get('xb_userid')."','".$this->productid."','".$this->goodsname."','".$this->typeidchild."','".$this->mtype."','9','".$this->keystr."',date('Y-m-d H:i:s'))";
					$miyaolist = parent::__get('HyDb')->execute($miyaosql);
					//echo $this->type;
					parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,'','',$this->fh_address,'','','','','2','2');
				
				}else {
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '兑换码生成失败，系统维修中';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			}
		}
		
		//商品信息的更新
		$updateproduct = "update shop_product set buycount=buycount+1,kucun=kucun-1 where id='".$this->productid."'";
		$updateproductsql = parent::__get('HyDb')->execute($updateproduct);
		
		
		//用户积分的变化
		$userscoresql  = "update xb_user set keyong_jifen=keyong_jifen-'".$this->score."'  where id='".parent::__get('xb_userid')."' ";
		$userscorelist = parent::__get('HyDb')->execute($userscoresql);
		
		
		//积分变动的插入
		$getdescribe = '购买'.$this->goodsname.'消耗'.$this->score.'馅饼';
		$gettime = time();
		$insertsql = "insert into xb_user_score (userid,goodstype,maintype,type,
					score,usermoney,getdescribe,gettime) values
				 ('".parent::__get('xb_userid')."','1','2','9',
				 		'".$this->score."','0','".$getdescribe."','".$gettime."')";
		parent::__get('HyDb')->execute($insertsql);
		
		//订单id的获取
		$ordernoidsql = "select id from shop_userbuy where orderno='".$orderno."' order by id desc";
		$ordernoidlist = parent::__get('HyDb')->get_row($ordernoidsql);
		
		
		if($userscorelist){
				
			$temparr = array(
					'goodsname' =>$this->goodsname,
					'keystr'     => $this->keystr,
					'passwd'     => $this->passwd,
					'tflag'     => $this->tflag,
					'createtime' => date('Y-m-d H:i:s'),
					'id'         => $ordernoidlist['id'],
					
			);
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '订单提交成功';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '订单提交失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		$r = parent::check_duihuan_canshu();
		if($r===false){
			return false;
		}
	
		//判断该商品每日兑换的数量
		$r = parent::check_duihuan_max_day();
		if($r===false){
			return false;
		}
	
	
		//1.判断该用户每日该商品的兑换数量
		$r = parent::check_duihuan_user_day();
		if($r===false){
			return false;
		}
	
		//2.商品每月的最大兑换次数
		$r = parent::check_duihuan_user_month();
		if($r===false){
			return false;
		}
	
		//判断该商品该用户每年的最大兑换次数
		$r = parent::check_duihuan_user_year();
		if($r===false){
			return false;
		}
	
		/* //支付方式的判断
			$r = parent::check_scoremoney_user();
			if($r===false){
			return false;
			} */
	
		/* $r = parent::check_zhifuway_user();
	
		if($r==false){
			return false;
		} */
	
		$this->controller_userduihuan();
	
		return true;
	}
	
	
	
	
	
	
	
	
}