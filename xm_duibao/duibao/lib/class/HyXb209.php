<?php
/*
 * 首页图片的获取
 */

class HyXb209 extends HyXb{
	
	private $width;
	private $height;
	private $type;
	
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
		
		$this->width  = isset($input_data['width'])?$input_data['width']:'';
		$this->height = isset($input_data['height'])?$input_data['height']:'';
		$this->type = isset($input_data['type'])?$input_data['type']:'';
		$this->button = isset($input_data['button'])?$input_data['button']:'';
	
	}
	
	
	//热门优惠分类
	public function controller_collectiontypelist(){
		
		
		
		if($this->type=='1'){//兑宝热门免费
			
			if($this->width==''){//753 * 292
				$this->width='200';
			}
			
			if($this->height==''){
				$this->height='160';
			}
			
			
			
			if($this->button==''){
				
				$youhuiquanconfsql  = "select id,shopid,shopname,img,imgurl,action, type,value,isused
								from xb_lunbotu where flag='1' and biaoshi='5' order by picname desc limit 3";
				$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
				
				
			}else if($this->button=='ios'){
				
				
				
				$youhuiquanconfsql  = "select id,shopid,shopname,img,imgurl,action, type,value,isused
								from xb_lunbotu where flag='1' and biaoshi='5' order by picname desc limit 3";
				$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
				
				$youhuiquanconflist = array();
			}
			
			
			foreach ($youhuiquanconflist as $keys => $vals){
				
				$replace = array("\t", "\r", "\n",);
					
				//图片展示
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接
				if(substr($youhuiquanconflist[$keys]['img'],0,7)=='http://' ||substr($youhuiquanconflist[$keys]['img'],0,8)=='https://' ){
					//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
				}else{
					$youhuiquanconflist[$keys]['img'] = $arr['duibao-basic'].$youhuiquanconflist[$keys]['img'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
						
				}
			}
			
			
		}else if($this->type=='2'){//兑宝特供优惠
			
			if($this->width==''){//753 * 292
				$this->width='320';
			}
				
			if($this->height==''){
				$this->height='179';
			}
			
			
			$youhuiquanconfsql  = "select id,img from xb_lunbotu where flag='1' and biaoshi='6' order by picname desc limit 4";
			$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
			
			foreach ($youhuiquanconflist as $keys => $vals){
				
				$replace = array("\t", "\r", "\n",);
					
				//图片展示
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接
				if(substr($youhuiquanconflist[$keys]['img'],0,7)=='http://' ||substr($youhuiquanconflist[$keys]['img'],0,8)=='https://' ){
					//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
				}else{
					$youhuiquanconflist[$keys]['img'] = $arr['duibao-basic'].$youhuiquanconflist[$keys]['img'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
				}
			}
			
			
		}else if($this->type=='3'){//特价好货
			
			if($this->width==''){//753 * 292
				$this->width='250';
			}
			
			if($this->height==''){
				$this->height='300';
			}
			
			$youhuiquanconfsql  = "select kindtype,kindname,smallpic from xb_kind where flag='1' and biaoshi='2' order by createtime desc limit 3";
			$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
			
			foreach ($youhuiquanconflist as $keys => $vals){
				
				$replace = array("\t", "\r", "\n",);
				
				//图片展示
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接
				if(substr($youhuiquanconflist[$keys]['smallpic'],0,7)=='http://' ||substr($youhuiquanconflist[$keys]['smallpic'],0,8)=='https://' ){
					//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['smallpic'] = str_replace($replace, '', $youhuiquanconflist[$keys]['smallpic']);
				}else{
					$youhuiquanconflist[$keys]['smallpic'] = $arr['duibao-basic'].$youhuiquanconflist[$keys]['smallpic'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['smallpic'] = str_replace($replace, '', $youhuiquanconflist[$keys]['smallpic']);
				}
				
			}
			
		}else if($this->type=='4'){
			
			if($this->width==''){//753 * 292
				$this->width='640';
			}
				
			if($this->height==''){
				$this->height='203';
			}
			
			
			$youhuiquanconfsql  = "select * from xb_lunbotu where flag='1' and biaoshi='7' ";
			$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
				
			foreach ($youhuiquanconflist as $keys => $vals){
			
				$replace = array("\t", "\r", "\n",);
					
				//图片展示
				$arr = unserialize(BUCKETSTR);//获取七牛访问链接
				if(substr($youhuiquanconflist[$keys]['img'],0,7)=='http://' ||substr($youhuiquanconflist[$keys]['img'],0,8)=='https://' ){
					//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
				}else{
					$youhuiquanconflist[$keys]['img'] = $arr['duibao-basic'].$youhuiquanconflist[$keys]['img'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$youhuiquanconflist[$keys]['img'] = str_replace($replace, '', $youhuiquanconflist[$keys]['img']);
				}
			}
			
			
			
		}
		
		
		if(count($youhuiquanconflist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '数据获取成功';
			$echoarr['dataarr'] = $youhuiquanconflist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '数据获去成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	
	//操作入口--热门优惠分类
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='209'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		
	
		//热门优惠分类
		$this->controller_collectiontypelist();
	
		return true;
	}
	
	
	
	
}