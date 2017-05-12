<?php
/*
 * 用户兑换
 */

class HyXb522 extends HyXb{
	
	private $productid;
	private $zhifuway;
	private $typeid;
	private $typeidchild;
	private $score;
	private $price;
	private $gateway;
	private $mbps;
	private $ttype;
	private $goodsname;
	private $xaifaurl;
	private $address_id;
	private $fh_phone;
	private $fh_address;
	private $fh_fahuotime;
	private $fh_shouhuotime;
	private $fh_shouhuoren;
	
	private $keyongjifen;
	private $keyongmoney;
	
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
		
		$this->productid = isset($input_data['productid'])? $input_data['productid']:'';  //商品的类型id
		$this->address_id = isset($input_data['address_id'])? $input_data['address_id']:'';
		
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
		
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
		
	}
	
	
	//用户兑换的实际操作
	public function controller_userduihuan(){
		
		//判断支付形式
		if($this->zhifuway=='1'){//积分支付
			$xiaohaomoney = 0;
			$xiaohaojifen = $this->score;
		
		}else if($this->zhifuway=='2'){//金额支付
			$xiaohaomoney = $this->price;
			$xiaohaojifen = 0;
		
		}else if($this->zhifuway=='3'){//混合支付
			//商品的积分
			$this->price = $this->price*DISCOUNT;//商品的金额
			$this->score = $this->price+$this->score; //商品所需的积分
			
			if($this->keyongjifen>=$this->score){//积分优先
				
				$xiaohaojifen = $this->score;
				$xiaohaomoney = 0;
			}else{//积分加金额--100+5元
				
				//用户账户的积分数$this->keyongjifen
				$shopscorenum = $this->score;//商品所需要的总积分
				
				//扣除积分所需要消耗的金额
				$shengxujine = ($shopscorenum-$this->keyongjifen)/DISCOUNT;
				$xiaohaojifen = $this->keyongjifen;
				$xiaohaomoney = $shengxujine;
				
			}
			
		
		}
		
		//积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录
		//订单编号date('YmdHis').mt_rand(1000,9999);
		//商品类型的判断
		$this->typeid = substr($this->typeid,0,2);
		
		//商品订单
		$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
		
		if($this->typeid=='11'){//该商品为流量兑换，走流量接口
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
						
						parent::userbuy_insert_data($orderno,$this->keystr);
						
					}
					
				}
			}
			
		}else if($this->typeid=='12'){//该商品为卡密类的商品，走卡密接口
			
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
				//生成秘钥
				$duihuanmasql  = "select id,duihuanma from xb_duihuanma where flag=9 and type='".$this->typeidchild."' order by id asc limit 1 ";
				$duihuanmalist = parent::__get('HyDb')->get_row($duihuanmasql);
				
				if($duihuanmalist['id']<=0){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '兑换码生成失败，系统维修中';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}else{
					$this->keystr = $duihuanmalist['duihuanma'];
					//更新兑换码的状态
					$updatecodesql  = "update xb_duihuanma set flag='1' where duihuanma='".$this->keystr."' and id='".$duihuanmalist['id']."' "; 
					$updatecodelist = parent::__get('HyDb')->execute($updatecodesql);
					parent::userbuy_insert_data($orderno,$this->keystr);
					
				}
			}
		}else if($this->typeid=='13'){//该商品为饭票商品，走饭票的接口
			
		}else if($this->typeid=='14'){//该商品为实物，走实物接口
			
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
				if($this->address_id==''){
					$addresssql = "select * from xb_user_address where is_default='1' and userid='".parent::__get('xb_userid')."'";
					$addresslist = parent::__get('HyDb')->get_row($addresssql);
					
					if($addresslist['id']>0){
						
						$this->address_id = $addresslist['address_id'];
						$this->fh_phone = $addresslist['mobile'];
						$this->fh_address = $addresslist['address'];
						$this->fh_shouhuoren = $addresslist['shouhuoren'];
						
						/* parent::updateproductdata();
						parent::updateuserscore();
						parent::updateuserscore(); */
						
						$r = parent::userbuy_insert_data($orderno,$code='',$this->fh_phone,$this->fh_address,$fh_fahuotime='',$fh_shouhuotime='',$this->address_id,$this->fh_shouhuoren);
						
					}else{
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '请填写收货地址';
						$echoarr['dataarr'] = $r;
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
					}
					
					
				}else{
					
					$addresssql = "select * from xb_user_address where id='".$this->address_id."'";
					$addresslist = parent::__get('HyDb')->get_row($addresssql);
					
					if($addresslist['id']>0){
					
						$this->address_id = $addresslist['address_id'];
						$this->fh_phone = $addresslist['mobile'];
						$this->fh_address = $addresslist['address'];
						$this->fh_shouhuoren = $addresslist['shouhuoren'];
					
						parent::userbuy_insert_data($orderno,$code='',$this->fh_phone,$this->fh_address,$fh_fahuotime='',$fh_shouhuotime='',$this->address_id,$this->fh_shouhuoren);
					
					}else{
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '请填写收货地址';
						$echoarr['dataarr'] = $r;
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
					}
				}
			}
		}
		
		
		//积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录
		
		//商品信息的更新
		$updateproduct = "update shop_product set buycount=buycount+1,kucun=kucun-1 where id='".$this->productid."'";
		$updateproductsql = parent::__get('HyDb')->execute($updateproduct);
		
		//用户积分的变化
		$userscoresql  = "update xb_user set keyong_jifen=keyong_jifen-'".$xiaohaojifen."',
						keyong_money=keyong_money-'".$xiaohaomoney."' where id='".parent::__get('xb_userid')."' ";
		$userscorelist = parent::__get('HyDb')->execute($userscoresql);
		
		//积分变动的插入
		$getdescribe = '购买'.$this->goodsname.'消耗'.$xiaohaojifen.'馅饼';
		$gettime = time();
		$insertsql = "insert into xb_user_score (userid,goodstype,maintype,type,
					score,usermoney,getdescribe,gettime) values
				 ('".parent::__get('xb_userid')."','1','2','9',
				 		'".$xiaohaojifen."','".$xiaohaomoney."','".$getdescribe."','".$gettime."')";
		
		parent::__get('HyDb')->execute($insertsql);

		
		if($userscorelist){
			
			$temparr = array(
					'goodsname' =>$this->goodsname,
					//'keystr'   => $this->keystr,yh16dddzb6703yy77d
					'keystr'     => $this->keystr,
					'createtime' => date('Y-m-d H:i:s'),
					
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
			$echoarr['dataarr'] = $r;
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
		
		$r = parent::check_zhifuway_user();
		
		if($r==false){
			return false;
		}
		
		$this->controller_userduihuan();
	
		return true;
	}

}