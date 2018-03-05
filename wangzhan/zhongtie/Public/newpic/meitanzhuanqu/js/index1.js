/*hover显示*/
$('.phboxCon_pic').on('mouseenter',function(){
	$(this).children().animate({"top":"0"});
	$(this).find('h3 span').html("<img src='../image/icon5.png'/>");
});
$('.phboxCon_pic').on('mouseleave',function(){
	$(this).children().animate({"top":"120px"});
	$(this).find('h3 span').html("<img src='../image/icon6.png'/>");
})


/*无缝滚动*/
var time=1;
$('.lbtn').on('click',function(){
	var step1=125;

	$('.rollingTab a').first().animate("marginLeft","step*time"+"px");
	$('.rollingTab a').first().appendTo('.rollingTab');
	time++;
})
$('.rbtn').on('click',function(){
	var step=125;
	$('.rollingTab a').last().animate("marginRight","step*time"+"px");
	$('.rollingTab a').last().prependTo('.rollingTab');
	time++;
})



$('.delivery_l').on('click',function(){
	var step=356;

	$('.delivery_tab_box a').first().animate("marginLeft","step*time"+"px");
	$('.delivery_tab_box a').first().appendTo('.delivery_tab_box');
	time++;
})
$('.delivery_r').on('click',function(){
	var step=356;
	$('.delivery_tab_box a').last().animate("marginRight","step*time"+"px");
	$('.delivery_tab_box a').last().prependTo('.delivery_tab_box');
	time++;
})




/*tab切换*/
function tabs(targetBtn,showBox,eventTrigger,curClass){
	var tBtn = $(targetBtn).children(),
		tShow = $(targetBtn).parent().find(showBox);
	tBtn.bind(eventTrigger,function(){
		var tabIndex = $(this).index();
		$(this).addClass(curClass).siblings().removeClass(curClass);
		tShow.hide().eq(tabIndex).show();
	});
}

/*双重tab切换*/
$('.marketBox_list_li1 span').on('click',function(){
	var i=$(this).index();
	$(this).addClass('on').siblings().removeClass('on');
	$(".box1").children(".tabBox_con").eq(i).show().siblings().hide();
	var cc = $(this).attr('cc');  //获取catCode
	//console.log("cc===="+cc);
	$.ajax({
		  type: "POST",
		  url: "/web/coalStoreAction_indexClassPrice.html",
		  data:  { "prdClassPriceDO.catCode":cc,"prdClassPriceDO.perPageSize":7},
		  success: function(data) {
			  //console.log('data>>>>'+data);
			  $(".box1").empty().append(data);
		  },
		  dataType: "html"
		});	
});

$(document).on('click','.marketBox_list_li2 span',function(){
	var i=$(this).index();
	$(this).addClass('clickbtn').siblings().removeClass('clickbtn');
	$(this).parent().siblings(".tabBox").eq(i).show().siblings(".tabBox").hide();
});


/*图片放大效果*/
var $imgbig=$('.brandBox ul li a p');
var wid=$imgbig.children().width();
var hei=$imgbig.children().height();
$imgbig.on('mouseenter',function(){
	$(this).children("img").width(wid*1.18);
	$(this).children("img").height(hei*1.18);
});
$imgbig.on('mouseleave',function(){
	$(this).children("img").width(wid);
	$(this).children("img").height(hei);
});



/*锚点连接*/
$('.coal_main_ul ul li').on("click",function(){
	$(this).children('span').css({"display":"block"});
	$(this).siblings().children('span').css({"display":"none"});
	$(this).children('a').addClass('coal_down_a');
	$(this).siblings().children('a').removeClass('coal_down_a');
})


/*表格中 价格点击效果 (点击)*/
var $td_img=$('.coal_tab tr td.main_steam_tab_td em');
$td_img .next('p').hide();
$td_img.on("mouseenter",function(){
	$(this).next('p').show();
	$(this).parents().siblings().find('.main_steam_tab_td>p').hide();
});
$td_img.on("mouseleave",function(){
	$(this).next('p').hide();
	$(this).parents().siblings().find('.main_steam_tab_td>p').hide();
});



/*煤炭商城 更多与收起效果*/
var flag=true;
var flagss=true;
var $btn_more=$('.formcoal_steam_box>.p_steamone p.p3 a');
var $btn_mores=$('.formcoal_steam_box>.xx>.t_bb p.p3 a');
$btn_more.on("click",function(){
	if(flag==true){
	$(this).parents(".p_steam_box").height('auto');
	$(this).html("<<收起");
	var newHigh=$(this).parent().prev().outerHeight();
	$(this).parents(".p_steam_box").find('span').height(newHigh+'px').css('line-height',(newHigh+'px'));
	flag=false;
	}
	else{
		$(this).parents(".p_steam_box").height('40px').find('span').height('40px').css('line-height','40px');
		$(this).html("更多>>");
		flag=true;
	}
});
$btn_mores.on('click',function(){
	if($(this).attr('flag')){
		
		if($(this).attr('flagss')){
			var xxHei = $('.formcoal_steam_box>.xx').outerHeight();
			$('.formcoal_steam_box>.xx').css('height',(xxHei-40)+'px');
			$(this).removeAttr('flagss');
		}
		$(this).parents(".t_bb").height('40px').find('span').height('40px').css('line-height','40px');
		$(this).html("更多>>");
		$(this).removeAttr('flag');

	}
	else{

		$(this).parents(".t_bb").height('auto');
		$(this).html("<<收起");
		var newHigh=$(this).parent().prev().outerHeight();
		if(newHigh>40){
			var xxHei = $('.formcoal_steam_box>.xx').outerHeight();
			$('.formcoal_steam_box>.xx').css('height',(xxHei+40)+'px');
			$(this).attr('flagss','1');
		}
		$(this).parents(".t_bb").find('span').height(newHigh+'px').css('line-height',(newHigh+'px'));
		$(this).attr('flag','1');
	}
});

$('.f_tj_a p').on('mouseenter',function(){
	$(this).children().show();
});
$('.f_tj_a p').on('mouseleave',function(){
	$(this).children().hide();
});
/*煤炭商城 更多与收起效果 end*/


//	$('.tab_list').each(function(){
//		var tabNum = $(this).find(".tab_list_box table").length;
//		$(this).find(".tab_list_box").css("width",tabNum*1100+"px");
//		$(this).find(".tab_btn").empty().html(function(){
//			for(var i=0; i<tabNum; i++){
//				$(this).append("<em></em>");
//			}
//		});
//		$(this).find(".tab_btn em:first").addClass("orangBtn1");
//	});
//var s=0;
//var timer1=setInterval(function(){	
//	$('.tab_list').each(function(){	
//		var tabNum = $(this).find(".tab_list_box table").length;
//		if(s==tabNum){
//			s=0;
//		}
//		var ft = -1100*s;		
//		$(this).find(".tab_btn em").eq(s).addClass('orangBtn1').siblings().removeClass('orangBtn1');
//		$(this).find(".tab_list_box").animate({
//			marginLeft: ft+"px"
//		});
//	})
//	s++;
//},5000);
//$(document).on("click",".tab_btn em",function(){
//	$(".tab_list_box").stop(true, true);
//	setTimeout("timer",5000);
//	$(this).addClass('orangBtn1').siblings().removeClass('orangBtn1');
//	var emNum = $(this).index();
//	var ft = -1100*emNum;		
//		$(this).parent().siblings(".tab_list_box").animate({
//			marginLeft: ft+"px"
//		});	
//	s = $(this).index();	
//});


$('.freeBoxCon').each(function(){
		var divNum = $(this).find(".freebox_dl").length;
		$(this).css("width",divNum*1100+"px");
		$(this).find("p").empty().html(function(){
			for(var i=0; i<divNum; i++){
				$(this).append("<span></span>");
			}
			
		});
		$(this).find("span:first").addClass("orangBtn2");
	});
var i=0;
var timer=setInterval(function(){
	$('.freeBox_con').each(function(){	
		var divNum = $(this).find(".freebox_dl").length;
		if(i==divNum){
			i=0;
		}
		var ft = -1100*i;		
		$(this).find("p span").eq(i).addClass('orangBtn2').siblings().removeClass('orangBtn2');
		$(this).find(".freeBoxCon").animate({
			marginLeft: ft+"px"
		});
	})
	i++;
},5000);
$(document).on("click",".freeBox_con p span",function(){
	$(".freeBoxCon").stop(true, true);
	setTimeout("timer",5000);
	$(this).addClass('orangBtn2').siblings().removeClass('orangBtn2');
	var spanNum = $(this).index();
	var ft = -1100*spanNum;		
		$(this).parent().siblings(".freeBoxCon").animate({
			marginLeft: ft+"px"
		});	
	i = $(this).index();	
});


