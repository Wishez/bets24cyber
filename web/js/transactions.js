$(document).ready(function(){
	function roundPlus(x, n) { 
		var dec = Math.pow(10, 10);
		return parseFloat((Math.ceil(x*dec)/dec).toFixed(n));
	}

	$("#fund_field").autocomplete({
	  source: "fund-name", // url-адрес
	  minLength: 1 // минимальное количество для совершения запроса
	});
	$("#user_field").autocomplete({
	  source: "user-name", // url-адрес
	  minLength: 1 // минимальное количество для совершения запроса
	});

	$('.currency_list .currency').click(function(){
		var amount_value = $('#amount').val().trim();
		var commison_value = $('#commison').val().trim();

		var currency_last = parseFloat($('.currency[selected]').data('currency'));
		var currency_new = parseFloat($(this).data('currency'));

		$('#amount').val(roundPlus(amount_value * currency_last / currency_new, 20));
		if(!(new RegExp('%').test(commison_value))){
			$('#commison').val(roundPlus(commison_value * currency_last / currency_new, 20));
		}
		

		$('.currency[selected]').removeAttr('selected');
		$(this).attr('selected', '');

	});
});