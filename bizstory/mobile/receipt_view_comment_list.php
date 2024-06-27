<?
/*
	생성 : 2012.09.11
	위치 : 접수댓글목록
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$where = " and rc.ri_idx = '" . $ri_idx . "'";
	$list = receipt_comment_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir);
?>
<div class="comment" id="comment_list_<?=$data['rc_idx'];?>">
	<div class="comment_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user"><a class="name_ui"><?=$data['writer'];?></a></span>
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

	<div class="comment_wrap" id="comment_view_<?=$data['rc_idx'];?>">
		<div class="comment_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and rcf.rc_idx = '" . $data['rc_idx'] . "'";
	$file_list = receipt_comment_file_data('list', $file_where, '', '', '');

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
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/bizstory/receipt/receipt_view_comment_download.php?rcf_idx=<?=$file_data['rcf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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