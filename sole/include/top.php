<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 탑
*/
	include $local_path . "/sole/include/header.php";

	$code_sole = $_SESSION[$sess_str . '_sole_idx'];
?>
<script type="text/javascript" src="<?=$local_dir;?>/sole/js/jquery.main.ready.js"></script>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-34294074-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<title>BIZSTORY 총판</title>
</head>

<body>
	<div id="loading">로딩중입니다...</div>
<?
	$sole_where = " and sole.sole_idx = '" . $_SESSION[$sess_str . "_sole_idx"] . "'";
	$sole_data = sole_info_data("view", $sole_where);
?>
	<div id="style-switcher">
		<div id="header">
			<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/sole/'" title="홈으로 이동 합니다." class="logo">
				<img src="<?=$local_dir;?>/bizstory/images/common/logo.jpg" width="198px" height="39px" alt="BI" />
			</a>
			<div id="etc_menu" class="animate_over">
				<ul>
					<li class="icon1"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/sole/'" title="홈">홈</a></li>
					<li class="icon3"><a href="javascript:void(0);" onclick="login_out()" title="<?=$sole_data['comp_name'];?> 로그아웃">로그아웃</a></li>
				</ul>
			</div>
			<div class="member_menu">
				<ul>
					<li class="icon"><a href="javascript:void(0);" class="on"><strong><?=$sole_data['comp_name'];?></strong></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<table id="layout_table">
		<tr>
			<td id="sidebar">
				<div id="sidebar_width">
					<ul id="sub_navi">
						<li id="left_0_1">
							<a href="javascript:void(0);" onclick="location.href='#'" class="icon01"><em></em>총판관리</a>
							<ul id="submenu_1">
								<li class="frist" id="left_1_1">
									<a href="javascript:void(0);" onclick="location.href='/sole/index.php?fmode=company&amp;smode=company'">업체목록</a>
								</li>
								<li id="left_1_2"></li>
								<li class="end" id="left_1_3"></li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
			<td id="container">
				<div class="toggle_frame">
					<div id="sidebar-close">사이드메뉴 닫기</div>
					<div id="toggle-sidebar">사이드메뉴 열기</div>
				</div>
				<div class="sub_layout_box">
					<div class="home_pagenavi">
						<h2><?=$navi_name;?></h2>
						<ul>
							<li><?=$first_navi_name;?></li>
							<li><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/sole/index.php?fmode=<?=$fmode;?>&amp;smode=<?=$smode;?>'"><?=$navi_name;?></a></li>
						</ul>
					</div>
					<hr />