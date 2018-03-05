<?php

//====================================================
/*
使用实例，两种输出方式
$HyDownload = new HyDownload();
$HyDownload->set_init( './', 'test1.php', 'ceshi测试', array('php') );
$HyDownload->download(); //一次性输出
$HyDownload->download_limit(5);  //以5kb每秒的速度输出
*/
//====================================================

class HyDownload{
	
	private $hzarr      = array('jpg','png','jpeg','gif','bmp','txt','doc','docx','xls','xlsx','apk','mp3','wma','aac','FLAC','ra','mid','mov','rm','wmv','mp4','rmvb','mkv','mgp','flv'); //允许下载的后缀名
	private $filepath   = './'; //文件路径
	private $filename   = '';   //文件名称
	private $filelength = '';   //文件名称
	private $showname   = '';   //文件名称
	private $mineType   = 'text/plain'; //文件类型
	private $expire     = 180; //文件类型
	private $errorstr   = ''; //错误输出
	
	
	//参数初始化
	public function set_init( $filepath, $filename, $showname = '', $expire=180 ) {
		
		$filepath = trim($filepath);
		$filename = trim($filename);
		$showname = trim($showname);
		
		$this->filepath = $filepath;
		$this->filename = $filename;
		$this->showname = $showname;
		$this->expire   = $expire;
		
		
	}
	
	
	
	
	
	
	
	public function download() {
		
		if($this->down_init()===false) {
			echo $this->errorstr;
			exit;
		}
		
		//发送Http Header信息 开始下载
		header("Pragma: public");
		header("Cache-control: max-age=".$this->expire);
		//header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Expires: " . gmdate("D, d M Y H:i:s",time()+$this->expire) . "GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
		header("Content-Disposition: attachment; filename=".$this->showname);
		header("Content-Length: ".$this->filelength);
		header("Content-type: ".$this->mineType);
		header('Content-Encoding: none');
		header("Content-Transfer-Encoding: binary" );
		
		readfile($this->filepath.$this->filename);
		exit();
	}
	
	
	//$download_rate为每秒输出kb数
	public function download_limit($download_rate = 100) {
		
		if($this->down_init()===false) {
			echo $this->errorstr;
			exit;
		}
		
		//发送Http Header信息 开始下载
		header("Pragma: public");
		header("Cache-control: max-age=".$this->expire);
		//header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Expires: " . gmdate("D, d M Y H:i:s",time()+$this->expire) . "GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
		header("Content-Disposition: attachment; filename=".$this->showname);
		header("Content-Length: ".$this->filelength);
		header("Content-type: ".$this->mineType);
		header('Content-Encoding: none');
		header("Content-Transfer-Encoding: binary" );
		
		$file = fopen(($this->filepath.$this->filename),"r");
		while (!feof($file)){
			print fread($file,round($download_rate*1024));// 发送当前部分文件给浏览者
			ob_flush();
			flush();// flush 内容输出到浏览器端
			sleep(1);// 终端1秒后继续
		}
		
		exit();
	}
	
	
	//下载文件之前的判断调用函数
	private function down_init() {
		
		//清除文件判断处理缓存
		clearstatcache();
		
		//判断文件路径
		if($this->filepath=='') {
			$this->errorstr = '文件路径不存在';
			return false;
		}
	
		//判断文件名称
		if($this->filename=='') {
			$this->errorstr = '文件名称不存在';
			return false;
		}
	
		//判断该下载文件是否存在
		if(is_file($this->filepath.$this->filename)) {
			$this->filelength = filesize($this->filepath.$this->filename);
		}else {
			$this->errorstr = '该下载文件不存在';
			return false;
		}
	
		//判断该文件的类型是否在允许下载的类型之中
		$houzhui = strtolower(substr(strrchr($this->filename, '.'),1));
	
		if(in_array($houzhui, $this->hzarr)) {
			//通过
		}else {
			$this->errorstr = '该类型的文件不允许下载';
			return false;
		}
	
		if($this->showname=='') {
			$this->showname = $this->filename;
		}else {
			//为显示名称增加后缀
			$this->showname = $this->showname.'.'.$houzhui;
		}
	
		//获取该后缀类型对应的输出类型
		$this -> mime_content_type($houzhui);
	
	}
	
	
	
	
	private function mime_content_type($houzhui) {
		
		$contentType = array(
				'ai'		=> 'application/postscript',
				'aif'		=> 'audio/x-aiff',
				'aifc'		=> 'audio/x-aiff',
				'aiff'		=> 'audio/x-aiff',
				'asc'		=> 'application/pgp', //changed by skwashd - was text/plain
				'asf'		=> 'video/x-ms-asf',
				'asx'		=> 'video/x-ms-asf',
				'au'		=> 'audio/basic',
				'avi'		=> 'video/x-msvideo',
				'bcpio'		=> 'application/x-bcpio',
				'bin'		=> 'application/octet-stream',
				'bmp'		=> 'image/bmp',
				'c'			=> 'text/plain', // or 'text/x-csrc', //added by skwashd
				'cc'		=> 'text/plain', // or 'text/x-c++src', //added by skwashd
				'cs'		=> 'text/plain', //added by skwashd - for C# src
				'cpp'		=> 'text/x-c++src', //added by skwashd
				'cxx'		=> 'text/x-c++src', //added by skwashd
				'cdf'		=> 'application/x-netcdf',
				'class'		=> 'application/octet-stream',//secure but application/java-class is correct
				'com'		=> 'application/octet-stream',//added by skwashd
				'cpio'		=> 'application/x-cpio',
				'cpt'		=> 'application/mac-compactpro',
				'csh'		=> 'application/x-csh',
				'css'		=> 'text/css',
				'csv'		=> 'text/comma-separated-values',//added by skwashd
				'dcr'		=> 'application/x-director',
				'diff'		=> 'text/diff',
				'dir'		=> 'application/x-director',
				'dll'		=> 'application/octet-stream',
				'dms'		=> 'application/octet-stream',
				'doc'		=> 'application/msword',
				'dot'		=> 'application/msword',//added by skwashd
				'dvi'		=> 'application/x-dvi',
				'dxr'		=> 'application/x-director',
				'eps'		=> 'application/postscript',
				'etx'		=> 'text/x-setext',
				'exe'		=> 'application/octet-stream',
				'ez'		=> 'application/andrew-inset',
				'gif'		=> 'image/gif',
				'gtar'		=> 'application/x-gtar',
				'gz'		=> 'application/x-gzip',
				'h'			=> 'text/plain', // or 'text/x-chdr',//added by skwashd
				'h++'		=> 'text/plain', // or 'text/x-c++hdr', //added by skwashd
				'hh'		=> 'text/plain', // or 'text/x-c++hdr', //added by skwashd
				'hpp'		=> 'text/plain', // or 'text/x-c++hdr', //added by skwashd
				'hxx'		=> 'text/plain', // or 'text/x-c++hdr', //added by skwashd
				'hdf'		=> 'application/x-hdf',
				'hqx'		=> 'application/mac-binhex40',
				'htm'		=> 'text/html',
				'html'		=> 'text/html',
				'ice'		=> 'x-conference/x-cooltalk',
				'ics'		=> 'text/calendar',
				'ief'		=> 'image/ief',
				'ifb'		=> 'text/calendar',
				'iges'		=> 'model/iges',
				'igs'		=> 'model/iges',
				'jar'		=> 'application/x-jar', //added by skwashd - alternative mime type
				'java'		=> 'text/x-java-source', //added by skwashd
				'jpe'		=> 'image/jpeg',
				'jpeg'		=> 'image/jpeg',
				'jpg'		=> 'image/jpeg',
				'js'		=> 'application/x-javascript',
				'kar'		=> 'audio/midi',
				'latex'		=> 'application/x-latex',
				'lha'		=> 'application/octet-stream',
				'log'		=> 'text/plain',
				'lzh'		=> 'application/octet-stream',
				'm3u'		=> 'audio/x-mpegurl',
				'man'		=> 'application/x-troff-man',
				'me'		=> 'application/x-troff-me',
				'mesh'		=> 'model/mesh',
				'mid'		=> 'audio/midi',
				'midi'		=> 'audio/midi',
				'mif'		=> 'application/vnd.mif',
				'mov'		=> 'video/quicktime',
				'movie'		=> 'video/x-sgi-movie',
				'mp2'		=> 'audio/mpeg',
				'mp3'		=> 'audio/mpeg',
				'mpe'		=> 'video/mpeg',
				'mpeg'		=> 'video/mpeg',
				'mpg'		=> 'video/mpeg',
				'mpga'		=> 'audio/mpeg',
				'ms'		=> 'application/x-troff-ms',
				'msh'		=> 'model/mesh',
				'mxu'		=> 'video/vnd.mpegurl',
				'nc'		=> 'application/x-netcdf',
				'oda'		=> 'application/oda',
				'patch'		=> 'text/diff',
				'pbm'		=> 'image/x-portable-bitmap',
				'pdb'		=> 'chemical/x-pdb',
				'pdf'		=> 'application/pdf',
				'pgm'		=> 'image/x-portable-graymap',
				'pgn'		=> 'application/x-chess-pgn',
				'pgp'		=> 'application/pgp',//added by skwashd
				'php'		=> 'application/x-httpd-php',
				'php3'		=> 'application/x-httpd-php3',
				'pl'		=> 'application/x-perl',
				'pm'		=> 'application/x-perl',
				'png'		=> 'image/png',
				'pnm'		=> 'image/x-portable-anymap',
				'po'		=> 'text/plain',
				'ppm'		=> 'image/x-portable-pixmap',
				'ppt'		=> 'application/vnd.ms-powerpoint',
				'ps'		=> 'application/postscript',
				'qt'		=> 'video/quicktime',
				'ra'		=> 'audio/x-realaudio',
				'rar'		=> 'application/octet-stream',
				'ram'		=> 'audio/x-pn-realaudio',
				'ras'		=> 'image/x-cmu-raster',
				'rgb'		=> 'image/x-rgb',
				'rm'		=> 'audio/x-pn-realaudio',
				'roff'		=> 'application/x-troff',
				'rpm'		=> 'audio/x-pn-realaudio-plugin',
				'rtf'		=> 'text/rtf',
				'rtx'		=> 'text/richtext',
				'sgm'		=> 'text/sgml',
				'sgml'		=> 'text/sgml',
				'sh'		=> 'application/x-sh',
				'shar'		=> 'application/x-shar',
				'shtml'		=> 'text/html',
				'silo'		=> 'model/mesh',
				'sit'		=> 'application/x-stuffit',
				'skd'		=> 'application/x-koan',
				'skm'		=> 'application/x-koan',
				'skp'		=> 'application/x-koan',
				'skt'		=> 'application/x-koan',
				'smi'		=> 'application/smil',
				'smil'		=> 'application/smil',
				'snd'		=> 'audio/basic',
				'so'		=> 'application/octet-stream',
				'spl'		=> 'application/x-futuresplash',
				'src'		=> 'application/x-wais-source',
				'stc'		=> 'application/vnd.sun.xml.calc.template',
				'std'		=> 'application/vnd.sun.xml.draw.template',
				'sti'		=> 'application/vnd.sun.xml.impress.template',
				'stw'		=> 'application/vnd.sun.xml.writer.template',
				'sv4cpio'	=> 'application/x-sv4cpio',
				'sv4crc'	=> 'application/x-sv4crc',
				'swf'		=> 'application/x-shockwave-flash',
				'sxc'		=> 'application/vnd.sun.xml.calc',
				'sxd'		=> 'application/vnd.sun.xml.draw',
				'sxg'		=> 'application/vnd.sun.xml.writer.global',
				'sxi'		=> 'application/vnd.sun.xml.impress',
				'sxm'		=> 'application/vnd.sun.xml.math',
				'sxw'		=> 'application/vnd.sun.xml.writer',
				't'			=> 'application/x-troff',
				'tar'		=> 'application/x-tar',
				'tcl'		=> 'application/x-tcl',
				'tex'		=> 'application/x-tex',
				'texi'		=> 'application/x-texinfo',
				'texinfo'	=> 'application/x-texinfo',
				'tgz'		=> 'application/x-gtar',
				'tif'		=> 'image/tiff',
				'tiff'		=> 'image/tiff',
				'tr'		=> 'application/x-troff',
				'tsv'		=> 'text/tab-separated-values',
				'txt'		=> 'text/plain',
				'ustar'		=> 'application/x-ustar',
				'vbs'		=> 'text/plain', //added by skwashd - for obvious reasons
				'vcd'		=> 'application/x-cdlink',
				'vcf'		=> 'text/x-vcard',
				'vcs'		=> 'text/calendar',
				'vfb'		=> 'text/calendar',
				'vrml'		=> 'model/vrml',
				'vsd'		=> 'application/vnd.visio',
				'wav'		=> 'audio/x-wav',
				'wax'		=> 'audio/x-ms-wax',
				'wbmp'		=> 'image/vnd.wap.wbmp',
				'wbxml'		=> 'application/vnd.wap.wbxml',
				'wm'		=> 'video/x-ms-wm',
				'wma'		=> 'audio/x-ms-wma',
				'wmd'		=> 'application/x-ms-wmd',
				'wml'		=> 'text/vnd.wap.wml',
				'wmlc'		=> 'application/vnd.wap.wmlc',
				'wmls'		=> 'text/vnd.wap.wmlscript',
				'wmlsc'		=> 'application/vnd.wap.wmlscriptc',
				'wmv'		=> 'video/x-ms-wmv',
				'wmx'		=> 'video/x-ms-wmx',
				'wmz'		=> 'application/x-ms-wmz',
				'wrl'		=> 'model/vrml',
				'wvx'		=> 'video/x-ms-wvx',
				'xbm'		=> 'image/x-xbitmap',
				'xht'		=> 'application/xhtml+xml',
				'xhtml'		=> 'application/xhtml+xml',
				'xls'		=> 'application/vnd.ms-excel',
				'xlt'		=> 'application/vnd.ms-excel',
				'xml'		=> 'application/xml',
				'xpm'		=> 'image/x-xpixmap',
				'xsl'		=> 'text/xml',
				'xwd'		=> 'image/x-xwindowdump',
				'xyz'		=> 'chemical/x-xyz',
				'z'			=> 'application/x-compress',
				'zip'		=> 'application/zip',
		);
		
		if(isset($contentType[$houzhui])) {
			$this->mineType = $contentType[$houzhui];
		}else {
			$this->mineType = 'application/octet-stream';
		}
		return true;
		
	}
	
	
	
}


