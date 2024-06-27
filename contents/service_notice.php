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
<title>공지사항</title>
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
		<div id="lnb">	
			<ul>
				<li class="on"><a href="<?=$local_dir;?>/contents/service_notice.php">공지사항</a></li>			
				<li><a href="<?=$local_dir;?>/contents/service_faq.php">자주묻는 질문</a></li>		
				<li><a href="<?=$local_dir;?>/contents/service_suggest.php">건의사항</a></li>			
			</ul>
		</div>
		
		<div class="contents">
			
			<h3>고객센터</h3>
			
			<h4 class="mt35">공지사항</h4>	
			
			<table class="list" border="1" cellspacing="0" summary="">
				<caption>공지사항</caption>
				<colgroup>
					<col width="*" />
					<col width="*" />
					<col width="*" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col">제목</th>
						<th scope="col">글쓴이</th>
						<th scope="col">등록일</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=17&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">스마트 협업 플랫폼 'BIZSTORY' 업무협약체결</a></td>
						<td>관리자</td>
						<td>2015.04.27</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=16&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">경기남부신문_(주)유비스토리의 솔루션을 소개하다.</a></td>
						<td>관리자</td>
						<td>2013.08.27</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=10&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">서경원 대표이사 [스마트콘텐츠 인력양성사업 운영위원...</a></td>
						<td>관리자</td>
						<td>2013.06.20</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=10&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">비즈스토리 솔루션 보도자료 [네이버 뉴스]</a></td>
						<td>관리자</td>
						<td>2013.04.03</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=11&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">스마트폰 앱창작터 서경원 심사위원 [네이버 뉴스]</a></td>
						<td>관리자</td>
						<td>2013.04.03</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=9&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">(주)유비스토리 웹접근성 인증마크 획득[네이버뉴스]</a></td>
						<td>관리자</td>
						<td>2013.03.20</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=8&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">V-Center : 제 21회 국제금형 및 관련기기..</a></td>
						<td>관리자</td>
						<td>2013.03.04</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=7&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">정보통신공사업면허 취득</a></td>
						<td>관리자</td>
						<td>2013.01.04</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=6&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">뷰스토리 GS인증마크 획득 (미리보기솔루션)</a></td>
						<td>관리자</td>
						<td>2013.01.04</td>
					</tr>
					<tr>
						<td><a href="http://www.ubstory.net/nuri/bbs/bbs.php?sub_type=view&b_idx=5&pidx=135518943753&didx=2&bs_idx=2" target="_blank" title="새창으로 이동">2013년 어떤계획을 가지고 계신가요?</a></td>
						<td>관리자</td>
						<td>2013.01.04</td>
					</tr>
				</tbody>
			</table>
			
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