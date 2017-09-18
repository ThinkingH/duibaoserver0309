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
	private $miyao_type;
	private $xushi_type;
	private $fafang_type;
	private $siteid;
	
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
		
		
		$this->siteid = isset($shopdata['siteid']) ? $shopdata['siteid'] : '';   //渠道编号
		$this->miyao_type = isset($shopdata['miyao_type']) ? $shopdata['miyao_type'] : '';   //秘钥的单双
		$this->xushi_type = isset($shopdata['xushi_type']) ? $shopdata['xushi_type'] :'';    //商品类型
		$this->fafang_type = isset($shopdata['fafang_type']) ? $shopdata['fafang_type'] :''; //卡密发放时间
		
		$this->typeid = isset($shopdata['typeid']) ? $shopdata['typeid'] :''; //类型
		
		
		
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
	
	}
	
	
	
	public function controller_duihuan(){
		
		
		$this->score = $this->score*$this->tid;
		
		/* if($this->miyao_type=='1'){//单秘钥
				
			$this->tflag='1';//单卡密
			
		}else if($this->miyao_type=='2'){//多秘钥
				
			$this->tflag='3';//多卡密
				
		}else if($this->miyao_type=='3'){//二维码
				
			$this->tflag='2';//二维码
				
		}else if($this->miyao_type=='4'){//实物
				
			$this->tflag='4';//实物
		} */
		
		//商品订单
		$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
		
		if($this->zhifuway=='1' || $this->zhifuway=='4' || $this->zhifuway=='5'){//积分兑换
			
			if($this->fafang_type=='1'){//立即发放，商户，
				
				if($this->xushi_type=='2'){//实物立即发放兑换码
					$r = $this->controller_duihuan_one($orderno);
				}else{
					
					if($this->typeid=='11'){
						$r = $this->controller_duihuan_two($orderno);//单独兑换流程
					}else{
						$r = $this->controller_duihuan_three($orderno);//上家发放，从表中读出对应的秘钥
					}
					
				}
				
			}else{//稍后发放，从后台输入对应秘钥
				
				$r = $this->controller_duihuan_four($orderno);
				
			}
			
		}else if($this->zhifuway=='2'){//金额支付
			
			
			
			
			
		}else if($this->zhifuway=='3'){//混合支付
			
			
			
		}
		
		if($r){
			
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
			
			$this->keystr = $r['keystr'];
			$this->passwd = $r['passwd'];
			$this->tflag = $r['tflag'];
			
			//账号和密码的展示
			if($this->fafang_type=='1'){//立即发放
					
			}else if($this->fafang_type=='2'){//稍后发放
					
				if($this->miyao_type=='1'){//单秘钥
					$this->keystr='正在派送中';
				}else if($this->miyao_type=='2'){//对秘钥
			
					$this->keystr = '正在派送中';
					$this->passwd = '正在派送中';
						
				}else if($this->miyao_type=='3'){//二维码
			
					$this->keystr = '正在派送中';
						
				}
					
			}
			
			
			
			
			
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
			
		}
		
	}
	
	
	//兑换生成卡密的实物，随机生成6位的随机字符串
	public function controller_duihuan_one($orderno){
		
		if($this->zhifuway=='1' ){
			
			//商品数量的判断
			if($this->tid>1){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '兑换数量不能大于2个';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			if($this->score>$this->keyongjifen){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
		//随机生成的兑换码
		$this->keystr = parent::getRandomString(6);
		
		if(strlen($this->keystr)==6){
		
			//秘钥数据的入库
			$miyaosql = "insert into shop_duihuanma(userid,goods_id,goods_name,type,maintype,flag,duihuanma,createtime) values
							('".parent::__get('xb_userid')."','".$this->productid."','".$this->goodsname."','".$this->typeidchild."',
							'".$this->mtype."','9','".$this->keystr."','".date('Y-m-d H:i:s')."')";
			$miyaolist = parent::__get('HyDb')->execute($miyaosql);
			
			parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,'','',$this->fh_address,'','','','','2','2');//状态和实物类型
			
			$miyao = array(
					'keystr'     => $this->keystr,
					'passwd'     => null,
					'tflag'     => '22',
					
			
			);
			
			return $miyao;
		
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
	
	
	//流量单独的兑换流量
	public function controller_duihuan_two($orderno){
		
		if($this->zhifuway=='1' ){
			
			//商品数量的判断
			if($this->tid>1){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '流量兑换数量不能大于2个';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
				
			if($this->score > $this->keyongjifen){
			
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
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
		}
		/* userbuy_insert_data($orderno='',$tid='',$code='',$passwd='',$fh_phone='',$fh_address='',
		 * $fh_fahuotime='',$fh_shouhuotime='',$address_id='',$shouhuoren='',$typeid='1',$mytype='1') */
		
		//模拟用户操作发放兑换码--运营商1，2，3--
		$url =$this->xiafaurl.'?gateway='.$this->gateway.'&mbps='.$this->mbps.'&ttype='.$this->ttype.'&orderno='.
			$orderno.'&userid='.parent::__get('xb_userid').'&youxiaoday=30&name='.urlencode($this->goodsname).
			'&describe='.urlencode($this->goodsname);
		
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
				
				
				$miyao = array(
						'keystr'     => $this->keystr,
						'passwd'     => null,
						'tflag'     => '1',
							
				);
					
				return $miyao;
			}
		}
		
	}
	
	
	//虚拟商品的电子卡，商家发放从对应的表中读出该字符串
	public function controller_duihuan_three($orderno){
		
		if($this->zhifuway=='1' ){
			
			//商品数量的判断
			if($this->tid>1){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '兑换数量不能大于2个';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
				
			if($this->score > $this->keyongjifen){
			
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
		}
		
		
		
		//生成秘钥SELECT `id`, `goods_id`, `siteid`, `goods_name`, `type`, `maintype`, `flag`, `duihuanma`, `passwd`, `createtime` FROM `xb_duihuanma` WHERE 1
		$duihuanmasql  = "select id,duihuanma,passwd from xb_duihuanma 
						where flag=9 and maintype='".$this->typeid."' and type='".$this->typeidchild."' 
						and goods_id='".$this->productid."' and  siteid='".$this->siteid."' 
						order by id asc limit 1 ";
		parent::hy_log_str_add($duihuanmasql);
		$duihuanmalist = parent::__get('HyDb')->get_all($duihuanmasql);
		
		if($duihuanmalist[0]['id']>0){//秘钥兑换成功
			
			$this->keystr=$duihuanmalist[0]['duihuanma'];
			$this->passwd=$duihuanmalist[0]['passwd'];
			
			//状态在待领取中
			parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','1','1');
			
			$miyao = array(
					'keystr'     => $this->keystr,
					'passwd'     => $this->passwd,
					'tflag'     => '23',
						
			);
				
			return $miyao;
			
		}else{//秘钥生成失败，系统错误
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '系统错误';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//兑换码稍微发放
	public function controller_duihuan_four($orderno){
		
		if($this->zhifuway=='1' ){
			
			//商品数量的判断
			if($this->tid>1){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '兑换数量不能大于2个';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
				
			if($this->score > $this->keyongjifen){
			
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '您的馅饼不足，无法兑换该商品';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
		}
		
		
		$this->keystr='';
		$this->passwd=null;
						
		parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','2','1');
		
		$miyao = array(
				'keystr'     => $this->keystr,
				'passwd'     => $this->passwd,
				'tflag'     => '24',
		
		);
		
		return $miyao;
		
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
	
		if($r==false){      $this->zhifuway
			return false;
		} */
		
		$this->controller_duihuan();
		
	
		return true;
	}
	
	
	
	
	
	
	
	
}