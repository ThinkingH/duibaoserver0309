function browserRedirect() { 

var sUserAgent= navigator.userAgent.toLowerCase(); 

var bIsIpad= sUserAgent.match(/ipad/i) == "ipad"; 

var bIsIphoneOs= sUserAgent.match(/iphone os/i) == "iphone os"; 

var bIsMidp= sUserAgent.match(/midp/i) == "midp"; 

var bIsUc7= sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4"; 

var bIsUc= sUserAgent.match(/ucweb/i) == "ucweb"; 

var bIsAndroid= sUserAgent.match(/android/i) == "android"; 

var bIsCE= sUserAgent.match(/windows ce/i) == "windows ce"; 

var bIsWM= sUserAgent.match(/windows mobile/i) == "windows mobile"; 

var url = window.location.href;
var url_m = '';
//判断是移动端
if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) { 

	if(!url.indexOf("m.315.com.cn")){
		url_m=url.replace("news.315.com.cn","m.315.com.cn").replace("agri.315.com.cn","m.315.com.cn").replace("www.315.com.cn","m.315.com.cn");
		window.location.href=url_m;
	}
 
}
}
browserRedirect();

window.onload=function(){
//样式控制
	if(!checkUsera()){
    	// alert('移动端');
    	checkloadjscssfile('width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0','meta');
    	removejscssfile('/dzadmin/adminsys/css/news/global.css','css');
    	removejscssfile('/dzadmin/adminsys/css/news/list.css','css');
    	checkloadjscssfile('../../../../css/newsphonestyle.css','css');
    }else{
    	// alert('PC端');
    	if(document.getElementById('forPhone')){
    		var metaId = document.getElementById('forPhone');
    		metaId.parentNode.removeChild(metaId);
    	}
    	removejscssfile('css/newsphonestyle.css','css');
    	checkloadjscssfile('/dzadmin/adminsys/css/news/global.css','css');
    	checkloadjscssfile('/dzadmin/adminsys/css/news/list.css','css');
    }
}

//临时载入的文件名 
var filesadded="";

//判断是移动端还是PC端
function checkUsera(){
	var userAgentInfo = navigator.userAgent;
    var Agents = ["Android", "iPhone",
                "SymbianOS", "Windows Phone",
                "iPad", "iPod"];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}
//动态加载css文件
function loadjscssfile(filename, filetype){
//如果文件类型为 .css ,则创建 link 标签，并设置相应属性 
if (filetype=="css"){ 
	var fileref=document.createElement("link");
	fileref.setAttribute("rel", "stylesheet");
	fileref.setAttribute("type", "text/css");
	fileref.setAttribute("href", filename);
}else if(filetype=="meta"){
	var fileref=document.createElement("meta");
	fileref.setAttribute("content",filename);
	fileref.setAttribute("name","viewport");
	fileref.setAttribute("id","forPhone");
}
if (typeof fileref!="undefined"){
	document.getElementsByTagName("head")[0].appendChild(fileref); 
	}
}
//文件判断，防止重复载入
function checkloadjscssfile(filename, filetype){ 
	if (filesadded.indexOf("["+filename+"]")==-1){ 
		loadjscssfile(filename, filetype); 
		//把 [filename] 存入 filesadded 
		filesadded+="["+filename+"]"; 
	} 
	else{ 
		alert("file already added!"); 
	}
}
//用 DOM removeChild 删除一个 “script” 或者 ”link” 元素
function removejscssfile(filename, filetype){ 
//判断文件类型 
var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none"; 
//判断文件名 
var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none"; 
var allsuspects=document.getElementsByTagName(targetelement); 
//遍历元素， 并删除匹配的元素 
for (var i=allsuspects.length; i>=0; i--){ 
	if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1) 
		allsuspects[i].parentNode.removeChild(allsuspects[i]); 
	}
}
