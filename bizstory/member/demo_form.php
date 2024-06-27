<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

// 서비스약관
	$page_where = " and pi.menu_code = 'agree'";
	$page_data = page_info_data('view', $page_where);
	$use_rule = $page_data['remark'];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="popup_joinform" name="popup_joinform" class="joinform" method="post" action="<?=$this_page;?>" onsubmit="return check_regist()">
			<input type="hidden" name="sub_type" id="post_sub_type" value="reg_post" />

			<fieldset>
				<legend class="blind">데모신청 폼</legend>

				<table class="tinytable write" summary="이메일, 핸드폰번호등 기본 가입양식을 입력합니다.">
					<caption>데모신청</caption>
					<colgroup>
						<col width="125px" />
						<col />
						<col width="95px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<td colspan="4">
								<div class="agreement content_2" title="이용약관">
									<?=$use_rule;?>
								</div>
								<div class="left">
									<label for="agree_check" class="fr">
										<input type="checkbox" name="agree_check" id="agree_check" title="약관에 동의해 주셔야만 데모신청을 하실 수 있습니다." />
										<span class="lh23">약관에 동의합니다.</span>
									</label>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_mem_email1">이메일</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[mem_email1]" id="post_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" maxlength="50" />
									@
									<input type="text" name="param[mem_email2]" id="post_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" maxlength="50" />
									<?=code_select($set_email_domain, 'post_mem_email3', 'post_mem_email3', '', '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_mem_email2\', \'post_mem_email3\');"');?>
									<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="N" />
									<strong class="btn_sml" onclick="double_email_chk();"><span>중복확인</span></strong>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_tel_num1">전화번호</label></th>
							<td>
								<div class="left">
									<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', '', '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
									-
									<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" />
									-
									<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" />
								</div>
							</td>
							<th><label for="post_hp_num1">핸드폰 번호</label></th>
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
						<tr>
							<th><label for="post_comp_name">상호명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[comp_name]" id="post_comp_name" class="type_text" title="상호명을 입력하세요." size="20" maxlength="50" />
								</div>
							</td>
							<th><label for="post_boss_name">대표자명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[boss_name]" id="post_boss_name" class="type_text" title="대표자명을 입력하세요." size="20" maxlength="20" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_comp_num1">사업자등록번호</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[comp_num1]" id="post_comp_num1" class="type_text" title="사업자등록번호를 입력하세요." size="4" maxlength="3" />
									-
									<input type="text" name="param[comp_num2]" id="post_comp_num2" class="type_text" title="사업자등록번호를 입력하세요." size="4" maxlength="2" />
									-
									<input type="text" name="param[comp_num3]" id="post_comp_num3" class="type_text" title="사업자등록번호를 입력하세요." size="4" maxlength="5" />
									<input type="hidden" name="post_comp_num_chk" id="post_comp_num_chk" value="N" />
									<strong class="btn_sml" onclick="double_comp_num_chk();"><span>중복확인</span></strong>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_upjong">업종</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[upjong]" id="post_upjong" class="type_text" title="업종을 입력하세요." size="24" maxlength="80" />
								</div>
							</td>
							<th><label for="post_uptae">업태</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[uptae]" id="post_uptae" class="type_text" title="업태를 입력하세요." size="24" maxlength="80" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_zip_code1">사업장주소</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[zip_code1]" id="post_zip_code1" class="type_text" title="우편번호 앞자리를 입력하세요." size="4" maxlength="3" />
									-
									<input type="text" name="param[zip_code2]" id="post_zip_code2" class="type_text" title="우편번호 뒷자리를 입력하세요." size="4" maxlength="3" />
									<strong class="btn_sml" onclick="execDaumPostcode('zip');"><span>우편번호찾기</span></strong>
								</div>
								<div class="left mt">
									<input type="text" name="param[address1]" id="post_address1" class="type_text" title="사업장주소를 입력하세요." size="29" maxlength="80" />
									<input type="text" name="param[address2]" id="post_address2" class="type_text" title="사업장상세주소를 입력하세요." size="32" maxlength="80" />
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<strong class="btn_big_violet" onclick="check_regist();"><span>신청</span></strong>
						<strong class="btn_big_gray" onclick="popupform_close();"><span>취소</span></strong>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<? include "../include/find_address_daum.php"; ?>

<script src="<?=$local_dir;?>/bizstory/js/jquery.mousewheel.min.js"></script>
<script src="<?=$local_dir;?>/bizstory/js/jquery.mCustomScrollbar.js"></script>
<link rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/jquery.mCustomScrollbar.css" type="text/css" media="screen" />
<script type="text/javascript">
	$(".content_2").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
</script>