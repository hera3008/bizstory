
	$(document).ready(function() {

	// File Style
		$(".type_basic").filestyle({
			image: "/common/upload/file_submit.gif",
			imagewidth : 82,
			imageheight : 29
		});
		$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기
		//only need force for IE6
		$("#backgroundPopup").css({
			"height": document.documentElement.clientHeight
		});
	});