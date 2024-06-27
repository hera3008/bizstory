// iScroll;
	function loaded() {
		myScroll = new iScroll('wrapper');
	}
	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	document.addEventListener('DOMContentLoaded', loaded, false);


// iScroll를 이용하면 터치로 스크롤할수 없다. iscroll을 사용할때 예외처리 해주는 스크립트;
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
	document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);