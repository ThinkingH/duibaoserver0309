
$(function(){
	$("input[name=pass]").on("keydown",function(){
		var val = $(this).val();
		var strength = checkPwdStrength(val);
		if(strength == 'M'){
			$(".js-password-qd").html('<img src="/passport/web/images/anquan2.gif">');
		}
		else if(strength == 'H'){
			$(".js-password-qd").html('<img src="/passport/web/images/anquan3.gif">');
		}
		else{
			$(".js-password-qd").html('<img src="/passport/web/images/anquan1.gif">');
		}
	});
});