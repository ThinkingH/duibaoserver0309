<?php

//蜘蛛判断函数
function isCrawler() {
	$agent= strtolower($_SERVER['HTTP_USER_AGENT']);
	if (!empty($agent)) {
		$spiderSite= array(
				//"tencenttraveler",
				"Baiduspider+",
				"BaiduGame",
				"Googlebot",
				"msnbot",
				"Sosospider+",
				"Sogou web spider",
				"ia_archiver",
				"Yahoo! Slurp",
				"YoudaoBot",
				"Yahoo Slurp",
				"MSNBot",
				"Java (Often spam bot)",
				"BaiDuSpider",
				"Voila",
				"Yandex bot",
				"BSpider",
				"twiceler",
				"Sogou Spider",
				"Speedy Spider",
				"Google AdSense",
				"Heritrix",
				"Python-urllib",
				"Alexa (IA Archiver)",
				"Ask",
				"Exabot",
				"Custo",
				"OutfoxBot/YodaoBot",
				"yacy",
				"SurveyBot",
				"legs",
				"lwp-trivial",
				"Nutch",
				"StackRambler",
				"The web archive (IA Archiver)",
				"Perl tool",
				"MJ12bot",
				"Netcraft",
				"MSIECrawler",
				"WGet tools",
				"larbin",
				"Fish search",
		);
		foreach($spiderSite as $val) {
			$str = strtolower($val);
			if (strpos($agent, $str) !==false) {
				return true;
			}
		}
	} else {
		return false;
	}
}

function sub_utf8($str,$len) {
	//中文完整截取函数utf8
	for($i=0;$i<$len;$i++) {
		$temp_str = substr($str,0,1);
		if(ord($temp_str)>127) {
			$i++;
			if($i<$len) {
				$new_str[] = substr($str,0,3);
				$str = substr($str,3);
			}
		}
		else {
			$new_str[] = substr($str,0,1);
			$str = substr($str,1);
		}
	}
	return join($new_str);
}