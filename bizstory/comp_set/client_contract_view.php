<?
/*
	생성 : 2012.05.07
	수정 : 2012.05.14
	위치 : 업무폴더 > 계약관리 - 보기
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
	if ($auth_menu['view'] == 'Y') // 보기권한
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
?>
<div class="new_report">

	<fieldset>
		<legend class="blind">계약정보 폼</legend>
		<table class="tinytable view" summary="계약정보를 등록/수정합니다.">
		<caption>계약정보</caption>
		<colgroup>
			<col width="100px" />
			<col />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th>계약구분</th>
				<td colspan="3">
					<div class="left"><?=$set_contract_type[$data['contract_type']];?></div>
				</td>
			</tr>
			<tr>
				<th>계약명</th>
				<td>
					<div class="left"><strong><?=$data['subject'];?></strong></div>
				</td>
				<th>계약일</th>
				<td>
					<div class="left"><?=date_replace($data['contract_date'], 'Y-m-d');?></div>
				</td>
			</tr>
			<tr>
				<th>계약번호</th>
				<td>
					<div class="left"><?=$data['contract_number'];?></div>
				</td>
				<th>착수일</th>
				<td>
					<div class="left"><?=date_replace($data['begin_date'], 'Y-m-d');?></div>
				</td>
			</tr>
			<tr>
				<th>담당자</th>
				<td>
					<div class="left"><?=$data['charge_name'];?></div>
				</td>
				<th>완료일</th>
				<td>
					<div class="left"><?=date_replace($data['complete_date'], 'Y-m-d');?></div>
				</td>
			</tr>
			<tr>
				<th>계약금액</th>
				<td>
					<div class="left">
						<?=number_format($data['con_price']);?> 원
						(계약정산 : <?=$data['con_price_chk'];?>)
					</div>
				</td>
				<th>월유지보수</th>
				<td>
					<div class="left">
						<?=number_format($data['month_price']);?> 원
						(유지보수정산 : <?=$data['month_price_chk'];?>)
					</div>
				</td>
			</tr>
			<tr>
				<th>내용</th>
				<td colspan="3">
					<div class="left">
						<p class="memo">
							<?=$data['remark'];?>
						</p>
					</div>
				</td>
			</tr>
		</tbody>
		</table>
		<div class="section">
			<div class="fr">
	<?
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$client_data = client_info_data("view", $where);

		if ($client_data['del_yn'] == 'N')
		{
			$btn_modify = "contract_modify_form('open', '" . $con_idx . "')";
			$btn_delete = "contract_delete('" . $con_idx . "')";
		}
		else
		{
			$btn_modify = "check_auth_popup('modify')";
			$btn_delete = "check_auth_popup('delete')";
		}

		if ($auth_menu['mod'] == 'Y') {
	?>
				<span class="btn_big_blue"><input type="button" value="수정" onclick="<?=$btn_modify;?>" /></span>
	<?
		}
		if ($auth_menu['del'] == 'Y') {
	?>
				<span class="btn_big_red"><input type="button" value="삭제" onclick="<?=$btn_delete;?>" /></span>
	<?
		}
	?>
			</div>
		</div>
	</fieldset>
	</form>

	<div class="section">
		<div class="fr">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="contract_insert_form('close')" /></span>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 계약 수정
	function contract_modify_form(form_type, cu_idx)
	{
		if (form_type == 'close')
		{
			$("#new_contract").slideUp("slow");
			$("#new_contract").html('');
		}
		else
		{
			$("#contractlist_cu_idx").val(cu_idx);
			$.ajax({
				type: "post", dataType: 'html', url: contract_form,
				data: $('#contractlistform').serialize(),
				success: function(msg) {
					$("#new_contract").slideUp("slow");
					$("#new_contract").slideDown("slow");
					$("#new_contract").html(msg);
				}
			});
		}
	}

//------------------------------------ 계약 삭제
	function contract_delete(idx)
	{
		if (confirm("선택하신 사용자를 삭제하시겠습니까?"))
		{
			$('#contractlist_sub_type').val('delete');
			$('#contractlist_cu_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: contract_ok,
				data: $('#contractlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#contract_total_value').html(msg.total_num);
						contract_modify_form('close', '')
						contract_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>
<?
	}
?>
