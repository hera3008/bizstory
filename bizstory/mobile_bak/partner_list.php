<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$client_where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	$client_list = client_info_data('list', $client_where, '', '', '');
?>
	<div id="bbs_list" class="homebox full sub list">

		<div class="toolbar han">
			<?=$btn_back;?>
			<h1>
				<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'">거래처 리스트</a>
			</h1>
		<?=$btn_logout;?>
		</div>

		<!-- Contents -->
		<div id="wrapper">
			<div id="scroller">
				<ul class="partnet">
	<?
		foreach ($client_list as $client_k => $client_data)
		{
			if (is_array($client_data))
			{
				$charge_info = $client_data['charge_info'];
				$charge_info_arr = explode('||', $charge_info);
				$info_str = explode('/', $charge_info_arr[0]);

				if ($client_data['tel_num'] != '--' && $client_data['tel_num'] != '-' && $client_data['tel_num'] != '') $tel_num_str = $client_data['tel_num'];
				else $tel_num_str = '';
	?>
					<li class="barmenu2 loop">
						<strong class="subject"><?=$client_data['client_name'];?><span><?=$info_str[0];?> </span></strong>
						<strong class="date"><span><?=$tel_num_str;?></span></strong>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/partner_view.php?ci_idx=<?=$client_data['ci_idx'];?>'" class="arrow">
							<em class="push"></em>
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
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon2"><span>일정</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon1"><span class="leave_type">나의정보</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>