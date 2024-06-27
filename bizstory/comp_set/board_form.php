<?
/*
	수정 : 2012.06.08
	위치 : 설정폴더 > 컨텐츠관리 > 게시판관리 - 등록/수정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_table_name = 'board_biz_' . $company_id;
	$bs_idx         = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
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
	if (($auth_menu['int'] == 'Y' && $bs_idx == '') || ($auth_menu['mod'] == 'Y' && $bs_idx != '')) // 등록, 수정권한
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
		$where = " and bs.bs_idx = '" . $bs_idx . "'";
		$data = board_set_data("view", $where);

		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;

		if ($data['view_yn']     == '') $data['view_yn']     = 'Y';
		if ($data["list_row"]    == '') $data["list_row"]    = "15";
		if ($data["reply_yn"]    == '') $data["reply_yn"]    = "N";
		if ($data["secret_yn"]   == '') $data["secret_yn"]   = "N";
		if ($data["comment_yn"]  == '') $data["comment_yn"]  = "N";
		if ($data["category_yn"] == '') $data["category_yn"] = "N";
		if ($data["link_yn"]     == '') $data["link_yn"]     = "N";
		if ($data["link_num"]    == '') $data["link_num"]    = "1";
		if ($data["file_yn"]     == '') $data["file_yn"]     = "N";
		if ($data["file_num"]    == '') $data["file_num"]    = "2";
		if ($data["file_max"]    == '') $data["file_max"]    = "10";

		$gauth_w = explode(",", $data["gauth_w"]);
		$gauth_r = explode(",", $data["gauth_r"]);
		$gauth_d = explode(",", $data["gauth_d"]);
		$gauth_reply_w = explode(",", $data["gauth_reply_w"]);
		$gauth_reply_r = explode(",", $data["gauth_reply_r"]);
		$gauth_reply_d = explode(",", $data["gauth_reply_d"]);
		$gauth_comment_w = explode(",", $data["gauth_comment_w"]);
		$gauth_comment_r = explode(",", $data["gauth_comment_r"]);
		$gauth_comment_d = explode(",", $data["gauth_comment_d"]);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">게시판설정 폼</legend>
			<table class="tinytable write" summary="게시판설정을 등록/수정합니다.">
			<caption>게시판설정</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_part_idx">지사</label></th>
					<td>
						<div class="left">
							<?=company_part_select($data['part_idx'], '');?>
						</div>
					</td>
					<th>사용여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_subject">게시판 제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" value="<?=$data["subject"];?>" title="게시판 제목을 입력하세요." size="30" maxlength="50" class="type_text" />
						</div>
					</td>
					<th><label for="explanation">게시판 설명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[explanation]" id="explanation" value="<?=$data["explanation"];?>" title="게시판 설명을 입력하세요." size="30" maxlength="100" class="type_text" />
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="sub_frame"><h4>기능설정</h4></div>
			<table class="tinytable write" summary="게시판 기능설정을 등록/수정합니다.">
			<caption>게시판 기능설정</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_list_row">게시물수</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[list_row]" id="post_list_row" value="<?=$data["list_row"];?>" title="페이지당 게시물수을 입력하세요." size="3" maxlength="5" class="type_text" />
							* 0이거나 값이 없을 경우 한페이지에 나옴
						</div>
					</td>
					<th>말머리</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[category_yn]", "post_category_yn", $data["category_yn"]);?>
						</div>
					</td>
				</tr>
				<tr>
					<th>비밀글</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[secret_yn]", "post_secret_yn", $data["secret_yn"]);?>
						</div>
					</td>
					<th>답변글</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[reply_yn]", "post_reply_yn", $data["reply_yn"]);?>
						</div>
					</td>
					<th>댓글</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[comment_yn]", "post_comment_yn", $data["comment_yn"]);?>
						</div>
					</td>
				</tr>
				<tr>
					<th>링크</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[link_yn]", "post_link_yn", $data["link_yn"]);?>
						</div>
					</td>
					<th><label for="post_link_num">링크수</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" id="post_link_num" name="param[link_num]" value="<?=$data["link_num"];?>" title="링크수를 입력하세요." size="3" maxlength="5" class="type_text" />
						</div>
					</td>
				</tr>
				<tr>
					<th>파일</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[file_yn]", "post_file_yn", $data["file_yn"]);?>
						</div>
					</td>
					<th><label for="post_file_num">파일 개수</label></th>
					<td>
						<div class="left">
							<input type="text" id="post_file_num" name="param[file_num]"  value="<?=$data["file_num"];?>" title="파일 개수를 입력하세요." size="3" maxlength="5" class="type_text" /> 개
						</div>
					</td>
					<th><label for="post_file_max">파일 최대크기</label></th>
					<td>
						<div class="left">
							<input type="text" id="post_file_max" name="param[file_max]" value="<?=$data["file_max"];?>" title="업로드파일 최대크기를 입력하세요." size="3" maxlength="2" class="type_text" /> MB * 개당
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="sub_frame"><h4>권한설정</h4></div>
			<table class="tinytable write" summary="게시판 권한설정을 등록/수정합니다.">
			<caption>게시판 권한설정</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="lauth_w">게시물쓰기</label></th>
					<td>
						<div class="left">
					<?
						$table_name1 = 'company_part_duty';
						$table_where1 = "and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "'";
						$table_order1 = "sort asc";

						$table_name2 = 'company_staff_group';
						$table_where2 = "and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "'";
						$table_order2 = "sort asc";

						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_w]', 'post_lauth_w', 'cpd_idx', 'duty_name', $data["lauth_w"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_w', 'post_gauth_w', 'csg_idx', 'group_name', $gauth_w);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_r">게시물읽기</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_r]', 'post_lauth_r', 'cpd_idx', 'duty_name', $data["lauth_r"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_r', 'post_gauth_r', 'csg_idx', 'group_name', $gauth_r);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_d">게시물삭제</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_d]', 'post_lauth_d', 'cpd_idx', 'duty_name', $data["lauth_d"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_d', 'post_gauth_d', 'csg_idx', 'group_name', $gauth_d);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_reply_w">답변글쓰기</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_reply_w]', 'post_lauth_reply_w', 'cpd_idx', 'duty_name', $data["lauth_reply_w"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_reply_w', 'post_gauth_reply_w', 'csg_idx', 'group_name', $gauth_reply_w);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_reply_r">답변글읽기</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_reply_r]', 'post_lauth_reply_r', 'cpd_idx', 'duty_name', $data["lauth_reply_r"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_reply_r', 'post_gauth_reply_r', 'csg_idx', 'group_name', $gauth_reply_r);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_reply_d">답변글삭제</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_reply_d]', 'post_lauth_reply_d', 'cpd_idx', 'duty_name', $data["lauth_reply_d"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_reply_d', 'post_gauth_reply_d', 'csg_idx', 'group_name', $gauth_reply_d);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_comment_w">댓글쓰기</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_comment_w]', 'post_lauth_comment_w', 'cpd_idx', 'duty_name', $data["lauth_comment_w"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_comment_w', 'post_gauth_comment_w', 'csg_idx', 'group_name', $gauth_comment_w);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_comment_r">댓글읽기</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_comment_r]', 'post_lauth_comment_r', 'cpd_idx', 'duty_name', $data["lauth_comment_r"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_comment_r', 'post_gauth_comment_r', 'csg_idx', 'group_name', $gauth_comment_r);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="lauth_comment_d">댓글삭제</label></th>
					<td>
						<div class="left">
					<?
						echo display_select($table_name1, $table_where1, $table_order1, 'param[lauth_comment_d]', 'post_lauth_comment_d', 'cpd_idx', 'duty_name', $data["lauth_comment_d"], "직책선택");
						echo display_checkbox($table_name2, $table_where2, $table_order2, 'gauth_comment_d', 'post_gauth_comment_d', 'csg_idx', 'group_name', $data["gauth_comment_d"]);
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark_top">문구</label></th>
					<td>
						<div class="left">
							<textarea name="param[remark_top]" id="post_remark_top" title="내용을 입력하세요."><?=$data["remark_top"];?></textarea>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($bs_idx == '') {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="bs_idx"   value="<?=$bs_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark_top",
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

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
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
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
				<?
					$f_default1 = str_replace('&amp;', '&', $f_default);;
				?>
						location.href = '?<?=$f_default1;?>';
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
	}
?>