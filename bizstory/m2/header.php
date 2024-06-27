<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="format-detection" content="telephone=no" />
<link rel="apple-touch-startup-image" href="./images/iphone-portrait.png" />
<link rel="apple-touch-icon-precomposed" href="./images/apple-touch-icon-114x114-precomposed.png" />
<link rel="apple-touch-icon" href="app_icon.png" />
<link rel="apple-touch-startup-image" href="startup.png"/> 

<meta name="publisher" content="" />
<meta name="keywords" content="BizStory,UBStory,HomeStory" />
<title>BIZSTORY</title>

<!-- link rel="stylesheet" href="./css/1.3.1/jquery.mobile-1.3.1.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script -->
<script src="./js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
<script type="text/javascript" src="./js/iscroll.js"></script>
<!--<script type="text/javascript" src="./js/modernizr.custom.js"></script>-->
<link type="text/css" href="../css/style.css" rel="stylesheet" />
<link type="text/css" href="./css/common.css" rel="stylesheet" />
<!-- script src="http://code.jquery.com/jquery-1.7.1.min.js"></script -->
<link type="text/css" href="./css/component.css" rel="stylesheet" /> <!-- 모달팝업 관련 -->
<script type="text/javascript" src="/common/js/jquery.ezmark.min.js"></script> <!-- 라디오체크박스 관련 -->
<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script> <!-- 도움말 관련 -->
<script type="text/javascript" src="./js/ui2.js"></script> <!-- 도움말 관련 -->
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<script type="text/javascript" src="./js/jquery.maskedinput.min.js"></script>




<script type="text/javascript">
var myScroll;

function loaded() {
	try {
		myScroll = new iScroll('wrapper', {
			useTransform: false,
			onBeforeScrollStart: function (e) {
			var target = e.target;
			while (target.nodeType != 1) target = target.parentNode;
			if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
					e.preventDefault();
			}
		});	
	} catch(e) {}
	
}
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

/* * * * * * * *
 *
 * Use this for high compatibility (iDevice + Android)
 *
 */
document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
/*
 * * * * * * * */


/* * * * * * * *
 *
 * Use this for iDevice only
 *
 */
//document.addEventListener('DOMContentLoaded', loaded, false);
/*
 * * * * * * * */


/* * * * * * * *
 *
 * Use this if nothing else works
 *
 */
//window.addEventListener('load', setTimeout(function () { loaded(); }, 200), false);
/*
 * * * * * * * */

function scrollToTop() {
	myScroll.scrollTo(0, 0, 100);
}
</script>
<style>
	/* 로딩이미지 박스 꾸미기 */
	div#viewLoading {
		text-align: center;
		/*padding-top: 70px;*/
		background: #000;
		filter: alpha(opacity=60);
		opacity: 0.6;
		z-index:10000;
</style>
<script>
	$(function()
	{
		// 페이지가 로딩될 때 'Loading 이미지'를 숨긴다.
		$('#viewLoading').hide();

		// ajax 실행 및 완료시 'Loading 이미지'의 동작을 컨트롤하자.
		$(window)
		.ajaxStart(function()
		{
			// 로딩이미지의 위치 및 크기조절	
			
			$('#viewLoading').css('position', 'absolute');
			$('#viewLoading').css('left', $('#page').offset().left);
			$('#viewLoading').css('top', $('#page').offset().top);
			$('#viewLoading').css('width', $(window).width());
			$('#viewLoading').css('height', $(window).height());
			
			//$(this).show();
			$('#viewLoading').fadeIn(500);
		})
		.ajaxStop(function()
		{
			//$(this).hide();
			$('#viewLoading').fadeOut(500);
		});
	});	
	
	$(function() {
		$(document).delegate(".call_me", "click", function() {
			window.location.href = $(this).attr('href');
		});
	});
</script>


</head>

<body>
<!-- 로딩 이미지 -->
<div id="viewLoading">
	<p>
		<img src="./images/loading.gif" style="margin:50% auto"/>
	</p>
</div>