<?php

/*
 * 馅饼接口的配置文件接口
 */

class HyXbCon{
	
	protected $inputdataarr;
	
	//获取传递的get参数数组
	function __construct($inputdataarr){
		$this->inputdataarr = $inputdataarr;
		unset($inputdataarr);
	}
	
	
	//初始化的入口
	function controller(){
		
		//获取操作类型的编号
		$thetype = isset($this->inputdataarr['thetype'])?$this->inputdataarr['thetype']:'';
		
		//判断操作类型格式是否正确
		if($thetype=='' || !is_numeric($thetype) ){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型编号格式非法';
			$echoarr['dataarr']    = array();
			echo json_encode($echoarr);
			
			return false;
			
			
		}else{
			
			//拼接生成要new的对应类名
			$newclassname = 'HyXb'.$thetype;
			
			$classfile_path = dirname(__FILE__).DIRECTORY_SEPARATOR.$newclassname.'.php';
			
			if(!file_exists($classfile_path)) {
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '操作类型文件不存在';
				$echoarr['dataarr']    = array();
				echo json_encode($echoarr);
				return false;
			}else {
				//new 对应类
				$initclass = new $newclassname($this->inputdataarr);
				$r = $initclass->controller_init();
				
				if($r===true){
					return true;
				}else{
					return true;
				}
					
				
				
			}
			
			
		
		}
		
	}
}