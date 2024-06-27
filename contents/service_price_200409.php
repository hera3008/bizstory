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
<title>서비스가격</title>
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
			
			<h3>서비스 가격</h3>
			<h4>서비스 상세내역</h4>
			
			<h5>Standard Service</h5>
			<table class="list" border="1" cellspacing="0" summary="">
				<caption>서비스 상세내역</caption>
				<colgroup>
					<col width="20%" />
					<col width="35%" />
					<col width="*" />
					<col width="10%" />
				</colgroup>
				<thead>
					<tr>
						<th colspan="2" scope="col" class="t_bg2">기능분류</th>
						<th scope="col" class="t_bg2">BIZSTORY Standard Service</th>
						<th scope="col" class="t_bg2">추가항목</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row" class="t_bg2_2">사용유저</th>
						<th scope="row" class="t_bg2_2">직원 등록 관리</th>
						<td>무제한 (Premium Service 제외)</td>
						<td></td>
					</tr>
					<tr>
						<th rowspan="2" scope="row" class="t_bg2_2">업무관리</th>
						<th scope="row" class="t_bg2_2">업무관리</th>
						<td rowspan="5" class="b_l">기본 지원 서비스</td>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg2_2">쪽지기능</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg2_2">게시판</th>
						<th scope="row" class="t_bg2_2">게시판 생성관리</th>
						<td></td>
					</tr>
					<tr>
						<th rowspan="2" scope="row" class="t_bg2_2">모바일</th>
						<th scope="row" class="t_bg2_2">업무관리</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg2_2">노티피케이션 기능</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg2_2">저장공간</th>
						<th scope="row" class="t_bg2_2">클라우드 서비스</th>
						<td>IDC 센터 내 저장공간 20GB 제공</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			
			
			<h5 class="h5_b mt35">Premium Service</h5>
			<table class="list b_t" border="1" cellspacing="0" summary="">
				<caption>서비스 상세내역</caption>
				<colgroup>
					<col width="20%" />
					<col width="35%" />
					<col width="*" />
					<col width="10%" />
				</colgroup>
				<thead>
					<tr>
						<th colspan="2" scope="col" class="t_bg">기능분류</th>
						<th scope="col" class="t_bg">BIZSTORY Premium Service</th>
						<th scope="col" class="t_bg">추가항목</th> 
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row" class="t_bg_2 c267db1" colspan="4">비즈스토리 Premium Service는 <strong class="txt_ss">Standard Service가 기본으로 포함</strong> 되어 있습니다.</th>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">지사기능</th>
						<th scope="row" class="t_bg_2">지사별 해당기능 포함</th>
						<td>1지사 추가시 \  (VAT 별도)</td>
						<td></td>
					</tr>
					<tr>
						<th rowspan="4" scope="row" class="t_bg_2">뷰어기능</th>
						<th scope="row" class="t_bg_2">아래한글</th>
						<td rowspan="18" class="b_l">프리미엄 서비스 전환시<br />계정당 서비스 비용(VAT 별도)<br />ㆍ계정 5개 : <br />ㆍ계정 10개 : <br />ㆍ계정 30개 : </td>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">MS 오피스</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">이미지 및 PDF</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">오토캐드 및 기타</th>
						<td></td>
					</tr>
					<tr>
						<th rowspan="2" scope="row" class="t_bg_2">업무관리</th>
						<th scope="row" class="t_bg_2">프로젝트 기능</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">이메일 연동기능</th>
						<td></td>
					</tr>
					<tr>
						<th rowspan="3" scope="row" class="t_bg_2">고객관리</th>
						<th scope="row" class="t_bg_2">고객접수관리</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">거래처 관리</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">정기점검 보고서</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">모바일</th>
						<th scope="row" class="t_bg_2">접수처리</th>
						<td></td>
					</tr>
					<tr>
						<th rowspan="5" scope="row" class="t_bg_2">에이전트</th>
						<th scope="row" class="t_bg_2">배포 수량(무제한)</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">광고배너 제공(기본 2개)</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">공지기능</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">알림기능</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">상담기능</th>
						<td></td>
					</tr>
					<tr>
						<th rowspan="3" scope="row" class="t_bg_2">문서보안(파일센터)</th>
						<th scope="row" class="t_bg_2">권한설정</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">파일관리</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">리비전관리</th>
						<td></td>
					</tr>
					<tr>
						<th scope="row" class="t_bg_2">저장공간</th>
						<th scope="row" class="t_bg_2">파일센터 데이터 저장공간</th>
						<td>용량별 월 사용료(VAT 별도)</td>
						<td></td>
					</tr>
					<tr>
						<th rowspan="3" scope="row" class="t_bg_2"><!--a class="modalLink" href="#modal1"></a --><img src="/contents/images/02_img1.png" style="width:150px;" alt="" /></th>
						<th rowspan="3" scope="row" class="t_bg_2">
							<ul>
								<li>CPU : Intel Celeron 2.0GHz Quad-Core</li>
								<li>Memory : 2GB SO-DIMM DDR3L</li>
								<li>Port : ISB 3.0 x 3, USB2.0 x 2, eSATA x 2</li>
								<li>LAN : Gigabit Ethernet x 2</li>
								<li>크기 : 185.5(H) x 170(W) x 230(D) mm</li>
								<li>무게  : 2.93 kg / 6.46 lb</li>
							</ul>
						</th>
						<td>3 TB (10인 기준) 월 사용료 -원</td>
						<td></td>
					</tr>
					<tr>
						<td>5 TB (20인 기준) 월 사용료 -원</td>
						<td></td>
					</tr>
					<tr>
						<td>8 TB (30인 기준) 월 사용료 -원</td>
						<td></td>
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

    <div class="overlay"></div>

    <div id="modal1" class="modal">
        <p class="closeBtn">Close</p>
        <h2>Your Content Here</h2>
    </div>


	<div id="backgroundPopup"></div>
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>

	<!-- 모달팝업추가작업중150708:완료되면 개발,커밋완료해야함 -->
	<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.modal.min.js"></script>
	<script type="text/javascript" src="<?=$local_dir;?>/common/js/site.js"></script>

</body>
</html>