<?
/*
	수정 : 2013.01.18
	위치 : 탑
*/
	include $local_path . "/include/header.php";

	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	$top_code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem      = $_SESSION[$sess_str . '_mem_idx'];
	$code_mem_name = $_SESSION[$sess_str . '_mem_name'];
	$code_level    = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];;

	if ($fmode == '' || $smode == '') {
?>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<script type="text/javascript">
//<![CDATA[
	$(function () {
		$('div.ticker marquee').marquee('pointer').mouseover(function () {
			$(this).trigger('stop');
		}).mouseout(function () {
			$(this).trigger('start');
		}).mousemove(function (event) {
			if ($(this).data('drag') == true) {
				this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
			}
		}).mousedown(function (event) {
			$(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
		}).mouseup(function () {
			$(this).data('drag', false);
		});
	});
//]]>
</script>
<?
	}
?>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.main.ready.js"></script>
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
<title>BIZSTORY</title>
</head>

<body>
	<div id="loading">로딩중입니다...</div>
	<div id="loading2">문서 미리보기 로딩중입니다...</div>
	<div id="popup_file_preview" title="파일 미리보기"></div>
	<div id="preview_file_result" title="파일변환결과"></div>

	<div id="style-switcher">
		<div id="header">
			<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="홈으로 이동 합니다." class="logo">
<?
// 총판로고
	$sole_logo_where = " and solef.sole_idx = '" . $company_info_data['sole_idx'] . "' and solef.sort = '1'";
	$sole_logo_data  = sole_file_data('view', $sole_logo_where);
	if ($sole_logo_data['img_sname'] == '')
	{
		$sole_logo_img = $local_dir . '/bizstory/images/common/logo.jpg';
	}
	else
	{
		$sole_logo_path = $local_path . "/data/sole/" . $sole_logo_data['img_sname'];
		$sole_logo_img  = $local_dir . "/data/sole/" . $sole_logo_data['img_sname'];

		if (file_exists($sole_logo_path) == true)
		{}
		else $sole_logo_img = $local_dir . '/bizstory/images/common/logo.jpg';
	}

	$site_url1  = $_SERVER['SERVER_NAME'];
	$site_url1 = str_replace('www.', '', $site_url1);
	if ($site_url1 == 'on-trade.co.kr')
	{
		echo '<img src="' . $local_dir . '/bizstory/images/ontrade/logo_ontrade.jpg" width="198px" height="39px" alt="BI" />'; // On Trade Logo
	}
	else if ($site_url1 == 'v-center.co.kr' || $site_url1 == 'vcenter.ubstory.co.kr')
	{
		echo '<img src="' . $local_dir . '/bizstory/images/vcenter/logo_vcenter.jpg" width="198px" height="39px" alt="BI" />'; // V Center Logo
	}
    else if ($site_url1 == 'gw.neoarena.co.kr' || $site_url1 == 'gw.neoarena.com' || $site_url1 == 'neoarena.ubstory.co.kr')
    {
        echo '<img src="' . $local_dir . '/bizstory/images/neoarena/logo_neoarena.jpg" width="198px" height="39px" alt="home" />'; // Neoarena Logo
    }
	else
	{
		echo '<img src="' . $sole_logo_img . '" width="198px" height="39px" alt="BI" />'; // Bizstory Logo
	}
?>
			</a>
			<div class="work_number">
<?	
	$top_chk = member_chk_data($code_mem);

	if ($code_level == '1')
	{
		$work_str    = 'Y';
		$msg_str     = 'Y';
		$receipt_str = 'Y';
		$project_str = 'Y';
	}
	else
	{
	// 업무메뉴가 있는 사람만 보이도록
		$menu_where = "
			and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
			and mac.view_yn = 'Y' and mi.del_yn = 'N' and mi.mode_folder = 'work' and mi.mode_file = 'work'";
		$menu_data = menu_auth_member_data('page', $menu_where);		
		if ($menu_data['total_num'] > 0)
		{
			$work_str = 'Y';

		// 읽을 업무, 보고, 댓글
			$check_num = work_read_check('');
			$read_work = $check_num['work_check'];
		}

	// 쪽지메뉴만 있는 사람만 보이도록
		$menu_where = "
			and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
			and mac.view_yn = 'Y' and mi.del_yn = 'N' and mi.mode_type = 'message'";
		$menu_data = menu_auth_member_data('page', $menu_where);
		if ($menu_data['total_num'] > 0)
		{
			$msg_str = 'Y';
		}

	// 접수메뉴만 있는 사람만 보이도록
		$menu_where = "
			and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
			and mac.view_yn = 'Y' and mi.del_yn = 'N' and mi.mode_folder = 'receipt' and mi.mode_file = 'receipt'";
		$menu_data = menu_auth_member_data('page', $menu_where);
		if ($menu_data['total_num'] > 0)
		{
			$receipt_str = 'Y';

		// 접수 - 완료, 보류, 취소는 제외
			$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
			if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
			$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
			$receipt_page = receipt_info_data('page', $receipt_where);
		}

	// 프로젝트메뉴가 있는 사람만 보이도록
		$menu_where = "
			and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
			and mac.view_yn = 'Y' and mi.del_yn = 'N' and mi.mode_folder = 'project' and mi.mode_file = 'project'";
		$menu_data = menu_auth_member_data('page', $menu_where);
		if ($menu_data['total_num'] > 0)
		{
			$project_str = 'Y';
		}
	}
?>
				<ul>
			<?
				if ($work_str == 'Y')
				{
			?>
					<li class="icon1"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=work'">업무</a><span class="num"><em><?=number_format($top_chk['work_ing']);?></em></span></li>
					<li class="icon2"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=work&amp;smode=work&amp;sview=today'">알림</a><span class="num" id="read_top_num"><em><?=number_format($read_work);?></em></span></li>
			<?
				}
				if ($msg_str == 'Y')
				{
			?>
					<li class="icon3"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=msg&amp;smode=msg'" class="on">쪽지</a><span class="num" id="msg_top_num"><em><?=number_format($top_chk['msg_ing']);?></em></span></li>
			<?
				}
				if ($receipt_str == 'Y')
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
			<form id="totalsearchform" name="totalsearchform" action="<?=$local_dir;?>/index.php?fmode=include&amp;smode=total_search" method="post" class="search_form" onsubmit="return check_total_search()">
				<fieldset>
					<legend class="blind">컨텐츠 검색</legend>
					<input type="text" name="total_search_keyword" id="total_search_keyword" size="22" maxlength="30" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" value="키워드를 입력하세요." title="키워드를 입력하세요." class="type_text" />
					<input type="submit" class="search_submit" value="검색" />
				</fieldset>
			</form>
		<?
			$mem_img = member_img_view($code_mem, $comp_member_dir);
		?>
			<div id="etc_menu" class="animate_over">
				<ul>
					<li class="icon1"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="홈">홈</a></li>
					<li class="icon2"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="<?=$code_mem_name;?> 퇴근"><?=$mem_image;?></a></li>
					<li class="icon3"><a href="javascript:void(0);" onclick="login_out()" title="<?=$code_mem_name;?> 로그아웃">로그아웃</a></li>
				</ul>
			</div>
			<div class="member_menu">
				<ul>
					<li class="icon"><a href="javascript:void(0);" style="cursor:pointer" class="on" title="쪽지가 도착했습니다."><span><?=$mem_img['img_26'];?></span><strong><?=$code_mem_name;?></strong></a>
					</li>
					<li class="icon2"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=myinfo&amp;smode=myinfo'">정보수정</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>
	<div class="popupform" title="액티브 파일업로드폼">
		<div id="data_form2" title="파일업로드폼"></div>
	</div>
	<div class="layer_staff_popup" title="직원레이어창">
		<div id="layer_staff_view" title="직원레이어창"></div>
	</div>

	<div id="sub_popupform" class="sub_popupform" title="팝업폼"></div>

	<table id="layout_table">
		<tr>
			<td id="sidebar">
				<div id="sidebar_width">
				<?
					$left_list = left_menu_list($code_comp, $top_code_part, $code_mem, $set_part_yn);

					$menu_chk = $left_list['menu'];
					$chk_sort = $left_list['sort'];

					$left_str = down_menu_view($menu_chk, 1, 0, 'sub_navi', $chk_sort);
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
							<strong>건의사항</strong>
							<a href="<?=$local_dir;?>/index.php?fmode=maintain_bbs&smode=bbs&bs_idx=1">더보기</a>
						</h4>
				<?
					$comp_bbs_where = " and b.bs_idx = '1'";
					$comp_bbs_list = comp_bbs_info_data('list', $comp_bbs_where, '', 1, 4);
				?>
						<ul class="showlist_list">
				<?
					if ($comp_bbs_list['total_num'] == 0)
					{
				?>
							<li>등록된 데이타가 없습니다.</li>
				<?
					}
					else
					{
						foreach ($comp_bbs_list as $comp_bbs_k => $comp_bbs_data)
						{
							if (is_array($comp_bbs_data))
							{
								$subject = $comp_bbs_data['subject'];
				?>
							<li><a href="<?=$local_dir;?>/index.php?fmode=maintain_bbs&amp;smode=bbs&amp;bs_idx=1&amp;b_idx=<?=$comp_bbs_data['b_idx'];?>"><?=$subject;?></a></li>
				<?
							}
						}
					}
				?>
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
					<div class="popup_bottom"><a href="javascript:void(0);" onclick="bookmark_open()"><img src="<?=$local_dir;?>/bizstory/images/btn/popupwrite_open.png" alt="즐겨찾기 열기" /></a></div>
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
						if ($fmode == 'bbs' && $smode == 'bbs')
						{
							$board_where = " and bs.bs_idx = '" . $bs_idx . "'";
							$board_data = bbs_setting_data('view', $board_where);

							$navi_data['menu_name'] = $board_data['subject'];
						}

					// 비즈스토리용 게시판
						if ($fmode == 'maintain_bbs' && $smode == 'bbs')
						{
							$board_where = " and bs.bs_idx = '" . $bs_idx . "'";
							$board_data = comp_bbs_setting_data('view', $board_where);

							$navi_name = $board_data['subject'];
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
