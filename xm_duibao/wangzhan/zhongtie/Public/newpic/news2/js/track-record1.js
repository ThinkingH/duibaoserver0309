$(function(){
	var requestIP = "http://192.168.30.16:8080/"; // 应用iP
	var articleId = $(".trackBox").attr('comid'); // 取文章id
	var articleType = $(".trackBox").attr('comType'); // 取文章type

	var reqIp ;
	var reqMac ;
	var type = articleType;
	var unitId = articleId;
    var newUrl = document.location.href; //当前页码url
    var parSource = document.referrer; //当前页面上一级页面的url
    var clientType = '';
    if(checkUsera()){
    	clientType = 1;
    }else{
    	clientType = 2;
    }
    getIPAndMac();
	

	function sendData(){
		$.ajax({  
		    type : "get",  
		    async:false,//控制异步还是同步模式，默认是异步模式
		    url : requestIP + "jydcms-admin/statistics/insertTrackComment",
		    dataType : "jsonp",//数据类型为jsonp
		    data:{
		        ip:reqIp,
		        mac:reqMac,
		        type:type,
		        unitId:unitId,
		        url:newUrl,
		        source:parSource,
		        clientType:clientType
		    },
		    jsonp: "jsonCallback",//服务端用于接收callback调用的function名的参数  
		    success : function(data){
		    	// alert(data);
		    },  
		    error:function(){
		        // alert('fail');
		    }
		});
	}

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


	function getIPAndMac() {//jsonp 跨域访问。获取当前请求用户的IP和Mac地址
	    $.ajax({  
            type : "get",  
            async:false,//控制异步还是同步模式，默认是异步模式
            url : requestIP + "jydcms-admin/net/getIPAndMac",
            dataType : "jsonp",//数据类型为jsonp     
            jsonp: "jsonCallback",//服务端用于接收callback调用的function名的参数  
            success : function(data){
            	/*alert(data.ip);
            	alert(data.mac);*/
	        	reqIp = data.ip;
	        	reqMac = data.mac;
				sendData();
            },  
            error:function(){
                alert('网络异常！');
            }
        });
	}

});
