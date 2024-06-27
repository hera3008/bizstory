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
<title>협력사</title>
</head>

<body id="login_layout" class="login">

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
		<div class="contents">
			
			<h3>협력사</h3>
			
			<div class="con_photo"><img src="<?=$local_dir;?>/contents/images/img1.gif" alt="" /></div>
			
			<h4 class="mt35">언론보도</h4>	
			<div class="con_news">
				<!-- div class="con_news_photo">
					<img alt="" src="http://ubstory.net/data/bbs/notice/16/1377564064161" />
				</div>
				<h5>스마트워크시대의 협업관리솔루션_비즈스토리v2.0출시</h5 -->
				
				<ul>
					<li>
    					<a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=17&didx=2" target="_blank" title="새창으로 이동">
		    				<dl>
		    					<dt>강소기업의 스마트 협업활성화와 경쟁력강화를 위한 ‘BIZSTORY’ 업무협약체결</dt>
		    					<dd>
		    						㈜유비스토리는 스마트 비즈니스 플랫폼 ‘BIZSTORY’의 경쟁력 강화를 위하여 ㈜에이블스토어, 건솔루션㈜와 업무협약을 체결하였다.
		    						<span>자세히보기</span>
		    					</dd>
		    				</dl>
		    			</a>
		                <div>
							<img width="400" src="http://ubstory.net/data/tmp/SU1HXzgyNDFf7YGs6riw7LaV7IaMLmpwZw==_1430124599.jpg" alt="" />
						</div>
					</li>
				</ul>
			</div>
			
			<h4 class="mt45">협력사 안내</h4>				
			<div class="box">
				<div>
					<h5>(주)에이블스토어</h5>
					<a href="http://ablestor.com/" target="_blank" title="새창으로 이동">바로가기</a>
				</div>
				<img src="<?=$local_dir;?>/contents/images/ablestor.png" class="b_logo" alt="ablestor" />
				<p>
					<strong><span>"인간과 기술 그리고 가능성"을 믿어라</span><br />
					성장성 있는 사업 분야를 개척하는 선두주자, 에이블스토어</strong>
					인간과 기술, 그리고 가능성을 열어가며 성장성 있는 사업 분야를 개척하는 기업 "에이블스토어"<br />
					에이블은 유능한, 뛰어난을 뜻하는 영어의 어원이며, 스토어는 저장이라는 뜻을 가집니다. 디지털 시대의 모든 데이터는 공유되고 저장되기에 저희 에이블스토어는 디지털 컨텐츠를 생성.공유.저장에 있어서 뛰어난 실력을 발휘하여 상상을 현실화 하고자 하는 기업입니다.
				</p>
				<ul>
					<li><a href="http://cafe.naver.com/synologynas" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/contents/images/blog.gif" alt="blog" /></a></li>
				</ul>
			</div>
			
			<div class="box f_r">
				<div>
					<h5>건솔루션(주)</h5>
					<a href="http://www.gunsol.com/" target="_blank" title="새창으로 이동">바로가기</a>
				</div>
				<img src="<?=$local_dir;?>/contents/images/gunsolution.png" class="b_logo" alt="gunsolution" />
				<p>
					<strong><span>금형 제조 전문 솔루션, 건(建)솔루션</span><br />금형 제조 분야에 전문화된, 건강한 솔루션을 제공하는 건솔루션㈜</strong>
					건솔루션㈜는 제조업에 대한 열정과 책임감으로 가득한 젊은 엔지니어들의 OPERATION 기술이 아닌, 제조 기술을 선도하고자 하는 마음이 모여 2011년 시작되었습니다. 그 후로 건솔루션㈜는 제조업의 노하우와 IT 기술을 연결하고 융합하여 금형 제조 분야에 전문화된 솔루션을 개발해 제공해 왔습니다.<br />그리고 이제는 한발 더 나아가 제조 분야의 솔루션을 선도해 나가고 있습니다.<br />
					건솔루션㈜는 늘 지금처럼 건강한 솔루션으로 보답하겠습니다. 
				</p>
				<ul>
					<li><a href="http://blog.naver.com/smirt" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/contents/images/blog.gif" alt="blog" /></a></li>
				</ul>
			</div>
			
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