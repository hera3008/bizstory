<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'msg';
	include $mobile_path . "/header.php";

	$contents_title = '받은쪽지';

	$where = "and mr.comp_idx = '" . $code_comp . "' and mr.mem_idx = '" . $code_mem . "'";
	$list = message_receive_data('list', $where, '', $page_num, $page_size);
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="memo_list" class="homebox full sub list css_memo">

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

			<ul id="thelist" class="partnet memo">
<?
	if ($list["total_num"] == 0) {
?>
				<li class="barmenu">
					받은 쪽지가 없습니다.
				</li>
<?
	}
	else
	{
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$remark = strip_tags($data["remark"]);
				$remark = string_cut($remark, 30);
				if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
				{
					$remark = $remark;
				}
				else
				{
					$remark = '<span">' . $remark . '</span>';
				}
?>
				<li class="barmenu2 loop">
					<strong class="subject"><?=$remark;?></strong>
					<strong class="date"><span><?=$data['reg_date'];?></span></strong>
					<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/message_view.php?mr_idx=<?=$data['mr_idx'];?>'" class="arrow">
						<em class="push"></em>
					</a>
				</li>
<?
				$num--;
				$i++;
			}
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