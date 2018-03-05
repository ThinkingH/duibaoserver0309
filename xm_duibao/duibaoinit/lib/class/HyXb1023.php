<?php
/*
 * 附近优惠信息的发布
 */

class HyXb1023 extends HyXb{
	
	protected $lat;
	protected $lng;
	private $houzhui;
	private $imgdata;
	private $discount;
	private $nowprice;
	private $yuanprice;
	private $address;
	private $proname;
	private $phone;
	private $over_datetime;
	private $imgpath;
	
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->lat = isset($input_data['lat'])? $input_data['lat']:'';  //纬度
		$this->lng = isset($input_data['lng'])? $input_data['lng']:'';  //经度
		$this->houzhui          = isset($input_data['houzhui']) ? $input_data['houzhui'] : 'jpg' ; //图片进行base64编码后处理的字符串，传递时请按规范先urlencode
		$this->imgdata          = isset($input_data['imgdata']) ? $input_data['imgdata'] : '' ;
		$this->type = isset($input_data['type'])? $input_data['type']:'';  //商品类型
		$this->discount = isset($input_data['discount'])? $input_data['discount']:'';  //折扣价格
		$this->nowprice   = isset($input_data['nowprice'])? $input_data['nowprice']:'';        //现价
		$this->yuanprice  = isset($input_data['yuanprice'])?$input_data['yuanprice']:'';     //原价
		$this->address  = isset($input_data['address'])?$input_data['address']:'';     //地址
		$this->proname  = isset($input_data['proname'])?$input_data['proname']:'';     //店铺名称
		$this->phone  = isset($input_data['phone'])?$input_data['phone']:'';  //商家电话
		$this->over_datetime  = isset($input_data['over_datetime'])?$input_data['over_datetime']:'';     //截止时间
		
		//图片的存放位置
		$this->imgpath = TMPPICPATH;
	}
	
	
	protected function controller_exec1(){
		//图片的保存
		if(!file_exists($this->imgpath )) {
			mkdir( $this->imgpath, 0777, true );
		}
		//图片文件名
		$filename = parent::__get('userid').date('Ymdhis').rand(1000,9999).'.'.$this->houzhui;
		$filepathname = $this->imgpath.$filename;
		
	//	$this->imgdata = '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';
		
		//把图片的编码解码为图片，存到对应的路径中
		$r = file_put_contents($filepathname,base64_decode($this->imgdata));
		
		//图片后缀重组
		$cz_filepathname = HyItems::hy_getfiletype($filepathname);
		//对文件进行重命名，修改后缀
		rename($filepathname,$cz_filepathname);
		
		if(false===parent::func_isImage($cz_filepathname)){
			//解析失败
			@unlink($cz_filepathname); //删除文件
			$echojsonstr = HyItems::echo2clientjson('335','图片解析失败，请重试');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{
			//上传到七牛云之前先进行图片格式转换，统一使用jpg格式
			$r = HyItems::hy_resave2jpg($cz_filepathname);
			if($r!==false) {
				parent::hy_log_str_add($r."\n");
				$cz_filepathname = $r;
			}
			
			//上传到七牛云
			$r = parent::upload_qiniu('duibao-find',$cz_filepathname,pathinfo($cz_filepathname,PATHINFO_BASENAME),'yes');
			if(false===$r){
				@unlink($cz_filepathname); //删除文件
				//上传失败
				$echojsonstr = HyItems::echo2clientjson('336','图片上传失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}else{
				@unlink($cz_filepathname); //删除文件
				$filebasename  = pathinfo($cz_filepathname, PATHINFO_BASENAME);
				$theurl = 'http://127.0.0.1/'.date('YmdHis').mt_rand(1000,9999);
				//发布数据的入库处理
				$insertsql = "insert into z_fabulist (hyflag,shstatus,userid,faflag,create_datetime,maintype,title,picurl,
						yuanprice,nowprice,address,lat,lng,over_datetime,zflag,phone) values
						('1','99','".parent::__get('userid')."','1',
						'".date('Y-m-d H:i:s')."','".$this->type."','".$this->proname."','".$filebasename."',
							'".$this->yuanprice."','".$this->nowprice."','".$this->address."',
							'".$this->lat."','".$this->lng."','".$this->over_datetime."','1','".$this->phone."')";
				parent::hy_log_str_add($insertsql."\n");
				$insertlist = parent::__get('HyDb')->execute($insertsql);
				
				if($insertlist){
					
					//判断该用户在一天之内的发布次数（一天只限制发布3次）
					$panduansql = "select count(*) as num from z_fabulist where userid='".parent::__get('userid')."' and faflag='1' 
									and create_datetime>='".date('Y-m-d 00:00:00')."' and create_datetime<='".date('Y-m-d 23:59:59')."'";
					parent::hy_log_str_add($panduansql."\n");
					$panduanlist = parent::__get('HyDb')->get_one($panduansql);
					
					if($panduanlist<=3){//每个用户前三次发布会赠送积分
						$getdescribe = '发布优惠信息获取10馅饼';
						$message = '恭喜你发布优惠信息获取10馅饼，请注意查看';
						//用户信息的增加
						parent::update_userscore('xb_user','10',parent::__get('userid'),parent::__get('userkey'));
						//积分记录的插入
						parent::insert_userscore('xb_user_score',parent::__get('userid'),'1','1','10',$getdescribe);
						//推送信息的插入
						parent::insert_usertuisong('xb_user_tuisong',parent::__get('userid'),'1','1',$taskid='0',$message);
						
						$userlistdata = parent::__get('userlistdata');
						//极光推送
						parent::func_jgpush($userlistdata['jiguangid'],$message,'1',$m_txt='',$m_time='86400');
					}
					
					$echojsonstr = HyItems::echo2clientjson('100','优惠信息发布成功');
					if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
					echo $echojsonstr;
					return false;
					
					
				}else{
					$echojsonstr = HyItems::echo2clientjson('338','优惠信息发布失败');
					if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
					echo $echojsonstr;
					return false;
				}
			}
		}
		
		
		
	}
	
	
	//操作入口
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		//参数判断
		$c = parent::func_ziduancheck();
		if($c===false){
			return false;
		}
		
		if($this->proname==''){
			$echojsonstr = HyItems::echo2clientjson('337','优惠信息名称不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		$ret = $this->controller_exec1();
		return $ret;
	}
	
}