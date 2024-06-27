<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$where = " and wr.wi_idx = '" . $wi_idx . "'";
	$list = work_report_data('list', $where, '', '', '');

	$work_where = " and wi.wi_idx = '" . $wi_idx . "'";
	$work_data = work_info_data('view', $work_where);

	$work_data = work_list_data($data, $wi_idx); // 작업내용

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$sub_where = " and mem.mem_idx = '" . $data['mem_idx'] . "'";
			$sub_data = member_info_data('view', $sub_where);

		// 업무보고 읽음으로 표시 - 자기것은 제외
			work_report_comment_check($wi_idx, $data['wr_idx'], '');

			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="report" id="report_list_<?=$data['wr_idx'];?>">
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
<?
	if ($work_data['report_yn'] == 'Y')
	{
		if ($data['mem_idx'] == $code_mem || $code_level <= '11')
		{
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="report_modify_form('open', '<?=$data['wr_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="report_delete('<?=$data['wr_idx'];?>')"></a>
<?
		}
	}
?>
	</div>

	<div id="report_modify_<?=$data['wr_idx'];?>" title="업무보고수정"></div>

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
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 업무보고서 수정
	function report_modify_form(form_type, wr_idx)
	{
		report_insert_form('close', 'modify');

		$("#reportlist_wr_idx").val(wr_idx);
		if (form_type == 'close')
		{
			$("#report_modify_" + wr_idx).html('');
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: report_form,
				data: $('#reportlistform').serialize(),
				success: function(msg) {
					$("#report_modify_" + wr_idx).html(msg);
					myScroll.refresh();
				}
			});
		}
	}

//------------------------------------ 업무보고서 삭제
	function report_delete(idx)
	{
		if (confirm("선택하신 보고서를 삭제하시겠습니까?"))
		{
			$('#reportlist_sub_type').val('delete');
			$('#reportlist_wr_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: report_ok,
				data: $('#reportlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#report_total_value').html(msg.total_num);
						report_modify_form('close')
						report_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>