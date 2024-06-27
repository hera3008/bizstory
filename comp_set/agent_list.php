<?
/*
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 타입관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_agent = search_agent_type($code_part, $code_agent);

// 로고파일
	$company_where = " and cf.comp_idx = '" . $code_comp . "' and cf.sort = '1'";
	$logo_data = company_file_data('view', $company_where);

	if ($logo_data['total_num'] == 0) $company_logo_img = '';
	else $company_logo_img = '<img src="' . $comp_company_dir . '/' . $logo_data['img_sname'] . '" width="180px" height="50px" alt="' . $company_data['comp_name'] . '" />';
?>

<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/agent/css/agent2.css" media="all" />
<div id="agent_wrap1">
	<div id="header">
		<div class="logo"><?=$company_logo_img;?></div>
		<div class="member_btn"></div>
		<div class="header_title">
			<p>비즈스토리 솔루션</p>
			<p>언제든지 신청하시면 신속정확한 비즈스토리</p>
		</div>
	</div>
	<!-- //헤더영역 -->
	<div id="container">
<?
	$banner_where = " and ab.comp_idx = '" . $code_comp . "' and ab.part_idx = '" . $code_part . "' and ab.view_yn = 'Y'";
	$banner_list = agent_banner_data('list', $banner_where, '', '', '');
?>
		<div class="agent_banner">
			<div id="agent_banner" class="ad_banner">
				<div id="ad_banner">
		<?
			if ($banner_list['total_num'] == 0)
			{
				echo '등록된 배너가 없습니다.';
			}
			else
			{
				foreach ($banner_list as $banner_k => $banner_data)
				{
					if (is_array($banner_data))
					{
						if ($banner_data["img_sname"] != '')
						{
							$content = '<img src="' . $comp_banner_dir . '/' . $banner_data["img_sname"] . '" alt="' . $banner_data["content"] . '" width="373" height="100" />';
						}
						else
						{
							$content = '';
						}

						if ($banner_data['link_url'] == '') $content = $content;
						else $content = '<a href="http://' . $banner_data['link_url'] . '" target="_blank">' . $content . '</a>';
		?>
					<div>
						<?=$content;?></a>
					</div>
		<?
					}
				}
			}
		?>
				</div>
				<div id="ad_counter"></div>
			</div>
		</div>

		<div id="agent_icon">
			<h4>부가 서비스</h4>
			<div>
				<ul>
					<li class="icon01"><a href="javascript:void(0);" onclick="new_open('receipt.php', 'receipt', '900', '600', 'Yes')">A/S 접수내역</a></li>
					<li class="icon02"><a href="./">아이템2</a></li>
				</ul>
				<ul>
					<li class="icon03"><a href="./">아이템3</a></li>
					<li class="icon04"><a href="./">아이템4</a></li>
				</ul>
			</div>
		</div>
<?
	$notice_where = " and an.comp_idx = '" . $code_comp . "' and an.part_idx = '" . $code_part . "' and an.view_yn = 'Y'";
	$notice_list = agent_notice_data('list', $where, '', '', '');
?>
		<div class="agent_news">
			<div id="main_notice" class="ticker">
				<div class="ticker_frame">
					<div id="ticker-wrapper" class="no-js">
						<ul id="js-news" class="js-hidden">
							<li class="news-item">등록된 공지사항이 나옵니다.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="agent_bbs">
			<div id="agent_servie" class="service_info">
				<h4><strong><?=$client_name;?></strong>최근처리내역</h4>
				<ul>
					<li>이 페이지에서 보이지 않습니다.</li>
					<li class="more">더보기</a></li>
				</ul>
			</div>
		</div>
	</div>
	<br />
	<div id="footer">
		<div class="footer_copy">Copyright ©<strong>BIZSTORY</strong> All Rights Reserved.</div>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.ticker.js"></script>
<script type="text/javascript">
//<![CDATA[
// SlideShow
	$('#ad_banner').cycle({
		fx: 'scrollUp', speed: 500, timeout:3000, pager: '#ad_counter'
	});
//]]>
</script>