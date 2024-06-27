<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'receipt';
	include $mobile_path . "/header.php";

	$contents_title = '접수목록';

	$where = " and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'";
	if ($list_type == 'list_no')
	{
		$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
	}
	$list = receipt_info_data('list', $where, '', $page_num, $page_size);
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="receipt_list" class="full sub list">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_menu;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<div id="pullDown">
				<span class="pullDownIcon"></span>
				<span class="pullDownLabel">Pull down to refresh...</span>
			</div>

			<ul id="thelist" class="bbs">
<?
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$list_data = receipt_list_data($data['ri_idx'], $data);
?>
				<li class="barmenu2 loop">
						<strong class="date"><span><?=date_replace($data['reg_date'], 'm-d');?></span></strong>
						<strong class="date"><?=$list_data['receipt_status_str'];?></strong>
						<strong class="gubun">[<?=$data['client_name'];?>]</strong>
						<?=$list_data['subject'];?>
		<?
			if ($list_data['total_file'] > 0)
			{
				echo '
						<span class="attach" title="첨부파일">', number_format($list_data['total_file']), '</span>';
			}
			if ($list_data['total_comment'] > 0)
			{
				echo '
						<span class="cmt" title="코멘트">', number_format($list_data['total_comment']), '</span>';
			}

			if ($read_work > 0)
			{
				echo '
						<span class="today_num" title="읽을 코멘트"><em>', number_format($read_work), '</em></span>';
			}
?>
					<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/receipt_view.php?ri_idx=<?=$data['ri_idx'];?>'" class="arrow" target="_webapp">
						<em class="push"></em>
					</a>
				</li>
<?
		}
	}
?>
			</ul>

			<div id="pullUp">
				<span class="pullUpIcon"></span>
				<span class="pullUpLabel">Pull up to refresh...</span>
			</div>

		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php\'" class="icon2"><span>접수목록</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php?list_type=list_no\'" class="icon2"><span>미처리</span></a>
			<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
</body>
</html>