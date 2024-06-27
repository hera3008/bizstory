<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
?>
<div class="ajax_write">
	<div class="ajax_frame">
		<form id="popup_idform" name="popup_idform" class="idform" method="post" action="<?=$this_page;?>" onsubmit="return check_id_find()">
			<input type="hidden" name="sub_type" value="find_id" />

		<fieldset>
			<legend class="blind">아이디 찾기 폼</legend>
			<table class="tinytable write" summary="사업자등록번호, 이름, 핸드폰번호를 입력하여 아이디 찾기를 합니다.">
			<caption>아이디 찾기</caption>
			<colgroup>
				<col width="130px" />
				<col />
			</colgroup>
			<tbody>
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
					<strong class="btn_big_violet" onclick="check_id_find();"><span>아이디찾기</span></strong>
					<strong class="btn_big_gray" onclick="popupform_close();"><span>취소</span></strong>
				</div>
			</div>
		</fieldset>
		</form>
	</div>
</div>