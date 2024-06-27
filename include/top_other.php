<?
/*
	수정 : 2012.08.06
	위치 : 탑
*/
	include $local_path . "/include/header.php";

	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	$top_code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem      = $_SESSION[$sess_str . '_mem_idx'];
	$code_level    = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn      = $company_set_data['part_yn'];
	$set_work_yn      = $company_set_data['work_yn'];
	$set_part_work_yn = $company_set_data['part_work_yn'];
	$set_receipt_yn   = $company_set_data['receipt_yn'];

	if ($fmode == '' || $smode == '') {
?>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.ticker.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<?
	}
?>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.main.ready.js"></script>

<link rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/jquery.mCustomScrollbar.css" type="text/css" media="screen" />
<title>BIZSTORY</title>
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
</head>

<body>
	<div id="loading">로딩중입니다...</div>
	<div id="loading2">문서 미리보기 로딩중입니다...</div>
	<div id="popup_file_preview" title="파일 미리보기"></div>
	<div id="preview_file_result" title="파일변환결과"></div>

	<div id="style-switcher">
		<div id="header">
			<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="홈으로 이동 합니다." class="logo">
				<img src="<?=$local_dir;?>/bizstory/images/common/logo.jpg" width="198px" height="39px" alt="BI" />
			</a>
			<div class="work_number">
<?
	$top_chk = member_chk_data($code_mem);

// 읽을 업무, 보고
	$check_num = work_read_check('');
	$read_work = $check_num['work_check'];

// 접수 - 완료, 보류, 취소는 제외
	$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
	$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
	$receipt_page = receipt_info_data('page', $receipt_where);
?>
				<ul>
			<?
				if ($set_work_yn == 'Y')
				{
				// 업무메뉴가 있는 사람만 보이도록
					$work_str = 'N';
					if ($code_level == '1')
					{
						$work_str = 'Y';
					}
					else
					{
						$menu_where = "
							and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
							and mac.del_yn = 'N' and mac.view_yn = 'Y' and mi.mode_folder = 'work' and mi.mode_file = 'work'";
						$menu_data = menu_auth_member_data('page', $menu_where);
						if ($menu_data['total_num'] > 0)
						{
							$work_str = 'Y';
						}
					}
					if ($work_str == 'Y')
					{
			?>
					<li class="icon1"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=work'">업무</a><span class="num"><em><?=number_format($top_chk['work_ing']);?></em></span></li>
					<li class="icon2"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=work&amp;sview=today'">알림</a><span class="num"><em><?=number_format($read_work);?></em></span></li>
			<?
					}
				}
			// 쪽지메뉴만 있는 사람만 보이도록
				$msg_str = 'N';
				if ($code_level == '1')
				{
					$msg_str = 'Y';
				}
				else
				{
					$menu_where = "
						and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
						and mac.del_yn = 'N' and mac.view_yn = 'Y' and mi.mode_type = 'message'";
					$menu_data = menu_auth_member_data('page', $menu_where);
					if ($menu_data['total_num'] > 0)
					{
						$msg_str = 'Y';
					}
				}
				if ($msg_str == 'Y')
				{
			?>
					<li class="icon3"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=msg_receive'" class="on">쪽지</a><span class="num"><em><?=number_format($top_chk['msg_ing']);?></em></span></li>
			<?
				}
				if ($set_receipt_yn == 'Y')
				{
			?>
					<li class="icon4">
						<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=receipt&amp;smode=receipt&amp;list_type=all_no'">접수</a>
						<span class="num3"><em><?=number_format($receipt_page['total_num']);?></em></span>
						<span class="num2"><em><?=number_format($top_chk['receipt_ing']);?></em></span>
					</li>
			<?
				}
			?>
				</ul>
			</div>
			<form action="./" method="post" class="search_form">
				<fieldset>
					<legend class="blind">컨텐츠 검색</legend>
					<input type="text" name="search_keyword" id="search_keyword" size="22" maxlength="30" onblur="if (this.value == '') {this.value = '키워드를 입력하세요.';}" onfocus="if (this.value == '키워드를 입력하세요.') {this.value = '';}" value="키워드를 입력하세요." title="키워드를 입력하세요." class="type_text" />
					<input type="submit" class="search_submit" value="검색" />
				</fieldset>
			</form>
<?
	$mf_where = " and mf.comp_idx = '" . $code_comp . "' and mf.mem_idx = '" . $code_mem . "' and mf.sort = 1";
	$mf_data  = member_file_data('view', $mf_where);

	if ($mf_data['img_sname'] != '')
	{
		$mem_image = '<img src="' . $comp_member_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $member_info_data['mem_name'] . '" width="26px" height="26px" />';
	}
?>
			<div id="etc_menu" class="animate_over">
				<ul>
					<li class="icon1"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="홈">홈</a></li>
					<li class="icon2"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="<?=$member_info_data['mem_name'];?> 퇴근"><?=$mem_image;?></a></li>
					<li class="icon3"><a href="javascript:void(0);" onclick="login_out()" title="<?=$member_info_data['mem_name'];?> 로그아웃">로그아웃</a></li>
				</ul>
			</div>
			<div class="member_menu">
				<ul>
					<li class="icon"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=msg_receive'" class="on" title="쪽지가 도착했습니다."><span><?=$mem_image;?></span><strong><?=$member_info_data['mem_name'];?></strong></a></li>
				</ul>
			</div>
		</div>
		<div id="top-close" title="상단 레이어 닫기"></div>
		<div id="toggle-top" title="상단 레이어 열기"></div>
	</div>

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>

	<div id="sub_popupform" class="sub_popupform" title="팝업폼"></div>

	<div class="popupcontainer" id="bookmark">
		<? //include $local_path . "/bizstory/work/main_work_form.php"; ?>
	</div>

	<table id="layout_table">
		<tr>
			<td id="sidebar">
				<div id="sidebar_width">
				<?
					$left_str = left_menu_view();
					echo $left_str;
				?>
					<div class="contWrap">
						<div id="scrollSetMenuWrap">
							<h4>즐겨찾기</h4>
							<div class="mask">
								<ul id="psvcList">
									<li><span id="psvc_0"><a href="javascript:void(0)">모바일사용</a></span></li>
									<li><span id="psvc_1"><a href="javascript:void(0)">전자계산기</a></span></li>
									<li><span id="psvc_2"><a href="javascript:void(0)">SMS추가</a></span></li>
									<li><span id="psvc_3"><a href="javascript:void(0)">지사기능</a></span></li>
									<li><span id="psvc_4"><a href="http://oldbizbizbizstory.bizstory.co.kr" target="_target">회계장부</a></span></li><!--회계장부//-->
									<li><span id="psvc_5"><a href="javascript:void(0)">아이콘추가</a></span></li>
									<li><span id="psvc_6"><a href="javascript:void(0)">아이콘관리1</a></span></li>
									<li><span id="psvc_7"><a href="javascript:void(0)">아이콘관리2</a></span></li>
									<li><span id="psvc_8"><a href="javascript:void(0)">아이콘관리3</a></span></li>
								</ul>
							</div>
						</div>
					</div>
					<div id="showlist_qa" class="showlist sidebar">
						<h4>
							<strong>비즈스토리 건의사항</strong>
							<a href="./">더보기</a>
						</h4>
						<ul class="showlist_list">
							<li><a href="./">이미지보기에서 화면 좌우로...</a></li>
							<li><a href="./">상품관리에 상품정렬으로 정...</a></li>
							<li><a href="./">아이콘테스트업체 한군데 추...</a></li>
							<li><a href="./">상품분류의 세번째 카테고리...</a></li>
						</ul>
					</div>
				</div>
			</td>
			<td id="container">
				<div class="toggle_frame">
					<div id="sidebar-close">사이드메뉴 닫기</div>
					<div id="toggle-sidebar">사이드메뉴 열기</div>
				</div>
				<div class="etc_frame">
					<? include $local_path . "/bizstory/include/bookmark.php"; ?>

					<div class="popup_bottom"></div>
					<div id="clock"></div>
					<a href="http://twitter.com/ubstory" class="twittericon" title="트위터 새창으로 이동" target="_blank">Followers</a>
				</div>
				<div class="sub_layout_box">
				<?
					if ($fmode != '' && $smode != '')
					{
						$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
						$navi_data = menu_info_data("view", $navi_where);

					// 업체별로 메뉴명 가지고 오기
						$sub_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $top_code_part . "' and mc.mi_idx = '" . $navi_data['mi_idx'] . "'";
						$sub_data = menu_company_data('view', $sub_where);

						$navi_name = $sub_data['menu_name'];
						if ($navi_name == '') $navi_name = $navi_data['menu_name'];

					// 게시판일 경우
						if ($fmode == 'board' && $smode == 'board')
						{
							$board_where = " and bs.bs_idx = '" . $bs_idx . "'";
							$board_data = board_set_data('view', $board_where);

							$navi_data['menu_name'] = $board_data['subject'];
						}

						$navi_view = menu_navigation_view($navi_data["mi_idx"]);
				?>
				<!-- Page Navigation Start -->
					<div class="home_pagenavi">
						<h2>
							<?=$navi_name;?>
						</h2>
				<?
						if (is_array($navi_view['menu_name']) === true)
						{
							$len_navi = count($navi_view['menu_name']);
				?>
						<ul>
				<?
							foreach ($navi_view['menu_name'] as $navi_k => $navi_name)
							{
								if ($navi_k == $len_navi)
								{
				?>
							<li><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=<?=$fmode;?>&amp;smode=<?=$smode;?>'"><?=$navi_name;?></a></li>
				<?
								}
								else
								{
				?>
							<li><?=$navi_name;?></li>
				<?
								}
							}
				?>
						</ul>
				<?
						}
				?>
					</div>
					<hr />
				<!-- //Page Navigation End -->
				<?
					// Tab Menu
						$tab_str = tab_menu_view($navi_data['mi_idx']);
						echo $tab_str;
					}
				?>
