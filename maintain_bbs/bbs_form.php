<?
/*
	생성 : 2012.12.14
	수정 : 2013.05.21
	위치 : 게시판 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$b_idx = $idx;

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_bbs = comp_bbs_setting_data("view", $set_where);
	if ($set_bbs["total_num"] > 0)
	{
	// 관리자일 경우
		$set_bbs["auth_yn"] = "N";
		If ($code_level >= 1 && $code_level <= 11 && $code_mem != "")
		{
			$set_bbs["auth_yn"] = "Y";
		}

	// 게시판설정값
		foreach($set_bbs as $key => $value)
		{
			$key  = "set_" . $key;
			$$key = $value;
		}
	}

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
	if ($auth_menu['int'] == 'Y' && $b_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $b_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		$where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$data = comp_bbs_info_data('view',  $where);

		$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
		$file_list = comp_bbs_file_data('list', $file_where, '', '', '');

	// 링크
		$link_where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "'";
		$link_list = comp_bbs_link_data('list', $link_where, '', '', '');

	// 파일
		$file_query = "select max(sort) as sort from comp_bbs_file where bs_idx = '" . $bs_idx . "' and b_idx = '" . $b_idx . "'";
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

	// 관리자일 경우 공지
		if ($set_auth_yn == "Y")
		{
			if ($data['bn_idx'] == "") $data['notice_yn'] = "N";
			else $data['notice_yn'] = "Y";

			$form_notice = '
				<label for="post_notice_yn">
					<input type="checkbox" name="notice_yn" id="post_notice_yn" value="Y" ' . checked($data['notice_yn'], 'Y') . ' />공지
				</label>
				<input type="hidden" name="old_notice_yn" id="old_notice_yn" value="' . $data['notice_yn'] . '" />
				<input type="hidden" name="bn_idx"        id="bn_idx"        value="' . $data['bn_idx'] . '" />';
		}

	// 말머리 목록
		$cate_where = " and bc.bs_idx = '" . $bs_idx . "' and bc.view_yn = 'Y'";
		$cate_list = comp_bbs_category_data('list', $cate_where, '', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />

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
								<select name="param[bc_idx]" id="post_bc_idx" title="말머리를 선택하세요.">
									<option value="">말머리선택</option>
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
											<a href="<?=$local_diir;?>/bizstory/bbs/bbs_download.php?bf_idx=<?=$file_data['bf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
		<?
			if ($set_link_yn == "Y")
			{
		?>
					<tr>
						<th><label for="post_link_url1">링크</label></th>
						<td colspan="3">
							<div class="left" id="link_data_view">
		<?
				$link_num = 1;
				foreach ($link_list as $link_k => $link_data)
				{
					if (is_array($link_data))
					{
		?>
								<div class="left">
									링크명 : <input type="text" name="link_param[link_name_<?=$link_num;?>]" id="post_link_name_<?=$link_num;?>" class="type_text" title="링크명을 입력하세요." size="20" value="<?=$link_data['link_name'];?>" />
									링크주소 : <input type="text" name="link_param[link_url_<?=$link_num;?>]" id="post_link_url_<?=$link_num;?>" class="type_text" title="링크주소를 입력하세요." size="50" value="<?=$link_data['link_url'];?>" />
									<select name="link_param[link_target_<?=$link_num;?>]" id="post_link_target_<?=$link_num;?>" title="링크타겟을 선택하세요.">
										<option value="_blank" <?=selected($link_data['link_target'], '_blank');?>>_blank</option>
										<option value="_self"  <?=selected($link_data['link_target'], '_self');?>>_self</option>
									</select>
								</div>
		<?
						$link_num++;
					}
				}
		?>
								<div class="left">
									링크명 : <input type="text" name="link_param[link_name_<?=$link_num;?>]" id="post_link_name_<?=$link_num;?>" class="type_text" title="링크명을 입력하세요." size="20" value="" />
									링크주소 : <input type="text" name="link_param[link_url_<?=$link_num;?>]" id="post_link_url_<?=$link_num;?>" class="type_text" title="링크주소를 입력하세요." size="50" value="" />
									<select name="link_param[link_target_<?=$link_num;?>]" id="post_link_target_<?=$link_num;?>" title="링크타겟을 선택하세요.">
										<option value="_blank">_blank</option>
										<option value="_self">_self</option>
									</select>
									<a href="javascript:void(0)" onclick="add_link()" class="btn_con_violet" title="링크추가">추가</a>
								</div>
								<input type="hidden" name="link_num" id="post_link_num" value="<?=$link_num;?>" />
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
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

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
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'bbs', '');

//------------------------------------ 파일추가
	function add_link()
	{
		var add_idx = $('#post_link_num').val();
		add_idx = parseInt(add_idx) + 1;

		var str = '';
		str = str + '<div class="left">';
		str = str + '	링크명 : <input type="text" name="link_param[link_name_' + add_idx + ']" id="post_link_name_' + add_idx + '" class="type_text" title="링크명을 입력하세요." size="20" />';
		str = str + '	링크주소 : <input type="text" name="link_param[link_url_' + add_idx + ']" id="post_link_url_' + add_idx + '" class="type_text" title="링크주소를 입력하세요." size="50" />';
		str = str + '	<select name="link_param[link_target_' + add_idx + ']" id="post_link_target_' + add_idx + '" title="링크타겟을 선택하세요.">';
		str = str + '		<option value="_blank">_blank</option>';
		str = str + '		<option value="_self">_self</option>';
		str = str + '	</select>';
		str = str + '</div>';


		$("#link_data_view").append(str);
		$('#post_link_num').val(add_idx);
	}

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
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

<?
	}
?>
		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
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
			//$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						close_data_form();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
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
