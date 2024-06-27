<?
/*
	수정 : 2012.11.02
	위치 : 에이전트 C 타입
*/
	include "../bizstory/common/setting.php";
	include $local_path . "/agent/include/agent_chk.php";
	include $local_path . "/agent/include/header.php";

	if ($bizstory_view == "Y") {
?>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/agent/css/agent.css" media="all" />
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<title>BizStory Agent - <?=$client_name;?></title>
<script type="text/javascript">
//<![CDATA[
	$(function () {
		$('div.demo marquee').marquee('pointer').mouseover(function () {
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
</head>

<body id="agent_popup">
<? include $local_path . "/agent/include/top.php"; ?>

<?
// 총관리 배너
	$tbanner_where = " and bi.view_yn = 'Y' and bi.banner_type = '1' and (bi.comp_all = 'Y' or concat(bi.comp_idx, ',') like '%," . $client_comp . ",%')";
	$tbanner_list = banner_info_data('list', $tbanner_where, '', '', '');

// 업체가 등록한 배너
	$banner_where = " and ab.comp_idx = '" . $client_comp . "' and ab.part_idx = '" . $client_part . "' and ab.view_yn = 'Y'";
	$banner_list = agent_banner_data('list', $banner_where, '', '', '');

	$total_banner = $tbanner_list['total_num'] + $banner_list['total_num'];
?>
<!-- Ad Banner -->
	<div class="agent_banner">
		<div id="agent_banner" class="ad_banner">
			<div id="ad_banner">
	<?
		if ($total_banner == 0)
		{
	?>
				등록된 배너가 없습니다.
	<?
		}
		else
		{
		// 총관리 배너
			foreach ($tbanner_list as $banner_k => $banner_data)
			{
				if (is_array($banner_data))
				{
					if ($banner_data["img_sname1"] != '')
					{
						$content = '<img src="' . $tbanner_dir . '/' . $banner_data["img_sname1"] . '" alt="' . $banner_data["content"] . '" width="373" height="100" />';
					}
					else
					{
						$content = '';
					}

					if ($banner_data['link_url'] == '') $content = $content;
					else $content = '<a href="' . $banner_data['link_url'] . '" target="_blank">' . $content . '</a>';
	?>
				<div>
					<?=$content;?></a>
				</div>
	<?
				}
			}

		// 업체가 등록한 배너
			foreach ($banner_list as $banner_k => $banner_data)
			{
				if (is_array($banner_data))
				{
					if ($banner_data["img_sname"] != '')
					{
						$content = '<img src="' . $banner_dir . '/' . $banner_data["img_sname"] . '" alt="' . $banner_data["content"] . '" width="373" height="100" />';
					}
					else
					{
						$content = '';
					}

					if ($banner_data['link_url'] == '') $content = $content;
					else $content = '<a href="' . $banner_data['link_url'] . '" target="_blank">' . $content . '</a>';
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

<?
// 접수알림
	$rc_data['chk_ci']  = $client_idx;
	$rc_data['chk_mac'] = $macaddress;
	$read_chk = receipt_read_check($rc_data);

	$notify_chk[1] = $read_chk['read_comment'];
	unset($rc_data);

	$button_where = " and abu.comp_idx = '" . $client_comp . "' and abu.part_idx = '" . $client_part . "' and abu.agent_type = '" . $client_agent . "'";
	$button_list = agent_button_data('list', $button_where, '', '', '');

	if ($button_list['total_num'] == 0) // 기존데이타
	{
		foreach ($set_agent_button as $k => $v)
		{
			$button_name[$k] = $v;
			$button_url[$k]  = '';
		}
	}
	else
	{
		foreach ($button_list as $k => $data)
		{
			if (is_array($data))
			{
				$sort     = $data['sort'];
				$btn_name = $data['btn_name'];
				$btn_type = $data['btn_type'];
				$link_url = $data['link_url'];

				$button_name[$sort] = $btn_name;
				if ($btn_type == 5) // 링크아이콘
				{
					$button_url[$sort]    = $link_url;
					$button_target[$sort] = ' target="_blank"';
				}
				else
				{
					if ($btn_type != '')
					{
						$button_url[$sort]    = '#" onclick="' . $set_agent_button_url[$btn_type];
						$button_target[$sort] = '';
					}
					else
					{
						$button_url[$sort]    = 'javascript:void(0);';
						$button_target[$sort] = '';
					}
				}
			// 알림알림
				if ($btn_type == 2) // 상담게시판
				{
					$cc_data['chk_comp'] = $client_comp;
					$cc_data['chk_part'] = $client_part;
					$cc_data['chk_ci']   = $client_idx;
					$cc_data['chk_ccg']  = $client_ccg_idx;
					$cc_data['chk_mac']  = $macaddress;
					$read_chk = agent_bnotice_read_check($cc_data);

					$notify_chk[$sort] = $read_chk['bnotice_check'];
					unset($cc_data);
				}
			// 상담알림
				if ($btn_type == 3) // 상담게시판
				{
					$cc_data['chk_ci']  = $client_idx;
					$cc_data['chk_mac'] = $macaddress;
					$read_chk = consult_read_check($cc_data);

					$notify_chk[$sort] = $read_chk['read_comment'];
					unset($cc_data);
				}
			}
		}
	}
	unset($data);
	unset($button_list);
?>
	<div id="agent_icon">
		<h4>부가 서비스</h4>
		<div>
			<ul>
				<li class="icon01">
					<a href="javascript:void(0);" onclick="move_receipt('');"><?=$button_name[1];?></a>
			<?
				if ($notify_chk[1] != '')
				{
			?>
					<span class="today_num"><em><?=$notify_chk[1];?></em></span>
			<?
				}
			?>
				</li>
				<li class="icon02">
					<a href="<?=$button_url[2];?>"<?=$button_target[2];?>><?=$button_name[2];?></a>
			<?
				if ($notify_chk[2] != '')
				{
			?>
					<span class="today_num"><em><?=$notify_chk[2];?></em></span>
			<?
				}
			?>
				</li>
			</ul>
			<ul>
				<li class="icon03">
					<a href="<?=$button_url[3];?>"<?=$button_target[3];?>><?=$button_name[3];?></a>
			<?
				if ($notify_chk[3] != '')
				{
			?>
					<span class="today_num"><em><?=$notify_chk[3];?></em></span>
			<?
				}
			?>
				</li>
				<li class="icon04">
					<a href="<?=$button_url[4];?>"<?=$button_target[4];?>><?=$button_name[4];?></a>
			<?
				if ($notify_chk[4] != '')
				{
			?>
					<span class="today_num"><em><?=$notify_chk[4];?></em></span>
			<?
				}
			?>
				</li>
			</ul>
		</div>
	</div>

<!--// 공지사항 //-->
	<div class="agent_news">
<?
	$tnotice_where = "
		and ni.notice_type = '1' and ni.view_yn = 'Y'
		and (concat(ni.comp_idx, ',') like '%" . $client_comp . "%' or ni.comp_all = 'Y')
	";
	$tnotice_list = notice_info_data('list', $tnotice_where, '', '', '');

	$notice_where = " and an.comp_idx = '" . $client_comp . "' and an.part_idx = '" . $client_part . "' and an.view_yn = 'Y'";
	$notice_list = agent_notice_data('list', $notice_where, '', '', '');

	$total_agent = $tnotice_list['total_num'] + $notice_list['total_num'];
?>
		<div class="demo">
			<div class="news_area">
				<marquee behavior="scroll" direction="left" scrollamount="2"><p>
	<?
		foreach ($tnotice_list as $notice_k => $notice_data)
		{
			if (is_array($notice_data))
			{
				if ($notice_data['link_url'] == '') $content = $notice_data['content'];
				else $content = '<a href="http://' . $notice_data['link_url'] . '" target="_blank" class="maintain_notice">' . $notice_data['content'] . '</a>';

			// 중요도
				if ($notice_data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
				else if ($notice_data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
				else if ($notice_data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
				else $important_span = '';
	?>
					<span class="maintain_notice" >ㆍ [Bizstory] <?=$content;?><?=$important_span;?></span>
	<?
			}
		}

		foreach ($notice_list as $notice_k => $notice_data)
		{
			if (is_array($notice_data))
			{
				if ($notice_data['link_url'] == '') $content = $notice_data['content'];
				else $content = '<a href="http://' . $notice_data['link_url'] . '" target="_blank">' . $notice_data['content'] . '</a>';
	?>
					<span style="padding-right:20px;">ㆍ <?=$content;?></span>
	<?
			}
		}
	?>
				</p></marquee>
			</div>
		</div>
	</div>

	<div class="agent_bbs">
		<!-- Servie Information -->
		<div id="agent_servie" class="service_info">
			<h4><strong><?=$client_name;?></strong>최근처리내역</h4>
			<ul>
		<?
			$receipt_where = " and ri.ci_idx = '" . $client_idx . "'";
			$receipt_list = receipt_info_data('list', $receipt_where, '', 1, 4);
			if ($receipt_list["total_num"] == 0)
			{
		?>
				<li>등록된 접수내역이 없습니다.</li>
		<?
			}
			else
			{
				foreach($receipt_list as $receipt_k => $data)
				{
					if (is_array($data))
					{
					// 접수상태
						$receipt_status_str = '<span style="';
						if ($data['receipt_status_bold'] == 'Y') $receipt_status_str .= 'font-weight:900;';
						if ($data['receipt_status_color'] != '') $receipt_status_str .= 'color:' . $data['receipt_status_color'] . ';';
						$receipt_status_str .= '">' . $data['receipt_status_str'] . '</span>';
		?>
				<li><strong>[<?=$data['receipt_status_str'];?>]</strong><a href="javascript:void(0);" onclick="move_receipt('<?=$data['ri_idx'];?>');"><?=$data['subject'];?></a></li>
		<?
					}
				}
			}
		?>
				<li class="more" onclick="move_receipt('');"><a href="javascript:void(0);" onclick="move_receipt()">더보기</a></li>
			</ul>
		</div>
	</div>

	<form id="agentform" name="agentform" method="post" action="">
		<input type="hidden" id="agent_client_comp"  name="client_comp"  value="<?=$client_comp;?>" />
		<input type="hidden" id="agent_client_part"  name="client_part"  value="<?=$codse_part;?>" />
		<input type="hidden" id="agent_client_agent" name="client_agent" value="<?=$client_agent;?>" />
		<input type="hidden" id="agent_client_idx"   name="client_idx"   value="<?=$client_idx;?>" />
		<input type="hidden" id="agent_client_code"  name="client_code"  value="<?=$client_code;?>" />
		<input type="hidden" id="agent_client_name"  name="client_name"  value="<?=$client_name;?>" />
		<input type="hidden" id="agent_macaddress"   name="macaddress"   value="<?=$macaddress;?>" />
		<input type="hidden" id="agent_receipt_idx"  name="receipt_idx"  value="" />
		<input type="hidden" id="agent_form_chk"     name="form_chk"     value="" />
		<input type="hidden" id="agent_sub_type"     name="sub_type"     value="" />
	</form>

<?
	include $local_path . "/agent/include/company_default.php";
?>
	<script type="text/javascript">
	//<![CDATA[
	// SlideShow
		$('#ad_banner').cycle({
			fx: 'scrollUp', speed: 500, timeout:3000, pager: '#ad_counter'
		});

		function move_receipt(idx)
		{
			$('#agent_receipt_idx').val(idx);
			$('#agent_sub_type').val('');
			document.agentform.action = 'receipt.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			document.agentform.submit();
		}

		function receipt_request()
		{
			$('#agent_sub_type').val('postform');
			document.agentform.action = 'receipt.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			document.agentform.submit();
		}

		function move_bnotice()
		{
			$('#agent_sub_type').val('');
			document.agentform.action = 'bnotice.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			document.agentform.submit();
		}

		function move_consult()
		{
			$('#agent_sub_type').val('');
			document.agentform.action = 'consult.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			document.agentform.submit();
		}

		function move_board()
		{
			$('#agent_sub_type').val('');
			document.agentform.action = 'board.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			//document.agentform.submit();
		}
	//]]>
	</script>
<?
	}
	else echo $error_string;

	include $local_path . "/agent/include/tail.php";
?>