	$('#update').click(function(){
		 $.ajax({
            url: '/update-league',
            beforeSend: function(){
            	$('#update').text('Обновление..').attr('disabled', true);

            },
            success: function (data) {
               $('#update').text('Обновить лиги').attr('disabled', false);
               location.reload();
            }
        });

	});