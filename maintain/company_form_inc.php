<?
	$comp_num = $data['comp_num'];
	$comp_num_arr = explode('-', $comp_num);
	$data['comp_num1'] = $comp_num_arr[0];
	$data['comp_num2'] = $comp_num_arr[1];
	$data['comp_num3'] = $comp_num_arr[2];

	$distinct_num = $data['distinct_num'];
	$distinct_num_arr = explode('-', $distinct_num);
	$data['distinct_num1'] = $distinct_num_arr[0];
	$data['distinct_num2'] = $distinct_num_arr[1];

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

	$comp_email = $data['comp_email'];
	$comp_email_arr = explode('@', $comp_email);
	$data['comp_email1'] = $comp_email_arr[0];
	$data['comp_email2'] = $comp_email_arr[1];

	$hp_num = $data['hp_num'];
	$hp_num_arr = explode('-', $hp_num);
	$data['hp_num1'] = $hp_num_arr[0];
	$data['hp_num2'] = $hp_num_arr[1];
	$data['hp_num3'] = $hp_num_arr[2];
?>
						<tr>
							<th><label for="post_comp_name">상호명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[comp_name]" id="post_comp_name" class="type_text" title="상호명을 입력하세요." size="30" value="<?=$data['comp_name'];?>" />
								</div>
							</td>
							<th><label for="post_comp_num1">사업자등록번호</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[comp_num1]" id="post_comp_num1" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="3" value="<?=$data['comp_num1'];?>" />
									-
									<input type="text" name="param[comp_num2]" id="post_comp_num2" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="2" value="<?=$data['comp_num2'];?>" />
									-
									<input type="text" name="param[comp_num3]" id="post_comp_num3" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="5" value="<?=$data['comp_num3'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_boss_name">대표자명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[boss_name]" id="post_boss_name" class="type_text" title="대표자명을 입력하세요." size="20" value="<?=$data['boss_name'];?>" />
								</div>
							</td>
							<th><label for="post_distinct_num1">고유번호</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[distinct_num1]" id="post_distinct_num1" class="type_text" title="고유번호를 모두 입력하세요." size="6" maxlength="6" value="<?=$data['distinct_num1'];?>" />
									-
									<input type="text" name="param[distinct_num2]" id="post_distinct_num2" class="type_text" title="고유번호를 모두 입력하세요." size="7" maxlength="7" value="<?=$data['distinct_num2'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_upjong">업종</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[upjong]" id="post_upjong" class="type_text" title="업종을 입력하세요." size="24" value="<?=$data['upjong'];?>" />
								</div>
							</td>
							<th><label for="post_uptae">업태</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[uptae]" id="post_uptae" class="type_text" title="업태를 입력하세요." size="24" value="<?=$data['uptae'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_zip_code1">사업장주소</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[zip_code1]" id="post_zip_code1" class="type_text" title="사업장주소 우편번호 앞자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code1'];?>" />
									-
									<input type="text" name="param[zip_code2]" id="post_zip_code2" class="type_text" title="사업장주소 우편번호 뒷자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code2'];?>" />
									<a href="javascript:void(0);" onclick="execDaumPostcode('zip');" class="btn_sml_violet"><span>우편번호찾기</span></a>
								</div>
								<div class="left mt">
									<input type="text" name="param[address1]" id="post_address1" class="type_text" title="사업장 주소 입력하세요." size="40" value="<?=$data['address1'];?>" />
									<input type="text" name="param[address2]" id="post_address2" class="type_text" title="사업장 상세주소 입력하세요." size="35" value="<?=$data['address2'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_tel_num1">전화번호</label></th>
							<td>
								<div class="left">
									<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
									-
									<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
									-
									<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
								</div>
							</td>
							<th><label for="post_fax_num1">팩스번호</label></th>
							<td>
								<div class="left">
									<?=code_select($set_telephone, 'param[fax_num1]', 'post_fax_num1', $data['fax_num1'], '팩스번호 앞자리를 선택하세요.', '없음', '', '');?>
									-
									<input type="text" name="param[fax_num2]" id="post_fax_num2" class="type_text" title="팩스번호를 모두 입력하세요." size="4" value="<?=$data['fax_num2'];?>" />
									-
									<input type="text" name="param[fax_num3]" id="post_fax_num3" class="type_text" title="팩스번호를 모두 입력하세요." size="4" value="<?=$data['fax_num3'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_comp_email1">이메일</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[comp_email1]" id="post_comp_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['comp_email1'];?>" />
									@
									<input type="text" name="param[comp_email2]" id="post_comp_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['comp_email2'];?>" />
									<?=code_select($set_email_domain, 'post_comp_email3', 'post_comp_email3', $data['comp_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_comp_email2\', \'post_comp_email3\');"');?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_charge_name">담당자</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[charge_name]" id="post_charge_name" class="type_text" title="담당자를 입력하세요." size="20" value="<?=$data['charge_name'];?>" />
								</div>
							</td>
							<th><label for="post_hp_num1">핸드폰 번호</label></th>
							<td>
								<div class="left">
									<?=code_select($set_cellular, 'param[hp_num1]', 'post_hp_num1', $data['hp_num1'], '핸드폰 번호 앞자리를 선택하세요.', '없음', '', '');?>
									-
									<input type="text" name="param[hp_num2]" id="post_hp_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['hp_num2'];?>" />
									-
									<input type="text" name="param[hp_num3]" id="post_hp_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['hp_num3'];?>" />
								</div>
							</td>
						</tr>
