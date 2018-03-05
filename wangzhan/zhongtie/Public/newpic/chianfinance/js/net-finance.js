$(function(){
	
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
	
});