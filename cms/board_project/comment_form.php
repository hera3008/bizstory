<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 보기 - 등록/수정폼
*/
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$where = " and bco.bco_idx = '" . $bco_idx . "'";
	$data = pro_board_comment_data('view', $where);

	$file_where = " and bcof.bco_idx = '" . $data['bco_idx'] . "'";
	$file_list = pro_board_comment_file_data('list', $file_where, '', '', '');

	$file_query = "select max(sort) as sort from pro_board_comment_file where bco_idx = '" . $bco_idx . "'";
	$file_chk = query_view($file_query);
	$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

	$file_upload_num = $file_chk['sort'];
	$file_chk_num    = $file_upload_num + 1;
?>
<div class="new_report">
	<form name="commentform" id="commentform" method="post" action="<?=$this_page;?>" onsubmit="return check_comment_form()">
		<input type="hidden" name="comp_idx" id="post_comp_idx" value="<?=$client_comp;?>" />
		<input type="hidden" name="part_idx" id="post_part_idx" value="<?=$client_part;?>" />
		<input type="hidden" name="ci_idx"   id="post_ci_idx"   value="<?=$client_idx;?>" />
		<input type="hidden" name="bs_idx"   id="post_bs_idx"   value="<?=$bs_idx;?>" />
		<input type="hidden" name="b_idx"    id="post_b_idx"    value="<?=$b_idx;?>" />
		<input type="hidden" name="bco_idx"  id="post_bco_idx"  value="<?=$bco_idx;?>" />
		<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" cols="50" rows="10" title="댓글내용을 입력하세요."><?=$data['remark'];?></textarea>

			<div class="filewrap">
				<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
				<div class="file">
					<ul id="file_fname_view">
<?
	foreach ($file_list as $file_k => $file_data)
	{
		if (is_array($file_data))
		{
			$file_chk = $file_data['sort'];
			$fsize = $file_data['img_size'];
			$fsize = byte_replace($fsize);
?>
					<li id="file_fname_<?=$file_chk;?>_liview" class="org_file">
						<a href="<?=$local_diir;?>/cms/board_project/comment_download.php?bcof_idx=<?=$file_data['bcof_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
						<a href="javascript:void(0);" class="btn_con" onclick="sub_file_delete('<?=$file_data['bcof_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
					</li>
<?
		}
	}
?>
					</ul>
				</div>
			</div>
		</div>
		<div class="action">
	<?
		if ($bco_idx == '') {
	?>
			<span class="btn_big"><input type="submit" value="등록" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="comment_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn_big"><input type="submit" value="수정" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="comment_modify_form('close', '<?=$bco_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="bco_idx"  value="<?=$bco_idx;?>" />
	<?
		}
	?>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'pro_board_comment', '');

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
			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
	<?
		if ($bco_idx == '') {
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
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 폼에서 파일삭제
	function sub_file_delete(idx, sort)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: comment_ok,
				data: {'sub_type':'file_delete', 'idx':idx},
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
						$('#file_fname_' + sort + '_liview').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}
//]]>
</script>