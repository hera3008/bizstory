<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'work_my';
	include $mobile_path . "/header.php";

	$contents_title = '나의 업무';
	$today_date     = date('Y-m-d');

	$where  = " and wi.comp_idx = '" . $code_comp . "'";
	$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
	$orderby = "
		 if (ws.code_value = 'WS90'
			, if (wi.end_date = '0000-00-00', '9999-12-31', wi.end_date)
			, if (ws.code_value = 'WS80'
				, '9000-12-31'
				, if (ws.code_value = 'WS01'
					, '9001-12-31'
					, if (ws.code_value = 'WS20'
						, '9002-12-31'
						, '9003-12-31'
					)
				)
			)
		) desc
		, if (datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date)) < 0
			, 0
			, datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date))
		) desc
		, wi.reg_date desc
	";
	$list = work_info_data('list', $where, $orderby, $page_num, $page_size);
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="work_list" class="full sub list">

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
			$list_data = work_list_data($data, $data['wi_idx']);
?>
				<li class="barmenu2 loop">
					<strong class="date"><span><?=$list_data['deadline_date_mobile'];?></span></strong>
					<?=$list_data['work_img'];?>
					<?=$list_data['part_img'];?>
					<?=$list_data['subject_txt'];?>
					<?=$list_data['important_img'];?>
					<?=$list_data['open_img'];?>
					<?=$list_data['file_str'];?>
					<?=$list_data['report_str'];?>
					<?=$list_data['comment_str'];?>
					<?=$list_data['new_img'];?>
					<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_view.php?wi_idx=<?=$data['wi_idx'];?>'" class="arrow">
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
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/work_my_list.php\'" class="icon2"><span>나의업무</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>업무등록</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
</body>
</html>