
	<?
		$banner_where = " and ab.comp_idx = '" . $client_comp . "' and ab.part_idx = '" . $client_part . "' and ab.agent_type = '" . $client_agent . "'";
		$banner_list = agent_banner_data('list', $banner_where, '', '', '');
	?>
		<div class="agent_banner">
			<!-- Ad Banner -->
			<div id="agent_banner" class="ad_banner">
				<div id="ad_banner">
		<?
			if ($banner_list['total_num'] == 0)
			{
		?>
					등록된 배너가 없습니다.
		<?
			}
			else
			{
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
			<!-- //Ad Banner -->
		</div>

		<div id="agent_icon">
			<h4>부가 서비스</h4>
			<div>
				<ul>
					<li class="icon01"><a href="javascript:void(0);" onclick="move_receipt('');">A/S 접수내역</a></li>
					<li class="icon02"><a href="./">아이템2</a></li>
				</ul>
				<ul>
					<li class="icon03"><a href="./">아이템3</a></li>
					<li class="icon04"><a href="./">아이템4</a></li>
				</ul>
			</div>
		</div>

		<div class="agent_news">
	<?
		$notice_where = " and an.comp_idx = '" . $client_comp . "' and an.part_idx = '" . $client_part . "' and an.agent_type = '" . $client_agent . "'";
		$notice_list = agent_notice_data('list', $where, '', '', '');
	?>
			<!-- Ticker -->
			<div id="main_notice" class="ticker">
				<div class="ticker_frame">
					<div id="ticker-wrapper" class="no-js">
						<ul id="js-news" class="js-hidden">
		<?
			if ($notice_list['total_num'] == 0)
			{
		?>
							<li class="news-item">등록된 공지사항이 없습니다.</li>
		<?
			}
			else
			{
				foreach ($notice_list as $notice_k => $notice_data)
				{
					if (is_array($notice_data))
					{
						if ($notice_data['link_url'] == '') $content = $notice_data['content'];
						else $content = '<a href="http://' . $notice_data['link_url'] . '" target="_blank">' . $notice_data['content'] . '</a>';
		?>
							<li class="news-item"><?=$content;?></li>
		<?
					}
				}
			}
		?>
						</ul>
					</div>
				</div>
			</div>
			<!-- //Ticker -->
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
		<input type="hidden" id="agent_client_comp"   name="client_comp"   value="<?=$client_comp;?>" />
		<input type="hidden" id="agent_client_part"   name="client_part"   value="<?=$codse_part;?>" />
		<input type="hidden" id="agent_client_agent"  name="client_agent"  value="<?=$client_agent;?>" />
		<input type="hidden" id="agent_client_idx"  name="client_idx"  value="<?=$client_idx;?>" />
		<input type="hidden" id="agent_client_code" name="client_code" value="<?=$client_code;?>" />
		<input type="hidden" id="agent_client_name" name="client_name" value="<?=$client_name;?>" />
		<input type="hidden" id="agent_receipt_idx" name="receipt_idx" value="" />
	</form>

	<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.ticker.js"></script>
	<script type="text/javascript">
	//<![CDATA[
	// SlideShow
		$('#ad_banner').cycle({
			fx: 'scrollUp', speed: 500, timeout:3000, pager: '#ad_counter'
		});

		function move_receipt(idx)
		{
			$('#agent_receipt_idx').val(idx);
			document.agentform.action = 'receipt.php';
			document.agentform.method = 'post';
			document.agentform.target = 'receipt_popup';
			document.agentform.submit();
		}
	//]]>
	</script>
<?
//	}
//	else echo $error_string;
?>