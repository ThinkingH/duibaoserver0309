var XYXK = XYXK || {};

XYXK.common = (function($) {
	var common = {
		init: function() {
			this.render();
			
		},
		render: function() {
			this.initializeEvent();
		},
		initializeEvent: function() {
			$('.gameinfo li,.cooList li').on('mouseenter',this.addAlpha);
			$('.gameinfo li,.cooList li').on('mouseleave',this.removeAlpha);
		},
		addAlpha: function() {
			$(this).addClass('alpha');
		},
		removeAlpha: function() {
			$(this).removeClass('alpha');
		}
	};
	return common;
})(jQuery);

$( '#slider' ).lateralSlider( {
captionPadding: '0',
captionHeight: 45
} );
$(function() {
	XYXK.common.init();
	$.imgPos = function() {
		var w = $(window).width();
		var imgW = (2560 - w) /2;
		$('#slider').css('marginLeft','-' + imgW + 'px');
		$('.circle').eq(0).css('marginLeft','-85px');
		$('.circle').eq(1).css('marginLeft','-25px');
		$('.circle').eq(2).css('marginLeft','45px');
		$('.circle').eq(3).css('marginLeft','105px');
	};
	$.imgPos();
	$(window).resize(function() {
		$.imgPos();
	});	
});