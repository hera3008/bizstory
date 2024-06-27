<?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'si.sort';
	if ($sorder2 == '') $sorder2 = 'asc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $send_page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $send_page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/maintain/service_default_list.php";      // 목록
	$link_ok           = $local_dir . "/bizstory/maintain/service_default_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/maintain/service_default_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/maintain/service_default_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/maintain/service_default_print_sel.php"; // 상세인쇄

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="' . $this_page . '?sub_type=postform&amp;' . $f_all . '" class="btn_big_green"><span>서비스등록</span></a>';
	}

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel(\'' . $link_excel . '\')"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print(\'' . $link_print . '\')"><span><em class="print"></em>인쇄</span></a>';
		$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print_detail(\'' . $link_print_detail . '\')"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼을 선택해 주세요.';
	$search_keyword = '검색할 단어를 입력해주세요.';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록
	if ($sub_type == "")
	{
	// 검색
		$where = '';
		if ($stext != '' && $swhere != '')
		{
			$where .= " and " . $swhere . " like '%" . $stext . "%'";
		}
		$page_data = service_info_data('page', $where, '', '', $page_size);
?>
	<div class="tablewrapper">

		<div id="tableheader">
			<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
				<?=$form_default;?>
				<div class="search">
					<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
						<option value=""><?=$search_column;?></option>
						<option value="si.subject"<?=selected($swhere, 'si.subject');?>>서비스명</option>
					</select>
					<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = '<?=$search_keyword;?>';}" onfocus="if (this.value == '<?=$search_keyword;?>') {this.value = '';}" />
					<a href="javascript:void(0);" class="btn_sml fl" onclick="check_search()"><span>검색</span></a>
					<?=$btn_down;?>
					<?=$btn_print;?>
					<?=$btn_print_sel;?>
				</div>
				<div class="etc_bottom">
					<?=$btn_write;?>
				</div>
			</form>
		</div>

		<div class="details">
			<div>Records <?=$page_data['total_num'];?> / Total Pages <?=$page_data['total_page'];?></div>
		</div>
		<hr />

		<form id="listform" name="listform" action="<?=$this_page;?>" method="post">
			<?=$form_page;?>
			<input type="hidden" id="list_si_idx"     name="si_idx"     value="" />
			<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
			<input type="hidden" id="list_sub_action" name="sub_action" value="" />
			<input type="hidden" id="list_post_value" name="post_value" value="" />

			<div id="data_list"></div>

			<div id="tablefooter">
				<?=page_view($page_size, $page_num, $page_data['total_page']);?>
			</div>
			<hr />

		</form>

	</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 검색
	function check_search()
	{
		$("#popup_notice_view").hide();

		var chk_msg = '', chk_total = '';

		var swhere       = $('#search_swhere').val();
		var swhere_title = $('#search_swhere').attr('title');

		chk_msg = check_input_value(swhere);
		if (chk_msg == 'No') chk_total = chk_total + swhere_title + '<br />';

		var stext        = $('#search_stext').val();
		var stext_title  = $('#search_stext').attr('title');

		chk_msg = check_input_value(stext);
		if (chk_msg == 'No' || stext == stext_title) chk_total = chk_total + stext_title + '<br />';

		if (chk_total == '')
		{
			document.listform.swhere.value = swhere;
			document.listform.stext.value  = stext;
			list_data();
		}
		else
		{
			$("#popup_notice_view").show();
			$("#popup_notice_memo").html(chk_total);
		}
		return false;
	}

//------------------------------------ 목록
	function list_data()
	{
		$.ajax({
			dataType : 'html',
			url      : "<?=$link_list;?>",
			data     : $('#listform').serialize(),
			success  : function(msg) {
				$('#data_list').html(msg);

				var total_page = $('#new_total_page').val();
				var page_num   = $('#new_page_num').val();

				$('#page_page_num').empty();
				if (page_num > total_page) page_num = total_page;
				else if (page_num < 1) page_num = 1;

				for (var page_chk = 1; page_chk <= total_page; page_chk++)
				{
					if (page_chk == page_num)
					{
						$('#page_page_num').append('<option value= ' + page_chk + ' selected="selected">' + page_chk + '</option>');
					}
					else
					{
						$('#page_page_num').append('<option value= ' + page_chk + '>' + page_chk + '</option>');
					}
				}
			}
		});
	}

	function check_yn(str1, str2, str3)
	{
		$("#popup_notice_view").hide();
<?
	if ($auth_menu['mod'] == "Y") {
?>
		$('#list_sub_type').val('check_yn')
		$('#list_sub_action').val(str1);
		$('#list_si_idx').val(str2);
		$('#list_post_value').val(str3);

		$.ajax({
			url      : "<?=$link_ok;?>",
			data     : $('#listform').serialize(),
			success  : function(msg) {
				if (msg.success_chk == "Y")
				{
					list_data();
				}
				else
				{
					$("#popup_notice_view").show();
					$("#popup_notice_memo").html('' + msg.error_string + '</p>');
				}
			}
		});
<?
	} else {
?>
		$("#popup_notice_view").show();
		$("#popup_notice_memo").html('수정권한이 없습니다..</p>');
<?
	}
?>
	}

	function check_sort(str1, str2)
	{
		$("#popup_notice_view").hide();
<?
	if ($auth_menu['mod'] == "Y") {
?>
		$('#list_sub_type').val(str1);
		$('#list_si_idx').val(str2);

		$.ajax({
			url     : "<?=$link_ok;?>",
			data    : $('#listform').serialize(),
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					list_data();
				}
				else
				{
					$("#popup_notice_view").show();
					$("#popup_notice_memo").html('' + msg.error_string + '</p>');
				}
			}
		});
<?
	} else {
?>
		$("#popup_notice_view").show();
		$("#popup_notice_memo").html('수정권한이 없습니다..</p>');
<?
	}
?>
	}

	list_data();
//]]>
</script>

<?
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 등록, 수정
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		$where = " and si.si_idx = '" . $si_idx . "'";
		$data = service_info_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';

		if ($data["use_price"] == '') $data["use_price"] = 0;
		else $data["use_price"] = number_format($data["use_price"]);

		if ($data["part_cnt"] == '') $data["part_cnt"] = 0;
		else $data["part_cnt"] = number_format($data["part_cnt"]);

		if ($data["client_cnt"] == '') $data["client_cnt"] = 0;
		else $data["client_cnt"] = number_format($data["client_cnt"]);

		if ($data["banner_cnt"] == '') $data["banner_cnt"] = 0;
		else $data["banner_cnt"] = number_format($data["banner_cnt"]);

		if ($data["sms_cnt"] == '') $data["sms_cnt"] = 0;
		else $data["sms_cnt"] = number_format($data["sms_cnt"]);

		if ($data["group_cnt"] == '') $data["group_cnt"] = 0;
		else $data["group_cnt"] = number_format($data["group_cnt"]);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_post()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">기본서비스 폼</legend>
			<table class="tinytable write" summary="기본서비스를 등록/수정합니다.">
			<caption>기본서비스</caption>
			<colgroup>
				<col width="120px" />
				<col />
				<col width="120px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_subject">서비스명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" size="50" value="<?=$data['subject'];?>" title="서비스명을 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
					<th><label for="post_use_price">서비스가격</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[use_price]" id="post_use_price" size="10" maxlength="10" value="<?=$data['use_price'];?>" title="서비스가격을 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_part_cnt">지사수</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[part_cnt]" id="post_part_cnt" size="10" maxlength="10" value="<?=$data['part_cnt'];?>" title="지사수를 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
					<th><label for="post_client_cnt">거래처수</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[client_cnt]" id="post_client_cnt" size="10" maxlength="10" value="<?=$data['client_cnt'];?>" title="거래처수를 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_banner_cnt">배너수</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[banner_cnt]" id="post_banner_cnt" size="10" maxlength="10" value="<?=$data['banner_cnt'];?>" title="배너수를 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
					<th><label for="post_sms_cnt">SMS수</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[sms_cnt]" id="post_sms_cnt" size="10" maxlength="10" value="<?=$data['sms_cnt'];?>" title="SMS수를 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_group_cnt">그룹수</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[group_cnt]" id="post_group_cnt" size="10" maxlength="10" value="<?=$data['group_cnt'];?>" title="그룹수를 입력하세요." class="type_text {validate:{required:true}}" />
						</div>
					</td>
				</tr>
				<tr>
					<th>보기여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
						</div>
					</td>
					<th>기본값여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[default_yn]", "post_default_yn", $data["default_yn"]);?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($auth_menu['int'] == "Y" && $sub_type == "postform") {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else if ($auth_menu['mod'] == "Y" && $sub_type == "modifyform") {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="si_idx"   value="<?=$si_idx;?>" />
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
//------------------------------------ Save
	function check_post()
	{
		$("#popup_notice_view").hide();
<?
	if (($auth_menu['int'] == "Y" && $sub_type == "postform") || ($auth_menu['mod'] == "Y" && $sub_type == "modifyform")) {
?>
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val();
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_use_price').val();
		chk_title = $('#post_use_price').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				url     : "<?=$link_ok;?>",
				data    : $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#popup_result_msg').dialog({autoOpen: true, title: '기본서비스 처리결과'});
						$('#popup_result_msg').html('정상적으로 처리되었습니다.');
						location.href = '<?=$this_page;?>?<?=$f_script;?>';
					}
					else
					{
						$("#popup_notice_view").show();
						$("#popup_notice_memo").html('' + msg.error_string + '</p>');
					}
				}
			});
		}
		else
		{
			$("#popup_notice_view").show();
			$("#popup_notice_memo").html(chk_total);
		}
<?
	} else {
?>
		$("#popup_notice_view").show();
		$("#popup_notice_memo").html('권한이 없습니다..</p>');
<?
	}
?>
		return false;
	}
//]]>
</script>
<?
	}
?>