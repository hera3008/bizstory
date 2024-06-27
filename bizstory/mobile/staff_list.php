<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "' and mem.login_yn = 'Y'";
	$order = "mem.mem_name asc";
	$list = member_info_data('list', $where, $order, $page_num, $page_size);
?>
<!-- BBS List -->
<div id="bbs_list" class="homebox full sub list">

	<!-- Toolbar -->
	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$local_dir;?>/index.php'">직원 리스트</a>
		</h1>
		<?=$btn_menu;?>
	</div>
	<!-- //Toolbar -->

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

				<ul class="bbs">
	<?
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
	?>
					<li class="barmenu">
						<a href="javascript:void(0)" class="loop">
							<strong><?=$data["mem_name"];?></strong>
							<span><?=$data['hp_num'];?></span>
						</a>
					</li>
	<?
			}
		}
	?>
				</ul>

			</div>
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