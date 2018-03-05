$(function(){
	
	//客户端二维码
	$('.downbox').on('mouseenter','a',function(){
		$('.d-ewm').stop().animate({'right':'-120px'},500);
	});
	$('.downbox').on('mouseleave','a',function(){
		$('.d-ewm').stop().animate({'right':'-6000px'},500);
	});

	//调用轮播函数
	focus.init('#list','#next','#prev','#buttons','#list,#buttons');

	//日期函数调用
	GetCurrentDateTime('day','week');
	nongLi('lunar');//农历

	//精品推荐效果
	$('.boutique').on('mouseenter','.item',function(){
		$(this).find('.layeron').stop().animate({'top':'18px'},500);
		$(this).find('.layer-cons').stop().animate({'top':'31px'},500);
		$(this).find('.biao').animate({'top':'-25px'},100);
	});
	$('.boutique').on('mouseleave','.item',function(){
		$(this).find('.layeron,.layer-cons').stop().animate({'top':'120px'},500);
		$(this).find('.biao').animate({'top':'-10px'},100);
	});

	//产业链金融效果
	$('.f_cyjr_up').on('mouseenter','.f_cyjr_c',function(){
		$(this).find('.f_layeron').stop().animate({'top':'0px'},500);
		$(this).find('.f_layer-cons').stop().animate({'top':'0px'},500);
		$(this).find('.f_cyjr_o').fadeOut();
	});
	$('.f_cyjr_up').on('mouseleave','.f_cyjr_c',function(){
		$(this).find('.f_layeron,.f_layer-cons').stop().animate({'top':'261px'},500);
		$(this).find('.f_cyjr_o').fadeIn();
	});
	var leftHei = parseInt($('.f_cyjr_hz').outerHeight());
	$('.f_cyjr_sq').css('height',leftHei+'px');

	//煤炭商城切换
	$(".marketBox_list_li3").on('click','span',function(){
			$(this).addClass("active").siblings().removeClass("active");
			$('.marketBox_list .tab_list:eq('+$(this).index()+')').removeAttr("style").show().siblings(".tab_list").hide();
		});
	$(".tab_list").slide({titCell:".tab_btn ul",mainCell:".tab_list_box ul",autoPage:true,delayTime:500,effect:"left",autoPlay:false,vis:1,trigger:"click"});

	//交易报价tab切换
	tabChange('.tradeprice-tabs','active','.infors');
	
});