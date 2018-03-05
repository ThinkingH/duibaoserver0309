<?php
/*
 * 领奖中心
 */
class HyXb1031 extends HyXb{
	
	private $page; 
	private $pagesize; 
	private $type; //
	private $xiafaurl; //type=1 全部数据 2-流量  3-优惠券  4-实物列表 5-领取操作
	private $typeid; //搜索查询字段
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //查询类型
		$this->typeid = isset($input_data['typeid']) ? $input_data['typeid']:'';  //
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
	}
	
	//获取抽奖数据
	public function controller_exec1(){
		$where = '';
		if($this->type=='1'){
			$where = '';
		}else if($this->type=='2'){
			$where = ' and type=2 ';//流量数据
		}else if($this->type=='3'){
			$where = ' and type=3 ';//优惠券数据
		}else if($this->type=='4'){
			$where = ' and type=1 ';//实物
		}
		//获取总条数
		$tasksumsql  = "select count(*) as num from db_prize_list where userid='".parent::__get('userid')."'  $where  ";
		parent::hy_log_str_add($tasksumsql."\n");
		$tasksumlist = parent::__get('HyDb')->get_one($tasksumsql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$tasksumlist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
		$prize_sql  = "select * from db_prize_list where userid='".parent::__get('userid')."' $where order by create_datetime desc ".$pagelimit;
		$prize_list = parent::__get('HyDb')->get_all($prize_sql);
		
		
		$rarr = array(
				'pagemsg' => $pagemsg,
				'list' => $prize_list,
		);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$rarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	//流量领取操作
	public function controller_exec2(){
		
		//获取该数据的详情
		$get_sql = "select * from db_prize_list where id='".$this->typeid."' and type='2'  ";
		$get_list = parent::__get('HyDb')->get_row($get_sql);
		if($get_list['id']>0 &&$get_list['quanid']>='100' ){
			$phone = $get_list['phone'];
			//判断手机号对应的运营商 1-移动 2-联通 3-电信
			$gateway = parent::yunyingshangcheck($phone,$type='num');
			if(!is_numeric($gateway)){
				$echojsonstr = HyItems::echo2clientjson('100','手机号错误');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
			$goodsname='';
			$keystr='';
			if($gateway=='1'){
				$goodsname = '移动流量100M';
				$productid = '4';
			}else if($gateway=='2'){
				$goodsname = '联通流量100M';
				$productid ='11';
			}else if($gateway=='3'){
				$goodsname = '电信流量100M';
				$productid ='7';
			}
			//商品订单
			$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
			//流量兑换
			$url =$this->xiafaurl.'?gateway='.$gateway.'&mbps=100&ttype=1&orderno='.$orderno.'&userid='.parent::__get('userid').
				'&youxiaoday=30&name='.urlencode($goodsname).'&describe='.urlencode($goodsname);
			parent::hy_log_str_add(HyItems::hy_trn2space($url)."\n");
			$duihuancode = HyItems::vget( $url, 10000 );
			if($duihuancode['httpcode']=='200'){
				$keystr = $duihuancode['content'];//生成兑换码
				//$keystr = 'uuuuuuuuuuuuuuuuuu';//生成兑换码
				if(strlen($keystr)!='18'){
					$echojsonstr = HyItems::echo2clientjson('411','领取失败,系统错误');
					if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
					echo $echojsonstr;
					return false;
				}else{
					//订单数据的插入
					$insert_sql = "insert into shop_userbuy (userid,siteid,typeid,mtype,name,productnum,orderno,keystr,productid,status,order_createtime)
							 values ('".parent::__get('userid')."','1000','11','1','".$goodsname."','1','".$orderno."','".$keystr."','".$productid."','3','".date('Y-m-d H:i:s')."')";
					parent::hy_log_str_add(HyItems::hy_trn2space($insert_sql)."\n");
					parent::__get('HyDb')->execute($insert_sql);
					//用户流量消耗100
					$update_sql = "update db_prize_list set quanid=quanid-100 where id='".$this->typeid."'";
					parent::hy_log_str_add(HyItems::hy_trn2space($update_sql)."\n");
					parent::__get('HyDb')->execute($update_sql);
					
					//获取订单id
					$selectid_sql  = "select id from shop_userbuy where orderno='".$orderno."'  ";
					$selectid_list = parent::__get('HyDb')->get_row($selectid_sql);
				}
				
				$temparr = array(
						'goodsname' =>$goodsname,
						'keystr'     => $keystr,
						'phone'     => $phone,
						'createtime' => date('Y-m-d H:i:s'),
						'orderid'         => $selectid_list['id'],//订单id
				);
				
				$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$temparr);
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
				
			}else{
				$echojsonstr = HyItems::echo2clientjson('412','领取失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
		}else{
			$echojsonstr = HyItems::echo2clientjson('413','流量未达到领取上限');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='5'){//领取操作
			$ret = $this->controller_exec2();
		}else{
			$ret = $this->controller_exec1();
		}
		
		return $ret;
	}
	
}