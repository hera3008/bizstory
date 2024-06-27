<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'consult';
	include $mobile_path . "/header.php";

	$contents_title = '나의 상담';
	$today_date     = date('Y-m-d');

	$where  = " and cons.comp_idx = '" . $code_comp . "' and concat(',', cons.charge_idx, ',') like '%," . $code_mem . ",%'";
	$list = consult_info_data('list', $where, '', $page_num, $page_size);
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="consult_list" class="full sub list">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<ul id="thelist" class="bbs">
<?
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$list_data = consult_list_data($data['cons_idx'], $data);
?>
				<li class="barmenu2 loop">
					<strong class="date"><span><?=date_replace($list_data['reg_date'], 'm-d');?></span></strong>
					<strong class="gubun">[<?=$list_data['client_name'];?>]</strong>
					<?=$list_data['subject'];?>
					<?=$list_data['important_img'];?>
					<?=$list_data['total_file_str'];?>
					<?=$list_data['total_comment_str'];?>
					<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/consult_view.php?cons_idx=<?=$list_data['cons_idx'];?>'" class="arrow">
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
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon2"><span>일정</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon1"><span class="leave_type">나의정보</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
</body>
</html>