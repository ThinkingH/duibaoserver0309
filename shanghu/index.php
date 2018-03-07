<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/jquery.fullpage.css">
<!-- <link rel="stylesheet" href="css/redwallet.css"> -->
<style type="text/css">
*{padding:0px;margin:0px;}
        .pop {  display: none;  width: 200px; height:300px;
	           min-height: 470px;  max-height: 750px;  
	           position: absolute;  top: 10%;  left: -140px;  bottom: 0;  right: 0; 
	           margin: auto;  padding: 25px;  z-index: 130; 
         }
        .pop2 {  display: none;  width: 200px; height:300px;
	           min-height: 470px;  max-height: 750px;  
	           position: absolute;  top: -10%;  left: -250px;  bottom: 0;  right: 0; 
	           margin: auto;  padding: 25px;  z-index: 130; 
	          
	        }
        .popimg{position:absolute;top:27%;  left:113px;}
        .pop-top span{  float: right;  cursor: pointer;  font-weight: bold; display:black;position:relative; top:30px; right:15px;}
        .pop-content-left{  float: left;  }
        .bgPop{  display: none;  position: absolute;  z-index: 129;  left: 0;  top: 0;  width: 100%;  height: 100%;  background: rgba(0,0,0,.2);  }
       .btnstyle {width:200px;height:50px; background-color: white;  color: #000;  cursor:pointer;
                 position:absolute;top:70%;  left:125px; font-size:"20px"}

</style>

<script src="js/jquery-1.10.1.min.js"></script>
<script src="js/jquery.fullpage.min.js"></script>
<script>$(function(){$('.tel_bj').next().css('cursor','pointer').click(function(){window.open("")
})
 
$(".an_btn1").click(function(){
	var system ={win:true,mac:true,xll:true,ipad:true };
	var p =navigator.platform;system.win =p.indexOf("Win") ==0;system.mac =p.indexOf("Mac") ==0;system.x11 =(p =="X11") ||(p.indexOf("Linux") ==0);
	system.ipad =(navigator.userAgent.match(/iPad/i) !=null)?true:false;
	if (system.win ||system.mac ||system.xll||system.ipad) {window.location.href="http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php";} else {alert("请您用pc浏览器访问登录")
} 
});

	$(".an_btn2").click(function(){window.location.href="./register/register.php";});
	$(".an_btn2").hover(function(){$(this).css('cursor','pointer')
})
});window.onload =function(){var screenWd =$(window).width();$('#dowebok').fullpage({continuousVertical:true,afterLoad:function(anchorLink,index){


},onLeave:function(index,direction){

}
});}
</script>

<script type="text/javascript">
$(document).ready(function () {
	
    $('.pop-close').click(function () {
    	//页面刷新
    	window.location.reload();
        $('.bgPop,.pop').hide();
        $('.bgPop,.pop2').hide();
    });
    $('.click_pop').click(function () {
        $('.bgPop,.pop').show();
    });
    
    $('.click_pop1').click(function(){
    	/*  $("img").click(function(){ 
    		 $(this).hide();  
    	}); */
    	 $(".pop .pop-content-left img").hide();  
    	 $(".pop-foot img").hide();  
    	$('.bgPop,.pop2').show();
    });
    
   
})


</script>



</head>
<body>
	<div id="dowebok" style="background-image:url(./images/background.jpg)";>
	<div class="section section1">
	<div class="top">
	<div class="top_left"><img src="images/icon.png" width="60" height="60"></div>
	<div class="top_right">
	<div class="lianx pull-left"><!-- <span class="tel_bj"></span><span>联系我们</span> --></div>
	<span class="an_btn233"></span><span class="an_btn1">登录</span></div>
	</div>
	 <div class="bottomd"><span class="tel_bj">400-6501208</span></div> 
	<div class="col-xs-12 col-sm-12">
	<div class="logo_pc"><img style="max-width:80%;" src="images/11.png"></div>
	<div class="logo_m"><img style="max-width:80%;" src="images/11.png"></div>
	<p class="text-center applyfw">
	
	<button type="button" class="btn btn-default  click_pop"  >开户红包</button>
	</div>
	</div>
	</div>

	
<!--遮罩层-->
<div class="bgPop"></div>
<!--弹出框-->
<div class="pop">
    <div class="pop-top">
        <span class="pop-close"><img src="images/hongbao/close1.png" style="position:absolute;top:-20px;left:130px;width:20px;height:20px"></span>
    </div>
    
    <div class="pop-content-left">
            <img src="images/hongbao/h1111.png" alt="" class="teathumb" height="300px" width="300px" >
    </div>
    
    <div class="pop-foot">
       <a href="javascript:void(0)" class="click_pop1">
            <img src="images/hongbao/h22.png" alt="" class="popimg" width="120px" height="120px">
       </a>
    </div>
</div>


<!-- 第二个弹出框 -->
<div class="pop2" >
    
    <div class="pop-content-left">
       <img src="images/hongbao/h33.png" alt="" class="teathumb" height="400px" width="400px">
    </div>
    
    <div class="pop-foot">
     <a href="javascript:void(0)">
      <!--  <input type="button" value="报名" class="pop-ok"/> -->
      <button type="button" class="btnstyle" onclick="window.location.href='./register/register.php'" style="font-size: 20px;color:red" >立即开户</button>
     </a>
      
    </div>
   
</div>
	
	
	
</body>
</html>