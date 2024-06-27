
	$(document).ready(function() {
	// Animate
		$(".loginform", this).stop().animate({top:'140px'},{queue:false,duration:800});

		$("#footer").html('<address><em>Copyright &copy;</em><strong>NEO ARENA</strong><span>All Rights Reserved.</span></address>');
		$(".engName").html('NEO ARENA');
		$(".hanName").html('네오아레나');

	// SlideShow
		$('#ad_banner').cycle({
			fx: 'scrollUp',
			speed: 500,
			timeout:3000,
			pager: '#ad_counter'
		});
	});