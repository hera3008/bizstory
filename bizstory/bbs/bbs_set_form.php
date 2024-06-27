<?
/*
	수정 : 2012.11.16
	위치 : 설정폴더 > 컨텐츠관리 > 게시판관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn = $company_set_data['part_yn'];
	$bs_idx      = $idx;

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
	if ($auth_menu['int'] == 'Y' && $bs_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $bs_idx != '') // 수정권한
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
		$where = " and bs.bs_idx = '" . $bs_idx . "'";
		$data = bbs_setting_data("view", $where);

		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;

		if ($data['skin_name']   == '') $data['skin_name']   = 'basic';
		if ($data["list_row"]    == '') $data["list_row"]    = "15";
		if ($data['view_yn']     == '') $data['view_yn']     = 'Y';
		if ($data["category_yn"] == '') $data["category_yn"] = "N";
		if ($data["reply_yn"]    == '') $data["reply_yn"]    = "N";
		if ($data["comment_yn"]  == '') $data["comment_yn"]  = "N";
		if ($data["link_yn"]     == '') $data["link_yn"]     = "N";
		if ($data["file_yn"]     == '') $data["file_yn"]     = "N";

		$skin_list = skin_list("bbs"); // 스킨목록
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
								<?=code_radio($set_use, "param[reply_yn]", "post_reply_yn", $data["reply_yn"]);?> * 추가할 예정
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
						<th><label for="post_part_add_idx">같이할 지사</label></th>
						<td colspan="5">
							<div class="left textarea_span">
					<?
						$table_name    = 'company_part';
						$table_where   = " and comp_idx = '" . $code_comp . "' and part_idx != '" . $code_part . "'";
						$table_order   = "part_name asc";
						$chk_name      = "part_add_idx";
						$chk_id        = "post_part_add_idx";
						$chk_value     = 'part_idx';
						$chk_value_str = 'part_name';
						$field_value   = explode(',', $data['part_add_idx']);
					?>
								<?=display_checkbox($table_name, $table_where, $table_order, $chk_name, $chk_id, $chk_value, $chk_value_str, $field_value);?>
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
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		oEditors.getById["post_remark_top"].exec("UPDATE_CONTENTS_FIELD", []);

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
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