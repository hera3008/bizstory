<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
	$mem_data = member_info_data('view', $mem_where);
?>
<div class="ajax_write">
	<div class="ajax_frame">
<?
	if ($mem_data['total_num'] == 0)
	{
?>
		<form id="popup_passform" name="popup_passform" class="passform" method="post" action="<?=$this_page;?>" onsubmit="return check_pass_find()">
			<input type="hidden" name="sub_type" value="find_pass" />

		<fieldset>
			<legend class="blind">비밀번호 찾기 폼</legend>
			<table class="tinytable write" summary="아이디, 사업자등록번호, 이름, 핸드폰번호을 입력하여 비밀번호 찾기를 합니다.">
			<caption>비밀번호 찾기</caption>
			<colgroup>
				<col width="130px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_mem_id">아이디</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[mem_id]" id="post_mem_id" class="type_text" title="아이디를 입력하세요." size="50" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_comp_num1">사업자등록번호</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[comp_num1]" id="post_comp_num1" class="type_text" title="사업자등록번호를 입력하세요." size="3" maxlength="3" />
							-
							<input type="text" name="param[comp_num2]" id="post_comp_num2" class="type_text" title="사업자등록번호를 입력하세요." size="2" maxlength="2" />
							-
							<input type="text" name="param[comp_num3]" id="post_comp_num3" class="type_text" title="사업자등록번호를 입력하세요." size="5" maxlength="5" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_name">이름</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[mem_name]" id="post_mem_name" class="type_text" title="이름을 입력하세요." size="20" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_hp_num1">핸드폰번호</label></th>
					<td>
						<div class="left">
							<?=code_select($set_cellular, 'param[hp_num1]', 'post_hp_num1', '', '핸드폰번호 앞자리를 선택하세요.', '없음', '', '');?>
							-
							<input type="text" name="param[hp_num2]" id="post_hp_num2" class="type_text" title="핸드폰번호를 입력하세요." size="4" maxlength="4" />
							-
							<input type="text" name="param[hp_num3]" id="post_hp_num3" class="type_text" title="핸드폰번호를 입력하세요." size="4" maxlength="4" />
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<strong class="btn_sml" onclick="check_pass_find();"><span>비밀번호찾기</span></strong>
					<strong class="btn_sml" onclick="popupform_close();"><span>취소하기</span></strong>
				</div>
			</div>
		</fieldset>
		</form>
<?
	}
	else
	{
?>
		<form id="popup_passform" name="popup_passform" class="passform" method="post" action="<?=$this_page;?>" onsubmit="return check_pass_form()">
			<input type="hidden" name="sub_type" value="pass_reset" />
			<input type="hidden" name="mem_idx"  value="<?=$mem_idx;?>" />

		<fieldset>
			<legend class="blind">비밀번호 재설정 폼</legend>
			<table class="tinytable write" summary="비밀번호 재설정을 합니다.">
			<caption>비밀번호 재설정</caption>
			<colgroup>
				<col width="130px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>아이디</th>
					<td>
						<div class="left"><?=$mem_data['mem_id'];?></div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_pwd">비밀번호</label></th>
					<td>
						<div class="left">
							<input type="password" name="param[mem_pwd]" id="post_mem_pwd" class="type_text" title="비밀번호를 입력하세요." size="20" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_pwd2">비밀번호확인</label></th>
					<td>
						<div class="left">
							<input type="password" name="param[mem_pwd2]" id="post_mem_pwd2" class="type_text" title="비밀번호확인을 입력하세요." size="20" />
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<strong class="btn_big_violet" onclick="check_pass_form();"><span>비밀번호재설정</span></strong>
					<strong class="btn_big_gray" onclick="popupform_close();"><span>취소하기</span></strong>
				</div>
			</div>
		</fieldset>
		</form>
<?
	}
?>
	</div>
</div>