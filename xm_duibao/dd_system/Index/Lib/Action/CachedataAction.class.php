<?php 
/*
 * 缓存数据的清除
 */
class CachedataAction extends Action {
	
	private $lock_cache                 = '97';
	//缓存数据的删除
	public function cache(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateyhqshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//删除文件的目录
		$url = DELPATH.'/duibaoinit/tmpsqlfile';
		
		$opendir = opendir($url);
		while ($file=readdir($opendir)){
			
			if($file!='.' && $file!='..'){
				$fullpath=$url."/".$file;
				if(!is_dir($fullpath)){
					unlink($fullpath);
				}else{
					cache($fullpath);
				}
			}
			
		}
		
		closedir($opendir);
		
		echo 'success';
	}
	
	
	
	
	
	
	//判断用户是否登陆的前台展现封装模块
	private function loginjudgeshow($lock_key) {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$lockarr = loginjudge($lock_key);
		if($lockarr['grade']=='C') {
			//通过
		}else if($lockarr['grade']=='B') {
			exit($lockarr['exitmsg']);
		}else if($lockarr['grade']=='A') {
			echo $lockarr['alertmsg'];
			$this -> error($lockarr['errormsg'],'__APP__/Login/index');
		}else {
			exit('系统错误，为确保系统安全，禁止登入系统');
		}
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
	}
	
	
	
	
}














































?>