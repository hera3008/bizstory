<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 등록/수정
*/
	$b_idx = $idx;

	$set_where = " and bs.bs_idx = '" . $bs_idx . "' and bs.view_yn = 'Y'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

// 관리자일 경우
	$set_auth_yn = "N";
	if ($_SESSION[$sess_str . "_ubstory_level"] == "1" || $_SESSION[$sess_str . "_ubstory_level"] == "11")
	{
		$set_auth_yn = "Y";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;scate=' . $send_scate;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

// 등록된 값
	$where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
	$data = pro_board_info_data('view', $set_board['name_db'], $where);

	if ($data['writer'] == '') $data['writer'] = $_SESSION[$sess_str . "_mem_name"];

// 말머리 - 답변글이 아닐 경우
	if ($set_board['category_yn'] == "Y" && $sub_type != "replyform")
	{
		$board_cate_where = " and bc.bs_idx = '" . $bs_idx . "' and bc.view_yn = 'Y'";
		$board_cate_list = pro_board_category_data("list", $board_cate_where, "", "", "");

		if ($data['bc_idx'] == "")
		{
			$cate_where = " and bc.bs_idx = '" . $bs_idx . "' and bc.view_yn = 'Y' and bc.default_yn = 'Y'";
			$cate_data = pro_board_category_data("view", $cate_where);

			$data['bc_idx'] = $cate_data['bc_idx'];
		}
		$cate_form = '
			<select name="param[bc_idx]" id="post_bc_idx" title="말머리를 선택하세요.">
				<option value=""> 말머리 선택 </option>';
		foreach ($board_cate_list as $k => $cate_data)
		{
			if (is_array($cate_data))
			{
				$menu_depth = $cate_data["menu_depth"] - 1;
				$empty_str = str_repeat("&#160;", $menu_depth * 4);
				$cate_form .= '
					<option value="' . $cate_data['bc_idx'] . '"' . selected($cate_data['bc_idx'], $data['bc_idx']) . '>' . $empty_str . $cate_data['menu_name'] . '</option>';
			}
		}
		$cate_form .= '
			</select>';
	}

// 첨부파일
	$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
	$file_list = pro_board_file_data('list', $file_where, '', '', '');

	$file_upload_num = $file_list['total_num'];
	$file_chk_num    = $file_upload_num + 1;

	$file_form_view = '
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td>
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view">';

	foreach ($file_list as $file_k => $file_data)
	{
		if (is_array($file_data))
		{
			$file_chk = $file_data['sort'];
			$fsize = $file_data['img_size'];
			$fsize = byte_replace($fsize);

			$file_form_view .= '
									<li id="file_fname_' . $file_chk . '_liview" class="org_file">
										<a href="' . $local_dir . '/cms/board_project/download.php?bf_idx=' . $file_data['bf_idx'] . '" title="' . $file_data['img_fname'] . ' 다운로드" class="fileicon">' . $file_data['img_fname'] .'  (' . $fsize .' )</a>
										<a href="javascript:void(0);" class="btn_con" onclick="file_multi_form_delete(\'' . $file_data['bf_idx'] . '\', \'' . $file_chk . '\')"><span>삭제</span></a>
									</li>';
		}
	}
	$file_form_view .= '
								</ul>
							</div>
						</div>
					</td>
				</tr>';

// 링크
	if ($set_board['link_yn'] == "Y")
	{
		for ($i = 1; $i <= $set_board['link_num']; $i++)
		{
			$link_where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $bdata_b_idx . "' and bl.sort = '" . $i . "'";
			$link_data  = pro_board_link_data("view", $link_where);

			if ($sub_type == "replyform") unset($link_data);

			$link_form_view .= '
				<tr>
					<th><label for="post_link_url' . $i . '">링크' . $i . '</label></th>
					<td>
						<div class="left">
							<input type="text" name="link_url' . $i . '" id="post_link_url' . $i . '" class="type_text" title="링크주소를 입력하세요." size="60" value="' . $link_data['link_url'] . '"  />
							<select name="link_target' . $i . '" id="post_link_target' . $i . '">
								<option value="_blank"' . selected($link_data['link_target'], '_blank') . '>새창</option>
								<option value="_self"' . selected($link_data['link_target'], '_self') . '>현재창</option>
							</select>
						</div>
					</td>
				</tr>';
		}
	}

// 비밀글일 경우
	if ($set_board['secret_yn'] == "Y")
	{
		$secret_form = '
			<label for="secret_yn"><input type="checkbox" name="param[secret_yn]" id="post_secret_yn" value="Y"' . checked($data['secret_yn'], 'Y') . ' />비밀글(비공개)</label>';
	}

// 관리자일 경우 공지
	if ($set_auth_yn == "Y" && ($sub_type == "postform" || $sub_type == "modifyform"))
	{
	// 게시물 공지
		$notice_where = " and bn.bs_idx = '" . $bs_idx . "' and bn.b_idx = '" . $b_idx . "'";
		$notice_data = pro_board_notice_data("view", $set_board['name_db'], $notice_where);
		$data['bn_idx'] = $notice_data["bn_idx"];
		if ($data['bn_idx'] == "") $data['notice_yn'] = "N";
		else $data['notice_yn'] = "Y";

		$notice_form = '
			<label for="notice_yn"><input type="checkbox" name="notice_yn" id="post_notice_yn" value="Y"' . checked($data['notice_yn'], "Y") . ' class="type_checkbox" />공지</label>
			<input type="hidden" name="old_notice_yn" id="old_notice_yn" value="' . $data['notice_yn'] . '" />
			<input type="hidden" name="bn_idx"        id="bn_idx"        value="' . $data['bn_idx'] . '" />';
	}

// 등록, 수정 버튼
	$write_button_view = '';
	if ($sub_type == 'replyform')
	{
		$data['subject'] = 'Re) ' . $data['subject'];
		$data['remark']  = '<br /><br />=======================================<br /><br />' . $data['remark'];

		$write_button_view = '
				<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
				<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = \'' . $this_page . '?' . $f_all . '\'" /></span>

				<input type="hidden" name="sub_type" value="post" />
				<input type="hidden" name="param[gno]"  value="' . $data['b_idx'] . '" />
				<input type="hidden" name="param[tgno]" value="' . $data['tgno'] . '" />
		';
	}
	else
	{
		if ($b_idx == '')
		{
			$write_button_view = '
				<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
				<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = \'' . $this_page . '?' . $f_all . '\'" /></span>

				<input type="hidden" name="sub_type" value="post" />
			';
		}
		else
		{
			$write_button_view = '
				<span class="btn_big fl"><input type="submit" value="수정하기" /></span>
				<span class="btn_big fl"><input type="button" value="수정취소" onclick="location.href = \'' . $this_page . '?' . $f_all . '\'" /></span>

				<input type="hidden" name="sub_type" value="modify" />
				<input type="hidden" name="b_idx"    value="' . $data['b_idx'] . '" />
			';
		}
	}
?>
<div class="ajax_write">
	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="comp_idx"    id="post_comp_idx"    value="<?=$client_comp;?>" />
			<input type="hidden" name="part_idx"    id="post_part_idx"    value="<?=$client_part;?>" />
			<input type="hidden" name="ci_idx"      id="post_ci_idx"      value="<?=$client_idx;?>" />
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
		<?
			if (file_exists($set_board['skin_path'] . "/write_form.php") == true) include $set_board['skin_path'] . "/write_form.php";
			else echo $set_board['skin_dir'] . "/write_form.php 지정한 파일이 없습니다.<br />";
		?>
			<?=$form_all;?>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
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

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

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
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
				<?
					$f_default1 = str_replace('&amp;', '&', $f_default);;
				?>
						location.href = '<?=$local_dir;?>/cms/board_project/board.php?<?=$f_default1;?>';
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