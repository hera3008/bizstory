<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 회사관리 > 회사설정
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$set_tax_yn = $comp_set_data['tax_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_ok = $local_dir . "/bizstory/comp_set/comp_set_ok.php"; // 저장

	$where = " and cs.comp_idx = '" . $code_comp . "'";
	$data = company_set_data('view', $where);

// 지사별
	$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
	$part_list = company_part_data('list', $part_where, '', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="cs_idx"   value="<?=$data['cs_idx'];?>" />

			<fieldset>
				<legend class="blind">회사설정</legend>
				<table class="tinytable write" summary="회사설정를 수정합니다.">
				<caption>회사설정</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_receipt_charge">접수알림 직원</label></th>
						<td>
							<input type="hidden" name="param[receipt_charge]" id="post_receipt_charge" value="<?=$data['receipt_charge'];?>" title="접수알림 직원 선택하세요." />
						<?
							$charge_idx_arr = explode(',', $data['charge_idx']);
							$charge_view = form_charge_view('check_member_idx[]', $data['receipt_charge'], $part_list, '');
							echo $charge_view['change_view'];
						?>
						</td>
					</tr>
					<tr>
						<th><label for="post_receipt_inform_yn">접수알리미여부</label></th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[receipt_inform_yn]", "post_receipt_inform_yn", $data["receipt_inform_yn"]);?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

//------------------------------------ Save
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		var mem_idx = document.getElementsByName('check_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = '';

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx = mem_idx[i].value;
				}
				else
				{
					total_member_idx += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}
		$('#post_receipt_charge').val(total_member_idx);

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
						check_auth_popup('정상적으로 처리되었습니다.');
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
		return false;
	}

// 지사별직원보기
	function part_charge_chk(idx)
	{
		$("#part_charge_btn_" + idx).html(" - ");
		$("#part_charge_view_" + idx).css({"display": "block"});
	}
<?
	if (is_array($part_charge_on))
	{
		foreach ($part_charge_on as $on_k => $on_v)
		{
			echo $on_v;
		}
	}
?>
//]]>
</script>