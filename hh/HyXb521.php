<?php
/*
 * 兑换信息的确认
 */

class HyXb521 extends HyXb{
	
	private $sendurl;//对内访问链接
	
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
		
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
		
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
		
		//流量接口
		$this->sendurl = URLSEND;
	}
	/*
	 * 商品类型字段标识
	 * 11001
	 * 虚拟+流量+流量类型
	 */
	
	
	//商品的兑换
	public function controller_duihuanproduct(){
		
		
		//判断是否审核通过，是否启用
		$productidsql  = "select * from shop_product where flag=1 and status=1 and id='".$this->productid."'";
		$productidlist = parent::__get('HyDb')->get_row($productidsql); 
		
		//分为1.实物--实物读取用户的地址表 2--虚拟物品获取兑换码
		if($productidlist['id']>0){
			
			//判断该商品的类型
			//$protypesql = "";
			
			//该商品每次下载的次数
			$userdaymax   = $productidlist['userdaymax'];
			$usermonthmax = $productidlist['usermonthmax'];
			$userallmax   = $productidlist['userallmax'];
			$daymax       = $productidlist['daymax']; //该商品的当日兑换次数
			$price        = $productidlist['price']; //商品的金额价格
			$score        = $productidlist['score'];  //商品的积分价格
			
			$zhifuway = $productidlist['feettype'];//用户的支付方式
			$typeid = $productidlist['typeid'];//判断商品的类型11001--流量  21001--实物  12001--卡密   13001--卡券
			$goodsname = $productidlist['name'];//商品名称
			
			
			//判断1.该商品的库存是否大于0  2.是否上架 3.用户每日 每月 每年的最大兑换次数
			if($productidlist['kucun']>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '该商品库存为零，不可以进行兑换';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			if($productidlist['stop_datetime']>date('Y-m-d H:i:s')){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该商品已下架，不可以进行兑换';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
			//判断该用户当天可以兑换的最大数量
			$starttime = date('Y-m-d 00:00:00');
			$endtime   = date('Y-m-d 23:59:59');
			$maxduihuansql  = "select count(id) as maxnum from shop_userbuy where order_createtime>='".$starttime."' and order_createtime<='".$endtime."' and productid='".$this->productid."'";
			$maxduihuanlist = parent::__get('HyDb')->get_row($maxduihuansql); 
			
			if($maxduihuanlist['maxnum']>$daymax){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该商品的兑换次数达到该商品的每日库存';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
			
			//判断该用户在一天 一月 一年之内兑换的次数
			$userbuysql  = "select count(id) as num from shop_userbuy where order_createtime>='".$starttime."' and order_createtime<='".$endtime."' and userid='".$this->userid."' ";
			$userbuylist = parent::__get('HyDb')->get_row($userbuysql); 
			
			if($userbuylist['num']>$userdaymax){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '超过用户每日最大兑换次数';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
			//开始时间从当前时间向前推一个月
			$startmonth = date("Y-m-d", strtotime("-1 month"));
			
			//当前时间
			$endmonth  = date("Y-m-d",time());
			
			$monthbuysql  = "select count(id) as num from shop_userbuy where order_createtime>='".$startmonth."' and order_createtime<='".$endmonth."' and userid='".$this->userid."'";
			$monthbuylist = parent::__get('HyDb')->get_row($monthbuysql); 
			
			if($monthbuylist['num']>$usermonthmax){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '超过用户每月最大兑换次数';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
			$keyongjifen = 0; //可用积分
			$keyongmoney = 0; //可用金额
			
			//查询用户的可用积分和金额
			$userscoresql  = "select keyong_jifen,keyong_money from xb_user where id='".$this->userid."' ";
			$userscorelist = parent::__get('HyDb')->get_row($userscoresql); 
			
			//判断用户积分或金额是否满足兑换
			if($zhifuway=='1'){//使用积分支付
				$keyongjifen = $userscorelist['keyong_jifen'];//用户可用积分
				
				if($keyongjifen<=$score){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '用户积分不足！';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			
			}else if($zhifuway=='2'){//使用金额
				$keyongmoney = $userscorelist['keyong_money'];
				
				if($keyongmoney<=$price){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '用户金额不足！';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}
			
			}else if($zhifuway=='3'){//两者混合使用
				
				$keyongjifen = $userscorelist['keyong_jifen'];
				$keyongmoney = $userscorelist['keyong_money'];
				
				//积分和金额的转换关系一元等于100积分,得出用户的总积分
				$keyongjifen = $keyongmoney*DISCOUNT+$keyongjifen;
				
				if($keyongjifen<=$score){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '用户积分不足！';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			}
			
			
			$data = array(
					'thetype' => '522',
					'nowtime' => parent::__get('xb_nowtime'),
					'md5key'  => parent::__get('xb_md5key'),
					'usertype'=> parent::__get('xb_usertype'),
					'userid'  => parent::__get('xb_userid'),
					'userkey' => parent::__get('xb_userkey'),
					'productid'  => $this->productid,
					'keyong_jifen' => $userscorelist['keyong_jifen'],
					'keyong_money' => $userscorelist['keyong_money'],
						
			);
			
			$HyXb521_c = new HyXb521_c();
			$a = $HyXb521_c->aaa($data);
			
			
			
			
			//商品的兑换操作封装在522接口中
			$sendurl = $this->sendurl;
			
			
			$res = HyItems::vpost($sendurl,$data,10000);
			
			
			if($res['httpcode']=='200'){
			
				echo $res['content'];
				return true;
			}
			
			
			
			/* //积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录
			//订单编号date('YmdHis').mt_rand(1000,9999);
			//商品类型的判断
			$protype = substr($typeid,0,1);
			
			if($protype=='11'){//该商品为流量兑换，走流量接口
				//流量商品名称的输入规则是 移动10M流量 --运营商+兆数+流量适用范围+唯一订单号+用户userid+有效期+商品名称+商品描述
				$ordersn = date('YmdHis').mt_rand(1000,9999);//商品订单号
				//获取流量标识 移动100M流量
				if($goodsname){
					
				}
				
					
			}else if($protype=='12'){//该商品为卡密类的商品，走卡密接口
					
					
			}else if($protype=='13'){//该商品为饭票商品，走饭票的接口
					
			}else if($protype=='14'){//该商品为实物，走实物接口
				
			}
			
			
			////积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录 */
			
			
			
			
			
			
			
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '该商品不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		 
		
	}
	
	
	
	
	
	
	//操作入口--商品的兑换
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='521'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//商品id的判断
		if($this->productid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品id不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
		//判断每页的条数，数值介于1到20之间
		if($this->count<0 || $this->count>20){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '每页展示的条数超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
	
		//商品的兑换
		$this->controller_duihuanproduct();
	
		return true;
	}
	
}