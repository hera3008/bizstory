//<![CDATA[
	var myScroll;
	function loaded() {
		myScroll = new iScroll('wrapper', {
			useTransform: false,
			onBeforeScrollStart: function (e) {
				var target = e.target;
				while (target.nodeType != 1) target = target.parentNode;

				if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
					e.preventDefault();
			}
		});
	}
	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	document.addEventListener('DOMContentLoaded', loaded, false);

	$(document).ready(function() {

	// Loading
		$('#loading').css({
			position:'absolute',
			left: ($(window).width() - $('#loading').outerWidth())/2,
			top: ($(window).height() - $('#loading').outerHeight())/2
		});
		$("#loading").fadeIn(300);
		$("#loading").fadeOut(1000);
		$("#homebox").fadeOut(1200);
		$(".homebox").fadeIn(300);

	// Toggle
		$(".toggle_container").hide();
		$(".trigger").click(function(){
			$(this).toggleClass("active").next().slideToggle("fast");
			return false;
		});

	// First Object
		$(".navi a:first").css({marginLeft: '0'});
	// Last Object
		$(".navi a:last").css({marginRight: '0'});

	// Show barmenu
		$(".sub .around_icon").hide();
		$(".menu").click(function(){
			$(".sub .around_icon").slideToggle("fast");
			return false;
		});
	});
//]]>