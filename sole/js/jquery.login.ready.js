	$(document).ready(function() {
	// Animate
		$(".login_logo", this).stop().animate({top:'65px'},{queue:false,duration:800});
		$(".loginform", this).stop().animate({top:'280px'},{queue:false,duration:800});
		$(".login_capture", this).stop().animate({right:'0px'},{queue:false,duration:800});
		$(".login_app", this).stop().animate({top:'140px'},{queue:false,duration:800});

		$(".gall-img", this).stop().animate({top:'40px'},{queue:false,duration:800});

		$("#footer").html('<address><em>Copyright &copy;</em><strong>BIZSTORY</strong><span>All Rights Reserved.</span></address>');
		$(".engName").html('BIZSTORY');
		$(".hanName").html('비즈스토리 총판');

	// SlideShow
		$('#ad_banner').cycle({
			fx: 'scrollUp',
			speed: 500,
			timeout:3000,
			pager: '#ad_counter'
		});
	});