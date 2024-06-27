<?
/*
	생성 : 2012.06.07
	수정 : 2012.09.10
	위치 : 게시판폴더
*/
	$b_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode . '&amp;bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;scate=' . $send_scate . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode"  value="' . $send_fmode . '" />
		<input type="hidden" name="smode"  value="' . $send_smode . '" />
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $sub_type == 'postform') || ($auth_menu['mod'] == 'Y' && $sub_type == 'modifyform')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$data = board_info_data('view', $set_table_name, $where);

		$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
		$file_list = board_file_data('list', $file_where, '', '', '');

	// 파일
		$file_query = "select max(sort) as sort from board_file where bs_idx = '" . $bs_idx . "' and b_idx = '" . $b_idx . "'";
		$file_chk = query_view($file_query);
		$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

		$file_upload_num = $file_chk['sort'];
		$file_chk_num    = $file_upload_num + 1;

	// 비밀글일 경우
		if ($set_secret_yn == "Y")
		{
			$form_secret = '
				<label for="post_secret_yn">
					<input type="checkbox" name="param[secret_yn]" id="post_secret_yn" value="Y"' . checked($data['secret_yn'], 'Y') . ' />비밀글(비공개)
				</label>';
		}

	// 게시물 공지
		$notice_where = " and bn.bs_idx = '" . $bs_idx . "' and bn.b_idx = '" . $b_idx . "'";
		$notice_data = board_notice_data("view", $set_table_name, $notice_where);
		$data['bn_idx'] = $notice_data["bn_idx"];
		if ($data['bn_idx'] == "") $data['notice_yn'] = "N";
		else $data['notice_yn'] = "Y";

	// 관리자일 경우 공지
		if ($sub_type == "postform" || $sub_type == "modifyform")
		{
			$form_notice = '
				<label for="post_notice_yn">
					<input type="checkbox" name="notice_yn" id="post_notice_yn" value="Y" ' . checked($data['notice_yn'], 'Y') . ' />공지
				</label>
				<input type="hidden" name="old_notice_yn" id="old_notice_yn" value="' . $data['notice_yn'] . '" />
				<input type="hidden" name="bn_idx"        id="bn_idx"        value="' . $data['bn_idx'] . '" />';
		}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<input type="hidden" name="set_table_name"  id="set_table_name"  value="<?=$set_table_name;?>" />

		<fieldset>
			<legend class="blind">게시물 폼</legend>
			<table class="tinytable write" summary="게시물 등록/수정합니다.">
			<caption>게시물</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
		<?
			if ($set_category_yn == 'Y') {
		?>
				<tr>
					<th><label for="post_bc_idx">말머리</label></th>
					<td>
						<div class="left">
							<select name="param[bc_idx]" id="post_bc_idx" title="말머리 입력하세요.">
								<option value="">::말머리선택::</option>
			<?
				foreach ($cate_list as $cate_k => $cate_data)
				{
					if (is_array($cate_data))
					{
			?>
								<option value="<?=$cate_data['bc_idx'];?>"<?=selected($cate_data['bc_idx'], $data['bc_idx']);?>><?=$cate_data['menu_name'];?></option>
			<?
					}
				}
			?>
							</select>
						</div>
					</td>
				</tr>
		<?
			}
		?>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
							<?=$form_secret;?>
							<?=$form_notice;?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">내용</label></th>
					<td>
						<div class="left textarea_span">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
	<?
		if ($set_file_yn == "Y")
		{
	?>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
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
										<a href="<?=$local_diir;?>/bizstory/board/download.php?wrf_idx=<?=$file_data['bf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
										<a href="javascript:void(0);" class="btn_con" onclick="file_multi_form_delete('<?=$file_data['bf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
									</li>
			<?
					}
				}
			?>
								</ul>
							</div>
						</div>
					</td>
				</tr>
	<?
		}
	?>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($b_idx == '') {
			?>
					<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big fl"><input type="submit" value="수정하기" /></span>
					<span class="btn_big fl"><input type="button" value="수정취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="b_idx"    value="<?=$b_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
			<?=$form_all;?>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work', '');

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

<?
	if ($set_category_yn == 'Y') {
?>
		chk_value = $('#post_bc_idx').val(); // 말머리
		chk_title = $('#post_bc_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

<?
	}
?>
		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
					<?
						$f_default1 = str_replace('&amp;', '&', $f_default);;
					?>
						location.href = '?<?=$f_default1;?>';
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
	}
?>
