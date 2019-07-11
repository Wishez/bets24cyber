$('.update').click(function(){
		var id = $(this).attr('update');
      $.ajax({
         url: "/league/upd",
         type: "GET",
         data: {'id': id},
         dataType: "html",
         success: function(data){
         	console.log(1);
         }
      
      }); 
});


function drag(){
 $('.drag-item').draggable({
   drag: function(event, ui){

         if($('#drag-hover').attr('league_id') != $(this).parent('tr').attr('league_id')){
            $('#drag-hover').attr('league_id', $(this).parent('tr').attr('league_id'));
            $('#drag-hover table').empty();
            var quals = $(this).parent('tr').nextAll('tr');
            var level = 1;
            if($(this).parent('tr').hasClass('quals-close-block')){
               level = 2;
            }else if($(this).parent('tr').hasClass('qual-new')){
               level = 3;
            }
            $(this).parent('tr').appendTo('#drag-hover table');

            //$('#drag-hover table tr').find('.empty-td').remove();
            var level_one = 0;
            var level_two = 0;
            //console.log($(this).parent('tr').nextAll('tr'));
            quals.each(function(){
               if(level < 3){
                  if((level == 1 && $(this).hasClass('qual')) || (level == 2 && $(this).hasClass('qual-new'))){
                     if($(this).hasClass('quals-close-block')){
                        level_one = 1;
                     }
                     if($(this).hasClass('qual-new')){
                        level_two = 1;
                     }
                     //$(this).find('.empty-td').remove();

                     $(this).appendTo('#drag-hover table');
                  }else return false;
            }
            });
            $('#drag-hover').attr('level', level_one + level_two + 1);
            console.log($('#drag-hover').attr('level'));
            $('#drag-hover').show();
            $(this).parent('tr').remove();
      }
      //$('#x').show();


      $('#drag-hover').css({'left': event.pageX, 'top': event.pageY});
         $('.drag-table').each(function(){
            var block = $(this);
            
            //console.log(block.offset(), $('#drag-hover').offset());

            if($('#drag-hover').offset().top > block.offset().top && $('#drag-hover').offset().top <= block.offset().top + block.height()){
               var levels = $('#drag-hover').attr('level');
               var level = block.attr('level');
               if(level == 1){
                  if(levels < 3){
                      block.css('border-bottom', '2px solid #2590b9');
                   }else{
                     block.css('border-bottom', '2px solid red'); 
                   }
               }else if(level == 2){
                  if(levels < 2){
                      block.css('border-bottom', '2px solid orange');
                   }else{
                     block.css('border-bottom', '2px solid red'); 
                   }
               }else{
                  block.css('border-bottom', '2px solid red'); 
               }
            }else{
               block.css('border-bottom', 'none');
            }

         });
   },
   stop: function(event, ui) {
      var b = $(this);
      var data = [];
      var find = true;
         $('.drag-table').each(function(){
            var block = $(this);
            if($('#drag-hover').offset().top > block.offset().top && $('#drag-hover').offset().top <= block.offset().top + block.height()){
               var levels = $('#drag-hover').attr('level');
               var level = block.attr('level');
               var id = 0;
               if(level == 1){
                  if(levels < 3){
                     find = false;
                     id = $('#drag-hover tr').eq(0).attr('league_id');
                      var d_level = $('#drag-hover tr').eq(0).attr('level');
                      var x_level = parseInt(level, 10) + 1;
                      $('#drag-hover tr').each(function(){
                        if($(this).attr('level') > d_level){
                           d_level = $(this).attr('level');
                           x_level++;
                        }
                        if(x_level == 2){
                           $(this).removeClass('qual-new');
                           $(this).addClass('quals-close-block');
                           $(this).find('.empty-td').remove();
                           $(this).find('td').eq(1).attr('colspan', 2);
                           $(this).attr('level', 2);
                           $(this).find('td').eq(0).removeClass().addClass('drag-item');
                           $(this).prepend('<td class="empty-td"></td>');
                        }else if(x_level == 3){
                           $(this).addClass('qual-new');
                           $(this).removeClass('quals-close-block');
                           $(this).find('.empty-td').remove();
                           $(this).find('td').eq(1).attr('colspan', 1);
                           $(this).attr('level', 3);
                           $(this).find('td').eq(0).removeClass().addClass('drag-item');
                           $(this).prepend('<td class="empty-td"></td>');
                           $(this).prepend('<td class="empty-td"></td>');
                        }

                      });
                   }else{
                     find = true;
                   }
               }else if(level == 2){
                  if(levels < 2){
                         id = $('#drag-hover tr').eq(0).attr('league_id');
                        find = false;
                        $('#drag-hover tr').addClass('qual-new');
                        $('#drag-hover tr').attr('level', 3);
                        $('#drag-hover tr').removeClass('quals-close-block');
                        $('#drag-hover tr').find('.empty-td').remove();
                        $('#drag-hover tr').find('td').eq(1).attr('colspan', 1);
                        $('#drag-hover tr').find('td').eq(0).removeClass().addClass('drag-item');
                        $('#drag-hover tr').prepend('<td class="empty-td"></td>');
                        $('#drag-hover tr').prepend('<td class="empty-td"></td>');
                   }else{
                     find = true;
                   }
               }else{
                  find = true;
               }
               if(!find){
                  $('#drag-hover tbody tr').addClass('drag-table');
                  block.after($('#drag-hover tbody').html());
                  data.push({'id': id, 'main': block.attr('league_id'), 'event': 1});
                  
               }
               return false;
            }

               // $('#drag-hover tbody tr').addClass('drag-table');
               // block.after($('#drag-hover tbody').html());
               // block.css('border-top', 'none');
               // find = true;
            });
         if(find){
             var d_level = parseInt($('#drag-hover tr').eq(0).attr('level'), 10);
             var x_level = 1;

             data.push({'id': $('#drag-hover tr').eq(0).attr('league_id'), 'main':null, 'event': 0});
            $('#drag-hover tr').each(function(){
               if($(this).attr('level') > d_level){
                  d_level = $(this).attr('level');
                  x_level++;
               }else if($(this).attr('level') < d_level){
                  d_level = $(this).attr('level');
                  x_level--;

               }
               if(x_level == 1){
                  $(this).removeClass('qual-new');
                  $(this).removeClass('quals-close-block');
                  $(this).find('.empty-td').remove();
                  $(this).find('td').eq(1).attr('colspan', 3);
                  $(this).attr('level', 1);
                  $(this).find('td').eq(0).removeClass().addClass('drag-item');            
               }else if(x_level == 2){
                     $(this).removeClass('qual-new');
                     $(this).addClass('quals-close-block');
                     $(this).find('.empty-td').remove();
                     $(this).find('td').eq(1).attr('colspan', 2);
                     $(this).attr('level', 2);
                     $(this).find('td').eq(0).removeClass().addClass('drag-item');
                     $(this).prepend('<td class="empty-td"></td>');                  
               }else if(x_level == 3){
                     $(this).addClass('qual-new');
                     $(this).removeClass('quals-close-block');
                     $(this).find('.empty-td').remove();
                     $(this).find('td').eq(1).attr('colspan', 1);
                     $(this).attr('level', 3);
                     $(this).find('td').eq(0).removeClass().addClass('drag-item');
                     $(this).prepend('<td class="empty-td"></td>');
                     $(this).prepend('<td class="empty-td"></td>');
               }
            });
            $('#drag-hover tbody tr').addClass('drag-table');
            $('.x-table tbody tr').eq(0).after($('#drag-hover tbody').html());
         }

         // //console.log($('#drag-hover tbody').html());
         $('#drag-hover').attr('league_id', '');
         $('#drag-hover').hide();
         $('tr').css('border-bottom', 'none');
         drag();
         console.log(data);
      $.ajax({
            url: "/league/save-drop",
            type: "GET",
            data: {'data': data},
            dataType: "html",
            success: function(data){
               console.log('ok');
            }
         });
   }, helper: $('tr'), scroll: false
 });
}
drag();
