<?php
/*
 * 商品详情页
 */
class HyXb1041 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $productid;  //商品id
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		$this->productid = isset($input_data['productid'])?$input_data['productid']:'';//订单编号
		
		if(''==$this->imgwidth) {
			$this->imgwidth = '800';
		}
		if(''==$this->imgheight) {
			$this->imgheight = '800';
		}
	}
	
	
	
	public function controller_exec1(){
		
		//商品数据
		$shopdata_sql = "select * from shop_product where flag=1 and status=1 and onsales=1 and id='".$this->productid."' ";
		$shopdata_list = parent::__get('HyDb')->get_row($shopdata_sql);
		
		//商户信息
		$store_sql  = "select id,storename,touxiang,phone from shop_site where flag=1 and checkstatus=2 and id='".$shopdata_list['siteid']."'";
		$store_list = parent::__get('HyDb')->get_row($store_sql); 
		
		//第三方店铺链接
		$product_sql  = "select * from shop_store where siteid='".$shopdata_list['siteid']."' ";
		$product_list = parent::__get('HyDb')->get_all($product_sql);
		
		//数据查询成功
		if($shopdata_list['id']>0){
			
			$shopdata_list['showpic1'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopdata_list['showpic1'],$this->imgwidth,$this->imgheight);
			$shopdata_list['showpic2'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopdata_list['showpic2'],$this->imgwidth,$this->imgheight);
			$shopdata_list['showpic3'] = HyItems::hy_qiniuimgurl('duibao-shop',$shopdata_list['showpic3'],$this->imgwidth,$this->imgheight);
			
			
			//feet=5免费商品
			if($shopdata_list['feetype']=='5'){
				$shopdata_list['prizeurl'] = 'http://xbapp.xinyouxingkong.com/choujiang/index.php';
			}else{
				$shopdata_list['prizeurl']='';
			}
			
			//feet=4vip会员
			if($shopdata_list['feetype']=='4'){
				$shopdata_list['video_url'] = 'http://xbapp.xinyouxingkong.com/choujiang/index.php';
			}else{
				$shopdata_list['video_url']='';
			}
			
			//商品详情
			$shopdata_list['miaoshu'] = htmlspecialchars_decode($shopdata_list['miaoshu']);
			
			//商品对应商户信息
			$shopdata_list['sitearr'] = $store_list;
			//第三方店铺信息
			$shopdata_list['shoparr'] = $product_list;
			
			if($shopdata_list['pickup']=='1'){
				$shopdata_list['pickup']='自提';
			}else if($shopdata_list['pickup']=='2'){
				$shopdata_list['pickup']='包邮';
			}else{
				$shopdata_list['pickup']='包邮';
			}
			//商品的价格展示score
			$shopdata_list['scoremoney'] = parent::func_diffzhifutype($shopdata_list['feetype'],$shopdata_list['price'],$shopdata_list['score']);
			
			$echojsonstr = HyItems::echo2clientjson('100','获取成功',$shopdata_list);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
			
		}
		
		
	}
	
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if( !is_numeric($this->productid)){
			$echojsonstr = HyItems::echo2clientjson('421','订单id不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		$ret = $this->controller_exec1();
		return $ret;
	}
	
}