<?php

//流来给你密钥兑换生成封装类

class HyMiyao {
	
	private $gateway;
	private $mbps;
	private $ttype;
	//private $provtype;
	private $orderno;
	private $youxiaoday;
	private $spname; //商品名称
	private $describe; //商品描述
	private $topstr;
	private $key_over_datetime;
	protected $HyDb;
	
	public function __construct($initarr=array()) {
		
		$this->gateway  = isset($initarr['gateway'])?$initarr['gateway']:'';
		$this->mbps     = isset($initarr['mbps'])?$initarr['mbps']:'';
		$this->ttype    = isset($initarr['ttype'])?$initarr['ttype']:'';
		//$this->provtype = isset($initarr['provtype'])?$initarr['provtype']:'';
		$this->orderno  = isset($initarr['orderno'])?$initarr['orderno']:'';
		$this->userid   = isset($initarr['userid'])?$initarr['userid']:'';
		$this->youxiaoday = isset($initarr['youxiaoday'])?$initarr['youxiaoday']:'';
		$this->spname   = isset($initarr['spname'])?$initarr['spname']:'';
		$this->describe = isset($initarr['describe'])?$initarr['describe']:'';
		
		
		$this->topstr = HyItems::topstr_num2str($this->gateway);
		
		
		if(!is_numeric($this->mbps)) {
			$this->mbps = 0;
		}
		//兑换码过期时间
		$this->key_over_datetime = date('Y-m-d H:i:s',(time()+($this->youxiaoday*24*60*60)));
		
		$this->HyDb = new HyDb();
		
		
	}
	
	
	
	
	public function miyao_create() {
		//判断该订单号是否已经使用过，使用过的订单号不可以再次生成密钥
		$r = $this->orderid_check();
		if($r!==true) {
			//已存在的直接输出订单号
			return $r;
		}
		
		$miyaostring = $this->hy_miyao();
		//将密钥插入数据库
		$this->orderid_miyao_insert($miyaostring);
		
		echo $miyaostring;
		
	}
	
	
	
	
	private function orderid_check() {
		
		$sql_pan = "select id,keystr from dh_orderlist where orderno='".$this->orderno."'";
		$list_pan = $this->HyDb->get_row($sql_pan);
		if(count($list_pan)>0) {
			echo $list_pan['keystr'];
			return false;
		}else {
			return true;
		}
		
	}
	private function orderid_miyao_insert($miyao='') {
		
		$sql_insert  = "insert into dh_orderlist (gateway,mbps,ttype,
						keystr,key_create_datetime,key_over_datetime,orderno,
						userid,name,content) values ('".$this->gateway."','".$this->mbps."','".$this->ttype."',
								'".$miyao."','".date('Y-m-d H:i:s')."','".$this->key_over_datetime."','".$this->orderno."',
								'".$this->userid."','".$this->spname."','".$this->describe."')";
		
		$this->HyDb->execute($sql_insert);
		
		return true;
		
	}
	
	
	private function hy_miyao() {
		
		$topstr = $this->topstr;
		
		$y = date('y');
		$m = date('m');
		$d = date('d');
		$h = date('G');
		$timestr = uniqid();
		
		$mkeystring = '';
		$mkeystring .= $topstr; //密钥开头首字母
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey($y));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey($m));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey($d));
		
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey($h));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($timestr,5,1))+hexdec(substr($timestr,12,1))));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($timestr,6,1))+hexdec(substr($timestr,11,1))));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($timestr,7,1))+hexdec(substr($timestr,10,1))));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($timestr,8,1))+hexdec(substr($timestr,9,1))));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		$mkeystring .= HyItems::hy_func_str32(mt_rand(0,32));
		
		
		$checkmd5 = md5($mkeystring);
		
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($checkmd5,8,1))+hexdec(substr($checkmd5,16,1))));
		$mkeystring .= HyItems::hy_func_str32(HyItems::hy_func_cintkey(hexdec(substr($checkmd5,12,1))+hexdec(substr($checkmd5,24,1))));
		
		//返回密钥字符串
		return $mkeystring;
		
		
		
	}
	
	
	
	
	
	
	
	
	
} 