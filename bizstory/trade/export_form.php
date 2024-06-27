<?
/*
	생성 : 2012.11.21
	위치 : 무역업무 > 수출신고 - 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$ei_idx    = $idx;

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
	if (($auth_menu['int'] == 'Y' && $ei_idx == '') || ($auth_menu['mod'] == 'Y' && $ei_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
			list_data();
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and ei.ei_idx = '" . $ei_idx . "'";
		$data = export_info_data('view', $where);

		if ($data['export_section'] == '') $data['export_section'] = 'C';
		if ($data['buyer_mark']     == '') $data['buyer_mark']     = 'ZZZZZZZZ9999A';
		if ($data['report_section'] == '') $data['report_section'] = 'H';
		if ($data['deal_section']   == '') $data['deal_section']   = '15';
		if ($data['export_kind']    == '') $data['export_kind']    = 'A';
		if ($data['payment_how']    == '') $data['payment_how']    = 'TT';
		if ($data['trasfer_terms']  == '') $data['trasfer_terms']  = 'CFR';
		if ($data['load_harbor']    == '') $data['load_harbor']    = 'ICN';
		if ($data['carry_means']    == '') $data['carry_means']    = '40';
		if ($data['test_how']       == '') $data['test_how']       = 'B';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">

		<fieldset>
			<legend class="blind">수출신고 폼</legend>
			<table class="tinytable write" summary="수출신고를 등록/수정합니다.">
			<caption>수출신고</caption>
			<colgroup>
				<col width="120px" />
				<col />
				<col width="120px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_declare_company">신고자 상호</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[declare_company]" id="post_declare_company" class="type_text" title="신고자 상호 입력하세요." size="30" value="<?=$data['declare_company'];?>" />
						</div>
					</td>
					<th><label for="post_declare_name">신고자 성명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[declare_name]" id="post_declare_name" class="type_text" title="신고자 성명 입력하세요." size="20" value="<?=$data['declare_name'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_customs_mark">통관고유부호</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[customs_mark]" id="post_customs_mark" class="type_text" title="통관고유부호 입력하세요." size="20" value="<?=$data['customs_mark'];?>" />
						</div>
					</td>
					<th>수출자구분</th>
					<td>
						<div class="left">
							<?=code_radio($set_export_section, 'param[export_section]', 'post_export_section', $data['export_section'], '수출자구분 선택하세요.');?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_buyer_mark">구매자부호</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[buyer_mark]" id="post_buyer_mark" class="type_text" title="구매자부호 입력하세요." size="20" value="<?=$data['buyer_mark'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th>신고구분</th>
					<td>
						<div class="left">
							<?=code_radio($set_report_section, 'param[report_section]', 'post_report_section', $data['report_section'], '신고구분 선택하세요.');?>
						</div>
					</td>
					<th><label for="post_deal_section">거래구분</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[deal_section]" id="post_deal_section" class="type_text" title="거래구분 입력하세요." size="5" value="<?=$data['deal_section'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_export_kind">수출종류</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[export_kind]" id="post_export_kind" class="type_text" title="수출종류 입력하세요." size="5" value="<?=$data['export_kind'];?>" />
						</div>
					</td>
					<th><label for="post_payment_how">결제방법</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[payment_how]" id="post_payment_how" class="type_text" title="결제방법 입력하세요." size="5" value="<?=$data['payment_how'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_trasfer_terms">인도조건</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[trasfer_terms]" id="post_trasfer_terms" class="type_text" title="인도조건 입력하세요." size="5" value="<?=$data['trasfer_terms'];?>" />
						</div>
					</td>
					<th><label for="post_load_harbor">적재항</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[load_harbor]" id="post_load_harbor" class="type_text" title="적재항 입력하세요." size="5" value="<?=$data['load_harbor'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_carry_means">운송수단</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[carry_means]" id="post_carry_means" class="type_text" title="운송수단 입력하세요." size="5" value="<?=$data['carry_means'];?>" />
						</div>
					</td>
					<th>검사방법</th>
					<td>
						<div class="left">
							<?=code_radio($set_test_how, 'param[test_how]', 'post_test_how', $data['test_how'], '검사방법 선택하세요.');?>
						</div>
					</td>
				</tr>
				<tr>
					<th>물품상태</th>
					<td>
						<div class="left">
							<?=code_radio($set_goods_state, 'param[goods_state]', 'post_goods_state', $data['goods_state'], '물품상태 선택하세요.');?>
						</div>
					</td>
					<th>임시개청신청</th>
					<td>
						<div class="left">
							<?=code_radio($set_openhouse, 'param[openhouse]', 'post_openhouse', $data['openhouse'], '임시개청신청 선택하세요.');?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($ei_idx == '') {
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
					<input type="hidden" name="ei_idx"   value="<?=$ei_idx;?>" />
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
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_declare_company').val();
		chk_title = $('#post_declare_company').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_declare_name').val();
		chk_title = $('#post_declare_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_customs_mark').val();
		chk_title = $('#post_customs_mark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

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
