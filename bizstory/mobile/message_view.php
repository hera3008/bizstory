<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'mag_view';
	include $mobile_path . "/header.php";

	$contents_title = '쪽지내용';

	$where = " and mr.mr_idx = '" . $mr_idx . "'";
	$data = message_receive_data('view', $where);
?>
<div id="bbs_view" class="homebox full sub view">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/message_view.php?mr_idx=<?=$mr_idx;?>'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

				<table border="1" cellspacing="0" class="board-list" summary="쪽지내용">
				<caption><?=$contents_title;?> 콘텐츠</caption>
				<colgroup>
					<col width="90px" />
					<col />
					<col width="90px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>보낸사람</th>
						<td><div class="left"><?=$data['send_mem_name'];?></div></td>
						<th>보낸일</th>
						<td><div class="left"><?=date_replace($data['reg_date'], 'Y-m-d H:i:s');?></div></td>
					</tr>
					<tr>
						<th>읽은일</th>
						<td colspan="3"><div class="left"><?=date_replace($data['read_date'], 'Y-m-d H:i:s');?></div></td>
					</tr>
					<tr>
						<td colspan="4"><div class="left"><p class="memo"><?=$data['remark'];?></p></div></td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td colspan="3">
				<?
					$file_where = " and msgf.ms_idx = '" . $data['ms_idx'] . "'";
					$file_list = message_file_data('list', $file_where, '', '', '');

					if ($file_list['total_num'] > 0) {
				?>
							<ul>
				<?
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
				?>
								<li>
									<?=$file_data['img_fname'];?>
								</li>
				<?
							}
						}
				?>
							</ul>
				<?
					}
				?>
						</td>
					</tr>
				</tbody>
				</table>

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