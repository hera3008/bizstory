<?
/*
	생성 : 2012.11.21
	위치 : 무역업무 > 수출신고 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$ei_idx = $idx;

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
	if ($auth_menu['view'] == 'Y') // 보기권한
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
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<fieldset>
			<legend class="blind">수출신고</legend>
			<table class="tinytable view" summary="등록한 수출신고에 대한 상세정보입니다.">
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
							<?=$data['declare_company'];?>
						</div>
					</td>
					<th><label for="post_declare_name">신고자 성명</label></th>
					<td>
						<div class="left">
							<?=$data['declare_name'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_customs_mark">통관고유부호</label></th>
					<td>
						<div class="left">
							<?=$data['customs_mark'];?>
						</div>
					</td>
					<th>수출자구분</th>
					<td>
						<div class="left">
							<?=$set_export_section[$data['export_section']];?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_buyer_mark">구매자부호</label></th>
					<td colspan="3">
						<div class="left">
							<?=$data['buyer_mark'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>신고구분</th>
					<td>
						<div class="left">
							<?=$set_report_section[$data['report_section']];?>
						</div>
					</td>
					<th><label for="post_deal_section">거래구분</label></th>
					<td>
						<div class="left">
							<?=$data['deal_section'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_export_kind">수출종류</label></th>
					<td>
						<div class="left">
							<?=$data['export_kind'];?>
						</div>
					</td>
					<th><label for="post_payment_how">결제방법</label></th>
					<td>
						<div class="left">
							<?=$data['payment_how'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_trasfer_terms">인도조건</label></th>
					<td>
						<div class="left">
							<?=$data['trasfer_terms'];?>
						</div>
					</td>
					<th><label for="post_load_harbor">적재항</label></th>
					<td>
						<div class="left">
							<?=$data['load_harbor'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_carry_means">운송수단</label></th>
					<td>
						<div class="left">
							<?=$data['carry_means'];?>
						</div>
					</td>
					<th>검사방법</th>
					<td>
						<div class="left">
							<?=$set_test_how[$data['test_how']];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>물품상태</th>
					<td>
						<div class="left">
							<?=$set_goods_state[$data['goods_state']];?>
						</div>
					</td>
					<th>임시개청신청</th>
					<td>
						<div class="left">
							<?=$set_openhouse[$data['openhouse']];?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
// 등록자만 가능하다.
	if ($data['reg_id'] == $code_mem || $code_level <= 11)
	{
		$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="open_data_form(\'' . $ei_idx . '\')" /></span>';
		$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $ei_idx . '\')" /></span>';
	}
?>
			<div class="section">
				<div class="fr">
					<?=$btn_modify;?>
					<?=$btn_delete;?>
				</div>
			</div>
		</fieldset>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>
<?
	}
?>