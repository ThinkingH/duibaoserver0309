<?php
/*
 * 订单兑换
 */
class HyXb1042 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $productid;  //商品id
	private $tid;  //商品数量
	private $keyongjifen;  //用户积分
	private $keyong_money;  //用户金额
	private $zhifuway;  //支付方式
	private $score;  //商品所需积分
	private $price;  //商品所需金额
	private $typeid;  //商品类型
	private $typeidchild;  //商品主类型
	private $goodsname;  //商品名称
	private $gateway;  //运营商
	private $mbps;  //流量大小
	private $ttype;  //流量适用范围
	private $fh_address;  //地址
	private $miyao_type;  //秘钥的单双
	private $xushi_type;  //商品类型1-虚拟 2-实物
	private $fafang_type;  //商品卡密发货的类型1-立即发放2-稍后发放
	private $keystr;  //秘钥
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->productid = isset($input_data['productid'])?$input_data['productid']:'';//商品id
		$this->tid = isset($input_data['tid'])? $input_data['tid']:'1';                //商品数量
		
		$shopdata = parent::shopduihuan_check();//商品属性的获取
		$userinfo = parent::check_scoremoney_user();//用户账户积分的获取
		
		$this->keyongjifen  = isset($userinfo['keyong_jifen']) ? $userinfo['keyong_jifen'] : '0'; //用户可用积分
		$this->keyong_money  = isset($userinfo['keyong_money']) ? $userinfo['keyong_money'] : '0';//用户可用金额
		
		
		$this->score     = isset($shopdata['score']) ? $shopdata['score'] : '0'; //商品兑换所需要的积分
		$this->price     = isset($shopdata['price']) ? $shopdata['price'] : '0'; //商品兑换所需要的金额
		$this->typeid    = isset($shopdata['typeid']) ? $shopdata['typeid'] : ''; //商品的类型
		$this->gateway   = isset($shopdata['gateway']) ? $shopdata['gateway'] : ''; //流量运营商
		$this->mbps      = isset($shopdata['mbps']) ? $shopdata['mbps'] : '';       //流量大小
		$this->ttype     = isset($shopdata['ttype']) ? $shopdata['ttype'] : '';    //流量使用的范围
		$this->goodsname = isset($shopdata['name']) ? $shopdata['name'] : '';     //商品名称
		$this->typeidchild = isset($shopdata['typeidchild']) ? $shopdata['typeidchild'] : '';  //商品的子类型
		$this->fh_address = isset($shopdata['fh_address']) ? $shopdata['fh_address'] : '';  
		
		$this->miyao_type = isset($shopdata['miyao_type']) ? $shopdata['miyao_type'] : '';   //秘钥的单双
		$this->xushi_type = isset($shopdata['xushi_type']) ? $shopdata['xushi_type'] :'';    //商品类型
		$this->fafang_type = isset($shopdata['fafang_type']) ? $shopdata['fafang_type'] :''; //卡密发放时间
		$this->zhifuway  = isset($shopdata['feetype']) ? $shopdata['feetype'] : '';//用户的支付方式1-积分 2-金额 3-混合4-vip免费 5-免费商品 
		
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
	}
	
	
	//总处理函数
	public function controller_exec1(){
		
		$this->score = $this->score*$this->tid;
		//商品订单
		$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
		
		//积分是否充足的判断
		$jifenscore = parent::check_zhifuway_user($this->tid);
		if($jifenscore===false){
			return false;
		}
		
		if($this->zhifuway=='2'){//金额支付
		}else if($this->zhifuway=='3'){//混合支付
			
		}else if($this->zhifuway=='1' || $this->zhifuway=='4' || $this->zhifuway=='5'){//积分支付
			if($this->fafang_type=='1'){//兑换码立即发放
				if($this->xushi_type=='2'){//实物系统生成对应的六位数字的兑换码，立即发放
					$miyaoarr = $this->controller_duihuan_one($orderno);
				}else{
					if($this->typeid=='11'){
						$miyaoarr = $this->controller_duihuan_two($orderno);//单独兑换流程
					}else{
						$miyaoarr = $this->controller_duihuan_three($orderno);//上家发放，从表中读出对应的秘钥
					}
				}
				
			}else{//稍后发放，从后台输入对应秘钥
				$miyaoarr = $this->controller_duihuan_four($orderno);
			}
		}
		
		if($miyaoarr){
			//商品信息的更新
			$updateproduct = "update shop_product set buycount=buycount+1,kucun=kucun-1 where id='".$this->productid."'";
			$updateproductsql = parent::__get('HyDb')->execute($updateproduct);
			
			if($this->zhifuway=='1'){//积分支付，账户积分的扣除
				//用户积分的变化
				parent::update_userscore('xb_user',$this->score,'2','' );
			}
				
			if($this->zhifuway=='2'){//2-金额支付 1-积分支付 4-vip会员商品 5-免费商品
				$this->score='0';//金额支付
			}else if($this->zhifuway=='4'){//vip商品
				$getdescribe = '免费领取VIP'.$this->goodsname;
				$this->score='0';
			}else if($this->zhifuway=='5'){
				$this->score='0';
			}else{
				$getdescribe = '购买'.$this->goodsname.'消耗'.$this->score.'馅饼';
			}
			//积分记录的增加
			parent::insert_userscore('xb_user_score',parent::__get('userid'),'1','9',$this->score,$getdescribe,'');
			
			//订单id的获取
			$ordernoidsql = "select id from shop_userbuy where orderno='".$orderno."' order by id desc";
			$ordernoidlist = parent::__get('HyDb')->get_row($ordernoidsql);
			
			$this->keystr = $miyaoarr['keystr'];
			$this->passwd = $miyaoarr['passwd'];
			$this->tflag = $miyaoarr['tflag'];
				
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
			$echojsonstr = HyItems::echo2clientjson('100','订单提交成功',$temparr);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			//其他函数输出
		}
		
	}
	
	//实物兑换，后台系统生成6位兑换码
	public function controller_duihuan_one($orderno){
		
		$this->keystr = parent::getRandomString(6);//随机生成的兑换码
		
		if(strlen($this->keystr)==6){
			//秘钥数据的入库
			$miyaosql = "insert into shop_duihuanma(userid,goods_id,goods_name,type,maintype,flag,duihuanma,createtime) values
							('".parent::__get('userid')."','".$this->productid."','".$this->goodsname."','".$this->typeidchild."',
							'".$this->mtype."','9','".$this->keystr."','".date('Y-m-d H:i:s')."')";
			$miyaolist = parent::__get('HyDb')->execute($miyaosql);

			parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,'','',$this->fh_address,'','','','','2','2');//状态和实物类型
				
			$miyao = array(
					'keystr'     => $this->keystr,
					'passwd'     => '',
					'tflag'     => '22',
			);
			return $miyao;
		}else {
			$echojsonstr = HyItems::echo2clientjson('422','兑换码生成失败，系统错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	}
	
	
	//流量单独的兑换流量
	public function controller_duihuan_two($orderno){
	
		if($this->zhifuway=='1' ){
			//商品数量的判断
			if($this->tid>1){
				$echojsonstr = HyItems::echo2clientjson('423','流量兑换数量不能大于2个');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
	
		}
		//订单去重判断
		$r = parent::repeat_userbuy_orderno($orderno);
		if($r){
			$echojsonstr = HyItems::echo2clientjson('100','订单提交成功',$r);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}
	
		//模拟用户操作发放兑换码--运营商1，2，3--
		$url =$this->xiafaurl.'?gateway='.$this->gateway.'&mbps='.$this->mbps.'&ttype='.$this->ttype.'&orderno='.
				$orderno.'&userid='.parent::__get('userid').'&youxiaoday=30&name='.urlencode($this->goodsname).
				'&describe='.urlencode($this->goodsname);
		$duihuancode = HyItems::vget( $url, 10000 );
	
		if($duihuancode['httpcode']=='200'){
			$this->keystr = $duihuancode['content'];//生成兑换码
			//$this->keystr = '000000000000000000';
			if(strlen($this->keystr)!='18'){//秘钥生成错误
				$echojsonstr = HyItems::echo2clientjson('424','兑换码生成失败,兑换失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}else{//兑换码生成成功，数据的插入
				parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,'','',$this->fh_address,'','','','','2','1');
	
				$miyao = array(
						'keystr'     => $this->keystr,
						'passwd'     => '',
						'tflag'     => '2',
				);
				return $miyao;
			}
		}
	
	}
	
	
	//虚拟商品的电子卡，商家发放从对应的表中读出该字符串
	public function controller_duihuan_three($orderno){
	
		$duihuanmasql  = "select id,duihuanma,passwd from xb_duihuanma
						where flag=9 and maintype='".$this->typeid."' and type='".$this->typeidchild."'
						and goods_id='".$this->productid."' and  siteid='".$this->siteid."'
						order by id asc limit 1 ";
		parent::hy_log_str_add(HyItems::hy_trn2space($duihuanmasql)."\n");
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
			$echojsonstr = HyItems::echo2clientjson('425','兑换失败');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	
	}
	
	
	//兑换码稍微发放,后台输出
	public function controller_duihuan_four($orderno){
	
		$this->keystr='';
		$this->passwd='';
	
		parent::userbuy_insert_data($orderno,$this->tid,$this->keystr,$this->passwd,'',$this->fh_address,'','','','','2','1');
	
		$miyao = array(
				'keystr'     => $this->keystr,
				'passwd'     => $this->passwd,
				'tflag'     => '24',
	
		);
		return $miyao;
	}
	
	
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		//兑换参数的判断
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
		
		$ret = $this->controller_exec1();
		return $ret;
	}
	
}