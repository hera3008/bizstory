<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/m/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$where = " and wr.wi_idx = '" . $wi_idx . "'";
	$list = work_report_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 업무보고 읽음으로 표시 - 자기것은 제외
			work_report_comment_check($wi_idx, $data['wr_idx'], '');

			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="report">
	<div class="report_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user"><?=$data['writer'];?></span>
		<span class="date">
<?
			$chk_date = date_replace($data['reg_date'], 'Y-m-d');
			if ($chk_date == date('Y-m-d'))
			{
				echo '<strong>', $data['reg_date'] , '</strong>';
			}
			else
			{
				echo $data['reg_date'];
			}
?>
		</span>
	</div>

	<div class="report_wrap">
		<div class="report_data">
			<?=$data['remark'];?>
			<div class="file">
<?
	$file_where = " and wrf.wr_idx = '" . $data['wr_idx'] . "'";
	$file_list = work_report_file_data('list', $file_where, '', '', '');
	if ($file_list['total_num'] > 0) {
?>
				<ul>
<?
		foreach ($file_list as $file_k => $file_data)
		{
			if (is_array($file_data))
			{
				$fsize = $file_data['img_size'];
				$fsize = byte_replace($fsize);
?>
					<li>
						<?=$file_data['img_fname'];?> (<?=$fsize;?>)
					</li>
<?
			}
		}
?>
				</ul>
<?
	}
?>
			</div>
		</div>
	</div>

</div>
<?
			$num--;
		}
	}
?>
