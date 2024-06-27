<?
/*
	수정 : 2012.10.09
	위치 : 설정관리 > 에이전트관리 > 상담게시판 > 상담게시판 - 보기 - 댓글 등록/수정폼
*/
	require_once "../common/set_info.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and consc.consc_idx = '" . $consc_idx . "'";
	$data = consult_comment_data('view', $where);

	/*
	$file_where = " and conscf.consc_idx = '" . $data['consc_idx'] . "'";
	$file_list = consult_comment_file_data('list', $file_where, '', '', '');

	$file_query = "select max(sort) as sort from consult_comment_file where consc_idx = '" . $consc_idx . "'";
	$file_chk = query_view($file_query);
	$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

	$file_upload_num = $file_chk['sort'];
	$file_chk_num    = $file_upload_num + 1;
	*/
	if ($sub_type == 'reply_form')
	{
		$file_upload_num = 0;
		$file_chk_num    = $file_upload_num + 1;
		$data['remark'] = '<br /><br />----------------------------------------------<br />' . $data['remark'];

		unset($file_list);
	}
?>

<div class="new_report">
	<form name="commentform" id="commentform" method="post" action="<?=$this_page;?>" onsubmit="return check_comment_form()">
		<input type="hidden" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" name="cons_idx" value="<?=$cons_idx;?>" />

		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" cols="50" rows="10" title="댓글내용을 입력하세요."><?=$data['remark'];?></textarea>

		</div>
		<div class="action">
	<?
		if ($consc_idx == '') {
	?>
			<span class="btn_big_green"><input type="submit" value="등록" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="comment_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		}
		else
		{
			if ($sub_type == 'reply_form')
			{
	?>
			<span class="btn_big_violet"><input type="submit" value="답변" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="comment_reply_form('close', '<?=$consc_idx;?>')" /></span>

			<input type="hidden" name="sub_type"   value="post" />
			<input type="hidden" name="consc_idx"  value="<?=$consc_idx;?>" />
			<input type="hidden" name="param[gno]" value="<?=$consc_idx;?>" />
	<?
			}
			else
			{
	?>
			<span class="btn_big_blue"><input type="submit" value="수정" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="comment_modify_form('close', '<?=$consc_idx;?>')" /></span>

			<input type="hidden" name="sub_type"  value="modify" />
			<input type="hidden" name="consc_idx" value="<?=$consc_idx;?>" />
	<?
			}
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
		var chk_total = '', chk_value = '', chk_title = '';

		//oEditors_comment.getById["commentpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#commentpost_remark').val(); // 내용
		chk_title = $('#commentpost_remark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						//file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환

						$('#comment_total_value').html(msg.total_num);
	<?
		if ($consc_idx == '') {
	?>
						comment_insert_form('close')
	<?
		} else {
	?>
						comment_modify_form('close','')
	<?
		}
	?>
						comment_list_data();
						//list_data();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
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