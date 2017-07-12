<?php

//图片缩略图的生成



//$endtime = date("Y-m-d",strtotime("+1 months", strtotime($this->todaytime)));

 function save($filename) {
 	
 	
 	 $config =   array(
 			'maxSize'           =>  -1,    // 上传文件的最大值
 			'supportMulti'      =>  true,    // 是否支持多文件上传
 			'allowExts'         =>  array(),    // 允许上传的文件后缀 留空不作后缀检查
 			'allowTypes'        =>  array(),    // 允许上传的文件类型 留空不做检查
 			'thumb'             =>  false,    // 使用对上传图片进行缩略图处理
 			'imageClassPath'    =>  'ORG.Util.Image',    // 图库类包路径
 			'thumbMaxWidth'     =>  '',// 缩略图最大宽度
 			'thumbMaxHeight'    =>  '',// 缩略图最大高度
 			'thumbPrefix'       =>  'thumb_',// 缩略图前缀
 			'thumbSuffix'       =>  '',
 			'thumbPath'         =>  '',// 缩略图保存路径
 			'thumbFile'         =>  '',// 缩略图文件名
 			'thumbExt'          =>  '',// 缩略图扩展名
 			'thumbRemoveOrigin' =>  false,// 是否移除原图
 			'thumbType'         =>  1, // 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
 			'zipImages'         =>  false,// 压缩图片文件上传
 			'autoSub'           =>  false,// 启用子目录保存文件
 			'subType'           =>  'hash',// 子目录创建方式 可以使用hash date custom
 			'subDir'            =>  '', // 子目录名称 subType为custom方式后有效
 			'dateFormat'        =>  'Ymd',
 			'hashLevel'         =>  1, // hash的目录层次
 			'savePath'          =>  '',// 上传文件保存路径
 			'autoCheck'         =>  true, // 是否自动检查附件
 			'uploadReplace'     =>  false,// 存在同名是否覆盖
 			'saveRule'          =>  'uniqid',// 上传文件命名规则
 			'hashType'          =>  'md5_file',// 上传文件Hash规则函数名
 	);
 	
 	
	//获取文件的后缀
 	$houzhui = $this->getExt($filename);
	
	
	// 如果是图像文件 检测文件格式
	if( in_array(strtolower($houzhui),array('gif','jpg','jpeg','bmp','png','swf'))) {
		$info   = getimagesize($file);
		if(false === $info || ('gif' == strtolower($file['extension']) && empty($info['bits']))){
			$this->error = '非法图像文件';
			return false;
		}
	}
	
	
	if($config['thumb'] && in_array(strtolower($houzhui),array('gif','jpg','jpeg','bmp','png'))) {
		$image =  getimagesize($filename);
		if(false !== $image) {
			//是图像文件生成缩略图
			$thumbWidth		=	explode(',',$this->thumbMaxWidth);
			$thumbHeight	=	explode(',',$this->thumbMaxHeight);
			$thumbPrefix	=	explode(',',$this->thumbPrefix);
			$thumbSuffix    =   explode(',',$this->thumbSuffix);
			$thumbFile		=	explode(',',$this->thumbFile);
			$thumbPath      =   $this->thumbPath?$this->thumbPath:dirname($filename).'/';
			$thumbExt       =   $this->thumbExt ? $this->thumbExt : $file['extension']; //自定义缩略图扩展名
			// 生成图像缩略图
			import($this->imageClassPath);
			for($i=0,$len=count($thumbWidth); $i<$len; $i++) {
				if(!empty($thumbFile[$i])) {
					$thumbname  =   $thumbFile[$i];
				}else{
					$prefix     =   isset($thumbPrefix[$i])?$thumbPrefix[$i]:$thumbPrefix[0];
					$suffix     =   isset($thumbSuffix[$i])?$thumbSuffix[$i]:$thumbSuffix[0];
					$thumbname  =   $prefix.basename($filename,'.'.$file['extension']).$suffix;
				}
				Image::thumb($filename,$thumbPath.$thumbname.'.'.$thumbExt,'',$thumbWidth[$i],$thumbHeight[$i],true);
			}
			if($this->thumbRemoveOrigin) {
				// 生成缩略图之后删除原图
				unlink($filename);
			}
		}
	}
	if($this->zipImags) {
		// TODO 对图片压缩包在线解压

	}
	return true;
}


//获取文件的后缀
function getExt($filename) {
	$pathinfo = pathinfo($filename);
	return $pathinfo['extension'];
}