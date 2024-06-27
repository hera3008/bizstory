<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$where = " and wc.wi_idx = '" . $wi_idx . "'";
	$list = work_comment_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 업무보고 읽음으로 표시 - 자기것은 제외
			work_report_comment_check($wi_idx, '', $data['wc_idx']);

			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="comment" id="comment_list_<?=$data['wc_idx'];?>">
	<div class="comment_info">
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
	if ($data['mem_idx'] == $code_mem || $code_level <= '11') {
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="comment_modify_form('open', '<?=$data['wc_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="comment_delete('<?=$data['wc_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div id="comment_modify_<?=$data['wc_idx'];?>" title="댓글수정"></div>

	<div class="comment_wrap" id="comment_view_<?=$data['wc_idx'];?>">
		<div class="comment_data">
			<?=$data['remark'];?>
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
//------------------------------------ 댓글 수정
	function comment_modify_form(form_type, wc_idx)
	{
		$("#commentlist_wc_idx").val(wc_idx);
		if (form_type == 'close')
		{
			$("#comment_modify_" + wc_idx).html('');
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#commentlistform').serialize(),
				success: function(msg) {
					$("#comment_modify_" + wc_idx).html(msg);
					myScroll.refresh();
				}
			});
		}
	}

//------------------------------------ 댓글 삭제
	function comment_delete(idx)
	{
		if (confirm("선택하신 댓글을 삭제하시겠습니까?"))
		{
			$('#commentlist_sub_type').val('delete');
			$('#commentlist_wc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
						comment_modify_form('close')
						comment_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>