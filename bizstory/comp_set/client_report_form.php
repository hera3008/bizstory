<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ci_idx    = $org_idx;

	$link_ok = $local_dir . "/bizstory/comp_set/client_report_ok.php"; // 저장

	$where = " and ri.comp_idx = '" . $code_comp . "' and ri.ci_idx = '" . $ci_idx . "'";
	$where = " and ";
	$list = receipt_info_data('list', $where, '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_frame">
			<span>수정할 경우만 비밀번호를 입력하세요.</span>
		</div>

		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post" onsubmit="return check_form('<?=$rr_idx;?>')">
			<input type="hidden" id="other_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
			<input type="hidden" id="other_ci_idx"   name="ci_idx"   value="<?=$ci_idx;?>" />

			<fieldset>
				<legend class="blind">거래처사용자 폼</legend>
				<table class="tinytable write" summary="거래처사용자를 등록/수정합니다.">
				<caption>거래처사용자</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_mem_id">아이디</label></th>
						<td>
							<div class="left">
				<?
					if ($rr_idx == "") {
				?>
								<input type="text" name="param[mem_id]" id="post_mem_id" value="<?=$data['mem_id'];?>" title="아이디를 입력하세요." class="type_text" />
								<input type="hidden" name="post_mem_id_chk" id="post_mem_id_chk" value="N" title="아이디 중복확인을 하세요." />
								<strong class="btn_sml" onclick="double_client_id_chk();"><span>중복확인</span></strong>
				<?
					} else {
				?>
								<?=$data['mem_id'];?>
				<?
					}
				?>
							</div>
						</td>
						<th><label for="post_mem_pwd">비밀번호</label></th>
						<td>
							<div class="left">
								<input type="password" name="param[mem_pwd]" id="post_mem_pwd" value="" title="비밀번호를 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_mem_name">이름</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[mem_name]" id="post_mem_name" value="<?=$data['mem_name'];?>" title="이름을 입력하세요." class="type_text" />
							</div>
						</td>
						<th><label for="post_tel_num">연락처</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[tel_num]" id="post_tel_num" value="<?=$data['tel_num'];?>" title="연락처를 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_mem_email1">이메일</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[mem_email1]" id="post_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['mem_email1'];?>" />
								@
								<input type="text" name="param[mem_email2]" id="post_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['mem_email2'];?>" />
								<?=code_select($set_email_domain, 'post_mem_email3', 'post_mem_email3', $data['mem_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_mem_email2\', \'post_mem_email3\');"');?>
							</div>
						</td>
					</tr>
					<tr>
						<th>로그인여부</th>
						<td colspan="3">
							<div class="left">
								<?=code_radio($set_use, "param[login_yn]", "post_login_yn", $data["login_yn"]);?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($rr_idx == "") {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="user_list('<?=$ci_idx;?>')" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="user_list('<?=$ci_idx;?>')" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="rr_idx"   value="<?=$rr_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>

			<div class="section">
				<div class="fr">
					<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
				</div>
			</div>
		</form>
	</div>
</div>