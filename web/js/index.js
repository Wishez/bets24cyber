$(document).ready(function(){
  var start = true;
  $('.slide_right').click(function(){
    if(start){
    start = false;
    var a = $(this).siblings('.slider-over').find('.slider-block');
 a.animate({
  "left": "-210px"
 }, 300, function(){
  start = true;
a.find('.news-block').first().clone().appendTo(a);
a.find('.news-block').first().remove();
a.css("left", "0px");

});
}
 });

    $('.slide_left').click(function(){
    if(start){
    start = false;
    var a = $(this).siblings('.slider-over').find('.slider-block');
    a.find('.news-block').last().clone().prependTo(a);
    a.find('.news-block').last().remove();
    a.css("left", "-210px");

 a.animate({
  "left": "0px"
 }, 300, function(){
  start = true;

});
}
 });

var open = [0, 0, 0, 0];
$('.active-button').click(function(){
    var a = $(this).closest('.stream').find('.matches');
    var type = open[a.data('lm')];
    if(type == 0){
      a.find('.match-last').hide();
      a.find('.match-actives').show();
    }else{
      a.find('.league-actives').show();
      a.find('.league-last').hide();      
    }
});
$('.last-button').click(function(){
    var a = $(this).closest('.stream').find('.matches');
    var type = open[a.data('lm')];
    if(type == 0){
      a.find('.league').hide();
      a.find('.match-actives').hide();
      a.find('.match-last').show();
    }else{
      a.find('.match').hide();
      a.find('.league-actives').hide();
      a.find('.league-last').show();
    }
});


$('.league_ch').click(function(){
      $(this).closest('.header-index').find('.line_ch').css("margin-left", "calc(50% + 1px)");
      var a = $(this).closest('.stream').find('.matches');
      open[a.data('lm')] = 1;
      a.find('.match').hide();
      a.find('.league').hide();
      a.find('.league-actives').show();

 });
$('.match_ch').click(function(){
    $(this).closest('.header-index').find('.line_ch').css("margin-left", "0px");
    var a = $(this).closest('.stream').find('.matches');
    open[a.data('lm')] = 0;
    a.find('.match').hide();
    a.find('.league').hide();
    a.find('.match-actives').show();
 });
});