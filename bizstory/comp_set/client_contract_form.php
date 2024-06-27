<?
/*
	생성 : 2012.05.07
	수정 : 2012.05.14
	위치 : 업무폴더 > 계약관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $con_idx == '') || ($auth_menu['mod'] == 'Y' && $con_idx != '')) // 등록, 수정권한
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
		$where = " and con.con_idx = '" . $con_idx . "'";
		$data = contract_info_data('view', $where);

		if ($data['con_price'] != '') $data['con_price'] = number_format($data['con_price']);
		if ($data["contract_type"] == '') $data["contract_type"] = 'new';
		if ($data["con_price_chk"] == '') $data["con_price_chk"] = 'Y';
		if ($data["month_price_chk"] == '') $data["month_price_chk"] = 'Y';
?>
<div class="new_report">

	<form id="contractform" name="contractform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_contract_form()">
		<input type="hidden" name="ci_idx" value="<?=$ci_idx;?>" />

	<fieldset>
		<legend class="blind">계약정보 폼</legend>
		<table class="tinytable write" summary="계약정보를 등록/수정합니다.">
		<caption>계약정보</caption>
		<colgroup>
			<col width="100px" />
			<col />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="contract_contract_type">구분</label></th>
				<td colspan="3">
					<div class="left">
						<?=code_radio($set_contract_type, "param[contract_type]", "contract_contract_type", $data["contract_type"]);?>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="contract_subject">계약명</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[subject]" id="contract_subject" class="type_text" title="계약명을 입력하세요." size="50" value="<?=$data['subject'];?>" />
					</div>
				</td>
				<th><label for="contract_contract_date">계약일</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[contract_date]" id="contract_contract_date" class="type_text datepicker" title="계약일을 입력하세요." size="10" value="<?=$data['contract_date'];?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="contract_contract_number">계약번호</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[contract_number]" id="contract_contract_number" class="type_text" title="계약번호를 입력하세요." size="30" value="<?=$data['contract_number'];?>" />
					</div>
				</td>
				<th><label for="contract_begin_date">착수일</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[begin_date]" id="contract_begin_date" class="type_text datepicker" title="착수일을 입력하세요." size="10" value="<?=$data['begin_date'];?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="contract_charge_name">담당자</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[charge_name]" id="contract_charge_name" class="type_text" title="담당자를 입력하세요." size="30" value="<?=$data['charge_name'];?>" />
					</div>
				</td>
				<th><label for="contract_complete_date">완료일</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[complete_date]" id="contract_complete_date" class="type_text datepicker" title="완료일을 입력하세요." size="10" value="<?=$data['complete_date'];?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="contract_con_price">계약금액</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[con_price]" id="contract_con_price" class="type_text" title="계약금액을 입력하세요." size="15" value="<?=$data['con_price'];?>" onkeyup="input_comma(this)" onblur="input_comma(this)" /> 원
						<input type="checkbox" name="param[con_price_chk]" id="contract_con_price_chk" title="계약정산을 선택하세요." value="Y" <?=checked($data['con_price_chk'], 'Y');?> />계약정산
					</div>
				</td>
				<th><label for="contract_month_price">월유지보수</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[month_price]" id="contract_month_price" class="type_text" title="월유지보수금액을 입력하세요." size="15" value="<?=$data['month_price'];?>" onkeyup="input_comma(this)" onblur="input_comma(this)" /> 원
						<input type="checkbox" name="param[month_price_chk]" id="contract_month_price_chk" title="유지보수정산을 선택하세요." value="Y" <?=checked($data['month_price_chk'], 'Y');?> />유지보수정산
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="contract_remark">내용</label></th>
				<td colspan="3">
					<div class="left textarea_span">
						<textarea name="param[remark]" id="contract_remark" title="내용을 입력하세요." cols="50" rows="5"><?=$data['remark'];?></textarea>
					</div>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
		<?
			if ($con_idx == '') {
		?>
				<span class="btn_big_green"><input type="submit" value="등록" /></span>
				<span class="btn_big_gray"><input type="button" value="취소" onclick="contract_insert_form('close')" /></span>

				<input type="hidden" name="sub_type" value="post" />
		<?
			} else {
		?>
				<span class="btn_big_blue"><input type="submit" value="수정" /></span>
				<span class="btn_big_gray"><input type="button" value="취소" onclick="contract_modify_form('close', '<?=$con_idx;?>')" /></span>

				<input type="hidden" name="sub_type" value="modify" />
				<input type="hidden" name="con_idx"  value="<?=$con_idx;?>" />
		<?
			}
		?>
			</div>
		</div>

	</fieldset>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_contract_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#contract_subject').val(); // 제목
		chk_title = $('#contract_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		oEditors.getById["contract_remark"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 적용됩니다.

		if (action_num == 0)
		{
			$.ajax({
				type: "post", dataType: 'json', url: contract_ok,
				data: $('#contractform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
						$("#loading").fadeIn('slow').fadeOut('slow');
					if (msg.success_chk == "Y")
					{
						$('#contract_total_value').html(msg.total_num);
	<?
		if ($con_idx == '') {
	?>
						contract_insert_form('close');
	<?
		} else {
	?>
						contract_modify_form('close','');
	<?
		}
	?>
						contract_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	$(".datepicker").datepicker();

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "contract_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});
//]]>
</script>
<?
	}
?>
