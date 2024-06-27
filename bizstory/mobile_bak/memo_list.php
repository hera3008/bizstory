<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$bbs_title  = '메모장';
	$today_date = date('Y-m-d');

	$where = "
		and mm.comp_idx = '" . $code_comp . "'
		and mm.mem_idx = '" . $code_mem . "'";
	$list = member_memo_data('list', $where, '', '', '');
?>
<!-- Memo List -->
<div id="memo_list" class="homebox full sub list css_memo">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$bbs_title;?></a>
		</h1>
		<?=$btn_menu;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">
			<!-- List -->
			<ul class="partnet memo">
<?
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$remark = string_cut($data['remark'], 30);
?>
				<li class="barmenu2 loop">
					<strong class="subject"><?=$remark;?></strong>
					<strong class="date"><span><?=$data['reg_date'];?></span></strong>
					<a href="javascript:window.location.href='<?=$mobile_dir;?>/memo_view.php?mm_idx=<?=$data['mm_idx'];?>'" class="arrow">
						<em></em>
					</a>
				</li>
<?
		}
	}
?>
			</ul>
		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>메모작성</span></a>
			<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>