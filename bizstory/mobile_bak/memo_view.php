<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$bbs_title = '메모상세';

	$where = "and mm.mm_idx = '" . $mm_idx . "'";
	$data = member_memo_data('view', $where);
?>
<!-- BBS Write -->
<div id="bbs_write" class="homebox full sub write">

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

				<form name="momewrite" class="basic memo" Onsubmit="return chk_mome()" method="post" action="./">
					<strong class="top_date"><span><?=$data['reg_date'];?></span></strong>
					<ul>
						<li>
							<textarea name="memo" id="memo" rows="13"><? echo nl2br($data['remark']); ?></textarea>
						</li>
					</ul>
				</form>

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