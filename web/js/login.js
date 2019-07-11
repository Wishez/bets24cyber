$(document).ready(function(){
	var openA = false;

	$('.in-auth').click(function(){
		if(slideout.isOpen()){
			slideout.close();
		}
		if(openA){
			$('.black-popup .popup-login').animate({top: 0, opacity: 0}, 300, function(){
					$('#registr').hide();
					$('.black-popup').show();
					$('#login').show().animate({top: 80, opacity: 1}, 300);
					openA = true;
			});
			
		}
			$('.black-popup').show();
			$('#login').show();
			$('#login').animate({top: 80, opacity: 1}, 300);
			openA = true;
	});

	$('.reg-auth').click(function(){
		if(slideout.isOpen()){
			slideout.close();
		}
		if(openA){
			$('.black-popup .popup-login').animate({top: 0, opacity: 0}, 300, function(){
					$('#login').hide();
					$('.black-popup').show();
					$('#registr').animate({top: 80, opacity: 1}, 300);
					openA = true;
			});
			
		}
			$('.black-popup').show();
			$('#registr').css('display', 'inline-block');
			$('#registr').animate({top: 80, opacity: 1}, 300);
			openA = true;
	});

	$('.black-popup').click(function(event){
		if(openA){
			if ($(event.target).closest(".popup-login").length) return;
			$('.black-popup .popup-login').animate({top: 0, opacity: 0}, 300, function(){
				$('.black-popup').hide();
				openA = false;
			});
			
		}
	});

	function check($el){
		var data = $.trim($el.val());
		if(data == ""){
			$el.css('border', '1px solid #f13c3c');
			return false;
		}else{
			$el.css('border', '1px solid #30343a');
			return true;
		}
	}
	$('#enter').click(function(){
		var e = check($("#login-form #email-i"));
		var p = check($("#login-form #password-i"));
		if(e && p){
			$.post( "/auth", $("#login-form").serialize(), function(data){
				if(data == 1){
					location.reload();
				}else{
					$("#login").find(".error-reg").text(data);
				}
		});
		}else{
			$("#login").find(".error-reg").text('Заполните все поля');
		}

	});

	$('#enter_reg').click(function(){
		var ver = true;
		$('#reg-form input').each(function(){
			var empty = check($(this));
			if(!empty){
				ver = false;
			}
		});

		if(ver){
			$.post("/reg", $("#reg-form").serialize(), function(data){
				if(data == 1){
					location.reload();
					//$("#registr").find(".error-reg").text("Регистрация прошла успешно").css('color', '#51e06b;');
				}else{
					$("#registr").find(".error-reg").text(data);
				}
		});
		}else{
			$("#registr").find(".error-reg").text('Заполните все поля');
		}
	});


});