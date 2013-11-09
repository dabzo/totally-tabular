
<script>
(function($){ // Closure to avoid jQuery conflicts
$(window).load(function() { //start after HTML, images have loaded

$('.tab-widget .tab-content').hide(); //hide all items

var InfiniteRotator =
{
  init: function()
  {
    var initialFadeIn = 0; //initial fade-in time (in milliseconds)
    var itemInterval = 8000; //interval between items (in milliseconds)
    var fadeTime = 500; //cross-fade time (in milliseconds)

    var numberOfItems = $('.tab-widget').length; //count number of items
    var currentItem = 0; //set current item
	var verticalPadding = 120; //a little wiggle-room

	var titleWidth = (100 / numberOfItems - 4) + '%'; //container-width minus 2% padding on either side
	$('.tab-title').css('width', titleWidth);
	
    //show first item
    $('.tab-widget .tab-title').eq(currentItem).toggleClass('current-item');
    $('#tab-container').height($('.tab-widget .tab-content').eq(currentItem).height() + verticalPadding);        
    $('.tab-widget .tab-content').eq(currentItem).fadeIn(fadeTime);

    //loop through the items
    var infiniteLoop = setInterval(function(){
      $('.tab-widget .tab-content').eq(currentItem).hide();
      
      if(currentItem == numberOfItems -1){
        currentItem = 0;
      }else{
        currentItem++;
      }
      
      $('.tab-widget .tab-title').removeClass('current-item');
      $('.tab-widget .tab-title').eq(currentItem).toggleClass('current-item');
      $('#tab-container').height($('.tab-widget .tab-content').eq(currentItem).height() + verticalPadding).fadeIn(fadeTime);        
      $('.tab-widget .tab-content').eq(currentItem).fadeIn(fadeTime);

      $('.tab-title').click(function(){
        $('.tab-widget .tab-title').removeClass('current-item');
        $(this).toggleClass('current-item');
        $('.tab-widget .tab-content').fadeOut(fadeTime);
        $('#tab-container').height($(this).next('.tab-content').height() + verticalPadding).fadeIn(fadeTime);
        $(this).next('.tab-content').fadeIn(fadeTime);
        clearInterval(infiniteLoop);
      }); 
    }, itemInterval);
  }
};

InfiniteRotator.init();
});     
})(jQuery);
</script>