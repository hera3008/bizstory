// Notice Swipe Event & auto Scrolling
	var notcScroll = function(){
		var $lists = $("section#notice").find("ol > li");
		var liHeight = $lists.outerHeight();
		$lists
			.each(function(index){
				$(this).animate({
					top : $(this).position().top - liHeight + 'px'
				}, 400, function(){
					if(index == 0)
						$(this).filter(":first-child").insertAfter($lists.filter(":last-child")).css({top: liHeight + 'px'});
				});
			});	
	};
	// initialize List Absolute Position Attribute
	$("section#notice").find("ol > li").each(function(index){
		$(this).css({
			position : 'absolute',
			top : index * $(this).outerHeight()  + "px"
		});
	});
	window.setInterval("notcScroll()", 3000);


// main jCarousel  메인에만 노출 // 처음시도했던 visual :3개씩만 나오지않아서 추후 새론걸로 작업 후 삭제
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
        auto: 2,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
});