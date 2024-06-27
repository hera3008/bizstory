<?
/*
	수정 : 2012.07.16
	위치 : 설정폴더 > 거래처관리 > 거래처등록 - 등록/수정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ci_idx    = $idx;

	$set_client_cnt = $comp_set_data['client_cnt'];
	$set_tax_yn     = $comp_set_data['tax_yn'];
	$set_tax_yn = 'Y';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$page_where = " and ci.comp_idx = '" . $code_comp . "'";
	$page_data = client_info_data('page', $page_where);

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $ci_idx == '') || ($auth_menu['mod'] == 'Y' && $ci_idx != '')) // 등록, 수정권한
	{
		if ($page_data['total_num'] >= $set_client_cnt && $ci_idx == '') // 거래처수확인
		{
?>
			<script type="text/javascript">
			//<![CDATA[
				alert('거래처수는 <?=$set_client_cnt;?>개까지 등록이 가능합니다. 더이상 거래처를 등록할 수 없습니다.');
				history.back();
			//]]>
			</script>
<?
		}
		else $form_chk = 'Y';
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
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$data = client_info_data("view", $where);

		if ($data['view_yn'] == '') $data['view_yn'] = 'Y';
		if ($data['ip_yn'] == '') $data['ip_yn'] = 'N';
		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
		if ($data['receipt_yn'] == '') $data['receipt_yn'] = 'Y';

		$zip_code = $data['zip_code'];
		$zip_code_arr = explode('-', $zip_code);
		$data['zip_code1'] = $zip_code_arr[0];
		$data['zip_code2'] = $zip_code_arr[1];

		$address = $data['address'];
		$address_arr = explode('||', $address);
		$data['address1'] = $address_arr[0];
		$data['address2'] = $address_arr[1];

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];

		$fax_num = $data['fax_num'];
		$fax_num_arr = explode('-', $fax_num);
		$data['fax_num1'] = $fax_num_arr[0];
		$data['fax_num2'] = $fax_num_arr[1];
		$data['fax_num3'] = $fax_num_arr[2];

		$client_email = $data['client_email'];
		$client_email_arr = explode('@', $client_email);
		$data['client_email1'] = $client_email_arr[0];
		$data['client_email2'] = $client_email_arr[1];

		$link_url = $data['link_url'];
		$link_url_arr = explode(',', $link_url);

		$ip_info = $data['ip_info'];
		$ip_info_arr = explode(',', $ip_info);

		$charge_info = $data['charge_info'];
		$charge_info_arr = explode('||', $charge_info);

		$par_where = " and part.part_idx = '" . $data['part_idx'] . "'";
		$part_data = company_part_data("view", $par_where);

		$part_agent_type = $part_data['agent_type'];
		if ($part_agent_type == '') $part_agent_type ='A';
		$part_agent_type = explode(',', $part_agent_type);
?>
<div class="info_text">
	<ul>
		<li>사용자, 계약정보는 거래처를 먼저 등록한 후 사용하세요</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">거래처정보 폼</legend>
			<table class="tinytable write" summary="거래처정보를 등록/수정합니다.">
			<caption>거래처정보</caption>
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
			<?
			// 거래처, 접수분류
				$str_script = "part_information(this.value, 'staff_info', 'post_mem_idx', '" . $data['mem_idx'] . "', ''); part_information(this.value, 'client_group', 'post_ccg_idx', '" . $data['ccg_idx'] . "', '');";
			?>
							<?=company_part_form($data['part_idx'], $part_data['part_name'], ' onchange="' . $str_script . '"');?>
						</div>
					</td>
					<th><label for="post_mem_idx">담당직원</label></th>
					<td>
						<div class="left">
							<select name="param[mem_idx]" id="post_mem_idx" title="담당직원을 선택하세요">
								<option value="">담당직원을 선택하세요</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_client_name">거래처명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[client_name]" id="post_client_name" class="type_text" title="거래처명을 입력하세요." size="20" value="<?=$data['client_name'];?>" />
						</div>
					</td>
					<th><label for="post_ccg_idx">거래처분류</label></th>
					<td>
						<div class="left">
							<select name="param[ccg_idx]" id="post_ccg_idx" title="거래처분류를 선택하세요">
								<option value="">거래처분류를 선택하세요</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tel_num1">연락처</label></th>
					<td>
						<div class="left">
							<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '선택', '', '{validate:{required:true}}');?>
							-
							<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
							-
							<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
						</div>
					</td>
					<th><label for="post_fax_num1">팩스번호</label></th>
					<td>
						<div class="left">
							<?=code_select($set_telephone, 'param[fax_num1]', 'post_fax_num1', $data['fax_num1'], '팩스번호 앞자리를 선택하세요.', '선택', '', '{validate:{required:true}}');?>
							-
							<input type="text" name="param[fax_num2]" id="post_fax_num2" class="type_text" title="팩스번호를 모두 입력하세요." size="4" value="<?=$data['fax_num2'];?>" />
							-
							<input type="text" name="param[fax_num3]" id="post_fax_num3" class="type_text" title="팩스번호를 모두 입력하세요." size="4" value="<?=$data['fax_num3'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_client_email1">이메일</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[client_email1]" id="post_client_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['client_email1'];?>" />
							@
							<input type="text" name="param[client_email2]" id="post_client_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['client_email2'];?>" />
							<?=code_select($set_email_domain, 'post_client_email3', 'post_client_email3', $data['client_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_client_email2\', \'post_client_email3\');"');?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_zip_code1">주소</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[zip_code1]" id="post_zip_code1" class="type_text" title="우편번호 앞자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code1'];?>" />
							-
							<input type="text" name="param[zip_code2]" id="post_zip_code2" class="type_text" title="우편번호 뒷자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code2'];?>" />
							<strong class="btn_sml" onclick="check_address_find();"><span>우편번호찾기</span></strong>
						</div>
						<div class="left mt">
							<input type="text" name="param[address1]" id="post_address1" class="type_text" title="주소 입력하세요." size="40" value="<?=$data['address1'];?>" />
							<input type="text" name="param[address2]" id="post_address2" class="type_text" title="상세주소 입력하세요." size="35" value="<?=$data['address2'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_link_url">링크주소</label></th>
					<td colspan="3">
						<div class="left" id="link_url_form">
				<?
					$len_num = 1;
					if ($ci_idx == '')
					{
				?>
							<input type="text" name="post_link_url[]" id="post_link_url1" class="type_text" title="링크주소를 입력하세요." size="50" />
							<strong class="btn_sml" onclick="add_link_url();"><span>추가</span></strong>
				<?
					}
					else
					{
						if (is_array($link_url_arr))
						{
							$total_len = count($link_url_arr);
							foreach ($link_url_arr as $arr_k => $arr_v)
							{
				?>
							<input type="text" name="post_link_url[]" id="post_link_url<?=$len_num;?>" class="type_text" title="링크주소를 입력하세요." size="50" value="<?=$arr_v;?>" />
				<?
								if ($arr_k == $total_len-1)
								{
				?>
							<strong class="btn_sml" onclick="add_link_url();"><span>추가</span></strong>
				<?
								}
								$len_num++;
							}
						}
					}
				?>
							<input type="hidden" name="post_link_url_num" id="post_link_url_num" value="<?=$len_num;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_ip_yn">IP차단여부</label></th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[ip_yn]", "post_ip_yn", $data["ip_yn"]);?>
						</div>
					</td>
					<th><label for="post_ip_info">IP허용</label></th>
					<td>
						<div class="left" id="ip_info_form">
				<?
					$len_num = 1;
					if ($ci_idx == '')
					{
				?>
							<input type="text" name="post_ip_info[]" id="post_ip_info1" class="type_text" title="ip허용을 입력하세요." size="20" />
							<strong class="btn_sml" onclick="add_ip_info();"><span>추가</span></strong>
				<?
					}
					else
					{
						if (is_array($ip_info_arr))
						{
							$total_len = count($ip_info_arr);
							foreach ($ip_info_arr as $arr_k => $arr_v)
							{
				?>
							<input type="text" name="post_ip_info[]" id="post_ip_info<?=$len_num;?>" class="type_text" title="ip허용을 입력하세요." size="20" value="<?=$arr_v;?>" />
				<?
								if ($arr_k == $total_len-1)
								{
				?>
							<strong class="btn_sml" onclick="add_ip_info();"><span>추가</span></strong>
				<?
								}
								$len_num++;
							}
						}
					}
				?>
							<input type="hidden" name="post_ip_info_num" id="post_ip_info_num" value="<?=$len_num;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_charge_info">담당자</label></th>
					<td colspan="3">
						<div class="left" id="charge_info_form">
				<?
					$len_num = 1;
					if ($ci_idx == '')
					{
				?>
							담당자명 : <input type="text" name="post_charge_name1" id="post_charge_name1" class="type_text" title="담당자명 입력하세요." size="10" />,
							연락처1 : <input type="text" name="post_charge_tel1_1" id="post_charge_tel1_1" class="type_text" title="담당자연락처1 입력하세요." size="15" />,
							연락처2 : <input type="text" name="post_charge_tel2_1" id="post_charge_tel2_1" class="type_text" title="담당자연락처2 입력하세요." size="15" />,
							메일주소 : <input type="text" name="post_charge_email1" id="post_charge_email1" class="type_text" title="이메일 입력하세요." size="20" />
							<strong class="btn_sml" onclick="add_charge_info();"><span>추가</span></strong>
				<?
					}
					else
					{
						if (is_array($charge_info_arr))
						{
							$total_len = count($charge_info_arr);
							foreach ($charge_info_arr as $arr_k => $arr_v)
							{
								$info_str = explode('/', $arr_v);
								if ($arr_k > 0)
								{
									echo '<br />';
								}
				?>
							담당자명 : <input type="text" name="post_charge_name<?=$len_num;?>" id="post_charge_name<?=$len_num;?>" class="type_text" title="담당자명 입력하세요." size="15" value="<?=$info_str[0];?>" />,
							연락처1 : <input type="text" name="post_charge_tel1_<?=$len_num;?>" id="post_charge_tel1_<?=$len_num;?>" class="type_text" title="담당자연락처1 입력하세요." size="15" value="<?=$info_str[1];?>" />,
							연락처2 : <input type="text" name="post_charge_tel2_<?=$len_num;?>" id="post_charge_tel2_<?=$len_num;?>" class="type_text" title="담당자연락처2 입력하세요." size="15" value="<?=$info_str[3];?>" />,
							메일주소 : <input type="text" name="post_charge_email<?=$len_num;?>" id="post_charge_email<?=$len_num;?>" class="type_text" title="이메일 입력하세요." size="30" value="<?=$info_str[2];?>" />
				<?
								if ($arr_k == $total_len-1)
								{
				?>
							<strong class="btn_sml" onclick="add_charge_info();"><span>추가</span></strong>
				<?
								}
								$len_num++;
							}
						}
					}
				?>
							<input type="hidden" name="post_charge_info_num" id="post_charge_info_num" value="<?=$len_num;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_memo1">접속정보</label></th>
					<td colspan="3">
						<div class="left">
							<textarea name="param[memo1]" id="post_memo1" class="type_text" title="접속정보를 입력하세요." cols="50" rows="5"><?=$data['memo1'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">간단메모</label></th>
					<td colspan="3">
						<div class="left">
							<textarea name="param[remark]" id="post_remark" class="type_text" title="간단메모를 입력하세요." cols="50" rows="5"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_receipt_sms_yn">접수 SMS</label></th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[receipt_sms_yn]", "post_receipt_sms_yn", $data["receipt_sms_yn"]);?>
						</div>
					</td>
					<th><label for="post_receipt_email_yn">접수 Email</label></th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[receipt_email_yn]", "post_receipt_email_yn", $data["receipt_email_yn"]);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_receipt_push_yn">접수 Push</label></th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[receipt_push_yn]", "post_receipt_push_yn", $data["receipt_push_yn"]);?>
						</div>
					</td>
					<th><label for="post_agent_type">에이전트타입</label></th>
					<td>
						<div class="left">
							<?=code_radio($part_agent_type, "param[agent_type]", "post_agent_type", $data["agent_type"], 'value');?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_report_type">점검보고서타입</label></th>
					<td>
						<div class="left">
							<select name="param[report_type]" id="post_report_type" title="점검보고서타입을 선택하세요">
								<option value="">점검보고서타입을 선택하세요</option>
							</select>
						</div>
					</td>
					<th><label for="post_view_yn">사용여부</label></th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
	if ($set_tax_yn == 'Y')
	{
		$tax_comp_num = $data['tax_comp_num'];
		$tax_comp_num_arr = explode('-', $tax_comp_num);
		$data['tax_comp_num1'] = $tax_comp_num_arr[0];
		$data['tax_comp_num2'] = $tax_comp_num_arr[1];
		$data['tax_comp_num3'] = $tax_comp_num_arr[2];

		$tax_zip_code = $data['tax_zip_code'];
		$tax_zip_code_arr = explode('-', $tax_zip_code);
		$data['tax_zip_code1'] = $tax_zip_code_arr[0];
		$data['tax_zip_code2'] = $tax_zip_code_arr[1];

		$tax_address = $data['tax_address'];
		$tax_address_arr = explode('||', $tax_address);
		$data['tax_address1'] = $tax_address_arr[0];
		$data['tax_address2'] = $tax_address_arr[1];

		$tax_email = $data['tax_email'];
		$tax_email_arr = explode('@', $tax_email);
		$data['tax_email1'] = $tax_email_arr[0];
		$data['tax_email2'] = $tax_email_arr[1];
?>
			<div class="sub_frame"><h4>세금계산서관련 정보입력</h4></div>
			<div class="sub_frame"><h4><input type="radio" name="same_info" id="same_info" value="Y" onclick="check_same_info()" /> 기본정보가 동일합니다.</h4></div>

			<table class="tinytable write" summary="세금계산서관련정보를 등록/수정합니다.">
			<caption>세금계산서관련정보</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_tax_comp_name">상호명</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[tax_comp_name]" id="post_tax_comp_name" class="type_text" title="상호명을 입력하세요." size="50" value="<?=$data['tax_comp_name'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tax_boss_name">대표자명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tax_boss_name]" id="post_tax_boss_name" class="type_text" title="대표자명을 입력하세요." size="20" value="<?=$data['tax_boss_name'];?>" />
						</div>
					</td>
					<th><label for="post_tax_comp_num1">사업자등록번호</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[tax_comp_num1]" id="post_tax_comp_num1" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="3" value="<?=$data['tax_comp_num1'];?>" onkeydown="return autoTab(this, 3, event);" onkeyup="return autoTab(this, 3, event);" />
							-
							<input type="text" name="param[tax_comp_num2]" id="post_tax_comp_num2" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="2" value="<?=$data['tax_comp_num2'];?>" onkeydown="return autoTab(this, 2, event);" onkeyup="return autoTab(this, 2, event);" />
							-
							<input type="text" name="param[tax_comp_num3]" id="post_tax_comp_num3" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="5" value="<?=$data['tax_comp_num3'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tax_upjong">업종</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tax_upjong]" id="post_tax_upjong" class="type_text" title="업종을 입력하세요." size="24" value="<?=$data['tax_upjong'];?>" />
						</div>
					</td>
					<th><label for="post_tax_uptae">업태</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tax_uptae]" id="post_tax_uptae" class="type_text" title="업태를 입력하세요." size="24" value="<?=$data['tax_uptae'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tax_zip_code1">사업장주소</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[tax_zip_code1]" id="post_tax_zip_code1" class="type_text" title="사업장주소 우편번호 앞자리를 입력하세요." size="4" maxlength="3" value="<?=$data['tax_zip_code1'];?>" />
							-
							<input type="text" name="param[tax_zip_code2]" id="post_tax_zip_code2" class="type_text" title="사업장주소 우편번호 뒷자리를 입력하세요." size="4" maxlength="3" value="<?=$data['tax_zip_code2'];?>" />
							<strong class="btn_sml" onclick="check_address_find2();"><span>우편번호찾기</span></strong>
						</div>
						<div class="left mt">
							<input type="text" name="param[tax_address1]" id="post_tax_address1" class="type_text" title="사업장 주소 입력하세요." size="40" value="<?=$data['tax_address1'];?>" />
							<input type="text" name="param[tax_address2]" id="post_tax_address2" class="type_text" title="사업장 상세주소 입력하세요." size="35" value="<?=$data['tax_address2'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tax_email1">전자계산서<br />담당자메일</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[tax_email1]" id="post_tax_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['tax_email1'];?>" />
							@
							<input type="text" name="param[tax_email2]" id="post_tax_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['tax_email2'];?>" />
							<?=code_select($set_email_domain, 'post_tax_email3', 'post_tax_email3', $data['tax_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_tax_email2\', \'post_tax_email3\');"');?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
	}
?>
			<div class="section">
				<div class="fr">
			<?
				if ($ci_idx == '') {
			?>
					<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big fl"><input type="submit" value="수정하기" /></span>
					<span class="btn_big fl"><input type="button" value="수정취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="ci_idx"   value="<?=$ci_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 사용자
	$client_user_where = " and cu.comp_idx = '" . $code_comp . "' and cu.ci_idx = '" . $ci_idx . "'";
	$client_user_list = client_user_data('list', $client_user_where, '', '', '');
?>
		<div id="task_cuser" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="cuser_gate" class="btn_i_minus" title="사용자목록" onclick="cuser_view()"></a> 사용자정보 <span id="cuser_total_value">[<?=number_format($client_user_list['total_num']);?>]</span>
				</p>
				<div class="new" id="cuser_new_btn"><span class="btn_sml"><input type="button" value="사용자등록" onclick="cuser_insert_form('open')" /></span></div>
			</div>

			<div id="new_cuser" title="사용자등록/수정"></div>

			<form id="cuserlistform" name="cuserlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="cuserlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="cuserlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="cuserlist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="cuserlist_cu_idx"    name="cu_idx" />
				<?=$form_page;?>
				<div id="cuser_list_data"></div>
			</form>
		</div>

		<div class="dotted2"></div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 계약
	$contract_where = " and con.comp_idx = '" . $code_comp . "' and con.ci_idx = '" . $ci_idx . "'";
	$contract_list = contract_info_data('list', $contract_where, '', '', '');
?>
		<div id="task_contract" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="contract_gate" class="btn_i_minus" title="계약목록" onclick="contract_view()"></a> 계약정보 <span id="contract_total_value">[<?=number_format($contract_list['total_num']);?>]</span>
				</p>
				<div class="new" id="contract_new_btn"><span class="btn_sml"><input type="button" value="계약등록" onclick="contract_insert_form('open')" /></span></div>
			</div>

			<div id="new_contract" title="계약등록/수정"></div>

			<form id="contractlistform" name="contractlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="contractlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="contractlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="contractlist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="contractlist_con_idx"   name="con_idx" />
				<?=$form_page;?>
				<div id="contract_list_data"></div>
			</form>
		</div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 메모
	$memo_where = " and cim.ci_idx = '" . $ci_idx . "'";
	$memo_list = client_memo_data('list', $memo_where, '', '', '');
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="메모목록" onclick="memo_view()"></a> 메모 <span id="memo_total_value">[<?=number_format($memo_list['total_num']);?>]</span>
				</p>
				<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_memo.png" alt="메모 쓰기" class="pointer" onclick="memo_insert_form('open')" /></div>
			</div>

			<div id="new_memo" title="메모쓰기"></div>

			<form id="memolistform" name="memolistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="memolist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="memolist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="memolist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="memolist_cim_idx"   name="cim_idx"   value="" />
				<?=$form_page;?>
				<div id="memo_list_data"></div>
			</form>
		</div>

	</div>
</div>
<? include $local_path . "/bizstory/include/find_address.php"; ?>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/client_memo.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_client_name').val(); // 거래처명
		chk_title = $('#post_client_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: "post", dataType: 'json',  url: link_ok,
				data: $('#postform').serialize(),
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

//------------------------------------ 세금계산서 데이타
	function check_same_info()
	{
		$('#post_tax_comp_name').val($('#post_client_name').val());
		$('#post_tax_zip_code1').val($('#post_zip_code1').val());
		$('#post_tax_zip_code2').val($('#post_zip_code2').val());
		$('#post_tax_address1').val($('#post_address1').val());
		$('#post_tax_address2').val($('#post_address2').val());
		$('#post_tax_email1').val($('#post_client_email1').val());
		$('#post_tax_email2').val($('#post_client_email2').val());
		$('#post_tax_email3').val($('#post_client_email3').val());
	}

//------------------------------------ 추가 링크주소
	function add_link_url()
	{
		var chk_num = $('#post_link_url_num').val();
			chk_num = parseInt(chk_num) + 1;

		$('#link_url_form').append('<br /><input type="text" name="post_link_url[]" id="post_link_url' + chk_num + '" class="type_text" title="링크주소를 입력하세요." size="50" value="" />');
		$('#post_link_url_num').val(chk_num);
	}

//------------------------------------ 추가 IP허용
	function add_ip_info()
	{
		var chk_num = $('#post_ip_info_num').val();
			chk_num = parseInt(chk_num) + 1;

		$('#ip_info_form').append('<br /><input type="text" name="post_ip_info[]" id="post_ip_info' + chk_num + '" class="type_text" title="IP허용을 입력하세요." size="20" value="" />');
		$('#post_ip_info_num').val(chk_num);
	}

//------------------------------------ 추가 담당자
	function add_charge_info()
	{
		var chk_num = $('#post_charge_info_num').val();
			chk_num = parseInt(chk_num) + 1;

		var str = '<br />\
			담당자명 : <input type="text" name="post_charge_name' + chk_num + '" id="post_charge_name' + chk_num + '" class="type_text" title="담당자명 입력하세요." size="15" value="" />,\
			연락처1 : <input type="text" name="post_charge_tel1_' + chk_num + '" id="post_charge_tel1_' + chk_num + '" class="type_text" title="담당자연락처1 입력하세요." size="15" value="" />,\
			연락처2 : <input type="text" name="post_charge_tel2_' + chk_num + '" id="post_charge_tel2_' + chk_num + '" class="type_text" title="담당자연락처2 입력하세요." size="15" value="" />,\
			메일주소 : <input type="text" name="post_charge_email' + chk_num + '" id="post_charge_email' + chk_num + '" class="type_text" title="이메일 입력하세요." size="30" value="" />';

		$('#charge_info_form').append(str);
		$('#post_charge_info_num').val(chk_num);
	}

//------------------------------------ 사용자 관련
	var cuser_list = '<?=$local_dir;?>/bizstory/comp_set/client_user.php';
	var cuser_form = '<?=$local_dir;?>/bizstory/comp_set/client_user_form.php';
	var cuser_ok   = '<?=$local_dir;?>/bizstory/comp_set/client_user_ok.php';

//------------------------------------ 사용자 등록
	function cuser_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_cuser").slideUp("slow");
			$("#new_cuser").html('');
			$('#cuser_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: cuser_form,
				data: $('#postform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$('#cuser_new_btn').css({'display':'none'});
					$("#new_cuser").slideUp("slow");
					$("#new_cuser").slideDown("slow");
					$("#new_cuser").html(msg);
				}
			});
		}
	}

//------------------------------------ 사용자목록
	function cuser_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: cuser_list,
			data: $('#cuserlistform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$('#cuser_list_data').html(msg);
			}
		});
	}

//------------------------------------ 사용자 열기/닫기
	var cuser_chk_val = 'close';
	function cuser_view()
	{
		if (cuser_chk_val == 'close')
		{
			cuser_chk_val = 'open';
			$('#cuser_list_data').html('');
			$("#cuser_gate").removeClass('btn_i_minus');
			$("#cuser_gate").addClass('btn_i_plus');
		}
		else
		{
			cuser_chk_val = 'close';
			cuser_list_data();
			$("#cuser_gate").removeClass('btn_i_plus');
			$("#cuser_gate").addClass('btn_i_minus');
		}
	}
	cuser_view();

//------------------------------------ 계약 관련
	var contract_list  = '<?=$local_dir;?>/bizstory/comp_set/client_contract.php';
	var contract_form  = '<?=$local_dir;?>/bizstory/comp_set/client_contract_form.php';
	var contract_ok    = '<?=$local_dir;?>/bizstory/comp_set/client_contract_ok.php';
	var contract_viewl = '<?=$local_dir;?>/bizstory/comp_set/client_contract_view.php';

//------------------------------------ 계약 등록
	function contract_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_contract").slideUp("slow");
			$("#new_contract").html('');
			$('#contract_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: contract_form,
				data: $('#postform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$('#contract_new_btn').css({'display':'none'});
					$("#new_contract").slideUp("slow");
					$("#new_contract").slideDown("slow");
					$("#new_contract").html(msg);
				}
			});
		}
	}

//------------------------------------ 계약목록
	function contract_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: contract_list,
			data: $('#contractlistform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$('#contract_list_data').html(msg);
			}
		});
	}

//------------------------------------ 계약 열기/닫기
	var contract_chk_val = 'close';
	function contract_view()
	{
		if (contract_chk_val == 'close')
		{
			contract_chk_val = 'open';
			$('#contract_list_data').html('');
			$("#contract_gate").removeClass('btn_i_minus');
			$("#contract_gate").addClass('btn_i_plus');
		}
		else
		{
			contract_chk_val = 'close';
			contract_list_data();
			$("#contract_gate").removeClass('btn_i_plus');
			$("#contract_gate").addClass('btn_i_minus');
		}
	}
	contract_view();

	part_information('<?=$data['part_idx'];?>', 'staff_info', 'post_mem_idx', '<?=$data['mem_idx'];?>', '');
	part_information('<?=$data['part_idx'];?>', 'client_group', 'post_ccg_idx', '<?=$data['ccg_idx'];?>', '');
	part_information('<?=$data['part_idx'];?>', 'report_class', 'post_report_type', '<?=$data['report_type'];?>', '');

//------------------------------------ 메모 관련
	var memo_list = '<?=$local_dir;?>/bizstory/work/client_view_memo_list.php';
	var memo_form = '<?=$local_dir;?>/bizstory/work/client_view_memo_form.php';
	var memo_ok   = '<?=$local_dir;?>/bizstory/work/client_view_memo_ok.php';
	var memo_chk_val = 'close';
	var file_chk_num = 0;
	var oEditors_memo = [];

	memo_view();
//]]>
</script>
<?
	}
?>