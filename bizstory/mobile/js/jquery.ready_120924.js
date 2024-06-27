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
/*
	// Ajax More1
		$.ajaxSetup({cache:false}); //캐시 false
		$(".more").click(function(){
			var send      = "./process/ajax_list.php";
			var more_type = $(".more").attr("alt")
			var more_size = $("#moresize").val();
			var more_num  = $("#morenum").val();
			$("#moretype").val(more_type);
			var pars      = $('#moreform').serialize();

			$.post(send, pars, function(xdata){
				$('#loading').css({
					position:'absolute',
					left: ($(window).width() - $('#loading').outerWidth())/2,
					top: ($(window).height() - $('#loading').outerHeight())/2
				});
				$("#loading").fadeIn(300);
				$("#loading").fadeOut(1000);
				$("#homebox").fadeOut(1200);
				$(".homebox").fadeIn(300);
				$.ajaxSetup({cache:true}); //캐시 false
				$("ul.bbs").append(xdata);
				$("ul.bbs").find(".ajax" + more_num).each(function(){
					$(this).fadeIn("slow");
				});
				$("#morenum").val(parseInt(more_num) + 1);
			});
		});

	// Ajax More2
		$.ajaxSetup({cache:false}); //캐시 false
		$(".more2").click(function(){
			var send      = "./process/ajax_list.php";
			var more_type = $(".more2").attr("alt")
			var more_size = $("#moresize").val();
			var more_num  = $("#morenum").val();
			$("#moretype").val(more_type);
			var pars      = $('#moreform').serialize();

			$.post(send, pars, function(xdata){
				$('#loading').css({
					position:'absolute',
					left: ($(window).width() - $('#loading').outerWidth())/2,
					top: ($(window).height() - $('#loading').outerHeight())/2
				});
				$("#loading").fadeIn(300);
				$("#loading").fadeOut(1000);
				$("#homebox").fadeOut(1200);
				$(".homebox").fadeIn(300);
				$.ajaxSetup({cache:true}); //캐시 false
				$("ul.memo").append(xdata);
				$("ul.memo").find(".ajax" + more_num).each(function(){
					$(this).fadeIn("slow");
				});
				$("#morenum").val(parseInt(more_num) + 1);
			});
		});

		*/
	});
//]]>