<?
	include "../bizstory/common/setting.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="description" content="Business application." />
<meta name="keywords" content="bizstory,biz,business" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<!--// <meta name="viewport" content="width=device-width, minimum-scale=0.4, maximum-scale=1.0, user-scalable=3;" /> //-->
<link href="/bizstory/images/icon/favicon.ico" rel="icon" type="image/ico" />
<link href="/bizstory/images/icon/favicon.png" rel="icon" type="image/png" />
<link href="/bizstory/images/icon/favicon.ico" rel="shortcut icon" type="image/ico" />
<link type="text/css" rel="stylesheet" href="/bizstory/css/common.css" media="all" />
<!--[if IE 7]>
	<style type="text/css">
		#layout_table {position:relative; z-index:2 !important;}
	</style>
<![endif]-->
<!--[if IE 6]>
	<script type="text/javascript" src="/common/js/DD_belatedPNG_0.0.8a-min.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		DD_belatedPNG.fix('*')
		try { document.execCommand('BackgroundImageCache', false, true); }catch(e){}
	</script>
	<style type="text/css">
		.hb_schedule .schedule_textarea textarea {
			background:none;
		}
	</style>
<![endif]-->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="/bizstory/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="/bizstory/editor/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/upload/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript" src="/bizstory/js/script_file.js" charset="utf-8"></script>

<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.login.ready.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.sliderkit.1.5.1.js"></script>
<title>문서보안형</title>
</head>

<body id="login_layout" class="login info">

	<div id="loading">로딩중입니다...</div>
	
	<? include $local_path . "/include/template_inchargeLayer.php"; ?>
	
	<div id="header">
		<div class="top_nav">
			<div>
				<h2 class="blind">공통 네비게이션 영역</h2>
				<ul class="top_group">
					<li><a href="http://www.ubstory.net" target="_blank" title="새창으로 이동">유비스토리</a></li>
					<li><a href="http://homestory.ubstory.net/" target="_blank" title="새창으로 이동">홈스토리</a></li>
					<li><a href="<?=$local_dir;?>/" class="on">비즈스토리</a></li>
					<li><a href="http://viewstory.ubstory.net" target="_blank" title="새창으로 이동">뷰스토리</a></li>
				</ul>
				<ul class="top_gnb">
					<li><a href="<?=$local_dir;?>/" title="메인으로" class="home"><span class="axi axi-ion-home"></span> 메인으로</a></li>
					<li><a href="<?=$local_dir;?>/" title="로그인" class="login"><span class="axi axi-lock-outline"></span> 로그인</a></li>
				</ul>		
			</div>
		</div>
		<div id="gnb_wrap">
			<div>
				<h1><a href="<?=$local_dir;?>/" class="logo"><img src="<?=$local_dir;?>/bizstory/images/common/logo.png" alt="홈스토리"></a></h1>
				<h2 class="blind">메인메뉴 영역</h2>
				<div id="gnb">
					<ul>
						<li class="menu1"><a href="<?=$local_dir;?>/contents/service_info.php">서비스 소개</a>
						<li class="menu2"><a href="<?=$local_dir;?>/contents/service_price.php">서비스 가격</a>
						<li class="menu3"><a href="<?=$local_dir;?>/contents/service_case.php">성공사례</a>
						<li class="menu4"><a href="<?=$local_dir;?>/contents/service_partner.php">협력사 </a>                 
						<li class="menu5"><a href="<?=$local_dir;?>/contents/service_notice.php">고객센터</a>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<h2 class="blind">메인 컨텐츠 영역</h2>
	<div id="container">
		<div id="lnb">	
			<ul>
				<li><a href="<?=$local_dir;?>/contents/service_info01.php">업무형</a></li>			
				<li><a href="<?=$local_dir;?>/contents/service_info02.php">유지보수형</a></li>		
				<li class="on"><a href="<?=$local_dir;?>/contents/service_info03.php">문서보안형</a></li>			
			</ul>
		</div>
		
		<div class="contents">
			
			<p class="under_line"><img src="<?=$local_dir;?>/contents/images/01_03_img1.png" alt="내문서는 내가 지킨다 : NAS를 통한 간편한 구축" /></p>
			<p class="under_line"><img src="<?=$local_dir;?>/contents/images/01_03_img2.png" alt="안전한 문서보관은 비즈스토리 : 보안문서 시스템 도입으로 해결하세요." /></p>
			<p class="under_line"><img src="<?=$local_dir;?>/contents/images/01_03_img3.png" alt="서비스특징" /></p>
			<p><img src="<?=$local_dir;?>/contents/images/01_03_img4.png" alt="주요기능" /></p>
			
		</div>
	</div>


	<div class="login_footer">
		<div class="address">
			<h3>Contact US</h3>
			<address>
				<p>431-815 경기도 안양시 동안구 시민대로 248번길 25 (부림동 1591-9) 안양창조산업센터 305호  &nbsp;&nbsp; T. 1544-7325 &nbsp;&nbsp; F. 0505-719-7325 &nbsp;&nbsp; E. ubsns@ubstory.net</p>
			</address>
		</div>	
		<div id="footer"></div>
	</div>


	<div id="backgroundPopup"></div>
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>



</body>
</html>