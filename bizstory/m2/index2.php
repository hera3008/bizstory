<?
	include "../common/set_info.php";
//	include  "./header.php";

	// 앱에서 넘겨주는 정보
	$mem_id		= $_GET["mem_id"];
	$auth_key	= $_GET["auth_key"];

	$mobile_dir  = '/bizstory/m';

//	echo $auth_key . " auth_key<BR />";
//	echo $mem_id . " mem_id<BR />";
//	exit;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<style type="text/css">
* {
 margin:0;
 padding:0;
 -webkit-text-size-adjust:none; }

body {background:#3e414a;width:100%;margin:0 auto;}

#loading_area {background:#3e414a;width:100%;margin:0 auto;text-align:center;}
#loading_area img {width:60%;}
#loading_area .logo {margin-top: 36%;}
#loading_area .loading_gif {margin-top: 8%;width:20%;}
#loading_area .load_txt_eng {margin-top:5%;width:30%;}
#loading_area .load_txt_kor {margin-top:10%;width:40%;}
</style>

<script src="./js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./js/common.js"></script>

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

/* * * Use this for high compatibility (iDevice + Android) * * */
document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
/* * * * * * * * */

function scrollToTop() {
	myScroll.scrollTo(0, 0, 100);
}

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
	
</script>

</head>
<body>

<article id="area">
	<div id="loading_area">
		<div class="logo_area"><img src="images/load_logo.gif" alt="bizstory" class="logo" /></div>
		<div class="load_area"><img src="images/gif-load.gif" alt="" class="loading_gif" /></div>
		<div class="load_txt_eng_area"><img src="images/loading_txt01.gif" alt="Loading... " class="load_txt_eng" /></div>
		<div class="load_txt_kor_area"><img src="images/loading_txt02.gif" alt="로딩중입니다. 잠시만 기다려 주십시요!" class="load_txt_kor" /></div>
	<div>
</article>

<form id="loginform" name="loginform" method="post" action="index.php">
<input type="hidden" name="mem_id" id="mem_id" value="<?=$mem_id;?>" />
<input type="hidden" name="auth_key" id="auth_key" value="<?=$auth_key;?>" />
</form>

<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$mobile_dir;?>/login_ok2.php';

//------------------------------------ 로그인
	function check_login()
	{
		var chk_total = '';
		var chk_value = '';

		jQuery("#loading").fadeIn('slow');
		jQuery("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		jQuery.ajax({
			async : false,
			type: 'get', dataType: 'json', url: link_ok,
			data: jQuery('#loginform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					location.href = '<?=$mobile_dir;?>/index.php?mlogin';
				}
				else {
					alert(msg.error_string);
				}
			},
			error : function(request, status, error){
				alert(request);
				alert(status);
				alert(error);
			},
			complete: function(){
				jQuery("#backgroundPopup").fadeOut("slow");
			}
		});

		return false;
	}

	// 로그인실행
	check_login();
//]]>
</script>

</body>
</html>