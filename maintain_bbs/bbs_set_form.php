<?
/*
	생성 : 2012.12.14
	수정 : 2012.12.14
	위치 : 총설정폴더 > 컨텐츠관리 > 게시판관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$bs_idx = $idx;

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
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and bs.bs_idx = '" . $bs_idx . "'";
		$data = comp_bbs_setting_data("view", $where);

		if ($data['skin_name']   == '') $data['skin_name']   = 'basic';
		if ($data["list_row"]    == '') $data["list_row"]    = "15";
		if ($data['view_yn']     == '') $data['view_yn']     = 'Y';
		if ($data["category_yn"] == '') $data["category_yn"] = "N";
		if ($data["reply_yn"]    == '') $data["reply_yn"]    = "N";
		if ($data["comment_yn"]  == '') $data["comment_yn"]  = "N";
		if ($data["link_yn"]     == '') $data["link_yn"]     = "N";
		if ($data["file_yn"]     == '') $data["file_yn"]     = "N";

		$skin_list = skin_list("maintain_bbs"); // 스킨목록
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
					<th><label for="post_subject">게시판 제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" value="<?=$data["subject"];?>" title="게시판 제목을 입력하세요." size="30" maxlength="50" class="type_text" />
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
					<th><label for="explanation">게시판 설명</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[explanation]" id="explanation" value="<?=$data["explanation"];?>" title="게시판 설명을 입력하세요." size="50" maxlength="100" class="type_text" />
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
					<th><label for="post_skin_name">스킨</label></th>
					<td>
						<div class="left">
							<select name="param[skin_name]" id="post_skin_name" class="type_select1">
					<?
						foreach ($skin_list as $k => $v)
						{
					?>
								<option value="<?=$v;?>" <?=selected($data["skin_name"], $v);?>><?=$v;?></option>
					<?
						}
					?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>말머리</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[category_yn]", "post_category_yn", $data["category_yn"]);?>
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
					<th>파일</th>
					<td colspan="3">
						<div class="left">
							<?=code_radio($set_use, "param[file_yn]", "post_file_yn", $data["file_yn"]);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark_top">내용</label></th>
					<td colspan="5">
						<div class="left textarea_span">
							<textarea name="param[remark_top]" id="post_remark_top" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark_top'];?></textarea>
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
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

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
		oEditors.getById["post_remark_top"].exec("UPDATE_CONTENTS_FIELD", []);

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
						list_data();
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