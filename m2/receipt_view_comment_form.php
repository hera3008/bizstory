<?
/*
	수정 : 2012.07.09
	위치 : 고객관리 > 접수목록 - 보기 - 댓글 등록/수정폼
*/
	require_once "../common/set_info.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and rc.rc_idx = '" . $rc_idx . "'";
	$data = receipt_comment_data('view', $where);
?>

<div class="new_report">
	<form name="commentform" id="commentform" method="post" action="<?=$this_page;?>" onsubmit="return check_comment_form()">
		<input type="hidden" name="ri_idx" value="<?=$ri_idx;?>" />

		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" style="width:100%" rows="10" title="댓글내용을 입력하세요." placeholder="코멘트내용을 입력하세요."><?=$data['remark'];?></textarea>
		<div class="action">
	<?
		if ($rc_idx == '') {
	?>
			<span class="btn01"><input type="submit" value="등록" /></span>
			<span class="btn08"><input type="button" value="취소" onclick="comment_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn07"><input type="submit" value="수정" /></span>
			<span class="btn08"><input type="button" value="취소" onclick="comment_modify_form('close', '<?=$rc_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="rc_idx"   value="<?=$rc_idx;?>" />
	<?
		}
	?>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[

//------------------------------------ 댓글등록/수정
	function check_comment_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		//oEditors_comment.getById["commentpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#commentpost_remark').val(); // 내용
		chk_title = $('#commentpost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						//file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환

						$('#comment_total_value').html(msg.total_num);
	<?
		if ($rc_idx == '') {
	?>
						comment_insert_form('close');
	<?
		} else {
	?>
						comment_modify_form('close','');
	<?
		}
	?>
						comment_list_data();
						//list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>
<?
	db_close();
?>