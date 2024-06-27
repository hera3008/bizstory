<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 보기 - 댓글등록/답변
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

	$where = " and consc.consc_idx = '" . $consc_idx . "'";
	$data = consult_comment_data('view', $where);

	$file_where = " and conscf.consc_idx = '" . $data['consc_idx'] . "'";
	$file_list = consult_comment_file_data('list', $file_where, '', '', '');

	$file_query = "select max(sort) as sort from consult_comment_file where consc_idx = '" . $consc_idx . "'";
	$file_chk = query_view($file_query);
	$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

	$file_upload_num = $file_chk['sort'];
	$file_chk_num    = $file_upload_num + 1;

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
		<input type="hidden" name="comp_idx" value="<?=$client_comp;?>" />
		<input type="hidden" name="part_idx" value="<?=$client_part;?>" />
		<input type="hidden" name="cons_idx" value="<?=$cons_idx;?>" />
		<input type="hidden" name="file_upload_num"   id="file_upload_num"    value="<?=$file_upload_num;?>" />
		<input type="hidden" name="param[writer]"     id="commentpost_writer" value="" />
		<input type="hidden" name="param[macaddress]" id="post_macaddress"    value="<?=$macaddress;?>" />

		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" cols="50" rows="10" title="댓글내용을 입력하세요."><?=$data['remark'];?></textarea>

			<div class="filewrap">
				<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
				<div class="file">
					<ul id="file_fname_view">
<?
	if (is_array($file_list))
	{
		foreach ($file_list as $file_k => $file_data)
		{
			if (is_array($file_data))
			{
				$file_chk = $file_data['sort'];
				$fsize = $file_data['img_size'];
				$fsize = byte_replace($fsize);
?>
					<li id="file_fname_<?=$file_chk;?>_liview" class="org_file">
						<a href="<?=$local_diir;?>/agent/consult_view_comment_download.php?conscf_idx=<?=$file_data['conscf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
						<a href="javascript:void(0);" class="btn_con" onclick="sub_file_delete('<?=$file_data['conscf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
					</li>
<?
			}
		}
	}
?>
					</ul>
				</div>
			</div>

		</div>
		<div class="action">
	<?
		if ($consc_idx == '') {
	?>
			<span class="btn_big"><input type="submit" value="등록" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="comment_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		}
		else
		{
			if ($sub_type == 'reply_form')
			{
	?>
			<span class="btn_big"><input type="submit" value="답변" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="comment_reply_form('close', '<?=$consc_idx;?>')" /></span>

			<input type="hidden" name="sub_type"   value="post" />
			<input type="hidden" name="consc_idx"  value="<?=$consc_idx;?>" />
			<input type="hidden" name="param[gno]" value="<?=$consc_idx;?>" />
	<?
			}
			else
			{
	?>
			<span class="btn_big"><input type="submit" value="수정" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="comment_modify_form('close', '<?=$consc_idx;?>')" /></span>

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
	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'consult_comment', '');

	// 에디터관련
	var oEditors_comment = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_comment,
		elPlaceHolder: "commentpost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
			}
		},
		fCreator: "createSEditor2"
	});

	$('#commentpost_writer').val($.cookie('consult_comment_writer_save'));

//------------------------------------ 댓글등록/수정
	function check_comment_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		oEditors_comment.getById["commentpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
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
			chk_value = $('#commentpost_writer').val(); // 작성자
			$.cookie('consult_comment_writer_save', chk_value, { expires: 7 });

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환

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
						list_data();
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