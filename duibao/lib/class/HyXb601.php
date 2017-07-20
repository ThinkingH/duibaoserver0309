<?php
/*
 * 首页类型分类接口
 */

class HyXb601 extends HyXb{
	
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
	
		//接收用户的userid
		$this->kindtype = isset($input_data['kindtype']) ? $input_data['kindtype']:'';  //商品的类型
	
	}
	
	
	public function controller_shouyetype(){
		
		
		if(trim($this->kindtype)=='all'){//获取首页的全部类型
			
			$seldata = "select id,kindtype,kindname from maintype where flag=1 order by id";
			$selsql  = parent::__get('HyDb')->get_all($seldata);
			
			
			if(count($selsql)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '列表获取成功！';
				$echoarr['dataarr']    = $selsql;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '列表获取为空！';
				$echoarr['dataarr']    = $selsql;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				
				echo json_encode($echoarr);
				return true;
			}
			
		}else{
			
			$seldata = "select id,childtype,smallpic from shouye_config where flag=1 and type='".trim($this->kindtype)."' order by id";
			$selsql  = parent::__get('HyDb')->get_all($seldata);
			
			/*  $replace = array("\t", "\r", "\n",);
				return str_replace($replace, ' ', $str);*/
			
			foreach ($selsql as $keys=>$vals){//
				
				$replace = array("\t", "\r", "\n",);
				$selsql[$keys]['smallpic'] =  str_replace($replace, '', $selsql[$keys]['smallpic']);
			}
			
			
			
			if(count($selsql)>0){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '子类型获取成功！';
					$echoarr['dataarr']    = $selsql;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '子类型获取为空！';
					$echoarr['dataarr']    = $selsql;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					
					echo json_encode($echoarr);
					return true;
				}
			
		}
		
		
	}
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
	
		//首页类型的获取
		$this->controller_shouyetype();
	
		return true;
	
	}
	
}